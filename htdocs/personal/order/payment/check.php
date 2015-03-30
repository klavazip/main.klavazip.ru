<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
CModule::IncludeModule('iblock');
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");


ob_start();

	global $USER;
	$month = array(1=>'января',2=>'февраля',3=>'марта',4=>'апреля',5=>'мая',6=>'июня', 7=>'июля',8=>'августа',9=>'сентября',10=>'октября',11=>'ноября',12=>'декабря');
	$validBefore=date('d.m.Y',strtotime('+5 day'));//'10.02.2012'
	$title='Платежное поручение';
	$nowDate=date('d ').$month[intval(date('m'))].date(' Y');//'10 февраля 2012'
	$orProps=array();
	$db_order = CSaleOrderPropsValue::GetList(array(),array("ORDER_ID"=>$_GET["ORDER_ID"]));
	while($arOrder1 = $db_order->GetNext())
	{
		$orProps[$arOrder1["CODE"]] = $arOrder1["VALUE"];
	}	

	if($arORES = CSaleOrder::GetByID($_GET["ORDER_ID"]))
	{
	    if($arORES['PERSON_TYPE_ID'] == '2')
	    {
	        $buyer=$orProps['COMPANY_NAME'].', ИНН '.$orProps['INN'].', КПП '.$orProps['KPP'].', тел.: '.$orProps['PHONE'];
	    }
	    elseif($arORES['PERSON_TYPE_ID'] == '3')
	    {
	        $buyer=$orProps['COMPANY_NAME'].' '.$orProps['FIRST_NAME'].' '.$orProps['SECOND_NAME'].', ИНН '.$orProps['INN'].' тел.: '.$orProps['PHONE'];
	    }
	}
 

	/**
	 * Сумма прописью
	 * @author runcore
	 */
	function num2str($inn, $stripkop=true) {
	    $nol = 'ноль';
	    $str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
	    $str[11] = array('','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать');
	    $str[10] = array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
	    $sex = array(
	        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
	        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
	    );
	    $forms = array(
	        array('копейка', 'копейки', 'копеек', 1), // 10^-2
	        array('рубль', 'рубля', 'рублей',  0), // 10^ 0
	        array('тысяча', 'тысячи', 'тысяч', 1), // 10^ 3
	        array('миллион', 'миллиона', 'миллионов',  0), // 10^ 6
	        array('миллиард', 'миллиарда', 'миллиардов',  0), // 10^ 9
	        array('триллион', 'триллиона', 'триллионов',  0), // 10^12
	    );
	    $out = $tmp = array();
	    // Поехали!
	    $tmp = explode('.', str_replace(',','.', $inn));
	    $rub = number_format($tmp[ 0], 0,'','-');
	    if ($rub== 0) $out[] = $nol;
	    // нормализация копеек
	    $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0,2) : '00';
	    $segments = explode('-', $rub);
	    $offset = sizeof($segments);
	    if ((int)$rub== 0) { // если 0 рублей
	        $o[] = $nol;
	        $o[] = morph( 0, $forms[1][ 0],$forms[1][1],$forms[1][2]);
	    }
	    else {
	        foreach ($segments as $k=>$lev) {
	            $sexi= (int) $forms[$offset][3]; // определяем род
	            $ri = (int) $lev; // текущий сегмент
	            if ($ri== 0 && $offset>1) {// если сегмент==0 & не последний уровень(там Units)
	                $offset--;
	                continue;
	            }
	            // нормализация
	            $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
	            // получаем циферки для анализа
	            $r1 = (int)substr($ri, 0,1); //первая цифра
	            $r2 = (int)substr($ri,1,1); //вторая
	            $r3 = (int)substr($ri,2,1); //третья
	            $r22= (int)$r2.$r3; //вторая и третья
	            // разгребаем порядки
	            if ($ri>99) $o[] = $str[100][$r1]; // Сотни
	            if ($r22>20) {// >20
	                $o[] = $str[10][$r2];
	                $o[] = $sex[ $sexi ][$r3];
	            }
	            else { // <=20
	                if ($r22>9) $o[] = $str[11][$r22-9]; // 10-20
	                elseif($r22> 0) $o[] = $sex[ $sexi ][$r3]; // 1-9
	            }
	            // Рубли
	            $o[] = morph($ri, $forms[$offset][ 0],$forms[$offset][1],$forms[$offset][2]);
	            $offset--;
	        }
	    }
	    // Копейки
	    if (!$stripkop) {
	        $o[] = $kop;
	        $o[] = morph($kop,$forms[ 0][ 0],$forms[ 0][1],$forms[ 0][2]);
	    }
	    return preg_replace("/\s{2,}/",' ',implode(' ',$o));
	}
 
	/**
	 * Склоняем словоформу
	 */
	function morph($n, $f1, $f2, $f5) {
	    $n = abs($n) % 100;
	    $n1= $n % 10;
	    if ($n>10 && $n<20) return $f5;
	    if ($n1>1 && $n1<5) return $f2;
	    if ($n1==1) return $f1;
	    return $f5;
	}
