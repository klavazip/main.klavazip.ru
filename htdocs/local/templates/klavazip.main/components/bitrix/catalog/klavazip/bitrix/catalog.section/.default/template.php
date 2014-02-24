<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?

class myPrice{
	public $base;   // базовая цена или нет
	public $price;	// цена
	public $priceId; //id цены
	public $currensy; // валюта
	public $discountPrice;	//скидка
	function __construct(){}
	
	//перевод  денг в строку
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
}?>

<?php
$arBasketItemsDelay = array();
if (isset($_SESSION["CATALOG_COMPARE_LIST"][8]["ITEMS"]))
	{
		$array_compare_keys = array_keys($_SESSION["CATALOG_COMPARE_LIST"][8]["ITEMS"]);
	}
/*if (count($arResult['ITEMS']) < 1)
	return;*/
if (CModule::IncludeModule("sale")&& CModule::IncludeModule("catalog")&& CModule::IncludeModule("iblock")){
$dbBasketItems = CSaleBasket::GetList(
    array(
            "NAME" => "ASC",
            "ID" => "ASC"
        ),
    array(
            "FUSER_ID" => $USER->GetId(),
            "LID" => SITE_ID,
            "DELAY" => "Y"
        ),
    false,
    false,
    array("ID","DELAY","PRODUCT_ID")
);

while ($arItems = $dbBasketItems->Fetch())
{
    if (strlen($arItems["CALLBACK_FUNC"]) > 0)
    {				        
        $arItems = CSaleBasket::GetByID($arItems["ID"]);
		
    }

    $arBasketItemsDelay[] = $arItems["PRODUCT_ID"];
}
}
?>
<?
if(strpos($arResult['DESCRIPTION'], '###') !== false) {
    echo substr( $arResult['DESCRIPTION'], 0, strpos($arResult['DESCRIPTION'], '###'));
} else {
    echo $arResult['DESCRIPTION'];
}
?>	



	 <table class="cat-items">
	 	
	 <?
	 //var_dump(count($arResult["ITEMS"]));
	 if(count($arResult["ITEMS"]))
	 foreach($arResult["ITEMS"] as $arElement):?>	
      <tr>
        <td>
            <div class="cat-item-pic">
            	<?if (strlen($arElement["DETAIL_PICTURE"][SRC])>0): ?>
            		<?$img =CFile::ResizeImageGet($arElement["DETAIL_PICTURE"],array("width"=>"157","height"=>"87"),BX_RESIZE_IMAGE_PROPORTIONAL_ALT,false);?>
                	<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=$img["src"]?>" alt="<?=$arElement['NAME']?>" title="<?=$arElement['NAME']?>" /></a> <!--width="157"-->
                	
                <?else:?>
                <div style="width:117;height=75;padding:45% 0;" >Картинка отсутствует</div>
                <?endif?>
                
                <div class="labels">
                	<?php
							$res=CIBlockElement::GetList(array(),array("IBLOCK_ID" => "8", "ID"=>$arElement['ID']),false,false,array("PROPERTY_SALELEADER","PROPERTY_NEWPRODUCT"));
							if($ar_res=$res->GetNext())
							{ ?>
                    			<?=$ar_res["PROPERTY_SALELEADER_VALUE"]==NULL?'':'<div class="label-hit">&nbsp;</div>'?>
                    			<?=$ar_res["PROPERTY_NEWPRODUCT_VALUE"]==NULL?'':'<div class="label-new">&nbsp;</div>';?><?  
							}
                	?>                	
                </div>
            </div>
            <? if(isset($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) && strlen($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) >0):?>
                <p style="text-align:center"><span class="artikul small">Артикул: <?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span></p>
            <?endif;?>
        </td>
        <td class="cat-item-props">
            <div class="cat-item-props">
                <p class="noMarginTop"><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="big16"><?=$arElement["NAME"]?></a></p>
                <p class="small12">
                	
                	<?foreach($arElement["PROPERTIES"] as $value):?>
                		<?
                			$masHar = array("diagonal","data_code","resolution","light","connector","surface","location_connector","manufactur","type_bga","state_bga","color","keyboard","volume_video","type_video","frequency","with_memory");
                			if (in_array($value["CODE"], $masHar) && strlen(trim($value["VALUE"]))>0):
                		?>
                    		<i><?=$value["NAME"]?></i>: <?=$value["VALUE"]?><br />
    					<?	endif;
    					endforeach;?> 
                	<?//=$arElement["PREVIEW_TEXT"]?>
	                	<!--Подсветка: CCFL<br>
	                Коннектор: 60<br>
	                Положение коннектора: слева снизу<br>
	                Разрешение: 800*480<br>
	                Размер: 7'<br>
	                Поверхность: матовая-->	
                </p>
            </div>
        </td>
        <td>
            <div class="cat-item-price">
                <div class="item-buy pink">
                <?  
	            	$prices=getPricesByItemId($arElement['ID'])						
						?>
						<span class="price"><?=$prices[0]?> <span class="rubl">⃏</span></span>
						<?
						if ($arElement["CATALOG_QUANTITY"]>0)
						{
	                    	echo '<span class="item-quantity-switch">	
			                        <input type="text" size="3" value="1" name="" class="q-input">
			                        <span class="switch-top"></span>
			                        <span class="switch-bot"></span>
	                    		</span>
	                    		<a href="'.$arElement['ADD_URL'].'#buy" class="button-buy catalog-item-buy" rel="nofollow">купить</a>';
						}
						else{
							?><a href="#" data-name="<?=$arElement['NAME']?>" data-id="<?=$arElement['ID']?>" class="notify-link pink italic" style="line-height:13px"><span class="borderLink">уведомить меня о наличии</span></a><?php 
						}
						?>
	                    
	                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="q-block">
                    <p class="big">Наличие</p>
                    <?
                    	if ($arElement["CATALOG_QUANTITY"]>0)
						{
							/*echo '
							<p>
                        		<span class="q-type q-yes">Москва - 5 шт</span>
                        		<span class="q-type q-yes">Санкт-Петербург — поступит 20.12.11</span>
                        		<span class="q-type q-no">Уфа — поступит 25.12.11</span>
                        		<span class="q-type q-no">Уфа — поступит 25.12.11</span>
                    		</p>
							';*/
							echo '<span class="q-type q-yes">Всего: '.$arElement["CATALOG_QUANTITY"]." шт</span>";
						}
						else{
							echo '
								 <p>
                        			<span class="q-type q-x">Нет в наличии на складах</span>
                    			</p>
							';
						}
                    ?>                    
                </div>
                                            	
                	<?
						if (!in_array($arElement['ID'], $array_compare_keys))								
                		{
                			echo '<a class="std borderLink floatleft catalog-item-compare" href="/catalog'.$arElement['COMPARE_URL'].'" >Сравнить</a>';							                            	
						}
						
                	?> 
                	<?if (!in_array($arElement['ID'], $arBasketItemsDelay)):?>                   			
                       		<p class="small12"><a class="borderLink std floatright wish-list" href="/?action=shelve&amp;id=<?=$arElement['ID']?>">В желаемые</a></p>
                    <?endif;?>
                    
                <div class="clearfix">&nbsp;</div>
            </div>
        </td>
      </tr>     
      <?endforeach;?>
    </table>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>

