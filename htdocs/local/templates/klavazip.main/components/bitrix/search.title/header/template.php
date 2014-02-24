<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N")
{
	?>
	<div id="<?=$CONTAINER_ID?>" class="boxSearch">
		<form action="<?echo $arResult["FORM_ACTION"]?>" method="get">
			<label class="over" for="inputSearch">Введите название товара, например iPhone 5</label>
			<input  
				size="40" 
				maxlength="50" 
				autocomplete="off"
				id="inputSearch"
				type="text"  
				name="q" 
				value="<?=(isset($_GET['q']) && strlen($_GET['q']) > 0) ? htmlspecialchars($_GET['q']) : ''?>" 
				<? /*?>
				onfocus="if(this.value=='Если хотите найти нужный товар введите iPhone 5'){this.value=''}" 
				onblur="if(this.value==''){this.value='Если хотите найти нужный товар введите iPhone 5'}"
				<? */ ?> 
			/>
			<input type="submit" name="s" value="Найти"/>
		</form>		
		<div class="clear"></div>			
	</div>
	<?
}
?>
<script type="text/javascript">
	var jsControl = new JCTitleSearch({
		'AJAX_PAGE' 	: '<?=POST_FORM_ACTION_URI?>',
		'CONTAINER_ID'	: '<?=$CONTAINER_ID?>',
		'INPUT_ID'		: '<?=$INPUT_ID?>',
		'MIN_QUERY_LEN'	: 0
	});
</script>