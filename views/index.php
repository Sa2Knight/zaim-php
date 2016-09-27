<?php
	require_once '../class/zaim.php';
	require_once '../class/util.php';
	$zaim = Util::get_oauth_consumer();
	echo $zaim->user_name();
?>

<html>
<head>
	<title>Zaim APIで遊んでみた</title>
</head>
<body>
	<h1>基本情報</h1>
	<table border="1">
		<tr>
			<td>入力回数</td>
			<td>0</td>
		</tr>
		<tr>
			<td>総収入</td>
			<td>0</td>
		</tr>
		<tr>
			<td>総支出</td>
			<td>0</td>
		</tr>
		<tr>
			<td>収益</td>
			<td>0</td>
		</tr>
	</table>
</body>
</html>
