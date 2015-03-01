<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?$APPLICATION->ShowTitle(false);?></title>
<?
$APPLICATION->ShowHead();	
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mask.plugin.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.checkbox.min.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.easing.1.3.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.flexslider.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/ui-slider.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/organictabs.jquery.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.reveal.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.jcarousel.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.radio.min.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.rating-2.0.min.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/cusel.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jScrollPane.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mousewheel.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/custom.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/cookie.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/main.js?0.1'); 
?>
<script type="text/javascript">
var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-39290877-1"]); var OpenStatParser={_params:{},_parsed:!1,_decode64:function(a){if("function"===typeof window.atob)return atob(a);var b,c,d,e,h,f=0,k=0;e="";var g=[];if(!a)return a;a+="";do b="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".indexOf(a.charAt(f++)),c="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".indexOf(a.charAt(f++)),e="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".indexOf(a.charAt(f++)),h="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".indexOf(a.charAt(f++)), d=b<<18|c<<12|e<<6|h,b=d>>16&255,c=d>>8&255,d&=255,64==e?g[k++]=String.fromCharCode(b):64==h?g[k++]=String.fromCharCode(b,c):g[k++]=String.fromCharCode(b,c,d);while(f<a.length);return e=g.join("")},_parse:function(){var a=window.location.search.substr(1).split("&");this._params={};for(var b=0;b<a.length;b++){var c=a[b].split("=");this._params[c[0]]=c[1]}this._parsed=!0},hasMarker:function(){this._parsed||this._parse();return"undefined"!==typeof this._params._openstat?!0:!1},buildCampaignParams:function(){if(!this.hasMarker())return!1; var a=this._decode64(this._params._openstat).split(";");return"utm_campaign="+a[3]+"&utm_source="+a[0]+"&utm_medium=cpc&utm_content="+a[2]}};if(OpenStatParser.hasMarker()){var campaignParams=OpenStatParser.buildCampaignParams();!1!==campaignParams&&_gaq.push(["_set","campaignParams",campaignParams])}_gaq.push(["_addOrganic","images.yandex.ru","text"]);_gaq.push(["_addOrganic","blogs.yandex.ru","text"]);_gaq.push(["_addOrganic","video.yandex.ru","text"]);_gaq.push(["_addOrganic","yandex.ru","query"]); _gaq.push(["_addOrganic","go.mail.ru","q"]);_gaq.push(["_addOrganic","mail.ru","q"]);_gaq.push(["_addOrganic","images.google.ru","q"]);_gaq.push(["_addOrganic","maps.google.ru","q"]);_gaq.push(["_addOrganic","google.com.ua","q"]);_gaq.push(["_addOrganic","rambler.ru","words"]);_gaq.push(["_addOrganic","nova.rambler.ru","query"]);_gaq.push(["_addOrganic","nova.rambler.ru","words"]);_gaq.push(["_addOrganic","gogo.ru","q"]);_gaq.push(["_addOrganic","nigma.ru","s"]); _gaq.push(["_addOrganic","search.qip.ru","query"]);_gaq.push(["_addOrganic","webalta.ru","q"]);_gaq.push(["_addOrganic","sm.aport.ru","r"]);_gaq.push(["_addOrganic","meta.ua","q"]);_gaq.push(["_addOrganic","search.bigmir.net","z"]);_gaq.push(["_addOrganic","search.i.ua","q"]);_gaq.push(["_addOrganic","index.online.ua","q"]);_gaq.push(["_addOrganic","web20.a.ua","query"]);_gaq.push(["_addOrganic","search.ukr.net","q"]);_gaq.push(["_addOrganic","search.ukr.net","search_query"]); _gaq.push(["_addOrganic","search.com.ua","q"]);_gaq.push(["_addOrganic","search.ua","q"]);_gaq.push(["_addOrganic","poisk.ru","text"]);_gaq.push(["_addOrganic","go.km.ru","sq"]);_gaq.push(["_addOrganic","liveinternet.ru","ask"]);_gaq.push(["_addOrganic","gde.ru","keywords"]);_gaq.push(["_addOrganic","affiliates.quintura.com","request"]);_gaq.push(["_addOrganic","akavita.by","z"]);_gaq.push(["_addOrganic","search.tut.by","query"]);_gaq.push(["_addOrganic","all.by","query"]); _gaq.push(["_setCampaignCookieTimeout",63072E6]);_gaq.push(["_trackPageview"]);(function(){var a=document.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"==document.location.protocol?"https://":"http://")+"stats.g.doubleclick.net/dc.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();
</script>
</head> 
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>

<?
# Compare page element interface
if( $APPLICATION->GetCurDir() == '/catalog/compare/' )
	$APPLICATION->IncludeComponent("klavazip:catalog.compare.fixed", "", array());


