<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

	<?//echo "<pre>"; print_r($arResult); echo "</pre>";?>
<div id="id-cart-list">
<div class="content-bg2">
	<!--<div class="inline-filter cart-filter">-->
	<p class="center">
			<span class="active-cart"><?=GetMessage("SALE_PRD_IN_BASKET_ACT")?></span>
			<a href="#" class="switch-cart" onclick="ShowBasketItems(2);"><?=GetMessage("SALE_PRD_IN_BASKET_SHELVE")?> (<?=count($arResult["ITEMS"]["DelDelCanBuy"])?>)</a>&nbsp;
			<!--<?if(false):?>
			<span class="active-cart"><a href="#" onclick="ShowBasketItems(3);"><?=GetMessage("SALE_PRD_IN_BASKET_NOTA")?> (<?=count($arResult["ITEMS"]["nAnCanBuy"])?>)</a></span>
			<?endif;?>-->
	</p>
	<!--</div>-->
	
	<?
	
	$can_buy=array_merge($arResult["ITEMS"]["AnDelCanBuy"],$arResult["ITEMS"]["nAnCanBuy"]);
	$arResult["ITEMS"]["AnDelCanBuy"]=$can_buy;
	?>

	<?if(count($arResult["ITEMS"]["AnDelCanBuy"]) > 0):?>
	<div class="relative">
		<div class='c-label' style='background:url(<?=SITE_TEMPLATE_PATH?>/img/cart-discount-label.png) no-repeat 0 0; display:none; right: 7px; position:absolute; top:-30px; height:30px; width:110px'></div>
	</div>
	<table class="table-cart" cellspacing="0" width="100%">
	<tbody>
	<?	
	class FastBox
	{
		private  $name;
		private  $quantity;
		private  $price;
		function setName($a){
			$this->name = $a;
		}
		function setQuantity($a){
			$this->quantity = $a;
		}
		function setPrice($a){
			$this->price = $a;
		}
		function toString(){
			return "Название:".$this->name." Колличество: ".$this->quantity." Цена: ".$this->price;	
		}
	}
	$arFastBox= array();
	$i = 0;	
	foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems)
	{
		$box = new FastBox;
		?>
		<tr>
			<?if (CModule::IncludeModule("iblock")):
				echo '<td>';
				$ob = CIBlockElement::GetList(
					array(),
					array("ID"=>$arBasketItems["PRODUCT_ID"]),
					false,
					false,
					array("DETAIL_PICTURE","IBLOCK_ID")					
				);
				$img = $ob->GetNext();
				if (strlen($img[DETAIL_PICTURE])>0){
					$imgPrew = CFile::ResizeImageGet($img[DETAIL_PICTURE],array("109","60"),BX_RESIZE_IMAGE_PROPORTIONAL,false);?>
					<div class="item-pic relative">
						<div class="labels">
	                	<?php
								$res=CIBlockElement::GetList(array(),array("IBLOCK_ID" => "8", "ID"=>$arBasketItems["PRODUCT_ID"]),false,false,array("PROPERTY_SALELEADER","PROPERTY_NEWPRODUCT"));
								if($ar_res=$res->GetNext())
								{ ?>
	                			<?=$ar_res["PROPERTY_SALELEADER_VALUE"]==NULL?'':'<div class="label-hit"></div>'?>
	                			<?=$ar_res["PROPERTY_NEWPRODUCT_VALUE"]==NULL?'':'<div class="label-new"></div>';?><?php
								} ?>                	
	                </div>
						<img src="<?=$imgPrew[src]?>" width="109px" >
					</div><?					
				} 
				//$img[DETAIL_PICTURE];
				
				echo '</td>';
			endif;?>
			
			
			<?if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-name"><?
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?><a href="<?=$arBasketItems["DETAIL_PAGE_URL"] ?>"><?
				endif;
				?><p class="big16 noMarginTop"><?=$arBasketItems["NAME"] ?></p><? $box->setName($arBasketItems["NAME"]);
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?></a><?
				endif;?>
				
				<?php //Подсветка: CCFL, коннектор: 60, положение коннектора: слева снизу, разрешение: 800*480, размер: 7', поверхность: матовая
	          	$ar=array("diagonal","color","frequency","with_memory","keyboard","connector","state_bga",
	          		"type_bga","surface","light","resolution","location_connector","SIZE","S_SIZE","ARTNUMBER","MATERIAL");
	          	//echo'<pre>';var_dump($arBasketItems);echo'</pre>';
	          	$db_props123 = CIBlockElement::GetProperty( $img["IBLOCK_ID"], $arBasketItems["PRODUCT_ID"] );
					$iiiprops = array();
					while($ar_props123 = $db_props123->GetNext())
						if($ar_props123["VALUE"]!=NULL && in_array($ar_props123["CODE"],$ar) )
							$iiiprops[]=$ar_props123["NAME"].': '.$ar_props123["VALUE_ENUM"];
				?>
				<p class="grey small"><?=implode(', ',$iiiprops)?></p>
				</td>
			<?endif;?>
			<?if (in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
				<td class="quantity">
					<span class="item-quantity-switch">
                    	<input class="q-input" type="text" maxlength="" name="QUANTITY_<?=$arBasketItems['ID'] ?>" value="<?=$arBasketItems['QUANTITY']?>" size="3"><? $box->setQuantity($arBasketItems['QUANTITY']);?>
                    	<span class="switch-top" style="top: 0px; right: -2px;"></span>
                    	<span class="switch-bot" style="bottom: 0px; right: -2px;"></span>
                	</span> шт.
				</td>
			<?endif;?>
			<?if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
				<td class="price-td-real">
					<div class="relative">
							<span class="price-for-script-real hidden"><?=number_format($arBasketItems["PRICE"],0,"","")?></span>
            			<p class="noMarginTop" style="padding-right:25px; text-align:right; color:#333333!important; font-size:14px!important"><span class="amount-for-script-real"><?=number_format($arBasketItems["PRICE"],0,"","")?></span> <span class="rubl">⃏</span></p>
            	</div>					
				</td>
			<?endif;?>
			<?if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
				<td class="price-td">
					<div class="relative">
            			<span class="price-for-script hidden"><?=number_format($arBasketItems["PRICE"],0,"","")?></span><? $box->setPrice(number_format($arBasketItems["PRICE"],0,"","")*1);?>
            			<p class="noMarginTop" style="padding-right:25px; text-align:right"><span class="amount-for-script"><?=number_format($arBasketItems["PRICE"],0,"","")?></span> <span class="rubl">⃏</span></p>
            			<a class="cart-delete" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delete"])?>" title="<?=GetMessage("SALE_DELETE_PRD")?>"></a>
                		<p style="text-align:right"><a href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["shelve"])?>" class="small borderLink grey"><?=GetMessage("SALE_OTLOG")?></a></p>
            		</div>					
				</td>
			<?endif;?>
			<!--<?if (in_array("VAT", $arParams["COLUMNS_LIST"])):?>
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
			
			<td class="cart-item-actions">
				<?if (in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
					<a class="cart-delete-item" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delete"])?>" title="<?=GetMessage("SALE_DELETE_PRD")?>"></a>
				<?endif;?>				
			</td>-->
		</tr>
		<?
		$arFastBox[]=$box;
		$i++;
	}
	?>
	</tbody>
	</table>
	
	
<!-- -------------------------------------------------------------- -->
<? /* ?>
 <div class="grey small12" style="">
                    <div class="b3 margin10 relative">
                    	<?php 
                    		class myDelivery1
                    		{
                    			public $id;
								public $name;
								public $dayFrom;
								public $dayTo;
								public $price;
								
								function __construct($id,$name,$dayFrom,$dayTo,$price){
									$this->id =  $id;
									$this->name = $name;
									$this->dayFrom = $dayFrom;
									$this->dayTo =  $dayTo;
									$this->price = $price;
								}
								
								public static function priceToString($price,$round=false){
									if ($round)	$strrev =strrev((string) round($price));	
									else $strrev =strrev((string) $price);
									$start = 0;
									if (strpos($strrev,".")>-1){
										$start = strpos($strrev,".");
										$resultRevers = substr($strrev,0,$start);			 
									}		
									for($i=$start;$i<strlen($strrev);$i++){					
										if (($i)%3==0 && $i!=$start+1){
											$resultRevers.=" ";
											$resultRevers.=$strrev[$i];
										}
										else $resultRevers.= $strrev[$i];					
									}
									return strrev($resultRevers);
								}
                    		}
							
							$arDelivery = array();
                    		if (CModule::IncludeModule("sale"))
							{
								$obDelivery = CSaleDelivery::GetList(array(),array(),false,false,array());
								while($Delivery = $obDelivery->GetNext()){
									$d = new myDelivery1($Delivery["ID"],$Delivery["NAME"],$Delivery["PERIOD_FROM"],$Delivery["PERIOD_TO"],$Delivery["PRICE"]);
									$arDelivery[] = $d;
								}
							}
                    	?>
						<span class="big16 noMarginTop" style="color:#F8ABAB">Доставка</span>
						<span class="cart-select floatright" style="height:18px;margin-right: 107px" >тип доставки</span>
                    	<div class="select-popup"  style="margin-right: 107px">
                    		<?foreach($arDelivery as $value):?>
                    			<a href="#" data-val="<?=$value->name;?>" data-id="<?=$value->id?>" data-price="<?=myDelivery1::priceToString($value->price,TRUE)?>" data-from="<?=$value->dayFrom?>" data-to="<?=$value->dayTo?>"><?=$value->name;?></a>
                    		<?endforeach;?>                            
                        </div>
                        <input type="hidden" class="select-input" value=""/>
						<div style="color: rgb(248, 171, 171); font-size: 18px; text-align: right; width: 100px;position:absolute; top:0;right:0">
							<p class="noMarginTop" style="padding-right:25px; text-align:right">
								<span class="cart-delivery-cost-2"></span><span class="rubl"> ⃏</span>
							</p>
						</div>	
                    </div>
                    <div class="clearfix"></div>
                    <p class="deliveryAbout" style="height:20px;display:block;width:300px"></p>
					
									
                </div>
				<div style="border-bottom: 1px dotted #CCCCCC;"></div>
<? */ ?>
<!-- -------------------------------------------------------------- -->	
	
	<p class="big18" style="text-align:right">Сумма по товарам: 
				<?
					if (in_array("PRICE", $arParams["COLUMNS_LIST"])):
						if (doubleval($arResult["DISCOUNT_PRICE"]) > 0):
							//echo '<span class="script-total">'.number_format($arResult["DISCOUNT_PRICE"],0,"","").'</span> <span class="rubl">⃏</span>';
				  		endif;
				  		if ($arParams['PRICE_VAT_SHOW_VALUE'] == 'Y'):
							//echo '<span class="script-total">'.$arResult["allNOVATSum"].'</span> <span class="rubl">⃏</span>';
							//echo '<span class="script-total">'.$arResult["allVATSum"].'</span> <span class="rubl">⃏</span>';
						endif;
						?><span class="script-true hidden"><?=number_format($arResult["allSum"],0,"","")?></span><?
				  		echo '<span class="script-total">'.number_format($arResult["allSum"],0,"","").'</span> <span class="rubl">⃏</span>'; // итоговая цена со всеми скидками

				  	endif;		
				 ?>
	</p>
    <? /**//* ?>	
	<p class="big18" style="text-align:right">Сумма по товарам с учетом стоимости доставки: 
		<span class="script-total2"><?=number_format($arResult["allSum"],0,"","")?></span> <span class="rubl">⃏</span>
	</p>
    <? /**/ ?>
    <p style="text-align:right"><span class="button-l"><span class="button-r"><input type="submit" name="BasketOrder"  id="basketOrderButton2" class="button" value="перейти к оформлению"></span></span></p>
    <div id="fastBox" style="display:none;visibility:hidden">
    	<?
    	foreach ($arFastBox as $value) {
			echo $value->toString()."<br>";
		}
    	?>
    </div>
   

        <!--<form>
        <p><span class="fo-label grey">Ваше имя</span> <input type="text" class="text-input"></p>
        <p><span class="fo-label grey">Ваш email</span> <input type="text" class="text-input"></p>
        <p><span class="fo-label grey">Ваш телефон</span> <input type="text" class="text-input"></p>
        <p style="text-align:right"><span class="button-l"><span class="button-r"><input type="submit" class="button" value="оформить заказ"></span></span></p>
        </form>-->
    </div>

	<?else:
	echo '<div class="content-bg2">';
		echo ShowNote(GetMessage("SALE_NO_ACTIVE_PRD"));
	echo '</div>';
	endif;?>
</div>
</div>
<script type="text/javascript" >
$(document).ready(function(){
	$('.table-cart tr').each(function(){
		//доставка
		$('[name=basket_form] .select-popup a').live('click',function(){ $('.script-total2').html($(this).attr('data-price').replace(' ','')*1+$('.script-true').html()*1) })
		$('.script-total2').html($('[data-id=1]:first').attr('data-price').replace(' ','')*1+$('.script-true').html()*1)
		
		$('.switch-top, .switch-bot').live('mousedown',function(){
			$('.script-true').html($('.script-total').html());
			var delCost = $('.top-cart .deliveryAbout').html().split('Стоимость:')[1].split('<span')[0].replace(' ','');
			$('.script-total2').html(delCost*1+$('.script-true').html()*1);
		})
		
	})
});
</script>
<style type="text/css"> .price-td-real { width:100px; display:none } </style>
<?