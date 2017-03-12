from clarifai.rest import ClarifaiApp
import json
import hashlib
import os
import subprocess


SERVER_URL = 'https://www.alexdradu.com/d/live-insights/uploads/'


def get_objects(url):

    app = ClarifaiApp("zcUWsOjpJC_dJqtKW_6mE-iGG9dYja-W4yF_YMH2",
                      "wEwbDG6rdGH1ibbFP8PRbtwvcAI6XxYv4WEWaiDl")
    model = app.models.get("general-v1.3")

    stuff = model.predict_by_url(url=url)

    return stuff['outputs'][0]['data']['concepts']
    # for thing in stuff['outputs'][0]['data']['concepts']:
    #     print type(thing)


# ffmpeg -i mov_bbb.mp4 -r 0.2 -f image2 image-%07d.png
def ffmpeg(user, video):
    upload = '../dashboard/public/uploads/' + video[:-4] + '/'
    videos = '../videos/' + user
    video = videos + '/' + video

    if not os.path.exists(upload):
        os.makedirs(upload)

    subprocess.call(['/usr/local/bin/ffmpeg', '-i', video, '-r',
                     '0.2', '-f', 'image2', upload + 'image-%07d.png'])


def get_video_stuff(user, video):
    ffmpeg(user, video)
    initvid = video

    upload = '../dashboard/public/uploads/' + video[:-4] + '/'
    videos = '../videos/' + user
    video = videos + '/' + video

    _everything = []
    for img in os.listdir(upload):
        _everything.append(get_objects(SERVER_URL+initvid[:-4]+'/'+img))

    with open(upload+'/MLD', 'w') as file:
        json.dump(_everything, file)

if __name__ == '__main__':
    os.chdir(os.path.abspath(os.path.dirname(__file__)))

    get_video_stuff("0", 'mov_bbb.mp4')
