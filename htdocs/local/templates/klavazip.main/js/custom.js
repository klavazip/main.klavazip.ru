
jQuery(function() {

	$('.nameHidden').click(function() {
		$(this).parent().parent().find('.subnavNameOpen').slideToggle();
		return false;
	});
	$('.nameHidden').click(function() {
		$('.townNavOpen').slideUp();
		return false;
	});
	$(document).bind("click", function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass(".nameHidden"))
			$(".subnavNameOpen").hide();
	});
	$('.nameTown').click(function() {
		$(this).parent().parent().find('.townNavOpen').slideToggle();
		return false;
	});
	$('.nameTown').click(function() {
		$('.subnavNameOpen').slideUp();
		return false;
	});
	$(document).bind("click", function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass(".nameTown"))
			$(".townNavOpen").hide();
	});

	$('.buttonCatalog').click(function() {
		$('.catalogNav').slideToggle(), $(this).toggleClass('active');
		return false;
	});

	$('.showAllFilter').click(function() {
		$(this).css({
			display : 'none'
		}), $(this).parent().find('.closeAllFilter').css({
			display : 'inline'
		}), $(this).parents('.oneBlockFilter').find('.blockCheckbox').animate({
			height : '100%'
		}, 200);
		return false;
	});
	$('.closeAllFilter').click(function() {
		$(this).css({
			display : 'none'
		}), $(this).parent().find('.showAllFilter').css({
			display : 'inline'
		}), $(this).parents('.oneBlockFilter').find('.blockCheckbox').animate({
			height : 85
		}, 200);
		return false;
	});
	$('.openComment').click(function() {
		$(this).css({
			display : 'none'
		}), $(this).parent().find('.closeComment').css({
			display : 'block'
		}), $('.blockAllComments').animate({
			'max-height' : '100%'
		}, 200);
		return false;
	});
	$('.closeComment').click(function() {
		$(this).css({
			display : 'none'
		}), $(this).parent().find('.openComment').css({
			display : 'block'
		}), $('.blockAllComments').animate({
			'max-height' : 520
		}, 200);
		return false;
	});

	$('.showAllAnalogs').click(function() {
		$(this).css({
			display : 'none'
		}), $(this).parent().find('.closeAllAnalogs').css({
			display : 'inline'
		}), $(this).parents('.boxAnalog').find('.blockContAnalog').animate({
			height : '100%'
		}, 200);
		return false;
	});
	$('.closeAllAnalogs').click(function() {
		$(this).css({
			display : 'none'
		}), $(this).parent().find('.showAllAnalogs').css({
			display : 'inline'
		}), $(this).parents('.boxAnalog').find('.blockContAnalog').animate({
			height : 60
		}, 200);
		return false;
	});

	$('.blockNumbersPage a').click(function() {
		$(this).parent().parent().find('.sortOpenList').slideToggle();
		return false;
	});
	$('.blockNumbersPage a').click(function() {
		$('.sortOpenListName').slideUp();
		return false;
	});
	$(document).bind("click", function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass(".blockNumbersPage a"))
			$(".sortOpenList").hide();
	});

	$('.blockSort a').click(function() {
		$(this).parent().parent().find('.sortOpenListName').slideToggle();
		return false;
	});
	$('.blockSort a').click(function() {
		$('.sortOpenList').slideUp();
		return false;
	});
	$(document).bind("click", function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass(".blockSort a"))
			$(".sortOpenListName").hide();
	});

	$('.imgPreview li a').click(function() {
		$('.imgPreview li').removeClass('active');
		$(this).parents('li').addClass('active');
		return false;
	});

	$('.imgPreview a').click(function() {
		srcBig = $(this).attr('data-big');
		$('.imgBig img').attr('src', srcBig);
		
		$('#zoom1').attr({'href' : $(this).attr('data-origin')} );
		
		$('#zoom1').parents('.imgBig').attr({'data-num' : $(this).attr('data-num')});
		
		$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
		
		return false;
	});

	var i = 0;

	$(".check").each(
			function() {
				i++;
				$(this).attr("id", "check_" + i).live(
						"change",
						function() { 
							if ($(this).attr("checked") == "checked") {
								$(this).parents('.lineCheckbox').find('label')
										.addClass('active');
							} else {
								$(this).parents('.lineCheckbox').find('label')
										.removeClass('active');
							}
						});
			});

	var s = 0;
	$(".radioButton").each(
			function() {
				s++;
				$(this).attr("id", "radioButton_" + s).live(
						"change",
						function() {
							if ($(this).attr("checked") == "checked") {
								$(this).parents('.lineRadio').find('label')
										.addClass('active');
							} else {
								$(this).parents('.lineRadio').find('label')
										.removeClass('active');
							}
						});
			});

	$('.chooseColor ul li a').click(
			function() {
				$('.chooseColor ul li').removeClass('active'), $(this).parent()
						.toggleClass('active');
				return false;
			});
	$('.chooseBox ul li a').click(
			function() {
				$('.chooseBox ul li').removeClass('active'), $(this).parent()
						.toggleClass('active');
				return false;
			});

	$('.linkMark').click(function() {
		$(this).parents('.productMark').find('.boxYourMark').slideToggle();
		return false;
	});
	$(document).bind("click", function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass(".linkMark"))
			$(".boxYourMark").hide();
	});

	$('.blockInputform .lineRadio').click(
			function() {
				$(this).parents('.blockInputform').find('label').removeClass(
						'active'), $('label', this).addClass('active');
			});
	$('.itemsRadio .lineRadio').click(
			function() {
				$(this).parents('.itemsRadio').find('label').removeClass(
						'active'), $('label', this).addClass('active');
			});

	$('.oneLineCompare').hover(function() {
		$('.bg_hover', this).slideToggle(0);
		return false;
	});
	$('#tableLine1').hover(function() {
		$('#line1 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine2').hover(function() {
		$('#line2 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine3').hover(function() {
		$('#line3 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine4').hover(function() {
		$('#line4 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine5').hover(function() {
		$('#line5 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine6').hover(function() {
		$('#line6 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine7').hover(function() {
		$('#line7 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine8').hover(function() {
		$('#line8 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine9').hover(function() {
		$('#line9 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine10').hover(function() {
		$('#line10 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine11').hover(function() {
		$('#line11 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine12').hover(function() {
		$('#line12 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine13').hover(function() {
		$('#line13 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine14').hover(function() {
		$('#line14 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine15').hover(function() {
		$('#line15 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine16').hover(function() {
		$('#line16 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine17').hover(function() {
		$('#line17 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine18').hover(function() {
		$('#line18 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine19').hover(function() {
		$('#line19 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine20').hover(function() {
		$('#line20 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine21').hover(function() {
		$('#line21 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine22').hover(function() {
		$('#line22 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine23').hover(function() {
		$('#line23 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine24').hover(function() {
		$('#line24 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine25').hover(function() {
		$('#line25 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine26').hover(function() {
		$('#line26 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine27').hover(function() {
		$('#line27 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine28').hover(function() {
		$('#line28 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine29').hover(function() {
		$('#line29 .bg_hover').slideToggle(0);
		return false;
	});
	$('#tableLine30').hover(function() {
		$('#line30 .bg_hover').slideToggle(0);
		return false;
	});

});

$(document)
		.ready(
				function() {
					$(".townNavOpen a").click(
							function() {
								var name = $(this).html();
								$(".nameTown").html(name);
								$(".townNavOpen").slideUp(100).parents(
										"#header").find(".nameTown")
										.removeClass("active");
								$('.townNavOpen a').removeClass("active");
								$(this).addClass("active");
								return false;
							});

					$('.boxOneProduct').mouseover(function() {
						$('.productCont', this).css({
							'z-index' : 10
						}), $('.productOpen', this).css({
							display : 'block'
						});
						return false;
					});
					$('.boxOneProduct').mouseout(function() {
						$('.productCont', this).css({
							'z-index' : 3
						}), $('.productOpen', this).css({
							display : 'none'
						});
						return false;
					});

					$('#rating_1')
							.rating(
									{
										fx : 'full',
										image : '/local/templates/klavazip.main/img/mark_star.png',
										loader : '/local/templates/klavazip.main/img/ajax-loader.gif',
										// url :
										// '/ajax/product-reating/index.php',
										click : function(v) {

											$
													.ajax({
														type : "POST",
														url : "/ajax/set-product-reating/",
														data : {
															v : v,
															id : $(
																	'#js-catalog_product_element_id')
																	.val()
														},
														success : function(data) {
															var jsonObject = eval('('
																	+ data
																	+ ')');

															if (jsonObject.st == 'error') {
																alert(jsonObject.mess);
															} else {
																$
																		.ajax({
																			type : "POST",
																			url : "/ajax/get-product-reating/",
																			data : {
																				id : $(
																						'#js-catalog_product_element_id')
																						.val()
																			},
																			success : function(
																					data) {
																				$(
																						'#js-element-boxStar')
																						.html(
																								data);
																			}
																		});
															}
														}
													});
										}
									});

					$('#rating_2')
							.rating(
									{
										fx : 'full',
										image : '/local/templates/klavazip.main/img/mark_star.png',
										loader : '/local/templates/klavazip.main/img/ajax-loader.gif',
										click : function(v) {

											$.ajax({
														type : "POST",
														url : "/ajax/set-product-reating/",
														data : {
															v : v,
															id : $(
																	'#js-catalog_product_element_id')
																	.val()
														},
														success : function(data) {
															var jsonObject = eval('('
																	+ data
																	+ ')');

															if (jsonObject.st == 'error') {
																alert(jsonObject.mess);
															} else {
																$(
																		'#comment-user-reating')
																		.val(v);
															}
														}
													});
										}
									});

				});


jQuery.fn.labelOver = function(overClass) {
	return this.each(function(){
		var label = jQuery(this);
		var f = label.attr('for');
		
		if (f) {
			var input = jQuery('#' + f);
			
			this.hide = function() {
			  label.hide();
			};
			
			this.show = function() {
			  if (input.val() == '') 
				  label.show();
			};

			// handlers
			input.focus(this.hide);
			input.blur(this.show);
		    label.addClass(overClass).click(function(){ input.focus(); });
			
			if (input.val() != '') this.hide(); 
		}
	});
};