?>
<style>
	table, table tr, table tr td { vertical-align:top; margin:0; padding:0 }
	div, span { margin:0; padding:0 }
	.floatl { float:left }
	.clear { clear:both }
	
	.border { border: 1px solid #000000 }
	table.tdborder td { border-right: 1px solid #000000; border-bottom: 1px solid #000000 }
	table.tdborder { border-top: 1px solid #000000; border-left: 1px solid #000000 }
	.border2 { border-top-width: 2px!important; border-left-width: 2px!important }
	.bordert2 { border-top-width: 2px!important }
	.bl2 { border-left: 2px solid #000 }
	.br2 { border-right: 2px solid #000!important }
	.bt { border-top: 1px solid #000 }
	
	.nb { border:none!important }
	.nbt { border-top:none!important }
	.nbb { border-bottom:none!important }
	.nbl { border-left:none!important }
	.nbr { border-right:none!important }
	
	.check { font-family: Arial; font-size: 11px; color:#000000; margin: 0 auto; width: 1000px }
	.head { padding: 0px 150px 15px; text-align:center }
	.onespan { padding:0 60px 0 15px }
	.onediv { padding:0 35px 0 0 }
	.hr{ min-height:2px; border-bottom:2px solid #000 }
	.th td { font-weight:bold; padding:7px 0; text-align:center }
	.und { border-top:1px solid #000; font-size:11px;font-weight:normal }
	.sig, .und { width:300px; margin:0 10px; text-align:center }
	.sig { height:15px; margin-top: 20px }
	.shtamp { /*background:url(sht.png)*/ no-repeat 210px 0; margin-top: 10px; height: 190px }
	
	.w40 { width:40px }
	.w60 { width:60px }
	.w80 { width:80px }
	.w85 { width:85px }
	.w90 { width:90px }
	.w105 { width:105px }
	.w120 { width:120px }
	.w455 { width:455px }
	.w480 { width:480px }
	.w515 { width:515px }		
	.w700 { width:700px }
	
	
	.h8 { height:8px }
	.h13 { font-weight:bold; font-size: 13px; text-align:center }
	.h19 { font-weight:bold; font-size: 19px; margin: 25px 0 15px; padding-bottom: 15px }
	table.trh32 tr { height:32px }

	.fs11 { font-size: 11px }
	.fs13 { font-size: 13px }
	.fs14 { font-size: 14px }
	.b { font-weight:bold }
	.tar { text-align:right }
	.pt5 { padding-top:5px }
    
    #pechat {
        position:absolute;
        top:0;
        left:370px;
        z-index:11;
        width:130px;
        
    }
   #podpis {
        position: absolute;
        width:100px;
        z-index:11;
        top:-35px;
        left:230px;
    }
    #podpis2 {
        position: absolute;
        width:100px;
        z-index:11;
        top:20px;
        left:230px;
    }
    #stamp_area {
        position:relative;
        z-index:10;
    }
</style>
<div class="check">
	<div class="head">
		Внимание! Счет действителен до <?=$validBefore?>. Оплата данного счета означает согласие с условиями поставки товара.<br/>
		Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.<br/> 
		Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.
	</div>
	
    <div class="h13"><?=$title?></div>
	
	<table cellpadding="0" cellspacing="0" class="fs14 tdborder">
		<tr> <td class='w480 nbb'>АКБ «Абсолют Банк» (ОАО) Г.МОСКВА</td> <td class='w60'>БИК</td> <td class="w455 nbb">044525976</td> </tr>
		<tr> <td class="nbb">&nbsp</td> <td class="nbb">Сч. №</td> <td class="nbb">30101810500000000976</td> </tr>
		<tr> <td class="fs11">Банк получателя</td> <td>&nbsp</td> <td>&nbsp</td> </tr>
		<tr> 
			<td>
				<div class="floatl">ИНН<span class="onespan">7720750320</span></div>
				<div class="onediv floatl border nbb nbt">КПП</div><span class="onespan" style="padding: 0;">772001001</span>
                
                <div class="clear"></div>
			</td>
			<td class="nbb">Сч. №</td> <td class="nbb">40702810522000039245</td>
		</tr>
		<tr> <td class="nbb">ООО "Клава"</td> <td class="nbb">&nbsp</td> <td class="nbb">&nbsp</td> </tr>
		<tr> <td class="nbb">&nbsp</td> <td class="nbb">&nbsp</td> <td class="nbb">&nbsp</td> </tr>
		<tr> <td class="fs11">Получатель</td> <td>&nbsp</td> <td>&nbsp</td> </tr>
	</table>
	
	<div class="h19 hr">Счет на оплату № <?=$_GET['ORDER_ID']?> от <?=$nowDate?> г.</div>
	
	<table cellpadding="0" cellspacing="0" class="fs13 trh32">
		<tr> <td class='w90'>Поставщик:</td> <td class='b'>ООО "Клава", ИНН 7720750320, КПП 772001001, 111024, Москва г, Энтузиастов 2-я ул. дом №5, корпус 40, тел.: 7(495) 666-29-17, 8(800) 555-62-65</td> </tr>
		<tr> <td>Покупатель:</td> <td class='b'><?=$buyer?></td> </tr>
		<tr> <td>Назначение:</td> <td class='b'>Оплата по заказу клиента №<?=$USER->GetId()?></td> </tr>
	</table>
	
	<table cellpadding="0" cellspacing="0" class="tdborder border bordert2 fs11 nbb nbr nbl">
		<tr class="th fs13">
			<td class="w40 bl2">№</td> 
			<td class="w85">Артикул</td> 
			<td class="w515">Товары (работы, услуги)</td>
			<td colspan="2" class="w105">Количество</td> 
			<td class="w80">Цена</td> 
		<td class="w80">Ставка НДС</td> 
			<td class="w80">Сумма НДС</td> 
			<td class="w80">Скидка</td> 
			<td class="w80 br2">Сумма</td>
		</tr>
		<?
		$tovPriceSum = 0;
		$tovNum=0;
		
		$thisOrder=CSaleOrder::GetByID($_GET["ORDER_ID"]);
		$pid=intval($thisOrder["PRICE"])+intval($thisOrder["DISCOUNT_VALUE"]);
		$pid=$pid>=10000?($pid<50000?1:2):0;
		//var_dump($pid);
		$dbBasketItems = CSaleBasket::GetList( array(), array("ORDER_ID"=>$_GET["ORDER_ID"]),false,false);//,array("NAME","QUANTITY","PRICE")
		while($basketItems = $dbBasketItems->GetNext()) 
		{ 
			
			$RESS=CIBlockElement::GetList(array(),array("IBLOCK_ID" => "8", "ID"=>$basketItems['PRODUCT_ID']),false,false,array('PROPERTY_CML2_ARTICLE'));
	        $ressEl=$RESS->Fetch();
			$prices = getPricesByItemId($basketItems["PRODUCT_ID"]);

			$i_Price = $prices[$pid];
			
			$i_SummNds = ($i_Price / 100) * 0;
			$i_Summ = $i_Price - $i_SummNds;
			$i_AllSumm = $basketItems["PRICE"];
				
			
			$tovPriceSum+=$prices[$pid]*$basketItems["QUANTITY"]; $tovNum++; ?>
			<tr>
				<td class="tar bl2"><?=$tovNum?></td> 
				<td><?=$ressEl['PROPERTY_CML2_ARTICLE_VALUE']?></td> 
				<td><?=$basketItems["NAME"]?></td>
				<td class="tar"><?=number_format($basketItems["QUANTITY"],0,"","")?></td> <td>шт.</td>
				<td class="tar"><?=number_format($i_Summ, 2, ",", " ")?></td>
				
				<td class="tar">Без НДС</td>
				<td class="tar"><?=number_format($i_SummNds, 2, ",", " ")?></td>
				
				<td class="tar"><?=number_format($basketItems["PRICE"]-$prices[$pid],2,","," ")?></td>
				<td class="tar br2"><?=number_format($prices[$pid] * $basketItems["QUANTITY"],2,","," ")?></td>
			</tr>
			<?
		} 
		
		$i_SummDeliveryNds = ($thisOrder["PRICE_DELIVERY"] / 100) * 0;
		$i_SummDelivery = $thisOrder["PRICE_DELIVERY"] - $i_SummDeliveryNds;
		$i_AllSummDelivery =  $thisOrder["PRICE_DELIVERY"];
		
		?>
		
		<tr>
			<td class="tar bl2"><?=$dbBasketItems->SelectedRowsCount()?></td> 
			<td>&nbsp;</td> 
			<td>Доставка</td>
			<td class="tar">1</td> <td>шт.</td>
			<td class="tar"><?=number_format($i_SummDelivery, 2, ",", " ")?></td>
			<td class="tar">Без НДС</td>
			<td class="tar"><?=number_format($i_SummDeliveryNds,2,","," ")?></td>
			<td class="tar">0.00</td>
			<td class="tar br2"><?=number_format($i_AllSummDelivery, 2, ",", " ")?></td>
		</tr>
		
		<?$dosSum=$thisOrder["PRICE_DELIVERY"];?>
		<?$tovPriceSum+=$dosSum;?>
		<? /*?>
		<tr class="tar b fs13">
			<td colspan="9" class="w700 nbb nbr bt pt5">Доставка:</td> 
			<td class="nbb nbr bt pt5"><?=number_format($dosSum, 2, ",", " ")?></td>
		</tr>
		<? */ ?>

		<tr class="tar b fs13">
			<td colspan="9" class="w700 nbb nbr bt pt5">Итого:</td> 
			<td class="nbb nbr bt pt5"><?=number_format($tovPriceSum, 2, ",", " ")?></td>
		</tr>
		
		
		<?/*<tr class="tar b fs13"> 
			<td colspan="9" class="w700 nbb nbr">Без НДС:</td> 
			<td class="nbb nbr">
				<?=($tovPriceSum / 100) * 0?>
			</td> 
		</tr>
		
		
		<tr class="tar b fs13">
			<td colspan="9" class="w700 nbb nbr">Итого:</td> 
			<td class="nbb nbr"><?=number_format($tovPriceSum, 2, ",", " ")?></td>
		</tr>*/?>
		
		<tr class="fs13"> 
			<td colspan="8" class="nb pt5">
				Всего наименований <?=$tovNum?>, на сумму <?=number_format($tovPriceSum,2,","," ")?> RUB
			</td>
		</tr>
		<tr class="b fs13"> 
			<td colspan="8" class="nb"><?=num2str(number_format($tovPriceSum,0,"",""))?> 00 копеек, НДС не облагается</td> 
		</tr>
	</table>

	
	<div class="hr h8"></div>

	
	<div class="shtamp" id="stamp_area">
		<table cellpadding="0" cellspacing="0" class="fs13 b mxx">
			<tr>
				<td class="w120">Руководитель</td> <td><div class="sig"></div><div class="und">подпись</div></td>
				<td><div class="sig">Кошелев Н.А.</div><div class="und">расшифровка подписи</div></td> 
			</tr>
			<tr>
				<td>Бухгалтер</td> <td><div class="sig"></div><div class="und">подпись</div></td>
				<td><div class="sig">Кошелев Н.А.</div><div class="und">расшифровка подписи</div></td>
			</tr>
			<tr>
				<td>Менеджер</td> <td><div class="sig"></div><div class="und">подпись</div></td>
				<td><div class="sig"></div><div class="und">расшифровка подписи</div></td>
			</tr>
		</table>
        <img id="podpis" src="http://klavazip.ru/bitrix/components/bxmaker/sale.order.full/templates/.default/images/podpis.png" />
        <img id="podpis2" src="http://klavazip.ru/bitrix/components/bxmaker/sale.order.full/templates/.default/images/podpis.png" />
        <img id="pechat" src="http://klavazip.ru/bitrix/components/bxmaker/sale.order.full/templates/.default/images/stamp.png" />
	</div>
</div>

<script type="text/javascript">//window.print();</script>

<?
if($arAuthResult===true){echo "Y";}
else {echo "$arAuthResult[MESSAGE]";}
die();
















