<?php

require_once('HTTP/OAuth/Consumer.php');
require 'util.php';

const SITE_URL = "https://api.zaim.net";
const API_URL = "https://api.zaim.net/v2/";
const REQUEST_TOKEN_PATH = "/v2/auth/request";
const AUTHORIZE_URL = "https://auth.zaim.net/users/auth";
const ACCESS_TOKEN_PATH = "https://api.zaim.net";

// OAuth認証し、セションに記録する
function set_oauth_consumer() {
	//下準備
	session_start();
	$keys = load_keys_file();
	$consumer = new HTTP_OAuth_Consumer($keys["key"], $keys["secret"]);

	//SSL設定
	$http_request = new HTTP_Request2();
	$http_request->setConfig('ssl_verify_peer', false);
	$consumer_request = new HTTP_OAuth_Consumer_Request;
	$consumer_request->accept($http_request);
	$consumer->accept($consumer_request);

	//アクセストークン読み込み
	$consumer->setToken($keys['access_token']);
	$consumer->setTokenSecret($keys['access_token_secret']);
	$_SESSION['consumer'] = $consumer;
}

// ユーザ名を取得
function user_name($consumer) {
	return get_verify($consumer)['name'];
}

// ユーザ情報を取得
function get_verify($consumer) {
	return get($consumer , 'home/user/verify')['me'];
}

// リクエストをGETで送信し、レスポンスのJSONを連想配列に変換して戻す
function get($consumer , $url) {
	$response = $consumer->sendRequest(API_URL . $url , array() , 'GET');
	$json = $response->getBody();
	return json_decode($json , true);
}

?>
