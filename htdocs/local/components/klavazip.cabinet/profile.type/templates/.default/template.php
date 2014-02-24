<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="boxContTab">
	<div class="documentOrder">							
		<div class="boxFormBallance">
			<? 
			if($arResult['STATUS'])
			{
				?><div style="padding-left: 0" class="result-block">Данный успешно сохранены</div><?
			}
			?>
			<form action="" name="check" method="post">									
				<div class="blockInputform">
					<div class="lineRadio">
						<input type="radio" <?=($arResult['CURRENT_TYPE'] == 1) ? 'checked="checked"' : ''?>  class="styledRadio radioButton" value="1" name="USER_TYPE" />
						<label for="radioButton_1">Физическое лицо</label>								
						<div class="clear"></div>
					</div>
					<div class="lineRadio">
						<input type="radio" <?=($arResult['CURRENT_TYPE'] == 2) ? 'checked="checked"' : ''?> class="styledRadio radioButton" name="USER_TYPE" value="2" />
						<label for="radioButton_2">Юридическое лицо</label>								
						<div class="clear"></div>
					</div> 
					<div class="lineRadio">
						<input type="radio" <?=($arResult['CURRENT_TYPE'] == 3) ? 'checked="checked"' : ''?> class="styledRadio radioButton" name="USER_TYPE" value="3" />
						<label for="radioButton_3">ИП</label>								
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
				<input type="submit" value="Сохранить изменения" name="submit" class="buttonBuy">
			</form>
		</div>				
	</div>										
</div>