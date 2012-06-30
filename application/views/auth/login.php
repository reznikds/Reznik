<? if(isset($error)){?>
<p style="color:red; text-align:center">Логин и/или пароль введен неверно</p>
<?}?>

<form action="" method="post">
	<table class="login">
		<tr>
			<th colspan="2" style="padding-bottom:10px;"><b>Авторизация</b></th>
		</tr>
		<tr>
			<td style="text-align: right">Логин:</td>
			<td><input type="text" name="login"></td>
		</tr>
		<tr>
			<td style="text-align: right">Пароль:</td>
			<td><input type="password" name="password"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Войти" style="width:154px; height:30px" name="btnSubmit"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align: right; padding: 10px 5px 0"><a href="/auth/restorepassword.html">Вспомнить пароль</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align: right"><a href="/auth/registration.html">Быстрая регистрация</a></td>
		</tr>
	</table>
</form>
