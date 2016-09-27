<?php

require_once('HTTP/OAuth/Consumer.php');
require_once 'util.php';

const SITE_URL = "https://api.zaim.net";
const API_URL = "https://api.zaim.net/v2/";
const REQUEST_TOKEN_PATH = "/v2/auth/request";
const AUTHORIZE_URL = "https://auth.zaim.net/users/auth";
const ACCESS_TOKEN_PATH = "https://api.zaim.net";

class Zaim {

	private $consumer;
	private $all_payments;
	private $all_incomes;

	// インスタンス生成時に、OAuth認証を行う
	public function __construct() {
		//下準備
		session_start();
		$keys = Util::load_keys_file();
		$this->consumer = new HTTP_OAuth_Consumer($keys["key"], $keys["secret"]);

		//SSL設定
		$http_request = new HTTP_Request2();
		$http_request->setConfig('ssl_verify_peer', false);
		$consumer_request = new HTTP_OAuth_Consumer_Request;
		$consumer_request->accept($http_request);
		$this->consumer->accept($consumer_request);

		//アクセストークン読み込み
		$this->consumer->setToken($keys['access_token']);
		$this->consumer->setTokenSecret($keys['access_token_secret']);
	}

	// キャッシュしている情報を削除
	public function clear_cach() {
		unset($all_payments);
		unset($all_incomes);
	}

	// ユーザ名を取得
	public function user_name() {
		return $this->get_verify()['name'];
	}

	// 総支出額を取得
	public function total_payments() {

	}

	// 総収入を取得
	public function total_incomes() {

	}

	// 総入力回数を取得
	public function total_input_count() {

	}

	// 全支出データを取得し、キャッシュする
	private function all_payments() {

	}

	// 全収入データを取得し、キャッシュする
	private function all_incomes() {

	}

	// ユーザ情報を取得
	private function get_verify() {
		return $this->get('home/user/verify')['me'];
	}

	// 支払情報を取得
	private function get_payments($params) {

	}

	// 収入情報を取得
	private function get_incomes($params) {

	}

	// リクエストをGETで送信し、レスポンスのJSONを連想配列に変換して戻す
	private function get($url) {
		$response = $this->consumer->sendRequest(API_URL . $url , array() , 'GET');
		$json = $response->getBody();
		return json_decode($json , true);
	}

}

?>
