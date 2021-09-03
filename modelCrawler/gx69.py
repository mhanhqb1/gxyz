import requests
import time
from datetime import datetime
from database import MySQLRepository
from bs4 import BeautifulSoup
# from google_trans_new import google_translator

mysql = MySQLRepository('gxyz')

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
		WHERE crawl_at is NULL and source_type = 'xiuren';
	""")
	data = mysql.all(sql)
	for v in data:
		print(v['id'])
		imgs = []
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
				'1'
			))
		sql = ("""
			INSERT INTO
				post_images(
					image,
					post_id,
					status
				)
			VALUES (
				%s,
				%s,
				%s
			)
			ON
				DUPLICATE KEY
			UPDATE
				post_id = VALUES(post_id),
				image = VALUES(image)
		""")
		try:
			mysql.executemany(sql, imgs)
		except Exception as e:
			print(e)

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

def xsnvshen():
	totalPages = 2
	totalCount = 0
	baseUrl = 'https://www.xsnvshen.com'
	for i in range(totalPages):
		i += 1
		# url = "http://www.xiuren.org/page-"+str(i)+".html"
		url = "https://www.xsnvshen.com/album/?p="+str(i)
		sourceType = 'xsnvshen'
		payload={}
		headers = {
			'Accept': '*/*',
			#'Accept-Encoding': 'gzip, deflate, br',
			'User-Agent': 'PostmanRuntime/7.26.8',
			'Accept-Language':'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
			'Accept-Encoding':'gzip, deflate'
		}
		response = requests.request("GET", url, headers=headers, data=payload, verify=False)
		soup = BeautifulSoup(response.text, 'html.parser')
		rows = soup.select('ul.picpos_6_1 li a')
		count = 0
		data = []
		for r in rows:
			sourceUrl = baseUrl + r.attrs['href']
			title = r.attrs['title']
			imgs = r.select('img')
			if (imgs == []):
				continue
			count += 1
			totalCount += 1
			image = imgs[0].attrs['src']
			print(i, totalCount, count, sourceUrl, image)
			data.append((
				title,
				image,
				-2,
				sourceType,
				sourceUrl,
				r.attrs['href']
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

def xsnvshen_detail():
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
		WHERE crawl_at is NULL and source_type = 'xsnvshen' LIMIT 1;
	""")
	data = mysql.all(sql)
	for v in data:
		print(v['id'])
		imgs = []
		response = requests.request("GET", v['source_url'], headers=headers, data=payload, verify=False)
		soup = BeautifulSoup(response.text, 'html.parser')
		imgs = soup.select('.swl-item swi-hd img')
		for img in imgs:
			image = img.attrs['data=original']
		print(response.text)
		return True
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
				'1'
			))
		sql = ("""
			INSERT INTO
				post_images(
					image,
					post_id,
					status
				)
			VALUES (
				%s,
				%s,
				%s
			)
			ON
				DUPLICATE KEY
			UPDATE
				post_id = VALUES(post_id),
				image = VALUES(image)
		""")
		try:
			mysql.executemany(sql, imgs)
		except Exception as e:
			print(e)

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

if __name__ == "__main__":
	main()
	get_detail()
    # test()
    # xsnvshen()
	# xsnvshen_detail()
