<!--Вызов отображение формы логина-->
<?=Request::factory('loginform/1')->execute()?>
<!-- / Вызов отображение формы логина-->

<p id="admin-menu">
<a href="/">Главная</a>
<a href="/materials.html" class="admin-menu-active">Учебные материалы</a>
<a href="/account.html">Авторизационные данные</a>
</p>
<? if ($mode == 'edit') {?>
<h2>Личный кабинет пользователя/Редактирование материала</h2>
<?}else{?>
<h2>Личный кабинет пользователя/Новый материал</h2>
<?}?>

<form enctype="multipart/form-data" action="" method="post">
<table class="materials-add">
<? if ($isAdmin) {?>
	<tr>
		<td style="text-align:right; width:200px;">Преподаватель:</td>
		<td>
			<select name="teacher_id">
				<option value=""></option>
<?foreach($teachers as $item) {
$selected = (Arr::get($values, 'teacher_id') == $item->id)?'selected':'';
?>
				<option value="<?=$item->id?>" <?=$selected?>><?=e($item->name)?></option>
<?}?>
			</select>
		</td>
	</tr>
<?}?>
	<tr>
		<td style="text-align:right; width:200px;">Расположение материала:</td>
		<td>
			<select name="node_id">
<?foreach($tree as $item) {
$leaf = ($item['right_key'] - $item['left_key'] == 1);
$disabled = (!$leaf)?'disabled ':'';
$selected = (Arr::get($values, 'node_id', '') == $item['id'])?'selected ':'';?>
				<option <?=$disabled?>value="<?=$item['id']?>"<?=$selected?>><?=str_repeat('&nbsp;', 6*$item['level']).e($item['name'])?></option>
<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<td style="text-align:right">Название предмета:</td>
		<td>
			<input type="text" name="subjectName" value="<?=e(Arr::get($values, 'subjectName', ''))?>">
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top; text-align:right">Название материала:</td>
		<td>
			<textarea name="materialName" cols="50" rows="5"><?=e(Arr::get($values, 'materialName', ''))?></textarea>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top; text-align:right">Доступ:</td>
		<td>
			<p><input type="radio" name="access" value="all" style="width:20px"  id="radio1"<?=(Arr::get($values, 'access', '') == 'all')?' checked':''?>> <label for="radio1">Свободный доступ</label></p>
			<p><input type="radio" name="access" value="auth" style="width:20px" id="radio2"<?=(Arr::get($values, 'access', '') == 'auth')?' checked':''?>> <label for="radio2">Только для авторизованных пользователей</label></p>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top; text-align:right">Учебный материал:</td>
		<td>
			<p><b>Вы можете загрузить новый файл</b></p>
<? if ($values['filename']) {?>
			<a target="_blank" href="/files/<?=$values['filename']?>">Скачать загруженный ранее</a><br>
<?}?>
			<input type="file" name="materialFile" value="Выбрать файл для загрузки...">
			<p>Разрешенные типы файлов: тектстовые файлы, архивы, видео, аудио.</p>
			<p>Размер файла не должен превышать 50 МБайт.</p>
			<p><b>Или указать ссылку на сторонний ресурс</b></p>
<? if ($values['url']) {?>
			<a target="_blank" href="<?=$values['url']?>">Скачать загруженный ранее</a><br>
<?}?>
			<input type="text" name="url" value="<?=e(Arr::get($values, 'url', ''))?>" style="width:419px">
			<p>Например: <i>http://www.site.com/uploads/1.doc</i></p>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="btnSubmit" value="<?=($mode=='edit')?'Сохранить':'Добавить'?> материал" style="width:235; height:45px;"></td>
	</tr>
</table>
</form>

<div>
<? foreach ($errors as $error) {?>
<p class="error_message"><?=$error?></p>
<?}?>
</div>

