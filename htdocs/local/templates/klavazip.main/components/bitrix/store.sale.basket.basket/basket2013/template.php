<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if( count($arResult['ITEMS']['AnDelCanBuy']) > 0 )
{
	?>
	<div class="boxMain">
		<h1>Корзина</h1>
		<div class="border_1"></div>
		<div class="mainBasket">
		<form method="post" name="Basket" action="">
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
						<td><p><?=intval($arBasketItems['PRICE'])?> <span class="curency"><?=KlavaMain::RUB?></span></p></td>
						<td> 
							<p class="price"><?=(isset($arBasketItems['DISCOUNT_PRICE'])) ? intval($arBasketItems['DISCOUNT_PRICE']) : intval($arBasketItems['PRICE']) ?> <span class="curency"><?=KlavaMain::RUB?></span></p>
						</td>
						<td>
							<div class="boxNumberProducts" id="order_item_block_count_<?=$arBasketItems['ID']?>">
								<a class="minus" onclick="klava.basket.editProductCount('minus', <?=$arBasketItems['ID']?>); return false;" href="#"></a>
								<a class="plus" onclick="klava.basket.editProductCount('plus', <?=$arBasketItems['ID']?>); return false;" href="#"></a>
								<div class="inputNumber">
									<input 
										data-bid="<?=$arBasketItems['ID']?>"
										data-id="<?=$arBasketItems['PRODUCT_ID']?>" 
										data-current="<?=$arResult['ELEMENT'][$arBasketItems['PRODUCT_ID']]['CURRENT_COUNT']?>" 
										class="q-input" 
										type="text" 
										name="QUANTITY_<?=$arBasketItems['ID'] ?>" 
										value="<?=$arBasketItems['QUANTITY']?>" 
										id="order_item_input_count_<?=$arBasketItems['ID']?>"
										onblur="klava.basket.editProductCount('set', <?=$arBasketItems['ID']?>)" 
										>
									<p>шт</p>
								</div>
							</div>
							<div class="box-not-count-block">
								Мы рады отгрузить вам <span id="box_new_count_val_<?=$arBasketItems['ID']?>">40</span> шт, 
								но есть только <?=$arResult['ELEMENT'][$arBasketItems['PRODUCT_ID']]['CURRENT_COUNT']?> — <a onclick="klava.order.setRealCount(<?=$arBasketItems['ID']?>); return false;" href="#">забрать всё</a> 
							</div>
						</td>
						<td>
							<p class="mainPrice">
								<?=(isset($arBasketItems['DISCOUNT_PRICE']) ? $arBasketItems['DISCOUNT_PRICE'] : $arBasketItems['PRICE']) * intval($arBasketItems['QUANTITY'])?>
								<span class="curency"><?=KlavaMain::RUB?></span>
							</p>
						</td>
						<td>
							<a class="iconBasket" onclick="klava.basket.delProduct(<?=$arBasketItems['ID']?>); return false;" href="#" title="Удалить из корзины"></a>
						</td>
					</tr>
					<?
				}
				?>
			</table>
			<div class="blockBasketSumm">
				<div class="blockSumm">
					<p class="mainPrice" style="display: inline-block; margin-top: 20px; text-align: left;">
						Сумма по товарам: <span><?= str_replace('руб', '', $arResult["allSum_FORMATED"])?><span class="curency"><?=KlavaMain::RUB?></span></span> 
						<? 
						if(intval($arResult["DISCOUNT_PRICE_FORMATED"]) > 0)
						{
							?>скидка для Вас составила: <span><?= str_replace('руб', '', $arResult["DISCOUNT_PRICE_FORMATED"])?><span class="curency"><?=KlavaMain::RUB?></span></span><?
						}
						?> 
					</p>
					<input class="buttonBuy"  type="submit" value="Оформить заказ" name="BasketOrder"  id="basketOrderButton2">
					<div class="clear"></div>
					<div class="summBottom">
						<p>Для быстрого заказа оставьте свой номер телефона — мы вам перезвоним и уточним все недостающие данные:</p>
						<a class="buttonOneClick" rel="buyClickBasket" href="#">Заказать в 1 клик</a>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</form>
		</div>
	</div>
	<? 
}
else
{
	?><div class="error-block" style="text-align: center;padding: 30px;">Ваша козина пока пуста</div><?
}