<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="margin-bottom"></div>
<div class="page-title">
	<h1>Сравнение товаров <span class="compare-number">(<?=$itemsCnt = count($arResult['ITEMS']);?>)</span></h1>
</div>
<div class="content-bg2 order-content compare-bg">
	<div class="compare-overflow relative">
    	<div class="c-shadow"></div>
        <div class="c-right"></div>
<?$delUrlID = "";?>

 <table class="compare-table" width="100%" cellpadding="0" cellspacing="0">
        	<thead>
                <tr>
                    <td>
                    	<div class="relative compare-select" style="width:100%">
                    		<?
								if($arResult["DIFFERENT"]):
							?>
                        		<p><a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT")))?>" rel="nofollow" ><span>Все характеристики</span></a></p>
                        		<p><a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT")))?>" rel="nofollow" class="active-compare"><span>Только различия</span></a></p>
                        	<?
								else:
							?>
                            	<p><a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT")))?>" rel="nofollow" class="active-compare"><span>Все характеристики</span></a></p>
                        		<p><a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT")))?>" rel="nofollow"><span>Только различия</span></a></p>
                            <?
								endif;
							?>
                            <div class="c-left"></div>
                        </div>
                    </td>
                  	

		
<?
foreach($arResult["ITEMS"] as $arElement):
$delUrlID .= "&ID[]=".$arElement["ID"];
?>
					<td>
                        <div class="relative">
                        	<?
                        		if (CModule::IncludeModule("iblock")){
									$elIMGObj = CIBlockElement::GetList(array(),array("ID"=>$arElement["ID"]),false,false,array("DETAIL_PICTURE"));
									$elIMG = $elIMGObj->Fetch();
									$img=CFile::ResizeImageGet($elIMG["DETAIL_PICTURE"],array("width"=>"110","height"=>"80"),BX_RESIZE_IMAGE_PROPORTIONAL_ALT,false); 	
								}
                        	?>
                       	<div class="labels" style="z-index:100">
			                	<?php
										$res565454=CIBlockElement::GetList(array(),array("IBLOCK_ID" => "8", "ID"=>$arElement["ID"]),false,false,array("PROPERTY_SALELEADER","PROPERTY_NEWPRODUCT"));
										if($ar_res=$res565454->GetNext())
										{ ?>
			                			<?=$ar_res["PROPERTY_SALELEADER_VALUE"]==NULL?'':'<div class="label-hit"></div>'?>
			                			<?=$ar_res["PROPERTY_NEWPRODUCT_VALUE"]==NULL?'':'<div class="label-new"></div>';?><?php
										} ?>                	
			               </div>
	                    	<p><img src="<?=$img["src"]?>" style="height:80px;"></p>
	                        <p><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a></p>
	                        <a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=".$arParams['IBLOCK_ID']."&ID[]=".$arElement['ID'],array("action", "IBLOCK_ID", "ID")))?>" class="compare-remove"></a>
	                    </div>
                	</td>
<?
endforeach;
?>
				</tr>
			</thead>			
			<tbody>
				
<?
$i=0;
if(isset($arResult["ITEMS"][0]["FIELDS"]))
foreach($arResult["ITEMS"][0]["FIELDS"] as $code=>$field):
	if($code == "NAME")
		continue;
?>
		<?if (($code!="DETAIL_PICTURE")&&($code!="PREVIEW_PICTURE")):?>
				<tr <?if ($i%2==0) echo 'class="even"'?>>
			
						<td class="grey"><?=GetMessage("IBLOCK_FIELD_".$code)?></td>
<?
	
	foreach($arResult["ITEMS"] as $arElement):
?>
					<td>
<?
		switch($code):
			case "NAME":
?>
						<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement[$code]?></a>
<?
			break;
			case "PREVIEW_PICTURE":
			case "DETAIL_PICTURE":				
			break;
			default:
				echo $arElement["FIELDS"][$code];
			break;
		endswitch;
		
?>
					</td>
<?
	endforeach;
?>
				</tr>
<?
	$i++;
	endif;
endforeach;
?>				
<?
$i=0;
foreach($arResult["SHOW_PROPERTIES"] as $code=>$arProperty):
	$arCompare = Array();
	foreach($arResult["ITEMS"] as $arElement)
	{
		$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
		if(is_array($arPropertyValue))
		{
			sort($arPropertyValue);
			$arPropertyValue = implode(" / ", $arPropertyValue);
		}
		$arCompare[] = $arPropertyValue;
	}
	$diff = (count(array_unique($arCompare)) > 1 ? true : false);
	if($diff || !$arResult["DIFFERENT"]):
