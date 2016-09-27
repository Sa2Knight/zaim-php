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
	private $cache;

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

	// キャッシュをクリア
	public function clear_cache() {
		unset($cache);
	}

	// ユーザ名を取得
	public function user_name() {
		return $this->get_verify()['name'];
	}

	// 総支出額を取得
	public function total_payments() {
		$all_money = $this->get_money();
		$total = 0;
		foreach($all_money as $money) {
			if ($money['mode'] == 'payment') {
				$total += $money['amount'];
			}
		}
		return $total;
	}

	// 総収入を取得
	public function total_incomes() {
		$all_money = $this->get_money();
		$total = 0;
		foreach($all_money as $money) {
			if ($money['mode'] == 'income') {
				$total += $money['amount'];
			}
		}
		return $total;
	}

	// 総入力回数を取得
	public function total_input_count() {
		return count($this->get_money());
	}

	// 支払先別のランキングを生成
	public function place_ranking() {
		$ranking = $this->create_ranking('place');
		$rank = 1;
		foreach ($ranking as &$r) {
			$r['rank'] = $rank;
			$rank += 1;
		}
		unset($r);
		return $ranking;
	}

	// カテゴリIDをカテゴリ名に一括変換
	private function categories2id($categories_id) {
	}

	// ジャンルIDをジャンル名に一括変換
	private function genres2id($genres_id) {
	}

	// 指定した条件でランキングを生成
	private function create_ranking($key , $params = array()) {
		$params = array('mode' => 'payment');
		$payments = $this->get_money($params);
		$t_hash = array();
		foreach ($payments as $pay) {
			$k = $pay[$key];
			if (isset($t_hash[$k]) == false) {
				$t_hash[$k] = array('num' => 0 , 'amount' => 0);
			}
			$t_hash[$k]['num'] += 1;
			$t_hash[$k]['amount'] += $pay['amount'];
		}
		unset($t_hash[""]); //未入力は削除
		return $t_hash;
	}

	// ユーザ情報を取得
	private function get_verify() {
		return $this->get('home/user/verify')['me'];
	}

	// 全入力情報を取得し、キャッシュする
	private function get_money($params = array()) {
		if (isset($this->cache) == false) {
			$this->cache = $this->get('home/money' , $params)['money'];
		}
		return $this->cache;
	}

	// ジャンル一覧を取得
	private function get_genres() {
		return $this->get('home/genre')['genres'];
	}

	// カテゴリ一覧を取得
	private function get_categories() {
		return $this->get('home/category')['categories'];
	}

	// リクエストをGETで送信し、レスポンスのJSONを連想配列に変換して戻す
	private function get($url , $params = array()) {
		$response = $this->consumer->sendRequest(API_URL . $url , $params , 'GET');
		$json = $response->getBody();
		return json_decode($json , true);
	}

}

?>
