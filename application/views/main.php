<!--Вызов отображение формы логина-->
<?=Request::factory('loginform')->execute()?>
<!-- / Вызов отображение формы логина-->

<p>Приветствую Вас на сайте удаленного обучения. У нас Вы сможите скачать семинары, <b>вопросы и ответы к экзаменам</b>, всевозможные задания для обучения в <b>Электромеханическом техникуме</b>.</p>
<p><b>Разделы системы:</b></p>

<?
function displayTree(array $tree, $teachers, $level, $id=NULL)
{
$ulid = ($id!==NULL) ? " id=\"ulid{$id}\"" : '';
?>
<? if ($level == 0) {?>
<ul<?=$ulid?>>
<?}else{?>
<ul<?=$ulid?> style="display:none">
<?}?>
<? foreach ($tree as $node) { ?>
	<li class="subcategory">
		<a href="javascript:toggleNode(<?=$node['id']?>);" class="ajax-a"><?=e($node['name'])?></a>
<?
		$node_id = $node['id'];
		if (count($node['_tree']) > 0)
		{
			displayTree($node['_tree'], $teachers, $level+1, $node_id);
		}
		else
		{?>
<?
if (isset($teachers[$node_id])) {?>
	<ul id="ulid<?=$node_id?>" style="display:none">
<?foreach($teachers[$node_id] as $t) {?>
		<li class="subcategory">
			<a href="/public/shownode/<?=$node_id?>/<?=$t['teacher_id']?>.html"><?=$t['name']?></a>
		</li>
<?}?>
	</ul>
<?}else{?>
	<ul id="ulid<?=$node_id?>" style="display:none">
		<li class="subcategory">
			Нет материалов
		</li>
	</ul>
<?}}?>
	</li>
<?}?>
</ul>
<?}?>

<? displayTree($tree, $teachers, 0); ?>

<script type="text/javascript">

function toggleNode(id)
{
	var ul = document.getElementById('ulid'+id);
	var display = ul.style.display;
	
	if (display == 'none') {
		display = 'block';
	}
	else {
		display = 'none';
	}
	
	ul.style.display = display;
	
	return FALSE;
}

</script>


