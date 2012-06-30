<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/0')->execute()?>
<!-- / Вызов отображение формы логина-->

<h2 class="materials-title">
<a href="/">Главная</a>
/<? foreach ($path as $item) {?>
<?=$item['name']?>/<?}?><?=$teachername?>
</h2>

<table class="ed-materials">
	<tr>
		<th style="width: 110px;">Дата публикации</th>
		<th>Дисциплина</th>
		<th>Название</th>
		<th style="width: 110px;">Ссылка</th
	</tr>
<? foreach ($materials as $item) {?>
	<tr>
		<td><?=date('d.m.Y', $item['ctime'])?></td>
		<td><?=e($item['subjectName'])?></td>
		<td style="text-align:left"><?=e($item['materialName'])?></td>
		<td>
			<a href="/public/download/<?=$item['id']?>.html">
<? if ($item['access'] == 'all') {?>
				<img src="/img/link.png" alt="Материал в свободном доступе" title="Материал в свободном доступе">
<?}else{?>
				<img src="/img/link_auth.png" alt="Только для авторизованных пользователей" title="Только для авторизованных пользователей">
<?}?>
			</a>
		</td>
	</tr>
<?}?>
</table>
<?php echo $pagination; ?>
