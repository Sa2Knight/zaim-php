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
?>

<html>
<head>
	<title>Zaim APIで遊んでみた</title>
</head>
<body>
	<h1>月別集計 <?php echo $link; ?></h1>
	<table border="1">
		<tr>
			<th>年月</th>
			<th>出費</th>
		</tr>
		<?php foreach ($monthly as $date => $payments) : ?>
			<tr>
				<td><?php echo $date ?></td>
				<td><?php echo $payments ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>
