<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div id="id-shelve-list" style="display:none;top:-20px;position:relative;">
<div class="content-bg2">
   <p class="center">
			
			<a href="#" class="switch-cart" onclick="ShowBasketItems(1);"><?=GetMessage("SALE_PRD_IN_BASKET_ACT")?> (<?=count($arResult["ITEMS"]["AnDelCanBuy"])?>)</a>&nbsp;
			<span class="active-cart"><?=GetMessage("SALE_PRD_IN_BASKET_SHELVE")?></span>
			<!--<?if(false):?>
			<span class="active-cart"><a href="#" onclick="ShowBasketItems(3);"><?=GetMessage("SALE_PRD_IN_BASKET_NOTA")?> (<?=count($arResult["ITEMS"]["nAnCanBuy"])?>)</a></span>
			<?endif;?>-->
	</p>		
	
	<?if(count($arResult["ITEMS"]["DelDelCanBuy"]) > 0):?>
	<table class="table-cart" cellspacing="0" width="100%">
	<tbody>
	<?
	foreach($arResult["ITEMS"]["DelDelCanBuy"] as $arBasketItems)
	{
		?>
		<!--<tr>
			<?if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-name"><?
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?><a href="<?=$arBasketItems["DETAIL_PAGE_URL"] ?>"><?
				endif;
				?><b><?=$arBasketItems["NAME"] ?></b><?
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?></a><?
				endif;?>
				<?if (in_array("PROPS", $arParams["COLUMNS_LIST"]))
				{
					foreach($arBasketItems["PROPS"] as $val)
					{
						echo "<br />".$val["NAME"].": ".$val["VALUE"];
					}
				}?>
				</td>
			<?endif;?>
			<?if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-price"><?=$arBasketItems["PRICE_FORMATED"]?></td>
			<?endif;?>
			<?if (in_array("VAT", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-price"><?=$arBasketItems["VAT_RATE_FORMATED"]?></td>
			<?endif;?>
			<?if (in_array("TYPE", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-type"><?=$arBasketItems["NOTES"]?></td>
			<?endif;?>
			<?if (in_array("DISCOUNT", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-discount"><?=$arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"]?></td>
			<?endif;?>
			<?if (in_array("WEIGHT", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-weight"><?=$arBasketItems["WEIGHT_FORMATED"]?></td>
			<?endif;?>
			<?if (in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-quantity"><?=$arBasketItems["QUANTITY"]?></td>
			<?endif;?>
			<td class="cart-item-actions">
				<?if (in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
					<a class="cart-delete-item" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delete"])?>" title="<?=GetMessage("SALE_DELETE_PRD")?>"></a>
				<?endif;?>
				<?if (in_array("DELAY", $arParams["COLUMNS_LIST"])):?>
					<a class="cart-shelve-item" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["add"])?>"><?=GetMessage("SALE_ADD_CART")?></a>
				<?endif;?>
			</td>
		</tr>-->
		<tr>
			<?if (CModule::IncludeModule("iblock")):
				echo '<td>';
				$ob = CIBlockElement::GetList(
					array(),
					array("ID"=>$arBasketItems["PRODUCT_ID"]),
					false,
					false,
					array("DETAIL_PICTURE")					
				);
				$img = $ob->GetNext();
				if (strlen($img[DETAIL_PICTURE])>0){
					$imgPrew = CFile::ResizeImageGet($img[DETAIL_PICTURE],array("109","60"),BX_RESIZE_IMAGE_PROPORTIONAL,false);
					echo '<div class="item-pic"><img src="'.$imgPrew[src].'" width="109" ></div>';					
				} 
				//$img[DETAIL_PICTURE];
				
				echo '</td>';
			endif;?>
			
			
			<?if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-name"><?
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?><a href="<?=$arBasketItems["DETAIL_PAGE_URL"] ?>"><?
				endif;
				?><p class="big16 noMarginTop"><?=$arBasketItems["NAME"] ?></p><?
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?></a><?
				endif;?>
				<p class="grey small">Подсветка: CCFL, коннектор: 60, положение коннектора: слева снизу, разрешение: 800*480, размер: 7', поверхность: матовая</p>
				<?/*if (in_array("PROPS", $arParams["COLUMNS_LIST"]))
				{
					foreach($arBasketItems["PROPS"] as $val)
					{
						echo "<br />".$val["NAME"].": ".$val["VALUE"];
					}
				}*/?>
				</td>
			<?endif;?>
			<?if (in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
				<td class="quantity">
					<span class="item-quantity-switch">
                    	<input class="q-input" type="text" maxlength="" name="QUANTITY_<?=$arBasketItems['ID'] ?>" value="<?=$arBasketItems['QUANTITY']?>" size="3">
                    	<span class="switch-top" style="top: 0px; right: -2px;"></span>
                    	<span class="switch-bot" style="bottom: 0px; right: -2px;"></span>
                	</span> шт.
				</td>
			<?endif;?>
			<?if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
				<td class="price-td">
					<div class="relative">
            			<span class="price-for-script hidden"><?=(int)$arBasketItems["PRICE"]?></span>
            			<p class="noMarginTop" style="padding-right:25px; text-align:right"><span class="amount-for-script"><?=(int)$arBasketItems["PRICE"]?></span> <span class="rubl">⃏</span></p>
            			<a class="cart-delete" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delete"])?>" title="<?=GetMessage("SALE_DELETE_PRD")?>"></a>				
                		<p style="text-align:right"><a href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["add"])?>" class="small borderLink grey"><?=GetMessage("SALE_ADD_CART")?></a></p>
            		</div>					
				</td>
			<?endif;?>			
		</tr>		
		<?
	}
	?>
	</tbody>
</table>
<?endif;?>
</div>
</div>
<?