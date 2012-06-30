<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/1')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
<a href="/">Главная</a>
<a href="/materials.html" style="padding-right:0">Учебные материалы</a> > <a href="/materials/stats.html" class="admin-menu-active">Статистика</a>
<a href="/account.html">Авторизационные данные</a>
</p>
<h2>Личный кабинет пользователя/Управление учебными материалами/Статистика</h2>
<p><b>Название материала:</b> <?=e($materialName)?></p>
<div class="filter">
	<form action="" method="post">
		<p><b>Фильтр</b></p>
		<p>ФИО <input type="text" name="FIO" value="<?=Arr::get($filter, 'FIO');?>"> Диапазон дат <input style="width: 75px" type="text" name="dateFrom" value="<?=Arr::get($filter, 'dateFrom', '01.01.2012');?>"> &mdash; <input style="width: 75px" type="text" name="dateTo" value="<?=Arr::get($filter, 'dateTo', '31.12.2035');?>"></p>
		<p><input type="submit" name="btnFilter" value="Применить" style="width:130px; height:25px;"></p>
	</form>
</div>
<div>
<? foreach ($errors as $error) {?>
<p class="error_message"><?=$error?></p>
<?}?>
</div>
<p><b>Количество скачиваний:</b> <?=e($count)?></p>
<table class="ed-materials" style="width:450px;">
	<tr>
		<th>ФИО</th>
		<th>Дата скачивания</th>
	</tr>
<? foreach ($stats as $item) {?>
	<tr>
		<td style="text-align:left"><?=e($item->user->name)?></td>
		<td><?=date('d.m.Y', $item->ctime)?></td>
	</tr>
<?}?>
</table>
	
	