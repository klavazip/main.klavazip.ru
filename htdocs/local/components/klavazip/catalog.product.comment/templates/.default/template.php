<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	if(count($arResult['ITEM']) > 0)
	{
		foreach ($arResult['ITEM'] as $ar_Value)
		{
			?>
			<div class="boxOneReview">
				<div class="boxStar2">
					<? 
					for( $i = 1; $i <= 5; $i++ )
					{
						if( $i <= $ar_Value['PROPERTY_REATING_STAR_VALUE'])
						{
							?><a class="active" href="#"></a><?						
						}
						else
						{
							?><a href="#"></a><?		
						}
					}
					?>
					<div class="clear"></div>	
				</div>
				<p class="name"><span><?=$ar_Value['NAME']?></span> <?=KlavaMain::formadDateMes($ar_Value['DATE_CREATE'], true)?></p>
				<p><?=$ar_Value['PREVIEW_TEXT']?></p>
			</div>
			<?
		}
	}