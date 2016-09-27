<?php
require_once '../class/zaim.php';
require_once '../class/util.php';
$zaim = Util::get_oauth_consumer();
?>

<html>
<head>
	<title>Zaim APIで遊んでみた</title>
</head>
<body>
	<h1>月別集計</h1>
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
