var __cl = function(s){if(!window.console) window.console={log:function(){}};console.log(s);}, agent = {};

Array.prototype.in_array = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++)	{
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
};


$(document).ready(function() {
	klava.initReady();
});


var klava = {
	initReady : function(){
		this.countSumm();
		this.sliderPrice();
		this.order.initReady();
		this.cabinet.initReady();
		this.fotoSlider.init();
		this.leftmenu.init();
		this.infodelivery.initReady();
		$(document).scroll(function(){
			klava.order.fixedPriceBlock();
		});
	},
	
	
	regpattern : {
		mail 	:  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/,
	    patNum 	: /^\d+$/
	},
	
	
	order : {
		
		initReady : function() 
		{
			this.fieldMask();
			this.getLocation();
			this.paymentVisible();  
			this.setDeliveryPrice();
			this.submitForm();
			this.hideError();
			this.isUserAutorize();
			this.visibleErrorLicenz();
			this.placeholderInit();
			this.hideLinkOrderPage();
			
			this.selectUserType(1);
		},
		
		
		setProfile : function(profileID)
		{
			if(profileID == 0)
			{
				$('#profile_new_name_in').show();
				
				$('input[data-code="USER_PHONE"]').val( '' ); 
				$('input[data-code="USER_EMAIL"]').val( '' ); 
				$('input[data-code="LAST_NAME"]').val( '' ); 
				$('input[data-code="NAME"]').val( '' ); 
				$('input[data-code="SECOND_NAME"]').val(''); 
				$('input[data-code="DELIVERY_ADRES_CITY"]').val(''); 
				$('#order-delevery-list').html('<div class="order-delivery-help">Способы доставки будут доступны после выбора города</div>');
				
				$('input[data-code="DELIVERY_ADRES_STREET"]').val('');	
				$('input[data-code="DELIVERY_ADRES_INDEX"]').val('');	
				$('input[data-code="DELIVERY_ADRES_HOME"]').val('');	
				$('input[data-code="DELIVERY_ADRES_KORPUS"]').val('');	
				$('input[data-code="DELIVERY_ADRES_FLAT"]').val('');
				
				klava.order.selectUserType(1);
				$('#order_user_type_block input[value="1"]').attr('checked', true);

				for( var i = 2; i <= 3; i++ )
				{
					if(i == 2)
						$('input[data-code="COMPANY_2_KPP"]').val('');

					$('input[data-code="COMPANY_' + i + '_NAME"]').val('');
					$('input[data-code="COMPANY_' + i + '_INN"]').val('');	
					$('input[data-code="COMPANY_' + i + '_OGRN"]').val('');	
					$('input[data-code="COMPANY_' + i + '_RASCHET_SCHET"]').val('');	
					
					$('input[data-code="COMPANY_BANK_' + i + '_BIK_BANK"]').val('');	
					$('input[data-code="COMPANY_BANK_' + i + '_KS_BANK"]').val('');
					$('input[data-code="COMPANY_BANK_' + i + '_NAME_BANK"]').val('');
					$('input[data-code="COMPANY_BANK_' + i + '_CITY_BANK"]').val('');
					
					$('input[data-code="U_ADRES_' + i + '_UCITY"]').val('');
					$('input[data-code="U_ADRES_' + i + '_USTREET"]').val('');
					$('input[data-code="U_ADRES_' + i + '_UINDEX"]').val('');
					$('input[data-code="U_ADRES_' + i + '_UHOME"]').val('');
					$('input[data-code="U_ADRES_' + i + '_UKORPUS"]').val('');
					$('input[data-code="U_ADRES_' + i + '_UFLAT"]').val('');
				}	
				
				
				$('textarea[data-code="COMMENT"]').val('');
				
				
				return;
			}	
			else
				$('#profile_new_name_in').hide();
			
			$.ajax({
    			type    : "POST",
    			url     : "/ajax/order/selected-profile/",
    			data    : {id: profileID},
    			success : function(data)
    			{
    				if(!data)
    					return;
    				
    				var jsonObject = eval( '(' + data + ')' );
    				if( jsonObject.st == 'ok' )
    				{ 
    					var params = jsonObject.result;
    					
    					$('input[data-code="USER_PHONE"]').val( params.VALUE.PHONE ).keydown(); 
    					$('input[data-code="USER_EMAIL"]').val( params.VALUE.EMAIL ).focus(); 
    					$('input[data-code="LAST_NAME"]').val( params.VALUE.LAST_NAME ).focus(); 
    					$('input[data-code="NAME"]').val( params.VALUE.NAME ).focus(); 
    					$('input[data-code="SECOND_NAME"]').val( params.VALUE.SECOND_NAME ).focus(); 
    					$('input[data-code="DELIVERY_ADRES_CITY"]').val( params.VALUE.CITY ).focus(); 

    					/* Выводим доставку по названию города */
    					$.ajax({
    						type    : "POST",
    						url     : "/ajax/order/get-city-name/",
    						data    : {name : params.VALUE.CITY},
    						success : function(data)
    						{
    							var jsonObject = eval( '(' + data + ')' );
    							if( jsonObject.st == 'ok' )
    							{
    								klava.order.getDelivery(jsonObject.result.id, function(){
    									
    									setTimeout(function(){
        		        					var delivery = $('#order-delevery-list input[value="'+ params.VALUE.DELIVERY_ID +'"]').attr('checked', true);
        		        					klava.order.selectDelivery(delivery);    					
    									}, 500);
    									
    								});
    							}
    							else
    							{
    								klava.order.getDelivery(0);
    							}
    						}
    					});
    					
    					
    					$('input[data-code="DELIVERY_ADRES_STREET"]').val( params.VALUE.STREET ).focus();	
    					$('input[data-code="DELIVERY_ADRES_INDEX"]').val( params.VALUE.INDEX ).focus();	
    					$('input[data-code="DELIVERY_ADRES_HOME"]').val( params.VALUE.HOME ).focus();	
    					$('input[data-code="DELIVERY_ADRES_KORPUS"]').val( params.VALUE.KORPUS ).focus();	
    					$('input[data-code="DELIVERY_ADRES_FLAT"]').val( params.VALUE.FLAT ).focus();	
    					
    					var userType = params.PROFILE.PERSON_TYPE_ID;
    					
    					$('#order_user_type_block input[value="'+ userType +'"]').attr('checked', true);
    					klava.order.selectUserType(userType);

    					if( userType == 3 || userType == 2 )
    					{
    						if(userType == 2)
    							$('input[data-code="COMPANY_2_KPP"]').val( params.VALUE.KPP ).focus();
    						
    						$('input[data-code="COMPANY_' + userType + '_NAME"]').val( params.VALUE.COMPANY_NAME ).focus();
    						$('input[data-code="COMPANY_' + userType + '_INN"]').val( params.VALUE.INN ).focus();	
    						$('input[data-code="COMPANY_' + userType + '_OGRN"]').val( params.VALUE.OGRN ).focus();	
    						$('input[data-code="COMPANY_' + userType + '_RASCHET_SCHET"]').val( params.VALUE.RASCHET_SCHET ).focus();	
    						
    						$('#order_company_' + userType + '_bank_field').show();			
    						$('input[data-code="COMPANY_BANK_' + userType + '_BIK_BANK"]').val( params.VALUE.BIK_BANK ).focus();	
    						$('input[data-code="COMPANY_BANK_' + userType + '_KS_BANK"]').val( params.VALUE.KS_BANK ).focus();
    						$('input[data-code="COMPANY_BANK_' + userType + '_NAME_BANK"]').val( params.VALUE.NAME_BANK ).focus();
    						$('input[data-code="COMPANY_BANK_' + userType + '_CITY_BANK"]').val( params.VALUE.CITY_BANK ).focus();
    						
    						$('input[data-code="U_ADRES_' + userType + '_UCITY"]').val( params.VALUE.UCITY ).focus();
    						$('input[data-code="U_ADRES_' + userType + '_USTREET"]').val( params.VALUE.USTREET ).focus();
    						$('input[data-code="U_ADRES_' + userType + '_UINDEX"]').val( params.VALUE.UINDEX ).focus();
    						$('input[data-code="U_ADRES_' + userType + '_UHOME"]').val( params.VALUE.UHOME ).focus();
    						$('input[data-code="U_ADRES_' + userType + '_UKORPUS"]').val( params.VALUE.UKORPUS ).focus();
    						$('input[data-code="U_ADRES_' + userType + '_UFLAT"]').val( params.VALUE.UFLAT ).focus();
    					}	
    					
    					$('textarea[data-code="COMMENT"]').val( params.VALUE.COMMENT );
    				}	 
    			}
    		});
		},
		
		
		copyDeliveryAdres : function(input, pType) 
		{
			var arCode = ['CITY', 'STREET', 'INDEX', 'HOME', 'KORPUS', 'FLAT'];
			
			var obValue = {};
			for( var i = 0; i < arCode.length; i++ )
			{
			   obValue[arCode[i]] = $('#js-order-form').find('input[data-code="DELIVERY_ADRES_' + arCode[i] + '"]').val();
			}
			
			if($(input).attr('checked'))
			{
		        for(key in obValue)
		        {
		            var inputFiled = $('#js-order-form').find('input[data-code="U_ADRES_' + pType + '_U' + key + '"]');
		            if( inputFiled.val().length > 0 )
		                inputFiled.attr("data-" + key, inputFiled.val());
		            
		            if(obValue[key].length > 0)
		            	inputFiled.val( obValue[key] ).parent().find('.placeholder').hide();
		        }
			}
			else
			{
			    for( var i = 0; i < arCode.length; i++ )
		        {
		            var inputFiled = $('#js-order-form').find('input[data-code="U_ADRES_' + pType + '_U' + arCode[i] + '"]');
		            if( inputFiled.attr("data-" + arCode[i]) !== undefined && inputFiled.attr("data-" + arCode[i]).length > 0 )
		                inputFiled.val( inputFiled.attr("data-" + arCode[i]) );
		            else
		                inputFiled.val('').parent().find('.placeholder').show();
		        }
		    };
		},
		
		
		hideLinkOrderPage : function() {
			
			if( location.pathname == "/personal/order/" )
				$('#js_footer_small_basket_cont').find('.buttonTakeOrder').hide();	
			
		},
		
		
		placeholderInit : function() {
			
			$('#js-order-form').find('input[data-placeholder="Y"]').focus(function() {
				
				$(this).parent().find('.placeholder').hide();
			
			}).blur(function() {
				if( $.trim( $(this).val() ).length  == 0 )
					$(this).parent().find('.placeholder').show();
			});
			
			
			$('#js-order-form').find('.placeholder').click(function() {
				
				$(this).parent().find('input').focus();
				
			});
			
		},
		

		isUserAutorize : function() 
		{
			$('#in_user_email').blur(function() 
			{
				if( ! klava.regpattern.mail.test( $(this).val() ) )
				{
					$('#js-field-block-USER_EMAIL')
			           .addClass('order-error-block')
			           .find('.order-error').text('Email указан не верно');
					
					return; 
				}
				
	    		$.ajax({
	    			type    : "POST",
	    			url     : "/ajax/order/isuser/",
	    			data    : { email : $(this).val() },
	    			success : function(data)
	    			{
	    				var jsonObject = eval( '(' + data + ')' );
	    				if(jsonObject.st == 'aut')
	    				{
	    					return;
	    				}	
	    				else if( jsonObject.st == 'ok' )
	    				{ 
	    					if(jsonObject.result == 'Y')
	    					{
	    						$('#order_aut_pass_block').show().find('input').attr({'data-validation' : 'Y'});	
	    					}	
	    					else
	    					{
	    						$('#order_aut_pass_block').hide().find('input').attr({'data-validation' : 'N'}).val('');
	    					}	
	    				}
	    			}
	    		});
			});
		},
		
		
		delProduct : function(productID)
		{
			if( confirm( 'Вы действительно хотите убрать этот товар?' ) )
			{
	    		$.ajax({
	    			type    : "POST",
	    			url     : "/ajax/order/delete-product/",
	    			data    : { 'id' : productID, 'sessid': bxSession.sessid},
	    			success : function(data)
	    			{
	    				var jsonObject = eval( '(' + data + ')' );
	    				if( jsonObject.st == 'ok' )
	    				{ 
	    					if( jsonObject.basket_is_empty == 'N')
	    					{
	    						location.href = '/personal/basket/';
	    					}	
	    					else
	    					{
	    						klava.loadBasketSmaill();
	    						klava.order.getPrice();
	    						klava.order.getBasket();
	    					}
	    				}	
	    				else
	    				{
	    					location.reload();
	    				}
	    			}
	    		});
			}	
		},
		 
		
		getPrice : function()
		{
			$.ajax({
    			type    : "POST",
    			url     : "/ajax/order/get-price/",
    			data    : {'sessid': bxSession.sessid},
    			success : function(data)
    			{
    				var jsonObject = eval( '(' + data + ')' );
    				if( jsonObject.st == 'ok' )
    				{ 
    					var price = jsonObject.result;
    					if( parseInt( price.DISCOUNT_PRICE ) > 0 )
    					{
    						$('#order-discont-summ').show().find('span').text( parseFloat( price.DISCOUNT_PRICE ) );
    						$('#order_discont_price').val( parseInt( price.DISCOUNT_PRICE ) );
    					}
    					else
    						$('#order-discont-summ').hide();
    					
    					$('.order-all-summ').find('span').text( parseFloat( price.allSum ) );
    					$('#order-all-summ').attr({'data-origprice' : parseFloat(price.allSum) });
    					klava.order.setDeliveryPrice();
    				}	 
    			}
    		});
		},
	
		
		getBasket : function() 
		{
			$.ajax({
				type    : "POST",
				url     : "/ajax/order/basket/",
				data    : {'sessid': bxSession.sessid},
				success : function(data)
				{
					var jsonObject = eval( '(' + data + ')' );
					if( jsonObject.st == 'ok' )
					{ 
						$('#order-basket-ajax-block').html( jsonObject.html );
					}	
					else
					{
						location.reload();
					}
				}
			});
		},
	
	
		editProductCount : function(mode, id) 
		{
			var inuputCount = $('#order_item_input_count_' + id);
			var itemBlockCount = $('#order_item_block_count_' + id);
			var __edit = function(newCount, id) 
			{
	    		$.ajax({
	    			type    : "POST",
	    			url     : "/ajax/basket/edit/",
	    			data    : { bid : id, cn  : newCount},
	    			success : function(data)
	    			{
	    				var jsonObject = eval( '(' + data + ')' );
	    				if( jsonObject.st == 'ok' ){ 
	    					klava.order.getBasket();
    						klava.order.getPrice();
    						klava.loadBasketSmaill();
	    				}	
	    			}
	    		});
			};
			
			switch(mode)
			{
				case 'plus':

					var newCount = parseInt(inuputCount.val()) + 1;
					if( newCount > inuputCount.attr('data-current') )
					{
						var boxCountVal = $('#box_new_count_val_' + id);
						itemBlockCount.addClass('box-not-count');
						inuputCount.val(newCount);
						boxCountVal.text(newCount);
					}	
					else
					{
						inuputCount.val(newCount);
						__edit(newCount, inuputCount.attr('data-bid'));
					}	
					
					break;
				
				case 'minus':
					
					if( parseInt(inuputCount.val()) == 1 )
						return; 
					
					var newCount = parseInt(inuputCount.val()) - 1;
					if( newCount > parseInt(inuputCount.attr('data-current')) )
					{
						var boxCountVal = $('#box_new_count_val_' + id);
						itemBlockCount.addClass('box-not-count');
						inuputCount.val(newCount);
						boxCountVal.text(newCount);
					}	
					else
					{
						itemBlockCount.removeClass('box-not-count');
						inuputCount.val(newCount);
						__edit(newCount, inuputCount.attr('data-bid'));
					}
					
					break;
					
				case 'set':
					
					if( parseInt(inuputCount.val()) < 1 )
					{
						inuputCount.val(1); 
						return;	
					}
					
					var newCount = parseInt(inuputCount.val());
					if( newCount > inuputCount.attr('data-current') )
					{
						var boxCountVal = $('#box_new_count_val_' + id);
						itemBlockCount.addClass('box-not-count');
						inuputCount.val(newCount);
						boxCountVal.text(newCount);
					}	
					else
					{
						__edit(newCount, inuputCount.attr('data-bid'));
					}
					
					
					break;
					
			}
		},

		
		setRealCount : function(id) 
		{
			var inputCount = $('#order_item_input_count_' + id);
			inputCount.val( inputCount.attr('data-current') );
			$('#order_item_block_count_' + id).removeClass('box-not-count');
		},
		
		
		fieldMask : function() 
		{
			$(".js-phone-mask").mask("+7 (999) 999-99-99");
			$(".js-phone-mask").each(function(index, el) 
			{
				var input = $(el);
				if( input.val().length == 0 )
					input.val('+7 ( _ _ _ ) _ _ _ - _ _ - _ _');
				
				input.blur(function() 
				{
					if( $(this).val().length == 0 )
						$(this).val('+7 ( _ _ _ ) _ _ _ - _ _ - _ _');
				}).focus(function() 
				{
					if( $(this).val() == '+7 ( _ _ _ ) _ _ _ - _ _ - _ _' )
						$(this).val('');
				});
			});
		},
	
	
		getLocation : function() 
		{
			$('.js-order-location').keyup(function() {
				
				var input = $(this);

				if(input.val().length == 0)
				{
					input.next().hide().empty();
					return;
				}
				
				
				input.prev().show();
				
				$.ajax({
					type    : "POST",
					url     : "/ajax/order/get-city/",
					data    : {s : input.val()},
					success : function(data)
					{
						input.prev().hide();
						
						var jsonObject = eval( '(' + data + ')' );
						if( jsonObject.st == 'ok' )
						{
							input.next().html(jsonObject.html).show();
						}
						else
						{
							input.next().hide();
						}
					}
				});	
				
				
			}).blur(function() {
				
				var input = $(this);
				
				if(input.val().length == 0)
				{
					input.next().hide().empty();
					return;
				}
				
				setTimeout(function() {
					
					if( input.attr('data-id').length != 0)
						return;
					
					input.prev().show();  
					
					$.ajax({
						type    : "POST",
						url     : "/ajax/order/get-city-name/",
						data    : {name : input.val()},
						success : function(data)
						{
							input.prev().hide();
							var jsonObject = eval( '(' + data + ')' );
							
							if( jsonObject.st == 'ok' )
							{
								input.attr({'data-id' : jsonObject.result.id});
								klava.order.getDelivery(jsonObject.result.id);
								input.next().hide();
							}
							else
							{
								klava.order.getDelivery(0);
								input.next().hide();
							}
						}
					});
						
					
				}, 100);
				
				
				
			});
		},
		
		
		setLocation : function(linkEl) 
		{
			var link = $(linkEl);
			link.parent().prev().val(link.attr('data-val')).attr({'data-id' : link.attr('data-id')});
			link.parent().hide();
			
			this.getDelivery(link.attr('data-id'));
		},

		
		getDelivery : function(cityID, callback) 
		{
			$.ajax({
				type    : "POST",
				url     : "/ajax/order/get-delivery/",
				data    : {city : cityID},
				success : function(data)
				{
					var jsonObject = eval( '(' + data + ')' );
					if( jsonObject.st == 'ok' )
					{
						$('#order-delevery-list').html(jsonObject.html);
						klava.order.selectDelivery( $('#order-delevery-list').find('input:checked') );
						klava.order.paymentVisible();
						klava.order.deliverytVisible();
						
						if(callback !== undefined)
							callback();
					}
				}
			});
		},
		
		
		setDeliveryPrice : function() 
		{
			var priceDelivery = parseFloat($('#order-delevery-list').find('input:checked').attr('data-price'));
			$('.order-delevery-summ').show().find('span').text( priceDelivery );

			var allSumm = parseFloat($('#order-all-summ').attr('data-origprice'));
			if( priceDelivery )
			{
				var summ = allSumm + parseFloat(priceDelivery);
				$('#order-delevery-summ, #order-delevery-summ-fix').show();
				$('#order_delivery_price').val( priceDelivery );
			}
			else
			{
				$('#order-delevery-summ, #order-delevery-summ-fix').hide();	
				var summ = allSumm;	
			}
			

			$('.order-all-summ').find('span').text( summ );
			
			$('#order_all_summ_field').val( summ ); 
			
		},

		
		selectDelivery : function(inputEl)
		{
			if( $(inputEl).attr('data-address') == 'Y' )
			{
				$('#oreder_delivery_adres_block').show().find('input[data-validation="N"]').each(function(index, el){
					$(el).attr({'data-validation' : 'Y'});
				});
			}	
			else
			{
				$('#oreder_delivery_adres_block').hide().find('input[data-validation="Y"]').each(function(index, el){
					$(el).attr({'data-validation' : 'N'});
				});;
			}	
			
			this.paymentVisible();
			this.setDeliveryPrice();
		},

		
		deliverytVisible : function() 
		{
			if($('#order_user_type_block').find('input:checked').val() == 1)
			{
				$('#order-delevery-list').find('input[data-pred="N"]').parent().show();
			}	
			else
			{
				$('#order-delevery-list').find('input[data-pred="N"]').parent().hide();
				$( $('#order-delevery-list').find('input')[0] ).attr({'checked' : true});
			}	
		},
		
		
		selectUserType : function(userTypeID)
		{
			this.getPaymentSystem(userTypeID);

			$('#order_company_params_block_2, #order_company_params_block_3').find('input[data-validation="Y"]').each(function(index, el){
				$(el).attr({'data-validation' : 'N'});
			});
			
			if( userTypeID == 1 )
			{
				$('.order_company_params_block').hide();
			}	
			else
			{
				$('.order_company_params_block').hide();
				$('#order_company_params_block_' + userTypeID).show().find('input[data-validation="N"]').each(function(index, el){
					$(el).attr({'data-validation' : 'Y'});
				});;
			}
			
			this.deliverytVisible();
		},
		
		
		getPaymentSystem : function(userTypeID) 
		{
			$.ajax({
				type    : "POST",
				url     : "/ajax/order/get-payment/",
				data    : {user_type_id : userTypeID},
				success : function(data)
				{
					if(data.length == 0) return;
					
					var jsonObject = eval( '(' + data + ')' );
					if( jsonObject.st == 'ok' )
					{
						$('#order_payment_list').html(jsonObject.html);
						klava.order.paymentVisible();
					}
				}
			});
		},
		
		
		paymentVisible : function() 
		{
			// только для физ лица
			if( $('#order_user_type_block').find('input:checked').val() != 1 )
				return;	
			
			var input = $('#order-delevery-list').find('input:checked');
			if( $(input).attr('data-pred') == 'N' )
			{
				$('#order_payment_list').find('input').each(function(index, el) {
					
					if( $(el).val() != 1 )
					{
						$(el).attr({'disabled' : true}).parent().addClass('disable');
					}
					else
					{
						$(el).attr({'disabled' : false, 'checked' : true}).parent().removeClass('disable');
					}	
				});
			}
			else
			{
				$('#order_payment_list').find('input').each(function(index, el) {
					
					if( $(el).val() == 1 )
					{
						$(el).attr({'disabled' : true}).parent().addClass('disable');
					}
					else
					{
						if(index == 1)
							$(el).attr({'checked' : true});

						$(el).attr({'disabled' : false}).parent().removeClass('disable');
					}	
				});
			}	
		},
		
		
		getBankinfo : function(input, id) {
			
			var bik = $(input).val();
			
			if( bik.length > 0 )
			{
				$.ajax({
					type    : "POST",
					url     : "/ajax/order/get-bank-info/",
					data    : {bik : $(input).val() },
					success : function(data)
					{
						$('#order_bankinfo_' + id).val(data);
					}
				});
			}	
		},
		
		
		getBankinfoExt : function(input, id) {
			
			var bik = $(input).val();
			
			if( bik.length > 0 )
			{
				$('#order_company_' + id + '_bank_bik_ajax_load').show();

				setTimeout(function() {
					
					$.ajax({
						type    : "POST",
						url     : "/ajax/order/get-bank-info/new.php",
						data    : {bik : $(input).val() },
						success : function(data)
						{
							$('#order_company_' + id + '_bank_bik_ajax_load').hide();
							
							var jsonObject = eval( '(' + data + ')' );
							if( jsonObject.st == 'ok' )
							{
								var result = jsonObject.result; 
								
								for( key in result )
								{
									$('#order_company_' + id + '_bank_field input[data-code="COMPANY_BANK_' + id + '_' + key + '"]')
									.val( result[key] )
									.parent().find('.placeholder').hide();
								}	
								
								$('#order_company_' + id + '_bank_field').show();
							}	
							else
							{
								$('#order_company_' + id + '_bank_field').fadeIn();
								$('#order_company_' + id + '_bank_bik_ajax_load_result').show();
								
								setInterval(function() {
									$('#order_company_' + id + '_bank_bik_ajax_load_result').fadeOut();
								}, 7000);
							}	
						}
					});
					
				}, 1000);
			}	
		},
		
		
		validate : function() 
		{
			var arError = [];		
		    $('#js-order-form input[data-validation="Y"]').each(function(index, el)
		    {
		    	var inputJq = $(el);
			    var code = inputJq.attr('data-code');
			  
			    
			    switch(true)
			    {
			   		case (code == 'PASSWORD'):
			   			
			   			var mail = $('#in_user_email').val();
			   			var pass = $('#in_user_pass').val(); 
			   		
			   			if( ! klava.regpattern.mail.test( mail ) )
				               return; 
			   			
			   			if( pass.length == 0 )
			   			{
			   				arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			   				return; 
			   			}
			   			
			    		var result = $.ajax({
			    			type    : 'POST',
			    			url     : '/ajax/order/auser/',
			    			data    : { email : mail, pass : pass},
			    			async   : false,
			    		}).responseText;
			    		
			    		if(result == 'N')
			    			arError.push( { code : code, text : 'Не верный Email или пароль' } );
			   			
			   			break; 

			   	   case (code == 'USER_EMAIL'):
				       
			           if( $.trim( inputJq.val() ).length == 0 )
			               arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			           else if( ! klava.regpattern.mail.test(inputJq.val()) )
			               arError.push( { code : code, text : 'Email введен с ошибками' } );
	
			           break;
			   
			       case (code == 'USER_PHONE'):
	
			           if( inputJq.val() == '+7 ( _ _ _ ) _ _ _ - _ _ - _ _' )
			               arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			       
			           break;
			       
			       case (code == 'DELIVERY_ADRES_INDEX'):
		                
			           if( inputJq.val() == '_ _ _ _ _ _ _ _ _ _' )
			           {
			        	   arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			           }
		               else if( ! klava.regpattern.patNum.test( inputJq.val()) )
		               {
		            	   arError.push( { code : code, text : 'Поле должно состоять только из цифр' } );
		               } 
		               else if( inputJq.val().length != 6 )
		               {
		            	   arError.push( { code : code, text : 'Поле должно состоять из 6 цифр' } );
		               }	    
			       
			       break;

			       case (code == 'COMPANY_2_KPP' || code == 'COMPANY_2_BIK' || code == 'COMPANY_3_BIK'): 
			           
			           if( inputJq.val() == '_ _ _ _ _ _ _ _ _' )
			           {
			        	   arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			           }
		               else if( ! klava.regpattern.patNum.test( inputJq.val()) )
		               {
		            	   arError.push( { code : code, text : 'Поле должно состоять только из цифр' } );
		               } 
		               else if( inputJq.val().length != 9 )
		               {
		            	   arError.push( { code : code, text : 'Поле должно состоять из 9 цифр' } );
		               }
			    	   
			       break;
			       
			       case (code == 'COMPANY_2_INN' ): 
			    	   
			    	   if( inputJq.val() == '_ _ _ _ _ _ _ _ _ _ _ _' )
			    	   {
			    		   arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			    	   }
			    	   else if( ! klava.regpattern.patNum.test( inputJq.val()) )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять только из цифр' } );
			    	   } 
			    	   else if( inputJq.val().length != 10 )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять из 10 цифр' } );
			    	   }
			       
			       break;
			       
			       case (code == 'COMPANY_3_INN'): 
			    	   
			    	   if( inputJq.val() == '_ _ _ _ _ _ _ _ _ _ _ _' )
			    	   {
			    		   arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			    	   }
			    	   else if( ! klava.regpattern.patNum.test( inputJq.val()) )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять только из цифр' } );
			    	   } 
			    	   else if( inputJq.val().length != 12 )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять из 12 цифр' } );
			    	   }
			       
			       break;
			       
			       case (code == 'COMPANY_2_OGRN'): 
			    	   
			    	   if( inputJq.val() == '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _' )
			    	   {
			    		   arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			    	   }
			    	   else if( ! klava.regpattern.patNum.test( inputJq.val()) )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять только из цифр' } );
			    	   } 
			    	   else if( inputJq.val().length != 13 )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять из 13 цифр' } );
			    	   }
			       
			       break;
			       
			       case (code == 'COMPANY_3_OGRN'): 
			    	   
			    	   if( inputJq.val() == '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _' )
			    	   {
			    		   arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			    	   }
			    	   else if( ! klava.regpattern.patNum.test( inputJq.val()) )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять только из цифр' } );
			    	   } 
			    	   else if( inputJq.val().length != 15 )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять из 15 цифр' } );
			    	   }
			       
			       break;
			       
			       case (code == 'COMPANY_2_RASCHET_SCHET' || code == 'COMPANY_3_RASCHET_SCHET'): 
			    	   
			    	   if( inputJq.val() == '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _' )
			    	   {
			    		   arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			    	   }
			    	   else if( ! klava.regpattern.patNum.test( inputJq.val()) )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять только из цифр' } );
			    	   } 
			    	   else if( inputJq.val().length != 20 )
			    	   {
			    		   arError.push( { code : code, text : 'Поле должно состоять из 20 цифр' } );
			    	   }
			       
			       break;
			       
			       
			       default: // стандартный валидатор текста, проверяет длину строки
			           
			           if( $.trim( inputJq.val() ).length == 0 )
			               arError.push( { code : code, text : 'Поле обязательно для заполнения' } );
			               
			           break;
			   }
			});
			
		    this.hideError(); 
		    
			if( arError.length > 0 )
			{
			   for( var j = 0; j < arError.length; j++ )
			   {
			       $('#js-field-block-' + arError[j].code)
			           .addClass('order-error-block')
			           .find('.order-error').text(arError[j].text);
			   }

			   
			   location.href = '#' + $($('.order-error-block')[0]).prev().attr('name');
			   
			   return false; 
			}
			else
				return true;
		},
		
		
		submitForm : function()
		{
			$('#js-order-form').submit(function(eventObject) 
			{
				if( $('#check_1').attr('checked') == 'checked' )
					return  ( klava.order.validate() );
				else
				{
					return false;		
				}
			});
		},
		
		
		submitFormBtn : function()
		{ 
			$('#js-order-form').submit();
		},
		
		
		visibleErrorLicenz : function() 
		{
			$('#check_1').change(function() {
				if( $(this).attr('checked') == 'checked' )
					$('#order_error_licenz_block').hide();
				else
					$('#order_error_licenz_block').show();
			});
		},

		
		hideError : function()
		{
			$('#js-order-form input[data-validation="Y"]').each(function(index, el) {
				$(el).focus( function(){
					$(this).parents('.blockInputform').removeClass('order-error-block');
				});
			});
		},
		
		
		fixedPriceBlock : function() {
			
			if( location.pathname !== '/personal/order/' )
				return;
			
			var sumBlockTop = parseInt($('#order-all-summ').offset().top) ;
			var fixedBlock = $('.boxLightFooter');
			
			if( parseInt(fixedBlock.offset().top) > sumBlockTop)
				$('#order_fixed_price_block').fadeOut();
			else
				$('#order_fixed_price_block').fadeIn();
			
		}
		
		
	},
	
	
	basket : {
		
		/* то же самое что и order кроме __edit каюсь, но пока так   */
		editProductCount : function(mode, id) 
		{
			var inuputCount = $('#order_item_input_count_' + id);
			var itemBlockCount = $('#order_item_block_count_' + id);
			var __edit = function(newCount, id) 
			{
	    		$.ajax({
	    			type    : "POST",
	    			url     : "/ajax/basket/edit/",
	    			data    : { bid : id, cn  : newCount},
	    			success : function(data)
	    			{
	    				var jsonObject = eval( '(' + data + ')' );
	    				if( jsonObject.st == 'ok' )
	    				{ 
    						klava.loadBasketSmaill();
    						klava.basket.getBasket();
	    				}	
	    			}
	    		});
			};
			
			switch(mode)
			{
				case 'plus':

					var newCount = parseInt(inuputCount.val()) + 1;
					if( newCount > inuputCount.attr('data-current') )
					{
						var boxCountVal = $('#box_new_count_val_' + id);
						itemBlockCount.addClass('box-not-count');
						inuputCount.val(newCount);
						boxCountVal.text(newCount);
					}	
					else
					{
						inuputCount.val(newCount);
						__edit(newCount, inuputCount.attr('data-bid'));
					}	
					
					break;
				
				case 'minus':
					
					if( parseInt(inuputCount.val()) == 1 )
						return; 
					
					var newCount = parseInt(inuputCount.val()) - 1;
					if( newCount > parseInt(inuputCount.attr('data-current')) )
					{
						var boxCountVal = $('#box_new_count_val_' + id);
						itemBlockCount.addClass('box-not-count');
						inuputCount.val(newCount);
						boxCountVal.text(newCount);
					}	
					else
					{
						itemBlockCount.removeClass('box-not-count');
						inuputCount.val(newCount);
						__edit(newCount, inuputCount.attr('data-bid'));
					}
					
					break;
					
				case 'set':
					
					if( parseInt(inuputCount.val()) < 1 )
					{
						inuputCount.val(1); 
						return;	
					}
					
					var newCount = parseInt(inuputCount.val());
					if( newCount > inuputCount.attr('data-current') )
					{
						var boxCountVal = $('#box_new_count_val_' + id);
						itemBlockCount.addClass('box-not-count');
						inuputCount.val(newCount);
						boxCountVal.text(newCount);
					}	
					else
					{
						__edit(newCount, inuputCount.attr('data-bid'));
					}
					
					
					break;
					
			}
		},
		
		
		delProduct : function(productID)
		{
			if( confirm( 'Вы действительно хотите убрать этот товар?' ) )
			{
	    		$.ajax({
	    			type    : "POST",
	    			url     : "/ajax/order/delete-product/",
	    			data    : { 'id' : productID, 'sessid': bxSession.sessid},
	    			success : function(data)
	    			{
	    				var jsonObject = eval( '(' + data + ')' );
	    				if( jsonObject.st == 'ok' )
	    				{ 
	    					if( jsonObject.basket_is_empty == 'N')
	    					{
	    						location.reload();
	    					}	
	    					else
	    					{
	    						klava.loadBasketSmaill();
	    						klava.basket.getBasket();
	    					}
	    				}	
	    				else
	    				{
	    					location.reload();
	    				}
	    			}
	    		});
			}	
		},
		
		getBasket : function() 
		{
			$.ajax({
				type    : "POST",
				url     : "/ajax/basket/get/",
				data    : {'sessid': bxSession.sessid},
				success : function(data)
				{
					var jsonObject = eval( '(' + data + ')' );
					if( jsonObject.st == 'ok' )
					{ 
						$('#order-basket-ajax-block').html( jsonObject.html );
					}	
					else
					{
						location.reload();
					}
				}
			});
		},
		
		setRealCount : function(id) 
		{
			var inputCount = $('#order_item_input_count_' + id);
			inputCount.val( inputCount.attr('data-current') );
			$('#order_item_block_count_' + id).removeClass('box-not-count');
		}

		
	},
	
	
	leftmenu : {
		
		init : function(){
			
			var blockMenu = $('#list-left-menu'); 
			
			blockMenu.find('li').mouseover(function(){
				
				klava.leftmenu.showSubMenu();
				blockMenu.find('.sub-left-menu').hide();
				$(this).find('a').addClass('select');
				$(this).find('.sub-left-menu').show(); 

			}).mouseleave(function(){
				$(this).find('a').removeClass('select');
			});
			
			
			$('#main-left-menu').mouseleave(function(){
				$('.sub-left-menu').hide();
				klava.leftmenu.hideSubMenu();
				
				if( $('#main-left-menu').attr('data-isindex') !== 'Y' )
					$('#main-left-menu').hide();
				
			});
			
			
			
			/* временно */
			$('.js-sub-section').mouseover(function() {
				$(this).addClass('select');
			}).mouseleave(function(){
				$(this).removeClass('select');
				$('li[data-dep="1"] > ul').hide();
			});
		},
		
		showSubMenu : function()
		{
			var overlay = $('#left-menu-overlay');
			if( overlay.css('display') == 'none' )
			{
				overlay.fadeIn();
			}
		},
		hideSubMenu : function()
		{
			var overlay = $('#left-menu-overlay');
			if( overlay.css('display') == 'block' )
			{
				if( $('.sub-left-menu-block').css('display') !== 'block' )
					overlay.fadeOut();
			}
		},
		
		/* Временная функия для старого меню */
		showSubMenu : function(subId, link) 
		{
			$('li[data-dep="1"] > ul').hide();
			$('#' + subId).show();
		}
	},
	
	
	fotoSlider : {
		
		init : function() {
			
			$('#wrap .mousetrap').live('click', function() {
				klava.fotoSlider.open($(this).parents('.imgBig').attr('data-num'));
			});
		},
		
		showWin : function(){
			$('.reveal-modal-bg, #images-slider-detail').fadeIn();
			$('.reveal-modal-bg').click(function(){
				klava.fotoSlider.close();
			});
		},
		
		close : function(){
			$('.reveal-modal-bg, #images-slider-detail').fadeOut();
		},
		
		selectFoto : function(i){
			$('#images-slider-detail .big-images img').hide();
			$('#images-slider-detail .big-images .js-foto-' + i).show();
			
			$('#images-slider-detail .nav a').removeClass('select');
			$('#images-slider-detail .nav .js-nav-foto-' + i).addClass('select');
		},
		
		
		open : function(i) {
			this.showWin();
			this.selectFoto(i);
		}
	},
	
                                                               
	cabinet : {
		
		initReady : function(){
			
			$('#user-info input[name="EMAIL"]').blur(function() {
				$(this).parent().removeClass('order-error-block');
			});
			
			klava.cabinet.allProductSelected();
			klava.cabinet.submitReturnForm();
		},
		
		
		submitRepeatOrder : function() {
			$('#js_product_list_form').submit();
		},
		
		
		submitUserInfoForm : function() {
			$('#user-info').submit(function(eventObject) 
			{
				var inputEmail = $($(eventObject.target)[0][3]);
				if( ! klava.regpattern.mail.test( inputEmail.val() ) )
				{
					inputEmail.parent().addClass('order-error-block');
					return false;
				}
				else
				{
					return true;
				}
			});
		},
		
		
		allProductSelected : function(){
			$('#order-detail-all-checked').change(function() {
				 if( $(this).attr('checked') == 'checked' )
				 {
					 $('.js-product-item').each(function(index, el) {
						 $(el).attr({'checked' : 'checked'}).next().addClass('checked');
					 });
				 }
				 else
				 {
					 $('.js-product-item').each(function(index, el) {
						 $(el).attr({'checked' : false}).next().removeClass('checked');
					 });
				 }
			});
		},
		
		
		loadOrderReturnPage : function(orderID) {
			
			
			var arInput = $('.js-product-item:checked');
			var arUrl = ['order_id=' + orderID];
			
			if(arInput.length > 0)
			{
				for( var i = 0; i < arInput.length; i++ )
				{
					arUrl.push( 'id[]=' + $(arInput[i]).val() );
				}
			}
			
			location.href = '/cabinet/order-return/form/?' + arUrl.join('&'); 
		},
		
		returnOrderAddControlUpload : function() {
			$('#return-order-upload-foto').append('<input type="file" value="" name="FILE[]" />');
		},
		
		submitReturnForm : function() {
			$('#rerurn-order').submit(function() {
				if( $.trim($('#js-return-text').val()).length == 0 )
				{
					alert('Пожалуйста напишите причину возврата товара');
					return false;
				}
				
				else return true;
			});
		},
		
		saveParamsUserProfile : function(formID, bot)
		{
			var options = { 
		        beforeSubmit:  function(){
		        	$(bot).next().addClass('edit-profile-ajax-start-load');
		        },   
		        success:       function(q, f){
		        	$(bot).next().removeClass('edit-profile-ajax-start-load').addClass('edit-profile-ajax-result-load');
		        	
		        	setTimeout(function() {
		        		$(bot).next().removeClass('edit-profile-ajax-result-load');
					}, 3000);
		        },  
		 
		        url:       '/ajax/edit-user-profile/',        
		        type:      'post',         
		        clearForm: false         
		    }; 

			$('#' + formID).ajaxSubmit(options); 
		},
		
		deleteProfile : function(profileID){
			
			if( confirm('Вы уверены что хотите удалить профиль?') )
			{
	    		$.ajax({
	    			type    : "POST",
	    			url     : "/ajax/del-user-profile/",
	    			data    : { 
	    				ses : bxSession.sessid, 
	    				id  : profileID,
	    			},
	    			success : function(data)
	    			{
	    				var jsonObject = eval( '(' + data + ')' );
	    				if( jsonObject.st == 'ok' ){ location.reload(); }	
	    				else { alert('Ошибка удаления'); }
	    			}
	    		});	
				
			}
		}
		
		
		
	},
	
	
	infodelivery : {
	
		
		initReady : function() {

			if( location.pathname == '/about/pay-delivery/' )
			{
				this.getLocation();
			}
			
		},
		
		
		getLocation : function() 
		{
			$('#js-delivery-location').keyup(function() {
				
				var input = $(this);

				if(input.val().length == 0)
				{
					input.next().hide().empty();
					return;
				}
				
				input.prev().show();
				
				$.ajax({
					type    : "POST",
					url     : "/ajax/info-delivery/get-city/",
					data    : {s : input.val()},
					success : function(data)
					{
						input.prev().hide();
						
						var jsonObject = eval( '(' + data + ')' );
						if( jsonObject.st == 'ok' )
						{
							input.next().html(jsonObject.html).show();
						}
						else
						{
							input.next().hide();
							/*
							setTimeout(function(){
								klava.infodelivery.getDelivery(0); 
							}, 7000);
							*/
						}
					}
				});	
			});
		},
		
		searchLocation : function() 
		{
			var input = $('#js-delivery-location');
			
			if(input.val().length == 0)
			{
				input.next().hide().empty();
				alert('Введите название города или населенного пункта');
				return;
			}
			
			$.ajax({
				type    : "POST",
				url     : "/ajax/info-delivery/get-city-search/",
				data    : {s : input.val()},
				success : function(data)
				{
					input.prev().hide();
					
					var jsonObject = eval( '(' + data + ')' );
					if( jsonObject.st == 'ok' )
					{
						klava.infodelivery.getDelivery(jsonObject.id); 
					}
					else
					{
						klava.infodelivery.getDelivery(0); 
					}
					
					input.next().hide();
				}
			});	
			
		},
		
		
		setLocation : function(linkEl) 
		{
			var link = $(linkEl);
			link.parent().prev().val(link.attr('data-val')).attr({'data-id' : link.attr('data-id')});
			link.parent().hide();
			
			this.getDelivery(link.attr('data-id'));
		},
		
		getDelivery : function(cityID) 
		{
			$.ajax({
				type    : "POST",
				url     : "/ajax/info-delivery/get-delivery/",
				data    : {city : cityID},
				success : function(data)
				{
					var jsonObject = eval( '(' + data + ')' );
					if( jsonObject.st == 'ok' )
					{
						$('#order-delevery-list').html(jsonObject.html);
						//klava.order.selectDelivery( $('#order-delevery-list').find('input:checked') );
						//klava.order.paymentVisible();
						//klava.order.deliverytVisible();
					}
				}
			});
		},
		
		selectDelivery : function(el) {
			$('#order-delevery-list .item-delivery').removeClass('item-delivery-select');
			
			var block = $(el); 
			
			block.addClass('item-delivery-select');
			
			$('#order-delevery-list .item-payment').removeClass('item-payment-disabled item-payment-select');
			$('#order-delevery-list .item-delivery').removeClass('item-delivery-disabled');
			if( block.attr('data-pred') == 'Y')
			{
				$('#order-delevery-list .item-payment').removeClass('item-payment-disabled');
				$('#order-delevery-list .item-payment[data-id="1"]').addClass('item-payment-disabled');
			}	
			else	
			{
				$('#order-delevery-list .item-payment').addClass('item-payment-disabled');
				$('#order-delevery-list .item-payment[data-id="1"]').removeClass('item-payment-disabled');
			}	
		},
		
		
		selectPayment : function(el) {
		
			$('#order-delevery-list .item-payment').removeClass('item-payment-select');
			
			var block = $(el);
			
			$(block).addClass('item-payment-select');

			$('#order-delevery-list .item-delivery').removeClass('item-delivery-disabled item-delivery-select');
			$('#order-delevery-list .item-payment').removeClass('item-payment-disabled');
			if(block.attr('data-id') == 1)
			{
				$('#order-delevery-list .item-delivery[data-pred="Y"]').addClass('item-delivery-disabled');
			}	
			else
			{
				$('#order-delevery-list .item-delivery[data-pred="N"]').addClass('item-delivery-disabled');
			}	
			
			
		}
		
		
	},
	
	catalog : {
		
		
		showWindowNoticAddProduct : function(id) {
			$('#notification_add_product').attr({'data-id' : id }).reveal();
		},
		
		sendNoticAddProduct : function() {
			
			var phone = $.trim($('#js_notic_click_phone').val()),
				mail = $.trim($('#js_notic_click_email').val()),
				id = $('#notification_add_product').attr('data-id');
			
			
			if( (phone.lebgth == 0 || phone == '+7 ( _ _ _ ) _ _ _ - _ _ - _ _' ) && mail.length == 0 )
				alert('Укажите один и способов уведомления');
			else
			{
				$.ajax({
					type    : "POST",
					url     : "/ajax/nitification-add-product/",
					data 	: {phone : phone, mail : mail, id : id},
					success : function(data){
						if(data == 'ok')
						{
							alert('Спасибо, как только товар появится в продаже мы вам обязательно сообщим!');
							$('#notification_add_product').triggerHandler('reveal:close');	
						}
					}
				});
				
			}	
		}
		
	},
	
	
	setCatalogListWiev : function(view) 
	{
		$.cookie('KLAVA_CATALOG_LIST_VIEW', view, {path:'/'});
	},
	
	
	addCompare : function(id)
	{
		$.ajax({
			type    : "POST",
			url     : "/ajax/compare/add/",
			data    : {id : id},
			success : function(data)
			{
				var jsonObject = eval( '(' + data + ')' );
				if( jsonObject.st !== 'ok' )
				{
					alert(jsonObject.mess);
				}	
				else
				{
					klava.addPromtAction('js_promt_compare_product', function() {
						klava.loadCompare();
						
						$('#footerLinkCompare').addClass('active');
					});
				}
			}
		});	
	},
	
	
	loadCompare : function() {
		
		$.ajax({
			type    : "POST",
			url     : "/ajax/compare/footer/",
			success : function(data){
				$('#js_promt_compare_product').prev().show().text(data);
			}
		});
	},
	

	localRedirect : function(url) {
		location.href = url;
	},	
	
	
	addBasket : function(id, cn) 
	{
		if( id == undefined )
			id = $('#js-catalog_product_element_id').val();

		if( cn == undefined )
			cn = $('#js_catalog_element_count_by').val();
		
		$.ajax({
			type    : "POST",
			url     : "/ajax/basket/add/",
			data    : { id : id, cn : cn},
			success : function(data){
				var jsonObject = eval( '(' + data + ')' );
				if( jsonObject.st == 'ok' )
				{ 
					klava.addPromtAction('js_promt_basket_product', function(){
						klava.loadBasketSmaill();
					});
				}	
				else { alert(jsonObject.mess); }	
			}
		});	
	},
	
	
	addBasketCatalogDetail : function()
	{
		var id = $('#js-catalog_product_element_id').val();
		var cn = $('#js_catalog_element_count_by').val();
		var currentCount = $('#js-catalog-element-current-count').val();

		if( parseInt(cn) > parseInt(currentCount) )
		{
			if(confirm('В наличии нет столько единиц товара, в наличии ' + currentCount + ' шт. Добавить в корзину имеющиеся кол-во?'))
				cn = currentCount;
			else
				return;
		}	
		
		$.ajax({
			type    : "POST",
			url     : "/ajax/basket/add/",
			data    : {id : id, cn : cn},
			success : function(data){
				var jsonObject = eval( '(' + data + ')' );
				if( jsonObject.st == 'ok' )
				{
					klava.addPromtAction('js_promt_basket_product', function(){
						klava.loadBasketSmaill();
					});		
				}	
				else{alert(jsonObject.mess);}	
			}
		});	
	},
	
	
	addPromtAction : function(id, calback) 
	{
		var el = $('#' + id);
		el.animate({bottom: 45}, 500, function(){
			setTimeout(function(){
				el.animate({bottom: -60}, 500, function(){
					if(calback != undefined)
						calback();
				});
			}, 1500);
		});
	},
	
	
	loadBasketSmaill : function()
	{
		$.ajax({
			type    : "POST",
			url     : "/ajax/basket/small/",
			success : function(data){
				var jsonObject = eval( '(' + data + ')' );
				$('#js_footer_small_basket_cont').html( jsonObject.html ); 
			}
		});
	},
	
	
	countSumm : function() 
	{ 
		$('#js_catalog_element_count_by').keyup(function(eventObject) 
		{
			if( eventObject.which >= 48 && eventObject.which <= 105) 
			{
				var i = parseInt( $('#js_catalog_element_count_by').val() );
				var price = parseInt( $('#js_catalog_element_count_by').attr('data-price') );
				var summ = i * price;
				$('#js_catalog_element_simm_price').text( parseInt(summ) );
			}
		});
	},
	
	
	toggleSpecBlockIndex : function(link, modeShow) 
	{
		$('#boxSpecialProductLink a').removeClass('selected');
		$(link).addClass('selected');
		$('#boxSpecialProductBlock > span').hide();
		$('#index-block-' + modeShow).show();
	},
	
	
	autorize : function()
	{
		var mail = $('#aut_mail').val();
		var pass = $('#aut_pass').val();
		var ch   = ( $('#check_login_1').attr('checked') == 'checked' ) ? 'Y' : 'N';
		 
		$('#aut-ajax-load').show();
		
		$.ajax({
			type    : "POST",
			url     : "/ajax/autorization/",
			data    : { mail : mail, pass : pass, back_url : location.pathname, ch : ch },
			success : function(data)
			{
				var jsonObject = eval( '(' + data + ')' );
				if( jsonObject.st == 'ok' )
				{
					location.href = jsonObject.url;
				}	
				else
				{
					$('#aut-ajax-load').hide();
					alert(jsonObject.mess);
				}	
			}
		});
	}, 
	
	
	setCatalogListCount : function(val)
	{
		$.cookie('PAGE_ELEMENT_COUNT_8', val, {path:'/'});
		location.reload();
	},
	
	
	setFilterSelectedList : function(code) 
	{
		$.cookie('KLAVA_SHOW_PROPERTY_' + code, 'Y', {path:'/'});
	},
	
	unsetFilterSelectedList : function(code) 
	{
		$.cookie( 'KLAVA_SHOW_PROPERTY_' + code, 'N', {path:'/'});
	},
	
	setCatalogListSort : function(order, by)
	{
		$.cookie('PAGE_ELEMENT_SORTING_8', order + '::' + by, {path:'/'});
		location.reload();
	},
	
	
	addComment : function() 
	{
		var text = $('#comment-user-text').val(),
			name = $('#comment-user-name').val(),
			email = $('#comment-user-email').val(),
			reating = $('#comment-user-reating').val();
		
		if( $.trim(text).length == 0 )
		{
			alert('Вы нечего не написали! :)');
			return;
		}
		
		$('#add-response-product').show();
		
		$.ajax({
			type    : "POST",
			url     : "/ajax/comment/add/",
			data    : { reating : reating, text : text, name : name, email : email, id : $('#js-catalog_product_element_id').val() },
			success : function(data)
			{
				var jsonObject = eval( '(' + data + ')' );
				
				if( jsonObject.st == 'ok' )
				{
					location.reload();
				}	
				else
				{
					$('#add-response-product').hide();
					alert(jsonObject.mess);
				}
			}
		});
	},
	
	
	productSpedOrder : function()
	{
		$.ajax({
			type    : "POST",
			url     : "/ajax/buyclick/",
			data    : { phone : $('#js_by_click_phone').val(), url : location.href, id : $('#js-catalog_product_element_id').val() },
			success : function(data)
			{
				var jsonObject = eval( '(' + data + ')' );
				if( jsonObject.st == 'ok' )
				{
					alert('Сообщение отправленно, ждите звонка! Удачи!');
					location.reload();
				}	
				else
				{
					alert(jsonObject.mess);
				}
			}
		});
	},
	
	
	productSpedOrderBasket : function()
	{
		$.ajax({
			type    : "POST",
			url     : "/ajax/buyclick/basket/",
			data    : {phone : $('#js_by_click_phone_bakset').val()},
			success : function(data)
			{
				var jsonObject = eval( '(' + data + ')' );
				
				if( jsonObject.st == 'ok' )
				{
					alert('Сообщение отправленно, ждите звонка! Удачи!');
					location.reload();
				}	
				else
				{
					alert(jsonObject.mess);
				}
			}
		});
	},
	
	сallbackSend : function()
	{
		$.ajax({
			type    : "POST",
			url     : "/ajax/сallback/",
			data    : {phone : $('#js_сallback_phone').val()},
			success : function(data)
			{
				var jsonObject = eval( '(' + data + ')' );
				
				if( jsonObject.st == 'ok' )
				{
					alert('Сообщение отправленно, ждите звонка! Удачи!');
					location.reload();
				}	
				else
				{
					alert(jsonObject.mess);
				}
			}
		});
	},
	
	
	sliderPrice : function() 
	{
		return;
		
	    jQuery( "#slider-price .slider" ).slider({
	        range  : true,
	        min    : 0,
	        max    : $('#js-catalog-price-filter-to').attr('data-max'),
	        step   : 50,
	        values : [ jQuery("input[name='price-from']").val(), jQuery("input[name='price-to']").val()],
	        slide  : function( event, ui ) 
	        {
	            jQuery( "#slider-price .left input" ).val(ui.values[ 0 ]);
	            jQuery( "#slider-price .right input" ).val(ui.values[ 1 ]);
	        },
	        stop : function( event, ui )
	        {
	        	var t = ( location.search.length > 0 ) ? '&' : '?';
	        	var url = location.href + t + 'price_from=' + ui.values[0] + '&price_to=' + ui.values[1];
	        	klava.localRedirect(url);
	        }
	    });
	    
	    jQuery('.ui-slider-handle').last().addClass('last');
	},
	
	
	basketAcationCount : function()	
	{		
		$('.minus').click(function () {
			
	        var $input = $(this).parents('.boxNumberProducts').find('.inputNumber input');
	        var count = parseInt($input.val()) - 1;
	        count = count < 1 ? 1 : count;
	        
	        $input.val(count);
	        $input.change();
	        
    		$.ajax({
    			type    : "POST",
    			url     : "/ajax/basket/edit/",
    			data    : { 
    				bid : $input.attr('data-bid'), 
    				cn  : $input.val(),
    			},
    			success : function(data)
    			{
    				var jsonObject = eval( '(' + data + ')' );
    				if( jsonObject.st == 'ok' ){ 
    					location.reload(); 
    				}	
    				else{ 
    					alert(jsonObject.mess); 
    				}
    			}
    		});	
	       
	        
	        return false;
		});
		
		
	    $('.plus').click(function () {
	        var $input = $(this).parents('.boxNumberProducts').find('.inputNumber input');
	        var count = parseInt($input.val()) + 1;
	        var currentCount = $input.attr('data-current');
	        
	        if(count <= currentCount)
	        {
	        	$input.val(count);
	        	$input.change();
	        	
	    		$.ajax({
	    			type    : "POST",
	    			url     : "/ajax/basket/edit/",
	    			data    : { 
	    				bid : $input.attr('data-bid'), 
	    				cn  : $input.val(),
	    			},
	    			success : function(data)
	    			{
	    				var jsonObject = eval( '(' + data + ')' );
	    				if( jsonObject.st == 'ok' ){ location.reload(); }	
	    				else { alert(jsonObject.mess); }
	    			}
	    		});	
	        }
	        else
	        {
	        	alert('В наличии нет такого кол-ва товаров!');
	        }
	        
	        return false;
	    });
	},
	
	
	addFavorites : function(id) 
	{
		var sId  = klava.getCookieVar('KLAVA_FAVOTITES_ID');
		var arId = [];
		
		if(sId != undefined)
		{
			arId = sId.split('_');
			if( ! arId.in_array(id) )
				arId.push(id);
			else
				return;
		}	
		else
		{
			arId.push(id);
		}
		
		$.cookie('KLAVA_FAVOTITES_ID', arId.join('_'), {path :'/'});
		
		klava.addPromtAction('js_promt_favorites_product', function(){
			$('#js_promt_favorites_product').prev().show().text(arId.length);
		});	
		
		$('#footerLinkFavourite').addClass('active');
	},
	
	
	delFavorites : function(id) 
	{
		console.log(klava.getCookieVar('KLAVA_FAVOTITES_ID'));
		
		var ar = klava.getCookieVar('KLAVA_FAVOTITES_ID').split('_');
		var	arResult = [];
		
		if(ar.length == 1)
		{
			$.cookie('KLAVA_FAVOTITES_ID', '', {path :'/'});
		}	
		else
		{
			for( var i = 0; i < ar.length; i++ )
			{
				console.log( ar[i] + ' ' + id);
				
				if( ar[i] != id )
					arResult.push(ar[i]);
			}
			
			$.cookie('KLAVA_FAVOTITES_ID', arResult.join('_'), {path :'/'});
		}	
		
		location.reload();
	}, 
	
	
	getCookieVar : function (name)
	{
	    var ar = document.cookie.split(';');
	    for( var i = 0; i < ar.length; i++ )
	    {
	        if( ar[i].split('=')[0].replace(' ', '') == name)
	            return ar[i].split('=')[1]; 
	    }
	}
};


