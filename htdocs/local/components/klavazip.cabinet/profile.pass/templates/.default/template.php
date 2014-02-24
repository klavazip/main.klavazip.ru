<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="boxContTab">
	<?
	if(count($arResult['ERROR']) > 0 )
	{
		?><div class="error-block"><?=implode('<br />', $arResult['ERROR'])?></div><?
	}
	
	if(strlen($arResult['RESULT']) > 0)
	{
		?><div class="result-block"><?=$arResult['RESULT']?></div><?
	}	
	?>
	<div class="documentOrder">							
		<div class="boxFormTab pad">								
			<form method="post" name="user-pass" action="">	
				<?=bitrix_sessid_post()?>										
				<label>Старый пароль </label>
				<div class="blockInputform">
					<input type="password" value="<?=$_POST['OLD_PASS']?>" name="OLD_PASS">
				</div>
				<div class="clear"></div>		
				<label>Новый пароль</label>
				<div class="blockInputform">
					<input type="password" value="<?=$_POST['NEW_PASS']?>" name="NEW_PASS">
				</div>
				<div class="clear"></div>		
				<label>Еще раз новый пароль </label>
				<div class="blockInputform">
					<input type="password" value="<?=$_POST['CONFIRM_NEW_PASS']?>" name="CONFIRM_NEW_PASS">
				</div>
				<div class="clear"></div>									
				<input type="submit" value="Сохранить изменения" class="buttonBuy buttonAuto" name="buttonBuy">										
			</form>
		</div>							
	</div>										
</div>