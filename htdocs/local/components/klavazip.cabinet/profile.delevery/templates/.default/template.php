<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="boxContTab">
	<div class="documentOrder">							
		<div class="boxFormTab pad">
			<? 
			$i_MaxIndex = count($arResult['ITEMS']) - 1;
			foreach ($arResult['ITEMS'] as $i => $ar_Value)
			{
				//echo '<pre>', print_r($ar_Value).'</pre>';
				?>
				<div class="oneBlockStepInf">
					<table style="width: 100%">
						<tr>
							<td>
								<div style="width: 25px;">
									<input id="ID_PROFILE_ID_<?=$ar_Value['ID']?>" type="radio" onchange="klava.order.profileToggleView('js-profile-block-<?=$i?>')" <?=($i_MaxIndex == $i) ? 'checked="checked"' : ''?> value="" name="PROFILE_ID" class="js-order-profile">
								</div>
							</td>	
					    	<td style="text-align: left;width: 100%;">
					    		<label for="ID_PROFILE_ID_<?=$ar_Value['ID']?>" style="float: none; padding: inherit; width: inherit;"> 
					    			<h4><b><?=$ar_Value['NAME']?></b> (<?=$ar_Value['PERSONAL_TYPE']['NAME']?>)</h4>
					    		</label>
					    	</td>
					    	<td  class="profile-list-del"  style="padding: 5px; text-align: right;">
					    		<a onclick="klava.cabinet.deleteProfile(<?=$ar_Value['ID']?>); return false;" href="">удалить</a> 
					    	</td>	
					  	</tr>
					</table>
					
					<div class="js-profile-block-<?=$i?> js-profile" style="<?=($i_MaxIndex == $i) ? 'display: block;' : 'display: none;'?> margin-top: 30px;">
						<form action="" name="profile_<?=$ar_Value['ID']?>" id="profile_<?=$ar_Value['ID']?>" method="post">		
							<input type="hidden" name="PROFILE_ID" value="<?=$ar_Value['ID']?>"/>
							<input type="hidden" name="PERSON_TYPE_ID" value="<?=$ar_Value['PERSONAL_TYPE']['ID']?>"/>
							
							<? 
							$ar_Property = array();
							foreach ($ar_Value['PROPERTY'] as $ar_PropValue)
							{
								$ar_Property[$ar_PropValue['CODE']] = $ar_PropValue;
							}
							
							//echo '<pre>', print_r($ar_Property).'</pre>';
							?>
						
							<label>Название профиля <span>*</span></label>
							<div class="blockInputform">
								<input  type="text"  name="PROFILE_NAME" value="<?=$ar_Value['NAME']?>" onfocus="if(this.value=='<?=$ar_Value['NAME']?>'){this.value=''}" onblur="if(this.value==''){this.value='<?=$ar_Value['NAME']?>'}" />
							</div>
							<div class="clear"></div>
							
							<label>Регион <span>*</span></label>
							<div class="blockInputform">
								<input name="PROPERTY[REGION][ID]" type="hidden" value="<?=$ar_Property['REGION']['ID']?>" />
								<input  
									class="js-order-location"
									data-code="REGION" 
									type="text"  
									name="PROPERTY[REGION][VALUE]" 
									value="<?=$ar_Property['REGION']['VALUE']?>" 
									onfocus="if(this.value=='<?=$ar_Property['REGION']['VALUE']?>'){this.value=''}" 
									onblur="if(this.value==''){this.value='<?=$ar_Property['REGION']['VALUE']?>'}" 
								/>
								<div class="order-selected-block" style="display: none;"></div>
							</div>
							<div class="clear"></div>

							
							<label>Район</label>
							<div class="blockInputform">
								<input name="PROPERTY[RAION][ID]" type="hidden" value="<?=$ar_Property['RAION']['ID']?>" />
								<input  
									class="js-order-location"
									data-code="RAION" 
									type="text"  
									name="PROPERTY[RAION][VALUE]" 
									value="<?=$ar_Property['RAION']['VALUE']?>" 
									onfocus="if(this.value=='<?=$ar_Property['RAION']['VALUE']?>'){this.value=''}" 
									onblur="if(this.value==''){this.value='<?=$ar_Property['RAION']['VALUE']?>'}" 
								/>
								<div class="order-selected-block" style="display: none;"></div>
							</div>
							<div class="clear"></div>		
							
							
							<label>Город <span>*</span></label>
							<div class="blockInputform">
								<input name="PROPERTY[CITY][ID]" type="hidden" value="<?=$ar_Property['CITY']['ID']?>" />
								<input  
									type="text"  
									class="js-order-location"
									data-code="CITY" 
									name="PROPERTY[CITY][VALUE]"  
									value="<?=$ar_Property['CITY']['VALUE']?>" 
									onfocus="if(this.value=='<?=$ar_Property['CITY']['VALUE']?>'){this.value=''}" 
									onblur="if(this.value==''){this.value='<?=$ar_Property['CITY']['VALUE']?>'}" 
								/>
								<div class="order-selected-block" style="display: none;"></div>
							</div>
							<div class="clear"></div>		
							
							
							<label>Улица <span>*</span></label>
							<div class="blockInputform">
								<input name="PROPERTY[STREET][ID]" type="hidden" value="<?=$ar_Property['STREET']['ID']?>" />
								<input  
									class="js-order-location"
									data-code="STREET" 
									type="text"  
									name="PROPERTY[STREET][VALUE]"
									value="<?=$ar_Property['STREET']['VALUE']?>" 
									onfocus="if(this.value=='<?=$ar_Property['STREET']['VALUE']?>'){this.value=''}" 
									onblur="if(this.value==''){this.value='<?=$ar_Property['STREET']['VALUE']?>'}" 
								/>
								<div class="order-selected-block" style="display: none;"></div>
							</div>
							<div class="clear"></div>
							
							
							<label>Индекс <span>*</span></label>
							<div class="blockInputform">
								<input name="PROPERTY[ZIP][ID]" type="hidden" value="<?=$ar_Property['ZIP']['ID']?>" />
								<input 
									type="text"  
									maxlength="6"
									name="PROPERTY[ZIP][VALUE]"
									value="<?=$ar_Property['ZIP']['VALUE']?>" 
									onfocus="if(this.value=='<?=$ar_Property['ZIP']['VALUE']?>'){this.value=''}" 
									onblur="if(this.value==''){this.value='<?=$ar_Property['ZIP']['VALUE']?>'}" 
									class="index" 
								/>
								<p class="comment">6 знаков</p>
							</div>
							<div class="clear"></div>		
							
							
							
							<label>Дом</label>
							<div class="blockInputform">
								<input name="PROPERTY[DOM][ID]" type="hidden" value="<?=$ar_Property['DOM']['ID']?>" />
								<input  
									type="text"  
									name="PROPERTY[DOM][VALUE]"
									value="<?=$ar_Property['DOM']['VALUE']?>" 
									onfocus="if(this.value=='<?=$ar_Property['DOM']['VALUE']?>'){this.value=''}" 
									onblur="if(this.value==''){this.value='<?=$ar_Property['DOM']['VALUE']?>'}" 
									class="numberAddress"
									/>										
							</div>
							<div class="clear"></div>
								

							<label>Корпус</label>
							<div class="blockInputform">
								<input name="PROPERTY[KORPUS][ID]" type="hidden" value="<?=$ar_Property['KORPUS']['ID']?>" />
								<input 
									type="text"
									name="PROPERTY[KORPUS][VALUE]"
									value="<?=$ar_Property['KORPUS']['VALUE']?>" 
									onfocus="if(this.value=='<?=$ar_Property['KORPUS']['VALUE']?>'){this.value=''}" 
									onblur="if(this.value==''){this.value='<?=$ar_Property['KORPUS']['VALUE']?>'}" 
									class="numberAddress"
								/>
							</div>
							<div class="clear"></div>	

							<? 
							if($ar_Value['PERSONAL_TYPE']['ID'] == 1)
							{
								?>
								<label>Квартира</label>
								<div class="blockInputform">
									<input name="PROPERTY[KVARTIRA][ID]" type="hidden" value="<?=$ar_Property['KVARTIRA']['ID']?>" />
									<input  
										type="text"  
										name="PROPERTY[KVARTIRA][VALUE]" 
										value="<?=$ar_Property['KVARTIRA']['VALUE']?>" 
										onfocus="if(this.value=='<?=$ar_Property['KVARTIRA']['VALUE']?>'){this.value=''}" 
										onblur="if(this.value==''){this.value='<?=$ar_Property['KVARTIRA']['VALUE']?>'}" 
										class="numberAddress"
									/>
								</div>
								<?
							}
							else
							{
								?>
								<label>Офис</label>
								<div class="blockInputform">
									<input name="PROPERTY[OFFICE][ID]" type="hidden" value="<?=$ar_Property['OFFICE']['ID']?>" />
									<input  
										type="text"  
										name="PROPERTY[OFFICE][VALUE]" 
										value="<?=$ar_Property['OFFICE']['VALUE']?>" 
										onfocus="if(this.value=='<?=$ar_Property['OFFICE']['VALUE']?>'){this.value=''}" 
										onblur="if(this.value==''){this.value='<?=$ar_Property['OFFICE']['VALUE']?>'}" 
										class="numberAddress"
									/>
								</div>
								<?
							}
							?>
							
							<input name="PROPERTY[LOCATION][ID]" type="hidden" value="<?=$ar_Property['LOCATION']['ID']?>" />
							<input 
									type="hidden" 
									name="PROPERTY[LOCATION][VALUE]" 
									value="<?=$ar_Property['LOCATION']['VALUE']?>" 
									class="order-location-city-id"
									/>
									
							<div class="clear"></div>
							
							
							<? /*?>							
							<label>Способ доставки по умолчанию</label>
							<div class="blockInputform">
								<?
								$i_LocationID = $ar_Property['LOCATION']['VALUE'];
								foreach ($arResult['DELIVERY'][$i_LocationID] as $ar_Value)
								{
									?>
									<div class="lineRadio">
										<input type="radio" class="styledRadio radioButton" name="DELIVERY_<?=$i_LocationID?>" value="<?=$ar_Value['ID']?>" />
										<label for="radioButton_1"><?=$ar_Value['NAME']?></label>								
										<div class="clear"></div>
									</div>
									<?
								}
								?>
							</div>
							<div class="clear"></div>
							<? */ ?>
										
							<input type="button" onclick="klava.cabinet.saveParamsUserProfile('profile_<?=$ar_Value['ID']?>', this)" class="buttonBuy" value="Сохранить изменения"/>
							<div class="edit-profile-ajax-load">
								<div class="load"></div>
								<div class="result">Сохранено!</div>
							</div>		
						</form>
					
					</div>
					
				</div>
				
				<?				
			}
			?>		
		
		
		
		
		
		
										

		</div>							
	</div>										
</div>