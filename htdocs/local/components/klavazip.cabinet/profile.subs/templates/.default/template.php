<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="boxContTab">
	<div class="documentOrder">							
		<div class="boxFormTab pad">								
			<form method="post" name="ballance" action="">											
				<div class="checkedBlock">
					<p class="textForm">Отметьте темы рассылок, которые вы хотели бы получать на электронную почту:</p>
					<? 
					foreach ($arResult['ITEMS'] as $ar_Value)
					{
						?>
						<div class="lineCheckDoc">
							<input type="checkbox" <?=($ar_Value['SELECTED'] == 'Y') ? 'checked="checked"' : ''?> class="styled" name="SUB[]" value="<?=$ar_Value['ID']?>"  id="check_<?=$ar_Value['ID']?>" />
							<label for="check_1"><?=$ar_Value['NAME']?></label>								
							<div class="clear"></div>
						</div>
						<? 						
					}
					?>
				</div>
				<div class="clear"></div>						
				<label>На адрес</label>
				<div class="blockInputform">
					<input type="text" onblur="if(this.value==''){this.value='<?=$arResult['USER_MAIL']?>'}" onfocus="if(this.value=='<?=$arResult['USER_MAIL']?>'){this.value=''}" value="<?=$arResult['USER_MAIL']?>" name="USER_MAIL">
					<p class="comment">Пример: mail@klavazip.ru</p>
				</div>
				<div class="clear"></div>									
				<input type="submit" name="submit" value="Сохранить изменения" class="buttonBuy buttonAuto">			 							
			</form>
		</div>							
	</div>										
</div>