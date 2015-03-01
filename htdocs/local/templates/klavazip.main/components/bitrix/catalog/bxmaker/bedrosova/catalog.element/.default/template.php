<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
	?>
	<div id="addOrder" class="reveal-modal forLogin">
	    <div class="myModal-inner">
			<div class="openWindow login">
				<a class="close-reveal-modal"></a>
				<h2>Добавление отзыва</h2>
				<div class="boxAddReview" style="position:relative; ">
					<div class="response-ajax-load" id="add-response-product">загрузка</div>
					<form action="" name="review" method="post">
						<label>Ваш отзыв</label>
						<div class="blockInputform"><textarea name="textarea" id="comment-user-text" cols="1" rows="1"></textarea></div>
						<div class="clear"></div>	
						<label>Ваше имя</label>
						<div class="blockInputform"><input  type="text"  name="q" id="comment-user-name" value="<?=$USER->GetFullName()?>"/></div>
						<div class="clear"></div>
						<label>Электронная почта</label>
						<div class="blockInputform">
							<input  type="text"  name="q" id="comment-user-email" value="<?=$USER->GetEmail()?>"/>
							<p class="comment">Не будет опубликована на сайте</p>						
						</div>
						<div class="clear"></div>	

						<? 
						if($USER->IsAuthorized())
						{
							?>
							<input type="hidden" id="comment-user-reating" value="" />
							<label>Ваша оценка <br/> товара</label>
							<div class="blockInputform">
								<div class="reviewRating">
									<div id="rating_2">
										<input type="hidden" name="votes" value="2"/>
										<input type="hidden" name="vote-id" value="2"/>
										<input type="hidden" name="cat_id" value="2"/>
									</div>
									<div class="clear"></div>
								</div>
							</div>
							<div class="clear"></div>
							<?							
						}
						?>
						<input type="button" onclick="klava.addComment()" class="buttonBuy" value="Отправить"/>	
					</form>
				</div>
			</div>
		</div>
	</div>

	<? 
	$ar_FotoSlider = array();
	$arResult["MORE_PHOTO"][] = $arResult['DETAIL_PICTURE']['ID'];
	
	$ar_FileID = array();
	
	if(intval($arResult['DETAIL_PICTURE']['ID']) > 0)
		$ar_FileID[] = intval($arResult['DETAIL_PICTURE']['ID']);
	
	foreach ($arResult["MORE_PHOTO"] as $ar_FileParams)
	{
		if(intval($ar_FileParams['ID']) > 0)
			$ar_FileID[] = $ar_FileParams['ID'];
	}
	
	foreach ($ar_FileID as $i_FileID)
	{
		$ar_File = CFile::ResizeImageGet($i_FileID, array('width' => 78, 'height' => 44), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$ar_FileBig = CFile::ResizeImageGet($i_FileID, array('width' => 450, 'height' => 224), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$ar_FileBigSlider = CFile::ResizeImageGet($i_FileID, array('width' => 630, 'height' => 420), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$ar_FilePrevSlider = CFile::ResizeImageGet($i_FileID, array('width' => 100, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true);
		
		if( strlen($ar_File['src']) > 0 )
		{
			$ar_FotoSlider[] = array(
				'm'  => $ar_File['src'], 
				'b'  => $ar_FileBig['src'], 
				'o'  => CFile::GetPath($i_FileID),
				'bs' =>	$ar_FileBigSlider['src'],
				'ms' => $ar_FilePrevSlider['src']		
			);
		}
	}
	?>
	
	<div id="images-slider-detail" class="images-slider-detail">
		<a href="#" onclick="klava.fotoSlider.close(); return false;" class="close"></a>
		<div class="big-images">
			<? 
			$i_Count = count($ar_FotoSlider);
			foreach ($ar_FotoSlider as $i => $ar_Value)
			{
				$j = ($i_Count - 1 == $i) ? 0 : $i + 1;
				?><img onclick="klava.fotoSlider.selectFoto(<?=$j?>); return false;" class="js-foto-<?=$i?>" <?=($i == 0) ? 'style="display: inline;"' : 'style="display: none;"'?>  src="<?=$ar_Value['bs']?>" alt="" /><?
			}
			?>
		</div>
		<div class="nav">
			<? 
			foreach ($ar_FotoSlider as $i => $ar_Value)
			{
				?><a href="#" class="js-nav-foto-<?=$i?> <?=($i == 0) ? 'select' : ''?> "  onclick="klava.fotoSlider.selectFoto(<?=$i?>); return false;"><img src="<?=$ar_Value['ms']?>" alt="" /></a><?
			}
			?>
		</div>
	</div>
	
	
	<div class="boxMain">
		<input type="hidden" id="js-catalog_product_element_id" value="<?=$arResult['ID']?>" />	
		<input type="hidden" id="js-catalog-element-current-count" value="<?=$arResult['CATALOG_QUANTITY']?>" />			
		<h1><?=$arResult['DETAIL_PAGE_TITLE']?></h1><br /><br />				
		<div class="boxCardProduct">
			<p class="numberId">Артикул <?=$arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']?></p>
			<div class="blockImagesCard">
				<div class="imgCardLeft">

					<? 
					if(count($ar_FotoSlider) > 0)
					{
						?>
						<div class="imgPreview">
							<div class=" jcarousel-skin-tango">
								<div class="jcarousel-container jcarousel-container-vertical" style="position: relative; display: block;">
									<div class="jcarousel-clip jcarousel-clip-vertical" style="position: relative;">
										<ul class="jcarousel-list jcarousel-list-vertical" id="carusel_preview" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; height: 370px;">
											<? 
											foreach ($ar_FotoSlider as $k => $ar_ImagesValue)
											{
												if( strlen($ar_ImagesValue['m']) > 0 && strlen($ar_ImagesValue['b']) > 0)
												{
													?>
													<li class="<?=($k == 0) ? 'active' : ''?>  jcarousel-item jcarousel-item-vertical jcarousel-item-<?=$k?> jcarousel-item-<?=$k?>-vertical" style="float: left; list-style: none outside none;" jcarouselindex="1">
														<a data-num="<?=$k?>" data-big="<?=$ar_ImagesValue['b']?>" data-origin="<?=$ar_ImagesValue['o']?>" href="#">
															<img alt="" src="<?=$ar_ImagesValue['m']?>">
														</a>
													</li>
													<?
												}
											}
											?>
										</ul>
									</div>
									<div class="jcarousel-prev jcarousel-prev-vertical jcarousel-prev-disabled jcarousel-prev-disabled-vertical" style="display: block;" disabled="disabled"></div>
									<div class="jcarousel-next jcarousel-next-vertical" style="display: block;"></div>
								</div>
							</div>
						</div>
						<?
					}
					?>
					
					<div class="productMark">
						<div class="boxYourMark">
							<p>Ваша оценка товара:</p>
							<div class="cardRaiting">
								<div id="rating_1">
									<input type="hidden" name="votes" value="2"/>
									<input type="hidden" name="vote-id" value="2"/>
									<input type="hidden" name="cat_id" value="2"/>
								</div>
								<div class="clear"></div>	
							</div>
						</div>
						<div class="boxStar" id="js-element-boxStar">
							<? 
							for( $i = 1; $i <= 5; $i++ )
							{
								if( $i <= $arResult['PROPERTIES']['REATING_STAR']['VALUE'] )
								{
									?><a href="#" class="active"></a><?
								}
								else
								{
									?><a href="#"></a><?
								}
							}
							?>
							<div class="clear"></div>	
						</div>
						<a href="#" class="linkMark"><span>Оценить</span></a>
						<div class="clear"></div>	
					</div>
				</div>
				
				<div class="imgCardRight">
					
					<? 
					$b_Foto = (strlen($ar_FotoSlider[0]['b']) > 0);
					?>
				
					<div class="<?=($b_Foto) ? 'imgBig' : 'no-imgBig'?> " data-num="0">
						<? 
						if($b_Foto)
						{
							?>
							<a href="<?=$ar_FotoSlider[0]['o']?>" class="cloud-zoom" id="zoom1" rel="tint: '#000000', position: 'element1' ">
								<img alt="" src="<?=(strlen($ar_FotoSlider[0]['b']) > 0) ? $ar_FotoSlider[0]['b'] : SITE_TEMPLATE_PATH.'/img/no-pic-big1.jpg'?>">
							</a>
							<div id="element1" style="height: 347px; width: 452px; left: 401px; position: absolute; top: 0; " ></div>
							<?							
						} 
						else
						{
							?>:( Фото временно нет<?
						}			
						?>
					</div>
					<div class="cardSocial">
						<!-- <p>Поделиться:</p> --> 
						<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
						<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir"></div> 
                        <div class="clear"></div>	
					</div>
				</div>
				<div class="clear"></div>	
			</div>
			<div class="blockCardRight">
			
				<? 
				if( intval($arResult['CATALOG_QUANTITY']) > 0 )
				{
					?>
					<div class="boxMainInfCard">
					  <div class="infCont">
							<? /*?><p class="oldPrice">2 290 <span class="curency">⃏</span></p><?*/?>
							<p class="price"><?=intval($arResult['CATALOG_PRICE_4'])?> <span class="curency"><?=KlavaMain::RUB?></span></p>
							<a class="optPrice" href="/about/opt/"><span>Оптом дешевле</span></a>
							<div class="inputNumber"><input id="js_catalog_element_count_by" data-price="<?=intval($arResult['CATALOG_PRICE_4'])?>" type="text" onblur="if(this.value==''){this.value='1'}" onfocus="if(this.value=='1'){this.value=''}" value="1" name="q"><p>шт</p></div>
							
							<p class="finishPrice">
								<span id="js_catalog_element_simm_price"><?=intval($arResult['CATALOG_PRICE_4'])?></span>
								<span class="curency"><?=KlavaMain::RUB?></span>
							</p>
							
							<div class="borderInf"></div>
							<div class="iconPresent">Есть в наличии</div>
							<input type="button" onclick="klava.addBasketCatalogDetail()" value="Купить" class="buttonBuy">
						    <a rel="buyClick" class="buttonOneClick" href="#">Купить в 1 клик</a>
						</div>
						<div class="blockMoreDescriptions">
						
							<? 
							/*
							if( strlen($arResult['PROPERTIES']['color']['VALUE']) > 0 )
							{
								?>
								<div class="chooseColor">
									<p>Цвет:</p>
									<ul>
										<? 
										switch ($arResult['PROPERTIES']['color']['VALUE'])
										{
											case 'Синий':   ?><li class="active"><a href="#"><img alt="" src="<?=SITE_TEMPLATE_PATH?>/img/color_1.png"></a></li><? break;
											case 'Желтый':	?><li class="active"><a href="#"><img alt="" src="<?=SITE_TEMPLATE_PATH?>/img/color_2.png"></a></li><? break;
											case 'Красный':	?><li class="active"><a href="#"><img alt="" src="<?=SITE_TEMPLATE_PATH?>/img/color_3.png"></a></li><? break;
											case 'Зеленый':	?><li class="active"><a href="#"><img alt="" src="<?=SITE_TEMPLATE_PATH?>/img/color_4.png"></a></li><? break;
										}
										?>
									</ul>
									<div class="clear"></div>	
								</div> 
								<?
							}

							
							if( strlen($arResult['PROPERTIES']['emkost']['VALUE']) > 0)
							{
								?>
								<div class="chooseBox">
									<p>Емкость:</p>
									<ul>
										<li class="active"><a href="#"><?=str_replace('мА·ч', '', $arResult['PROPERTIES']['emkost']['VALUE']) ?> фарад</a></li>
									
										<? /*?>
										<li class="active"><a href="#">120 фарад</a></li>
										<li><a href="#">130 фарад</a></li>
										<li><a href="#">140 фарад</a></li>									
										<? / ?>
									</ul>
									<div class="clear"></div>	
								</div> 
								<? 
							}
							*/
							?>
							
							
							
							
							<div class="itemsDescription">
								<div class="blockLeft">
									<div class="oneLineDescription">
										<a target="_blank" href="/about/pay-delivery/"><span>Оплата:</span></a>
										<img alt="" title="Оплата наличными" src="/local/templates/klavazip.main/img/pay_1.png">
										<img alt="" title="Оплата картой Visa" src="/local/templates/klavazip.main/img/pay_2.png">
										<img alt="" title="Оплата картой MasterCkard" src="/local/templates/klavazip.main/img/pay_3.png">
										<img alt="" title="Оплата через Сбербанк" src="/local/templates/klavazip.main/img/pay_4.png"> 
										<img alt="" title="Оплата через Яндекс деньги" src="/local/templates/klavazip.main/img/pay_5.png">
										<img alt="" title="Оплата через WebMoney" src="/local/templates/klavazip.main/img/pay_6.png">
										<img alt="" title="Оплата через Qiwi" src="/local/templates/klavazip.main/img/pay_7.png">
									</div>
									<div class="oneLineDescription">
										<a target="_blank" href="/about/pay-delivery/"><span>Доставка:</span></a>
										<img alt="" title="Доствка ТК Байкал сервис" src="/local/templates/klavazip.main/img/delivery_1.png">
										<img alt="" title="Доставка почтой России" src="/local/templates/klavazip.main/img/delivery_2.png">									
										<img alt="" title="Доставка ТК DPD" src="/local/templates/klavazip.main/img/delivery/logo-dpd.png">									
										<img alt="" title="Доставка ТК СДЭК" src="/local/templates/klavazip.main/img/delivery/logo-sdec.jpg">									
										<img alt="" title="Доставка курьером до двери" src="/local/templates/klavazip.main/img/delivery/logo-courier.png">										
									</div>
										
									<div class="oneLineDescription"><p><a href="/about/garantia/" target="_blank"><span>Гарантия:</span></a>&nbsp; 1 год</p></div>

									<? 
									if( strpos($APPLICATION->GetCurDir(), 'akkumulyatory-po-modelyam') !== false )
									{
										?><div class="oneLineDescription"><p><a href="/articles/o-batareyakh/" target="_blank"><span>Подробнее о батареях</span></a></p></div><?
									}
									?>
									
									<div class="oneLineDescription">
										<a href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2508/*http://market.yandex.ru/shop/94607/reviews" target="_blank">
											<img width="88" height="31" border="0" alt="Читайте отзывы покупателей и оценивайте качество магазина на Яндекс.Маркете" src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2505/*http://grade.market.yandex.ru/?id=94607&amp;action=image&amp;size=0">
										</a>
									</div>
					
									
								</div>
								<div class="blockRight">
									<div class="oneLineDescription">
										<a class="addCompare" href="#" onclick="klava.addCompare('<?=$arResult['ID']?>'); return false;"><span>К сравнению</span></a>
										<div class="clear"></div>
									</div>
									<div class="oneLineDescription">
										<a class="addFavourite" href="#" onclick="klava.addFavorites('<?=$arResult['ID']?>'); return false;"><span>В избранное</span></a>
										<div class="clear"></div>
									</div>
									<div class="oneLineDescription">
										<a class="iconPrint" target="_blank" href="/tovar-print/?id=<?=$arResult['ID']?>">Распечатать</a>
										<div class="clear"></div>
									</div>
								</div>
								<div class="clear"></div>	
							</div>
						</div>
					</div>
					<? 
				}
				else
				{
					?>
					<div class="boxMainInfCard2">
					    <div class="infCont">							
							<p class="price"><?=intval($arResult['CATALOG_PRICE_4'])?> <span class="curency"><?=KlavaMain::RUB?></span></p>
							<a class="optPrice" href="/about/opt/"><span>Оптом дешевле</span></a>
							<? 
							if(count($arResult['ANALOGS']) > 0)
							{
								?>															
								<div class="iconPresent" style="top: 87px">Есть аналоги:</div>  
								<input type="button" onclick="klava.addBasketCatalogDetail()" value="Купить" class="buttonBuy">
								<a rel="buyClick" class="buttonOneClick" href="#">Купить в 1 клик</a>

								<div class="analogBottom">
									<div class="analogRepeat">	
										<div class="boxAnalog">
											<div class="blockContAnalog">
												<? 
												foreach ($arResult['ANALOGS'] as $ar_AnalogValue)
												{
													?>
													<div class="oneMoreProduct">
														<a href="<?=$ar_AnalogValue['DETAIL_PAGE_URL']?>"><img alt="" src="<?=$ar_AnalogValue['IMG']?>"></a>
														<p><a href="<?=$ar_AnalogValue['DETAIL_PAGE_URL']?>"><?=$ar_AnalogValue['NAME']?></a> <?=intval($ar_AnalogValue['PRICES']['PRICE'])?> <span class="curency"><?=KlavaMain::RUB?></span></p>
														<div class="clear"></div>
													</div>
													<? 			

													break;
												}
												?>
											</div>
											<? 
											if(count($arResult['ANALOGS']) > 1)
											{
												?>
												<div class="boxShowAllAnalog">
													<a class="showAllAnalogs" href="#" style="display: inline;">Показать еще <?=(count($arResult['ANALOGS']) - 1)?></a>
													<a class="closeAllAnalogs" href="#" style="display: none;">Свернуть</a>
												</div>
												<?
											}
											?>
										</div>
									</div>		
								</div>	
								<? 
							}
							else
							{
								if( strlen($arResult['PROPERTIES']['DATA_TRANZITA']['VALUE']) > 0 )
								{
									$s_DateTranzit = $arResult['PROPERTIES']['DATA_TRANZITA']['VALUE'];
									if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $s_DateTranzit, $regs)) 
									{
										$s_DateTranzitRes = $regs[3].'.'.$regs[2].'.'.$regs[1];
									} 
									else 
									{
										$s_DateTranzitRes = $s_DateTranzit;
									}
									
									$s_CurrentDay = date("d.m.Y");
									$compare_date = $DB->CompareDates($s_DateTranzitRes, $s_CurrentDay);
									if ($compare_date < 0) 
										$b_DateTranzitValied = false;
									else 
										$b_DateTranzitValied = true;
								}

								if($b_DateTranzitValied)
								{
									?>
									<div class="iconNoPresent">Ожидается поступление: <?=$s_DateTranzitRes?></div>
									<input type="button" class="buttonBuy" onclick="klava.catalog.showWindowNoticAddProduct('<?=$arResult['ID']?>')" value="Резерв">
									<? 
								}	
								else
								{
									?>
									<div class="iconNoPresent">Нажмите на "Уведомить" и когда мы получим эту деталь, сообщим вам. Цена может отличаться от текущей цены. При транзите детали прийдет уведомление с датой поступления товара и его точной ценой на момент поступления. Сроки поступления  детали в данный момент не известны.</div>
									<input type="button" class="buttonBuy" onclick="klava.catalog.showWindowNoticAddProduct('<?=$arResult['ID']?>')" value="Уведомить">
									<? 
								}	
							}
							?>
						</div>
					</div>
					
					<div class="blockMoreDescriptions" style="padding-top: 200px">
						<div class="itemsDescription">
							<div class="blockLeft">
								<div class="oneLineDescription">
									<a target="_blank" href="/about/pay-delivery/"><span>Оплата:</span></a>
									<img alt="" title="Оплата наличными" src="/local/templates/klavazip.main/img/pay_1.png">
									<img alt="" title="Оплата картой Visa" src="/local/templates/klavazip.main/img/pay_2.png">
									<img alt="" title="Оплата картой MasterCkard" src="/local/templates/klavazip.main/img/pay_3.png">
									<img alt="" title="Оплата через Сбербанк" src="/local/templates/klavazip.main/img/pay_4.png"> 
									<img alt="" title="Оплата через Яндекс деньги" src="/local/templates/klavazip.main/img/pay_5.png">
									<img alt="" title="Оплата через WebMoney" src="/local/templates/klavazip.main/img/pay_6.png">
									<img alt="" title="Оплата через Qiwi" src="/local/templates/klavazip.main/img/pay_7.png">										
								</div>
								<div class="oneLineDescription">
									<a target="_blank" href="/about/pay-delivery/"><span>Доставка:</span></a>
									<img alt="" title="Доствка ТК Байкал сервис" src="/local/templates/klavazip.main/img/delivery_1.png">
									<img alt="" title="Доставка почтой России" src="/local/templates/klavazip.main/img/delivery_2.png">									
									<img alt="" title="Доставка ТК DPD" src="/local/templates/klavazip.main/img/delivery/logo-dpd.png">									
									<img alt="" title="Доставка ТК СДЭК" src="/local/templates/klavazip.main/img/delivery/logo-sdec.jpg">									
									<img alt="" title="Доставка курьером до двери" src="/local/templates/klavazip.main/img/delivery/logo-courier.png">									
								</div>
								<div class="oneLineDescription"><p><a target="_blank" href="/about/garantia/"><span>Гарантия:</span></a>&nbsp; 1 год</p></div>
								<? 
								if( strpos($APPLICATION->GetCurDir(), 'akkumulyatory-po-modelyam') !== false )
								{
									?><div class="oneLineDescription"><p><a href="/articles/o-batareyakh/" target="_blank"><span>Подробнее о батареях</span></a></p></div><?
								}
								?>
								<div class="oneLineDescription">
									<a href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2508/*http://market.yandex.ru/shop/94607/reviews" target="_blank">
										<img width="88" height="31" border="0" alt="Читайте отзывы покупателей и оценивайте качество магазина на Яндекс.Маркете" src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2505/*http://grade.market.yandex.ru/?id=94607&amp;action=image&amp;size=0">
									</a>
								</div>
							</div>
							<div class="blockRight">
								<div class="oneLineDescription">
									<a class="addCompare" href="#" onclick="klava.addCompare('<?=$arResult['ID']?>'); return false;"><span>К сравнению</span></a>
									<div class="clear"></div>
								</div>
								<div class="oneLineDescription">
									<a class="addFavourite" href="#" onclick="klava.addFavorites('<?=$arResult['ID']?>'); return false;"><span>В избранное</span></a>
									<div class="clear"></div>
								</div>
								<div class="oneLineDescription">
									<a class="iconPrint" target="_blank" href="/tovar-print/?id=<?=$arResult['ID']?>">Распечатать</a>
									<div class="clear"></div>
								</div>
							</div>
							<div class="clear"></div>	
						</div>
					</div>
					<?
				}
				?>
			</div>
			<div class="clear"></div>
			<div class="boxCardInf">
				<div class="leftInf">
					<div id="ex-one">
						<div class="cardTab">
							<ul class="nav">
								<li><a class="current" href="#description1"><span>Характеристики</span></a></li>
								<li><a href="#description2"><span>Отзывы</span> <span><?=$arResult['COMMENT_COUN']?></span> </a></li>
							</ul>
							<div class="clear"></div>
						</div>
						<div class="contCardTab">
							<div class="list-wrap">	
								<div id="description1">
									<table cellspacing="0" cellpadding="0">
										<? 
										$ar_PropertyCode = array(
											"diagonal",
											"data_code",
											"emkost",
											"kollichesto_jacheek",
											"naprjazhenie",
											"resolution",
											"light",
											"connector",
											"surface",
											"location_connector",
											"manufactur",
											"type_bga",
											"state_bga",
											"color",
											"keyboard",
											"volume_video",
											"type_video",
											"frequency",
											"with_memory",
											"tip_chekhla",
											"dlya_chego",
											"material",
											"OBEM_OPERATIVNOY_PAMYATI",
											"OBEM_VSTROENNOY_PAMYATI",
											"PROTSESSOR",
											"FRONTALNAYA_KAMERA",
											"FOTOKAMERA",
											"TIP_SIM_KARTY",
											"STANDART_SVYAZI",
											"MODEL_VIDEOADAPTERA",
											"TIP_ZU",
											"SILA_TOKA",
											"MODEL_TELEFONA_ILI_PLANSHETA",
											"OPERATSIONNAYA_SISTEMA",
											"KOLICHESTVO_YADER",
											"PODDERZHKA_KARTY_PAMYATI",
											"RAZEM",
											"VID_NAUSHNIKOV",
											"PRODOLZHITELNOST_RABOTY",
											"DLINA_PROVODA",
											"RAZYEM_NAUSHNIKOV",
											"PITANIE",
											"TIP_NAUSHNIKOV",
											"KOLICHESTVO_IZLUCHATELEY",
											"SVETOVOY_POTOK_LYUMEN",
											"DIAPAZON_VOSPROIZVODIMYKH_CHASTOT",
											"IMPEDANS",
											"FORMA_RAZEMA_NAUSHNIKOV",
											"TIP_KREPLENIYA",
											"POZOLOCHENNYE_RAZEMY",
											"OSOBENNOSTI",
											"PODKLYUCHENIE",
											"CHUVSTVITELNOST",
											"ZVUK",
											"MOSHCHNOST_KOLONOK",
											"OTNOSHENIE_SIGNAL_SHUM",
											"VKHODY",
											"FM_TYUNER",
											"TIP_MAGNITOLY",
											"KOLICHESTVO_RADIOSTANTSIY",
											"LINEYNYY_VKHOD",
											"VYKHOD_NA_NAUSHNIKI",
											"INTERFEYS_USB",
											"CHASY",
											"TIP_NAVIGATORA",
											"OBLAST_PRIMENENIYA",
											"PODDERZHKA_GLONASS",
											"PODDERZHKA_WAAS",
											"TIP_ANTENNY",
											"KONSTRUKTSIYA_VIDEOREGISTRATORA",
											"KOLICHESTVO_KANALOV_ZAPISI_VIDEO_ZVUKA",
											"PODDERZHKA_HD",
											"ZAPIS_VIDEO",
											"REZHIM_ZAPISI",
											"FUNKTSII",
											"UGOL_OBZORA",
											"NOCHNOY_REZHIM",
											"REZHIM_FOTOSEMKI",
											"DLITELNOST_ROLIKA",
											"REZHIMY_ZAPISI_VIDEO",
											"VIDEOKODEK",
											"VYKHODY",
											"PODKLYUCHENIE_K_KOMPYUTERU_PO_USB",
											"DLINA",
											"MATERIAL_OPLETKI",
											"DIAPAZON_K",
											"DIAPAZON_KA",
											"DIAPAZON_KU",
											"DIAPAZON_X",
											"DETEKTOR_LAZERNOGO_IZLUCHENIYA",
											"PODDERZHKA_REZHIMOV",
											"PRIEMNIK_SIGNALA_RADIOKANAL",
											"REZHIM_GOROD",
											"REZHIM_TRASSA",
											"OBNARUZHENIE_RADARA_TIPA_STRELKA",
											"ZASHCHITA_OT_OBNARUZHENIYA",
											"PAMYAT_NASTROEK",
											"OTOBRAZHENIE_INFORMATSII",
											"REGULIROVKA_YARKOSTI",
											"REGULIROVKA_GROMKOSTI",
											"OTKLYUCHENIE_ZVUKA",
											"TIP_DISPLEYA",
											"PODSVETKA",
											"PODDERZHIVAEMYE_FORMATY_TEKSTOVYE",
											"PODDERZHIVAEMYE_FORMATY_GRAFICHESKIE",
											"PODDERZHIVAEMYE_FORMATY_ZVUKOVYE",
											"PODDERZHIVAEMYE_FORMATY_DRUGIE",
											"ZASHCHITA_V_KOMPLEKTE",
										);
										

										foreach ( $arResult['PROPERTIES'] as $s_Code => $ar_PropertyValue )
										{
											if( in_array($s_Code, $ar_PropertyCode) && strlen($ar_PropertyValue['VALUE']) > 0 )
											{
												?>
												<tr class="color">
													<td><?=$ar_PropertyValue['NAME']?></td>
													<td><?=$ar_PropertyValue['VALUE']?></td>
												</tr>
												<?																								
											}
										}
										?>
									</table>
									<p><?=$arResult['DETAIL_TEXT']?></p>
								</div>
								
								<div class="hide" id="description2" style="position: relative; top: 0px; left: 0px; display: none;">

									<a rel="addOrder" class="buttonAddReview" href="#">Оставить отзыв</a>

									<div class="blockAllComments">
										<? $APPLICATION->IncludeComponent("klavazip:catalog.product.comment", ".default", array('ID' => $arResult['ID']), false);?>
									</div>
									
									<? 
									if($arResult['COMMENT_COUN'] > 4)
									{
										?>
										<div class="moreComments">
											<a class="openComment" href="#"><span>Показать все комментарии</span></a>
											<a class="closeComment" href="#"><span>Свернуть</span></a>
										</div>
										<?
									}
									?>
									
								</div>
								
							</div>							
						</div>
					</div>
				</div>
				<div class="rightInf">
					<? 
					$APPLICATION->IncludeComponent("klavazip:catalog.detail.list", "", array(
						'ANALOGI' 		=> $arResult['ANALOGS'],
						'SECTION_ID' 	=> $arResult['IBLOCK_SECTION_ID']	
						)
					);
					?>
				</div>
				<div class="clear"></div>
			</div>	
		</div>
	</div>