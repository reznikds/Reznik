<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/1')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
	<a href="/">Главная</a>
<? if(isset($teacher)) {?>
	<a href="/materials.html">Учебные материалы</a>
	<a href="/account.html" class="admin-menu-active">Авторизационные данные</a>
<?}?>
</p>
<h2>Личный кабинет пользователя/Авторизационные данные</h2>
<div class="change-password">
	<p style="text-align:center">Ваш логин (адрес эл. почты): <?=$username?></p>
	<p style="text-align:center"><b>Изменить пароль</b></p>
	<form action="" method="post">
		<table class="changepassword">
			<tr>
				<td style="text-align: right">Старый пароль:</td>
				<td><input type="password" name="oldpass" id="oldpass"></td>
				<td style="width: 75px;">
					<span style="display: none" id="oldpassok"><img src="/img/ok.png" title="Старый пароль введен правильно" alt="Старый пароль введен правильно"></span>
					<span style="display: none" id="oldpasserror"><img src="/img/error.png" title="Ошибка в старом пароле" alt="Ошибка в старом пароле"></span>
				</td>
			</tr>
			<tr>
				<td style="text-align: right">Новый пароль:</td>
				<td><input type="password" name="newpass1" id="newpass1"></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align: right">Повторите новый пароль:</td>
				<td><input type="password" name="newpass2" id="newpass2"></td>
				<td>
					<span style="display: none" id="newpassmatchesok"><img src="/img/ok.png" title="Новые пароли совпадают" alt="Новые пароли совпадают"></span>
					<span style="display: none" id="newpassmatcheserror"><img src="/img/error.png" title="Новые пароли несовпадают" alt="Новые пароли несовпадают"></span>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2"><input type="checkbox" id="showpassbtn"><label for="showpassbtn"> Не прятать пароли за звездочки</label></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Сохранить новый пароль" name="btnSubmit"></td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</form>
</div>

<!-- Вывод сообщения об успешной смене пароля -->
<?if(isset($success)){?>
	<p style="text-align:center; color:green;">
		Новый пароль успешно сохранен
	</p>
<?}?>
<!-- /Вывод сообщения об успешной смене пароля -->

<!-- Вывод ошибок валидации при сохранении нового пароля -->
<? if(isset($errors)){?>
<?foreach($errors as $error){?>
<p style="text-align:center; color:red"><?=$error?></p>
<?}?>
<?}?>
<!-- / Вывод ошибок валидации при сохранении нового пароля -->

<script type="text/javascript">
	function checkPassword()
	{
		var oldpass = $("#oldpass").val();

		$.ajax({
			type: "POST",
			data: "oldpass=" + oldpass,
			url: "/ajax/checkpassword",
			dataType: "json",
			success: function(data)
			{
				if(data.result)
				{
					$("#oldpassok").css('display','inline');
					$("#oldpasserror").css('display','none');
				}
				else
				{
					$("#oldpasserror").css('display','inline');
					$("#oldpassok").css('display','none');
				}
			}
		})
	}

	function showPass()
	{
		var checked = $("#showpassbtn").attr('checked');

		if(checked == "checked")
		{
			document.getElementById('oldpass').type = 'text';
			document.getElementById('newpass1').type = 'text';
			document.getElementById('newpass2').type = 'text';
		}
		else
		{
			document.getElementById('oldpass').type = 'password';
			document.getElementById('newpass1').type = 'password';
			document.getElementById('newpass2').type = 'password';
		}
	}

	function matchesPass()
	{
		if($("#newpass1").val() == $("#newpass2").val())
		{
			$("#newpassmatchesok").css('display','inline');
			$("#newpassmatcheserror").css('display','none');
		}
		else
		{
			$("#newpassmatcheserror").css('display','inline');
			$("#newpassmatchesok").css('display','none');
		}
	}

	$(document).ready(function(){
		$("#oldpass").blur(checkPassword);
		$("#showpassbtn").click(showPass);
		$("#newpass2").keyup(matchesPass);
	});
</script>
