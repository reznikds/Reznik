<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Установка</title>
<link href="/css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="contaner">
		<div id="header">
			<h1 style="text-align: center">Установка</h1>
		</div>
		<div id="content">
<?=$content?>
		</div>
		<div style="clear:both"></div>
		<div id="empty"></div>
	</div>
	<div id="footer">
		<hr>
		<p style="padding-top:5px">&copy; 2012</p>
	</div>
</body>

<? if (isset($_GET['d'])) {?>
<div id="kohana-profiler">
<?=View::factory('profiler/stats')?>
</div>
<?}?>

</html>