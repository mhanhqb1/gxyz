import mysql.connector
class MySQLRepository(object):
  def __init__(self, database, host="", port="", user="", password=""):
      import os
      host = host or os.environ.get('MYSQL_HOST') or "127.0.0.1"
      port = port or os.environ.get('MYSQL_PORT') or "3306"
      user = user or os.environ.get('MYSQL_USER') or "root"
      password = password or os.environ.get('MYSQL_PASS') or ""
      database = database or os.environ.get('MYSQL_DATABASE')
      self._connector = mysql.connector.connect(
          host=host,
          port=port,
          user=user,
          password=password,
          database=database,
          auth_plugin='mysql_native_password'
      )

  def close(self):
      self._connector.close()

  def cursor(self):
      return self._connector.cursor(buffered=True, dictionary=True)

  def all(self, sql: str, param={}):
      try:
        cur = self.cursor()
        cur.execute(sql, param)
        result = cur.fetchall()
      finally:
        self._connector.commit()
        cur.close()
      return result

  def fetch(self, sql: str, param={}):
      try:
          cur = self.cursor()
          cur.execute(sql, param)
          result = cur.fetchone()
      except Exception as e:
        print(e)
      finally:
          cur.close()
      return result

  def insert(self, sql: str, param={}):
      try:
        cur = self.cursor()
        cur.execute(sql, param)
        self._connector.commit()
        result = cur.lastrowid
        cur.close()
      except Exception as e:
        print(e)
      finally:
        cur.close()
      return result

  def execute(self, sql: str, param={}):
      cur = self.cursor()
      cur.execute(sql, param)
      self._connector.commit()
      cur.close()

  def executemany(self, sql: str, param=[]):
      cur = self.cursor()
      cur.executemany(sql, param)
      self._connector.commit()
      cur.close()

  def write_bad_url(self, url):
      sql = (
          " UPDATE funding_rounds SET bad_url=1 "
          " WHERE data_id IN %s" % str(tuple(url))
      )
      self.execute(sql)


if __name__ == '__main__':
    print('common')
