<!-- Форма логина -->
<div id="slider">
	<div id="slider-in">
		<form action="/auth/login.html" method="post">
			<p style="padding-bottom: 2px;">Логин:</p>
			<div><input type="text" name="login" id="login"></div>
			<p style="padding-bottom: 2px;">Пароль:</p>
			<div><input type="password" name="password"></div>
			<div><input type="submit" name="btnSubmit" value="Войти" style="width: 100px; height: 25px; margin-top: 10px"></div>
		</form>
		<p style="padding:10px 0 5px"><a href="/auth/restorepassword.html">Вспомнить пароль</a></p>
		<p style="padding: 0"><a href="/auth/registration.html">Быстрая регистрация</a></p>
	</div>
	<div id="open-div"><a href="#" id="open-button">Вход</a></div>
	<div id="close-div" style="display:none"><a href="#" id="close-button">Закрыть</a></div>
</div>

<script type="text/javascript">
	function OpenSlider()
	{
		$("#slider-in").animate({ height: "194px" });
		$("#login").focus();
		$("#open-div").toggle();
		$("#close-div").toggle();
		return false;
	}

	function CloseSlider()
	{
		$("#slider-in").animate({ height: "0" });
		$("#open-div").toggle();
		$("#close-div").toggle();
		return false;
	}

	$(document).ready(function(){
		$("#open-button").click(OpenSlider);
		$("#close-button").click(CloseSlider);
	});
</script>