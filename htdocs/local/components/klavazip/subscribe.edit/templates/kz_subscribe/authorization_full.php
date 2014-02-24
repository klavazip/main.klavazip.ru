<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//******************************************
//subscription authorization form
//******************************************
?>
<form action="<?echo $arResult["FORM_ACTION"].($_SERVER["QUERY_STRING"]<>""? "?".htmlspecialchars($_SERVER["QUERY_STRING"]):"")?>" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
<thead><tr><td colspan="2"><b><?echo GetMessage("subscr_auth_sect_title")?></b></td></tr></thead>
<tr valign="top">
	<td width="30%">
		<p>e-mail<br /><input class=" text-input" type="text" name="sf_EMAIL" size="20" value="<?echo $arResult["REQUEST"]["EMAIL"];?>" title="<?echo GetMessage("subscr_auth_email")?>" /></p>
		<p><?echo GetMessage("subscr_auth_pass")?><br /><input type="password" class=" text-input" name="AUTH_PASS" size="20" value="" title="<?echo GetMessage("subscr_auth_pass_title")?>" /></p>
	</td>
	<td width="70%">
		<?echo GetMessage("adm_auth_note")?>
	</td>
</tr>
<tfoot><tr><td colspan="2">
	<span class="button-l">
		<span class="button-r">
			<input class="button" type="submit" name="autorize" value="<?echo GetMessage("adm_auth_butt")?>" />
		</span>
	</span>
</td></tr></tfoot>
</table>
<input type="hidden" name="action" value="authorize" />
<?echo bitrix_sessid_post();?>
</form>
<br />

<form action="<?=$arResult["FORM_ACTION"]?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
<thead><tr><td colspan="2"><b><?echo GetMessage("subscr_pass_title")?></b></td></tr></thead>
<tr valign="top">
	<td width="30%">
		<p>e-mail<br /><input type="text" class=" text-input" name="sf_EMAIL" size="20" value="<?echo $arResult["REQUEST"]["EMAIL"];?>" title="<?echo GetMessage("subscr_auth_email")?>" /></p>
	</td>
	<td width="70%">
		<?echo GetMessage("subscr_pass_note")?>
	</td>
</tr>
<tfoot><tr><td colspan="2">
	<span class="button-l">
		<span class="button-r">
			<input type="submit" class="button" name="sendpassword" value="<?echo GetMessage("subscr_pass_button")?>" />
		</span>
	</span>
</td></tr></tfoot>
</table>
<input type="hidden" name="action" value="sendpassword" />
<?echo bitrix_sessid_post();?>
</form>
<br />
