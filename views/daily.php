<?php
require_once '../class/zaim.php';
require_once '../class/util.php';
$zaim = Util::get_oauth_consumer();
$daily = $zaim->daily_payments();
function getColor($pay) {
	if ($pay < 1000) return '#FFFFFF';
	if ($pay < 1500) return '#ADFF9C';
	if ($pay < 3000) return '#FFA500';
	if ($pay < 5000) return '#FF4500';
	return '#FF0000';
}
function getZaimURL($date) {
	$base = "https://zaim.net/money?";
	$url  = $base . "start_date=" . $date . "&end_date=" . $date;
	echo $url;
}
?>
<?php require_once("header.html"); ?>
	<h1>日別集計</h1>
	<table border="1">
		<tr>
			<th class='center'>日にち</th>
			<th class='center'>出費</th>
		</tr>
		<?php foreach ($daily as $date => $payments) : ?>
			<tr>
				<td class='center'><a class="link" href="<?php getZaimURL($date); ?>"><?php echo $date ?></a></td>
				<td class='right' style="background-color: <?php echo getColor($payments); ?>"><?php Util::echo_money($payments) ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php require_once("footer.html"); ?>
