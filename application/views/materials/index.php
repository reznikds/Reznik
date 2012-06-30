<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/1')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
<a href="/">Главная</a>
<a href="/materials.html" class="admin-menu-active">Учебные материалы</a>
<a href="/account.html">Авторизационные данные</a>
</p>
<h2>Личный кабинет пользователя/Управление учебными материалами</h2>

<? if ($isAdmin) {?>
<div class="filter">
	<form action="" method="post">
		<p><b>Фильтр по ФИО преподавателя</b></p>
		<p>ФИО <input type="text" name="FIO" value="<?=Arr::get($filter, 'FIO');?>"></p>
		<p><input type="submit" name="btnFilter" value="Применить" style="width:130px; height:25px;"></p>
	</form>
</div>
<?}?>

<div class="pic">
	<a href="/materials/add.html">
		<img src="/img/add.png" alt="Добавить новый материал" title="Добавить новый материал">
		Добавить материал
	</a>
	<div></div>
</div>
<table class="ed-materials">
	<tr>
		<th style="width: 110px;">Дата публикации</th>
<? if ($isAdmin) {?>
		<th>Преподаватель</th>
<?}?>
		<th>Дисциплина</th>
		<th>Кому предназначен</th>
		<th>Название</th>
		<th style="width: 110px;">Ссылка</th>
		<th style="width: 110px;">Действия</th>
	</tr>
<? foreach($materials as $item) {?>
<? if ($item['isLeaf']) {?>
	<tr>
<?}else{?>
	<tr style="background-color:#fcc;">
<?}?>
		<td><?=date('d.m.Y', $item['ctime'])?></td>
<? if ($isAdmin) {?>
		<td><?=e($item['name'])?></td>
<?}?>
		<td><?=e($item['subjectName'])?></td>
		<td>
<? foreach ($item['path'] as $ipath) {?>
			<?=$ipath['name']?><br>
<?}?>
		</td>
		<td style="text-align:left"><?=e($item['materialName'])?></td>
		<td>
			<a target="_blank" href="<?=$item['link']?>">
<? if ($item['access'] == 'all') {?>
				<img src="/img/link.png" alt="Материал в свободном доступе" title="Материал в свободном доступе">
<?}else{?>
				<img src="/img/link_auth.png" alt="Только для авторизованных пользователей" title="Только для авторизованных пользователей">
<?}?>
			</a>
		</td>
		<td>
			<div><a href="/materials/stats/<?=$item['id']?>.html"><img src="/img/statistics.png" alt="Статистика скачивания" title="Статистика скачивания"></a></div>
			<div><a href="/materials/edit/<?=$item['id']?>.html"><img src="/img/edit.png" alt="Редактировать материал" title="Редактировать материал"></a></div>
			<div><a href="/materials/delete/<?=$item['id']?>.html" onClick="return window.confirm('Вы действительно хотите удалить этот материал ?');"><img src="/img/delete.png" alt="Удалить материал" title="Удалить материал"></a></div>
		</td>
	</tr>
<?}?>
</table>

<?php echo $pagination; ?>

<p>&nbsp;</p>
<div class="help">
	<p>Красным цветом помечены материалы, находящиеся в отсутствующей или неправильной категории. Зайдите в редактирование красного материала и выберите правильную категорию.</p>
	<p>Если красным ничего не помечено, значит в ваших материалах пролем нет и все хорошо.</p>
</div>

