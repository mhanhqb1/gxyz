import requests
import ffmpeg
import sys

in_filename = "https://v.imgccc.com/2019/201906/20190622_%E5%90%84%E7%A7%8D%E8%B0%83%E6%95%99%E9%9C%B2%E8%84%B8%E6%B7%AB%E8%AF%AD%E6%AF%8D%E7%8B%97.mp4"
out_filename = "THUMBNAIL.jpg"

def generate_thumbnail(in_filename, out_filename):
    probe = ffmpeg.probe(in_filename)
    duration = float(probe['streams'][0]['duration'])
    if (duration > 14):
        time = 7#float(probe['streams'][0]['duration']) // 2
    else:
        time = duration // 2
    width = probe['streams'][0]['width']
    try:
        (
            ffmpeg
            .input(in_filename, ss=time)
            .filter('scale', width, -1)
            .output(out_filename, vframes=1)
            .overwrite_output()
            .run(capture_stdout=True, capture_stderr=True)
        )
    except ffmpeg.Error as e:
        print(e.stderr.decode(), file=sys.stderr)
        sys.exit(1)

generate_thumbnail(in_filename, out_filename)
