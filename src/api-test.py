import requests
import json
import sys
import os
# "https://graph.facebook.com/v2.8/263341274109444/?access_token=<token>"


TOKEN = '''
EAACEdEose0cBAFyLZAj9mlL0RF9l77AgPyVfBP70ZBpl6kLW5NNxzptLm3SH8pRn43ZAgE52cZCVfTfnOZC5y7RWQl3kGz1Ye3vf2wAtQoHunZCtD48AinRMEXzVakfarenyHZBH0TqxJBjLgMKE3qItecIl4EP6VesSvbjkIu7eGTPZALk7xZAX0d4erdl1Dwe8ZD
'''


class Facebook(object):

    def __init__(self, token=None, config_file='config.json'):
        self._url = None
        self._version = None
        self._token = token
        self._token_request = '?access_token={}'.format(token)

        self.read_config(config_file)

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
        self._token_request = '?access_token={}'.format(token)

    def get(self, content='me'):
        # Return the answer from Facebook Graph API
        return requests.get(self._url + content + self._token_request).json()

if __name__ == '__main__':

    if len(sys.argv) == 2:
        # Take only a token
        facebook = Facebook(sys.argv[1])
    elif len(sys.argv) == 3:
        # Take a token and a custom json config file
        facebook = Facebook(sys.argv[1], sys.argv[2])
    elif len(sys.argv) == 1:
        print 'Missing arguments!'

    print facebook
    print facebook.get('me')
