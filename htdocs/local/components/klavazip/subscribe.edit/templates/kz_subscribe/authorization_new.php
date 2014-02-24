<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["ALLOW_ANONYMOUS"]=="Y" && $_REQUEST["authorize"]<>"YES" && $_REQUEST["register"]<>"YES"):?>
	<b><?echo GetMessage("subscr_title_auth2")?></b><br/>
	<p><?echo GetMessage("adm_auth1")?> <a href="<?echo $arResult["FORM_ACTION"]?>?authorize=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_auth2")?></a>.</p>
	<?if($arResult["ALLOW_REGISTER"]=="Y"):?>
	<p><?echo GetMessage("adm_reg1")?> <a href="<?echo $arResult["FORM_ACTION"]?>?register=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_reg2")?></a>.</p>
	<?endif;?>
	<p><i><?echo GetMessage("adm_reg_text")?></i></p>
<?elseif(!$USER->IsAuthorized() && ($arResult["ALLOW_ANONYMOUS"]=="N" || $_REQUEST["authorize"]=="YES" || $_REQUEST["register"]=="YES")):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:system.auth.form",
		"",
		Array(
			"REGISTER_URL" => "/auth/",
			"PROFILE_URL" => "/auth/",
			"SHOW_ERRORS" => "N"
		),
	false
	);?>
<?endif;?>
