<?php
	require_once '../class/zaim.php';
	require_once '../class/util.php';
	$zaim = Util::get_oauth_consumer();
	$total_pay = $zaim->total_payments();
	$total_income = $zaim->total_incomes();
	$profit = $total_income - $total_pay;
	$total_input_count = $zaim->total_input_count();
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
			<td><?php echo $total_input_count ?></td>
		</tr>
		<tr>
			<td>総収入</td>
			<td><?php echo $total_income;  ?></td>
		</tr>
		<tr>
			<td>総支出</td>
			<td><?php echo $total_pay; ?></td>
		</tr>
		<tr>
			<td>収益</td>
			<td><?php echo $profit; ?></td>
		</tr>
	</table>

	<h1>集計情報</h1>
	<p>月別集計</p>
	<ul>
		<li><a href="/monthly.php?link=累計">累計</a></li>
		<li><a href="/monthly.php?category_id=101&link=食費">食費</a></li>
		<li><a href="/monthly.php?genre_id=10502&link=電気代">電気代</a></li>
		<li><a href="/monthly.php?genre_id=10503&link=ガス代">ガス代</a></li>
		<li><a href="/monthly.php?genre_id=10501&link=水道代">水道代</a></li>
		<li><a href="/monthly.php?genre_id=9047786&comment=ポケモンGO&link=ポケモンGO">ポケモンGO</a></li>
		<li><a href="/monthly.php?genre_id=10203&link=デグー関連">デグー関連</a></li>
	</ul>
	<p>ランキング</p>
	<ul>
		<li><a href="/ranking.php?target=category">カテゴリ</a></li>
		<li><a href="/ranking.php?target=genre">ジャンル</a></li>
		<li><a href="/ranking.php?target=place">支払先</a></li>
	</ul>

</body>
</html>
