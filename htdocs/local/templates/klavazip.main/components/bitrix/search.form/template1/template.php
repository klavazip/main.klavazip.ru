<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<form class="search-form" action="<?=$arResult["FORM_ACTION"]?>">
<?if($arParams["USE_SUGGEST"] === "Y"):?><?$APPLICATION->IncludeComponent(
				"bitrix:search.suggest.input",
				"",
				array(
					"NAME" => "q",
					"VALUE" => "",
					"INPUT_SIZE" => 15,
					"DROPDOWN_SIZE" => 10,
				),
				$component, array("HIDE_ICONS" => "Y")
			);?><?else:?><input type="text" name="q" class="search-field" value="" size="15" maxlength="50" /><?endif;?>
		<input name="s" class="search-submit" type="submit" value="" />
		
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('[name="q"]').addClass('search-field');
})
</script>

