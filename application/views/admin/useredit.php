<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/2')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
<a href="/">Главная</a>
<a href="/admin/category.html">Управление категориями</a>
<a href="/admin/users/list.html">Управление пользователями</a> >
<? if ($mode == 'edit') {?>
<a href="" class="admin-menu-active">Редактирование пользователя</a>
<?}else{?>
<a href="/admin/users/add.html" class="admin-menu-active">Добавление пользователя</a>
<?}?>
<a href="/admin/users/upload.html">Загрузка пользователей</a>
</p>

<? if ($mode == 'edit') {?>
<h2>Админка/Управление пользователями/Редактирование пользователя</h2>
<?}else{?>
<h2>Админка/Управление пользователями/Добавление пользователя</h2>
<?}?>

<? if ($mode == 'add') {?>
<p>Добавление активного пользователя</p>
<?}?>
<form action="" method="post">
<table class="materials-add">
	<tr>
		<td style="text-align:right; width:200px;">Роль:</td>
		<td>
			<select name="role">
				<option value=""></option>
				<option value="admin"   <?=($role=='admin')?'selected':'';?>>Администратор</option>
				<option value="teacher" <?=($role=='teacher')?'selected':'';?>>Преподаватель</option>
				<option value="student" <?=($role=='student')?'selected':'';?>>Студент</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Эл. почта:</td>
		<td>
			<input type="text" name="email" value="<?=e(Arr::get($values, 'email', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Пароль:</td>
		<td>
			<input type="password" name="password" value="">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Повторите пароль:</td>
		<td>
			<input type="password" name="password_confirm" value="">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">ФИО:</td>
		<td>
			<input type="text" name="name" value="<?=e(Arr::get($values, 'name', ''))?>">
		</td>
	</tr>
<? if ($mode == 'edit') {?>
	<tr>
		<td style="text-align:right">Регистрационный код:</td>
		<td>
			<input type="text" name="confirmcode" value="<?=e(Arr::get($values, 'confirmcode', ''))?>">
		</td>
	</tr>
<?}?>
	<tr>
		<td style="text-align:right">Примечание:</td>
		<td>
			<input type="text" name="note" value="<?=e(Arr::get($values, 'note', ''))?>">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="btnSubmit" value="<?=($mode=='edit')?'Сохранить':'Добавить'?> пользователя" style="width:235; height:45px;"></td>
	</tr>
</table>
</form>

<? if ($mode == 'add') {?>
<p>Добавление неактивного пользователя</p>
<form action="" method="post">
<table class="materials-add">
	<tr>
		<td style="text-align:right; width:200px;">Роль:</td>
		<td>
			<select name="role">
				<option value=""></option>
				<option value="admin"   <?=($role=='admin')?'selected':'';?>>Администратор</option>
				<option value="teacher" <?=($role=='teacher')?'selected':'';?>>Преподаватель</option>
				<option value="student" <?=($role=='student')?'selected':'';?>>Студент</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="text-align:right">ФИО:</td>
		<td>
			<input type="text" name="name" value="<?=e(Arr::get($values, 'name', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Регистрационный код:</td>
		<td>
			<input type="text" name="confirmcode" value="<?=e(Arr::get($values, 'confirmcode', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Примечание:</td>
		<td>
			<input type="text" name="note" value="<?=e(Arr::get($values, 'note', ''))?>">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="btnSubmit" value="<?=($mode=='edit')?'Сохранить':'Добавить'?> пользователя" style="width:235; height:45px;"></td>
	</tr>
</table>
</form>
<?}?>

<div>
<? foreach ($errors as $error) {?>
<p class="error_message"><?=$error?></p>
<?}?>
</div>

