<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="subscribe-form" style="padding-left: 10px;">
<h3 class="mod-title">Рассылка</h3>
<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
	<?echo bitrix_sessid_post();?>
	<input type="hidden" name="PostAction" value="Add" />

	<table>
		<tr>
			<td>
				Имя/Компания:<br/>
				<input type="text" name="COMPANY" class=" text-input" size="20" value="" title="Введите название вашей компании, или ваше имя" />
			</td>
		</tr>
		<tr>
			<td>
				E-Mail:<br/>
				<input type="text" name="EMAIL" class=" text-input" size="20" value="<?=$arResult["EMAIL"]?>" title="<?=GetMessage("subscr_form_email_title")?>" />
			</td>
		</tr>
		<tr>
			<td>
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<label for="sf_RUB_ID_<?=$itemValue["ID"]?>">
				<input type="checkbox" name="RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> /> <?=$itemValue["NAME"]?>
				</label><br />
			<?endforeach;?>
			</td>
		</tr>
		<tr>
			<td class="subscribe-form-td">
			<span class="button-l">
				<span class="button-r">
					<input type="submit" class="button" name="OK" value="<?=GetMessage("subscr_form_button")?>" />
				</span>
			</span>
			</td>
		</tr>
	</table>
</form>
</div>
