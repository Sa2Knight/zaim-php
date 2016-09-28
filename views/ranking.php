<?php

require_once '../class/zaim.php';
require_once '../class/util.php';
$zaim = Util::get_oauth_consumer();
$target = $_GET['target'];
if ($target == 'category') {
	$title = 'カテゴリー';
	$ranking = $zaim->category_ranking();
} elseif ($target == 'genre') {
	$title = 'ジャンル';
	$ranking = $zaim->genre_ranking();
} elseif ($target == 'place') {
	$title = '支払先';
	$ranking = $zaim->place_ranking();
}

?>
<?php require_once("header.html"); ?>
	<h1><?php echo $title ?> 別ランキング</h1>
	<table border="1">
		<tr>
			<th class='center'>順位</th>
			<th class='center'><?php echo $title ?></th>
			<th class='center'>回数</th>
			<th class='center'>総額</th>
		</tr>
		<?php foreach ($ranking as $k => $v) : ?>
			<tr>
				<td class='center'><?php echo $v['rank']; ?></td>
				<td class='center'>
					<p class='link' onclick='moveAllDayPaymentsURL("<?php echo $title ?>" , "<?php echo $v['id'] ?>")'><?php echo $v['key']; ?></p>
				</td>
				<td class='center'><?php echo $v['num']; ?></td>
				<td class='right'><?php Util::echo_money($v['amount']); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php require_once("footer.html"); ?>
