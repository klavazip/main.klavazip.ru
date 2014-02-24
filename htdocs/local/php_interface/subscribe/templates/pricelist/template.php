<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
global $SUBSCRIBE_TEMPLATE_RUBRIC;
$SUBSCRIBE_TEMPLATE_RUBRIC = $arRubric;
$SUBSCRIBE_TEMPLATE_RESULT = false;
?>
<?
$BID = 8;//ID инфоблока данных
$priceTypeID = 4;// ID типа цен для вывода, http://klavazip.ru/bitrix/admin/cat_group_admin.php?lang=ru
$mainUrl = 'http://klavazip.ru';//url сайта, подставляемый для ссылок/картинок
$arAD = array();//идент-ры РК, если требуется; можно раскомментировать примеры ниже
#$arAD = array('catalog', 'subscribe');//фиксированные и неизменные параметры РК
#$arAD = array('catalog', date('dmY'));//параметры РК, зависящие от даты выпуска
?>

<center>
<table style="padding:5px 20px; border-spacing:0;background: url('http://klavazip.ru/images/klavazip/bg.png') repeat-x left top;" >
	<tr>
        <td>
            <a href="http://klavazip.ru/" title="На главное"><img src="http://klavazip.ru/images/klavazip/mainlogo/logo_191x.png" /></a>
        </td>
    </tr>
    <tr>
		<td>
			<table style="width:700px; background:#ffffff; margin:10px 10px 20px; border-radius:5px;" >
				<tr>
					<td width="700">
					   <table width="700" height="10" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td></td>
							</tr>
						</table>
						<table width="700" cellpadding="8" cellspacing="0" border="0">
							<tr>
								<td bgcolor="#F7797B" width="3">
								</td>
								<td width="697" bgcolor="#ffffff" align="justify" valign="middle" style="color:#000000;font-size:14px;font-family:'Arial',Helvetica,sans-serif;">
									<p><strong><center>Здравствуйте, #USER_NAME#!</center></strong></p>
									<p style="font-size:12px;font-family:'Arial',Helvetica,sans-serif;font-weight: normal">Предлагаем вашему вниманию обновленный прайс-лист нашей продукции, который находится в приложении к письму.</p>
								</td>
							</tr>
						</table>
						<table width="700" height="10" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td></td>
							</tr>
						</table>
						<table width="700" cellpadding="8" cellspacing="0" border="0" >
							<tr>
								<td bgcolor="#F7797B" width="3"></td>
								<td bgcolor="#ffffff" width="511" align="justify" valign="top" style="font-size:12px;font-family:'Arial',Helvetica,sans-serif;font-weight: normal">
									Для отказа от рассылки в дальнейшем необходимо пройти по <a href="<?= $mainUrl?>/personal/subscribe/unsubscribe/?mid=#MAIL_ID#&amp;mhash=#MAIL_MD5#&<?= implode('&', $arAD)?>" target="_blank">данной ссылке</a>.
								</td>
								<td bgcolor="#F7797B" align="right" width="56" style="color:#ffffff;font-size:12px;font-family:'Arial',Helvetica,sans-serif;">
									<strong>
									<p>Тел:
									<br>Тел:
									<br>ICQ:
									<br>Email:
									<br>Skype:
									<br>Сайт:
									</p>
									</strong>
								</td>
								<td bgcolor="#F7797B" align="left" width="130" style="color:#ffffff;font-size:12px;font-family:'Arial',Helvetica,sans-serif;">
									<p>+7 (495) 666 29 17
									<br>+7 (812) 339 25 45
									<br>643286189
									<br><a href="mailto:info@klavazip.ru" style="a:link color:#ffffff;a:visited color:#ffffff;a:hover color:#ffffff;a text-decoration: underline; border-bottom: #ffffff; color: #ffffff;">Info@klavazip.ru</a>
									<br><a href="skype:klavazip?call" style="a:link color:#ffffff;a:visited color:#ffffff;a:hover color:#ffffff;a text-decoration: underline; border-bottom: #ffffff; color: #ffffff;">klavazip</a>
									<br><a href="http://www.klavazip.ru" target="_blank" title="Перейти на сайт интернет-магазина «КлаваЗип»" style="a:link color:#ffffff;a:visited color:#ffffff;a:hover color:#ffffff;a text-decoration: underline; border-bottom: #ffffff; color: #ffffff;">www.klavazip.ru</a>
									</p>
								</td>
							</tr>
						</table>
						<table width="700" height="10" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>
<img src="http://<?= $mainUrl?>/bitrix/tools/posting_read.php?mid=#MAIL_ID#&amp;subscribe_pid=#POSTING_ID#&amp;mhash=#MAIL_MD5#" width="1" height="1" >

<?
	return array(
		"SUBJECT"=>$SUBSCRIBE_TEMPLATE_RUBRIC["NAME"],
		"BODY_TYPE"=>"html",
		"CHARSET"=>"UTF-8",
		"DIRECT_SEND"=>"Y",
		"FROM_FIELD"=>$SUBSCRIBE_TEMPLATE_RUBRIC["FROM_FIELD"],
		"FILES" => array(CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/price.XLS"))
	);
?>