<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 

if($arResult['ERROR'] == 'Y')
{
	?><div class="error-block">Ошибка</div><?
}
else
{
	?>
	<div class="boxContTab">
		<div class="documentOrder">
			<h1>Заказ <span><?=$arResult['ORDER']['ID']?> <span>от <?=$arResult['ORDER']['DATE_INSERT']?></span></span></h1>
			<div class="boxOrderStatus">
				<? 
				switch ($arResult['ORDER']['STATUS_ID'])
				{
					case 'N':?><div class="status_3 active"></div><div class="status_4"></div><div class="status_1"></div><div class="status_2"></div><div class="status_5"></div><div class="clear"></div><div class="statusText_3">Подтвержден</div><?break;
					case 'X':?><div class="status_3"></div><div class="status_4"></div><div class="status_1"></div><div class="status_2"></div><div class="status_5 active"></div><div class="clear"></div><div class="statusText_5">Отменен</div><? break;
					case 'P':?><div class="status_3 disabled"></div><div class="status_4 active"></div><div class="status_1"></div><div class="status_2"></div><div class="status_5"></div><div class="clear"></div><div class="statusText_4">Оплачен</div><? break;
					case 'F':?><div class="status_3 disabled"></div><div class="status_4 disabled"></div><div class="status_1 active"></div><div class="status_2"></div><div class="status_5"></div><div class="clear"></div><div class="statusText_1">Отправлен</div><? break;
					case 'D':?><div class="status_3 disabled"></div><div class="status_4 disabled"></div><div class="status_1 disabled"></div><div class="status_2 active"></div><div class="status_5"></div><div class="clear"></div><div class="statusText_2">Доставлен</div><?break;
				}
				?>
			</div>
			
			<form action="" method="post" name="product-list-form" id="js_product_list_form">
				<div class="tableHistoryOrder">
					<table cellspacing="0" cellpadding="0">
						<tr class="head">
							<td><input type="checkbox" id="order-detail-all-checked" class="styled"></td>
							<td><p>Фото</p></td>
							<td><p>Название</p></td>
							<td><p>Артикул</p></td>
							<td><p>Характеристики</p></td>
							<td><p>Количество</p></td>
							<td><p>Стоимость</p></td>
						</tr>
						<? 
						foreach ($arResult['PRODUCT'] as $ar_Value)
						{
							?>
							<tr>
								<td>
									<input type="checkbox" value="<?=$ar_Value['ID']?>" name="SELECT_ELEMENT_ID[]" class="styled js-product-item">
									<input type="hidden" value="<?=$ar_Value['COUNT']?>" name="COUNT[<?=$ar_Value['ID']?>]" />
									<input type="hidden" value="<?=$ar_Value['ID']?>" name="ELEMENT_ID[]" />
								</td>
								<td><a target="_blank" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><img alt="" width="62" src="<?=$ar_Value['IMG']?>"></a></td>
								<td><a target="_blank" class="name" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><?=$ar_Value['NAME']?></a></td>
								<td><p><?=$ar_Value['ARTICUL']?></p></td>
								<td><p><?=$ar_Value['PROPERTY']?></p></td>
								<td><p class="number"><?=$ar_Value['COUNT']?></p></td>
								<td><p class="price"><?=$ar_Value['PRICE']?> <?=KlavaMain::RUB?></p></td>
							</tr>
							<?
						}
						?>
					</table>
				</div>
			</form>										
			
			<div class="leftInfOrder">
				<p><span>Адрес доставки:</span> <?=$arResult['ORDER']['ADRESS']?></p>
				<p><span>Способ доставки:</span> <?=$arResult['ORDER']['DELIVERY']['NAME']?></p>
				<p><span>Трек-номер:</span> 777777777</p>
				<p><span>Способ оплаты:</span> <?=$arResult['ORDER']['PAY_SYSTEM']['NAME']?></p>
			</div>
			<div class="rightInfOrder">
				<p>Сумма по товарам: <strong><?=$arResult['ORDER']['PRODUCT_PRICE']?> <?=KlavaMain::RUB?></strong></p>
				<p>Стоимость доставки: <strong><?= intval($arResult['ORDER']['DELIVERY']['PRICE'])?> <?=KlavaMain::RUB?></strong></p>
				<p class="price">Сумма к оплате: <span><strong> <?= intval($arResult['ORDER']['PRICE'])?> <?=KlavaMain::RUB?></strong></span></p>
			</div>
			<div class="clear"></div>
			<div class="orderInfBottom">
				<? 
				if($arResult['ORDER']['STATUS_ID'] == 'D' && $arResult['ORDER']['PAY_SYSTEM']['ID'] !== 'X')
				{
					?><a class="buttonReturn" onclick="klava.cabinet.loadOrderReturnPage(<?=$arResult['ORDER']['ID']?>); return false;" href="#">Отправить заявку на возврат</a><?	
				}
				
				if($arResult['ORDER']['STATUS_ID'] == 'N')
				{
					/*
					?><a class="linkCancel" href="#">Отменить заказ</a><?
					*/
				}
				
				
				?><a class="linkTurn" onclick="klava.cabinet.submitRepeatOrder(); return false;" href="#">Повторить заказ</a><?
				
				
				if($arResult['ORDER']['STATUS_ID'] == 'N' && $arResult['ORDER']['PAY_SYSTEM']['ID'] != 31 && $arResult['ORDER']['PAY_SYSTEM']['ID'] !== 'X')
				{
					if($arResult['ORDER']['PAY_SYSTEM']['ID'] == 8)
					{
						$s_PaymentPatch = '/personal/order/payment/check.php?ORDER_ID='.$arResult['ORDER']['ID'];
					}	
					else
					{
						$s_PaymentPatch = '/personal/order/payment/?ORDER_ID='.$arResult['ORDER']['ID'];
					}	
					?><a class="linkPay" target="_blank" href="<?=$s_PaymentPatch?>">Оплатить заказ</a><?

				}
				
				if($arResult['ORDER']['PAY_SYSTEM']['ID'] !== 'X' && $arResult['ORDER']['PAY_SYSTEM']['ID'] !== 'D')
				{
					?><a class="buttonReturn" href="<?=$APPLICATION->GetCurPageParam("status=x");?>">Отменить заказ</a><?
				}	
				?>
				
				<div class="clear"></div>
			</div>
		</div>										
	</div>
	<?
}