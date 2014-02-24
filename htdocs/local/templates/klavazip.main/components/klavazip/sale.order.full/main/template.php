<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!$USER->IsAuthorized())
{
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
}
else
{
	?>
	<div class="boxMain">				
		<h1>Оформление заказа</h1>				
		<div class="border_1"></div>
		<div class="boxMainCont">
			<div class="boxFormOrders">
				<ul>
					<li <?=($arResult["CurrentStep"] == 1) ? 'class="active"' : ''?>><a>Личные <br/> данные</a></li>
					<li><span><img src="<?=SITE_TEMPLATE_PATH?>/img/marker_order.png" alt="" /></span></li>
					<li <?=($arResult["CurrentStep"] == 2) ? 'class="active"' : ''?>><a>Информация <br/>об оплате</a></li>
					<li><span><img src="<?=SITE_TEMPLATE_PATH?>/img/marker_order.png" alt="" /></span></li>
					<li <?=($arResult["CurrentStep"] == 3) ? 'class="active"' : ''?>><a>Информация<br/> о доставке</a></li>
					<li><span><img src="<?=SITE_TEMPLATE_PATH?>/img/marker_order.png" alt="" /></span></li>
					<li <?=($arResult["CurrentStep"] == 4) ? 'class="active"' : ''?>><a>Способ<br/> оплаты</a></li>						
					<li><span><img src="<?=SITE_TEMPLATE_PATH?>/img/marker_order.png" alt="" /></span></li>
					<li <?=($arResult["CurrentStep"] == 5) ? 'class="active"' : ''?>><a>Подтверждение<br/> заказа</a></li>						
				</ul>
				<div class="clear"></div>
				<div class="orderContent">
					<?  
					if ($arResult["CurrentStep"] < 6)
					{
						?><form 
							method="post" 
							id="js-order-form" 
							action="<?= htmlspecialcharsbx($arParams["PATH_TO_ORDER"]) ?>" 
							name="order_form"
							data-nextstep="<?=$arResult["CurrentStep"] + 1?>"
							data-step="<?=$arResult["CurrentStep"]?>"
							>
							<? echo bitrix_sessid_post();
						}

						if(strlen($arResult["ERROR_MESSAGE"]) > 0)
						{
							?><div style="padding: 30px;"><?=ShowError($arResult["ERROR_MESSAGE"]);?></div><? 
						}								
						 
						if ($arResult["CurrentStep"] == 1)
						{
							?><div class="oneBlockOrderForm"><?
							include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/step1.php");
							?></div><? 
						}	
						elseif ($arResult["CurrentStep"] == 2)
						{
							include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/step2.php");
						}
						elseif ($arResult["CurrentStep"] == 3)
						{
							include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/step3.php");
						}
						elseif ($arResult["CurrentStep"] == 4)
						{
							include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/step4.php");
						}
						elseif ($arResult["CurrentStep"] == 5)
						{
							include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/step5.php");
						}
						elseif ($arResult["CurrentStep"] >= 6)
						{
							include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/step6.php");
						}

						
						if($arResult["CurrentStep"] == 5)
						{
							?>
							<div class="bottomOrderButton">
								<input type="submit" value="Подтвердить заказ" name="contButton" class="buttonBuy">
								<p class="summLastStep">Сумма к оплате: <span><?=str_replace(array('руб'), array(''), $arResult['ORDER_TOTAL_PRICE_FORMATED'])?> <span class="curency">⃏</span></span></p>
								<a class="markerBack" href="/personal/cart/">Назад в корзину</a>
								<div class="clear"></div>
							</div>
							<?
						}
						else if($arResult["CurrentStep"] < 5)
						{
							?>
							<div class="bottomOrderButton">
								<div style="text-align: right;">
									<?
									if($arResult["CurrentStep"] > 1)
									{
										?><input onclick="klava.order.setFormMode('backButton')" style="display: inline-block; float: none !important;" type="submit" class="buttonBuy" name="backButton" value="<< Назад"><?
									}
									?>
									<input onclick="klava.order.setFormMode('buttonBuy')" style="display: inline-block; float: none !important;" type="submit" class="buttonBuy" name="contButton" value="Далее >>"/> 
								</div>
								
								<a href="/personal/cart/" style="position: absolute; margin-top: -30px" class="markerBack">Назад в корзину</a>
								<div class="clear"></div>
							</div>
							<? 
						}
						
						
						if ($arResult["CurrentStep"] > 0 && $arResult["CurrentStep"] <= 7)
						{
							?>
							<input type="hidden" name="ORDER_PRICE" value="<?= $arResult["ORDER_PRICE"] ?>">
							<input type="hidden" name="ORDER_WEIGHT" value="<?= $arResult["ORDER_WEIGHT"] ?>">
							<input type="hidden" name="SKIP_FIRST_STEP" value="<?= $arResult["SKIP_FIRST_STEP"] ?>">
							<input type="hidden" name="SKIP_SECOND_STEP" value="<?= $arResult["SKIP_SECOND_STEP"] ?>">
							<input type="hidden" name="SKIP_THIRD_STEP" value="<?= $arResult["SKIP_THIRD_STEP"] ?>">
							<input type="hidden" name="SKIP_FORTH_STEP" value="<?= $arResult["SKIP_FORTH_STEP"] ?>">
							<?
						}
					
						if ($arResult["CurrentStep"] > 1 && $arResult["CurrentStep"] <= 6)
						{
							?>
							<input type="hidden" name="PERSON_TYPE" value="<?= $arResult["PERSON_TYPE"] ?>">
							<input type="hidden" name="BACK" value="">
							<?
						}
					
						if ($arResult["CurrentStep"] > 2 && $arResult["CurrentStep"] <= 6)
						{
							?>
							<input type="hidden" name="PROFILE_ID" value="<?= $arResult["PROFILE_ID"] ?>">
							<input type="hidden" name="DELIVERY_LOCATION" value="4<?= $arResult["DELIVERY_LOCATION"] ?>">
							<?
							$dbOrderProps = CSaleOrderProps::GetList(array("SORT" => "ASC"), array("PERSON_TYPE_ID" => $arResult["PERSON_TYPE"], "ACTIVE" => "Y", "UTIL" => "N"), false, false, array("ID", "TYPE", "SORT"));
							while ($arOrderProps = $dbOrderProps->Fetch())
							{
								if ($arOrderProps["TYPE"] == "MULTISELECT")
								{
									if (count($arResult["POST"]["ORDER_PROP_".$arOrderProps["ID"]]) > 0)
									{
										for ($i = 0; $i < count($arResult["POST"]["ORDER_PROP_".$arOrderProps["ID"]]); $i++)
										{
											?><input type="hidden" name="ORDER_PROP_<?= $arOrderProps["ID"] ?>[]" value="<?= $arResult["POST"]["ORDER_PROP_".$arOrderProps["ID"]][$i] ?>"><?
										}
									}
									else
									{
										?><input type="hidden" name="ORDER_PROP_<?= $arOrderProps["ID"] ?>[]" value=""><?
									}
								}
								else
								{
									?><input type="hidden" name="ORDER_PROP_<?= $arOrderProps["ID"] ?>" value="<?= $arResult["POST"]["ORDER_PROP_".$arOrderProps["ID"]] ?>"><?
								}
							}
						}
					
						if ($arResult["CurrentStep"] > 3 && $arResult["CurrentStep"] < 6)
						{
							?><input type="hidden" name="DELIVERY_ID" value="<?= is_array($arResult["DELIVERY_ID"]) ? implode(":", $arResult["DELIVERY_ID"]) : IntVal($arResult["DELIVERY_ID"]) ?>"><? 
						}	
					
						if ($arResult["CurrentStep"] > 4 && $arResult["CurrentStep"] < 6)
						{
							?>
							<input type="hidden" name="TAX_EXEMPT" value="<?= $arResult["TAX_EXEMPT"] ?>">
							<input type="hidden" name="PAY_SYSTEM_ID" value="<?= $arResult["PAY_SYSTEM_ID"] ?>">
							<input type="hidden" name="PAY_CURRENT_ACCOUNT" value="<?= $arResult["PAY_CURRENT_ACCOUNT"] ?>">
							<?
						}
						
						if ($arResult["CurrentStep"] < 6)
						{
							?><input type="hidden" name="CurrentStep" value="<?= ($arResult["CurrentStep"] + 1) ?>"><?
						}
					
					
						if ($arResult["CurrentStep"] < 6)
						{
							?></form><?
						}	
					?>
				
				</div>	
				<div class="contentBottomOrder"></div>
			</div>
			
			<? 
			if($arResult["CurrentStep"] < 6)
			{
				?>
				<div class="stepRight">
					<p><span>*</span> Поля, помеченные звездочкой, обязательны для заполнения.</p>
					<p class="last"><span> <a href="/personal/cart/" target="_blank">Кол-во товаров <?=$arResult['BASKET_COUNT_ELEMENT']?>, на сумму <strong><?=$arResult['ORDER_PRICE']?></strong> <span class="curency">&#8399;</span></a></span></p>
				</div>
				<?
			}
			else
			{
				/*
				?>
				<div class="stepRight">
					<p>Вы можете следить за выполнением своего заказа в <a href="#">Персональном разделе сайта</a>. Обратите внимание, что для входа в этот раздел вам необходимо будет ввести логин и пароль пользователя сайта.</p>
					<p>Для того, чтобы аннулировать заказ, воспользуйтесь функцией отмены заказа, которая доступна в вашем <a href="#">персональном разделе сайта</a> .</p>
					<p class="last">
						Пожалуйста, при обращении к администрации интернет-магазина обязательно указывайте номер вашего заказа <b>(№<?=intval($_GET['ORDER_ID'])?>)</b>. 
					</p>
				</div>
				<?
				*/					
			}
			?>
			<div class="clear"></div>
		</div>
	</div>		
	
	<? 
}



