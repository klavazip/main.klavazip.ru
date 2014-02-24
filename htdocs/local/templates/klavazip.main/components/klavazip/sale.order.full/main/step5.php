<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//echo '<pre>', print_r($arResult).'</pre>';

$ar_ResultPoperty = array();
foreach ($arResult["ORDER_PROPS_PRINT"] as $ar_Value)
{
	$ar_ResultPoperty[$ar_Value['GROUP_NAME']][] = $ar_Value;
}


foreach ($ar_ResultPoperty as $s_GroupName => $ar_Proeprty)
{
	?>
	<div class="oneBlockStepInf"><h4><?=$s_GroupName?></h4></div>
	<div class="oneBlockStepInf">
		<?
		foreach ($ar_Proeprty as $ar_Val)
		{
			if($ar_Val['TYPE'] == 'LOCATION')
				continue;
			
			if(strLen($ar_Val["VALUE_FORMATED"])>0)
			{
				?>
				<div class="oneLineInfStep">
					<p><?=$ar_Val['NAME']?></p>
					<p><?=$ar_Val["VALUE_FORMATED"]?></p>
					<div class="clear"></div>
				</div>
				<?
			}	
		}
	?></div><?
}
?>

<div class="oneBlockStepInf"><h4>Оплата и доставка</h4></div>
<div class="oneBlockStepInf">
	<div class="oneLineInfStep">
		<p>Способ доставки</p>
		<p><?=$arResult["DELIVERY"]["NAME"]?></p>
		<div class="clear"></div>
	</div>
	<div class="oneLineInfStep">
		<p>Способ оплаты</p>
		<p><?=$arResult["PAY_SYSTEM"]["PSA_NAME"]?></p>
		<div class="clear"></div>
	</div>
</div>


<div class="oneBlockStepInf"><h4>Состав заказа</h4></div>

<div class="oneBlockStepInf">
	<div class="oneLineInfStep">
		<p>Состав заказа</p>
		<div class="tableStepOrder">
			<table cellspacing="0" cellpadding="0">
				<?
				$i = 1;
				foreach($arResult["BASKET_ITEMS"] as $arBasketItems)
				{
					?>
					<tr>
						<td><?=$i?></td>
						<td><a target="_blank" href="<?=$arBasketItems['DETAIL_PAGE_URL']?>"><?=$arBasketItems['NAME']?></a></td>
						<td><?=intval($arBasketItems['QUANTITY'])?> <span>шт</span></td>
						<td><?=intval($arBasketItems['PRICE'])?> <span class="curency">⃏</span></td>
					</tr>
					<?
					$i++;
				}
				?>
			</table>

			<p class="summStep">Сумма по товарам: <span><strong><?=str_replace('руб', '', $arResult["ORDER_PRICE_FORMATED"])?> <span class="curency">⃏</span></strong></span></p>
			
			<? 
			if(strlen($arResult["DELIVERY_PRICE_FORMATED"]) > 0)
			{
				?><p class="summStep">Стоимость доставки: <span><strong><?=str_replace('руб', '', $arResult["DELIVERY_PRICE_FORMATED"])?> <span class="curency">⃏</span></strong></span></p><?				
			}
			?>
			
			<p class="summStep total">Итого: <span><strong><?=str_replace('руб', '', $arResult['ORDER_TOTAL_PRICE_FORMATED'])?> <span class="curency">⃏</span></strong></span></p>
		</div>
		<div class="clear"></div>
	</div>								
</div>


<div class="oneBlockStepInf last">
	<div class="oneLineInfStep">
		<p>Комментарии к заказу</p>
		<div class="commentStep">
			<textarea rows="1" cols="1" name="ORDER_DESCRIPTION"></textarea>
			<div class="lineCheckDoc">
				<input type="checkbox" class="styled check" name="check_1" />
				<label for="check_1">Я согласен с <a href="#">условиями заказа</a></label>								
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>								
</div>

