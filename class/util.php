<?php

const KEYSFILE = "../keys.json";

function load_keys_file() 
{
	$json = file_get_contents(KEYSFILE);
	$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	$keys = json_decode($json , true);
	return $keys;
}

?>
