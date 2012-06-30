<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/2')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
<a href="/">Главная</a>
<a href="/admin/category.html">Управление категориями</a>
<a href="/admin/users/list.html">Управление пользователями</a>
<a href="/admin/users/upload.html" class="admin-menu-active">Загрузка пользователей</a>
</p>

<h2>Админка/Загрузка пользователей</h2>

<?foreach($errors as $error) {?>
<p style="color:red"><?=$error?></p>
<?}?>
<? if(isset($success)) {?>
	<p style="color:green">Загрузка прошла успешно</p>
<?}?>

<p>Вы можете загрузить сразу много пользователей при помощи csv-файла соответствующего формата.</p>
<form enctype="multipart/form-data" action="" method="post">
	<p style="text-align: center">Выберите файл со списком пользователей: <input type="file" name="codesFile" value="Выбрать файл для загрузки..." ></p>
	<p style="text-align:center;"><input type="submit" name="btnSubmit" value="Обработать" style="width:200px; height:45px;"></p>
</form>
<p>&nbsp;</p>
<div class="help">
	<p style="text-align: center"><b>Справка по формату файла, содержащего список пользователей</b></p>
	<p>Формат файла: csv, кодировка: UTF-8, разделитель: ; (точка с запятой)</p>
	<p>1-й столбец &mdash; регистрационный код</p>
	<p>2-й столбец &mdash; роль (admin;teacher;student)</p>
	<p>3-й столбец &mdash; примечание (необязательно)</p>
	<p>&nbsp;</p>
	<p><b>пример:</b></p>
	<p>123456789;admin;</p>
	<p>987654321;student;2012-й год;</p>
</div>
