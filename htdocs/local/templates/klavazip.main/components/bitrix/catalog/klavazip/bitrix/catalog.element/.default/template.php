<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$APPLICATION->IncludeComponent("bestrank:recently.visited", ".default", array(
    "IBLOCK_ID" => "6",
    "TITLE" => "Просмотренные товары",
    "COUNT" => "10",
    "LINK_TEMPLATE" => "id",
    "FRIENDLY_CATALOG" => "/catalog/",
    "FRIENDLY_LINK_TEMPLATE" => "#SECTION_ID#/#ELEMENT_ID#/",
    "LIST_PROPERTY_CODE" => array(
	0 => "",
	1 => "",
    ),
    "IBLOCK_PRICE_CODE" => array(
    ),
    "DISPLAY_IMG_WIDTH" => "",
    "DISPLAY_IMG_HEIGHT" => ""
	), false
);
?>
<?php
global $USER;
if ($USER->IsAuthorized()) {
    $uid = $USER->GetId();
    $name = $USER->GetFullName();
    $name.=' (ID = ' . $uid . ')';
    $res123 = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "9", "%NAME" => '(ID = ' . $uid . ')'), false, false, array('PROPERTY_user', 'PROPERTY_items', 'ID')); //
    if ($ar123 = $res123->Fetch()) {
	//echo'<pre>';var_dump($ar123);echo'</pre>';
	$itemsidar = explode(',', $ar123['PROPERTY_ITEMS_VALUE']);
	$itemsidar = array_unique($itemsidar);
	if ($key = array_search($arResult['ID'], $itemsidar))
	    unset($itemsidar[$key]);
	array_unshift($itemsidar, $arResult['ID']);

	//var_dump($itemsidar);
	$itemsidar = array_slice($itemsidar, 0, 99);

	$el = new CIBlockElement;
	$add =
		$el->Update(
		$ar123['ID'], array(
	    'PROPERTY_VALUES' => array(
		'items' => implode(',', $itemsidar)
	    )
		)
	);
    }
    else {
	$el = new CIBlockElement;
	$add = $el->Add(array('IBLOCK_ID' => 9, 'PROPERTY_VALUES' => array('items' => $arResult['ID'], 'user' => $uid), 'NAME' => $name, 'ACTIVE' => 'Y'));
    }
    //if(isset($add)) { if($add) echo 'ок'; else echo 'Error: '.$el->LAST_ERROR; }
} else {
    if (isset($_SESSION['looki'])) {
	$_SESSION['looki'] = array_unique($_SESSION['looki']);
	if ($key = array_search($arResult['ID'], $_SESSION['looki']))
	    unset($_SESSION['looki'][$key]);
	array_unshift($_SESSION['looki'], $arResult['ID']);

	/* if(!in_array($arResult['ID'],$_SESSION['looki']))
	  $_SESSION['looki'][]=$arResult['ID']; */
    }
    else
	$_SESSION['looki'] = array($arResult['ID']);
    //var_dump($_SESSION['looki']);
}
?>
<?php
$arBasketItemsDelay2 = array();
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "DELAY" => "Y"), false, false, array("ID", "DELAY", "PRODUCT_ID"));
while ($arItems = $dbBasketItems->Fetch())
    $arBasketItemsDelay2[] = $arItems["PRODUCT_ID"];
?>

<?php
if (isset($_SESSION["CATALOG_COMPARE_LIST"][8]["ITEMS"]))
    $array_compare_keys = array_keys($_SESSION["CATALOG_COMPARE_LIST"][8]["ITEMS"]);
?>

<div class="content-bg">
    <?
