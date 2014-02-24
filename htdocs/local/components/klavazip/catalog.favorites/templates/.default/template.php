<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	if(count($arResult['ITEAM']) > 0)
	{
		?>
		<div class="tableProducts" style="padding: 20px">
			<table cellspacing="0" cellpadding="0">
				<tr class="head">
					<td><p>Фото</p></td>
					<td><a href="#">Название</a></td>
					<td><a href="#" class="active">Артикул</a></td>
					<td><p>Характеристики</p></td>
					<td><a href="#">Цена</a></td>
					<td><a href="#">Остаток</a></td>
					<td></td>
					<td></td>
				</tr>
				<?
				foreach ($arResult['ITEAM'] as $ar_Value)
				{
					?>
					<tr>
						<td><a href="<?=$ar_Value['DETAIL_URL']?>"><img width="62" src="<?=$ar_Value['IMG']?>" alt=""></a></td>
						<td><a href="<?=$ar_Value['DETAIL_URL']?>"><?=$ar_Value['NAME']?></a></td>
						<td><p><?=$ar_Value['ARTICLE']?></p></td>
						<td><p><?=$ar_Value['PROPERTY']?></p></td>
						<td><p class="price"><?=$ar_Value['PRICE']?> <span><?=KlavaMain::RUB?></span></p></td>
						<td><p class="number"><?=$ar_Value['QUANTITY_COUNT']?>  шт.</p></td>
						<td>
							<? 
							if(intval($ar_Value['QUANTITY_COUNT']) > 0)
							{
								?><a title="Добавить в корзину" onclick="klava.addBasket('<?=$ar_Value['ID']?>', 1); return false;" href="#" class="basketTable"></a><?
							}
							else
							{
								?><a title="Сообщить о поступлении товара в продажу" onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_Value['ID']?>'); return false;" href="#" class="basketTableNotyfy"></a><?
							}
							?>
						</td>
						<td><a  class="compareBasket" style="position: static;" href="#" onclick="klava.delFavorites('<?=$ar_Value['ID']?>'); return false;"></a></td>
					</tr>
					<?
				}
				?>
			</table>
		</div>	
		<? 		
	}
	else
	{
		?><div class="error-block" style="height: 500px; text-align: center; padding-top: 30px">Вы еще нечего не добавили в избранное :)</div><?
	}
	?>

	