# from flask import Flask
from pytube import YouTube

stream = YouTube('https://youtu.be/9bZkp7q19f0').streams.first()
print(stream)

