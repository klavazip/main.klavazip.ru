<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
$iIndex = rand();
$arResult["FOLDERS"] = array();
for ($ii = 1; $ii <= $arResult["SystemFolder"]; $ii++)
{
	if (($arResult["version"] == 2 && $ii == 2) || $ii == $arParams["FID"])
		continue;
	$arResult["FOLDERS"][] = array("ID" => $ii, "TITLE" => GetMessage("PM_FOLDER_ID_".$ii));
}
if (is_array($arResult["UserFolder"]) && !empty($arResult["UserFolder"]))
{
	foreach ($arResult["UserFolder"] as $res)
	{
		if ($res["ID"] = $arParams["FID"])
			continue;
		$arResult["FOLDERS"][] = array("ID" => $res["ID"], "TITLE" => $res["TITLE"]);
	}
}
/********************************************************************
				/Input params
********************************************************************/
if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-error">
	<div class="forum-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></div>
</div>
<?
endif;
if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-success">
	<div class="forum-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></div>
</div>
<?
endif;
?>
<div style="float:right;">
	<div class="out"><div class="in" style="width:<?=$arResult["count"]?>%">&nbsp;</div></div>
	<div class="out1"><div class="in1"><?=GetMessage("PM_POST_FULLY")." ".$arResult["count"]?>%</div></div>
</div>
<div class="forum-clear-float"></div>

<a name="postform"></a>
<div class="forum-header-box">
	<div class="forum-header-options">
		<span class="forum-option-folder"><a href="<?=$arResult["pm_list"]?>"><?=$arResult["FolderName"]?></a></span>
	</div>
	<div class="forum-header-title"><span><?=$arResult["MESSAGE"]["POST_SUBJ"];?></span></div>
</div>

<div class="forum-block-container">
	<div class="forum-block-outer">
		<div class="forum-block-inner">
			<table cellspacing="0" class="forum-table forum-pmessages">
			<thead>
 				<tr class="forum-row-first forum-row-odd">
					<th class="forum-first-column"><?=GetMessage("PM_FROM")?>:</th>
<?/*<pre><?var_dump($arResult["MESSAGE"])?></pre>*/?><?$arResult["MESSAGE"]["AUTHOR_NAME"]=$arResult["MESSAGE"]["AUTHOR_LOGIN"];$arResult["MESSAGE"]["RECIPIENT_NAME"]=$arResult["MESSAGE"]["RECIPIENT_LOGIN"];?>
					<td class="forum-last-column"><a href="<?=$arResult["MESSAGE"]["AUTHOR_LINK"]?>"><?=$arResult["MESSAGE"]["AUTHOR_NAME"]?></a></td>
				</tr>
				<tr class="forum-row-even">
					<th><?=GetMessage("PM_TO")?>:</th>
					<td><a href="<?=$arResult["MESSAGE"]["RECIPIENT_LINK"]?>"><?=$arResult["MESSAGE"]["RECIPIENT_NAME"]?></a></td>
				</tr>
				<tr class="forum-row-odd">
					<th><?=GetMessage("PM_DATA")?>:</th>
					<td><?=$arResult["MESSAGE"]["POST_DATE"]?></td>
				</tr>
			</thead>
			<tbody>
				<tr class="forum-last-first forum-row-even">
					<td colspan="2" class="forum-pmessage-text">
						<?=$arResult["MESSAGE"]["POST_MESSAGE"]?>
<?
		if (($arResult["MESSAGE"]["REQUEST_IS_READ"] == "Y") && ($arParams["version"]==2)):
?>
		<div class="forum-pm-notification">
			<?=GetMessage("PM_REQUEST_NOTIF")?>
			<form action="<?=$APPLICATION->GetCurPageParam()?>" method="get" name="PMESSAGE" class="forum-form" >
				<input type="hidden" name="FID" value="<?=$arResult["FID"]?>" />
				<input type="hidden" name="MID" value="<?=$arResult["MID"]?>" />
				<input type="hidden" name="PAGE_NAME" value="pm_read" />
				<input type="hidden" name="action" value="send_notification" />
				<?=bitrix_sessid_post()?>
				<input type="submit" class="forum-mess-button" value="<?=GetMessage("PM_SEND_NOTIF")?>" />
			</form>
		</div>
<?
		endif;
