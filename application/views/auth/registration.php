<?if(isset($success)) {?>
	<p style="text-align:center; color:green;">
		Регистрация прошла успешно, на Ваш email отправлено письмо с регистрационными данными.
	</p>
<?}?>
<? isset($errors) or $errors = array()?>
<? foreach($errors as $item) {?>
	<p style="color:red;"><?=$item?></p>
<?}?>

	<form action="" method="post">
		<table class="reg">
			<tr>
				<th colspan="2" style="padding-bottom:10px;"><b>Регистрация</b></th>
			</tr>
			<tr>
				<td style="text-align:right">Эл. почта:</td>
				<td><input type="text" name="email" value="<?= Arr::get($values, 'email'); ?>"></td>
			</tr>
			<tr>
				<td style="text-align:right">Ваше имя:</td>
				<td><input type="text" name="name" value="<?= Arr::get($values, 'name'); ?>"></td>
			</tr>
			<tr>
				<td style="text-align:right">Код приглашения:</td>
				<td><input type="text" name="regcode" value="<?= Arr::get($values, 'regcode'); ?>"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Зарегистрироваться" style="width:154px; height:30px" name="btnSubmit"></td>
			</tr>
		</table>
	</form>