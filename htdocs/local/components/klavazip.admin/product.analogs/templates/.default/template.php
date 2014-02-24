<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 

?>


	<div class="admin-link-redirect">
	    <form class="form-horizontal" action="" method="get">
		    <div class="control-group">
		   		<label class="control-label">Артикул</label>
		    	<div class="controls">
		    		<input type="text" value="<?=$_GET['ARTICUL']?>" name="ARTICUL">
		    	</div>
		    </div>
		    <br />
		    <div class="control-group">
			    <div class="controls">
				    <button class="btn btn-small btn-primary" type="submit" class="btn">Показать</button>
			    </div>
		    </div>
	    </form>
	</div>
	
	<br />
	<br />
	
	<? 
	
	if(count($arResult) > 0)
	{
		?>
		<b>Товар:</b>	
		<table class="table table-bordered table-hover">
			<tr class="success">
				<th>Артикул</th>
				<th>Название</th>
				<th>Кол-во товара</th>
				<th>Сортировка</th>
				<th>Картинка</th>
			</tr>
			<tr>									
				<td align="center"><?=$arResult['PRODUCT']['PROPERTY_CML2_ARTICLE_VALUE'] ?></td>
				<td><a target="_blank" href="<?=$arResult['PRODUCT']['DETAIL_PAGE_URL']?>"><?=$arResult['PRODUCT']['NAME']?></a>	 </td>
				<td align="center"><p><?=$arResult['PRODUCT']['CATALOG_QUANTITY']?> шт</p></td>
				<td align="center"><p><?=$arResult['PRODUCT']['SORT']?></p></td>
				<td align="center"><img width="30" src="<?=$arResult['PRODUCT']['IMG'] ?>" alt="" /></td>
			</tr>		
		
			
			<tr>
				<td colspan="3"><b>Аналоги:</b></td>
			</tr>	
			
		
			<tr class="warning">							
				<th>Артикул</th>
				<th>Название</th>
				<th>Кол-во товара</th>	
				<th>Сортировка</th>
				<th>Картинка</th>
			</tr>
			
			<? 
			foreach ($arResult['ANALOGI'] as $ar_Value)
			{
				?>
				<tr>									
					<td align="center"><?=$ar_Value['PROPERTY_CML2_ARTICLE_VALUE'] ?></td>
					<td><a target="_blank" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><?=$ar_Value['NAME']?></a>	 </td>
					<td align="center"><p><?=$ar_Value['CATALOG_QUANTITY']?> шт</p></td>
					<td align="center"><p><?=$ar_Value['SORT']?></p></td>
					<td align="center"><img width="30" src="<?=$ar_Value['IMG'] ?>" alt="" /></td>
				</tr>		
				<?
			}
			?>
							
		</table>

		
		<?
	}	
	?>

		
				
				
	
					
					