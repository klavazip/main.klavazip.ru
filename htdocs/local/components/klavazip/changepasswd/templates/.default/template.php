<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="boxMain">
	<h1>Смена пароля</h1>
	<div class="border_1"></div>
	<? 
	if( $_GET['aut'] == 'Y' && $USER->IsAuthorized())
	{
		?><div class="result-block">Вы успешно изменили пароль и авторизовались на сайте <a href="/">На главную</a> </div><?
	}	
	else
	{
		if( count($arResult['CH_ERROR']) > 0 )
		{
			?><div class="error-block"><?=implode('<br />', $arResult['CH_ERROR'])?></div><?
		}
			
			
		if( strlen($arResult['RESULT']) > 0 )
		{
			?><div class="result-block"><?=$arResult['RESULT']?> <a href="/">На главную</a> </div><?
		}
		else
		{
			?>
			<div class="boxMainCont"> 
			
				<div class="boxReg">
					<form name="bform" method="post" target="_top" action="">
						
						<label>Логин</label>
						<div class="blockInputform">
							<input type="text" name="USER_LOGIN" value="<?=htmlspecialchars($_GET['USER_LOGIN'])?>">
						</div>
						<div class="clear"></div>
						 
						<label>Контрольная строка</label>
						<div class="blockInputform">
							<input type="text" name="USER_CHECKWORD" autocomplete="off" value="<?=htmlspecialchars($_GET['USER_CHECKWORD'])?>">
						</div>
						<div class="clear"></div>
						
						<label>Новый пароль</label>
						<div class="blockInputform">
							<input type="password" name="USER_PASSWORD" value="">
							<p class="comment">Не менее 6 знаков</p>
						</div>
						<div class="clear"></div>
							
						<label>Еще раз пароль</label>
						<div class="blockInputform">
							<input type="password" name="USER_CONFIRM_PASSWORD" value="">
						</div>
						<div class="clear"></div>
					
						<input class="buttonBuy buttonAuto" type="submit" name="change_pwd" value="Изменить пароль">
					</form>
				</div>
			</div>
			<?
		}
	}	
	?>
</div>