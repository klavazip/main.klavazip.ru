<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div style="position: relative;" class="boxMain">
	<h1>Доставка и оплата</h1>
	<div class="border_1"></div>
	<div class="static-delivery-pay" id="js-order-form">
		<div class="blockInputform" style="position: relative;">
			<input type="button" onclick="klava.infodelivery.searchLocation()" class="buttonBuy" value="Показать">
			<span class="ajax-load" style="display: none;"></span>
			<input 
				type="text" 
				data-placeholder="Y" 
				autocomplete="off" 
				id="js-delivery-location" 
				data-id="" 
				value=""
			>
			<div class="order-selected-block" style="display: none;"></div>
			<span class="placeholder">Введите название города или населенного пункта</span>
		</div>
		<div class="clear"></div>
		<div id="order-delevery-list" class="order-delevery-list">
			<div class="help">
				Для уточнения возможных способов доставки,  а так же сроков и стоимости введите название вашего города или населенного пункта
			</div>	
		</div>
	</div>	
</div>