$(function(){	
	$(".compareFirst .blockCompareProducts").scroll(function(){
	   $(".compareSecond .blockCompareProducts").scrollLeft($(".compareFirst .blockCompareProducts").scrollLeft());
	   $(".compareSecond .compare_left").scrollTop($(".compareFirst .blockCompareProducts").scrollTop());
	  });
	  $(".compareFirst2 .blockCompareProducts").scroll(function(){
	   $(".compareSecond .blockCompareProducts").scrollLeft($(".compareFirst2 .blockCompareProducts").scrollLeft());
	   $(".compareSecond .compare_left").scrollTop($(".compareFirst2 .blockCompareProducts").scrollTop());
	  });
});

$(document).ready(function() 
{
	var $window = $(window),
	$navigation = $(".headScroll");
  
	if( $navigation.length > 0 )
	{
		$window.scroll(function() 
		{
			if (!$navigation.hasClass("fixed") && ($window.scrollTop() > $navigation.offset().top)) {
				$navigation.addClass("fixed").data("top", $navigation.offset().top);
			}
			else if ($navigation.hasClass("fixed") && ($window.scrollTop() < $navigation.data("top"))) {
				$navigation.removeClass("fixed");
			}
		}); 
	}
});

/*
(function($) {
	$(function() {

		$('input.styled').checkbox();

		$('#add').click(function() {
			var inputs = '';
			for (i = 1; i <= 5; i++) {
				inputs += '<br /><label><input type="checkbox" name="checkbox" class="styled" /> checkbox ' + i + '</label>';
			}
			$('form').append(inputs);
			$('input.styled').checkbox();
			return false;
		})

		$('#disabled').click(function() {
			(function($) {
				$.fn.toggleDisabled = function() {
					return this.each(function() {
						this.disabled = !this.disabled;
					});
				};
			})(jQuery);
			$('input.styled').toggleDisabled().trigger('refresh');
			return false;
		});

		$('#checked').click(function() {
			(function($) {
				$.fn.toggleChecked = function() {
					return this.each(function() {
						this.checked = !this.checked;
					});
				};
			})(jQuery);
			$('input.styled').toggleChecked().trigger('refresh');
			return false;
		})

	});
	})(jQuery);
*/

