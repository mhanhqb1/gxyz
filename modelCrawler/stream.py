from flask import Flask
from pytube import YouTube
app = Flask(__name__)

@app.route('/stream/<id>')
def hello_name(id):
    stream = YouTube('https://youtu.be/'+id).streams.first()
    print(stream.url)
    return stream.url

if __name__ == '__main__':
    app.run('0.0.0.0',debug = True)

