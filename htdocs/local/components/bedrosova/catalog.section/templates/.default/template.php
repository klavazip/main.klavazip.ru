<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	if( count($_GET['filter']) > 0 && count($arResult["ITEMS"]) == 0 )
	{
		?><br /><br /><div class="error-block">Нечего не найдено по указаным параметрам, пожалуйста измените условия поиска.</div><?
	}	
	else
	{	
		$s_CatalogListView = (isset($_COOKIE['KLAVA_CATALOG_LIST_VIEW'])) ? $_COOKIE['KLAVA_CATALOG_LIST_VIEW'] : 'type1' ;
		?>
		
		<div class="contTabs">
			<div class="list-wrap">	
				<? 
				if($s_CatalogListView == 'type1')
				{
					?><div id="description1"><?
				}
				else 
				{
					?><div class="hide" id="description1" style="display: none;"><?
				}
				?>
				
				<div class="blockProducts">
					<? 
					if(count($arResult["ITEMS"]) > 0)
					{
						foreach ($arResult["ITEMS"] as $i_Key => $ar_Value)
						{
							$s_ImagesPath = ( strlen($ar_Value['PREVIEW_PICTURE']['SRC']) > 0 ) ? $ar_Value['PREVIEW_PICTURE']['SRC'] : '/local/templates/klavazip.main/img/no-pic-big.jpg';
							?>
							<div class="boxOneProduct">
								<div class="productCont">
									<a href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><img alt="" src="<?=$s_ImagesPath?>"></a>
									<a href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><?=$ar_Value['NAME']?></a>								
									<div class="productBottom">
										<p class="price"><?=intval($ar_Value['CATALOG_PRICE_4'])?> <span><?=KlavaMain::RUB?></span></p>
										<? 
										if( intval($ar_Value['CATALOG_QUANTITY']) > 0 )
										{
											?><p class="present">Есть в наличии</p><?
										}	
										else if( count($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] ) > 0 )
										{
											?><p class="presentExt">Нет в наличии <br /> но есть аналоги</p><?
										}	
										else 
										{
											if( strlen($ar_Value['DATA_TRANZITA']) > 0 )
											{
												$s_DateTranzit = $ar_Value['DATA_TRANZITA'];
												if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $s_DateTranzit, $regs))
													$s_DateTranzitRes = $regs[3].'.'.$regs[2].'.'.$regs[1];
												else
													$s_DateTranzitRes = $s_DateTranzit;
													
												$b_DateTranzitValied = ($DB->CompareDates($s_DateTranzitRes, date("d.m.Y")) < 0) ? false : true;
											}
											
											if($b_DateTranzitValied)
											{
												?><p class="present">Ожидается <br /> поступление: <?=$s_DateTranzitRes?></p><? 
											}	
											else
											{
												?><p class="present">Нет в наличии</p><? 
											}
										}	
										?>
									</div>
								</div> 
								<div class="productOpen">
									<div class="contOpenProduct">
										<div class="openProductBottom">
											
											<? 
											if(intval($ar_Value['CATALOG_QUANTITY']) > 0 || count($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] ) > 0)
											{
												?><input onclick="klava.addBasket('<?=$ar_Value['ID']?>', 1); return false;" type="submit" value="В корзину" class="buttonBuy"><?
											}
											else
											{
												?><input onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_Value['ID']?>')" title="Сообщить о поступлении товара" type="submit" value="Уведомить" class="buttonNotify"><?
											}
											?>
																			
											<a href="#" onclick="klava.addFavorites('<?=$ar_Value['ID']?>'); return false;" class="icon_2"></a>
											<a href="#" onclick="klava.addCompare('<?=$ar_Value['ID']?>'); return false;" class="icon_1"></a>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>					
							<?
						}
					}
					?>
					<div class="clear"></div>
				</div>
	
					<?
					echo $arResult["NAV_STRING"];
				
					if(($p = strpos($arResult['DESCRIPTION'], '###')) !== false) 
					{
						?><div class="text-desc"><?=substr( $arResult['DESCRIPTION'], 0, $p); ?></div><?
					}
					else
					{	 
						?><div class="text-desc"><?=$arResult['DESCRIPTION'];?></div><? 
					}
					?>
									
				</div>
				
				<? 
				if($s_CatalogListView == 'type2')
				{
					?><div id="description2" style="position: relative; top: 0px; left: 0px;"><?
				}
				else 
				{
					?><div class="hide" id="description2" style="position: relative; top: 0px; left: 0px; display: none;"><?
				}
				?>
							
					<div class="boxView2">
						
						<? 
						if(count($arResult["ITEMS"]) > 0)
						{
							foreach ($arResult["ITEMS"] as $i_Key => $ar_Value)
							{
								# Formating property
								$s_Proeprty = '';	
								foreach ($arResult['FILTER_PROPERTY_SHOW'] as $s_ProeprtyCode)
								{
									if( strlen($ar_Value['PROPERTIES'][$s_ProeprtyCode]['VALUE']) > 0 )
									{
										$s_Proeprty .= '&nbsp; '.$ar_Value['PROPERTIES'][$s_ProeprtyCode]['NAME'].' <span>'.$ar_Value['PROPERTIES'][$s_ProeprtyCode]['VALUE'].'</span>';
									}
								}	
								
								$s_ImagesPath = ( strlen($ar_Value['PREVIEW_PICTURE']['SRC']) > 0 ) ? $ar_Value['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/img/no-pic-big.jpg';
								?>
								<div class="oneBlockView">
									<div class="mainInfProduct">
										<a class="name" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><?=$ar_Value['NAME']?></a>
										<a href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><img alt="" src="<?=$s_ImagesPath?>"></a>
										<p>
											<span>Артикул <?=$ar_Value['PROPERTIES']['CML2_ARTICLE']['VALUE']?></span> 
											<?=$s_Proeprty?>
										</p>
									</div>
									<div class="infProductRight">		
										
										<? 
										if(intval($ar_Value['CATALOG_QUANTITY']) > 0 || count($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] ) > 0)
										{
											?><input type="button" onclick="klava.addBasket('<?=$ar_Value['ID']?>', 1); return false;" value="В корзину" class="buttonBuy"><?
										}
										else
										{
											?><input title="Сообщить о поступлении товара" onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_Value['ID']?>')" type="submit" value="Уведомить" class="buttonNotify"><?
										}
										?>
																			
										<a class="addCompare" href="#" onclick="klava.addCompare('<?=$ar_Value['ID']?>'); return false;"><span>К сравнению</span></a>
										<a class="addFavourite" href="#" onclick="klava.addFavorites('<?=$ar_Value['ID']?>'); return false;"><span>В избранное</span></a>
									</div>
									<div class="infProductPrice">
									
										<p class="price"><?=intval($ar_Value['CATALOG_PRICE_4'])?> <span><?=KlavaMain::RUB?></span></p>
									
										<? 
										if( intval($ar_Value['CATALOG_QUANTITY']) > 0 )
										{
											?><p class="present st_1">Есть в наличии</p><?
										}	
										else if( count($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] ) > 0 )
										{
											?><p class="present st_1">Нет в наличии <br /> но есть аналоги</p><?
										}	
										else 
										{
											if( strlen($ar_Value['PROPERTIES']['DATA_TRANZITA']['VALUE']) > 0 )
											{
												$s_DateTranzit = $ar_Value['PROPERTIES']['DATA_TRANZITA']['VALUE'];
												if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $s_DateTranzit, $regs))
													$s_DateTranzitRes = $regs[3].'.'.$regs[2].'.'.$regs[1];
												else
													$s_DateTranzitRes = $s_DateTranzit;
													
												$b_DateTranzitValied = ($DB->CompareDates($s_DateTranzitRes, date("d.m.Y")) < 0) ? false : true;
											}
											
											if($b_DateTranzitValied)
											{
												?><p class="present st_1">Ожидается <br /> поступление: <?=$s_DateTranzitRes?></p><? 
											}	
											else
											{
												?><p class="present st_1">Нет в наличии</p><? 
											}
										}
										?>
										
									</div>
									<div class="clear"></div>
									
									
									<? 
									if( count($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] ) > 0 )
									{
										?>
										<div class="analogProducts">
											<h3>аналоги:</h3>
											<div class="allAnalogs">
												<?
												foreach ($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] as  $ar_AnalogValue)
												{
													?>
													<div class="boxOneAnalog">
														<a href="<?=$ar_AnalogValue['DETAIL_PAGE_URL']?>"><img alt="" src="<?=$ar_AnalogValue['IMG']?>"></a>
														<div class="analogDescription">
															<a class="name" href="<?=$ar_AnalogValue['DETAIL_PAGE_URL']?>"><?=$ar_AnalogValue['NAME']?></a>
															<p><?=intval($ar_AnalogValue['PRICES']['PRICE'])?> <?=KlavaMain::RUB?></p>
														</div>
													</div>
													<?
												}
												?>
												<div class="clear"></div>
											</div>
										</div>
										<? 
									}
									?>
								</div>	
								<? 
							}	
						}		
										
						echo $arResult["NAV_STRING"];
						
						if(($p = strpos($arResult['DESCRIPTION'], '###')) !== false) 
						{
							?><div class="text-desc"><?=substr( $arResult['DESCRIPTION'], 0, $p); ?></div><?
						}
						else
						{	 
							?><div class="text-desc"><?=$arResult['DESCRIPTION'];?></div><? 
						}
						?>
						
					</div>
				</div>
				
				<? 
				if($s_CatalogListView == 'type3')
				{
					?><div id="description3" style="position: relative; top: 0px; left: 0px;"><?
				}
				else 
				{
					?><div class="hide" id="description3" style="position: relative; top: 0px; left: 0px; display: none;"><?
				}
				?>
					<div class="tableProducts">
						<? 
						if(count($arResult["ITEMS"]) > 0)
						{
							if( isset($_COOKIE['PAGE_ELEMENT_SORTING_8']) )
								$ar_Sort = explode('::', $_COOKIE['PAGE_ELEMENT_SORTING_8']);
							?>
							<table cellspacing="0" cellpadding="0">
								<tr class="head">
									<td><p>Фото</p></td>
									<td><a <?=($ar_Sort[0] == 'name') ? 'class="active"' : ''?> onclick="klava.setCatalogListSort('name', '<?=($ar_Sort[1] == 'asc') ? 'desc' : 'asc'?>'); return false;" href="#">Название</a></td>
									<td><a <?=($ar_Sort[0] == 'PROPERTY_CML2_ARTICLE') ? 'class="active"' : ''?> onclick="klava.setCatalogListSort('PROPERTY_CML2_ARTICLE', '<?=($ar_Sort[1] == 'asc') ? 'desc' : 'asc'?>'); return false;"  href="#">Артикул</a></td>
									<td><p>Характеристики</p></td>
									<td><a <?=($ar_Sort[0] == 'catalog_PRICE_4')  ? 'class="active"' : ''?> onclick="klava.setCatalogListSort('catalog_PRICE_4', '<?=($ar_Sort[1] == 'asc') ? 'desc' : 'asc'?>'); return false;" href="#">Цена</a></td>
									<td><a <?=($ar_Sort[0] == 'CATALOG_QUANTITY') ? 'class="active"' : ''?> onclick="klava.setCatalogListSort('CATALOG_QUANTITY', '<?=($ar_Sort[1] == 'asc') ? 'desc' : 'asc'?>'); return false;" href="#">Остаток</a></td>
									<td></td>
								</tr>
								<?
								foreach ($arResult["ITEMS"] as $i_Key => $ar_Value)
								{
									# Formating property
									$ar_ProeprtyValue = array();
									foreach ($arResult['FILTER_PROPERTY_SHOW'] as $s_ProeprtyCode)
									{
										if( strlen($ar_Value['PROPERTIES'][$s_ProeprtyCode]['VALUE']) > 0 )
											$ar_ProeprtyValue[] = $ar_Value['PROPERTIES'][$s_ProeprtyCode]['VALUE'];
									}
									
									$s_ImagesPath = ( strlen($ar_Value['PREVIEW_PICTURE']['SRC']) > 0 ) ? $ar_Value['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/img/no-pic-big.jpg';
									?>
									<tr>
										<td><a href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><img alt="" width="62" src="<?=$s_ImagesPath?>"></a></td>
										<td><a href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><?=$ar_Value['NAME']?></a></td>
										<td><p><?=$ar_Value['PROPERTIES']['CML2_ARTICLE']['VALUE']?></p></td>
										<td><p><?= implode('&nbsp; / &nbsp;', $ar_ProeprtyValue)?></p></td>
										<td><p class="price"><?=intval($ar_Value['CATALOG_PRICE_4'])?> <span><?=KlavaMain::RUB?></span></p></td>
										<td>
											<p class="number">
												<? 
												if( intval($ar_Value['CATALOG_QUANTITY']) > 0 )
												{
													if( intval($ar_Value['CATALOG_QUANTITY']) < 10 )
													{
														echo intval($ar_Value['CATALOG_QUANTITY']).' шт.'; 
													}	
													else
													{
														echo ' >10 шт.';
													}
												}	
												else if( count($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] ) > 0 )
												{
													?>Нет в наличии, <br />есть аналоги<?
												}	
												else 
												{
													if( strlen($ar_Value['PROPERTIES']['DATA_TRANZITA']['VALUE']) > 0 )
													{
														$s_DateTranzit = $ar_Value['PROPERTIES']['DATA_TRANZITA']['VALUE'];
														if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $s_DateTranzit, $regs))
															$s_DateTranzitRes = $regs[3].'.'.$regs[2].'.'.$regs[1];
														else
															$s_DateTranzitRes = $s_DateTranzit;
															
														$b_DateTranzitValied = ($DB->CompareDates($s_DateTranzitRes, date("d.m.Y")) < 0) ? false : true;
													}
													
													if($b_DateTranzitValied)
													{
														?>Ожидается <br /> поступление: <?=$s_DateTranzitRes?><? 
													}	
													else
													{
														?>Нет в наличии<? 
													}
												}
												?>
											</p>
										</td>
										
										<td>
											<? 
											if (intval($ar_Value['CATALOG_QUANTITY']) > 0 || count($arResult['ITEMS_ANALOGS'][$ar_Value['ID']] ) > 0)
											{
												?><a title="Добавить в корзину" class="basketTable" href="#" onclick="klava.addBasket('<?=$ar_Value['ID']?>', 1); return false;"></a><?	
											}
											else
											{
												?><a title="Сообщить о поступлении товара" class="basketTableNotyfy" href="#" onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_Value['ID']?>'); return false;"></a><?
											}
											?>
										</td>
									</tr>
									<?
								}	
								?>
							</table>
							<?	
						}	
						
						echo $arResult["NAV_STRING"];
						
						if(($p = strpos($arResult['DESCRIPTION'], '###')) !== false) 
						{
							?><div class="text-desc"><?=substr( $arResult['DESCRIPTION'], 0, $p); ?></div><?
						}
						else
						{	 
							?><div class="text-desc"><?=$arResult['DESCRIPTION'];?></div><? 
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<? 
	}	