<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Удаленное обучение</title>
<link href="/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>

<body>
	<div id="contaner">
	<!--Вызов отображение формы логина-->
	<?=Request::factory('loginform')->execute()?>
	<!-- / Вызов отображение формы логина-->
		<div id="header">
			<h1 style="text-align: center"><a href="/">Удаленное обучение</a></h1>
		</div>
		<div id="content">
        <h2 style="text-align: center; color:red; font-size:18px;">Страница не найдена</h2>
		<p style="text-align: center">Вернитесь на <a href="/">главную</a>.</p>
		</center>
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