?>
					</td>
				</tr>
			<tbody>
			<tfoot>
				<tr>
					<td colspan="2" class="forum-column-footer">
						<div class="forum-footer-inner">
							<div class="forum-pmessage-navigation">
								<span class="forum-footer-option forum-pmessage-prev forum-footer-option-first">
								<?
							if (!empty($arResult["MESSAGE_PREV"])):
									?><a href="<?=$arResult["MESSAGE_PREV"]["MESSAGE_LINK"]?>"><?=GetMessage("P_PREV")?></a><?
							else :
									?><?=GetMessage("P_PREV")?><?
							endif;
								?></span>
								<span class="forum-pmessage-current"></span>
								<span class="forum-footer-option forum-pmessage-next forum-footer-option-last"><?
							if (!empty($arResult["MESSAGE_NEXT"])):
									?><a href="<?=$arResult["MESSAGE_NEXT"]["MESSAGE_LINK"]?>"><?=GetMessage("P_NEXT")?></a><?
							else :
									?><?=GetMessage("P_NEXT")?><?
							endif;
								?></span>
							</div>
							<span class="forum-footer-option forum-pmessage-action forum-footer-option-first">
		<form class="forum-form" action="<?=POST_FORM_ACTION_URI?>" method="POST">
			<input type="hidden" name="FID" value="<?=$arResult["FID"]?>" />
			<input type="hidden" name="MID" value="<?=$arResult["MID"]?>" />
			<input type="hidden" name="PAGE_NAME" value="pm_read" />
			<?=bitrix_sessid_post()?>
			<select name="action">
				<option value="reply" <?=($_REQUEST["action"] == "reply" ? " selected='selected'" : "")?>><?=GetMessage("PM_ACT_REPLY")?></option>
<? if ($arParams['FID'] == 3): // sent ?>
				<option value="edit" <?=($_REQUEST["action"] == "edit" ? " selected='selected'" : "")?>><?=GetMessage("PM_ACT_EDIT")?></option> 
<? endif;?>
				<option value="delete" <?=($_REQUEST["action"] == "delete" ? " selected='selected'" : "")?>><?=GetMessage("PM_ACT_DELETE")?></option>
			</select>
			<input type="submit" value="OK" />
		</form>							
							</span>
<?/*?>
							<span class="forum-footer-option forum-pmessage-copy">
		<form class="forum-form" action="<?=POST_FORM_ACTION_URI?>" method="POST">
			<input type="hidden" name="FID" value="<?=$arResult["FID"]?>" />
			<input type="hidden" name="MID" value="<?=$arResult["MID"]?>" />
			<input type="hidden" name="PAGE_NAME" value="pm_read" />
			<input type="hidden" name="action" value="copy" />
			<?=bitrix_sessid_post()?>
			<?=GetMessage("PM_ACT_COPY")?> <?=GetMessage("PM_IN")?>:
			<select name="folder_id">
			<?
			foreach ($arResult["FOLDERS"] as $res)
			{
				?><option value="<?=$res["ID"]?>" <?=(($_REQUEST["action"] == "copy" && $res["ID"] == $_REQUEST["folder_id"]) 
					? " selected='selected'" : "")?>><?=$res["TITLE"]?></option><?
			}
			?>
			</select>
			<input type="submit" value="OK" />
		</form>
							</span>
<?*/?>
							<span class="forum-footer-option forum-pmessage-move forum-footer-option-last">
		<form class="forum-form" action="<?=POST_FORM_ACTION_URI?>" method="POST">
			<input type="hidden" name="FID" value="<?=$arResult["FID"]?>" />
			<input type="hidden" name="MID" value="<?=$arResult["MID"]?>" />
			<input type="hidden" name="PAGE_NAME" value="pm_read" />
			<input type="hidden" name="action" value="move" />
			<?=bitrix_sessid_post()?>
			<span><?=GetMessage("PM_ACT_MOVE")?> <?=GetMessage("PM_IN")?>:&nbsp;</span>
			<select name="folder_id">
			<?
			foreach ($arResult["FOLDERS"] as $res)
			{
				?><option value="<?=$res["ID"]?>" <?=(($_REQUEST["action"] == "move" && $res["ID"] == $_REQUEST["folder_id"]) 
					? " selected='selected'" : "")?>><?=$res["TITLE"]?></option><?
			}
			?></select>
			<input type="submit" value="OK" />
		</form>
							</span>
						</div>
					</td>
				</tr>
			</tfoot>
			</table>
		</div>
	</div>
</div>
