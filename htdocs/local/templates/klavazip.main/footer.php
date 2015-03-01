<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

		</div>
			<div class="clear"></div>
	
	</div>
			<div class="boxContentLinks">
			<div id="content2">
				<div class="oneBlockLinks">
					<ul>
						<li><a href="/catalog/akkumulyatory-po-noutbukam/">Аккумуляторы</a></li>
						<li class="last"><a href="/catalog/mikroskhemy/">Микросхемы</a></li>
					</ul>
				</div>	
				<div class="oneBlockLinks">
					<ul>
						<li><a href="/catalog/videokarty/">Видеокарты</a></li>
						<li class="last"><a href="/catalog/klaviatury/">Клавиатуры</a></li>
					</ul>
				</div>	
				<div class="oneBlockLinks">
					<ul>
						<li><a href="/catalog/matritsy/">Матрицы</a></li>
						<li class="last"><a href="/catalog/kabeli/">Кабели</a></li>
					</ul>
				</div>	
				<div class="oneBlockLinks">
					<ul>
						<li><a href="/catalog/perekhodniki/">Переходники</a></li>
						<li class="last"><a href="/catalog/korpusnye-detali/">Корпусные детали</a></li>
					</ul>
				</div>	
				<div class="oneBlockLinks">
					<ul>
						<li><a href="/catalog/razemy/">Разъемы</a></li>
						<li class="last"><a href="/catalog/sistemy-okhlazhdeniya-/">Системы охлаждения</a></li>
					</ul>
				</div>	
				<div class="clear"></div>			
			</div>	
		</div>
		
		<div class="boxFooter"> 
			<div id="footer">
				<div class="footerIcons">
					<a title="Группа в Facebook" href="http://www.facebook.com/groups/346072022080941/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/icon_footer_2.png" alt="" /></a>
					<a title="Группа в Контакте" href="http://vkontakte.ru/club20242050" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/icon_footer_1.png" alt="" /></a>
					<a title="Наш в Twitter" href="https://twitter.com/#!/KlavaZip" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/icon_footer_3.png" alt="" /></a>
				</div>
				
				<div class="footerYandex">
					<a target="_blank" href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2508/*http://market.yandex.ru/shop/94607/reviews"><img src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2505/*http://grade.market.yandex.ru/?id=94607&action=image&size=0" border="0" width="88" height="31" alt="Читайте отзывы покупателей и оценивайте качество магазина на Яндекс.Маркете" /></a>
				</div>
				
				<div class="blockFooterContact1">
					<p>Москва — +7 495 666-29-17</p>
					<p>Санкт-Петербург — +7 812 339-25-45</p>
					<p><?/*Регионы (звонок бесплатный) — 8 800 555-62-65*/?></p>
				</div>
				<div class="blockFooterContact2">
					<p>Эл.почта — info@klavazip.ru</p>
					<p>Скайп — klavazip</p>
					<p>ICQ — 619-614-196</p>
				</div>
				<div class="footerTimeWork">Время работы: будни, с 10 до 19</div>
				
				
				<div class="LiveInternet">
					<!--LiveInternet counter-->
					<script type="text/javascript"><!--
					document.write("<a href='http://www.liveinternet.ru/click' "+
					"target=_blank><img src='//counter.yadro.ru/hit?t26.6;r"+
					escape(document.referrer)+((typeof(screen)=="undefined")?"":
					";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
					screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
					";"+Math.random()+
					"' alt='' title='LiveInternet: показано число посетителей за"+
					" сегодня' "+
					"border='0' width='88' height='15'><\/a>")
					//-->
					</script>
					<!--/LiveInternet-->
				</div>
				
				
				<div class="clear"></div>
				<p class="copy">
					2010-<?=date('Y')?> © Klavazip <br />
					Все права защищены 
				</p>	
				<ul class="footerList">
					<li><a href="/about/pay-delivery/">Доставка и оплата</a></li>
					<li><a href="/about/garantia/">Гарантия</a></li>
					<li><a href="/about/opt/">Оптовикам</a></li>
					<li><a href="/about/">О компании</a></li>
					<li><a href="/forum/">Форум</a></li>
					<li><a href="/articles/">Статьи</a></li>
				</ul>
				
			</div>
		</div>
	<div class="boxLightFooter fixed">
		<div class="contLightFooter">
			<div class="itemLightFooter1">
				<? 
				$b_CountCompare = (count($_SESSION['CATALOG_COMPARE_LIST'][8]['ITEMS']) > 0);
				?>
			
				<div id="footerLinkCompare" class="footerLinkCompare <?=($b_CountCompare) ? 'active' : ''?>">
				
					<a href="/catalog/compare/">Сравнение</a> 
					<? 
					if($b_CountCompare)
					{
						?><span style="display: inline;"><?=count($_SESSION['CATALOG_COMPARE_LIST'][8]['ITEMS'])?></span><?
					}	
					else
					{
						?><span></span><?
					}
					?>
					
					<div class="promptFooter" style="bottom: -60px" id="js_promt_compare_product"><div class="contPrompt">Товар добавлен в сравнение <div class="promptBottom"></div></div></div>
				</div>
			</div>
			<div class="itemLightFooter1">
			
				<? 
				$s_ElementIdHash = $_COOKIE['KLAVA_FAVOTITES_ID'];
				if(strlen($s_ElementIdHash) > 0)
				{
					$ar_ElementID = explode('_', $s_ElementIdHash);
				}
				else
				{
					$ar_ElementID = array();
				}
				
				$b_CountFavorites = (count($ar_ElementID) > 0);
				?>
			
			
				<div id="footerLinkFavourite" class="footerLinkFavourite <?=($b_CountFavorites) ? 'active' : ''?>">
					
					<a href="/favorites/">Избранное</a> 
					
					<?
					if( $b_CountFavorites )
					{
						?><span style="display: inline;"><?=count($ar_ElementID)?></span><?
					}
					else
					{
						?><span>0</span><?	
					}
					?>
					
					<div class="promptFooter" style="bottom: -60px" id="js_promt_favorites_product"><div class="contPrompt">Товар добавлен в избранное <div class="promptBottom"></div></div></div>
				</div>
			</div>
			<div class="lineFooterLight"></div>
			<div class="lineFooterLight right"></div>
			<div class="itemLightFooter1 basket" id="js_footer_small_basket_cont">
				<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "footer_bascet", array(), false);?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>

