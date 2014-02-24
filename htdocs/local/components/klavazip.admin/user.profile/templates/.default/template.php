<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

	<div class="admin-link-redirect">
	    <form class="form-horizontal" action="" method="get">
		    <div class="control-group">
		   		<label class="control-label">XML_ID пользователя </label>
		    	<div class="controls">
		    		<input style="width: 100%" type="text" value="<?=htmlspecialchars($_GET['XML_ID'])?>" name="XML_ID">
		    	</div>
		   		<label class="control-label">ID пользователя</label>
		    	<div class="controls">
		    		<input style="width: 100%" type="text" value="<?=intval($_GET['ID'])?>" name="ID">
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
	if(count($arResult['USER']) > 0)
	{
		?>
		<b>Товар:</b>	
		<table class="table table-bordered table-hover">
			<tr class="success">
				<th>ID</th>
				<th>Имя</th>
				<th>Фамилия</th>
				<th>Отчетсво</th>
				<th>Email</th>
				<th>Логин</th>
				<th>XML_ID</th>
			</tr>
			<tr>									
				<td align="center"><?=$arResult['USER']['ID']?></td>
				<td><?=$arResult['USER']['NAME']?></td>
				<td align="center"><?=$arResult['USER']['LAST_NAME']?></td>
				<td align="center"><?=$arResult['USER']['SECOND_NAME']?></td>
				<td align="center"><?=$arResult['USER']['EMAIL']?></td>
				<td align="center"><?=$arResult['USER']['LOGIN']?></td>
				<td align="center"><?=$arResult['USER']['XML_ID']?></td>
			</tr>
			<tr>
				<th colspan="7">
					Список прифилей (<?=count($arResult['USER']['PROFILE'])?>)				
				</th>
				<tr>
					<td colspan="7">
						<table class="table table-bordered table-hover">
							<tr class="success">
								<th>ID</th>
								<th>Название</th>
								<th>Тип плательщика</th>
								<th>Дата последнего обновления</th>
								<th>XML_ID</th>
								<th>-</th>
							</tr>
							
							<? 
							foreach ($arResult['USER']['PROFILE'] as $ar_ValueProf)
							{
								?>
								<tr>
									<td><?=$ar_ValueProf['ID']?></td>
									<td><?=$ar_ValueProf['NAME']?></td>
									<td>
										<? 
										switch ($ar_ValueProf['PERSON_TYPE_ID'])
										{
											case 1: echo 'Физическое лицо [1]'; break;
											case 2: echo 'Юридическое лицо [2]'; break;
											case 3: echo 'ИП [3]'; break;
										}
										?>
									</td>
									<td><?=$ar_ValueProf['DATE_UPDATE']?></td>
									<td><?=$ar_ValueProf['XML_ID']?></td>
									<td><a href="<?=$APPLICATION->GetCurPageParam('delprofilleid='.$ar_ValueProf['ID'])?>">Удалить</a> </td>
								</tr>	
								<tr>
									<td></td>
									<td>Свойтсва:</td>
									<td colspan="4">
										<table class="table table-bordered table-hover">
											<tr class="success">
												<th>Название</th>
												<th>Значение</th>
												<th>Символьный код</th>
											</tr>
											<? 
											foreach ($ar_ValueProf['PROPERTY'] as $ar_Val)
											{
												?>
												<tr>
													<td><?=$ar_Val['PROP_NAME']?></td>	
													<td><?=$ar_Val['VALUE']?></td>	
													<td><?=$ar_Val['CODE']?></td>	
												</tr>
												<?
											}	
											?>
										</table>	
									</td>
								</tr>						
								<?						
							}	
							?>
						</table>	
					</td>
				</tr>
			</tr>
		</table>
		<?
	}	
	?>

		
				