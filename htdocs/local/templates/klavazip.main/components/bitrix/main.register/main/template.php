<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="boxMain">
	<h1>Регистрация</h1>
	<div class="border_1"></div>
	
	<? 
	if (count($arResult["ERRORS"]) > 0)
	{
		?>
		<div class="error-block">
			<?
			foreach ($arResult["ERRORS"] as $key => $error)
				if (intval($key) == 0 && $key !== 0) 
					$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);
		
			ShowError(implode("<br />", $arResult["ERRORS"]));
			?>
		</div>
		<?
	}
	
	if( intval($arResult['VALUES']['USER_ID']) > 0 )
	{
		LocalRedirect('/aut/registration/?registr=Y');
	}
	
	
	if( $_GET['registr'] == 'Y' )
	{
		?><div class="result-block">Вы успешно зарегистрированны</div><?
	}
	else
	{
		?>
		<div class="boxMainCont">
			<div class="boxReg">
				<form  method="post" action="<?=POST_FORM_ACTION_URI?>"  enctype="multipart/form-data" name="regForm">
					
					<label>Логин</label>
					<div class="blockInputform">
						<input type="text" name="REGISTER[LOGIN]" value="">
						<p class="comment">Не менее 3 знаков</p>
					</div>
					<div class="clear"></div>
					
					<label>Электронная почта</label>
					<div class="blockInputform">
						<input type="text" name="REGISTER[EMAIL]"  value="">
						<p class="comment">Пример: mail@klavazip.ru</p>
					</div>
					<div class="clear"></div>
					
					<label>Пароль</label>
					<div class="blockInputform">
						<input type="password" name="REGISTER[PASSWORD]" value="">
						<p class="comment">Не менее 6 знаков</p>
					</div>
					<div class="clear"></div>
					
					<label>Еще раз пароль</label>
					<div class="blockInputform">
						<input type="password" name="REGISTER[CONFIRM_PASSWORD]" value="">
					</div>
					<div class="clear"></div>
					
					<label>Перетащите ползунок</label>
					<div class="blockInputform">
						<div class="QapTcha"></div>
					</div>
					<div class="clear"></div>
					
					<input class="buttonBuy buttonAuto" type="submit" name="register_submit_button" value="Зарегистрироваться">
				</form>
			</div>
		</div>
		<?
	}
	?>
</div> 
