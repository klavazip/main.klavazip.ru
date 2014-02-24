<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?if (is_array($arResult['DETAIL_PICTURE_350']) || count($arResult["MORE_PHOTO"])>0):?>
<script type="text/javascript">
$(function() {
	$('div.catalog-detail-image a').fancybox({
		'transitionIn': 'elastic',
		'transitionOut': 'elastic',
		'speedIn': 600,
		'speedOut': 200,
		'overlayShow': false,
		'cyclic' : true,
		'padding': 20,
		'titlePosition': 'over',
		'onComplete': function() {
			$("#fancybox-title").css({ 'top': '100%', 'bottom': 'auto' });
		} 
	});
});
</script>
<?endif;?> 

<div class="catalog-detail-image">
	<table class="catalog-detail" cellspacing="0">
		<tr>

		<?if (is_array($arResult['DETAIL_PICTURE_350']) || count($arResult["MORE_PHOTO"])>0):?>
			<td class="catalog-detail-image">
			<?if (is_array($arResult['DETAIL_PICTURE_350'])):?>
				<div class="catalog-detail-image" id="catalog-detail-main-image">
					<a rel="catalog-detail-images" href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" title="<?=(strlen($arResult["DETAIL_PICTURE"]["DESCRIPTION"]) > 0 ? $arResult["DETAIL_PICTURE"]["DESCRIPTION"] : $arResult["NAME"])?>"><img itemprop="image" src="<?=$arResult['DETAIL_PICTURE_350']['SRC']?>" alt="<?=$arResult["NAME"]?>" id="catalog_detail_image" width="<?=$arResult['DETAIL_PICTURE_350']["WIDTH"]?>" height="<?=$arResult['DETAIL_PICTURE_350']["HEIGHT"]?>" /></a>
				</div>
			<?endif;?>
				<div class="catalog-detail-images">
			<?if(count($arResult["MORE_PHOTO"])>0):
				foreach($arResult["MORE_PHOTO"] as $PHOTO):
			?>
				<div class="catalog-detail-image"><a rel="catalog-detail-images" href="<?=$PHOTO["SRC"]?>" title="<?=(strlen($PHOTO["DESCRIPTION"]) > 0 ? $PHOTO["DESCRIPTION"] : $arResult["NAME"])?>"><img border="0" src="<?=$PHOTO["SRC_PREVIEW"]?>" width="<?=$PHOTO["PREVIEW_WIDTH"]?>" height="<?=$PHOTO["PREVIEW_HEIGHT"]?>" alt="<?=$arResult["NAME"]?>" /></a></div>
			<?
				endforeach;
			endif?>

				</div>
			</td>
		<?endif;?>

			<td class="catalog-detail-desc">
			<?if($arResult["PREVIEW_TEXT"]):?>
				<span itemprop = "description"><?=$arResult["PREVIEW_TEXT"];?></span> 
				<div class="catalog-detail-line"></div>
			<?endif;?>
					<?if(is_array($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):?>
						<div class="catalog-item-offers">
						<?foreach($arResult["OFFERS"] as $arOffer):?>
							<?if(!empty($arParams["OFFERS_FIELD_CODE"]) || !empty($arOffer["DISPLAY_PROPERTIES"])):?>
							<table cellspacing="0">
							<?foreach($arParams["OFFERS_FIELD_CODE"] as $field_code):?>
								<tr><td class="catalog-item-offers-field"><span><?echo GetMessage("IBLOCK_FIELD_".$field_code)?>:</span></td><td><?
										echo $arOffer[$field_code];?></td></tr>
							<?endforeach;?>
							
							<?foreach($arOffer["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
								<tr><td class="catalog-item-offers-field"><span><?=$arProperty["NAME"]?>:</span></td><td><?
									if(is_array($arProperty["DISPLAY_VALUE"]))
										echo implode(" / ", $arProperty["DISPLAY_VALUE"]);
									else
										echo $arProperty["DISPLAY_VALUE"];?></td></tr>
							<?endforeach?>
							</table>
							<?endif;?>
							<?foreach($arOffer["PRICES"] as $code=>$arPrice):?>
								<?if($arPrice["CAN_ACCESS"]):?>
									<div class="catalog-detail-price-offer" itemprop = "offers" itemscope itemtype = "http://schema.org/Offer"><label><?=GetMessage("CATALOG_PRICE")?></label>&nbsp;&nbsp;
									<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
										<s><span itemprop = "price"><?=$arPrice["PRINT_VALUE"]?></span></s> <span class="catalog-price" itemprop = "price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
									<?else:?>
										<span class="catalog-price" itemprop = "price"><?=$arPrice["PRINT_VALUE"]?></span>
									<?endif?>
									</div>
								<?endif;?>
							<?endforeach;?>
							<div class="catalog-item-links">
							<?if($arParams["USE_COMPARE"]):?>
								<noindex>
								<a href="<?echo $arOffer["COMPARE_URL"]?>" class="catalog-item-compare" onclick="return addToCompare(this, '<?=GetMessage("CATALOG_IN_COMPARE")?>');" rel="nofollow" id="catalog_add2compare_link_ofrs_<?=$arOffer['ID']?>"><?echo GetMessage("CATALOG_COMPARE")?></a>
								</noindex>
							<?endif?>
							<?if($arOffer["CAN_BUY"]):?>
								<a href="<?echo $arOffer["ADD_URL"]?>" class="catalog-item-buy<?/*catalog-item-in-the-cart*/?>" rel="nofollow"  onclick="return addToCart(this, 'catalog_detail_image', 'list', '<?=GetMessage("CATALOG_IN_BASKET")?>');" id="catalog_add2cart_link_ofrs_<?=$arOffer['ID']?>"><?echo GetMessage("CATALOG_ADD_TO_BASKET")?></a>
							<?elseif(count($arResult["CAT_PRICES"]) > 0):?>
								<span class="catalog-item-not-available"><?=GetMessage("CATALOG_NOT_AVAILABLE")?></span>
							<?endif?>
							</div>
							<div class="catalog-detail-line"></div>
						<?endforeach;?>
						</div>
					<?else:?>
				<div class="catalog-detail-price">
				<?foreach($arResult["PRICES"] as $code=>$arPrice):
					if($arPrice["CAN_ACCESS"]):
				?>
					<label><?=GetMessage("CATALOG_PRICE")?></label>
					<p>
						<span itemprop = "offers" itemscope itemtype = "http://schema.org/Offer">
							<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
								<span class="catalog-detail-price" itemprop = "price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span> <s><span itemprop = "price"><?=$arPrice["PRINT_VALUE"]?></span></s>
							<?else:?>
								<span class="catalog-detail-price" itemprop = "price"><?=$arPrice["PRINT_VALUE"]?></span>
							<?endif;?>
						</span>
					</p>
				<?
						break;
					endif;
				endforeach;
				?>
				</div>

				<?if($arResult["CAN_BUY"]):?>
				<div class="catalog-detail-buttons">
					<!--noindex--><a href="<?=$arResult["ADD_URL"]?>" rel="nofollow" onclick="return addToCart(this, 'catalog_detail_image', 'detail', '<?=GetMessage("CATALOG_IN_BASKET")?>');" id="catalog_add2cart_link"><span><?echo GetMessage("CATALOG_ADD_TO_BASKET")?></span></a><!--/noindex-->
				</div>
				<?endif;?>

				<div class="catalog-item-links">
					<?if(!$arResult["CAN_BUY"] && (count($arResult["PRICES"]) > 0)):?>
					<span class="catalog-item-not-available"><!--noindex--><?=GetMessage("CATALOG_NOT_AVAILABLE");?><!--/noindex--></span>
					<?endif;?>

					<?if($arParams["USE_COMPARE"] == "Y"):?>
					<a href="<?=$arResult["COMPARE_URL"]?>" class="catalog-item-compare" onclick="return addToCompare(this, '<?=GetMessage("CATALOG_IN_COMPARE")?>');" rel="nofollow" id="catalog_add2compare_link" rel="nofollow"><?echo GetMessage("CATALOG_COMPARE")?></a>
					<?endif;?>
				</div>
				<?endif;?>
			</td>
		</tr>
	</table>
	
<?
if (is_array($arResult['DISPLAY_PROPERTIES']) && count($arResult['DISPLAY_PROPERTIES']) > 0):
?>
	<?$arProperty = $arResult["DISPLAY_PROPERTIES"]["RECOMMEND"]?>
	
	<?if(count($arProperty["DISPLAY_VALUE"]) > 0):?>
	<div class="catalog-detail-recommends">
		<h4><?=$arProperty["NAME"]?></h4>
			<div class="catalog-detail-recommend">
			<?
			global $arRecPrFilter;
			$arRecPrFilter["ID"] = $arResult["DISPLAY_PROPERTIES"]["RECOMMEND"]["VALUE"];
			$APPLICATION->IncludeComponent("bitrix:store.catalog.top", "", array(
				"IBLOCK_TYPE" => "",
				"IBLOCK_ID" => "",
				"ELEMENT_SORT_FIELD" => "sort",
				"ELEMENT_SORT_ORDER" => "desc",
				"ELEMENT_COUNT" => $arParams["ELEMENT_COUNT"],
				"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"DISPLAY_COMPARE" => "N",
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"FILTER_NAME" => "arRecPrFilter",
				"DISPLAY_IMG_WIDTH"	 =>	$arParams["DISPLAY_IMG_WIDTH"],
				"DISPLAY_IMG_HEIGHT" =>	$arParams["DISPLAY_IMG_HEIGHT"],
				"SHARPEN" => $arParams["SHARPEN"],
				"ELEMENT_COUNT" => 30,
				),
				$component
			);
			?>
		</div>
	</div>
	<?unset($arResult["DISPLAY_PROPERTIES"]["RECOMMEND"])?>
	<?endif;?>
<?endif;?>
<?
if (is_array($arResult['DISPLAY_PROPERTIES']) && count($arResult['DISPLAY_PROPERTIES']) > 0):
?>
	<div class="catalog-detail-properties">
		<h4><?=GetMessage('CATALOG_PROPERTIES')?></h4>
		<div class="catalog-detail-line"></div>
		<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<div class="catalog-detail-property">
				<span><?=$arProperty["NAME"]?></span>
				<b>
<?
		if(is_array($arProperty["DISPLAY_VALUE"])):
			echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
		elseif($pid=="MANUAL"):
?>
					<a href="<?=$arProperty["VALUE"]?>"><?=GetMessage("CATALOG_DOWNLOAD")?></a>
<?
		else:
			echo $arProperty["DISPLAY_VALUE"];
		endif;
?>
				</b>
			</div>
	<?endforeach;?>
	</div>
<?endif;?>

	<?if($arResult["DETAIL_TEXT"]):?>
	<div class="catalog-detail-full-desc">
		<h4><?=GetMessage('CATALOG_FULL_DESC')?></h4>
		<div class="catalog-detail-line"></div>
		<span  itemprop = "description"><?=$arResult["DETAIL_TEXT"];?></span>
	</div>
	<?endif;?>
	
</div>