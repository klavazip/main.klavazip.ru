<? 	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Купить запчасти и детали для ноутбука в Москве - интернет-магазин \"Клавазип\"");
	?>
	<div class="boxSpecialProduct">

		<div class="boxSpecialProductLink" id="boxSpecialProductLink">
			<a href="#" onclick="klava.toggleSpecBlockIndex(this, 'specialoffer'); return false;" class="itemProduct selected"><span>Спецпредложения</span></a>
			<a href="#" onclick="klava.toggleSpecBlockIndex(this, 'saleoffer'); return false;" class="itemProduct "><span>Новинки</span></a>
			<a href="#" onclick="klava.toggleSpecBlockIndex(this, 'newproduct'); return false;" class="itemProduct "><span>Хиты продаж</span></a>
		</div>
		
		<div class="clear"></div>
		
		<div class="blockProducts" id="boxSpecialProductBlock">
			<span id="index-block-specialoffer">
				<? $APPLICATION->IncludeComponent("klavazip:catalog.index", ".default", array('MODE' => 'SPECIALOFFER'), false);?>
			</span>
			<span id="index-block-saleoffer" style="display: none;"> 
				<? $APPLICATION->IncludeComponent("klavazip:catalog.index", ".default", array('MODE' => 'SALELEADER'), false);?>
			</span>
			<span id="index-block-newproduct" style="display: none;">
				<? $APPLICATION->IncludeComponent("klavazip:catalog.index", ".default", array('MODE' => 'SPECIALOFFER'), false);?>
			</span>
			<div class="clear"></div>					
		</div> 
	 
		<p class="mainText">
			Наш интернет-магазин предлагает вам качественные недорогие детали для ноутбука, которые могут потребоваться при ремонте компьютера. 
			Вы можете обратиться к менеджерам, позвонив по телефонам в Москве, Санкт-Петербурге или в регионах, чтобы уточнить условия работы 
			с нами, наличие товара и прочие вопросы. К сожалении, даже надежные компьютеры от именитого производителя и отдельные запчасти для 
			ноутбука приходят в негодность. А для устранения поломки требуются различные детали для ноутбука. Поэтому встает вопрос &ndash; 
			где купить запчасти для ноутбука, чтобы ремонт не обошелся слишком дорого? Ведь часто бывает, что купить новый ноутбук оказывается дешевле, 
			чем тратиться на ремонт. В нашем каталоге вы можете найти все необходимые детали для ноутбука любой модели и заказать их доставку по Москве 
			или в другой город России. При этом цены не станут для вас неприятным сюрпризом. Запчасти для ноутбука от компании «Клава» &ndash; выгодное 
			вложение в ремонт любимой техники.
		</p>
		
	
	
			<?$APPLICATION->IncludeComponent("bitrix:news.list", "news_on_main", array(
	"IBLOCK_TYPE" => "news",
	"IBLOCK_ID" => "1",
	"NEWS_COUNT" => "5",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "/news/#CODE#/",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"INCLUDE_SUBSECTIONS" => "Y",
	"PAGER_TEMPLATE" => ".default",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => "Новости",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "Y",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "N",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
		

		

		
		
	</div>
	<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");