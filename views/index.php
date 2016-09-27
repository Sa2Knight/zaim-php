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
</body>
</html>
