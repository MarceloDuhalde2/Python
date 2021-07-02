import sys
import datetime
from library import config
conn = config.db_connection()
cursor = conn.cursor(dictionary=True)
coingecko = config.coingecko_client()
coin_list = coingecko.get_coins_list()
print("execution: date: "+str(datetime.datetime.now()))
# if(datetime.datetime.now().hour == 21):
# 	query = "UPDATE symbols sy SET  sy.close_3_day_usd = sy.close_2_day_usd, sy.volume_3_day_usd = sy.volume_2_day_usd, sy.close_3_day_btc = sy.close_2_day_btc, sy.volume_3_day_btc = sy.volume_2_day_btc"
# 	cursor.execute(query)
# 	query = "UPDATE symbols sy SET  sy.close_2_day_usd = sy.close_1_day_usd, sy.volume_2_day_usd = sy.volume_1_day_usd, sy.close_2_day_btc = sy.close_1_day_btc, sy.volume_2_day_btc = sy.volume_1_day_btc"
# 	cursor.execute(query)
	#aca tengo que hacer algo para eliminar los pares que sean deslistados.
	#query = "SELECT * FROM "
	#cursor.execute(query)

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

query = "SELECT `id_coingecko` FROM `symbols`"
cursor.execute(query)
db_data = [item['id_coingecko'] for item in cursor.fetchall()]
batched = [db_data[i:i + 99] for i in range(0, len(db_data), 99)]
vs_currencies = "usd,btc"
for batch in batched:
	csv = ",".join(batch)
	tickers = coingecko.get_price(ids=csv, vs_currencies=vs_currencies, include_24hr_vol='true')
	for id, ticker in tickers.items():
		if("usd" in ticker and "btc" in ticker):
			query = "UPDATE `symbols` SET `last_close_usd`=%s, `last_close_btc`=%s WHERE `id_coingecko`=%s"
			data_query = (ticker["usd"], ticker["btc"], id)
			cursor.execute(query,data_query)

# son alertas de 50% de variacion y demas, lo saco.
# 			#check alarma diferencia de precio +50%, antes de actualizar
# 			query = "SELECT `last_close_usd`, `last_close_btc` FROM `symbols` WHERE `id_coingecko`='"+id+"'"
# 			cursor.execute(query)
# 			symb = cursor.fetchone()
# 			if(symb["last_close_usd"] != None and symb["last_close_btc"] != None):
# 				if((((symb["last_close_usd"] + symb["last_close_usd"]*0.5) < ticker["usd"] or (symb["last_close_btc"] + symb["last_close_btc"]*0.5) < ticker["btc"]) or ((symb["last_close_usd"] - symb["last_close_usd"]*0.5) > ticker["usd"] or (symb["last_close_btc"] - symb["last_close_btc"]*0.5) > ticker["btc"])) and ticker["usd_24h_vol"] > 200000):
# 					message= "#"+id+" - Alert 50%: ""\n"
# 					message+= "Info: https://www.coingecko.com/es/monedas/"+str(id)+"\n"
# 					message+= "Markets: https://www.coingecko.com/es/monedas/"+str(id)+"#markets\n"
# 					message+= "Now Price USD: "+str(format(ticker["usd"],'.8f'))+"\n"
# 					message+= "Now Price BTC: "+str(format(ticker["btc"],'.8f'))+"\n"
# 					message+= "1 Hour Price USD: "+str(format(symb["last_close_usd"],'.8f'))+"\n"
# 					message+= "1 Hour Price BTC: "+str(format(symb["last_close_btc"],'.8f'))+"\n"
# 					message+= "24 hs Volume USD: "+str(format(ticker["usd_24h_vol"],'.0f'))+"\n"
# 					config.send_message_telegram(message, "cc_coingecko_bot")
					
# 		if(datetime.datetime.now().hour == 21):
# 			if("usd" in ticker and "btc" in ticker):
# 				query = "UPDATE `symbols` SET `close_1_day_usd`=%s, `volume_1_day_usd`=%s, `close_1_day_btc`=%s, `volume_1_day_btc`=%s WHERE `id_coingecko`=%s"
# 				data_query = (ticker["usd"], ticker["usd_24h_vol"], ticker["btc"], ticker["btc_24h_vol"], id)
# 				cursor.execute(query,data_query)
# 				#check alarma 3 green soldiers
# 				#check alarma diferencia de precio +50%, antes de actualizar
# 				query = "SELECT `close_3_day_usd`, `volume_3_day_usd`, `close_3_day_btc`, `volume_3_day_btc`, `close_2_day_usd`, `volume_2_day_usd`, `close_2_day_btc`, `volume_2_day_btc` FROM `symbols` WHERE `id_coingecko`='"+id+"'"
# 				cursor.execute(query)
# 				symb = cursor.fetchone()
# 				if(symb["close_3_day_usd"] != None and symb["close_2_day_usd"] != None and symb["close_3_day_btc"] != None and symb["close_2_day_btc"] != None):
# 					if((((symb["close_3_day_usd"] < symb["close_2_day_usd"] and symb["close_2_day_usd"] < ticker["usd"]) and (symb["volume_3_day_usd"] < symb["volume_2_day_usd"] and symb["volume_2_day_usd"] < ticker["usd_24h_vol"])) or ((symb["close_3_day_btc"] < symb["close_2_day_btc"] and symb["close_2_day_btc"] < ticker["btc"]) and (symb["volume_3_day_btc"] < symb["volume_2_day_btc"] and symb["volume_2_day_btc"] < ticker["btc_24h_vol"]))) and ticker["usd_24h_vol"] > 200000):
# 						message= "#"+id+" - Three Soldiers: ""\n"
# 						message+= "Info: https://www.coingecko.com/es/monedas/"+str(id)+"\n"
# 						message+= "Markets: https://www.coingecko.com/es/monedas/"+str(id)+"#markets\n"
# 						message+= "Last Day Price USD: "+str(format(ticker["usd"],'.2f'))+"\n"
# 						message+= "2 Day Price USD: "+str(format(symb["close_2_day_usd"],'.2f'))+"\n"
# 						message+= "3 Day Price USD: "+str(format(symb["close_3_day_usd"],'.2f'))+"\n"
# 						message+= "Last Day Volume USD: "+str(format(ticker["usd_24h_vol"],'.2f'))+"\n"
# 						message+= "2 Day Volume USD: "+str(format(symb["volume_2_day_usd"],'.2f'))+"\n"
# 						message+= "3 Day Volume USD: "+str(format(symb["volume_3_day_usd"],'.2f'))+"\n\n"
						
# 						message+= "Last Day Price BTC: "+str(format(ticker["btc"],'.8f'))+"\n"
# 						message+= "2 Day Price BTC: "+str(format(symb["close_2_day_btc"],'.8f'))+"\n"
# 						message+= "3 Day Price BTC: "+str(format(symb["close_3_day_btc"],'.8f'))+"\n"
# 						message+= "Last Day Volume BTC: "+str(format(ticker["btc_24h_vol"],'.8f'))+"\n"
# 						message+= "2 Day Volume BTC: "+str(format(symb["volume_2_day_btc"],'.8f'))+"\n"
# 						message+= "3 Day Volume BTC: "+str(format(symb["volume_3_day_btc"],'.8f'))+"\n"
# 						config.send_message_telegram(message, "cc_coingecko_bot")
config.close_connection(conn)