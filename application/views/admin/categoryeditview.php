<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/2')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
<a href="/">Главная</a>
<a href="/admin/category.html" class="admin-menu-active">Управление категориями</a>
<a href="/admin/users/list.html">Управление пользователями</a>
<a href="/admin/users/upload.html">Загрузка пользователей</a>
</p>

<h2>Админка/Управление категориями</h2>

<? if(isset($errors)){?>
<?foreach($errors as $item){?>
<p style="color:red"><?=$item?></p>
<?}?>
<?}?>

<p>Добавление категории:</p>
<div>
	<form method="post" action="">
		<select name="parentId">
			<option value="">/</option>
<?foreach($categories as $item) {?>
			<option value="<?=$item['id']?>"><?=str_repeat('&nbsp;', 4*$item['level']).e($item['name'])?></option>
<?}?>
		</select>
		<input type="text" name="categoryName">
		<input type="submit" value="Создать" name="btnSubmitAdd">
	</form>
</div>

<p>Редактирование названия категории:</p>
<div>
	<form method="post" action="">
		<select name="parentId">
<?foreach($categories as $item) {?>
			<option value="<?=$item['id']?>"><?=str_repeat('&nbsp;', 4*$item['level']).e($item['name'])?></option>
<?}?>
		</select>
		<input type="text" name="categoryName">
		<input type="submit" value="Сохранить" name="btnSubmitChange">
	</form>
</div>

<p>Удаление категории:</p>
<div>
	<form method="post" action="">
		<select name="catDeleteId">
			<?foreach($categories as $item) {?>
			<option value="<?=$item['id']?>"><?=str_repeat('&nbsp;', 4*$item['level']).e($item['name'])?></option>
			<?}?>
		</select>
		<input type="submit" value="Удалить" name="btnSubmitDel" onClick="return window.confirm('Вы действительно хотите удалить эту категорию ?');">
	</form>
</div>
