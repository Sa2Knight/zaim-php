<?php

require_once '../class/zaim.php';
require_once '../class/util.php';
$zaim = Util::get_oauth_consumer();
$target = $_GET['target'];
if ($target == 'category') {
} elseif ($target == 'genre') {
} elseif ($target == 'place') {
	$title = '支払先';
	$ranking = $zaim->place_ranking();
}

?>

<html>
<head>
	<title>Zaim APIで遊んでみた</title>
</head>
<body>
	<h1><?php echo $title ?> 別ランキング</h1>
	<table border="1">
		<tr>
			<th>順位</th>
			<th><?php echo $title ?></th>
			<th>回数</th>
			<th>総額</th>
		</tr>
		<?php foreach ($ranking as $k => $v) : ?>
			<tr>
				<td><?php echo $v['rank']; ?></td>
				<td><?php echo $k; ?></td>
				<td><?php echo $v['num']; ?></td>
				<td><?php echo $v['amount']; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>
