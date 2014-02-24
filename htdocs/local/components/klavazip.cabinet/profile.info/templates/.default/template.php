<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="boxContTab">

	<? 
	if(count($arResult['ERROR']) > 0)
	{
		?><div class="error-block"><?= implode('<br />', $arResult['ERROR'])?></div><?
	}
	?>

	<div class="documentOrder">							
		<div class="boxFormTab pad">								
			<form method="post" name="user-info" id="user-info" action="/cabinet/profile/info/">											
				<label>Фамилия </label>
				<div class="blockInputform">
					<input 
						type="text" 
						onblur="if(this.value==''){ this.value='<?=$arResult['USER']['LAST_NAME']?>'}" 
						onfocus="if(this.value=='<?=$arResult['USER']['LAST_NAME']?>'){this.value=''}" 
						value="<?=$arResult['USER']['LAST_NAME']?>" 
						name="LAST_NAME"
					>
				</div>
				<div class="clear"></div>		
				<label>Имя</label>
				<div class="blockInputform">
					<input 
						type="text" 
						onblur="if(this.value==''){this.value='<?=$arResult['USER']['NAME']?>'}" 
						onfocus="if(this.value=='<?=$arResult['USER']['NAME']?>'){this.value=''}" 
						value="<?=$arResult['USER']['NAME']?>" 
						name="NAME"
					>
				</div>
				<div class="clear"></div>		
				<label>Отчество </label>
				<div class="blockInputform">
					<input 
						type="text" 
						onblur="if(this.value==''){this.value='<?=$arResult['USER']['SECOND_NAME']?>'}" 
						onfocus="if(this.value=='<?=$arResult['USER']['SECOND_NAME']?>'){this.value=''}" 
						value="<?=$arResult['USER']['SECOND_NAME']?>" 
						name="SECOND_NAME"
					>
				</div>
				<div class="clear"></div>		
				<label>Электронная почта </label>
				<div class="blockInputform">
					<input 
						type="text" 
						onblur="if(this.value==''){this.value='<?=$arResult['USER']['EMAIL']?>'}" 
						onfocus="if(this.value=='<?=$arResult['USER']['EMAIL']?>'){this.value=''}" 
						value="<?=$arResult['USER']['EMAIL']?>"  
						name="EMAIL"
					>	
					<div class="order-error">Не верно введен Email</div>
					<p class="comment">Пример: mail@klavazip.ru</p>									
				</div>
				<div class="clear"></div>
				<label>Контактный телефон</label>
				<div class="blockInputform"> 
					<input 
						type="text" 
						class="index js-phone-mask" 
						onblur="if(this.value==''){this.value='<?=$arResult['USER']['PERSONAL_PHONE']?>'}" 
						onfocus="if(this.value=='<?=$arResult['USER']['PERSONAL_PHONE']?>'){this.value=''}" 
						value="<?=$arResult['USER']['PERSONAL_PHONE']?>" 
						name="PERSONAL_PHONE"
					>										
				</div>
				<div class="clear"></div>		
				<input type="submit" onclick="klava.cabinet.submitUserInfoForm()" value="Сохранить изменения" name="buttonBuy" class="buttonBuy buttonAuto">										
			</form>
		</div>							
	</div>										
</div>