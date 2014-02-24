<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


	if( count($arResult['ITEMS']) > 0 )
	{
		?>
		<div class="catalog-producer">
			<h4>Производители:</h4>  
			<?		
			foreach ($arResult['ITEMS'] as $ar_Value)
			{
				?><a href="<?=$ar_Value['URL']?>"><?=$ar_Value['NAME']?></a> <?
			}	
			?>
		</div>
		<? 
	}	
	
	
					










