<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="boxMain">
	<h1>Ваш заказ № <?=intval($_GET['ID'])?> успешно создан</h1>
	<div class="border_1"></div>
	<? 
	if (!empty($arResult["PAY_SYSTEM"]))
	{
		?>
		<div style="padding: 40px">
			<div>Способ оплаты: <?= $arResult["PAY_SYSTEM"]["NAME"]?></div><br />
			<?
			if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
			{
				if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
				{
					if($arResult["PAY_SYSTEM"]["ID"] != '8') // 8
					{ 
	        			?>
	        			<script language="JavaScript">window.open('/personal/order/payment/?ORDER_ID=<?=$arResult["ORDER_ID"]?>');</script>
	        			Если окно с платежной информацией не открылось автоматически, нажмите на ссылку 
	        			<a style="color: #E69B9B; text-decoration: underline;" href="/personal/order/payment/?ORDER_ID=<?=$arResult['ORDER_ID']?>" target="_blank"><b>Оплатить заказ</b></a>.
	        			<?
	        		}
	        		else 
	        		{
	        			?>
	        			<a style="color: #E69B9B; text-decoration: underline;" target="_blank" href="/personal/order/payment/check.php?ORDER_ID=<?=$arResult['ORDER_ID']?>">
	        				Показать счет на оплату
	        			</a>
	        			<?
					}
				}
				else
				{
					if($arResult["PAY_SYSTEM"]["ID"] != '8') 
	        		{ 
	            		if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]) > 0)
	    				{
	    					include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
	    				}
					}
	        		else 
	        		{
	        			?>
	        			<a style="color: #E69B9B; text-decoration: underline;" target="_blank" href="/personal/order/payment/check.php?<?=http_build_query($_GET)?>">
	        				Показать и распечатать счет
	        			</a>
	        			<? 
					}
				} 
			}
			?> 
			<div style="padding-top: 20px">Следить за состоянием заказа вы можете в <a href="/cabinet/" style="color: #E69B9B; text-decoration: underline;">Личном кабинете на сайте</a></div>
		</div>
		<? 
	}

	# Статистика для яндекса и гугла 
		
	$ar_Params = array();
	foreach ($arResult['BASKET'] as $i => $ar_Value)
		$ar_Params[] = array('id' => $i, 'name' => $ar_Value["NAME"], 'price' => $ar_Value["PRICE"], 'quantity' => $ar_Value["QUANTITY"]); 
	?>
	
	<script type="text/javascript">
		yaParams = {order_id : <?=$arResult["ORDER_ID"]?>, order_price : <?=$arResult["SUMM"]?>, currency : "RUR", exchange_rate : 1, goods: <?=CUtil::PhpToJSObject($ar_Params)?>};
		
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-39290877-1']);
		_gaq.push(['_trackPageview']); 

		_gaq.push(['_addTrans', '<?=$arResult["ORDER_ID"]?>', 'Klavazip', '<?=intval($arResult["SUMM"])?>', '0', '<?=intval($arResult["PRICE_DELIVERY"])?>', '<?=$orderRes["CITY"]?>', '', 'Russia']);
		 
		<? 
		foreach ($arResult['BASKET'] as $ar_Value)
		{
		   	?>_gaq.push(['_addItem', '<?=$arResult["ORDER_ID"]?>', '<?=$ar_Value["PRODUCT_ID"]?>', '<?=$ar_Value["NAME"]?>', 'Section', '<?=intval($ar_Value["PRICE"])?>', '<?=intval($ar_Value["QUANTITY"])?>']);<?
		}
		?>

		_gaq.push(['_trackTrans']); 
		
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		
		var _oms = window._oms || [];
		_oms.push(["create_order", {order_id: "<?=$arResult["ORDER_ID"]?>", sum: <?=$arResult["SUMM"]?>}]); 
	</script>
</div>