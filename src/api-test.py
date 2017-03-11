import requests
import json
import time
import sys
import os

# "https://graph.facebook.com/v2.8/263341274109444/?access_token=<token>"
#             url            version     any_id       token

CONFIG_FILE = 'config.json'
LISTEN_REQUEST = '?fields=reactions{type},live_views'
TOKEN_FROMAT = '&access_token={}'
REFRESH_RATE = 5


class Facebook(object):

    def __init__(self, token=None):
        self._url = None
        self._version = None
        self._token = token
        self._token_request = TOKEN_FROMAT.format(token)

        self.read_config(CONFIG_FILE)

    def __str__(self):
        return 'Facebook(url:{}, version:{}, token:{})'.format(self._url,
                                                               self._version,
                                                               self._token)

    def read_config(self, filename):
        # Read a given config file (json)
        with open(filename, 'r') as file:
            _config = json.load(file)
            self._url = '{}/{}/'.format(_config['url'], _config['version'])
            self._version = _config['version']

    def set_token(self, token):
        # Set/Overwrite the token
        self._token = token
        self._token_request = TOKEN_FROMAT.format(token)

    def get(self, id='me', request=''):
        # Return the response from Facebook Graph API
        _response = requests.get(self._url+id+request+self._token_request)
        _response = _response.json()
        if 'error' not in _response.keys():
            return _response
        else:
            return "ERROR::" + str(_response['error'])

    def get_video_status(self, video_id):
        _response = self.get(video_id, '?fields=status')
        if 'status' in _response:
            return _response['status'] == 'LIVE'

    def listen(self, video_id):
        _blockchain = []
        _block = None
        _count = 0

        while self.get_video_status(video_id):

            # Obtain response with {reactions: data[], views: int}
            _response = self.get(video_id, LISTEN_REQUEST)

            if 'reactions' in _response.keys():

                # Get all reactions from live video
                _reactions = _response['reactions']['data']
                # Ignore reactions already encountered
                _reactions = _reactions[_count:]
                # Increase reactions encountered count
                _count += len(_reactions)
                # Get the number of live views at the time
                _views = _response['live_views']
                # Create a block of data
                _block = {'block_id': len(_blockchain),     # int
                          'timestamp': int(time.time()),    # int
                          'reactions': _reactions,          # array dicts
                          'view_count': _views}             # int
                # Append the block to the blockchain
                _blockchain.append(_block)

                # print "BLOCK", _block
                # print "BLOCKS", _blocks

            time.sleep(REFRESH_RATE)

        print _blockchain

if __name__ == '__main__':

    if len(sys.argv) == 3:
        # Take only a token
        facebook = Facebook(sys.argv[1])

        # Take a video id and listen for changes
        facebook.listen(sys.argv[2])
