from pycoingecko import CoinGeckoAPI
import mysql.connector
from datetime import date
import requests 
import datetime
import telegram

def coingecko_client():
	client = CoinGeckoAPI()
	return client

def db_connection():
	dbhost = "localhost"
	dbuser = "root"
	dbpass = "root"
	db = "portfolio_crypto"
	conn = mysql.connector.connect(user=dbuser, password=dbpass, host=dbhost, database=db)
	conn.autocommit = True
	return conn

def close_connection(conn):
	conn.close()

def send_message_telegram(message, type):
	chat_id = ""
	# debo usar un diccionario para hacer el switch
	switcher = {
		"cc_coingecko_bot" : "telegram_token",
		"cc_new_coin_bot" : "telegram_token",
	}
	bot_token = switcher.get(type)
	bot = telegram.Bot(token=bot_token)
	bot.send_message(chat_id=chat_id, text=message, parse_mode=None, disable_web_page_preview=None, disable_notification=False, reply_to_message_id=None, reply_markup=None, timeout=15)
	
	return True

def log_data(conn, desc):
	now = datetime.datetime.now()
	cursor = conn.cursor()
	cursor.execute("insert into `logs` (`desc`, `insert_date`) values (%s, %s);", (desc, now))
