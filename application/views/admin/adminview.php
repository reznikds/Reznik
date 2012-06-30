<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/2')->execute()?>
<!-- / Вызов отображение формы логина-->

<p><b>Функции администратора:</b></p>
<ul>
	<li><a href="/admin/category.html">Управление категориями</a></li>
	<li><a href="/admin/users/list.html">Управление пользователями</a></li>
	<li><a href="/admin/users/upload.html">Загрузка пользователей</a></li>
</ul>

