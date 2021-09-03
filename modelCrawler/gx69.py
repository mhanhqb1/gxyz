import requests
import time
from datetime import datetime
from database import MySQLRepository
from bs4 import BeautifulSoup
# from google_trans_new import google_translator

mysql = MySQLRepository('sgbc')

def get_detail():
	payload={}
	headers = {
        'Accept': '*/*',
        #'Accept-Encoding': 'gzip, deflate, br',
        'User-Agent': 'PostmanRuntime/7.26.8',
        'Accept-Language':'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding':'gzip, deflate'
    }
	today = datetime.today().strftime('%Y-%m-%d')
	sql = ("""
	    SELECT
		    *
		FROM
		    posts
		WHERE crawl_at is NULL and source_type = 'xiuren' limit 1;
	""")
	data = mysql.all(sql)
	for v in data:
		print(v['id'])
		imgs = [(
			v['image'],
			v['id'],
			'1',
			'1',
			v['image']
		)]
		response = requests.request("GET", v['source_url'], headers=headers, data=payload)
		response.encoding = 'zh-cn'
		soup = BeautifulSoup(response.text, 'html.parser')
		tags = soup.select('div#main #post #title .date > a')
		k = 0
		postTags = []
		for t in tags:
		    k += 1
		    if (k == 1):
		        continue
		    postTags.append(t.text)
		rows = soup.select('div#main #post .post .photoThum')
		for r in rows:
			url = r.select('a')
			imgs.append((
				url[0].attrs['href'],
				v['id'],
				'0',
				'1',
				url[0].attrs['href']
			))
		# sql = ("""
		# 	INSERT INTO
		# 		images(
		# 			url,
		# 			model_id,
		# 			is_hot,
		# 			status,
		# 			source_id
		# 		)
		# 	VALUES (
		# 		%s,
		# 		%s,
		# 		%s,
		# 		%s,
		# 		%s
		# 	)
		# 	ON
		# 		DUPLICATE KEY
		# 	UPDATE
		# 		model_id = VALUES(model_id),
		# 		is_hot = VALUES(is_hot),
		# 		status = VALUES(status)
		# """)
		# try:
		# 	mysql.executemany(sql, imgs)
		# except Exception as e:
		# 	print(e)

		sql = ("""
			UPDATE
				posts
			SET
				crawl_at = CURDATE(),
                tags = %(post_tags)s
			WHERE
				id = %(model_id)s
		""")
		try:
			print(postTags)
			mysql.execute(sql, {
                'post_tags': ','.join([str(elem) for elem in postTags]),
				'model_id': v['id']
			})
		except Exception as e:
			print(e)

def main():
	# Init
	totalPages = 377
	count = 1
	payload={}
	headers = {
        'Accept': '*/*',
        #'Accept-Encoding': 'gzip, deflate, br',
        'User-Agent': 'PostmanRuntime/7.26.8',
        'Accept-Language':'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding':'gzip, deflate'
    }

	for i in range(totalPages):
		i += 1
		url = "http://www.xiuren.org/page-"+str(i)+".html"
		xiurenCover = "http://www.xiuren.org/cover.php?src="
		response = requests.request("GET", url, headers=headers, data=payload)
		response.encoding = 'zh-cn'
		soup = BeautifulSoup(response.text, 'html.parser')
		rows = soup.select('div#main .loop .content')
		if (rows != []):
			data = []
			for r in rows:
				url = r.select('a')
				cover = r.select('img')
				name = url[0].attrs['title']
				print(count, name)
				count += 1
				data.append((
                    name,
                    cover[0].attrs['src'].replace(xiurenCover, ""),
                    -2,
                    'xiuren',
					url[0].attrs['href'],
					url[0].attrs['href']
				))
			sql = ("""
				INSERT INTO
					posts(
						title,
						image,
						status,
                        source_type,
                        source_url,
                        source_id
					)
				VALUES (
					%s,
					%s,
					%s,
                    %s,
                    %s,
                    %s
				)
				ON
					DUPLICATE KEY
				UPDATE
					title = VALUES(title),
					image = VALUES(image),
                    source_url = VALUES(source_url)
			""")
			try:
				mysql.executemany(sql, data)
			except Exception as e:
				print(e)

def get18Video(url = False):
    baseUrl = "https://v.imgccc.com"
    payload={}
    headers = {
        'Accept': '*/*',
        #'Accept-Encoding': 'gzip, deflate, br',
        'User-Agent': 'PostmanRuntime/7.26.8'
    }
    if (url == False):
        url = baseUrl
    else:
        url = baseUrl + url
        print(url)
    response = requests.request("GET", url, headers=headers, data=payload)
    soup = BeautifulSoup(response.text, 'html.parser')
    rows = soup.select('td.fb-n a')
    translator = google_translator()
    count = 0
    for r in rows:
        newUrl = r.attrs['href']
        if ('.mp4' in newUrl):
            try:
                count += 1
                title = r.text.replace('.mp4', '')#translator.translate(r.text.replace('.mp4', ''), lang_tgt='en')
                print(count, title)
                sourceId = r.text
                sourceUrl = newUrl
                streamUrl = newUrl
                data = [(
                    title,
                    -2,
                    'imgccc',
                    sourceUrl,
                    sourceId,
                    baseUrl + streamUrl
                )]
                sql = ("""
                    INSERT INTO
                        posts(
                            title,
                            status,
                            source_type,
                            source_url,
                            source_id,
                            stream_url
                        )
                    VALUES (
                        %s,
                        %s,
                        %s,
                        %s,
                        %s,
                        %s
                    )
                    ON
                        DUPLICATE KEY
                    UPDATE
                        title = VALUES(title),
                        status = VALUES(status),
                        source_url = VALUES(source_url),
                        stream_url = VALUES(stream_url)
                """)
                try:
                    mysql.executemany(sql, data)
                except Exception as e:
                    print(e)
            except Exception as e:
                print(e)
        elif ('..' not in newUrl):
            get18Video(newUrl)
    return True

def test():
    import m3u8_to_mp4
    a = m3u8_to_mp4.download('https://ccn.killcovid2021.com//m3u8/517116/517116.m3u8?st=uAYkkh_WiG-wZI8ZWtoUUA&e=1630640189')
    print(a)

if __name__ == "__main__":
	# main()
	get_detail()
    # test()
