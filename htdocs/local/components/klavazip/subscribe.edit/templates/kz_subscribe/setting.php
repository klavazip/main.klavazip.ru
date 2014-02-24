<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//setting section
//***********************************
?>
<form action="<?= POST_FORM_ACTION_URI?>" method="post">
<?echo bitrix_sessid_post();?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
<thead><tr><td colspan="2"><b><?echo GetMessage("subscr_title_settings")?></b></td></tr></thead>
<tr valign="top">
	<td width="40%">
		<p>Имя/Компания:<br />
		<input class=" text-input" type="text" name="COMPANY" value="<?=$arResult["SUBSCRIPTION"]["COMPANY"]!=""?$arResult["SUBSCRIPTION"]["COMPANY"]:$arResult["REQUEST"]["COMPANY"];?>" size="30" maxlength="255" /></p>
		<p><?echo GetMessage("subscr_email")?><br />
		<input class=" text-input" type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" size="30" maxlength="255" /></p>
		<p>
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<label><input type="checkbox" name="RUB_ID[]" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> /><?=$itemValue["NAME"]?></label><br />
		<?endforeach;?></p>
		<p><label><input type="radio" name="FORMAT" value="text"<?if($arResult["SUBSCRIPTION"]["FORMAT"] == "text") echo " checked"?> /><?echo GetMessage("subscr_text")?></label>&nbsp;/&nbsp;<label><input type="radio" name="FORMAT" value="html"<?if($arResult["SUBSCRIPTION"]["FORMAT"] == "html") echo " checked"?> />HTML</label></p>
	</td>
	<td width="60%">
		<p><?echo GetMessage("subscr_settings_note1")?></p>
		<p><?echo GetMessage("subscr_settings_note2")?></p>
	</td>
</tr>
<tfoot><tr><td colspan="2">
	<span class="button-l">
		<span class="button-r">
			<input class="button" type="submit" name="Save" value="<?echo ($arResult["ID"] > 0? GetMessage("subscr_upd"):GetMessage("subscr_add"))?>" />
		</span>
	</span>
</td></tr></tfoot>
</table>
<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
<?if($_REQUEST["register"] == "YES"):?>
	<input type="hidden" name="register" value="YES" />
<?endif;?>
<?if($_REQUEST["authorize"]=="YES"):?>
	<input type="hidden" name="authorize" value="YES" />
<?endif;?>
</form>
<br />