?>
				<tr <?if ($i%2==0) echo 'class="even"'?>>
					<td class="grey"><?=$arProperty["NAME"]?></td>
<?
		foreach($arResult["ITEMS"] as $arElement):
			if($diff):
?>
					<td>
<?
				echo (
					is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					: $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]
				);
?>
					</td>
<?
			else:
?>
					<td>
<?
				echo (
					is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					: $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]
				);
?>
					</td>
<?
			endif;
		endforeach;
?>
				</tr>
<?
$i++;
	endif;
endforeach;
?>
<?
foreach($arResult["SHOW_OFFER_FIELDS"] as $code=>$field):
?>
				<tr class="even">
					<td class="gray"><?=GetMessage("IBLOCK_FIELD_".$code)?></td>
<?
	foreach($arResult["ITEMS"] as $arElement):
?>
					<td>
					<?=$arElement['OFFER_FIELDS'][$code]?>
					</td>
<?
	endforeach;
?>
				</tr>
<?

endforeach;
?>


<?foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty):
	$arCompare = Array();
	foreach($arResult["ITEMS"] as $arElement)
	{
		$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
		if(is_array($arPropertyValue))
		{
			sort($arPropertyValue);
			$arPropertyValue = implode(" / ", $arPropertyValue);
		}
		$arCompare[] = $arPropertyValue;
	}
	$diff = (count(array_unique($arCompare)) > 1 ? true : false);
	if($diff || !$arResult["DIFFERENT"]):
?>
				<tr class="even">
					<td class="grey"><?=$arProperty["NAME"]?></td>
<?
		foreach($arResult["ITEMS"] as $arElement):
			if($diff):
?>
					<td>
<?
				echo (
					is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					: $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]
				);
?>
					</td>
<?
			else:
?>
					<td>
<?
				echo (
					is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
					: $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]
				);
?>
					</td>
<?
			endif;
		endforeach;
?>
				</tr>
<?
	$i++;
	endif;
endforeach;
?>
<?
if(isset($arResult["ITEMS"][0]["PRICES"]))
foreach($arResult["ITEMS"][0]["PRICES"] as $code=>$arPrice):
	if($arPrice["CAN_ACCESS"]):
?>
				<tr class="even">
					<td class="grey"><?=$arResult["PRICES"][$code]["TITLE"]?></td>
<?
		foreach($arResult["ITEMS"] as $arElement):
?>
					<td class="pink big">
<?
			if($arElement["PRICES"][$code]["CAN_ACCESS"]):
				echo $arElement["PRICES"][$code]["VALUE_NOVAT"]." ";
			endif;
?><span class="rubl">⃏</span>
					</td>
<?
		endforeach;
?>
				</tr>
<?	
endif;
endforeach;
?>
				<tr>
					<td class="grey data-column">Цена</td><?
					foreach($arResult["ITEMS"] as $arElement){ ?>
						<td class="pink big"><?$prices=getPricesByItemId($arElement['ID'])?><?=$prices[0]?> <span class="rubl">⃏</span></td><? 
					} ?>
				</tr>
				<tr>
					<td class="grey data-column"></td><?
					foreach($arResult["ITEMS"] as $arElement){ ?>
						<td>
							<span class="button-l">
								<span class="button-r"><a class="button catalog-item-buy" href="/?action=ADD2BASKET&id=<?=$arElement['ID']?>#buy">купить</a></span>
							</span>
						</td><? 
					} ?>
				</tr>
			</tbody>
		</table>
<?//if(strlen($delUrlID) > 0):
	//$delUrl = htmlspecialchars($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=".$arParams['IBLOCK_ID'].$delUrlID,array("action", "IBLOCK_ID", "ID")));
	?>
	<!--<p><a href="<?//=$delUrl?>"><?//=GetMessage("CATALOG_DELETE_ALL")?></a></p>-->
<?	
//endif;
?>
	</div>
</div>
</div>
<script type='text/javascript'>
$(document).ready(function(){
	$('.compare-table tbody tr').each(function(){
		var deltr=true;
		$(this).find('td:not(.grey)').each(function(){
			deltr=deltr & ($.trim($(this).html()).length==0);
		})
		//console.log(deltr)
		if(deltr) $(this).remove();
	})
	$('.compare-table tbody tr').removeClass('even');
	$('.compare-table tbody tr:even').addClass('even');
})
</script>