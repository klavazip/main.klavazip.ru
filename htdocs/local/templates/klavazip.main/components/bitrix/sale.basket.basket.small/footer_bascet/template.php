<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="footerLinkBasket <?if ($arResult["READY"]=="Y"){?>active<?}?>"><a href="/personal/basket/">Корзина</a> 
	<?
	if ($arResult["READY"]=="Y")
	{
		$count = 0;
		$summ = 0;
		foreach ($arResult["ITEMS"] as $v)
		{
			if ($v["DELAY"]=="N" && $v["CAN_BUY"]=="Y")
			{
				$count++;
				$summ= $summ + ($v["PRICE"] * $v['QUANTITY']);
			}
		}
		?>
		<a href="/personal/basket/" class="products"><?=$count?> товаров</a> <span><span>/</span> <?=number_format($summ, 0,'.',' ');?> <span class="st_1"><?=KlavaMain::RUB?></span></span>
		<a href="/personal/order/" class="buttonTakeOrder">Оформить заказ</a>
		<?
	}
	?>
	<div class="promptFooter" id="js_promt_basket_product" style="bottom: -60px">
		<div class="contPrompt">
			Товар добавлен в корзину
			<div class="promptBottom"></div>
		</div>
	</div>
</div>