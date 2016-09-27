<?php
require_once '../class/zaim.php';
require_once '../class/util.php';
$link = $_GET['link'];
$params = array();
if (isset($_GET['genre_id'])) {
	$params['genre_id'] = $_GET['genre_id'];
} elseif (isset($_GET['category_id'])) {
	$params['category_id'] = $_GET['category_id'];
}
if (isset($_GET['comment'])) {
	$params['comment'] = $_GET['comment'];
}
$zaim = Util::get_oauth_consumer();
$monthly = $zaim->monthly_payments($params);
$sum = 0;
foreach ($monthly as $pay) {
	$sum += $pay;
}
?>

<html>
<head>
	<title>Zaim APIで遊んでみた</title>
	<link rel="stylesheet" href="/css/style.css" type="text/css">
</head>
<body>
	<h1>月別集計 <?php echo $link; ?></h1>
	<table border="1">
		<tr>
			<th class='center'>年月</th>
			<th class='center'>出費</th>
		</tr>
		<?php foreach ($monthly as $date => $payments) : ?>
			<tr>
				<td class='center'><?php echo $date ?></td>
				<td class='right'><?php Util::echo_money($payments) ?></td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td class='center'>合計</td>
			<td class='right'><?php Util::echo_money($sum) ?></td>
		</tr>
	</table>
</body>
</html>