//page init
/*
jQuery(function(){
    sliderWeight();
    //sliderPrice();
    sliderSize();
});
*/

function sliderWeight() 
{
    jQuery( "#slider-weight .slider" ).slider({
        range: true,
        min: 0,
        max: 36,
        step: 1,
        values: [ jQuery("input[name='weight-from']").val(), jQuery("input[name='weight-to']").val()],
        slide: function( event, ui ) {jQuery( "#slider-weight .left input" ).val(ui.values[ 0 ]);jQuery( "#slider-weight .right input" ).val(ui.values[ 1 ]);}
    });
	
    jQuery('.ui-slider-handle').last().addClass('last');
    jQuery( "#slider-weight .left input" ).val($( "#slider-weight .slider" ).slider( "values", 0 ));
    jQuery( "#slider-weight .right input" ).val($( "#slider-weight .slider" ).slider( "values", 1 ) );
}

function sliderSize() 
{
    jQuery( "#slider-size .slider" ).slider({
        range: true,
        min: 14,
        max: 70,
        step: 0.5,
        values: [ jQuery("input[name='size-from']").val(), jQuery("input[name='size-to']").val()],
        slide: function( event, ui ) {jQuery( "#slider-size .left input" ).val(ui.values[ 0 ]); jQuery( "#slider-size .right input" ).val(ui.values[ 1 ]);}
    });
    jQuery('.ui-slider-handle').last().addClass('last');
    jQuery( "#slider-size .left input" ).val($( "#slider-size .slider" ).slider( "values", 0 ));
    jQuery( "#slider-size .right input" ).val($( "#slider-size .slider" ).slider( "values", 1 ) );
}

