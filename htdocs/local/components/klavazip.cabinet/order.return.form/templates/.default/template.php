<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="boxContTab">
	<div class="documentOrder">
		<? 
		if( isset($_GET['result_id']) )
		{
			?><div class="result-block" style="padding-left: 0">Ваша зявка у спешно отправлена. Номер вашей заявки <b><?=intval($_GET['result_id'])?></b></div><?
		}
		else
		{
			?>
			
			<h1>Заявка на возврат товаров из заказа <span><?=$arResult['ORDER']['ID']?><span> от <?=$arResult['ORDER']['DATE_INSERT']?></span></span></h1>	
			
			<div class="tableHistoryOrder history">
				<table cellspacing="0" cellpadding="0">
					<tr class="head">										
						<td><p>Фото</p></td>
						<td><p>Название</p></td>
						<td><p>Артикул</p></td>
						<td><p>Характеристики</p></td>
						<td><p>Количество</p></td>
						<td><p>Стоимость</p></td>
					</tr>
					<? 
					foreach ($arResult['PRODUCT'] as $ar_Value)
					{
						?>
						<tr>										
							<td><a target="_blank" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><img alt="" width="62" src="<?=$ar_Value['IMG']?>"></a></td>
							<td><a target="_blank" class="name" href="<?=$ar_Value['DETAIL_PAGE_URL']?>"><?=$ar_Value['NAME']?></a></td>
							<td><p><?=$ar_Value['ARTICUL']?></p></td>
							<td><p><?=$ar_Value['PROPERTY']?></p></td>
							<td><p class="number"><?=$ar_Value['COUNT']?></p></td>
							<td><p class="price"><?=$ar_Value['PRICE']?> <span class="curency">⃏</span></p></td>
						</tr>
						<?					
					}
					?>
				</table>
			</div>							
			<div class="rightInfOrder">
				<p>Сумма по товарам: <strong><?=$arResult['PRICE_PRODUCT_SUMM']?> <span class="curency">⃏</span></strong></p>								
			</div>
			<div class="clear"></div>
			<div class="top_1"></div>
			<div class="boxFormTab">
				<form enctype="multipart/form-data" method="post" name="rerurn-order" id="rerurn-order">
					
					<? /*?>									
					<label>Причина возврата</label>
					<div class="blockInputform">
						<div class="boxSelect_1">
							<div class="lineForm">	
								<select  id="reason" name="reason" class="se418" tabindex="1">
									<option value="">Брак</option>	
									<option value="">Брак2</option>																								
								</select>
							</div>
						</div>
					</div>
					<? */ ?>	
					
					<label>Причина возврата</label>
					
					<div class="blockInputform">
						<textarea rows="1" cols="1" id="js-return-text" name="TEXT_RETURN"><?=htmlspecialchars($_POST['TEXT_RETURN']) ?></textarea>
					</div>
					
					<div class="clear"></div>		
					<label>Фото товара</label>
					<div class="blockInputform">
	
						<div id="return-order-upload-foto">
							<input type="file" value="" name="FILE[]" />
						</div>
						<a class="morePhotos" onclick="klava.cabinet.returnOrderAddControlUpload();return false;" href="#"><span>Загрузить еще фото</span></a>
	
						<? /*?>
						<p class="nameImg">foto_brak.jpg</p>
						<a class="delImg" href="#"></a>
						<div class="clear"></div>
						<? */ ?>	
					</div>
	
					
					<div class="clear"></div>								
					<input type="submit" value="Отправить заявку" class="buttonBuy">										
				</form>
				
				<? 
				if(count($arResult['ERROR']) > 0)
				{
					?> <br />
					<div style="padding-left: 0" class="error-block"><?=implode('<br />', $arResult['ERROR'])?></div>
					<?
				}
				?>
			</div>	
			<? 
		}
		?>
		
		
	</div>										
</div>



