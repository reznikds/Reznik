<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/2')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
<a href="/">Главная</a>
<a href="/admin/category.html">Управление категориями</a>
<a href="/admin/users/list.html" class="admin-menu-active">Управление пользователями</a>
<a href="/admin/users/upload.html">Загрузка пользователей</a>
</p>

<h2>Админка/Управление пользователями</h2>

<div class="filter" style="width: 820px; margin-bottom:35px;">
	<form action="" method="post">
		<p><b>Фильтр</b></p>
		<p>
			<span>
				ФИО:
				<input style="width:175px" type="text" name="FIO" value="<?=Arr::get($filter, 'FIO');?>">
			</span>
			<span>
				Роль:
				<select name="role">
					<option value="">Все</option>
					<option value="student" <?=(Arr::get($filter, 'role') == 'student')?'selected':'';?>>Студент</option>
					<option value="teacher" <?=(Arr::get($filter, 'role') == 'teacher')?'selected':'';?>>Преподаватель</option>
				</select>
			</span>
			<span>
				Активность:
				<select name="isActive">
					<option value="">Все</option>
					<option value="yes" <?=(Arr::get($filter, 'isActive') == 'yes')?'selected':'';?>>Активен</option>
					<option value="no"  <?=(Arr::get($filter, 'isActive') == 'no' )?'selected':'';?>>Неактивен</option>
				</select>
			</span>
			<span>
				Примечание:
				<select name="note" style="width:150px;">
					<option value="">Все</option>
<? foreach ($notes as $item) {?>
					<option value="<?=$item['note']?>" <?=(Arr::get($filter, 'note') == $item['note'])?'selected':'';?>><?=$item['note']?></option>
<?}?>
				</select>
			</span>
		</p>
		<p style="padding-top:20px;"><input type="submit" name="btnFilter" value="Применить" style="width:130px; height:25px;"></p>
	</form>
</div>

<div class="pic">
	<a href="/admin/users/add.html">
		<img src="/img/add.png" alt="Добавить нового пользователя" title="Добавить нового пользователя">
		Добавить пользователя
	</a>
</div>

<form action="" method="post">
<table class="ed-materials">
	<tr>
		<th style="width:70px"><a href="javascript:toggleCheckboxes()">отметить всеx</a></th>
		<th>id</th>
		<th>ФИО</th>
		<th>Роль</th>
		<th>Код/email</th>
		<th>Примечание</th>
		<th style="width: 110px;">Действия</th>
	</tr>
<? foreach($users as $item) {?>
	<tr>
		<td style="text-align:center;"><input type="checkbox" name="cb[<?=$item->id?>]" value="on"></td>
		<td><?=$item->id?></td>
		<td><?=e($item->name)?></td>
<? $role = $item->getRole();?>
<? if ($role == 'admin') {?>
		<td>Администратор</td>
<?} elseif($role == 'teacher') {?>
		<td>Преподаватель</td>
<?}else{?>
		<td>Студент</td>
<?}?>

<? if ($item->confirmcode != '') {?>
		<td><?=e($item->confirmcode)?></td>
<?}else{?>
		<td><?=e($item->email)?></td>
<?}?>
		<td><?=e($item->note)?></td>
		<td>
			<div><a href="/admin/users/edit/<?=$item->id?>.html"><img src="/img/edit.png" alt="Редактировать пользователя" title="Редактировать пользователя"></a></div>
			<div><a href="/admin/users/delete/<?=$item->id?>.html" onClick="return window.confirm('Вы действительно хотите удалить этого пользователя ?');"><img src="/img/delete.png" alt="Удалить пользователя" title="Удалить пользователя"></a></div>
		</td>
	</tr>
<?}?>
</table>
<div style="margin-top: 10px;">
	<input type="submit" name="btnDelete" value="Удалить отмеченных" style="width:235; height:30px;" onClick="return window.confirm('Вы действительно хотите удалить отмеченных пользователей ?');">
</div>
</form>

<?php echo $pagination; ?>

<script type="text/javascript">

function toggleCheckboxes()
{
	var inputs = document.getElementsByTagName("input");
	var re = '^cb\\\[\\\d+\\\]$';
	
	for (var i=0; i<inputs.length; i++)
	{
		var input = inputs[i];
		
		if (input.name.search(re) != -1)
		{
			var state = input.checked;
			break;
		}
	}
	
	for (var i=0; i<inputs.length; i++)
	{
		var input = inputs[i];
		
		if (input.name.search(re) != -1) {
			input.checked = ! state;
		}
	}
}

</script>

