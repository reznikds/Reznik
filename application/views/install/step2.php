<h2>Настройка соединения с базой данных</h2>

<div class="error_message">
<? if (isset($dberror)) {?>
	<p style="text-align: center;">Ошибка в параметрах соединения с базой данных</p>
<?}?>
</div>

<form action="" method="post">
<table class="materials-add">
	<tr>
		<td style="text-align:right">Сервер базы данных:</td>
		<td>
			<input type="text" name="hostname" value="<?=e(Arr::get($values, 'hostname', 'localhost'))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Имя базы данных:</td>
		<td>
			<input type="text" name="database" value="<?=e(Arr::get($values, 'database', ''))?>">
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
		<td>&nbsp;</td>
		<td><input type="submit" name="btnSubmit" value="Продолжить" style="width:235; height:45px;"></td>
	</tr>
</table>
</form>

