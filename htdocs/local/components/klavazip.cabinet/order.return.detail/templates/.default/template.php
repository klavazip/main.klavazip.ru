<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="boxContTab">
	<div class="documentOrder">
		<h1>Заявка на возврат товаров из заказа <span><?=$arResult['ORDER']['ID']?><span> от <?=$arResult['ORDER']['DATE_INSERT']?></span></span></h1>	
		<div class="tableHistoryOrder history">
			<table cellspacing="0" cellpadding="0">
				<tr class="head">										
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
						<td><a target="_blank" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><img alt="" width="62" src="<?=$ar_Value['IMG']?>"></a></td>
						<td><a target="_blank" class="name" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><?=$ar_Value['NAME']?></a></td>
						<td><p><?=$ar_Value['ARTICUL']?></p></td>
						<td><p><?=$ar_Value['PROPERTY']?></p></td>
						<td><p class="number"><?=$ar_Value['COUNT']?></p></td>
						<td><p class="price"><?=$ar_Value['PRICE']?> <span class="curency">⃏</span></p></td>
					</tr>
					<?					
				}
				?>
			</table>
		</div>							
		<div class="rightInfOrder">
			<p>Сумма по товарам: <strong><?=$arResult['PRICE_PRODUCT_SUMM']?> <span class="curency">⃏</span></strong></p>								
		</div>
		<div class="clear"></div>
		<div class="top_1"></div>
		<div class="boxFormTab">
				
			<label>Причина возврата</label>
			<div class="blockInputform"><?=$arResult['RETURN_TEXT']?></div>
			
			<div class="clear"></div>		
			<label>Фото товара</label>

			<div class="blockInputform">
				<? 
				foreach ($arResult['FOTO'] as $ar_Value)
				{
					?>
					<a target="_blank" href="<?=$ar_Value['b']?>">
						<img src="<?=$ar_Value['m']?>" alt="" /> <br /><br />
					</a>
					<?
				}
				?>
			</div>
			<div class="clear"></div>								
			
		</div>	
			
	</div>										
</div>