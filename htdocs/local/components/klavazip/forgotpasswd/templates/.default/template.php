<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="boxMain">
	<h1>Форма восстановления пароля</h1>
	<div class="border_1"></div>

	<? 
	if( strlen($arResult['CH_RESULT']) > 0 )
	{
		?><div class="result-block"><?=$arResult['CH_RESULT']?></div><?
	}

	if( count($arResult['CH_ERROR']) > 0 )
	{
		?><div class="error-block"><?=implode('<br />', $arResult['CH_ERROR'])?></div><?
	}
	?>
	
	<div class="boxMainCont">
		
		Если вы забыли пароль, введите E-Mail.
		Контрольная строка для смены пароля, будут высланы вам по E-Mail. <br /><br />
	 
		<div class="boxReg">
			<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
				<label>Электронная почта</label>
				<div class="blockInputform">
					<input type="text" name="USER_EMAIL" value="<?=htmlspecialchars($_POST['USER_EMAIL'])?>">
					<p class="comment">Пример: mail@klavazip.ru</p>
				</div>
				<div class="clear"></div>
				<input class="buttonBuy buttonAuto" type="submit" name="send_account_info" value="Восстановить пароль">
			</form>
		</div>
	</div>
</div> 