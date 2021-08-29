from flask import Flask
from pytube import YouTube
app = Flask(__name__)

@app.route('/stream/<id>')
def hello_name(id):
    yt = YouTube('http://youtube.com/watch?v='+id)
    stream = yt.streams.filter(progressive=True, file_extension='mp4').order_by('resolution').desc().first()
    return stream.url

if __name__ == '__main__':
    app.run('0.0.0.0',debug = True)