<?
if(strpos($arResult['DESCRIPTION'], '###') !== false) {
    echo substr( $arResult['DESCRIPTION'], strpos($arResult['DESCRIPTION'], '###') + 3);
}
?>	



<?php
//ФИЛЬТР СВОЙСТВ
/*
if(isset($_GET['nodelprops']) && $_GET['nodelprops']!='')
{
	//var_dump($_GET['nodelprops']);
	//var_dump(explode(',',$_GET['nodelprops']));
	//var_dump(explode('%2C',$_GET['nodelprops']));
	$delprops=explode(',',$_GET['nodelprops']);
}
else
*/



if(count($arResult["ITEMS"]))
{
	$arFilter=array("SUBSECTION"=>$arResult["ITEMS"][0]['IBLOCK_SECTION_ID'], 'IBLOCK_ID'=>$arResult["ITEMS"][0]['IBLOCK_ID']);
	
	$catItemsC = CIBlockElement::GetList( array(), $arFilter, false, false, array() );
	$fullCount = $catItemsC->SelectedRowsCount();
	
	$selProps=array();
	foreach($arParams["PROPERTY_CODE"] as $key=>$propName)
		$selProps[$key] = 'PROPERTY_'.strtoupper($propName);
	$delprops=array();
	$catItemsC2 = CIBlockElement::GetList( array(), $arFilter, false, false, $selProps );
	while($elPr = $catItemsC2->Fetch())
	{
		//echo'<pre>';var_dump($elPr);echo'</pre>';
		foreach($selProps as $key=>$selProp)
		{
			//var_dump($elPr[$selProp.'_VALUE']);
			if($elPr[$selProp.'_VALUE']!=NULL)
				if(!in_array($arParams["PROPERTY_CODE"][$key],$delprops))
					$delprops[]=$arParams["PROPERTY_CODE"][$key];
		}
	}
	//var_dump($count);
	//echo'<pre>';var_dump($selProps);echo'</pre>';
	//echo'<pre>';var_dump($delprops);echo'</pre>';
	//echo'<pre>';var_dump($arFilter);echo'</pre>';
}
else
	$delprops=isset($_GET['nodelprops'])?explode(',',$_GET['nodelprops']):array();
