<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="headScroll">
	<div class="wrapperScroll">
		<div class="compareLeft">&nbsp;</div>
		<div class="compareRight">
			<div class="compareFirst2">
				<div class="blockCompareProducts">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<? 
							foreach ($arResult['ITEAM'] as $ar_Value)
							{
								?>
								<td>									
									<div class="oneProductCompareHead">
										<p><a href="<?=$ar_Value['DETAIL_URL']?>"><?=$ar_Value['NAME']?></a> <span>—</span> <?=$ar_Value['PRICE']?> <span class="curency"><?=KlavaMain::RUB?></span></p>
										<? 
										if( intval($ar_Value['QUANTITY']) > 0 )
										{
											?><a href="#" onclick="klava.addBasket('<?=$ar_Value['ID']?>', 1); return false;" class="basketHead"><span>В корзину</span></a><?	
										}
										else
										{
											?><a href="#" onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_Value['ID']?>'); return false;" class="basketHead"><span>Уведомить</span></a><?
										}
										?>
									</div>
								</td>
								<?
							}
							?>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>