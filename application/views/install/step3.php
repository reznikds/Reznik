<h2>Настройка параметров отправки почты</h2>

<div class="error_message">
<? if (isset($needCheck)) {?>
	<p style="text-align: center;">Сначала Вы должны указать и проверить настройки SMTP сервера, нажав кнопку "Проверить настройки".</p>
<?}?>
<? if (isset($SMTPerror)) {?>
	<p style="text-align: center;">Не удалось отправить почту, скорее всего ошибка в параметрах SMTP сервера или в вашем адресе email.</p>
<?}?>
</div>
<div class="success_message">
<? if (isset($time)) {?>
	<p style="text-align: center;">Тестовое сообщение отправлено в <?=$time?>, проверьте почту.</p>
<?}?>
</div>

<form action="" method="post">
<table class="materials-add">
	<tr>
		<td style="text-align:right">SMTP сервер:</td>
		<td>
			<input type="text" name="hostname" value="<?=e(Arr::get($values, 'hostname', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Имя пользователя:</td>
		<td>
			<input type="text" name="username" value="<?=e(Arr::get($values, 'username', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Пароль:</td>
		<td>
			<input type="password" name="password" value="<?=e(Arr::get($values, 'password', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Порт:</td>
		<td>
			<input type="text" name="port" value="<?=e(Arr::get($values, 'port', 25))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Таймаут, сек:</td>
		<td>
			<input type="text" name="timeout" value="<?=e(Arr::get($values, 'timeout', 10))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Домен (localDomain):</td>
		<td>
			<input type="text" name="localDomain" value="<?=e(Arr::get($values, 'localDomain', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">email администратора:</td>
		<td>
			<input type="text" name="systemEmail" value="<?=e(Arr::get($values, 'systemEmail', ''))?>">
		</td>
	</tr>
	
	
	<tr>
		<td><hr></td>
		<td><hr></td>
	</tr>
	<tr>
		<td colspan="2">
			Укажите свой email для проверки настроек SMTP сервера. После нажатия кнопки "Проверить настройки"
			скрипт попытается отправить тестовое сообщение на этот email.
		</td>
	</tr>
	<tr>
		<td style="text-align:right">email:</td>
		<td>
			<input type="text" name="myemail" value="<?=e(Arr::get($values, 'myemail', ''))?>">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="btnCheck" value="Проверить настройки" style="width:235; height:45px;">
			<input type="submit" name="btnSubmit" value="Продолжить" style="width:235; height:45px;">
		</td>
	</tr>
</table>
</form>

