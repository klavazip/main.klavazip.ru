<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//*************************************
//show confirmation form
//*************************************
?>
<form action="<?=$arResult["FORM_ACTION"]?>" method="get">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
<thead><tr><td colspan="2"><b><?echo GetMessage("subscr_title_confirm")?></b></td></tr></thead>
<tr valign="top">
	<td width="40%">
		<p><?echo GetMessage("subscr_conf_code")?><br />
		<input class=" text-input" type="text" name="CONFIRM_CODE" value="<?echo $arResult["REQUEST"]["CONFIRM_CODE"];?>" size="20" /></p>
	</td>
	<td width="60%">
		<?echo GetMessage("subscr_conf_note1")?> <a title="<?echo GetMessage("adm_send_code")?>" href="<?echo $arResult["FORM_ACTION"]?>?ID=<?echo $arResult["ID"]?>&amp;action=sendcode&amp;<?echo bitrix_sessid_get()?>"><?echo GetMessage("subscr_conf_note2")?></a>.
	</td>
</tr>
<tfoot><tr><td colspan="2">
<span class="button-l">
	<span class="button-r">
		<input type="submit" class="button" name="confirm" value="<?echo GetMessage("subscr_conf_button")?>" />
	</span>
</span>
</td></tr></tfoot>
</table>
<input type="hidden" name="ID" value="<?echo $arResult["ID"];?>" />
<?echo bitrix_sessid_post();?>
</form>
<br />
