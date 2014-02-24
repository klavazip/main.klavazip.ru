<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$i_CityID = intval($_POST['city']);


$rs_Delivery = CSaleDelivery::GetList(
	array('PRICE' => 'ASC', 'NAME' => 'ASC'),
	array(
		'LID' 		=> SITE_ID,
		'ACTIVE'   	=> 'Y',
		'LOCATION' 	=> $i_CityID
	)
);


	ob_start();
	?>
	<table>
		<tr>
			<th class="name">Способы доставки</th>
			<th class="day">Сроки</th>
			<th class="price">Цена</th>
		</tr>
	</table>
		
	<?
	if($rs_Delivery->SelectedRowsCount() > 0 && $i_CityID > 0)
	{
	
		while ($ar_Delivery = $rs_Delivery->Fetch())
		{
			// Этот фиерический пиздец пока так работает
			$s_ShowAdres = (strpos($ar_Delivery['DESCRIPTION'], 'Самовывоз') === false) ? 'Y': 'N';
			$s_Prodoplata = ( strpos($ar_Delivery['NAME'], '(C предоплатой)') !== false ) ? 'Y' : 'N';
			$s_Prodoplata = (( strpos($ar_Delivery['NAME'], '(C предоплатой)' ) !== false ) || ( strpos($ar_Delivery['NAME'], 'Без наложенного платежа' ) ) ) ? 'Y' : 'N';
			?>
			<div 
				class="item-delivery" 
				onclick="klava.infodelivery.selectDelivery(this)"
				data-address="<?=$s_ShowAdres?>" 
				data-pred="<?=$s_Prodoplata?>"
				>
				<table>
					<tr>
						<td class="company">
							<div class="name">
								<img src="<?=CFile::GetPath($ar_Delivery['LOGOTIP'])?>" alt="" />
								<?=$ar_Delivery['NAME']?>
							</div>
							
							<div class="desc-delivery">
								<?=$ar_Delivery['DESCRIPTION']?>
							</div> 
						</td>
						<td class="day">от <?=$ar_Delivery['PERIOD_FROM']?> <?=(intval($ar_Delivery['PERIOD_TO']) > 0) ? 'до ' . $ar_Delivery['PERIOD_TO'] : ''?> дн.</td>
						<td class="price"><?= floatval($ar_Delivery['PRICE'])?> <?=KlavaMain::RUB?></td>
					</tr>
				</table>	
			</div>	
			<?
		}
	}
	else
	{
		?>
		<div data-pred="Y" data-address="Y" onclick="klava.infodelivery.selectDelivery(this)" class="item-delivery">
			<table>
				<tr>
					<td class="company">
						<div class="name">
							<img alt="" src="/upload/sale/delivery/logotip/87b/logo_pochta.png">Почта России 1-ый класс Без наложенного платежа
						</div>
						<div class="desc-delivery"></div> 
					</td>
					<td class="day">от 3 до 7 дн.</td>
					<td class="price">250 руб.</td>
				</tr>
			</table>	
		</div>
		<div data-pred="N" data-address="Y" onclick="klava.infodelivery.selectDelivery(this)" class="item-delivery">
			<table>
				<tr>
					<td class="company">
						<div class="name">
							<img alt="" src="/upload/sale/delivery/logotip/87b/logo_pochta.png">Почта России 1-ый класс C наложенным платежом										
						</div>
						<div class="desc-delivery"></div> 
					</td>
					<td class="day">от 7 до 12 дн.</td>
					<td class="price">800 руб.</td>
				</tr>
			</table>	
		</div>
		<?
	}	
	
	
?>		
				
	<br /><br />
		
	<table>
		<tr>
			<th class="name">Способы оплаты</th>
			<th class="price"></th>
			<th class="price">Коммисия</th>
		</tr>
	</table>	
	<? 
	$rs_PaySystem = CSalePaySystem::GetList(array("SORT" => "ASC"), array("ACTIVE" => "Y"));
	while ($ar_PaySystem = $rs_PaySystem->Fetch())
	{
		$ar_Desc = explode('|', $ar_PaySystem['DESCRIPTION']);
		?>
		<div 
			class="item-payment" 
			onclick="klava.infodelivery.selectPayment(this)"
			data-id="<?=$ar_PaySystem['ID']?>"
			>
			<table>
				<tr>
					<td class="name">
						<div class="payment-name"><img src="<?=$ar_Desc[0]?>" alt="" /><?=$ar_PaySystem['NAME']?></div>
					</td>
					<td class="td-error"> <div class="error"> Способы оплаты не доступен </div> </td>
					<td class="proc">
						<div class="t"><?=(strlen($ar_Desc[1]) > 0) ? $ar_Desc[1] : '-' ?></div>
					</td>	
				</tr>
			</table>	
		</div>
		<?
	}
	
	$html = ob_get_contents();
	ob_end_clean();
		
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
				//echo $html;
				
/*
$rs_Delivery = CSaleDelivery::GetList(
	array("PRICE" => "ASC", "NAME" => "ASC"),
	array(
		"LID" 					=> SITE_ID,
		//"+<=WEIGHT_FROM" => $ORDER_WEIGHT,
		//"+>=WEIGHT_TO" => $ORDER_WEIGHT,
		//"+<=ORDER_PRICE_FROM" 	=> $ORDER_PRICE,
		//"+>=ORDER_PRICE_TO"   	=> $ORDER_PRICE,
		"ACTIVE"   				=> "Y",
		"LOCATION" 				=> $i_CityID
	),
	false,
	false,
	array()
);

if($rs_Delivery->SelectedRowsCount() > 0)
{
	ob_start();
		
		$i = 0; 
		while ($ar_Delivery = $rs_Delivery->Fetch())
		{
			// Этот фиерический пиздец пока так работает
			$s_ShowAdres = (strpos($ar_Delivery['DESCRIPTION'], 'Самовывоз') === false) ? 'Y': 'N';
			$s_Prodoplata = ( strpos($ar_Delivery['NAME'], '(C предоплатой)') !== false ) ? 'Y' : 'N';
			$s_Prodoplata = (( strpos($ar_Delivery['NAME'], '(C предоплатой)' ) !== false ) || ( strpos($ar_Delivery['NAME'], 'Без наложенного платежа' ) ) ) ? 'Y' : 'N';
			?>
			<label> 
				<input 
					<?=($i == 0) ? 'checked="checked"' : ''?> 
					type="radio" 
					data-address="<?=$s_ShowAdres?>" 
					data-pred="<?=$s_Prodoplata?>"
					data-price="<?=floatval($ar_Delivery['PRICE'])?>"
					name="DELEVERY" 
					value="<?=$ar_Delivery['ID']?>"
					onchange="klava.order.selectDelivery(this)"
					> 
					<?= floatval($ar_Delivery['PRICE'])?> <?=KlavaMain::RUB?> от <?=$ar_Delivery['PERIOD_FROM']?> <?=(intval($ar_Delivery['PERIOD_TO']) > 0) ? 'до ' . $ar_Delivery['PERIOD_TO'] : ''?> дн. 
				<span class="desc">
					<?=$ar_Delivery['NAME']?><br />
					<?=$ar_Delivery['DESCRIPTION']?>
				</span>
			</label>
			<?
			$i++;
		}
		
		$html = ob_get_contents();
	ob_end_clean();
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
}
*/