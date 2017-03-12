import os
import sys
import json
import hashlib
import requests

from vaderSentiment import vaderSentiment

URL_ENDS = ['sentiment', 'keyPhrases']
URL = 'https://westus.api.cognitive.microsoft.com/text/analytics/v2.0/'


def get_header():
    return {'Ocp-Apim-Subscription-Key': 'd6126b84a6754464b7574af30f1ea959',
            'Content-Type': 'application/json',
            'Accept': 'application/json'}


def get_comments(video_id):
    file = hashlib.sha256(str(video_id).encode('utf-8')).hexdigest()
    path = os.path.abspath(os.path.dirname(__file__)) + '/data/'
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
    return json.loads(response.content.decode('utf-8'))
    # response = json.loads(response.content)
    # for document in response['documents']:
    #     print document


def get_keyphrase(video_id):
    with requests.session() as req_session:
        response = req_session.post(URL+URL_ENDS[1],
                                    headers=get_header(),
                                    data=get_data(video_id))

    return json.loads(response.content.decode('utf-8'))
    # response = json.loads(response.content)
    # for document in response['documents']:
    #     print document


def get_vader(video_id):
    comments = get_comments(video_id)
    analyzer = vaderSentiment.SentimentIntensityAnalyzer()
    vaders = []

    for comment in comments:
        vs = comment['message'], analyzer.polarity_scores(comment['message'])
        vaders.append(vs)

    return vaders


if __name__ == '__main__':

    # 263804970729741
    documents_s = get_sentiment(sys.argv[1])
    documents_k = get_keyphrase(sys.argv[1])
    documents_v = get_vader(sys.argv[1])

    final = []

    if 'documents' not in documents_s.keys():
        print(json.dumps(final))
        sys.exit()

    for i, sentiment in enumerate(documents_s['documents']):
        final.append({'id': sentiment['id'],
                      'sentiment': sentiment['score'],
                      'keyPhrases': documents_k['documents'][i]['keyPhrases'],
                      'vader': documents_v[i][1]})

    print(json.dumps(final))
