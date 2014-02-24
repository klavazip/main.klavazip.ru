<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	foreach ($arResult['ITEAM'] as $ar_Value)
	{
		?>
		<div class="boxOneProduct">
			<div class="productCont">
				<? 
				if (strlen($ar_Value['IMG']) > 0)
				{
					?><a href="<?=$ar_Value['DETAIL_URL']?>"><img alt="" src="<?=$ar_Value['IMG']?>"></a><?
				}	
				?>
				<a href="<?=$ar_Value['DETAIL_URL']?>"><?=$ar_Value['NAME']?></a>
				<div class="productBottom">
					<p class="price"><?=$ar_Value['PRICE']?> <span><?=KlavaMain::RUB?></span></p>
					<? 
					if( intval($ar_Value['QUANTITY_COUNT']) > 0 )
					{
						?><p class="present"><img src="/local/templates/klavazip.main/img/icon_present.png" alt="" /> Есть в наличии</p><?
					}	
					else if( $ar_Value['IS_ANALOGI'] )
					{
						?><p class="presentExt"> <img src="/local/templates/klavazip.main/img/icon_present.png" alt="" /> Есть аналоги</p><?  
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
							?><p class="present"> <img src="/local/templates/klavazip.main/img/no_product.png" alt="" /> Ожидается <br /> поступление: <?=$s_DateTranzitRes?></p><? 
						}	
						else
						{
							?><p class="present"><img src="/local/templates/klavazip.main/img/no_product.png" alt="" /> Нет в наличии</p><? 
						}
					}	
					?>
				</div>
			</div>
			<div class="productOpen">
				<div class="contOpenProduct">
					<div class="openProductBottom">
						<? 
						if(intval($ar_Value['QUANTITY']) > 0 || $ar_Value['IS_ANALOGI'])
						{
							?><input type="button" onclick="klava.addBasket('<?=$ar_Value['ID']?>', 1)" value="В корзину" class="buttonBuy"><?
						}
						else
						{
							?><input type="button" onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_Value['ID']?>')" value="Уведомить" class="buttonNotify"><?
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