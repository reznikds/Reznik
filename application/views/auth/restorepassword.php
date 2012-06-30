<p>Для того, чтобы вспомнить пароль, необходимо ввести адрес эл. почты, который вы указали при
регистрации. Если адрес эл. почты будет обнаружен в базе системы, мы отправим на него ссылку для восстановления пароля.
После перехода по ссылке следующим сообщением вы получите новый пароль.</p>
<? isset($errors) or $errors = array()?>
<? foreach($errors as $error) {?>
<p style="color:red; text-align:center"><?=$error?></p>
<?}?>
<? if(isset($success)){?>
<p style="color:green; text-align:center">Проверьте вашу эл. почту.</p>
<?}?>
<form action="" method="post">
	<table class="rememberpassform">
		<tr>
			<th colspan="2" ><b>Вспоминаем пароль</b></th>
		</tr>
		<tr>
			<td style="text-align: right">Эл. почта:</td>
			<td><input type="text" name="email" id="email" style="width: 165px;"></td>
		</tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Вспомнить" style="width:170px; height:30px" name="btnSubmit"></th>
	</table>
</form>

<script type="text/javascript">
	function runajax()
	{
		var email = $("#email").val();
		
		$.ajax({
		  type: "POST",
		  data: "email=" + email,
		  url: "/ajax/emailunique",
		  dataType: "json",
		  success: function(data)
		  {
			if(!data.result)
			{
				$("#trueimg").css('display','inline');
				$("#falseimg").css('display','none');
			}
			else
			{
				$("#falseimg").css('display','inline');
				$("#trueimg").css('display','none');
			}
		  }
		})
	}
	
	$(document).ready(function(){
		$("#email").blur(runajax);
	});
</script>
