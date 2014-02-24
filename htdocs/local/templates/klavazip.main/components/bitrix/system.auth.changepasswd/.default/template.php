<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>




<div class="boxMain">
	<h1>Смена пароля</h1>
	<div class="border_1"></div>

	<div class="boxMainCont"> 
		
		<div class="boxReg">
			<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
				
				<label>Логин</label>
				<div class="blockInputform">
					<input type="text" name="USER_LOGIN" value="<?=$arResult["LAST_LOGIN"]?>">
				</div>
				<div class="clear"></div>
				 
				<label>Контрольная строка</label>
				<div class="blockInputform">
					<input type="text" name="USER_CHECKWORD" value="<?=$arResult["USER_CHECKWORD"]?>">
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
				
				

			
				<input class="buttonBuy" type="submit" name="change_pwd" value="Изменить пароль">
			</form>
		</div>
	</div>
</div> 

