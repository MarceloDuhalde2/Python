<?php
define('CG_DIR', 'https://api.coingecko.com/api/v3/');

//////////////////////////////////////////////////////////////////////////////////////////////////////
/**
* get tickers by ids
* @param $exchange_id array ids of tokens in coingecko, example: 12, 14
* @return $response object con respuesta de api. 
*/
function get_tickers_by_ids(array $ids){
	$aux_response = array();
	$aux = CG_DIR."/simple/price?ids=".$i."&vs_currencies=usd";
	$response = json_decode(file_get_contents($aux))->tickers;
	$tickers = array_merge($aux_response, $response);
	return $tickers;
}

/**
* get tickers by ids
* @param $exchange_id array ids of tokens in coingecko, example: 12, 14
* @return $response object con respuesta de api. 
*/
function get_last_price(string $id){
	$aux = CG_DIR."/simple/price?ids=".$id."&vs_currencies=usd";
	$last_price = json_decode(file_get_contents($aux))->$id->usd;
	return $last_price;
}

/**
* search coins id by name
* @param $exchange_id array ids of tokens in coingecko, example: 12, 14
* @return $response object con respuesta de api. 
*/
function search_coins_id_by_name(string $name){
	$coins = array();
	$aux = CG_DIR."/coins/list";
	$response = json_decode(file_get_contents($aux));
	foreach ($response as $key => $coin){
		$symbol_exist = stripos($coin->symbol, $name);
		$name_exist = stripos($coin->name, $name);
		if ($symbol_exist === false and $name_exist === false) {
		}else{
			$coins[] = $coin;
		}
	}
	return $coins;
}

/**
* List markets by exchange ID
* get_markets_by_exchange("binance");
*
* @param $coin_id string id of coin, example: "bitcoin"
* @param $vs_currency string vs_currency, example: "btc", "eth", "usd"
* @param $days string days of data, example: 1,30,200, max
* @return $response object con respuesta de api. 
*/
function get_market_chart_by_id(string $coin_id, string $vs_currency, string $days){
	$aux_coins_ids = array(
		"USDT" => "usd",
		"BTC" => "btc",
		"PAX" => "usd",
		"USDC" => "usd",
		"TUSD" => "usd",
		"ETH" => "eth",
		"BNB" => "bnb",
		"XRP" => "xrp",
		"USDS" => "usd",
		"WBTC" => "btc",
		"DAI" => "usd",
		"HUSD" => "usd",
		"USD" => "usd"	
	);
	if(isset($vs_currency))
		$data["vs_currency"] = $aux_coins_ids[$vs_currency];
	if(isset($days))
		$data["days"] = $days;
	
	$aux = CG_DIR."coins/".$coin_id."/market_chart?".http_build_query($data);

	$response = json_decode(file_get_contents($aux));
	//$aux_response = $response;
	foreach ($response->prices as $key => $value) {
		//$aux_response[$key]["date"] = date("Y-m-d H:i:s", strtotime('+3 hours', $value[0]/1000));
		$aux_response[$key]["date"] = date("Y-m-d H:i:s", $value[0]/1000);
		$aux_response[$key]["close"] = $value[1];
		$aux_response[$key]["volume"] = $response->total_volumes[$key][1];
	}
	return $aux_response;
}