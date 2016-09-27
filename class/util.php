<?php

require_once 'zaim.php';

class Util {

	const KEYSFILE = "../keys.json";

	public static function get_oauth_consumer()
	{
		if (isset($_SESSION['consumer']) == false) {
			$object = serialize(new Zaim());
			$_SESSION['consumer'] = $object;
		}
		$zaim = unserialize($_SESSION['consumer']);
		$zaim->clear_cache();
		return $zaim;
	}

	public static function load_keys_file()
	{
		$json = file_get_contents(Util::KEYSFILE);
		$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		$keys = json_decode($json , true);
		return $keys;
	}

	public static function date2month($date , $cutoff = 25) {
		$pattern = '/^(\d{4})-(\d{1,2})-(\d{1,2})$/';
		preg_match($pattern , $date , $m);
		$year = intval($m[1]);
		$month = intval($m[2]);
		$day = intval($m[3]);

		$datetime = date_create($year . '-' . $month . '-' . $cutoff);
		$week_day = (int)$datetime->format('w');
		if ($week_day == 0) $cutoff -= 2;
		if ($week_day == 6) $cutoff -= 1;

		if ($cutoff <= $day) {
			$month += 1;
			if ($month == 13) {
				$month = 1;
				$year += 1;
			}
		}

		return $year . '-' . $month;
	}

	public static function echo_money($money) {
		$money = intval($money);
		if ($money >= 10000) {
			$m = intval($money / 10000);
			$s = $money % 10000;
			echo $m . '万' . $s;
		} else {
			echo $money;
		}
	}

}

?>
