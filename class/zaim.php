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

	// 月ごとの支出を計算
	public function monthly_payments($params = array()) {
		$params['mode'] = 'payment';
		$payments = $this->get_money($params);
		$monthly = array();
		foreach ($payments as $pay) {
			$month = Util::date2month($pay['date']);
			if (isset($monthly[$month]) == false) {
				$monthly[$month] = 0;
			}
			$monthly[$month] += $pay['amount'];
		}
		return $monthly;
	}

	// カテゴリ別のランキングを生成
	public function category_ranking() {
		return $this->create_ranking('category_id' , $this->get_categories());
	}

	// ジャンル別のランキングを生成
	public function genre_ranking() {
		return $this->create_ranking('genre_id' , $this->get_genres());
	}

	// 支払先別のランキングを生成
	public function place_ranking() {
		return $this->create_ranking('place');
	}

	// 指定した条件でランキングを生成
	private function create_ranking($key , $id_info = null , $params = array()) {
		$ranking = $this->aggregate_payments($key , $params);
		if ($id_info) {
			$ids = array_map(function($r) { return $r['id']; } , $ranking);
			$id2name = $this->id2names($id_info , $ids);
		}
		uasort($ranking , function($a , $b) { return ($a['num'] <= $b['num']) ? 1 : -1; });

		$rank = 1;
		foreach ($ranking as &$r) {
			$r['rank'] = $rank;
			$rank += 1;
			if ($id_info) {
				$r['key'] = $id2name[$r['id']];
			} else {
				$r['key'] = $r['id'];
			}
		}
		unset($r);
		return $ranking;
	}

	// 支払情報内の、特定の要素を集計する
	private function aggregate_payments($key , $params = array()) {
		$params = array('mode' => 'payment');
		$payments = $this->get_money($params);
		$t_hash = array();
		foreach ($payments as $pay) {
			$k = $pay[$key];
			if (isset($t_hash[$k]) == false) {
				$t_hash[$k] = array('num' => 0 , 'amount' => 0);
			}
			$t_hash[$k]['id'] = $k;
			$t_hash[$k]['num'] += 1;
			$t_hash[$k]['amount'] += $pay['amount'];
		}
		unset($t_hash[""]); //未入力は削除
		return $t_hash;
	}

	// IDのリストを、カテゴリ名 or ジャンル名に一括変換
	private function id2names($id_info , $ids) {
		$id2name = array();
		foreach ($id_info as $i) {
			if (array_search($i['id'] , $ids)) {
				$id2name[$i['id']] = $i['name'];
			}
		}
		return $id2name;
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
