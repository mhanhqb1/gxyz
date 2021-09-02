import requests
import ffmpeg
import sys
from database import MySQLRepository

mysql = MySQLRepository('gxyz')

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
        return True
    except ffmpeg.Error as e:
        print(e.stderr.decode(), file=sys.stderr)
        return False
        sys.exit(1)

sql = ("""
    SELECT
        *
    FROM
        posts
    WHERE source_type = 'imgccc' and status = -3 limit 100;
""")
data = mysql.all(sql)
for v in data:
    in_filename = v['stream_url']
    out_filename = v['title'] + '-' + str(v['id']) + '.jpg'
    print(out_filename)
    a = generate_thumbnail(in_filename, out_filename)
    print(a)
    if (a != False):
        sql = ("""
            UPDATE
                posts
            SET
                crawl_at = CURDATE(),
                status = 1,
                image = %(image)s
            WHERE
                id = %(post_id)s
        """)
        try:
            mysql.execute(sql, {
                'image': '/imgs/posts/video/' + out_filename,
                'post_id': v['id']
            })
        except Exception as e:
            print(e)