/*
(function($) {
	$(function() {

		$('input.styledRadio').radio();

		$('#add').click(function() {
			var inputs = '';
			for (i = 1; i <= 5; i++) {
				inputs += '<br /><label><input type="radio" name="radio" class="styledRadio" /> radio ' + i + '</label>';
			}
			$('form').append(inputs);
			$('input.styledRadio').radio();
			return false;
		});

		$('#toggle').click(function() {
			(function($) {
				$.fn.toggleDisabled = function() {
					return this.each(function() {
						this.disabled = !this.disabled;
					});
				};
			})(jQuery);
			$('input.styledRadio').toggleDisabled().trigger('refresh');
			return false;
		});

	})
	})(jQuery);
*/
 
jQuery(document).ready(function() {


	$('input.styled').checkbox();

	$('#add').click(function() {
		var inputs = '';
		for (var i = 1; i <= 5; i++) {
			inputs += '<br /><label><input type="checkbox" name="checkbox" class="styled" /> checkbox ' + i + '</label>';
		}
		$('form').append(inputs);
		$('input.styled').checkbox();
		return false;
	});

	$('#disabled').click(function() {
		(function($) {
			$.fn.toggleDisabled = function() {
				return this.each(function() {
					this.disabled = !this.disabled;
				});
			};
		})(jQuery);
		$('input.styled').toggleDisabled().trigger('refresh');
		return false;
	});

	$('#checked').click(function() {
		(function($) {
			$.fn.toggleChecked = function() {
				return this.each(function() {
					this.checked = !this.checked;
				});
			};
		})(jQuery);
		$('input.styled').toggleChecked().trigger('refresh');
		return false;
	});
	
	
	
    sliderWeight();
    sliderSize();

	
	
	$('input.styledRadio').radio();

	$('#add').click(function() {
		var inputs = '';
		for (var i = 1; i <= 5; i++) {
			inputs += '<br /><label><input type="radio" name="radio" class="styledRadio" /> radio ' + i + '</label>';
		}
		$('form').append(inputs);
		$('input.styledRadio').radio();
		return false;
	});

	$('#toggle').click(function() {
		(function($) {
			$.fn.toggleDisabled = function() {
				return this.each(function() {
					this.disabled = !this.disabled;
				});
			};
		})(jQuery);
		$('input.styledRadio').toggleDisabled().trigger('refresh');
		return false;
	});
	
	cuSel({changedEl : ".lineForm select", visRows : 10, scrollArrows : false});
	jQuery('#carusel_preview').jcarousel({vertical:true, scroll:1});
	
	
	$('label.over').labelOver('over');

});


