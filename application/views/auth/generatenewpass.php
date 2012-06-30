<p style="text-align:center">Восстановление пароля.</p>
<? isset($errors) or $errors = array()?>
<? foreach($errors as $error) {?>
<p style="color:red; text-align:center"><?=$error?></p>
<?}?>
<? if(isset($success)){?>
<p style="color:green; text-align:center">Проверьте вашу эл. почту.</p>
<?}?>