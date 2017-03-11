import requests
import json
import time
import datetime
import sys
import os

# "https://graph.facebook.com/v2.8/263341274109444/?access_token=<token>"
#             url            version     any_id       token

# CONSTANTS
CONFIG_FILE = 'config.json'
LISTEN_REQUEST = '?fields=reactions{type},live_views'
COMMEN_REQUEST = '?fields=comments'
TOKEN_FROMAT = '&access_token={}'
REFRESH_RATE = 5


class Facebook(object):

    # token should be given by sys.argv[1]
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

    # Read the given config file
    def read_config(self, filename):
        # Read a given config file (json)
        with open(filename, 'r') as file:
            _config = json.load(file)
            self._url = '{}/{}/'.format(_config['url'], _config['version'])
            self._version = _config['version']

    # Set the token if needed
    def set_token(self, token):
        # Set/Overwrite the token
        self._token = token
        self._token_request = TOKEN_FROMAT.format(token)

    # Returns a json response from the Facebook Graph API
    def get(self, id='me', request=''):
        # Return the response from Facebook Graph API
        _response = requests.get(self._url+id+request+self._token_request)
        _response = _response.json()
        if 'error' not in _response.keys():
            return _response
        else:
            return "ERROR::" + str(_response['error'])

    # Returns True if video status is LIVE, false otherwise
    def get_video_status(self, video_id):
        _response = self.get(video_id, '?fields=status')
        if 'status' in _response:
            return _response['status'] == 'LIVE'

    # Returns the latest LIVE video id from the token argv
    def get_video_id(self):
        _response = self.get('me/live_videos', '?fields=id&limit=1')
        return str(_response['data'][0]['id'])

    # Returns a dictionary containing all block related data
    def make_block(self, blockchain, reactions, views):
        return {'block_id': len(blockchain),     # int
                'timestamp': int(time.time()),   # int
                'reactions': reactions,          # array dicts
                'view_count': views}             # int

    # Request *data from a live video until it goes offline
    def listen(self, video_id, logfile='output.payload'):
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
                _block = self.make_block(_blockchain, _reactions, _views)
                # Append the block to the blockchain
                _blockchain.append(_block)
            else:
                # In case of empty reactions!
                _views = _response['live_views']
                _blockchain.append(self.make_block(_blockchain, [], _views))

            # Wait before making a new request
            time.sleep(REFRESH_RATE)

        # At the end of the stream, obtian the comments and description
        _desc = ''
        if 'description' in self.get(video_id, '?fields=description').keys():
            _desc = self.get(video_id, '?fields=description')['description']

        _comments_request = self.get(video_id, COMMEN_REQUEST)
        _comments = []
        if 'comments' in _comments_request:
            _comments = self.get(video_id, COMMEN_REQUEST)['comments']['data']

        # Store everything as rawdata of blockchain and comments and metadata
        _rawdata = {'video_id': video_id,
                    'description': _desc,
                    'blockchain': _blockchain,
                    'comments': _comments}

        # Log the data
        if logfile is not None:
            with open(logfile, 'w') as file:
                json.dump(_rawdata, file, indent=4)

        return str(_rawdata)


# Utility method to convert Graph API timestamps to UNIX timestamps
def date_to_unix(timedate_string):
    # Magic
    timedate_string = timedate_string[:-5]
    return int(time.mktime(
        datetime.datetime.strptime(
            timedate_string, "%Y-%m-%dT%H:%M:%S").timetuple()))


if __name__ == '__main__':

    # argv[1] - token
    # argv[2] - video id

    # Take only a token
    facebook = Facebook(sys.argv[1])

    # Take a video id and listen for changes
    print (facebook.listen(facebook.get_video_id()))