//echo "<pre>";print_r($arResult);echo "</pre>";
//echo "<pre>";print_r($_SESSION);echo "</pre>";
//echo "<pre>";print_r($_SESSION["DELAY_LIST"]);echo "</pre>";
    ?>

    <table class="item-table" >
	<tr>
	    <td class="item-left">
		<div class="item-left">
		    <div class="item-pic relative">
			<div class="labels" style="z-index:100">
			    <?php
			    $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "8", "ID" => $arResult['ID']), false, false, array("PROPERTY_SALELEADER", "PROPERTY_NEWPRODUCT", "DETAIL_PICTURE"));
			    if ($ar_res = $res->GetNext()) {
				?>
				<?= $ar_res["PROPERTY_SALELEADER_VALUE"] == NULL ? '' : '<div class="label-hit"></div>' ?>
				<?= $ar_res["PROPERTY_NEWPRODUCT_VALUE"] == NULL ? '' : '<div class="label-new"></div>'; ?><?php }
			    ?>
			</div>
			<?php
			if (isset($ar_res['DETAIL_PICTURE']))
			    if (strlen($ar_res['DETAIL_PICTURE']) > 0)
				$arResult['DETAIL_PICTURE_350']['SRC'] = CFile::GetPath($ar_res['DETAIL_PICTURE']);
			?>
			<? $srcpic = is_array($arResult['DETAIL_PICTURE_350']) ? $arResult['DETAIL_PICTURE_350']['SRC'] : SITE_TEMPLATE_PATH . '/img/no-pic-big.jpg' ?>
			<a href="<?= $srcpic ?>" title="<?= (strlen($arResult["DETAIL_PICTURE"]["DESCRIPTION"]) > 0 ? $arResult["DETAIL_PICTURE"]["DESCRIPTION"] : $arResult["NAME"]) ?>">
                <span class="zoom"></span>
                <img src="<?= $srcpic ?>" width="249" alt="<?= $arResult['DETAIL_PICTURE_350']['SRC'] ?  $arResult["NAME"] : 'no-photo' ?>" title="<?= $arResult['DETAIL_PICTURE_350']['SRC'] ?  $arResult["NAME"] : 'no-photo' ?>" />
            </a>
		    </div>

		    <?
		    $RESS = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "8", "ID" => $arResult['ID']), false, false, array('PROPERTY_CML2_ARTICLE'));
		    if ($ressEl = $RESS->Fetch()) {
			?><p style='text-align:center'><span class="artikul small">Артикул: <?= $ressEl['PROPERTY_CML2_ARTICLE_VALUE'] ?></span></p><? } ?>
		    
            <div class="item-pics">
			<?
			if (count($arResult["MORE_PHOTO"]) > 0):
			    $i = 0;
			    foreach ($arResult["MORE_PHOTO"] as $PHOTO):
				$i++;
				if ($i % 3 == 0) {
				    echo '<a href="' . $PHOTO["SRC"] . '" rel="nofollow" class="noMargin"  title="'.$ressEl['NAME'].'"  ><img src="' . $PHOTO["SRC_PREVIEW"] . '" width="70" alt="'.$ressEl['NAME'].'" title="'.$ressEl['NAME'].'" /></a>';
				    echo '<div class="clearfix"></div>';
				} else {
				    echo '<a href="'.$PHOTO["SRC"].'" rel="nofollow" title="'.$ressEl['NAME'].'"  ><img src="' . $PHOTO["SRC_PREVIEW"] . '" width="70" alt="'.$ressEl['NAME'].'" title="'.$ressEl['NAME'].'" /></a>';
				}
			    endforeach;
			endif;
			?>
			<div class="clearfix"></div>
		    </div>



                    <div class="clearfix"></div>
		    <!--noindex-->
			<div class="social-links">
			    <table>
				<tr>
				    <td style="width:40px"><iframe src="//www.facebook.com/plugins/like.php?href=<? echo urlencode($_SERVER["REQUEST_URI"]) ?>&amp;send=false&amp;layout=button_count&amp;width=125&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21"  style="border:none; overflow:hidden; width:102px; height:20px;" ></iframe></td>
				    <td style='padding-bottom:10px'>
					   <!-- VK Widget Like -->
                       <div id="vk_like"></div>
					</td>
				</tr>
				<tr>
				    <td>
					<a href="https://twitter.com/share" class="twitter-share-button" data-via="KlavaZip" data-size="small">Tweet</a>
                    <script>!function(d, s, id) {
                    	var js, fjs = d.getElementsByTagName(s)[0];
                    	if (!d.getElementById(id)) {
                    	    js = d.createElement(s);
                    	    js.id = id;
                    	    js.src = "//platform.twitter.com/widgets.js";
                    	    fjs.parentNode.insertBefore(js, fjs);
                    	}
                        }(document, "script", "twitter-wjs");</script>
				    </td>
				    <td>
					
					<script type="text/javascript">
                    // Поместите этот тег туда, где должна отображаться кнопка +1. 
                    document.write('<div style="overflow: hidden; width: 39px; height: 25px; padding: 0;"><g:plusone annotation="inline">&nbsp;</g:plusone></div>');
                    //Поместите этот вызов функции отображения в соответствующее место.
            	    window.___gcfg = {lang: 'ru'};
            
            	    (function() {
            		    var po = document.createElement('script');
            		    po.type = 'text/javascript';
            		    po.async = true;
            		    po.src = 'https://apis.google.com/js/plusone.js';
            		    var s = document.getElementsByTagName('script')[0];
            		    s.parentNode.insertBefore(po, s);
            		})();
					</script>
				    </td>
				</tr>
			    </table>


						<!--<script type="text/javascript">
						// Google!!
						  window.___gcfg = {lang: 'ru'};

						  (function() {
						    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						    po.src = 'https://apis.google.com/js/plusone.js';
						    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
						  })();
						</script>-->

			</div>
            <!--/noindex-->
			<div class="item-links">
			    <? /* <p><span><img src="<?=SITE_TEMPLATE_PATH?>/img/icon-print.png" width="11" height="11" alt=" "></span>
			      <a href="<?=htmlspecialchars($APPLICATION->GetCurUri("print=Y"));?>" target="_blank" class="print">Версия для печати</a></p> */ ?>
			    <p><span><img src="<?= SITE_TEMPLATE_PATH ?>/img/icon-question.png" width="6" height="11" alt=" " /></span> <a href="#" class="openQuestion" data-product-name="<?=$arResult["NAME"];?>" >Задать вопрос по товару</a></p>
			    <?
			    if (isset($_SESSION["DELAY_LIST"])) {
				if (!in_array($arResult['ID'], $_SESSION["DELAY_LIST"])) {
				    //echo '<p><span><img src="'.SITE_TEMPLATE_PATH.'/img/icon-delay.png" width="5" height="10" alt=" "></span> <a class="catalog-item-delay" href="/catalog'.$arResult['ADD_URL'].'">Отложить товар</a></p>';
				}
			    } else {
				//echo '<p><span><img src="'.SITE_TEMPLATE_PATH.'/img/icon-delay.png" width="5" height="10" alt=" "></span> <a class="catalog-item-delay" href="/catalog'.$arResult['ADD_URL'].'">Отложить товар</a></p>';
			    }
			    ?>



			    <?
			    if (!in_array($arResult['ID'], $array_compare_keys)) {
				echo '<p><span><img src="' . SITE_TEMPLATE_PATH . '/img/icon-weight.png" width="13" height="12" alt=" " /></span>
                <a href="/catalog' . $arResult['COMPARE_URL'] . '" class="catalog-item-compare">Сравнить</a></p>';
			    }
			    ?>
			    <?php
