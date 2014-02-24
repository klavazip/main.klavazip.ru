<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


	
	<? 
	
	if($_GET['add'] == 'Y')
	{
		?><div class="alert alert-info">Запись успешно добавлена</div><?
	}	
	
	if( count($arResult['ERROR']) > 0 )
	{
		?>
		<div class="alert alert-success">
			<?=implode('<br />', $arResult['ERROR'])?>
	    </div>
		<?
		
	}	
	?>
	
	<div class="admin-link-redirect">
	
	    <form class="form-horizontal" action="" method="post">
		    <div class="control-group">
		   		<label class="control-label">Старая ссылка</label>
		    	<div class="controls">
		    		<input type="text" name="OLD_LINK">
		    	</div>
		    </div>
		    <div class="control-group">
		    	<label class="control-label">Новая ссылка</label>
		    	<div class="controls">
		    		<input type="text" name="NEW_LINK">
		    	</div>
		    </div>
		    
		    <br />
		    <div class="control-group">
			    <div class="controls">
				    <button class="btn btn-small btn-primary" type="submit" class="btn">Сохранить</button>
			    </div>
		    </div>
	    </form>
	</div>

    