<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 

?>

		
<table class="table table-bordered table-hover">
	<tr class="warning">
		<th>Номер заявки</th>
		<th>Дата заявки</th>
		<th>Кол-во товара</th>
		<th>Номер заказа</th>	
		<th>Пользователь</th>	
	</tr>
	<? 
	foreach ($arResult['ITEMS'] as $ar_Value)
	{
		?>
		<tr>									
			<td><a href="<?=$ar_Value['DETAIL_URL']?>"><?=$ar_Value['ID']?></a></td>
			<td><p><?=$ar_Value['DATE_CREATE']?></p></td>
			<td><p><?= count($ar_Value['PROPERTY_ELEMENT_ID_VALUE'])?></p></td>
			<td><?=$ar_Value['PROPERTY_ORDER_ID_VALUE']?></td>
			<td><?=$ar_Value['USER_NAME']?></td>
		</tr>		
		<?
	}
	?>
</table>
		
				
				
<div class="boxContTab">
	<div class="tableProducts historyOrder">
		<table cellpadding="0" cellspacing="0">
			<tr class="head">									
				<td><p>Номер заявки</p></a></td>
				<td><p>Дата заявки</p></a></td>
				<td><p>Кол-во товара</p></a></td>
				<td><p>Номер заказа</p></td>	
			</tr>
			
			<? 
			foreach ($arResult['ITEMS'] as $ar_Value)
			{
				?>
				<tr>									
					<td><a href="<?=$ar_Value['DETAIL_URL']?>"><?=$ar_Value['ID']?></a></td>
					<td><p><?=$ar_Value['DATE_CREATE']?></p></td>
					<td><p><?= count($ar_Value['PROPERTY_ELEMENT_ID_VALUE'])?></p></td>
					<td><?=$ar_Value['PROPERTY_ORDER_ID_VALUE']?></td>	
				</tr>		
				<?
			}
			?>
			
			
			<? /*?>
			
				<option value="N">Принят, ожидается оплата</option>
	<option value="P">Оплачен, формируется к отправке</option>
	<option value="F">Отправлен</option>
	<option value="D">Доставлен</option>
			
			<tr>									
				<td><a href="#">31531335</a></td>
				<td><p>16.04.2012</p></td>
				<td><p>560</p></td>
				<td><p class="price">122 122 <span class="curency">&#8399;</span></p></td>
				<td><p>Курьером в пределах МКАД</p></td>
				<td><p></p></td>
				<td>
					<div class="status_3 active"></div>
					<div class="status_4"></div>
					<div class="status_1"></div>
					<div class="status_2"></div>
					<div class="clear"></div>
					<div class="statusText_3">Подтвержден</div>
				</td>									
			</tr>		
			<tr>									
				<td><a href="#">35354355</a></td>
				<td><p>18.03.2012</p></td>
				<td><p>1</p></td>
				<td><p class="price">250 <span class="curency">&#8399;</span></p></td>
				<td><p>Транспортная компания «Грузовозофф»</p></td>
				<td><p>35453434</p></td>
				<td>
					<div class="status_3 disabled"></div>
					<div class="status_4 active"></div>
					<div class="status_1"></div>
					<div class="status_2"></div>
					<div class="clear"></div>
					<div class="statusText_4">Оплачен</div>
				</td>									
			</tr>			
			<tr>									
				<td><a href="#">24546546</a></td>
				<td><p>20.02.2012</p></td>
				<td><p>2</p></td>
				<td><p class="price">4 200 <span class="curency">&#8399;</span></p></td>
				<td><p>EMS Почта России</p></td>
				<td><p>546565466</p></td>
				<td>
					<div class="status_3 disabled"></div>
					<div class="status_4 disabled"></div>
					<div class="status_1 active"></div>
					<div class="status_2"></div>
					<div class="clear"></div>
					<div class="statusText_1">Отправлен</div>
				</td>									
			</tr>		
			<tr>									
				<td><a href="#">24546554</a></td>
				<td><p>22.01.2012</p></td>
				<td><p>1</p></td>
				<td><p class="price">321 <span class="curency">&#8399;</span></p></td>
				<td><p>EMS Почта России</p></td>
				<td><p>546465000</p></td>
				<td>
					<div class="status_3 disabled"></div>
					<div class="status_4 disabled"></div>
					<div class="status_1 disabled"></div>
					<div class="status_2 active"></div>
					<div class="clear"></div>
					<div class="statusText_2">Доставлен</div>
				</td>									
			</tr>	
			<tr>									
				<td><a href="#">24546546</a></td>
				<td><p>24.12.2011</p></td>
				<td><p>12</p></td>
				<td><p class="price">12 650 <span class="curency">&#8399;</span></p></td>
				<td><p>Транспортная компания «Грузовозофф»</p></td>
				<td><p>545454554</p></td>
				<td>
					<div class="statusCancel">Отменен</div>
				</td>									
			</tr>	
			<tr>									
				<td><a href="#">24546546</a></td>
				<td><p>26.11.2011</p></td>
				<td><p>4</p></td>
				<td><p class="price">1 232 <span class="curency">&#8399;</span></p></td>
				<td><p>EMS Почта России</p></td>
				<td><p>516355545</p></td>
				<td>
					<div class="status_3 disabled"></div>
					<div class="status_4 disabled"></div>
					<div class="status_1 disabled"></div>
					<div class="status_2 active"></div>
					<div class="clear"></div>
					<div class="statusText_2">Доставлен</div>
				</td>									
			</tr>	
			<tr>									
				<td><a href="#">23485684</a></td>
				<td><p>28.10.2011</p></td>
				<td><p>3</p></td>
				<td><p class="price">891 <span class="curency">&#8399;</span></p></td>
				<td><p>Транспортная компания «Грузовозофф»</p></td>
				<td><p>654654654</p></td>
				<td>
					<div class="status_3 disabled"></div>
					<div class="status_4 disabled"></div>
					<div class="status_1 disabled"></div>
					<div class="status_2 active"></div>
					<div class="clear"></div>
					<div class="statusText_2">Доставлен</div>
				</td>									
			</tr>	
			<?*/?>							
		</table>
	</div>	
	
	<?=$arResult["NAV_STRING"]?>
</div>	
					
					