//var_dump($arBasketItemsDelay2);
			    if (!in_array($arResult['ID'], $arBasketItemsDelay2)) {
				?><p>
    				<span><img src="<?= SITE_TEMPLATE_PATH ?>/img/icon-heart.png" width="11" height="10" alt=" " /></span>
    				<a class="wish-list" href="/?action=shelve&amp;id=<?= $arResult["ID"] ?>">В желаемые</a>
    			    </p><?php } ?>
			</div>
		    
                </div>
            </td>
            <td class="item-right">
		<h1 class="item-title"><?= $arResult["NAME"] ?></h1>
		<? $prices = getPricesByItemId($arResult["ID"]) ?>
                <div class="prices">
		    <div class="price-block price1">
			<span class="price-type">розничная цена</span>
			<div class="price"><?= $prices[0] ?> <span class="rubl">&#8399;</span></div>
		    </div>
		    <div class="price-block price2">
			<span class="price-type">при заказе &gt;10000 <span class="rubl">&#8399;</span></span>
			<div class="price"><?= $prices[1] ?> <span class="rubl">&#8399;</span></div>
		    </div>
		    <div class="price-block price2">
			<span class="price-type">при заказе &gt;50000 <span class="rubl">&#8399;</span></span>
			<div class="price"><?= $prices[2] ?> <span class="rubl">&#8399;</span></div>
		    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="nalichie">
		    <p class="big">Наличие</p>
		    <?php if ($arResult["CATALOG_QUANTITY"] > 0) { ?><span class="q-type q-yes">Всего: <?= $arResult["CATALOG_QUANTITY"] ?> шт</span><?php } else {
			?><p><span class="q-type q-x">Нет в наличии на складах</span></p><?php } ?>
                    <div class="clearfix"></div>
		    Более полную информацию о  <?= $arResult["NAME"] ?> вы можете узнать у менеджера магазина.
                </div>
                <div class="item-buy">
		    <?
		    if ($arResult["CATALOG_QUANTITY"] > 0) {
			echo '  <span class="button-l"><span class="button-r"><a class="button catalog-item-buy" href="' . $arResult["ADD_URL"] . '#buy">купить</a></span></span>
								                    <span class="item-quantity-switch">
								                        <input class="q-input" type="text" maxlength="" name="" value="1" size="3">
								                        <span class="switch-top"></span>
								                        <span class="switch-bot"></span>
								                    </span>';
		    } else {
			?>
    		    <a href="#" style="float:left;line-height:13px" data-name="<?= $arResult['NAME'] ?>" data-id="<?= $arResult['ID'] ?>" class="notify-link pink italic">
    			<span class="borderLink">уведомить меня о наличии</span>
    		    </a>
		    <? } ?>


                </div>
                <div class="item-tabs-a">
        		    <a href="#" class="active-tab-link"><h2>Характеристики <?= $arResult["NAME"] ?></h2></a> 
                    <a href="#" class="noMargin"><span>Аналоги</span></a>
                </div>
                <div class="item-tabs">
		    <div class="item-tab active-item-tab">
			<? // Вывод свойств?>
			<? foreach ($arResult["PROPERTIES"] as $value): ?>
			    <?
			    //$masHar = array("Диагональ экрана","Разрешение экрана","Варианты подсветок экрана","Коннектор на шлейфе","Поверхность ","Расположение коннектора","Производитель","Тип BGA Чипа","Состояние BGA Чипа","Цвет","Раскладка клавиатуры");
			    $masHar = array("diagonal", "data_code", "emkost", "kollichesto_jacheek", "naprjazhenie", "resolution", "light", "connector", "surface", "location_connector", "manufactur", "type_bga", "state_bga", "color", "keyboard", "volume_video", "type_video", "frequency", "with_memory");
			    if (in_array($value["CODE"], $masHar) && strlen(trim($value["VALUE"])) > 0):
				?>
				<p><span class="prop-name"><?= $value["NAME"] ?></span> <span class="prop-val"><?= $value["VALUE"] ?></span></p>
				<?
			    endif;
			endforeach;
			?>
                        <p><span class="prop-name">Совместимо с:</span>
			    <span class="prop-val">
				<?
				$j = 0;
				if (is_array($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"])):
				    foreach ($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"] as $elemRecmmend):
					?>
					<?
					$itemRecomOb = CIBlockElement::GetList(array(), array("ID" => $elemRecmmend), false, false, array("DETAIL_PAGE_URL", "NAME"));
					$itemRecom = $itemRecomOb->Fetch();
					?>
					<? if ($j < 5): ?>

	    				<a href="<?= $itemRecom["DETAIL_PAGE_URL"] ?>"><?= $itemRecom["NAME"] ?></a><br>
					<? else: ?>
	    				<a href="#" class="openProps italic borderLink">показать полный список</a><br>
	    				<span class="hidden-props">
	    				    <a href="<?= $itemRecom["DETAIL_PAGE_URL"] ?>"><?= $itemRecom["NAME"] ?></a><br>
	    				</span>
					<? endif ?>
				    <? endforeach; ?>
				<? endif; ?>
                            </span>
                        </p>
                    </div>
                    <div class="item-tab">
			<?

			function getAnalog($DESCRIPTION, $value) {
			    $key = -1;
			    $analogs = array();
			    foreach ($DESCRIPTION as $k => $v)
				if (stripos($v, 'Аналог') !== false) {
				    $key = $k;
				    break;
				};
			    if ($key != -1)
				$analogs = explode(',', $value[$key]);
			    foreach ($analogs as $k => $v)
				if (!strlen(trim($v)))
				    unset($analogs[$k]);
			    return $analogs;
			}

			$analogs = array();
//ec($arResult["PROPERTIES"]["CML2_TRAITS"]);
			//аналоги этого товара
			if (is_array($arResult["PROPERTIES"]["CML2_TRAITS"]["VALUE"]))
			    $analogs = getAnalog($arResult["PROPERTIES"]["CML2_TRAITS"]["DESCRIPTION"], $arResult["PROPERTIES"]["CML2_TRAITS"]["VALUE"]);
//ec($analogs);
			//этот товар в аналогах других
			//$addAnal=array(); - ДЛЯ ПРОВЕРКИ
			//берем XML_ID этого товара
			$res = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arResult['IBLOCK_ID'], 'ID' => $arResult['ID']), false, false);
			while ($resAr = $res->Fetch())
			    $arResult['XML_ID'] = $resAr['XML_ID'];
			//var_dump($arResult['XML_ID']);
			//ищем его во всех товарах
			/* аналоги
			  $res=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$arResult['IBLOCK_ID']),false,false);
			  while($resAr=$res->GetNextElement())
			  if($resPAr=$resAr->GetProperties(array(),array('CODE'=>'CML2_TRAITS'))) {
			  $tempAnalogs=getAnalog( $resPAr["CML2_TRAITS"]["DESCRIPTION"], $resPAr["CML2_TRAITS"]["VALUE"]);
			  if(in_array($arResult['XML_ID'],$tempAnalogs))
			  {
			  $resFAr=$resAr->GetFields();
			  //$addAnal[]=$resFAr['XML_ID']; - ДЛЯ ПРОВЕРКИ
			  $analogs[]=$resFAr['XML_ID'];
			  }
			  }
			 */
			/* ?><pre><?var_dump($addAnal)?></pre><? - ДЛЯ ПРОВЕРКИ */

			if (count($analogs)) {
 			    ?><table class="analogi" style="width: 100%; padding:0; border-spacing: 0;" ><?php
			$itemAnalogObj = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arResult['IBLOCK_ID'], "XML_ID" => $analogs), false, false, array("DETAIL_PAGE_URL", "NAME", "ID", 'CATALOG_QUANTITY'));
			while ($itemAnalog = $itemAnalogObj->GetNext()) {
			    /* ?><pre><?var_dump($itemAnalog)?></pre><? */
			    $prices = getPricesByItemId($itemAnalog['ID']);
				?>
				    <tr>
					<td><a href="<?= $itemAnalog["DETAIL_PAGE_URL"] ?>" title="<?= $itemAnalog["NAME"] ?>" rel="nofollow"  ><?= $itemAnalog["NAME"] ?></a>
					    <img src="<?= SITE_TEMPLATE_PATH ?>/img/<?= $itemAnalog['CATALOG_QUANTITY'] > 0 ? 'q-yes.png' : 'close-popup.png' ?>"  alt="<?= $itemAnalog["NAME"] ?>" />
					</td>
					<td class="pink big" style="text-align:right"><span class="price"><?= $prices[0] ?> <span class="rubl">&#8399;</span></span></td>
				    </tr><?
		    }
			    ?></table><? } else {
			    ?>У этого товара нет аналогов.<? } ?>

                    </div>
                </div>
            </td>
	</tr>
    </table>
    
    
    <?
    $arRecommended = array();
    $arParentSection['IBLOCK_SECTION_ID'] = $arResult['IBLOCK_SECTION_ID'];
    $arFirstParentSection = array();
    while (empty($arLinkedSections) && !empty($arParentSection['IBLOCK_SECTION_ID'])) {
	$arParentSection = CIBlockSection::GetList(array(), array('ID' => $arParentSection['IBLOCK_SECTION_ID'], 'IBLOCK_ID' => $arResult['IBLOCK_ID']), false, array('UF_LINKED_SECTIONS', 'UF_NEW_TITLE'))->Fetch();
	if (empty($arFirstParentSection)) {
	    $arFirstParentSection = $arParentSection;
	}
    }
    if (!empty($arParentSection['UF_LINKED_SECTIONS'])) {
	$rsList = CIBlockElement::GetList(array('RAND' => 'ASC'), array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'], 'SECTION_ID' => $arParentSection['UF_LINKED_SECTIONS']), false, array('nTopCount' => 4));
	while ($arItem = $rsList->GetNext(true, false)) {
	    if (!empty($arFirstParentSection['UF_NEW_TITLE'])) {
		$arItem['NAME'] = str_replace(array('#NAME#', '#SECTION_NAME#'), array($arItem['NAME'], $arFirstParentSection['NAME']), $arFirstParentSection['UF_NEW_TITLE']);
	    }

	    $file_path = '/upload/no-photo.png';
	    if(!empty($arItem['PREVIEW_PICTURE'])) {
		$file_path = CFile::GetPath($arItem['PREVIEW_PICTURE']);
	    }
	    $arRecommended[] = array(
		'ID' => $arItem['ID'],
		'NAME' => $arItem['NAME'],
		'PREVIEW_PICTURE' => $file_path,
		'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL']
	    );
	}
    } else {
	
    }
    ?>    
    <? if (!empty($arRecommended)): ?>    
        <div class="item-border"></div>
        <h3 class="item-title">Рекоммендуемые товары <?= $arResult["NAME"] ?></h3>
        <div class="reply-border"></div>
	<? foreach ($arRecommended as $arItem): ?>
	    <div class="recommended">
		<div class="cat-item-pic">
		    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" title="<?= $arItem['NAME'] ?>" rel="nofollow" ><img src="<?= $arItem['PREVIEW_PICTURE'] ?>" alt="<?= $arItem['NAME'] ?>" /></a>
		</div>
		<div class="cat-item-props">
		    <p class="noMarginTop">
			     <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"  ><?= $arItem['NAME'] ?></a>
		    </p>
		    <p class="small12">

		    </p>
		</div>
	    </div>
	<? endforeach; ?>
        <div class="clear"></div>
    <? endif; ?>
    
    
    
    <div class="item-border"></div>
    <h2 class="item-title">Отзывы <?= $arResult["NAME"] ?></h2>
    <div class="reply-border"></div>
    <?php
    //$arResult['USE_CAPTCHA'] = !$USER->IsAuthorized() ? 'Y' : 'N';
    $arResult['USE_CAPTCHA'] = 'Y';
    
    if ($arResult['USE_CAPTCHA'] == 'Y') {
	$arResult['capCode'] = htmlspecialchars($APPLICATION->CaptchaGetCode());
    }
    if (isset($_POST['reply-name'])) {
	$arResult['ERROR'] = '';

	if ($arResult['USE_CAPTCHA'] == 'Y') {
	    include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/captcha.php');
	    $captcha_code = $_POST['captcha_sid'];
	    $captcha_word = $_POST['captcha_word'];
	    $cpt = new CCaptcha();
	    $captchaPass = COption::GetOptionString('main', 'captcha_password', '');
	    if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0) {
		if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
		    $arResult['ERROR'] = 'Введенные символы не верны.';
	    } else {
		$arResult['ERROR'] = 'Введите символы с картинки.';
	    }
	}
	if (!strlen($arResult['ERROR'])) {
	    $el = new CIBlockElement;
	    $add = $el->Add(array('IBLOCK_ID' => 10,
    		'PROPERTY_VALUES' => array(
                'name' => $_POST['reply-name'], 
                'text' => $_POST['reply-text'], 
                "item" => $arResult["ID"], 
                "uid" => $_POST['uid'], 
                'email' => $_POST['reply-email'],
                'URL' => $APPLICATION->GetCurPage(),
                'UNIQ_STR' => md5(time().$_POST['reply-name'].'bxmaker_md5_key'.rand(10,50))
            ),
            'NAME' => 'Имя ' . $_POST['reply-name'], 
            'ACTIVE' => 'Y')
        );
	}
    }
    ?>

    <?php
    $res321 = CIBlockElement::GetList(array('DATE_CREATE' => 'desc'), array("IBLOCK_ID" => "10", "PROPERTY_item" => $arResult["ID"]), false, false, array('PROPERTY_name', 'PROPERTY_text', 'PROPERTY_uid', 'DATE_CREATE')); //
    while ($ar321 = $res321->Fetch()) {
	$ar1 = explode(' ', $ar321["DATE_CREATE"]);
	$ar1 = explode('.', $ar1[0]);
	$ar1[2] = substr($ar1[2], 2, 2);
	$quan = '';
	//echo'<pre>';var_dump($ar321);echo'</pre>';
	if ($ar321["PROPERTY_UID_VALUE"] != '') { //echo '('.intval($ar321["PROPERTY_UID"]).')'
	    if ($ar321["PROPERTY_UID_VALUE"]) {
		$rsUser = CUser::GetByID($ar321["PROPERTY_UID_VALUE"]);
		if ($arUser = $rsUser->Fetch())
		    $quan = ' (' . intval($arUser["UF_RATING"]) . ')';
	    }
	}
	?>
        <div class="reply-body">
    	<div class="reply-header"><?= $ar321["PROPERTY_NAME_VALUE"] ?><?= $quan ?><div class="reply-date"><?= implode('.', $ar1) ?></div></div>
	    <?= $ar321["PROPERTY_TEXT_VALUE"] ?>
    	<div class="reply-border"></div>
        </div> <?php
    }
	?>
    <!--<div class="reply-body">
	   <div class="reply-header">Alex <div class="reply-date">21.06.88</div></div>
	   Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex <br />
	   Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex Alex
	   <div class="reply-border"></div>
    </div> -->

    <div class="reply-mini-header"><span>Оставить отзыв о товаре</span></div>
    <form action="<?= POST_FORM_ACTION_URI ?>" method="post">
	<table class="reply-table">
	    <tr> <td class="reply-label">Ваше имя</td> <td><input type="text" class="text-input w350" name="reply-name" value="<? if ($USER->IsAuthorized()) echo $USER->GetLogin(); else echo htmlspecialchars($_POST['reply-name']) ?>"/></td> </tr>
	    <tr> <td class="reply-label">Ваш email</td> <td><input type="text" class="text-input w350" name="reply-email" value="<? if ($USER->IsAuthorized()) echo $USER->GetEmail(); else echo htmlspecialchars($_POST['reply-email']) ?>"/></td> </tr>
	    <tr> <td class="reply-label">Ваш отзыв</td> <td class='tatd'><textarea class="textarea" name="reply-text"><?= $_POST['reply-text'] ?></textarea></td> </tr>
	    <? if ($arResult['USE_CAPTCHA'] == 'Y'): ?>
    	    <tr>
    		<td class="reply-label">Код</td>
    		<td>
    		    <input type="hidden" name="captcha_sid" value="<?= $arResult['capCode'] ?>" />
    		    <input class="text-input w350" type="text" name="captcha_word" size="30" maxlength="50" value="" />
    		    <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult['capCode'] ?>" width="180" height="40" alt="CAPTCHA" />
    		</td>
    	    </tr>
	    <? endif; ?>
	</table>
	<input type="hidden" name="uid" value="<? if ($USER->IsAuthorized()) echo $USER->GetId() ?>" />
	<p class="center"><span class="button-l"><span class="button-r">
		    <input class="button" type="submit" value="отправить" />
		</span></span></p>
    </form>

</div>

<?
@define("MY_ER_REDIR", "Y");
?>

<script type="text/javascript" >
		/*$(document).ready(function(){
	$('.twitter-count-horizontal').css('width','106px');
})*/
</script>


