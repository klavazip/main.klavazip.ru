<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$i_CityID = intval($_POST['city']);
if( $i_CityID > 0 )
{
	$rs_Delivery = CSaleDelivery::GetList(array("PRICE" => "ASC", "NAME" => "ASC"), array("LID" => SITE_ID, "ACTIVE" => "Y", "LOCATION" => $i_CityID), false, false, array());
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
}
else
{
	ob_start();
	?>
	<label> 
		<input type="radio" onchange="klava.order.selectDelivery(this)" value="4" name="DELEVERY" data-price="250" data-pred="Y" data-address="Y"> 
		250 руб. от 3 до 7 дн. 
		<span class="desc">
		Почта России 1-ый класс Без наложенного платежа<br>
		</span>
	</label>
				
	<label> 
		<input type="radio" onchange="klava.order.selectDelivery(this)" value="7" name="DELEVERY" data-price="800" data-pred="N" data-address="Y"> 
		800 руб. от 7 до 12 дн. 
		<span class="desc">
			Почта России 1-ый класс C наложенным платежом<br>
		</span>
	</label>
	<?
	
	$html = ob_get_contents();
	ob_end_clean();
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
}		



