<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
	
	<div class="oneBlockAnalogProducts">
		<h3>Похожие товары</h3>
		<? 
		foreach ($arResult['RELATED_PRODUCT'] as $ar_Val)
		{
			?>
			<div class="oneMoreProduct">
				<p>
					<a href="<?=$ar_Val['DETAIL_URL']?>"><?=$ar_Val['NAME']?></a> 
					
					<?=$ar_Val['PRICE']?> <span class="curency"><?=KlavaMain::RUB?></span>
				</p>
				<a href="<?=$ar_Val['DETAIL_URL']?>"><img style="width: auto;" alt="" src="<?=$ar_Val['IMG']?>"></a>
				<div class="clear"></div>
			</div>
			<?
		}
		?>
	</div>	
	<div class="oneBlockAnalogProducts">
		<h3>Популярные товары</h3>
		<? 
		foreach ($arResult['POPULAR_PRODUCT'] as $ar_Val)
		{
			?>
			<div class="oneMoreProduct">
				<p><a href="<?=$ar_Val['DETAIL_URL']?>"><?=$ar_Val['NAME']?></a> <?=$ar_Val['PRICE']?> <span class="curency"><?=KlavaMain::RUB?></span></p>
				<a href="<?=$ar_Val['DETAIL_URL']?>"><img style="width: auto;" alt="" src="<?=$ar_Val['IMG']?>"></a>
				<div class="clear"></div>
			</div>
			<?
		}
		?>
	</div>	
	<?
	/*
	foreach ($arResult['ITEAM'] as $ar_Value)
	{
		?><div class="oneBlockAnalogProducts"><h3>Другие товары</h3><?
			foreach ($ar_Value as $ar_Val)
			{
				?>
				<div class="oneMoreProduct">
					<a href="<?=$ar_Val['DETAIL_URL']?>"><img style="width: auto;" alt="" src="<?=$ar_Val['IMG']?>"></a>
					<p><a href="<?=$ar_Val['DETAIL_URL']?>"><?=$ar_Val['NAME']?></a> <?=$ar_Val['PRICE']?> <span class="curency"><?=KlavaMain::RUB?></span></p>
					<div class="clear"></div>
				</div>
				<?
			}
		?></div><?
	}
	?>
	<div class="clear"></div>
	<? */ ?>