<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


function PrintPropsForm($arSource=Array(), $PRINT_TITLE = "", $arParams)
{
	if (!empty($arSource))
	{
		$ar_Result = array();
		foreach ($arSource as $ar_Value)
		{
			$ar_Result[$ar_Value['GROUP_NAME']][] = $ar_Value; 
		}
		
		//echo '<pre>', print_r($ar_Result).'</pre>';
		
		foreach($ar_Result as $s_GroupNname => $arProperties)
		{
			?>
			<div class="oneBlockOrderForm">
				<h4><?=$s_GroupNname?></h4>								

				<div class="boxFormTab">		
												
					<? 
					foreach ($arProperties as $ar_PropertyVal)
					{
						if($ar_PropertyVal["TYPE"] !== "LOCATION")
						{
							?>
							<label>
								<?=$ar_PropertyVal['NAME']?>
								<? 
								if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
								{
									?><span><?=($ar_PropertyVal["REQUIED_FORMATED"] == "Y") ? '*' : ''?></span><?
								}
								?>
							</label>
							<?							
						}
						?>
						
						<div class="blockInputform" style="position: relative;" id="js-field-block-<?=$ar_PropertyVal['CODE']?>">
						
							<?
							if($ar_PropertyVal["TYPE"] == "CHECKBOX")
							{
								?><input type="checkbox" name="<?=$ar_PropertyVal["FIELD_NAME"]?>" value="Y"<?if ($ar_PropertyVal["CHECKED"]=="Y") echo " checked";?>><?
							}
							elseif($ar_PropertyVal["TYPE"] == "TEXT")
							{
								switch (true)
								{
									
									case ($ar_PropertyVal['CODE'] == 'INN' || $ar_PropertyVal['CODE'] == 'CODE_PO_OKPO'):
										
										?><input
												type="text" 
												maxlength="10" 
												class="index"
												data-validation="<?=($ar_PropertyVal["REQUIED_FORMATED"] == 'Y') ? 'Y' : 'N'?>"
												data-code="<?=$ar_PropertyVal['CODE']?>"
												name="<?=$ar_PropertyVal["FIELD_NAME"]?>"
												onblur="if(this.value==''){this.value='_ _ _ _ _ _ _ _ _ _'}" 
												onfocus="if(this.value=='_ _ _ _ _ _ _ _ _ _'){this.value=''}" 
												value="<?=(strlen($ar_PropertyVal["VALUE"]) > 0) ? $ar_PropertyVal["VALUE"] : '_ _ _ _ _ _ _ _ _ _'?>"
												>
												<p class="comment">10 знаков</p>
										<?
										if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
										{
											?><div class="order-error">Поле обязательно к заполнению</div><?
										}
																				
										break;
										
										
									case ($ar_PropertyVal['CODE'] == 'KPP' || $ar_PropertyVal['CODE'] == 'ZIP' || $ar_PropertyVal['CODE'] == 'UZIP' || $ar_PropertyVal['CODE'] == 'BIK'):
										
										?><input
												type="text" 
												maxlength="9" 
												class="index"
												data-validation="<?=($ar_PropertyVal["REQUIED_FORMATED"] == 'Y') ? 'Y' : 'N'?>"
												data-code="<?=$ar_PropertyVal['CODE']?>"
												name="<?=$ar_PropertyVal["FIELD_NAME"]?>"
												onblur="if(this.value==''){this.value='_ _ _ _ _ _ _ _ _ _'}" 
												onfocus="if(this.value=='_ _ _ _ _ _ _ _ _ _'){this.value=''}" 
												value="<?=(strlen($ar_PropertyVal["VALUE"]) > 0) ? $ar_PropertyVal["VALUE"] : '_ _ _ _ _ _ _ _ _ _'?>"
												>
												<p class="comment">9 знаков</p>
										<?
										if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
										{
											?><div class="order-error">Поле обязательно к заполнению</div><?
										}
																				
										break;
										
									case ($ar_PropertyVal['CODE'] == 'EMAIL'):
										
										?><input
												type="text" 
												class="index"
												data-validation="<?=($ar_PropertyVal["REQUIED_FORMATED"] == 'Y') ? 'Y' : 'N'?>"
												data-code="<?=$ar_PropertyVal['CODE']?>"
												name="<?=$ar_PropertyVal["FIELD_NAME"]?>"
												value="<?=$ar_PropertyVal["VALUE"]?>"
												>
												<p class="comment"><?=$ar_PropertyVal['DESCRIPTION']?></p>
										<?
										if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
										{
											?><div class="order-error">Поле обязательно к заполнению</div><?
										}
																				
										break;
										
										
									case (
											$ar_PropertyVal['CODE'] == 'DOM' || 
											$ar_PropertyVal['CODE'] == 'UDOM' || 
											$ar_PropertyVal['CODE'] == 'KORPUS' || 
											$ar_PropertyVal['CODE'] == 'KVARTIRA' || 
											$ar_PropertyVal['CODE'] == 'KORP' || 
											$ar_PropertyVal['CODE'] == 'UKORP' || 
											$ar_PropertyVal['CODE'] == 'OFFICE' ||
											$ar_PropertyVal['CODE'] == 'UOFFICE'
											):
										
										?><input
												type="text" 
												maxlength="3"
												class="numberAddress"
												data-validation="<?=($ar_PropertyVal["REQUIED_FORMATED"] == 'Y') ? 'Y' : 'N'?>"
												data-code="<?=$ar_PropertyVal['CODE']?>"
												name="<?=$ar_PropertyVal["FIELD_NAME"]?>"
												value="<?=$ar_PropertyVal["VALUE"]?>"
												>
												<p class="comment"><?=$ar_PropertyVal['DESCRIPTION']?></p>
										<?
										if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
										{
											?><div class="order-error">Поле обязательно к заполнению</div><?
										}
																				
										break;
										
										
									case ($ar_PropertyVal['CODE'] == 'PHONE_SMS' || $ar_PropertyVal['CODE'] == 'PHONE'):
										
										?><input
											type="text" 
											class="index js-phone-mask"
											data-validation="<?=($ar_PropertyVal["REQUIED_FORMATED"] == 'Y') ? 'Y' : 'N'?>"
											data-code="<?=$ar_PropertyVal['CODE']?>"
											name="<?=$ar_PropertyVal["FIELD_NAME"]?>"
											value="<?=(strlen($ar_PropertyVal["VALUE"]) > 0 ) ? $ar_PropertyVal["VALUE"] : ''?>" 
											>
											<p class="comment"><?=$ar_PropertyVal['DESCRIPTION']?></p>
										<?
										if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
										{
											?><div class="order-error">Поле обязательно к заполнению</div><?
										}
																				
										
										
										break;
										
									
									case (
										$ar_PropertyVal['CODE'] == 'CITY'    || 
										$ar_PropertyVal['CODE'] == 'REGION'  || 
										$ar_PropertyVal['CODE'] == 'RAION'   || 
										$ar_PropertyVal['CODE'] == 'STREET'  ||
										$ar_PropertyVal['CODE'] == 'UCITY'   || 
										$ar_PropertyVal['CODE'] == 'UREGION' || 
										$ar_PropertyVal['CODE'] == 'URAION'  || 
										$ar_PropertyVal['CODE'] == 'USTREET'
										):
										
										?>
										<span class="ajax-load" style="display: none;"></span>
										<input
											autocomplete="off"
											type="text" 
											class="js-order-location"
											data-id=""
											data-delivery="<?=($s_GroupNname == 'Данные для доставки' && $ar_PropertyVal['CODE'] == 'CITY') ? 'Y' : 'N'?>"
											data-validation="<?=($ar_PropertyVal["REQUIED_FORMATED"] == 'Y') ? 'Y' : 'N'?>"
											data-code="<?=$ar_PropertyVal['CODE']?>"
											name="<?=$ar_PropertyVal["FIELD_NAME"]?>"
											value="<?=(strlen($ar_PropertyVal["VALUE"]) > 0 ) ? $ar_PropertyVal["VALUE"] : ''?>" 
											>
											<div class="order-selected-block" style="display: none;"></div>
											<p class="comment"><?=$ar_PropertyVal['DESCRIPTION']?></p>
										<?
										if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
										{
											?><div class="order-error">Поле обязательно к заполнению</div><?
										}
																				
										
										
										break;
										
										
									default:

										   
										?><input
												type="text" 
												maxlength="<?=(intval($ar_PropertyVal['SIZE1']) > 0) ? $ar_PropertyVal['SIZE1'] : 250 ?>" 
												data-validation="<?=($ar_PropertyVal["REQUIED_FORMATED"] == 'Y') ? 'Y' : 'N'?>"
												data-code="<?=$ar_PropertyVal['CODE']?>"
												value="<?=$ar_PropertyVal["VALUE"]?>" 
												name="<?=$ar_PropertyVal["FIELD_NAME"]?>"
											>
											<p class="comment"><?=$ar_PropertyVal['DESCRIPTION']?></p>
											<? 
											if($ar_PropertyVal["REQUIED_FORMATED"] == 'Y')
											{
												?><div class="order-error">Поле обязательно к заполнению</div><?
											}
																				
										break;
									
								}
								
							
							
							}
							elseif($ar_PropertyVal["TYPE"] == "SELECT")
							{
								?>
								<div class="boxSelect_2">
									<div class="lineForm">									
										<select id="<?=$ar_PropertyVal["FIELD_NAME"]?>" name="<?=$ar_PropertyVal["FIELD_NAME"]?>" class="se218" tabindex="1">
											<? 
											foreach($ar_PropertyVal["VARIANTS"] as $arVariants)
											{
												?><option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option><?
											}
											?>
										</select>
									</div>
								</div>
								<?
							}
							elseif ($ar_PropertyVal["TYPE"] == "MULTISELECT")
							{
								?><select multiple name="<?=$ar_PropertyVal["FIELD_NAME"]?>" size="<?=$ar_PropertyVal["SIZE1"]?>"><?
									foreach($arProperties["VARIANTS"] as $arVariants)
									{
										?><option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option><?
									}
								?></select><?
								
								?>
								<div class="boxSelect_2">
									<div class="lineForm">									
									
										<select id="region" name="region" class="se218" tabindex="1" multiple name="<?=$ar_PropertyVal["FIELD_NAME"]?>" size="<?=$ar_PropertyVal["SIZE1"]?>">
											<? 
											foreach($arProperties["VARIANTS"] as $arVariants)
											{
												?><option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option><?
											}
											?>
										</select>
									</div>
								</div>
								<?
								
								
							}
							elseif ($ar_PropertyVal["TYPE"] == "TEXTAREA")
							{
								?><textarea rows="<?=$ar_PropertyVal["SIZE2"]?>" cols="<?=$ar_PropertyVal["SIZE1"]?>" name="<?=$ar_PropertyVal["FIELD_NAME"]?>"><?=$ar_PropertyVal["VALUE"]?></textarea><?
							}
							elseif ($ar_PropertyVal["TYPE"] == "LOCATION")
							{
								?>
								<input type="hidden" name="COUNTRY_ORDER_PROP_5ORDER_PROP_<?=$ar_PropertyVal["ID"]?>" value="35" />
								<input 
									type="hidden" 
									name="ORDER_PROP_<?=$ar_PropertyVal["ID"]?>" 
									value="<?=(intval($_POST['ORDER_PROP_'.$ar_PropertyVal["ID"]]) > 0 ) ? intval($_POST['ORDER_PROP_'.$ar_PropertyVal["ID"]]) : '2954' ?>" 
									id="order-location-city-id"
									class="order-location-city-id"   
									
									/>
								<?
							}
							?>
						
															
						</div>
						<div class="clear"></div>	
						<?
					}
					?>							
				</div>															
			</div>
			<?
		}
		
		/*
		if (strlen($PRINT_TITLE) > 0)
		{
			?>
			<b><?= $PRINT_TITLE ?></b><br /><br />
			<?
		}
		?>
		<table class="sale_order_full_table">
		<?
		foreach($arSource as $arProperties)
		{
			if($arProperties["SHOW_GROUP_NAME"] == "Y")
			{
				?>
				<tr>
					<td colspan="2" align="center">
						<b><?= $arProperties["GROUP_NAME"] ?></b>
					</td>
				</tr>
				<?
			}
			?>
			<tr>
				<td align="right" valign="top">
					<?= $arProperties["NAME"] ?>:<?
					if($arProperties["REQUIED_FORMATED"]=="Y")
					{
						?><span class="sof-req">*</span><?
					}
					?>
				</td>
				<td>
					<?
					if($arProperties["TYPE"] == "CHECKBOX")
					{
						?>
						<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
						<?
					}
					elseif($arProperties["TYPE"] == "TEXT")
					{
						?>
						<input type="text" maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>">
						<?
					}
					elseif($arProperties["TYPE"] == "SELECT")
					{
						?>
						<select name="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
						<?
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
						}
						?>
						</select>
						<?
					}
					elseif ($arProperties["TYPE"] == "MULTISELECT")
					{
						?>
						<select multiple name="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
						<?
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
						}
						?>
						</select>
						<?
					}
					elseif ($arProperties["TYPE"] == "TEXTAREA")
					{
						?>
						<textarea rows="<?=$arProperties["SIZE2"]?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
						<?
					}
					elseif ($arProperties["TYPE"] == "LOCATION")
					{
						$value = 0;
						foreach ($arProperties["VARIANTS"] as $arVariant) 
						{
							if ($arVariant["SELECTED"] == "Y") 
							{
								$value = $arVariant["ID"]; 
								break;
							}
						}
						
						if ($arParams["USE_AJAX_LOCATIONS"] == "Y"):
							$GLOBALS["APPLICATION"]->IncludeComponent(
								"bitrix:sale.ajax.locations",
								".default",
								array(
									"AJAX_CALL" => "N",
									"COUNTRY_INPUT_NAME" => "COUNTRY_".$arProperties["FIELD_NAME"],
									"REGION_INPUT_NAME" => "REGION_".$arProperties["FIELD_NAME"],
									"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
									"CITY_OUT_LOCATION" => "Y",
									"LOCATION_VALUE" => $value,
									"ORDER_PROPS_ID" => $arProperties["ID"],
									"ONCITYCHANGE" => "",
								),
								null,
								array('HIDE_ICONS' => 'Y')
							);						
						else:
						?>
						<select name="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
						<?
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<option value="<?=$arVariants["ID"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
						}
						?>
						</select>
						<?
						endif;
					}
					elseif ($arProperties["TYPE"] == "RADIO")
					{
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>" value="<?=$arVariants["VALUE"]?>"<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>"><?=$arVariants["NAME"]?></label><br />
							<?
						}
					}

					if (strlen($arProperties["DESCRIPTION"]) > 0)
					{
						?><br /><small><?echo $arProperties["DESCRIPTION"] ?></small><?
					}
					?>
					
				</td>
			</tr>
			<?
		}
		?>
		</table>
		<?
		*/
		
		return true;
	}
	return false;
}
?>

	<div class="oneBlockStepInf"><h4><b>Профили</b></h4></div>

	<?  
	//echo '<pre>', print_r($arResult["USER_PROFILES"]).'</pre>';
	foreach($arResult["USER_PROFILES"] as $i => $arUserProfiles)
	{
		?>
		<div class="oneBlockStepInf">
			<table style="width: 100%">
			  <tr>
			    <td><h4><b><?=$arUserProfiles["NAME"]?></b></h4></td>
			    <td style="width: 50%; text-align: right;"> 
			    	<input class="js-order-profile" type="radio" name="PROFILE_ID" id="ID_PROFILE_ID_<?= $arUserProfiles["ID"] ?>" value="<?= $arUserProfiles["ID"];?>"
			    	<?if ($arUserProfiles["CHECKED"]=="Y") echo " checked";?> 
			    	onchange="klava.order.profileToggleView('js-profile-block-<?=$i?>')" >
					<label style="float: none; padding: inherit; width: inherit;" for="ID_PROFILE_ID_<?= $arUserProfiles["ID"] ?>"> выбрать профиль </label>
			    </td>
			  </tr>
			</table>
		</div>
		
		<div class="oneBlockStepInf js-profile-block-<?=$i?> js-profile" style="display: none;">
			<?
			foreach($arUserProfiles["USER_PROPS_VALUES"] as $arUserPropsValues)
			{
				if (strlen($arUserPropsValues["VALUE_FORMATED"]) > 0 && $arUserPropsValues['PROP_TYPE'] !== 'LOCATION')
				{
					?>
					<div class="oneLineInfStep">
						<p><?=$arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"][$arUserPropsValues["ORDER_PROPS_ID"]]["NAME"]?></p>
						<p><?=$arUserPropsValues["VALUE_FORMATED"]?></p>
						<div class="clear"></div>
					</div>
					<?
				}
			}
			?>
		</div>	

		<?
	}
	?>
	
	<div class="oneBlockStepInf">
		<table style="width: 100%">
		  <tr>
		    <td><h4><b>Новый профиль</b></h4></td>
		    <td style="width: 50%; text-align: right;">
		    	<input 
		    		type="radio" 
		    		name="PROFILE_ID" 
		    		id="ID_PROFILE_ID_0"
		    		checked="checked"
		    		class="js-order-profile"
		    		value="0" 
		    		onchange="klava.order.profileToggleView('js-profile-block-new')"
		    	>
				<label style="float: none; padding: inherit; width: inherit;" for="ID_PROFILE_ID_0"> выбрать </label>
		    </td>
		  </tr> 
		</table> 
	</div>

	<div id="js-order-new-profile" class="js-profile-block-new js-profile">
		<?PrintPropsForm($arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"], GetMessage("SALE_NEW_PROFILE_TITLE"), $arParams);?>
	</div>
			
			
<? /* ?>
	<input type="submit" name="backButton" value="&lt;&lt; <?echo GetMessage("SALE_BACK_BUTTON")?>">
	<input type="submit" name="contButton" value="<?= GetMessage("SALE_CONTINUE")?> &gt;&gt;">
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td valign="top" width="60%" align="right"><input type="submit" name="contButton" value="<?= GetMessage("SALE_CONTINUE")?> &gt;&gt;"></td>
		<td valign="top" width="5%" rowspan="3">&nbsp;</td>
		<td valign="top" width="35%" rowspan="3">
			<?echo GetMessage("STOF_CORRECT_NOTE")?><br /><br />
			<?echo GetMessage("STOF_PRIVATE_NOTES")?>
		</td>
	</tr>
	<tr>
		<td valign="top" width="60%">
			<?
			


			$bPropsPrinted = PrintPropsForm($arResult["PRINT_PROPS_FORM"]["USER_PROPS_N"], GetMessage("SALE_INFO2ORDER"), $arParams);

			if(!empty($arResult["USER_PROFILES"]))
			{
				if ($bPropsPrinted)
					echo "<br /><br />";
				?>
			
				<b><?echo GetMessage("STOF_PROFILES")?></b><br /><br />
			
				<table class="sale_order_full_table">
					<tr>
						<td colspan="2">
							<?= GetMessage("SALE_PROFILES_PROMT")?>:
							<script language="JavaScript">
							function SetContact(enabled)
							{
								if(enabled)
									document.getElementById('sof-prof-div').style.display="block";
								else
									document.getElementById('sof-prof-div').style.display="none";
							}
							</script>
						</td>
					</tr>
			
					<?
					foreach($arResult["USER_PROFILES"] as $arUserProfiles)
					{
						?>
						<tr>
							<td valign="top" width="0%">
								<input type="radio" name="PROFILE_ID" id="ID_PROFILE_ID_<?= $arUserProfiles["ID"] ?>" value="<?= $arUserProfiles["ID"];?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " checked";?> onClick="SetContact(false)">
							</td>
							<td valign="top" width="100%">
								<label for="ID_PROFILE_ID_<?= $arUserProfiles["ID"] ?>">
								<b><?=$arUserProfiles["NAME"]?></b><br />
								<table>
									<?
									foreach($arUserProfiles["USER_PROPS_VALUES"] as $arUserPropsValues)
									{
										if (strlen($arUserPropsValues["VALUE_FORMATED"]) > 0)
										{
											?>
											<tr>
												<td><?=$arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"][$arUserPropsValues["ORDER_PROPS_ID"]]["NAME"]?>:</td>
												<td><?=$arUserPropsValues["VALUE_FORMATED"]?></td>
											</tr>
											<?
										}
									}
									?>
								</table>
								</label>
							</td>
						</tr>
						<?
					}
					?>
					<tr>
						<td width="0%">
							<input type="radio" name="PROFILE_ID" id="ID_PROFILE_ID_0" value="0"<?if ($arResult["PROFILE_ID"]=="0") echo " checked";?> onClick="SetContact(true)">
						</td>
						<td width="100%"><b><label for="ID_PROFILE_ID_0"><?echo GetMessage("SALE_NEW_PROFILE")?></label></b><br /></td>
					</tr>
				</table>
				<?
			}
			else
			{
				?><input type="hidden" name="PROFILE_ID" value="0"><?
			}
			?>
			<br /><br />
			
			
			
			<div id="sof-prof-div">
			<?
			PrintPropsForm($arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"], GetMessage("SALE_NEW_PROFILE_TITLE"), $arParams);
			?>
			</div>
			<?
			if ($arResult["USER_PROFILES_TO_FILL"]=="Y")
			{
				?>
				<script language="JavaScript">
					SetContact(<?echo ($arResult["USER_PROFILES_TO_FILL_VALUE"]=="Y" || $arResult["PROFILE_ID"] == "0")?"true":"false";?>);
				</script>
				<?
			}
			
			?>
		</td>
	</tr>
	<tr>
		<td valign="top" width="60%" align="right">
			<?
			
			
			if(!($arResult["SKIP_FIRST_STEP"] == "Y"))
			{
				?>
				<input type="submit" name="backButton" value="&lt;&lt; <?echo GetMessage("SALE_BACK_BUTTON")?>">
				<?
			}
			?>
			<input type="submit" name="contButton" value="<?= GetMessage("SALE_CONTINUE")?> &gt;&gt;">
		</td>
	</tr>
</table>
<? */ ?>
				
							

