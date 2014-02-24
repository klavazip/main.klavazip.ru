<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
	<div id="<?echo $CONTAINER_ID?>">
	<? /* <form action="<?echo $arResult["FORM_ACTION"]?>"> */ ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td style="width:100%;padding-right: 50px;"><input class="text-input" data-placeholder="<?=GetMessage('T_SEARCH_TITLE_PLACEHOLDER');?>" id="<?echo $INPUT_ID?>" type="text" name="q" value="<?=isset($_GET['q'])?$_GET['q']:''?>" style="width:100%"  size="40" maxlength="50" autocomplete="off" /></td><td><input name="s" class="search-button" type="submit" value="" /></td></tr>
</table>
	<? /* </form> */ ?>
	</div>
<?endif?>
<script type="text/javascript">
var jsControl = new JCTitleSearch({
	//'WAIT_IMAGE': '/bitrix/themes/.default/images/wait.gif',
	'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
	'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
	'INPUT_ID': '<?echo $INPUT_ID?>',
	'MIN_QUERY_LEN': 2
});
</script>
