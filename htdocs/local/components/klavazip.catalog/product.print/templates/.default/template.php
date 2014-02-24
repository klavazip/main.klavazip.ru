<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

	<div class="headerPrintRight">
		<p><span>www.klavazip.ru</span> Полный адрес страницы:<br/> http://klavazip.ru/catalog/matritsy/diagonali-10-1/n101lge-l11/</p>
		</div>
	</div>
	
	<div class="printCard">

	<h1><?=$arResult['PRODUCT']['NAME']?></h1>
	<div class="printCardLeft">
		<div class="printStars">
			
			<? 
			for( $i = 1; $i <= 5; $i++ )
			{
				if( $i <= $arResult['PRODUCT']['REATING'] )
				{
					?><a href="#" class="active"></a><? 
				}
				else
				{
					?><a href="#"></a><?
				}
			}
			?>
			<p>арт. <?=$arResult['PRODUCT']['ARTICUL']?></p>
			<div class="clear"></div>				
		</div>
		<img src="<?=$arResult['PRODUCT']['IMG']?>" alt="" />
		<p class="mainDescription"><?=$arResult['PRODUCT']['TEXT']?></p>
	</div>
	<div class="printCardRight">
		
		<? /*?>
		<p class="oldPrice"><?= intval($arResult['PRODUCT']['PRICE'])?> <span class="curency">&#8399;</span></p>
		<? */ ?>
		
		<p class="price"><?=intval($arResult['PRODUCT']['PRICE'])?> <span class="curency">&#8399;</span></p>
		<p class="present"><?=( intval($arResult['PRODUCT']['COUNT']) > 0 ) ? 'Есть' : 'Нет' ?> в наличии </p>
		
		<? /*?>
		<p class="descriptionText">Цвет: желтый<br/> Емкость: 120 фарад</p>
		<? */ ?>
		
		<div class="linesDescription">
		
			<h4>Характеристики</h4>
			
			<? 
			foreach ($arResult['PRODUCT']['PROPERTY'] as $ar_Value)
			{
				?>
				<div class="oneLineDescriptionPrint">
					<p><?=$ar_Value['NAME']?></p>
					<p><?=$ar_Value['VALUE']?></p>
					<div class="clear"></div>
				</div>
				<?
			}
			?>

		</div>
	</div> 
	
	<? 
	//echo '<pre>', print_r($arResult['PRODUCT']).'</pre>';
	?>