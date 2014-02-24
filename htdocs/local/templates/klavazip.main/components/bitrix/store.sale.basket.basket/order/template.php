<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	// Используется для вывода в компоненте оформления заказа
	$_SESSION['KLAVA_ORDER_PRICE'] = array(
		'DISCOUNT_PRICE' 	=> $arResult['DISCOUNT_PRICE'],
		'DISCOUNT_PERCENT' 	=> $arResult['DISCOUNT_PERCENT'],
		'allSum' 			=> $arResult['allSum']		
	);
	?>
	<div class="mainBasket" style="min-height: inherit;">
		<table class="table-cart">
			<tr class="head">
				<td></td>
				<td><p>Товар</p></td>
				<td><p>Цена</p></td>
				<td><p class="price">Ваша цена</p></td>
				<td><p>Количество</p></td>
				<td><p>Стоимость</p></td>
				<td></td>
			</tr>
			<?
			foreach ($arResult['ITEMS']['AnDelCanBuy'] as $key => $arBasketItems)
			{ 
				?>
				<tr>
					<td><p><?=$key+1?></p></td>
					<td>
						<a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>" target="_blank"><img alt="" width="80" src="<?=$arResult['ELEMENT'][$arBasketItems['PRODUCT_ID']]['IMG']?>"></a>
						<div class="descriptionTableProduct">
							<a target="_blank" class="name" href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>"><?=$arBasketItems['NAME']?></a>
							<p><?=$arResult['ELEMENT'][$arBasketItems['PRODUCT_ID']]['PROPERTY']?></p>
							<p>всего в наличии: <b><?=$arResult['ELEMENT'][$arBasketItems['PRODUCT_ID']]['CURRENT_COUNT']?> шт.</b></p>
						</div>
					</td>
					<td><p><?= intval($arBasketItems['PRICE'])?> <span class="curency"><?=KlavaMain::RUB?></span></p></td>
					<td> 
						<p class="price"><?=(isset($arBasketItems['DISCOUNT_PRICE'])) ? intval($arBasketItems['DISCOUNT_PRICE']) : intval($arBasketItems['PRICE']) ?> <span class="curency"><?=KlavaMain::RUB?></span></p>
					</td>
					<td>
						<div class="boxNumberProducts" id="order_item_block_count_<?=$arBasketItems['ID']?>">
							<a class="minus" onclick="klava.order.editProductCount('minus', <?=$arBasketItems['ID']?>); return false;" href="#"></a>
							<a class="plus" onclick="klava.order.editProductCount('plus', <?=$arBasketItems['ID']?>); return false;" href="#"></a>
							<div class="inputNumber">
								<input 
									data-bid="<?=$arBasketItems['ID']?>"
									data-id="<?=$arBasketItems['PRODUCT_ID']?>" 
									data-current="<?=$arResult['ELEMENT'][$arBasketItems['PRODUCT_ID']]['CURRENT_COUNT']?>" 
									class="q-input" 
									type="text" 
									maxlength="4"
									name="QUANTITY_<?=$arBasketItems['ID']?>" 
									value="<?=$arBasketItems['QUANTITY']?>" 
									id="order_item_input_count_<?=$arBasketItems['ID']?>"
									onblur="klava.order.editProductCount('set', <?=$arBasketItems['ID']?>)" 
									>
								<p>шт</p>
							</div>
						</div>
						<div class="box-not-count-block">
							Мы рады отгрузить вам <span id="box_new_count_val_<?=$arBasketItems['ID']?>">40</span> шт, 
							но есть только <?=$arBasketItems['QUANTITY']?> — <a onclick="klava.order.setRealCount(<?=$arBasketItems['ID']?>); return false;" href="#">забрать всё</a> 
						</div>
					</td> 
					<td>
						<p class="mainPrice">
							<?=(isset($arBasketItems['DISCOUNT_PRICE']) ? $arBasketItems['DISCOUNT_PRICE'] : $arBasketItems['PRICE']) * intval($arBasketItems['QUANTITY'])?>
							<span class="curency"><?=KlavaMain::RUB?></span>
						</p>
					</td>
					<td>
						<a class="iconBasket"  onclick="klava.order.delProduct(<?=$arBasketItems['ID']?>); return false;" href="#" title="<?=GetMessage("SALE_DELETE_PRD")?>"></a>
					</td>
				</tr>
				<?
			}
			?>
		</table>
	</div>