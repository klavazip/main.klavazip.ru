<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

function VariationOfNumber($iNumber, $sclonForm1, $sclonForm2, $sclonForm3)
{
	if ($iNumber == 11 || $iNumber == 12 || $iNumber == 13 || $iNumber == 14)
		return $sclonForm3;
		
	$iNumber = abs($iNumber) % 100;
	$iNumber = $iNumber % 10;
		
	if ($iNumber > 10 && $iNumber < 20)
		return $sclonForm3;
		
	if ($iNumber > 1 && $iNumber < 5)
		return $sclonForm2;
		
	if ($iNumber == 1)
		return $sclonForm1;
		
	return $sclonForm3;
}



if(count($arResult['ITEM']) == 0)
{
	?><div class="error-block" style="height: 700px">Нечего не найдено, попробуйте изменить параметры запроса</div><?
}
else
{
	?>
	<div class="boxMain">
		<h1>Результаты поиска </h1>
		<div class="tableProducts" style="padding: 20px;">
			<table>
				<tr class="head">
					<td><p style="padding-bottom: 5px">Фото</p></td>
					<td><p>Название</p></td>
					<td><p>Артикул</p></td>
					<td><p>Характеристики</p></td>
					<td><p>Цена</p></td>
					<td><p>Остаток</p></td>
					<td></td>
				</tr>
				<?
				foreach ($arResult['ITEM'] as $ar_ItemValue)
				{
					?>
					<tr>
						<td><a href="<?=$ar_ItemValue['DETAIL_PAGE_URL']?>"><img width="62" alt="" src="<?=CFile::GetPath($ar_ItemValue['PREVIEW_PICTURE'])?>"></a></td>
						<td><a href="<?=$ar_ItemValue['DETAIL_PAGE_URL']?>"><?=$ar_ItemValue['NAME']?></a></td>
						<td><p><?=$ar_ItemValue['PROPERTIES']['PROPERTY_CML2_ARTICLE']['VALUE']?></p></td>
						<td><p><?=$ar_ItemValue['PROPERTY_STRING']?></p></td>
						<td><p class="price"><?=intval($ar_ItemValue['CATALOG_PRICE_4'])?>  <span><?=KlavaMain::RUB?></span></p></td>
						<td><p class="number"><?=(intval($ar_ItemValue['CATALOG_QUANTITY']) > 0) ? intval($ar_ItemValue['CATALOG_QUANTITY']).' шт.' : 'Нет' ?> </p></td>
						<td>
							<?
							if(intval($ar_ItemValue['CATALOG_QUANTITY']) > 0)
							{
								?><a title="Добавить в корзину" class="basketTable" href="#" onclick="klava.addBasket('<?=$ar_ItemValue['ID']?>', 1); return false;"></a><?								
							}
							else
							{
								?><a title="Сообщить о поступлении товара в продажу" class="basketTableNotyfy"  href="#" onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_ItemValue['ID']?>'); return false;"></a><?
							}
							?>
						</td>
					</tr>
					<? 		
				}
				?>
			</table>
		</div>
	</div>
	<?
}
?>
<div style="margin-left: 88px;padding: 20px;width: 780px;"><?=$arResult["NAV_STRING"];?></div>
