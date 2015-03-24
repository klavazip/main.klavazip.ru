<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>



<script type="text/javascript">
	$(document).ready(function() {
		klava.order.setProfile(<?=$arResult['USER_PROFILE'][0]['ID']?>);
	});
</script>	


<div class="boxMain" style="position: relative;">
	<h1>Оформление заказа</h1>
	<div class="border_1"></div>
	
	<div id="order-basket-ajax-block">
		<?
		$APPLICATION->IncludeComponent("bitrix:store.sale.basket.basket", "order", array(
			"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
			"COLUMNS_LIST" 					=> array("NAME", "PROPS", "PRICE", "QUANTITY", "DELETE", "DELAY"),
			"HIDE_COUPON" 					=> "N",
			"QUANTITY_FLOAT" 				=> "N",
			"PRICE_VAT_SHOW_VALUE" 			=> "N",
			"SET_TITLE" 					=> "N",
			"AJAX_OPTION_ADDITIONAL" 		=> ""
			),
			false
		);
		?>
	</div><br />
	
	<? 
	if(isset($arResult['ERROR_ORDER']) && strlen($arResult['ERROR_ORDER']) > 0)
	{
		?><div class="error-block"><?=$arResult['ERROR_ORDER']?></div><?
	}
	?>
	
		
		
	
	<form action="/personal/order/" method="post" id="js-order-form">
	
	
			<div class="oneBlockOrderForm">
				<h4>Профили</h4>								
				<div class="boxFormTab">																									
					<div class="blockInputform">
						<div class="boxSelect_2">
							<div class="lineForm">									
								<select id="region" name="USER_PROFILE_ID" onchange="klava.order.setProfile(this.value)" class="se920" tabindex="1">
									<option value="0">-- Создать новый профиль --</option>
									<? 
									foreach ($arResult['USER_PROFILE'] as $k => $ar_Value)
									{
										?><option <?=($k == 0) ? 'selected="selected"' : ''?> value="<?=$ar_Value['ID']?>"><?=$ar_Value['NAME']?> [<?=$ar_Value['TYPE']?>]</option><?
									}	
									?>																							
								</select>
							</div>
						</div>
					</div>
					<div class="clear"></div>	
					<? 
					$b_VisInp = (count($arResult['USER_PROFILE']) > 0);
					?>
					<div class="blockInputform" id="profile_new_name_in" style="position: relative; display: <?=($b_VisInp) ? 'none' : 'block'?>">
						<input type="text" class="in900" name="NEW_PROFILE_NAME" value="Новый профиль">
					</div>
					
					<div class="clear"></div>		
						
				</div>															
			</div>
	
	
	
	
			<h1>Контакные данные</h1>
			
			<div class="orderContent">
				<div class="oneBlockOrderForm">
					<div class="boxFormTab">		

						<label>Телефон*</label>
						<a name="error-user-phone"></a>
						<div id="js-field-block-USER_PHONE" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value="<?=$arResult['USER']['PERSONAL_PHONE']?>" 
								name="USER[PHONE]"
								data-code="USER_PHONE" 
								data-validation="Y"
								class="index js-phone-mask" 
								maxlength="18" 
								autocomplete="off"
							>
							<div class="order-error">Поле обязательно к заполнению</div>						
						</div>
						<div class="clear"></div>
						
						<label>Email*</label>
						<a name="error-user-email"></a>
						<div id="js-field-block-USER_EMAIL" style="position: relative;" class="blockInputform">
							
							<input 
								type="text" 
								value="<?=$arResult['USER']['EMAIL']?>"
								data-code="USER_EMAIL" 
								data-validation="Y"
								id="in_user_email"
								name="USER[EMAIL]" 
								class="index"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>
							<span class="placeholder" style="display: <?=(strlen($arResult['USER']['EMAIL']) > 0) ? 'none' : 'block'?>">Введите свой E-mail</span>						
						</div>

						<div class="clear"></div>
						
						<div style="display: none; position: relative;" id="order_aut_pass_block">
							
							<div class="order-mail-user-notic"> 
								Пользователь с таким Email уже существует, введите пароль для входа
							</div>
							
							<label>Пароль*</label>
							<a name="error-user-pass"></a>
							<div id="js-field-block-PASSWORD" style="position: relative;" class="blockInputform">
								<input 
									type="password" 
									value=""
									data-code="PASSWORD" 
									data-validation="N"
									id="in_user_pass"
									name="PASSWORD" 
									class="index"
									autocomplete="off"
									>
								<div class="order-error">Поле обязательно к заполнению</div>						
							</div>
						</div>	
						
						<div class="clear"></div>
						<label>Фамилия*</label>
						<a name="error-user-last-name"></a>
						<div id="js-field-block-LAST_NAME" style="position: relative;" class="blockInputform">
							<input 
								type="text"  
								value="<?=$arResult['USER']['LAST_NAME']?>" 
								data-code="LAST_NAME"
								data-validation="Y" 
								maxlength="250"
								name="USER[LAST_NAME]"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>		
							<span class="placeholder" style="display: <?=(strlen($arResult['USER']['LAST_NAME']) > 0) ? 'none' : 'block'?>">Введите свою фамилию</span>				
						</div>
						<div class="clear"></div>
						<label>Имя*</label>
						<a name="error-user-name"></a>
						<div id="js-field-block-NAME" style="position: relative;" class="blockInputform">
							<input 
								type="text"  
								value="<?=$arResult['USER']['NAME']?>" 
								data-code="NAME"
								data-validation="Y" 
								maxlength="250"
								name="USER[NAME]"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>
							<span class="placeholder" style="display: <?=(strlen($arResult['USER']['NAME']) > 0) ? 'none' : 'block'?>">Введите свое имя</span>						
						</div>
						<div class="clear"></div>
						<label>Отчество*</label>
						<a name="error-user-second-name"></a>
						<div id="js-field-block-SECOND_NAME" style="position: relative;" class="blockInputform">							
							<input 
								type="text"  
								value="<?=$arResult['USER']['SECOND_NAME']?>" 
								data-code="SECOND_NAME"
								data-validation="Y" 
								maxlength="250"
								name="USER[SECOND_NAME]"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder" style="display: <?=(strlen($arResult['USER']['SECOND_NAME']) > 0) ? 'none' : 'block'?>">Введите свое отчество</span>					
						</div>
					</div>		
				</div>
			</div>
			
			<br /><br />
			
			<h1>Доставка</h1>
			
			
			<div class="orderContent">
				<div class="oneBlockOrderForm">
					<div class="boxFormTab">		
						
						<label>Введите название города или населенного пункта*</label>
						<a name="error-delivery-city"></a>
						<div id="js-field-block-DELIVERY_ADRES_CITY" style="position: relative;" class="blockInputform">
							<span style="display: none;" class="ajax-load"></span>
							<input 
								type="text" 
								value="" 
								data-code="DELIVERY_ADRES_CITY" 
								data-validation="Y"
								name="DELIVERY_ADRES[CITY]" 
								data-id="" 
								class="js-order-location" 
								autocomplete="off"
								data-placeholder="Y"
								>
							<div style="display: none;" class="order-selected-block"></div>
							<div class="order-error">Поле обязательно к заполнению</div>
							<span class="placeholder">Введите город доставки</span>
						</div>
						<div class="clear"></div>
						
						<label>Способ доставки*</label>
						<div style="position: relative;" class="blockInputform">
							<div class="delevery-list" id="order-delevery-list">
								<div class="order-delivery-help">Способы доставки будут доступны после выбора города</div>
							</div>
						</div>
						<div class="clear"></div>
						
						<!-- адрес доставки если выбрали НЕ самовывоз -->
						<div id="oreder_delivery_adres_block" style="display: none;"> 
						
							<label>Улица*</label>
							<a name="error-adres-street"></a>
							<div id="js-field-block-DELIVERY_ADRES_STREET" style="position: relative;" class="blockInputform">
								<input 
									type="text" 
									value="" 
									autocomplete="off"
									data-validation="Y"
									data-code="DELIVERY_ADRES_STREET"
									name="DELIVERY_ADRES[STREET]"
									data-placeholder="Y"
									>
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите улицу</span>					
							</div>
							<div class="clear"></div>
							
			
							<label>Индекс*</label>
							<a name="error-adres-index"></a>
							<div id="js-field-block-DELIVERY_ADRES_INDEX" style="position: relative;" class="blockInputform">
								
								<input 
									type="text"
									value=""
									name="DELIVERY_ADRES[INDEX]"
									class="index" 
									maxlength="6"
									data-validation="Y"
									data-code="DELIVERY_ADRES_INDEX"
									data-placeholder="Y"
									>
								<div class="order-error">Поле обязательно к заполнению</div>
								<span class="placeholder">Введите почтовый индекс</span>						
							</div>
							<div class="clear"></div>
							
							
							<label></label>
							<a name="error-adres-home"></a>
							<div id="js-field-block-DELIVERY_ADRES_HOME" style="position: relative;" class="blockInputform">
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Дом*</span>
									
									<input 
										data-validation="Y" 
										data-code="DELIVERY_ADRES_HOME"
										type="text" 
										value="" 
										name="DELIVERY_ADRES[HOME]" 
										class="numberAddress" 
										maxlength="3"
										data-placeholder="Y"
										>
										<span class="placeholder" style="margin-left: 35px">№ дома</span>	
								</span>
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Корпус</span> 
									
									<input 
										type="text" 
										value="" 
										name="DELIVERY_ADRES[KORPUS]" 
										class="numberAddress" 
										maxlength="3"
										data-placeholder="Y"
										data-code="DELIVERY_ADRES_KORPUS"
										>
										<span class="placeholder" style="margin-left: 161px">№ корп.</span>
								</span>
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Квартира</span> 
									
									<input 
										type="text" 
										value="" 
										name="DELIVERY_ADRES[FLAT]" 
										class="numberAddress" 
										maxlength="3"
										data-placeholder="Y"
										data-code="DELIVERY_ADRES_FLAT"
										>
										<span class="placeholder" style="margin-left: 304px">№ квар.</span>
								</span>
								
								<div class="order-error">Поле обязательно к заполнению</div>						
							</div>
							<div class="clear"></div>
						</div>	
					</div>		
				</div>
				
				<br /><br />
				
			</div>
			
			<br /><br />
			
			<h1>Оплата</h1>
			
			
			<div class="orderContent">
				<div class="oneBlockOrderForm">
				
					<div class="boxFormTab">		
						<label>Тип плательщика*</label> 
						<div style="position: relative;" class="blockInputform" id="order_user_type_block" >
							<label><input type="radio" autocomplete="off" checked="checked" onchange="klava.order.selectUserType(1)" name="USER_TYPE" value="1"> Физическое лицо</label>
							<label><input type="radio" autocomplete="off" onchange="klava.order.selectUserType(2)" name="USER_TYPE" value="2"> Юридическое лицо</label>
							<label><input type="radio" autocomplete="off" onchange="klava.order.selectUserType(3)" name="USER_TYPE" value="3"> ИП</label>
						</div>
					</div>
					<div class="clear"></div>	 
	
	
	
						
					<!-- Дынные лица если это ООО --> 
					<div class="boxFormTab order_company_params_block" style="display: none;" id="order_company_params_block_2">	
					
						<label>Наименование компании*</label>
						<a name="error-com3-inn"></a>
						<div id="js-field-block-COMPANY_2_NAME" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value=""  
								maxlength="250"
								name="COMPANY_2[COMPANY_NAME]"
								autocomplete="off"	
								data-validation="N"	
								data-code="COMPANY_2_NAME"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder">Введите наименование компании</span>						
						</div>
		
						<div class="clear"></div>
					
					
						<label>КПП*</label>
						<a name="error-com2-kpp"></a>
						<div id="js-field-block-COMPANY_2_KPP" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value="" 
								maxlength="9"
								name="COMPANY_2[KPP]"	
								autocomplete="off"
								data-validation="N"
								data-code="COMPANY_2_KPP"
								data-placeholder="Y" 
								>
							<div class="order-error">Поле обязательно к заполнению</div>
							<span class="placeholder">Введите КПП</span>						
						</div>
		
						<div class="clear"></div>	
						
						<label>ИНН*</label>
						<a name="error-com2-inn"></a>
						<div id="js-field-block-COMPANY_2_INN" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value=""  
								maxlength="12"
								name="COMPANY_2[INN]"
								autocomplete="off"
								data-validation="N"
								data-code="COMPANY_2_INN"
								data-placeholder="Y" 
								>
							<div class="order-error">Поле обязательно к заполнению</div>
							<span class="placeholder">Введите ИНН</span>							
						</div>
		
						<div class="clear"></div>	
						
						<label>ОГРН*</label>
						<a name="error-com2-ogrn"></a>							
						<div id="js-field-block-COMPANY_2_OGRN" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value="" 
								maxlength="13"
								name="COMPANY_2[OGRN]"	
								autocomplete="off"
								data-validation="N"
								data-code="COMPANY_2_OGRN"
								data-placeholder="Y" 
								>
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder">Введите ОГРН</span>							
												
						</div>
		
						<div class="clear"></div>	
						
						<label>Расчетный счет*</label>
						<a name="error-com2-raschet"></a>							
						<div id="js-field-block-COMPANY_2_RASCHET_SCHET" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value="" 
								maxlength="20"
								name="COMPANY_2[RASCHET_SCHET]"	
								autocomplete="off"	
								data-validation="N"
								data-code="COMPANY_2_RASCHET_SCHET"
								data-placeholder="Y" 
								>
							
							<div class="order-error">Поле обязательно к заполнению</div>
							<span class="placeholder">Введите расчетный счет</span>									
						</div>
						<div class="clear"></div>	
		
						<label>БИК банка*</label>
						<a name="error-com2-bik"></a>
						<div id="js-field-block-COMPANY_2_BIK_BANK" style="position: relative;" class="blockInputform">
							<span class="ajax-load-bank" id="order_company_2_bank_bik_ajax_load" style="display: none;">Поиск реквизитов банка</span>
							<span class="ajax-load-bank-result" id="order_company_2_bank_bik_ajax_load_result" style="display: none;">Данные не найдены, укажите вручную</span>
							<input 
								type="text" 
								value="" 
								onblur="klava.order.getBankinfoExt(this, 2)" 
								maxlength="9"
								autocomplete="off"
								data-validation="N"
								name="COMPANY_BANK_2[BIK_BANK]"
								data-code="COMPANY_BANK_2_BIK_BANK"
								data-placeholder="Y" 
								>
							
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder">Введите БИК банка</span>								
						</div>
						<div class="clear"></div>	
						
						<? /*?>
						<label></label>			
						<div class="blockInputform commentStep">
							<textarea 
								placeholder="После ввода БИК банка данные должны загрузится автоматически. Если это не произошло то впишите данные банка самостоятельно в это поле." 
								rows="1" 
								autocomplete="off"  
								name="COMPANY_2[BANK]" 
								cols="1" 
								id="order_bankinfo_2"></textarea>
						</div>
						<div class="clear"></div>	
						<?*/?>
						

						
						<div id="order_company_2_bank_field" style="display: none;">
						
							<label>Корр. счет банка*</label>
							<a name="error-com2-bik"></a>
							<div id="js-field-block-COMPANY_2_KS_BANK" style="position: relative;" class="blockInputform">
								<input 
									type="text"  
									value="" 
									maxlength="20"
									autocomplete="off"
									data-validation="N"
									data-code="COMPANY_BANK_2_KS_BANK"
									name="COMPANY_BANK_2[KS_BANK]"
									data-placeholder="Y" 
									>
								
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите Корр. счет банка</span>								
							</div>
							<div class="clear"></div>
						
							<label>Наименование банка*</label>
							<a name="error-com2-bik"></a>
							<div id="js-field-block-COMPANY_BANK_2_BANK_NAME" style="position: relative;" class="blockInputform">
								<input 
									type="text" 
									value="" 
									maxlength="20"
									autocomplete="off"
									data-validation="N"
									name="COMPANY_BANK_2[NAME_BANK]"
									data-code="COMPANY_BANK_2_NAME_BANK"
									data-placeholder="Y" 
									>
								
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите наименование банка</span>								
							</div>
							<div class="clear"></div>
							
							<label>Город банка*</label>
							<a name="error-com2-bik"></a>
							<div id="js-field-block-COMPANY_BANK_2_CITY_BANK" style="position: relative;" class="blockInputform">
								<input 
									type="text" 
									value="" 
									maxlength="20"
									autocomplete="off"
									data-validation="N"
									name="COMPANY_BANK_2[CITY_BANK]"
									data-code="COMPANY_BANK_2_CITY_BANK"
									data-placeholder="Y" 
									>
								
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите Город банка</span>								
							</div>
							<div class="clear"></div>
						
						</div>
						
						
						
						
						
						
						
						
						<!-- Адрес для юрика -->  
							<br /><br />
							<label></label>
							<a name="error-adres-street"></a>
							<div style="position: relative;" class="blockInputform">Юридический адрес</div>
							<div class="clear"></div>
							
							<label></label>
							<div style="position: relative;" class="blockInputform">
								<div class="lineCheckDoc">
									<input onchange="klava.order.copyDeliveryAdres(this, 2)" type="checkbox" class="styled" id="copy_delivery_data_2" />
									<label for="copy_delivery_data_2">Скопировать данные доставки </label> 								
									<div class="clear"></div>
								</div>
							</div>
							<div class="clear"></div>
						
							<label>Город*</label>
							<a name="error-adres-street"></a>
							<div id="js-field-block-U_ADRES_2_UCITY" style="position: relative;" class="blockInputform">
								<input 
									data-code="U_ADRES_2_UCITY"
									name="U_ADRES_2[UCITY]"
									data-validation="Y"
									data-placeholder="Y"
									type="text" value="" autocomplete="off"
									>
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите Город</span>					
							</div>
							<div class="clear"></div>
						
							<label>Улица*</label>
							<a name="error-adres-street"></a>
							<div id="js-field-block-U_ADRES_2_USTREET" style="position: relative;" class="blockInputform">
								<input 
									data-code="U_ADRES_2_USTREET"
									name="U_ADRES_2[USTREET]"
									data-validation="Y"
									data-placeholder="Y"
									type="text" value="" autocomplete="off"
									>
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите улицу</span>					
							</div>
							<div class="clear"></div>
							
			
							<label>Индекс*</label>
							<a name="error-adres-index"></a>
							<div id="js-field-block-U_ADRES_2_UINDEX" style="position: relative;" class="blockInputform">
								<input 
									data-code="U_ADRES_2_UINDEX"
									name="U_ADRES_2[UINDEX]"
									data-validation="Y"
									data-placeholder="Y"
									type="text" value="" class="index" maxlength="6"
									>
								<div class="order-error">Поле обязательно к заполнению</div>
								<span class="placeholder">Введите почтовый индекс</span>						
							</div>
							<div class="clear"></div>
							
							
							<label></label>
							<a name="error-adres-home"></a>
							<div id="js-field-block-U_ADRES_2_UHOME" style="position: relative;" class="blockInputform">
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Дом*</span>
									<input 
										name="U_ADRES_2[UHOME]" 
										data-code="U_ADRES_2_UHOME"
										data-validation="Y" 
										data-placeholder="Y"
										type="text" value="" class="numberAddress" maxlength="3"
										>
										<span class="placeholder" style="margin-left: 35px">№ дома</span>	
								</span>
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Корпус</span> 
									<input 
										name="U_ADRES_2[UKORPUS]" 
										data-code="U_ADRES_2_UKORPUS"
										data-placeholder="Y"
										type="text" value="" class="numberAddress" maxlength="3"
										>
										<span class="placeholder" style="margin-left: 161px">№ корп.</span>
								</span>
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Квартира</span> 
									<input 
										name="U_ADRES_2[UFLAT]" 
										data-code="U_ADRES_2_UFLAT"
										data-placeholder="Y"
										type="text" value="" class="numberAddress" maxlength="3"
										>
										<span class="placeholder" style="margin-left: 304px">№ квар.</span>
								</span>
								
								<div class="order-error">Поле обязательно к заполнению</div>						
							</div>
							
						
							<div class="clear"></div>
						
							<br /><br />
						
						<!-- end -->  
						
						
					</div>	
					
					
					
					
					<!-- Дынные лица если это ИП  --> 
					<div class="boxFormTab order_company_params_block" style="display: none;" id="order_company_params_block_3">	
					 
					 

						<label>Наименование компании*</label>
						<a name="error-com3-inn"></a>
						<div id="js-field-block-COMPANY_3_NAME" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value=""  
								maxlength="250"
								name="COMPANY_3[COMPANY_NAME]"
								autocomplete="off"	
								data-validation="N"	
								data-code="COMPANY_3_NAME"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder">Введите наименование ИП</span>						
						</div>
		
						<div class="clear"></div>
					 
					 
						<label>ИНН*</label>
						<a name="error-com3-inn"></a>
						<div id="js-field-block-COMPANY_3_INN" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value=""  
								maxlength="12"
								name="COMPANY_3[INN]"
								autocomplete="off"	
								data-validation="N"	
								data-code="COMPANY_3_INN"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder">Введите ИНН</span>						
						</div>
		
						<div class="clear"></div>	
						
						<label>ОГРН*</label>
						<a name="error-com3-ogrn"></a>							
						<div id="js-field-block-COMPANY_3_OGRN" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value="" 
								maxlength="15"
								name="COMPANY_3[OGRN]"
								autocomplete="off"
								data-validation="N"	
								data-code="COMPANY_3_OGRN"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>
							<span class="placeholder">Введите ОГРН</span>							
						</div>
		
						<div class="clear"></div>	
						
						<label>Расчетный счет*</label>
						<a name="error-com3-raschet"></a>							
						<div id="js-field-block-COMPANY_3_RASCHET_SCHET" style="position: relative;" class="blockInputform">
							<input 
								type="text" 
								value="" 
								maxlength="20"
								name="COMPANY_3[RASCHET_SCHET]"
								autocomplete="off"
								data-validation="N"	
								data-code="COMPANY_3_RASCHET_SCHET"
								data-placeholder="Y"
								>
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder">Введите расчетный счет</span>						
						</div>
						<div class="clear"></div>	
		
						<label>БИК банка*</label>
						<a name="error-com3-bik"></a>
						<div id="js-field-block-COMPANY_BANK_3_BIK_BANK" style="position: relative;" class="blockInputform">
							<span class="ajax-load-bank" id="order_company_3_bank_bik_ajax_load" style="display: none;">Поиск реквизитов банка</span>
							<span class="ajax-load-bank-result" id="order_company_3_bank_bik_ajax_load_result" style="display: none;">Данные не найдены, укажите вручную</span>
							<input 
								type="text"  
								value="" 
								onblur="klava.order.getBankinfoExt(this, 3)" 
								maxlength="9"
								autocomplete="off"
								data-validation="N"	
								name="COMPANY_BANK_3[BIK_BANK]"
								data-code="COMPANY_BANK_3_BIK_BANK"
								
								data-placeholder="Y"
								>
								<span class="desc"></span>
							<div class="order-error">Поле обязательно к заполнению</div>	
							<span class="placeholder">Введите БИК банка</span>						
						</div>
						<div class="clear"></div>	
								
						<? /*?>		
									
						<label></label>			
						<div class="commentStep">
							<textarea
								placeholder="После ввода БИК банка данные должны загрузится автоматически. Если это не произошло то впишите данные банка самостоятельно в это поле." 
								rows="1" 
								autocomplete="off" 
								name="COMPANY_3[BANK]" 
								cols="1" 
								id="order_bankinfo_3" 
								></textarea>
						</div>
						<div class="clear"></div>	
						<? */ ?>

						
						<div id="order_company_3_bank_field" style="display: none;">
						
							<label>Корр. счет банка*</label>
							<a name="error-com2-bik"></a>
							<div id="js-field-block-COMPANY_3_KS_BANK" style="position: relative;" class="blockInputform">
								<input 
									type="text" 
									value="" 
									maxlength="20"
									autocomplete="off"
									data-validation="N"
									data-code="COMPANY_BANK_3_KS_BANK"
									name="COMPANY_BANK_3[KS_BANK]"
									data-placeholder="Y" 
									>
								
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите Корр. счет банка</span>								
							</div>
							<div class="clear"></div>
						
							<label>Наименование банка*</label>
							<a name="error-com2-bik"></a>
							<div id="js-field-block-COMPANY_BANK_3_BANK_NAME" style="position: relative;" class="blockInputform">
								<input 
									type="text" 
									value="" 
									maxlength="20"
									autocomplete="off"
									data-validation="N"
									name="COMPANY_BANK_3[NAME_BANK]"
									data-code="COMPANY_BANK_3_NAME_BANK"
									data-placeholder="Y" 
									>
								
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите наименование банка</span>								
							</div>
							<div class="clear"></div>
							
							<label>Город банка*</label>
							<a name="error-com2-bik"></a>
							<div id="js-field-block-COMPANY_BANK_3_CITY_BANK" style="position: relative;" class="blockInputform">
								<input 
									type="text" 
									value="" 
									maxlength="20"
									autocomplete="off"
									data-validation="N"
									name="COMPANY_BANK_3[CITY_BANK]"
									data-code="COMPANY_BANK_3_CITY_BANK"
									data-placeholder="Y" 
									>
								
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите Город банка</span>								
							</div>
							<div class="clear"></div>
						
						</div>
						
						
												
						<!-- Адрес для ИП -->  
							<br /><br />
							<label></label>
							<a name="error-adres-street"></a>
							<div style="position: relative;" class="blockInputform">Юридический адрес</div>
							<div class="clear"></div>
							
							<label></label>
							<div style="position: relative;" class="blockInputform">
								<div class="lineCheckDoc">
									<input onchange="klava.order.copyDeliveryAdres(this, 3)" type="checkbox" class="styled" id="copy_delivery_data_3" />
									<label for="copy_delivery_data_3">Скопировать данные доставки </label> 								
									<div class="clear"></div>
								</div>
							</div>
							<div class="clear"></div>
						
							<label>Город*</label>
							<a name="error-adres-street"></a>
							<div id="js-field-block-U_ADRES_3_UCITY" style="position: relative;" class="blockInputform">
								<input 
									data-code="U_ADRES_3_UCITY"
									name="U_ADRES_3[UCITY]"
									data-validation="Y"
									data-placeholder="Y"
									type="text" value="" autocomplete="off"
									>
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите Город</span>					
							</div>
							<div class="clear"></div>
						
							<label>Улица*</label>
							<a name="error-adres-street"></a>
							<div id="js-field-block-U_ADRES_3_USTREET" style="position: relative;" class="blockInputform">
								<input 
									data-code="U_ADRES_3_USTREET"
									name="U_ADRES_3[USTREET]"
									data-validation="Y"
									data-placeholder="Y"
									type="text" value="" autocomplete="off"
									>
								<div class="order-error">Поле обязательно к заполнению</div>	
								<span class="placeholder">Введите улицу</span>					
							</div>
							<div class="clear"></div>
							
			
							<label>Индекс*</label>
							<a name="error-adres-index"></a>
							<div id="js-field-block-U_ADRES_3_UINDEX" style="position: relative;" class="blockInputform">
								<input 
									data-code="U_ADRES_3_UINDEX"
									name="U_ADRES_3[UINDEX]"
									data-validation="Y"
									data-placeholder="Y"
									type="text" value="" class="index" maxlength="6"
									>
								<div class="order-error">Поле обязательно к заполнению</div>
								<span class="placeholder">Введите почтовый индекс</span>						
							</div>
							<div class="clear"></div>
							
							
							<label></label>
							<a name="error-adres-home"></a>
							<div id="js-field-block-U_ADRES_3_UHOME" style="position: relative;" class="blockInputform">
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Дом*</span>
									<input 
										name="U_ADRES_3[UHOME]" 
										data-code="U_ADRES_3_UHOME"
										data-validation="Y" 
										data-placeholder="Y"
										type="text" value="" class="numberAddress" maxlength="3"
										>
										<span class="placeholder" style="margin-left: 35px">№ дома</span>	
								</span>
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Корпус</span> 
									<input 
										name="U_ADRES_3[UKORPUS]" 
										data-code="U_ADRES_3_UKORPUS"
										data-placeholder="Y"
										type="text" value="" class="numberAddress" maxlength="3"
										>
										<span class="placeholder" style="margin-left: 161px">№ корп.</span>
								</span>
							
								<span>
									<span style="color: #1A1A1A; font: 14px/18px Arial;">Квартира</span> 
									<input 
										name="U_ADRES_3[UFLAT]" 
										data-code="U_ADRES_3_UFLAT"
										data-placeholder="Y"
										type="text" value="" class="numberAddress" maxlength="3"
										>
										<span class="placeholder" style="margin-left: 304px">№ квар.</span>
								</span>
								
								<div class="order-error">Поле обязательно к заполнению</div>						
							</div>
							
						
							<div class="clear"></div>
						
							<br /><br />
						
						<!-- end -->  
						
						
								
					</div>	
						
					<div class="boxFormTab">			
						<label>Способ оплаты*</label>
						<div style="position: relative;" class="blockInputform">
							<div class="delevery-list" id="order_payment_list">
							
								<? 
								foreach ($arResult['PAYMENT'] as $i => $ar_Value)
								{
									$ar_Desc = explode('|', $ar_Value['DESCRIPTION']);
									?>
									<label>
										<input  <?=($i == 0) ? 'checked="checked"' : ''?> type="radio" name="PAYMENT" value="<?=$ar_Value['ID']?>"> 
										<span class="n"><img src="<?=$ar_Desc[0]?>" alt="" /> <?=$ar_Value['NAME']?> <?=(strlen($ar_Desc[1]) > 0) ? "<i>(коммисия $ar_Desc[1])</i>" : ""?></span>
									</label>
									<?
								}	
								?>
							</div>
							<div class="order-error">Поле обязательно к заполнению</div>						
						</div>
					</div>		
					
					<div class="clear"></div>
		
					<div class="boxFormTab">			
						<label></label>
						<div style="position: relative;" class="blockInputform">
							
							<div class="order-delevery-summ" style="display: none;" id="order-delevery-summ">Стоимость доставки: <b><span></span> <?=KlavaMain::RUB?></b></div> 
							
							<?$b_Discont = (intval($_SESSION['KLAVA_ORDER_PRICE']['DISCOUNT_PRICE']) > 0);?>
							<div <?=(!$b_Discont) ? 'style="display: none;"' : ''?> id="order-discont-summ">Скидка: <b> <span><?=intval($_SESSION['KLAVA_ORDER_PRICE']['DISCOUNT_PRICE'])?></span> <?=KlavaMain::RUB?></b> </div> 
							<div style="margin-top: 10px"
								id="order-all-summ"
								class="order-all-summ"
								data-origprice="<?=intval($_SESSION['KLAVA_ORDER_PRICE']['allSum'])?>"
								>
								Сумма к оплате: <b><span><?=intval($_SESSION['KLAVA_ORDER_PRICE']['allSum'])?></span> <?=KlavaMain::RUB?></b> 
							</div> 
							<br />
							
							<input type="hidden" value="" name="ALL_SUMM" id="order_all_summ_field" />
							<input type="hidden" value="" name="DELIVERY_SUMM" id="order_delivery_price" />
							<input type="hidden" value="<?=intval($_SESSION['KLAVA_ORDER_PRICE']['DISCOUNT_PRICE'])?>" name="DISCONT_SUMM" id="order_discont_price" />
							
						</div>
						<div class="clear"></div>
					</div>		
				</div>	
			</div>
		
			
			<div class="oneBlockStepInf last">
				<div class="oneLineInfStep">
					<p>Комментарии к заказу</p>
					<div class="commentStep">
						<textarea rows="1" cols="1" name="DESCRIPTION" data-code="COMMENT"></textarea>
						<div class="lineCheckDoc">
							<input checked="checked" type="checkbox" class="styled check" name="check_1" />
							<label for="check_1">Я согласен с </label> <a target="_blank" class="order-about-usloviya-link" href="/about/usloviya/">условиями заказа <i></i></a>								
							<div class="clear"></div>
						</div>
						<div style="padding-left: 0; display: none;" class="error-block" id="order_error_licenz_block">
							Мы не можем принять ваш заказ, если Вы не согласны с условиями.
						</div>
					</div>
					<div class="clear"></div>
				</div>	
			</div>
			
			<div class="orderContent" style="top: 0; border-radius:0;">
				<div class="bottomOrderButton">
					<div style="text-align:center;">
						<input type="submit" value="Подтвердить заказ" name="backButton" class="buttonBuy buttonAuto" style="display: inline-block; float: none !important;">	
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<?=bitrix_sessid_post()?>
	</form>
	
	<div class="order-fixed-price-block" id="order_fixed_price_block">
	    <div class="order-delevery-summ" id="order-delevery-summ-fix">Стоимость доставки: <b><span>600</span> <?=KlavaMain::RUB?> </b></div>
		<div class="order-all-summ">Сумма к оплате: <b><span>25500</span> <?=KlavaMain::RUB?></b></div>		
		
		<input type="submit" value="Подтвердить заказ" onclick="klava.order.submitFormBtn()" name="backButton" class="buttonBuy buttonAuto" style="display: inline-block; float: none !important; left: 530px;position: absolute;top: 19px;">
			
	</div>

</div>