$(window).load(function() {
	$('.flexslider').flexslider({animation : "slide"});
	$("#ex-one").organicTabs();
});




//////////////////////////////////////////////////////////////////////////////////
//Cloud Zoom V1.0.2
//(c) 2010 by R Cecco. <http://www.professorcloud.com>
//MIT License
//
//Please retain this copyright header in all versions of the software
//////////////////////////////////////////////////////////////////////////////////
(function ($) {

 $(document).ready(function () {
     $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
 }); 

 function format(str) {
     for (var i = 1; i < arguments.length; i++) {
         str = str.replace('%' + (i - 1), arguments[i]);
     }
     return str;
 }

 function CloudZoom(jWin, opts) {
     var sImg = $('img', jWin);
		var	img1;
		var	img2;
     var zoomDiv = null;
		var	$mouseTrap = null;
		var	lens = null;
		var	$tint = null;
		var	softFocus = null;
		var	$ie6Fix = null;
		var	zoomImage;
     var controlTimer = 0;      
     var cw, ch;
     var destU = 0;
		var	destV = 0;
     var currV = 0;
     var currU = 0;      
     var filesLoaded = 0;
     var mx,
         my; 
     var ctx = this, zw;
     // Display an image loading message. This message gets deleted when the images have loaded and the zoom init function is called.
     // We add a small delay before the message is displayed to avoid the message flicking on then off again virtually immediately if the
     // images load really fast, e.g. from the cache. 
     //var	ctx = this;
     setTimeout(function () {
         //						 <img src="/images/loading.gif"/>
         if ($mouseTrap === null) {
             var w = jWin.width();
             //jWin.parent().append(format('<div style="width:%0px;position:absolute;top:75%;left:%1px;text-align:center" class="cloud-zoom-loading" >Loading...</div>', w / 3, (w / 2) - (w / 6))).find(':last').css('opacity', 0.5);
         }
     }, 200);


     var ie6FixRemove = function () {

         if ($ie6Fix !== null) {
             $ie6Fix.remove();
             $ie6Fix = null;
         }
     };

     // Removes cursor, tint layer, blur layer etc.
     this.removeBits = function () {
         //$mouseTrap.unbind();
         if (lens) {
             lens.remove();
             lens = null;             
         }
         if ($tint) {
             $tint.remove();
             $tint = null;
         }
         if (softFocus) {
             softFocus.remove();
             softFocus = null;
         }
         ie6FixRemove();

         $('.cloud-zoom-loading', jWin.parent()).remove();
     };


     this.destroy = function () {
         jWin.data('zoom', null);

         if ($mouseTrap) {
             $mouseTrap.unbind();
             $mouseTrap.remove();
             $mouseTrap = null;
         }
         if (zoomDiv) {
             zoomDiv.remove();
             zoomDiv = null;
         }
         //ie6FixRemove();
         this.removeBits();
         // DON'T FORGET TO REMOVE JQUERY 'DATA' VALUES
     };


     // This is called when the zoom window has faded out so it can be removed.
     this.fadedOut = function () {
         
			if (zoomDiv) {
             zoomDiv.remove();
             zoomDiv = null;
         }
			 this.removeBits();
         //ie6FixRemove();
     };

     this.controlLoop = function () {
         if (lens) {
             var x = (mx - sImg.offset().left - (cw * 0.5)) >> 0;
             var y = (my - sImg.offset().top - (ch * 0.5)) >> 0;
            
             if (x < 0) {
                 x = 0;
             }
             else if (x > (sImg.outerWidth() - cw)) {
                 x = (sImg.outerWidth() - cw);
             }
             if (y < 0) {
                 y = 0;
             }
             else if (y > (sImg.outerHeight() - ch)) {
                 y = (sImg.outerHeight() - ch);
             }

             lens.css({
                 left: x,
                 top: y
             });
             lens.css('background-position', (-x) + 'px ' + (-y) + 'px');

             destU = (((x) / sImg.outerWidth()) * zoomImage.width) >> 0;
             destV = (((y) / sImg.outerHeight()) * zoomImage.height) >> 0;
             currU += (destU - currU) / opts.smoothMove;
             currV += (destV - currV) / opts.smoothMove;

             zoomDiv.css('background-position', (-(currU >> 0) + 'px ') + (-(currV >> 0) + 'px'));              
         }
         controlTimer = setTimeout(function () {
             ctx.controlLoop();
         }, 30);
     };

     this.init2 = function (img, id) {

         filesLoaded++;
         //console.log(img.src + ' ' + id + ' ' + img.width);	
         if (id === 1) {
             zoomImage = img;
         }
         //this.images[id] = img;
         if (filesLoaded === 2) {
             this.init();
         }
     };

     /* Init function start.  */
     this.init = function () {
         // Remove loading message (if present);
         $('.cloud-zoom-loading', jWin.parent()).remove();


/* Add a box (mouseTrap) over the small image to trap mouse events.
		It has priority over zoom window to avoid issues with inner zoom.
		We need the dummy background image as IE does not trap mouse events on
		transparent parts of a div.
		*/
         $mouseTrap = jWin.parent().append(format("<div class='mousetrap' style='background-image:url(\".\");z-index:999;position:absolute;width:%0px;height:%1px;left:%2px;top:%3px;\'></div>", sImg.outerWidth(), sImg.outerHeight(), 0, 0)).find(':last');

         //////////////////////////////////////////////////////////////////////			
         /* Do as little as possible in mousemove event to prevent slowdown. */
         $mouseTrap.bind('mousemove', this, function (event) {
             // Just update the mouse position
             mx = event.pageX;
             my = event.pageY;
         });
         //////////////////////////////////////////////////////////////////////					
         $mouseTrap.bind('mouseleave', this, function (event) {
             clearTimeout(controlTimer);
             //event.data.removeBits();                
				if(lens) { lens.fadeOut(299); }
				if($tint) { $tint.fadeOut(299); }
				if(softFocus) { softFocus.fadeOut(299); }
				zoomDiv.fadeOut(300, function () {
                 ctx.fadedOut();
             });																
             return false;
         });
         //////////////////////////////////////////////////////////////////////			
         $mouseTrap.bind('mouseenter', this, function (event) {
				mx = event.pageX;
             my = event.pageY;
             zw = event.data;
             if (zoomDiv) {
                 zoomDiv.stop(true, false);
                 zoomDiv.remove();
             }

             var xPos = opts.adjustX,
                 yPos = opts.adjustY;
                          
             var siw = sImg.outerWidth();
             var sih = sImg.outerHeight();

             var w = opts.zoomWidth;
             var h = opts.zoomHeight;
             if (opts.zoomWidth == 'auto') {
                 w = siw;
             }
             if (opts.zoomHeight == 'auto') {
                 h = sih;
             }
             //$('#info').text( xPos + ' ' + yPos + ' ' + siw + ' ' + sih );
             var appendTo = jWin.parent(); // attach to the wrapper			
             switch (opts.position) {
             case 'top':
                 yPos -= h; // + opts.adjustY;
                 break;
             case 'right':
                 xPos += siw; // + opts.adjustX;					
                 break;
             case 'bottom':
                 yPos += sih; // + opts.adjustY;
                 break;
             case 'left':
                 xPos -= w; // + opts.adjustX;					
                 break;
             case 'inside':
                 w = siw;
                 h = sih;
                 break;
                 // All other values, try and find an id in the dom to attach to.
             default:
                 appendTo = $('#' + opts.position);
                 // If dom element doesn't exit, just use 'right' position as default.
                 if (!appendTo.length) {
                     appendTo = jWin;
                     xPos += siw; //+ opts.adjustX;
                     yPos += sih; // + opts.adjustY;	
                 } else {
                     w = appendTo.innerWidth();
                     h = appendTo.innerHeight();
                 }
             }

             zoomDiv = appendTo.append(format('<div id="cloud-zoom-big" class="cloud-zoom-big" style="display:none;position:absolute;left:%0px;top:%1px;width:%2px;height:%3px;background-image:url(\'%4\');z-index:99;"></div>', xPos, yPos, w, h, zoomImage.src)).find(':last');

             // Add the title from title tag.
             if (sImg.attr('title') && opts.showTitle) {
                 zoomDiv.append(format('<div class="cloud-zoom-title">%0</div>', sImg.attr('title'))).find(':last').css('opacity', opts.titleOpacity);
             }

             // Fix ie6 select elements wrong z-index bug. Placing an iFrame over the select element solves the issue...		
             if ($.browser.msie && $.browser.version < 7) {
                 $ie6Fix = $('<iframe frameborder="0" src="#"></iframe>').css({
                     position: "absolute",
                     left: xPos,
                     top: yPos,
                     zIndex: 99,
                     width: w,
                     height: h
                 }).insertBefore(zoomDiv);
             }

             zoomDiv.fadeIn(500);

             if (lens) {
                 lens.remove();
                 lens = null;
             } /* Work out size of cursor */
             cw = (sImg.outerWidth() / zoomImage.width) * zoomDiv.width();
             ch = (sImg.outerHeight() / zoomImage.height) * zoomDiv.height();

             // Attach mouse, initially invisible to prevent first frame glitch
             lens = jWin.append(format("<div class = 'cloud-zoom-lens' style='display:none;z-index:98;position:absolute;width:%0px;height:%1px;'></div>", cw, ch)).find(':last');

             $mouseTrap.css('cursor', lens.css('cursor'));

             var noTrans = false;

             // Init tint layer if needed. (Not relevant if using inside mode)			
             if (opts.tint) {
                 lens.css('background', 'url("' + sImg.attr('src') + '")');
                 $tint = jWin.append(format('<div style="display:none;position:absolute; left:0px; top:0px; width:%0px; height:%1px; background-color:%2;" />', sImg.outerWidth(), sImg.outerHeight(), opts.tint)).find(':last');
                 $tint.css('opacity', opts.tintOpacity);                    
					noTrans = true;
					$tint.fadeIn(500);

             }
             if (opts.softFocus) {
                 lens.css('background', 'url("' + sImg.attr('src') + '")');
                 softFocus = jWin.append(format('<div style="position:absolute;display:none;top:2px; left:2px; width:%0px; height:%1px;" />', sImg.outerWidth() - 2, sImg.outerHeight() - 2, opts.tint)).find(':last');
                 softFocus.css('background', 'url("' + sImg.attr('src') + '")');
                 softFocus.css('opacity', 0.5);
                 noTrans = true;
                 softFocus.fadeIn(500);
             }

             if (!noTrans) {
                 lens.css('opacity', opts.lensOpacity);										
             }
				if ( opts.position !== 'inside' ) { lens.fadeIn(500); }

             // Start processing. 
             zw.controlLoop();

             return; // Don't return false here otherwise opera will not detect change of the mouse pointer type.
         });
     };

     img1 = new Image();
     $(img1).load(function () {
         ctx.init2(this, 0);
     });
     img1.src = sImg.attr('src');

     img2 = new Image();
     $(img2).load(function () {
         ctx.init2(this, 1);
     });
     img2.src = jWin.attr('href');
 }

 $.fn.CloudZoom = function (options) {
     // IE6 background image flicker fix
     try {
         document.execCommand("BackgroundImageCache", false, true);
     } catch (e) {}
     this.each(function () {
			var	relOpts, opts;
			// Hmm...eval...slap on wrist.
			eval('var	a = {' + $(this).attr('rel') + '}');
			relOpts = a;
         if ($(this).is('.cloud-zoom')) {
             $(this).css({
                 'position': 'relative',
                 'display': 'block'
             });
             $('img', $(this)).css({
                 'display': 'block'
             });
             // Wrap an outer div around the link so we can attach things without them becoming part of the link.
             // But not if wrap already exists.
             if ($(this).parent().attr('id') != 'wrap') {
                 $(this).wrap('<div id="wrap" style="top:0px;z-index:9999;position:relative;"></div>');
             }
             opts = $.extend({}, $.fn.CloudZoom.defaults, options);
             opts = $.extend({}, opts, relOpts);
             $(this).data('zoom', new CloudZoom($(this), opts));

         } else if ($(this).is('.cloud-zoom-gallery')) {
             opts = $.extend({}, relOpts, options);
             $(this).data('relOpts', opts);
             $(this).bind('click', $(this), function (event) {
                 var data = event.data.data('relOpts');
                 // Destroy the previous zoom
                 $('#' + data.useZoom).data('zoom').destroy();
                 // Change the biglink to point to the new big image.
                 $('#' + data.useZoom).attr('href', event.data.attr('href'));
                 // Change the small image to point to the new small image.
                 $('#' + data.useZoom + ' img').attr('src', event.data.data('relOpts').smallImage);
                 // Init a new zoom with the new images.				
                 $('#' + event.data.data('relOpts').useZoom).CloudZoom();
                 return false;
             });
         }
     });
     return this;
 };

 $.fn.CloudZoom.defaults = {
     zoomWidth: 'auto',
     zoomHeight: 'auto',
     position: 'right',
     tint: false,
     tintOpacity: 0.5,
     lensOpacity: 0.5,
     softFocus: false,
     smoothMove: 3,
     showTitle: true,
     titleOpacity: 0.5,
     adjustX: 0,
     adjustY: 0
 };

})(jQuery);



