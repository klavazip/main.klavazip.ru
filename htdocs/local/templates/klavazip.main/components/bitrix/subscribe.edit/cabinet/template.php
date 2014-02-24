<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="boxContTab">
	<div class="documentOrder">							
		<div class="boxFormTab pad">	
			<?
			if(count($arResult["MESSAGE"]) > 0)
			{
				?><div style="padding-left: 0" class="result-block"><?=implode('<br />', $arResult["MESSAGE"]) ?></div><?
			}
			if(count($arResult["ERROR"]) > 0)
			{
				?><div style="padding-left: 0" class="error-block"><?=implode('<br />', $arResult["ERROR"]) ?></div><?
			}
			?>
			<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
				<?=bitrix_sessid_post();?>
				<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
				<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
				<input type="hidden" name="RUB_ID[]" value="0" />													
				<div class="checkedBlock">
					<p class="textForm">Отметьте темы рассылок, которые вы хотели бы получать на электронную почту:</p>
					<? 
					foreach ($arResult["RUBRICS"] as $i => $ar_Value)
					{
						?>
						<div class="lineCheckDoc">
							<input type="checkbox" <?if($ar_Value["CHECKED"]) echo " checked"?> class="styled" name="RUB_ID[]" value="<?=$ar_Value['ID']?>"  id="check_<?=$i?>" />
							<label for="check_<?=$i?>"><?=$ar_Value['NAME']?></label>								
							<div class="clear"></div>
						</div>
						<? 						
					}
					?>
				</div>
				<div class="clear"></div>						
				<label>На адрес</label>
				<div class="blockInputform">
					<? $s_Mail = $arResult["SUBSCRIPTION"]["EMAIL"]!=""? $arResult["SUBSCRIPTION"]["EMAIL"]: $arResult["REQUEST"]["EMAIL"];?>
					<input type="text" onblur="if(this.value==''){this.value='<?=$s_Mail?>'}" onfocus="if(this.value=='<?=$s_Mail?>'){this.value=''}" value="<?=$s_Mail?>" name="EMAIL">
					<p class="comment">Пример: mail@klavazip.ru</p>
				</div>
				<div class="clear"></div>									
				<input type="submit" name="Save" value="Сохранить изменения" class="buttonBuy buttonAuto">	
			</form>
		</div>							
	</div>										
</div>