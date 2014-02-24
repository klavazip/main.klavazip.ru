<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
global $SUBSCRIBE_TEMPLATE_RUBRIC;
$SUBSCRIBE_TEMPLATE_RUBRIC = $arRubric;
$SUBSCRIBE_TEMPLATE_RESULT = false;
?>
<?
$mainUrl = 'http://klavazip.ru';//url сайта, подставляемый для ссылок/картинок
$arAD = array();//идент-ры РК, если требуется; можно раскомментировать примеры ниже
?>

<center>
<table style="padding:5px 20px; width:100%; height;100%; border-spacing:0;background: #fff repeat-x left top;" >
	<tr>
		<td>
			<table style="width:700px; background:#ffffff; margin:10px 10px 20px; border-radius:5px;" >
				<tr>
					<td width="700">
					   <table width="100%" cellpadding="8" cellspacing="0" border="0">
							<tr>
								<td >
								</td>
								<td width="697" bgcolor="#ffffff" align="justify" valign="middle" style="">
									<p><strong><center>Уважаемый, #USER_NAME#!</center></strong></p>
									<br />
                                    <p style="">
                                    30 января 2013 года на сайте www.klavazip.ru произошел технический сбой, в результате которого 
                                    на ваш email адрес было отправлено много писем с новостями и рассылкой прайс-листа.
                                    </p>
                                    <br />
                                    <p>Приносим свои извинения за предоставленные неудобства!</p>
                                    <br />
                                    <p>Дабы "загладить" нашу вину, мы приняли решение сделать Вам скидку на заказы 31 января и 01 февраля в размере:</p>
                                    <ul>
                                        <li>10% если заказ до 10 тыс. руб.</li>
                                        <li>15% если заказ свыше 10 тыс. руб.</li>
                                        <li>22% если заказ свыше 50 тыс. руб.</li>
                                    </ul>
                                    <p>
                                    Чтобы получить скидку необходимо при формировании заказа сообщить менеджеру кодовое слово: «СБОЙ СИСТЕМЫ».
                                    В случае если, Вы будете делать заказ через сайт, кодовое слово необходимо написать в комментариях к заказу.
                                    </p>
                                </td>
							</tr>
						</table>
						
                     #MSG_EPILOG#
                             
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>
<img src="http://klavazip.ru/bitrix/tools/posting_read.php?mid=#MAIL_ID#&amp;subscribe_pid=#POSTING_ID#&amp;mhash=#MAIL_MD5#" width="1" height="1" />

<?
	return array(
		"SUBJECT"=> $SUBSCRIBE_TEMPLATE_RUBRIC["NAME"],
		"BODY_TYPE"=>"html",
		"CHARSET"=>"UTF-8",
		"DIRECT_SEND"=>"Y",
		"FROM_FIELD"=>$SUBSCRIBE_TEMPLATE_RUBRIC["FROM_FIELD"],
		//"FILES" => array(CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/price.XLS"))
	);
?>