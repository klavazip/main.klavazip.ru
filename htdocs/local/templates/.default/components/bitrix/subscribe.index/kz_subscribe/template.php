<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<h4><?echo GetMessage("SUBSCR_NEW_TITLE")?></h4>
<p><?echo GetMessage("SUBSCR_NEW_NOTE")?></p><br/>

<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
	<?echo bitrix_sessid_post();?>
	<input type="hidden" name="PostAction" value="Add" />
	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
		<input type="checkbox" name="RUB_ID[]" id="sf_RUB_ID_<?=$itemID?>" value="<?=$itemValue["ID"]?>" checked />&nbsp;
		<label for="sf_RUB_ID_<?=$itemID?>"><?=$itemValue["NAME"]?></label><br/>
	<?endforeach;?>
	<br/>
	<input type="text" class=" text-input" name="EMAIL" size="20" value="<?=$arResult["EMAIL"]?>" title="<?echo GetMessage("SUBSCR_EMAIL_TITLE")?>" />
	<br/><br/>
	<span class="button-l">
		<span class="button-r">
			<input class="button" type="submit" value="<?echo GetMessage("SUBSCR_BUTTON")?>" />
		</span>
	</span>
</form>
<br />

<table width="100%">
	<tr>
		<td width="33%">
			<h4><?echo GetMessage("SUBSCR_EDIT_TITLE")?></h4>
			<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
				<?echo bitrix_sessid_post();?>
				<input type="hidden" name="action" value="authorize" />
				e-mail<br />
				<input class=" text-input" type="text" name="sf_EMAIL" size="20" value="<?=$arResult["EMAIL"]?>" title="<?echo GetMessage("SUBSCR_EMAIL_TITLE")?>" />
				<?if ( $arResult["SHOW_PASS"]=="Y"):?>
				<br/><?echo GetMessage("SUBSCR_EDIT_PASS")?>:<br />
				<input class=" text-input" type="password" name="AUTH_PASS" size="20" value="" title="<?echo GetMessage("SUBSCR_EDIT_PASS_TITLE")?>" />
				<?endif;?>
				<br/><br/>
				<span class="button-l">
					<span class="button-r">
						<input class="button" type="submit" value="<?echo GetMessage("SUBSCR_EDIT_BUTTON")?>" />
					</span>
				</span>
			</form>
			<br />
		</td>
		<td width="33%">
			<h4><?echo GetMessage("SUBSCR_PASS_TITLE")?></h4>
			<form action="<?=$arResult["FORM_ACTION"]?>" method="get">
				<?echo bitrix_sessid_post();?>
				<input type="hidden" name="action" value="sendpassword" />
				e-mail<br />
				<input class=" text-input" type="text" name="sf_EMAIL" size="20" value="<?=$arResult["EMAIL"]?>" title="<?echo GetMessage("SUBSCR_EMAIL_TITLE")?>" />
				<br/><br/>
				<span class="button-l">
					<span class="button-r">
						<input class="button" type="submit" value="<?echo GetMessage("SUBSCR_PASS_BUTTON")?>" />
					</span>
				</span>
			</form>
		</td>
		<td width="33%">
			<h4><?echo GetMessage("SUBSCR_UNSUBSCRIBE_TITLE")?></h4>
			<form action="<?=$arResult["FORM_ACTION"]?>" method="get">
				<?echo bitrix_sessid_post();?>
				<input type="hidden" name="action" value="unsubscribe" />
				e-mail<br />
				<input class=" text-input" type="text" name="sf_EMAIL" size="20" value="<?=$arResult["EMAIL"]?>" title="<?echo GetMessage("SUBSCR_EMAIL_TITLE")?>" />
				<?if( $arResult["SHOW_PASS"]=="Y"):?>
				<br/><?echo GetMessage("SUBSCR_EDIT_PASS")?><br/>
				<input class=" text-input" type="password" name="AUTH_PASS" size="20" value="" title="<?echo GetMessage("SUBSCR_EDIT_PASS_TITLE")?>" />
				<?endif;?>
				<br/><br/>
				<span class="button-l">
					<span class="button-r">
						<input class="button" type="submit" value="<?echo GetMessage("SUBSCR_EDIT_BUTTON")?>" />
					</span>
				</span>
			</form>
		</td>
	</tr>
</table>


