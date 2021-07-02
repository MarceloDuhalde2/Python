import sys
import datetime
from library import config
conn = config.db_connection()
cursor = conn.cursor(dictionary=True)
coingecko = config.coingecko_client()
coin_list = coingecko.get_coins_list()
print("execution: date: "+str(datetime.datetime.now()))
query = "SELECT `id` FROM `symbols` WHERE `id_coingecko`='"+symbol["id"]+"'"
cursor.execute(query)
db_data = cursor.fetchone()

for symbol in coin_list:
	query = "SELECT `id` FROM `symbols` WHERE `id_coingecko`='"+symbol["id"]+"'"
	cursor.execute(query)
	db_data = cursor.fetchone()
	if (db_data == None):
		query = "INSERT INTO `symbols` (`name`, `id_coingecko`) VALUES (%s,%s)"
		data_query = (symbol["symbol"], symbol["id"])
		cursor.execute(query,data_query)
		exchanges_data = coingecko.get_coin_by_id(id=symbol["id"], localization="false", tickers="true", market_data="false", community_data="false", developer_data="false", sparkline="false")
		if(exchanges_data.get("tickers") != None):
			for exchange in exchanges_data["tickers"]:
				if(exchange["market"]["identifier"] in ("uniswap", "balancer", "mooniswap", "one_inch")):
					message= "#"+symbol["id"]+" - Alert New  DEFI Coin: ""\n"
					message+= "Info: https://www.coingecko.com/es/monedas/"+str(symbol["id"])+"\n"
					message+= "Markets: https://www.coingecko.com/es/monedas/"+str(symbol["id"])+"#markets\n"
					config.send_message_telegram(message, "cc_new_coin_bot")
					break

config.close_connection(conn)