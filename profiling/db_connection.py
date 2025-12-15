import MySQLdb

def connect_to_db():
    conn = MySQLdb.connect(
        host="localhost",
        user="root",
        password="root",
        database="teste"
    )
    return conn
