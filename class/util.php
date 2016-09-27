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

}

?>
