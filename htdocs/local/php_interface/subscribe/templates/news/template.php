<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
global $SUBSCRIBE_TEMPLATE_RUBRIC;
$SUBSCRIBE_TEMPLATE_RUBRIC = $arRubric;
$SUBSCRIBE_TEMPLATE_RESULT = false;
?>
<?
$BID = 1;//ID инфоблока данных
$mainUrl = 'http://klavazip.ru';//url сайта, подставляемый для ссылок/картинок
$arAD = array();//идент-ры РК, если требуется; можно раскомментировать примеры ниже
#$arAD = array('catalog', 'subscribe');//фиксированные и неизменные параметры РК
#$arAD = array('catalog', date('dmY'));//параметры РК, зависящие от даты выпуска
?>
<center>
<table width="100%" height="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
		<td bgcolor="#e4e4e4" width="100%">
			<table width="700" cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td width="700">
						<table width="700" height="10" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td></td>
							</tr>
						</table>
						<table width="700" height="70" cellpadding="8" cellspacing="0" border="0">
							<tr>
								<td bgcolor="#F7797B" width="3"></td>
								<td width="697" bgcolor="#ffffff" align="right" valign="middle" style="color:#F7797B;font-size:16px;font-family:'Arial',Helvetica,sans-serif;text-transform:uppercase;">
								Интернет-магазин KlavaZip<br>
								</td>
							</tr>
						</table>
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
									<table cellpadding="1" cellspacing="1" width="100%">
<?
if (CModule::IncludeModule('iblock')) {
	if (!empty($arAD)) {
		foreach ($arAD as $i => &$rk)
			$rk = 'r'.($i+1).'='.$rk;
	}
	$rsElement = CIBlockElement::GetList(
										array('ID' => 'DESC'),
										array(
											'IBLOCK_ID' => $BID, 'ACTIVE' => 'Y',
											'>DATE_CREATE' => $arRubric['START_TIME'],
											'<=DATE_CREATE' => $arRubric['END_TIME'],
											), false, false,
										array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'PREVIEW_TEXT'));
	while ($arElement = $rsElement->GetNext(true, false)) {

		$SUBSCRIBE_TEMPLATE_RESULT = true;
		$URL = $arElement['DETAIL_PAGE_URL'];

		if (!empty($arAD)) {
			if (strpos($URL, '?') !== false)
				$URL = $URL.'&'.implode('&', $arAD);
			else
				$URL = $URL.'?'.implode('&', $arAD);
		}

		//$arFile = CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
		?>
		<tr valign="top">
				<?/*if ($arFile !== false):?>
					<a href="<?= $mainUrl?><?= $URL?>"><img src="<?= $mainUrl?><?= str_replace(array(' ', '+'), '%20', $arFile['SRC'])?>" width="<?= $arFile['WIDTH']?>" height="<?= $arFile['HEIGHT']?>" alt="<?= $arElement['NAME']?>" border="0" /></a>
				<?else:?>
					<a href="<?= $mainUrl?><?= $URL?>"><img src="<?= $mainUrl?>/upload/no-photo.png" width="160" height="93" alt="<?= $arElement['NAME']?>" border="0" /></a>
				<?endif;*/?>
			<td>
				<font face="Arial, Helvetica, sans-serif" size="2">
					<a href="<?= $mainUrl?><?= $URL?>"><?= $arElement['NAME']?></a><br/>
					<?= strip_tags($arElement['PREVIEW_TEXT'])?>
				</font>
			</td>
		</tr>
		<tr><td colspan="2"></td></tr>
		<?
	}
}
?>
									</table>
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

<?if($SUBSCRIBE_TEMPLATE_RESULT)
	return array(
		"SUBJECT"=>$SUBSCRIBE_TEMPLATE_RUBRIC["NAME"],
		"BODY_TYPE"=>"html",
		"CHARSET"=>"UTF-8",
		"DIRECT_SEND"=>"Y",
		"FROM_FIELD"=>$SUBSCRIBE_TEMPLATE_RUBRIC["FROM_FIELD"],
	);
else
	return false;
?>