<div id="notification_add_product" class="reveal-modal">
    <div class="myModal-inner">
		<div class="openWindow">
			<a class="close-reveal-modal"></a>
			<h2>Сообщить о поступлении</h2>
			<div class="boxOrderOneClick">
				<p>Мы сообщим Вам о поступлении товара в продажу по указанным контактам.</p>
				<div class="formTel">
					<form action="" name="Tel" method="post">
						<label>Телефон для СМС</label>
						<input type="text" id="js_notic_click_phone" class="js-phone-mask" name="phone" value=""/>
						<div class="clear"></div>		
						<label>E-mail</label>
						<input type="text" id="js_notic_click_email" name="email" value="<?=$USER->GetEmail()?>"/>
						<div class="clear"></div>		
						<input type="button" class="buttonBuy" onclick="klava.catalog.sendNoticAddProduct()" value="Отправить"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="buyClick" class="reveal-modal">
    <div class="myModal-inner">
		<div class="openWindow">
			<a class="close-reveal-modal"></a>
			<h2>Заказ в 1 клик</h2>
			<div class="boxOrderOneClick">
				<p>Оставьте свой телефонный номер, чтобы наш менеджер смог перезвонить вам и оформить заказ. Мы перезвоним вам в ближайшее рабочее время (будни, с 10 до 19 часов).</p>
				<div class="formTel">
					<form action="" name="Tel" method="post">
						<label>Контактный телефон</label>
						<input type="text" id="js_by_click_phone" class="js-phone-mask" name="search" value=""/>
						<div class="clear"></div>		
						<input type="button" onclick="klava.productSpedOrder()" class="buttonBuy" value="Заказать"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="buyClickBasket" class="reveal-modal">
    <div class="myModal-inner">
		<div class="openWindow">
			<a class="close-reveal-modal"></a>
			<h2>Заказ в 1 клик</h2>
			<div class="boxOrderOneClick">
				<p>Оставьте свой телефонный номер, чтобы наш менеджер смог перезвонить вам и оформить заказ. Мы перезвоним вам в ближайшее рабочее время (будни, с 10 до 19 часов).</p>
				<div class="formTel">
					<form action="" name="Tel" method="post">
						<label>Контактный телефон</label>
						<input type="text" id="js_by_click_phone_bakset" class="js-phone-mask" name="search" value=""/>
						<div class="clear"></div>		
						<input type="button" onclick="klava.productSpedOrderBasket()" class="buttonBuy" value="Заказать"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="сallback-send" class="reveal-modal">
    <div class="myModal-inner">
		<div class="openWindow">
			<a class="close-reveal-modal"></a>
			<h2>Заказ в 1 клик</h2>
			<div class="boxOrderOneClick">
				<p>Оставьте свой телефонный номер, чтобы наш менеджер смог перезвонить вам и оформить заказ. Мы перезвоним вам в ближайшее рабочее время (будни, с 10 до 19 часов).</p>
				<div class="formTel">
					<form action="" name="Tel" method="post">
						<label>Контактный телефон</label>
						<input type="text" id="js_сallback_phone" class="js-phone-mask" name="search" value=""/>
						<div class="clear"></div>		
						<input type="button" onclick="klava.сallbackSend()" class="buttonBuy" value="Заказать"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="left-menu-overlay" id="left-menu-overlay"></div>

<script type="text/javascript">
(function(){var widget_id="23510";var s=document.createElement("script");s.type="text/javascript";s.async=true;s.src="//code.jivosite.com/script/widget/"+widget_id;var ss=document.getElementsByTagName("script")[0];ss.parentNode.insertBefore(s,ss)})();
(function(d,w,c){(w[c]=w[c]||[]).push(function(){try{w.yaCounter20002201=new Ya.Metrika({id:20002201,webvisor:true,clickmap:true,trackLinks:true,accurateTrackBounce:true,params:yaParams})}catch(e){}});var n=d.getElementsByTagName("script")[0],s=d.createElement("script"),f=function(){n.parentNode.insertBefore(s,n)};s.type="text/javascript";s.async=true;s.src=(d.location.protocol=="https:"?"https:":"http:")+"//mc.yandex.ru/metrika/watch.js";if(w.opera=="[object Opera]")d.addEventListener("DOMContentLoaded", f,false);else f()})(document,window,"yandex_metrika_callbacks");
var _oms=window._oms||[];_oms.push(["set_project_id","ugowqivydtvagbyiyvmzxbyvlsfkfgcpofgctrsr"]);_oms.push(["set_domain",".klavazip.ru"]);(function(){var oms=document.createElement("script");oms.type="text/javascript";oms.async=true;oms.src="//ohmystats.com/oms.js";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(oms,s)})();
</script>

<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter20002201 = new Ya.Metrika({id:20002201, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/20002201" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

</body>
</html>		