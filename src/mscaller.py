import json
import os
import sys
import msapi
import hashlib
import requests

URL_ENDS = ['sentiment', 'keyPhrases']
URL = 'https://westus.api.cognitive.microsoft.com/text/analytics/v2.0/'


def get_header():
    return {'Ocp-Apim-Subscription-Key': msapi.API_KEY,
            'Content-Type': 'application/json',
            'Accept': 'application/json'}


def get_comments(video_id):
    file = hashlib.sha256(str(video_id)).hexdigest()
    path = os.path.dirname(__file__) + './data/'
    with open(path+file, 'r') as inputfile:
        data = json.load(inputfile)

    comments = []
    for comment in data['comments']:
        comments.append({'timestamp': str(comment['created_time']),
                         'message': comment['message']})
    return comments


def get_data(video_id):
    data = get_comments(video_id)
    payload = {'documents': []}
    for comment in data:
        payload['documents'].append({'language': 'en',
                                     'id': comment['timestamp'],
                                     'text': comment['message']})

    return json.dumps(payload)


def get_sentiment(video_id):
    with requests.session() as req_session:
        response = req_session.post(URL+URL_ENDS[0],
                                    headers=get_header(),
                                    data=get_data(video_id))

    return json.loads(response.content)
    # response = json.loads(response.content)
    # for document in response['documents']:
    #     print document


def get_keyphrase(video_id):
    with requests.session() as req_session:
        response = req_session.post(URL+URL_ENDS[1],
                                    headers=get_header(),
                                    data=get_data(video_id))

    return json.loads(response.content)
    # response = json.loads(response.content)
    # for document in response['documents']:
    #     print document


if __name__ == '__main__':
    documents_s = get_sentiment(263804970729741)
    documents_k = get_keyphrase(263804970729741)

    print documents_k

    final = []

    for i, sentiment in enumerate(documents_s['documents']):
        final.append({'id': sentiment['id'],
                      'sentiment': sentiment['score'],
                      'keyPhrases': documents_k['documents'][i]['keyPhrases']})

    for i in final:
        print i