# Autorisation form
$APPLICATION->IncludeComponent("klavazip:aut.win", "", array());?>
		
<div class="boxTop">
	<div id="header">
		<div class="logo"><a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/img/logo.png" alt=""/></a></div>
		<div class="boxNav">
			<?
			# Top menu
			$APPLICATION->IncludeComponent("bitrix:menu", "horizontal", array(
				"ROOT_MENU_TYPE" 		=> "top",
				"MENU_CACHE_TYPE" 		=> "A",
				"MENU_CACHE_TIME" 		=> "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" 	=> array(),
				"MAX_LEVEL" 			=> "1",
				"CHILD_MENU_TYPE" 		=> "left",
				"USE_EXT" 				=> "Y",
				"DELAY" 				=> "N",
				"ALLOW_MULTI_SELECT" 	=> "N"
				),
				false
			);
			?>
		</div>
		<div class="boxName"><?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "template1", array());?></div>
		<div class="headerLine">
			<div class="phone">
				<div>Москва +7 (495) 666-29-17</div>
				<div>Санкт-Петербург  +7 (812) 339-25-45</div>
				<div><?/*Регионы 8 800 555 62 65*/?></div>
			</div>
			<div class="contact">
				<div class="mail"><i></i> <a href="mailto:info@klavazip.ru">info@klavazip.ru</a> </div>  	
				<div class="skype"><i></i> <a href="skype:klavazip?call">klavazip</a></div>
				<div class="icq"><i></i> 619-614-196</div>
			</div>
			<div class="boxTimeWork">Звонить в будни, с 10 до 19</div>
			<div class="btn-send">
				<span>Не дозвонились?</span>
				<a href="#" rel="сallback-send">Заказать обратный звонок</a>
			</div>
		</div>
	</div>
	<div id="content">
		<div class="contentLeft">
			<div class="boxCatalog mainPage">
				<a href="#" class="buttonCatalog active"><span>Каталог товаров</span></a>
				<?/*$APPLICATION->IncludeComponent("klavazip:left.menu.section", "", array());*/?>
				<?$APPLICATION->IncludeComponent("klavazip:menu.left", "vertical_multilevel", array(
	"ROOT_MENU_TYPE" => "left",
	"MENU_CACHE_TYPE" => "N",
	"MENU_CACHE_TIME" => "3600",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => array(
	),
	"MAX_LEVEL" => "4",
	"CHILD_MENU_TYPE" => "left",
	"USE_EXT" => "Y",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N"
	),
	false
);?>
			</div>
		</div>
		<div class="contentRight">
			<?
			# Search input field
	        $APPLICATION->IncludeComponent("bitrix:search.title", "header", array(
				"NUM_CATEGORIES" 	 		=> "1",
				"TOP_COUNT" 		 		=> "5",
				"ORDER" 			 		=> "date",
				"USE_LANGUAGE_GUESS" 		=> "Y",
				"CHECK_DATES" 		 		=> "N",
				"SHOW_OTHERS" 		 		=> "N",
				"PAGE" 				 		=> "#SITE_DIR#search/index.php",
				"CATEGORY_0_TITLE" 	 		=> "Поиск",
				"CATEGORY_0" 		 		=> array("iblock_catalog", "iblock_offers"),
				"CATEGORY_0_iblock_catalog" => array("8"),
				"CATEGORY_0_iblock_offers"  => array("all"),
				"SHOW_INPUT" 				=> "Y",
				"INPUT_ID" 					=> "title-search-input",
				"CONTAINER_ID" 				=> "search"
				),
				false
			);
			?>
		</div>
		<div class="clear"></div>	
		<?
		# Index page slider 
		if ($APPLICATION->GetCurDir() == '/')
		{
			?>
			<div class="boxSlider">
				<div class="sliderBottom">
					<div class="blockSlider">
						<div class="flexslider">
							<ul class="slides">
								<li onclick="location.href='/articles/o-batareyakh/'"><img src="<?=SITE_TEMPLATE_PATH?>/img/slide_1.jpg"  alt="" border="0px" /></li>	
								<? /*?>
								<li onclick="location.href='/articles/o-batareyakh/'"><img src="<?=SITE_TEMPLATE_PATH?>/img/slide_1.jpg"  alt="" border="0px" /></li>	
								<li onclick="location.href='/articles/o-batareyakh/'"><img src="<?=SITE_TEMPLATE_PATH?>/img/slide_1.jpg"  alt="" border="0px" /></li>
								<?*/?>							
							</ul>
						 </div>
					</div>
				</div>
			</div>	
			<div class="contentLeft">&nbsp;</div>
			<div class="contentRight">
			<?
		}
		else 
		{
			$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array("START_FROM" => "1", "PATH" => "", "SITE_ID" 	 => "-"), false);
		}