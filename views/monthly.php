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

function getZaimURL($month) {
	$base = "https://zaim.net/money/month?month=";
	$exploded = explode("-" , $month);
	$y = intval($exploded[0]);
	$m = intval($exploded[1]);
	$url = sprintf("%s%d%02d" , $base , $y , $m);
	echo $url;
}
?>
<?php require_once("header.html"); ?>
	<h1>月別集計 <?php echo $link; ?></h1>
	<table border="1">
		<tr>
			<th class='center'>年月</th>
			<th class='center'>出費</th>
		</tr>
		<?php foreach ($monthly as $date => $payments) : ?>
			<tr>
				<td class='center'><a class="link" href="<?php getZaimURL($date) ?>"><?php echo $date ?></a></td>
				<td class='right'><?php Util::echo_money($payments) ?></td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td class='center'>合計</td>
			<td class='right'><?php Util::echo_money($sum) ?></td>
		</tr>
	</table>
<?php require_once("footer.html"); ?>
