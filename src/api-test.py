import requests
import json
import os
# "https://graph.facebook.com/v2.8/263341274109444/?access_token=<access token sanitized>"


TOKEN = '''EAACEdEose0cBAFyLZAj9mlL0RF9l77AgPyVfBP70ZBpl6kLW5NNxzptLm3SH8pRn43ZAgE52cZCVfTfnOZC5y7RWQl3kGz1Ye3vf2wAtQoHunZCtD48AinRMEXzVakfarenyHZBH0TqxJBjLgMKE3qItecIl4EP6VesSvbjkIu7eGTPZALk7xZAX0d4erdl1Dwe8ZD'''

CONTENT = '/me/'
URL = URL + CONTENT + '?access_token={}'.format(TOKEN)

r = requests.get(URL)
print r.json()


class Facebook(object):

    def __init__(self, token=None, config_file='config.json'):
        self._url = None
        self._version = None
        self._token = token

        self.read_config(config_file)

    def read_config(self, filename):
        with open(filename, 'r') as file:
            _config = json.load(file)
            self._url = _config['url'] + _config['version'] + '/'
            self._version = _config['version']

    def set_token(self, token):
        if self._token is not None:
            print 'Overwriting token: ' + self._token
        self._token = token
