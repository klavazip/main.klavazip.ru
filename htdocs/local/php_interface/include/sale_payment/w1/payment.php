<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
$iTestTransaction = CSalePaySystemAction::GetParamValue("TEST_TRANSACTION");
$strYourInstId = CSalePaySystemAction::GetParamValue("SHOP_ID");
?>
<?php /*
<table border="0" cellspacing="0" cellpadding="1" width="100%"><tr><td class="tableborder">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<form action="https://select.worldpay.com/wcc/purchase" method="post" target="_blank">
	<tr>
		<td align="center" class="tablebody" colspan="2">
			<font class="tablebodytext">
			
			<input type="hidden" name="instId" value="<?= $strYourInstId ?>">
			<input type="hidden" name="cartId" value="<?= IntVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"]) ?>">
			<input type="hidden" name="amount" value="<?= htmlspecialchars($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["SHOULD_PAY"]) ?>">
			<input type="hidden" name="currency" value="<?= htmlspecialchars($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"]) ?>">
			<?if (IntVal($iTestTransaction) > 0):?>
				<input type="hidden" name="testMode" value="<?= $iTestTransaction ?>">
			<?endif;?>
			<input type="hidden" name="desc" value="Order #<?= IntVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"]) ?>">

			<!--  order properties codes ->  -->
			<?
			$arTmp = array("name" => "PAYER_NAME", "tel" => "PHONE", "email" => "EMAIL",
					"fax" => "FAX", "address" => "ADDRESS", "postcode" => "ZIP",
					"country" => "COUNTRY"
				);
			foreach ($arTmp as $key => $value)
			{
				if (($val = CSalePaySystemAction::GetParamValue($value)) !== False)
				{
					?><input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($val) ?>"><?
				}
			}
			?>

			<input type="hidden" name="MC_CurrentStep" value="<?= IntVal($GLOBALS["CurrentStep"]) ?>">
			<input type="submit" value="Submit to WorldPay for Payment Now" class="inputbutton">
			</font>
		</td>
	</tr>
	</form>
</table>
</td></tr></table>
*/ ?>

<?php
//		| ПРОВЕРКИ РЕЗУЛЬТАТА |
//		V							 V
// Функция, которая возвращает результат в Единую кассу
function print_answer($result, $description)
{
  print "WMI_RESULT=" . strtoupper($result) . "&";
  //print "WMI_DESCRIPTION=" .urlencode($description);
  print "WMI_DESCRIPTION=".$description;
  exit();
}

if(isset($_POST["WMI_MERCHANT_ID"]))
{
	// Проверка наличия необходимых параметров в POST-запросе
	if (!isset($_POST["WMI_PAYMENT_NO"]))
  		print_answer("Retry", "Отсутствует параметр WMI_PAYMENT_NO");

	if (!isset($_POST["WMI_ORDER_STATE"]))
  		print_answer("Retry", "Отсутствует параметр WMI_ORDER_STATE");

  if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED")
  {
    // TODO: Пометить заказ, как «Оплаченный» в системе учета магазина
    print_answer("Ok", "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!");
  }
  else if (strtoupper($_POST["WMI_ORDER_STATE"]) == "PROCESSING")
  {
    // TODO: Пометить заказ, как «Оплаченный» в системе учета магазина
    print_answer("Ok", "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!");
    // Данная ситуация возникает, если в платежной форме WMI_AUTO_ACCEPT=0.
    // В этом случае интернет-магазин может принять оплату или отменить ее.
  }
  else if (strtoupper($_POST["WMI_ORDER_STATE"]) == "REJECTED")
  {
    // TODO: Пометить заказ, как «Неоплаченный» в системе учета магазина
    print_answer("Ok", "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " отменен!");
  }
  else
  {
	// Случилось что-то странное, пришло неизвестное состояние заказа
    print_answer("Retry", "Неверное состояние ". $_POST["WMI_ORDER_STATE"]);
  }
  
  $goodEvent=true;
}
?>
<?//echo'<pre>';var_dump($arOrder);echo'</pre>'?>
<?php
//		| ФОРМА ОТПРАВКИ |
//		V					  V 
if(!isset($goodEvent))
{ ?>
	<div>Заказ №<?=$arOrder["ID"]?></div>
	<div>Стоимость заказа <?=$arOrder["PRICE"]?> руб.</div>
	<form method="post" action="https://merchant.w1.ru/checkout/default.aspx" accept-charset="UTF-8">
		  <input type="hidden" name="WMI_MERCHANT_ID"    value="155327560998"/>
		  <input type="hidden" name="WMI_PAYMENT_AMOUNT" value="<?=$arOrder["PRICE"]?>"/>
		  <input type="hidden" name="WMI_CURRENCY_ID"    value="643"/>
		  <input type="hidden" name="WMI_DESCRIPTION"    value="Заказ №<?=$arOrder["ID"]?>"/>
		  <input type="hidden" name="WMI_SUCCESS_URL"    value="https://myshop.ru/w1/paid.php"/>
		  <input type="hidden" name="WMI_FAIL_URL"       value="https://myshop.ru/w1/fail.php"/>
		  <input type="hidden" name="WMI_PAYMENT_NO"	    value="<?=$_GET["ORDER_ID"]?>">
		  <input type="submit" value="Оплатить"/>
	</form>
<?php } else { ?>Заказ успешно оплачен.<?php } ?>
<pre><?//var_dump($_POST)?></pre>