?>



<?//ФИЛЬТР ПО ЦЕНЕ?>
	<?php
	if(isset($arResult["ITEMS"][0]))
	{
		$IBSECID = $arResult['ITEMS'][0]["IBLOCK_SECTION_ID"];
		$thisSecId = $IBSECID;
		$_SESSION["secId"]=$thisSecId;
	}	
	elseif(isset($_SESSION["secId"]))
		$thisSecId = $_SESSION["secId"];

	$minPrice = 100000000;
	$maxPrice = 0;
	$res=CIBlockElement::GetList(array(),array("IBLOCK_ID" => "8", "SUBSECTION"=>$thisSecId),false,false,array("ID"));
	while($ar_res=$res->GetNext())
	{
		$prices=getPricesByItemId($ar_res['ID']);
		$curpr = intval($prices[0]);
		if($curpr < $minPrice) $minPrice = $curpr;
		if($curpr > $maxPrice) $maxPrice = $curpr;
	}
	?>

	<?php $url1=explode('?',$_SERVER['REQUEST_URI'])?>
	<?php if(isset($_GET['del_filter'])) header('Location: '.$url1[0])?>

	<script type="text/javascript">
	$(document).ready(function(){
		$( "input.price-min" ).val('<?=isset($_GET['price-minf'])&&!isset($_GET['del_filter'])?$_GET['price-minf']:$minPrice?>');
		$( "input.price-max" ).val('<?=isset($_GET['price-maxf'])&&!isset($_GET['del_filter'])?$_GET['price-maxf']:$maxPrice?>');
		$( ".price-min-label" ).html('<?=$minPrice?>');
		$( ".price-max-label" ).html('<?=$maxPrice?>');
		
		$( "#slider-range" ).slider({
			range: true,
			min: <?=$minPrice?>,
			max: <?=$maxPrice?>,
			step:10,
			values: [ $( "input.price-min" ).val(), $( "input.price-max" ).val() ],
			slide: function( event, ui ) {
				$( "input.price-min" ).val( ui.values[ 0 ] );
				$( "input.price-max" ).val( ui.values[ 1 ] );
				
				$('.price-min2').css({left: $('a.ui-slider-handle:first').position().left }).html(ui.values[ 0 ])
				$('.price-max2').css({left: $('a.ui-slider-handle:last').position().left }).html(ui.values[ 1 ])
			}
		});

		$('.price-min2').html( $( "#slider-range" ).slider( "values", 0 ) ).css({left: $('a.ui-slider-handle:first').position().left })
		$('.price-max2').html( $( "#slider-range" ).slider( "values", 1 ) ).css({left: $('a.ui-slider-handle:last').position().left })
	})
	</script>
<?//ФИЛЬТР ПО ЦЕНЕ END?>

<?
@define("MY_ER_REDIR","Y");
?>

<script type="text/javascript" >
$(document).ready(function(){
	function in_array(val,arr) {
		for(var i=arr.length; i>=0; i--)
			if(arr[i]==val)
				return true;
		return false;
	}
	
	$butt = $('.hide-this').find('div:has(.filter-button)')
	$butt.height('27px').width($butt.find('.filter-button').width()).css({bottom:'30px',left:'100px',position:'absolute'})
	$('.reset-filter').css({bottom:'30px',position:'absolute'})
	var arPr=["<?=implode('","',$delprops)?>"];
	$('.floatleft:has([name^="arrFilter_pf["])').each(function(){
		if(!in_array( $(this).find('[name]').attr('name').split('arrFilter_pf[')[1].split(']')[0], arPr ) )
			$(this).hide();
	})
	var val=$('[name=nodelprops]').val();
	if(val.length==0)
	 if(arPr.length!=0)
	 	if(arPr.length[0]!='')
			$('[name=nodelprops]').val(arPr.join(','));
		
	$('.hide-this').find('div:has(.checkboxes)').remove()
	$('.hide-this').height($('.floatleft:has([name^="arrFilter_pf["]):visible').length*17+20+60+20+10+'px')	
})
</script>
