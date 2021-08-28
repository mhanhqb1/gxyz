import requests
import time
from datetime import datetime
from database import MySQLRepository
from bs4 import BeautifulSoup
from googletrans import Translator, constants

#mysql = MySQLRepository('sbgc')

def get_detail():
	today = datetime.today().strftime('%Y-%m-%d')
	sql = ("""
	    SELECT
		    *
		FROM
		    models
		WHERE crawl_at is NULL;
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
		response = requests.request("GET", v['source_url'])
		response.encoding = 'gbk'
		soup = BeautifulSoup(response.text, 'html.parser')
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
		sql = ("""
			INSERT INTO
				images(
					url,
					model_id,
					is_hot,
					status,
					source_id
				)
			VALUES (
				%s,
				%s,
				%s,
				%s,
				%s
			)
			ON
				DUPLICATE KEY
			UPDATE
				model_id = VALUES(model_id),
				is_hot = VALUES(is_hot),
				status = VALUES(status)
		""")
		try:
			mysql.executemany(sql, imgs)
		except Exception as e:
			print(e)

		sql = ("""
			UPDATE
				models
			SET
				crawl_at = CURDATE()
			WHERE
				id = %(model_id)s
		""")
		try:
			mysql.execute(sql, {
				'model_id': v['id']
			})
		except Exception as e:
			print(e)

def main():
	# Init
	totalPages = 377
	count = 1

	for i in range(totalPages):
		i += 1
		url = "http://www.xiuren.org/page-"+str(i)+".html"
		xiurenCover = "http://www.xiuren.org/cover.php?src="
		response = requests.request("GET", url)
		response.encoding = 'gbk'
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
					url[0].attrs['href'],
					name,
					cover[0].attrs['src'].replace(xiurenCover, "")
				))
			sql = ("""
				INSERT INTO
					models(
						source_url,
						name,
						image
					)
				VALUES (
					%s,
					%s,
					%s
				)
				ON
					DUPLICATE KEY
				UPDATE
					name = VALUES(name),
					image = VALUES(image)
			""")
			try:
				mysql.executemany(sql, data)
			except Exception as e:
				print(e)

def convertTitle():
    # Init
    totalPages = 2#377
    count = 1
    url = "http://www.xiuren.org/page-1.html"
    payload={}
    headers = {
        'Accept': '*/*',
        #'Accept-Encoding': 'gzip, deflate, br',
        'User-Agent': 'PostmanRuntime/7.26.8'
    }
    translator = Translator()
    for i in range(totalPages):
        i += 1
        url = "http://www.xiuren.org/page-"+str(i)+".html"
        response = requests.request("GET", url, headers=headers, data=payload)
        # response.encoding = 'gbk'
        soup = BeautifulSoup(response.text, 'html.parser')
        rows = soup.select('div#main .loop .content')
        if (rows != []):
            for r in rows:
                url = r.select('a')
                name = url[0].attrs['title']
                translation = translator.translate('骨感高挑妖艳外围女郎姗姗就打奥特曼宾馆激情全裸人体艺术大尺度美图 25P')
                print(translation.text)

if __name__ == "__main__":
	#main()
	#get_detail()
    convertTitle()