<? /*?>
==========							
							
							
<table border="0" cellspacing="0" cellpadding="5">
<tr>
	<td valign="top" width="60%" align="right">
		<input type="submit" name="contButton" value="<?= GetMessage("SALE_CONFIRM")?>">
	</td>
	<td valign="top" width="5%" rowspan="3">&nbsp;</td>
	<td valign="top" width="35%" rowspan="3">
		<?echo GetMessage("STOF_CORRECT_PROMT_NOTE")?><br /><br />
		<?echo GetMessage("STOF_CONFIRM_NOTE")?><br /><br />
		<?echo GetMessage("STOF_CORRECT_ADDRESS_NOTE")?><br /><br />
		<?echo GetMessage("STOF_PRIVATE_NOTES")?>
	</td>
</tr>
<tr>
	<td valign="top" width="60%">
		<?
		if(!empty($arResult["ORDER_PROPS_PRINT"]))
		{
			?>
			<b><?echo GetMessage("STOF_ORDER_PARAMS")?></b><br /><br />
			<table class="sale_order_full_table">
				<?
				foreach($arResult["ORDER_PROPS_PRINT"] as $arProperties)
				{
					if ($arProperties["SHOW_GROUP_NAME"] == "Y")
					{
						?>
						<tr>
							<td colspan="2" align="center"><b><?= $arProperties["GROUP_NAME"] ?></b></td>
						</tr>
						<?
					}
					
					if(strLen($arProperties["VALUE_FORMATED"])>0)
					{
						?>
						<tr>
							<td width="50%" align="right" valign="top">
								<?= $arProperties["NAME"] ?>:
							</td>
							<td width="50%"><?=$arProperties["VALUE_FORMATED"]?></td>
						</tr>
						<?
					}
				}
				?>
			</table>
			<?
		}
		?>
		<br /><br />
		<b><?echo GetMessage("STOF_PAY_DELIV")?></b><br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td width="50%" align="right"><?= GetMessage("SALE_DELIV_SUBTITLE")?>:</td>
				<td width="50%">
					<?
					//echo "<pre>"; print_r($arResult); echo "</pre>";
					if (is_array($arResult["DELIVERY"]))
					{
						echo $arResult["DELIVERY"]["NAME"];
						if (is_array($arResult["DELIVERY_ID"]))
						{
							echo " (".$arResult["DELIVERY"]["PROFILES"][$arResult["DELIVERY_PROFILE"]]["TITLE"].")";
						}
					}
					elseif ($arResult["DELIVERY"]=="ERROR")
					{
						echo ShowError(GetMessage("SALE_ERROR_DELIVERY"));
					}
					else
					{
						echo GetMessage("SALE_NO_DELIVERY");
					}
					?>
				</td>
			</tr>
			<?if(is_array($arResult["PAY_SYSTEM"]) || $arResult["PAY_SYSTEM"]=="ERROR" || $arResult["PAYED_FROM_ACCOUNT"] == "Y")
			{
				?>
				<tr>
					<td width="50%" align="right"><?= GetMessage("SALE_PAY_SUBTITLE")?>:</td>
					<td width="50%">
						<?
						if($arResult["PAYED_FROM_ACCOUNT"] == "Y")
							echo " (".GetMessage("STOF_PAYED_FROM_ACCOUNT").")";
						elseif (is_array($arResult["PAY_SYSTEM"]))
						{
							echo $arResult["PAY_SYSTEM"]["PSA_NAME"];
						}
						elseif ($arResult["PAY_SYSTEM"]=="ERROR")
						{
							echo ShowError(GetMessage("SALE_ERROR_PAY_SYS"));
						}
						elseif($arResult["PAYED_FROM_ACCOUNT"] != "Y")
						{
							echo GetMessage("STOF_NOT_SET");
						}
						
						?>				
					</td>
				</tr>
				<?
			}
			?>
		</table>

		<br /><br />
		<b><?= GetMessage("SALE_ORDER_CONTENT")?></b><br /><br />

		<table class="sale_order_full data-table">
			<tr>
				<th><?echo GetMessage("SALE_CONTENT_NAME")?></th>
				<th><?echo GetMessage("SALE_CONTENT_PROPS")?></th>
				<th><?echo GetMessage("SALE_CONTENT_PRICETYPE")?></th>
				<th><?echo GetMessage("SALE_CONTENT_DISCOUNT")?></th>
				<th><?echo GetMessage("SALE_CONTENT_WEIGHT")?></th>
				<th><?echo GetMessage("SALE_CONTENT_QUANTITY")?></th>
				<th><?echo GetMessage("SALE_CONTENT_PRICE")?></th>
			</tr>
			<?
			foreach($arResult["BASKET_ITEMS"] as $arBasketItems)
			{
				?>
				<tr>
					<td><?=$arBasketItems["NAME"]?></td>
					<td>
						<?
						foreach($arBasketItems["PROPS"] as $val)
						{
							echo $val["NAME"].": ".$val["VALUE"]."<br />";
						}
						?>
					</td>
					<td><?=$arBasketItems["NOTES"]?></td>
					<td><?=$arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"]?></td>
					<td><?=$arBasketItems["WEIGHT_FORMATED"]?></td>
					<td><?=$arBasketItems["QUANTITY"]?></td>
					<td align="right"><?=$arBasketItems["PRICE_FORMATED"]?></td>
				</tr>
				<?
			}
			?>
			<tr>
				<td align="right"><b><?=GetMessage("SALE_CONTENT_WEIGHT_TOTAL")?>:</b></td>
				<td align="right" colspan="6"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
			</tr>
			<tr>
				<td align="right"><b><?=GetMessage("SALE_CONTENT_PR_PRICE")?>:</b></td>
				<td align="right" colspan="6"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
			</tr>
			<?
			if (doubleval($arResult["DISCOUNT_PRICE_FORMATED"]) > 0)
			{
				?>
				<tr>
					<td align="right"><b><?echo GetMessage("SALE_CONTENT_DISCOUNT")?>:</b></td>
					<td align="right" colspan="6"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?>
						<?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?>
							(<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)
						<?endif;?>
					</td>
				</tr>
				<?
			}
			if (doubleval($arResult["VAT_PRICE"]) > 0)
			{
				?>
				<tr>
					<td align="right">
						<b><?echo GetMessage("SALE_CONTENT_VAT")?>:</b>
					</td>
					<td align="right" colspan="6"><?=$arResult["VAT_PRICE_FORMATED"]?></td>
				</tr>
				<?
			}
			if(!empty($arResult["arTaxList"]))
			{
				foreach($arResult["arTaxList"] as $val)
				{
					?>
					<tr>
						<td align="right"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
						<td align="right" colspan="6"><?=$val["VALUE_MONEY_FORMATED"]?></td>
					</tr>
					<?
				}
			}
			if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
			{
				?>
				<tr>
					<td align="right">
						<b><?echo GetMessage("SALE_CONTENT_DELIVERY")?>:</b>
					</td>
					<td align="right" colspan="6"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
				</tr>
				<?
			}
			?>
			<tr>
				<td align="right"><b><?= GetMessage("SALE_CONTENT_ITOG")?>:</b></td>
				<td align="right" colspan="6"><b><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></b>
				</td>
			</tr>
			<?
			if (doubleval($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
			{
				?>
				<tr>
					<td align="right"><b><?echo GetMessage("STOF_PAY_FROM_ACCOUNT1")?></b></td>
					<td align="right" colspan="6"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
				</tr>
				<?
			}
			?>
		</table>

		<br /><br />
		<b><?= GetMessage("SALE_ADDIT_INFO")?></b><br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td width="50%" align="right" valign="top">
					<?= GetMessage("SALE_ADDIT_INFO_PROMT")?>
				</td>
				<td width="50%" valign="top">
					<textarea rows="4" cols="40" name="ORDER_DESCRIPTION"><?=$arResult["ORDER_DESCRIPTION"]?></textarea>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td valign="top" width="60%" align="right">
		<?if(!($arResult["SKIP_FIRST_STEP"] == "Y" && $arResult["SKIP_SECOND_STEP"] == "Y" && $arResult["SKIP_THIRD_STEP"] == "Y" && $arResult["SKIP_FORTH_STEP"] == "Y"))
		{
			?>
			<input type="submit" name="backButton" value="&lt;&lt; <?echo GetMessage("SALE_BACK_BUTTON")?>">
			<?
		}
		?>
		<input type="submit" name="contButton" value="<?= GetMessage("SALE_CONFIRM")?>">
	</td>
</tr>
</table>

<? */ ?>