$().ready(function(){

var prevewimg = $(".gallery-prevew-image");
/*var loading = $("<img src='/bitrix/templates/klavazip/components/bitrix/catalog/bxmaker/bitrix/catalog.element/.default/images/ajax-loader.gif' id='load'>");
$(prevewimg).hide();*/

var position = $(".gallery-item img:first").position();
$( "#gallery-item-hover" ).css("left", position.left);

	$(".gallery-prevew-image").elevateZoom();
	$(".gallery-prevew-image").click(function(){
		$( "#div" ).fadeIn();
		$( "#large" ).append("<img src='" + this.src + "'>");
		var imgs = $(".gallery-list img");
		for(i=0; i<imgs.length; i++)
		{
			$( "#sidebar" ).append("<img src='" + imgs[i].src + "'>");
		}
		
		$("#sidebar img").click(function(){
			var position = $(this).position();
			$( "#sidebar-hover" ).animate({top: position.top}); //css("top", position.top);
			
			var src = this.src;
			src = src.replace("small/", "");
			$( "#large img" ).remove();
			$( "#large" ).append("<img src='" + src + "'>");
			//$( "#large" ).zoom({ on:'click' });
		});
		
		$("#large").click(function(){
			var scr1="";
			$("#large img").each(function(i){
				scr1=this.src;
			   
			});
			//alert(scr1);
			var str="";
			var scr2="";
			
			var next=false;
			var first="";
			var first_pos=0;
			var j=0;	
			$("#sidebar img").each(function(i){
				var position2 = $(this).position();
				j++;
				var src = this.src;
			    src = src.replace("small/", "");
				
				if (j==1) {
					first=src;
					first_pos=$(this).position();
					}
				
				if (src==scr1) next=true;
			
				if (src!=scr1 && scr2=="" && next){
					$( "#sidebar-hover" ).animate({top: position2.top}); 
					scr2=src;
					$( "#large img" ).remove();
			        $( "#large" ).append("<img src='" + src + "'>");
					
					//var position = $("#gallery-item-hover").position();
					//alert(position.top);
					//$( "#gallery-item-hover" ).css("top", position.top);
					
					
				}
			});
			
			//alert (first);
			if (scr2==""){
				$( "#sidebar-hover" ).animate({top: first_pos.top}); 
				scr2=first;
				$( "#large img" ).remove();
			    $( "#large" ).append("<img src='" + scr2 + "'>");
				
			
			}
			//alert(scr2);
			//$( "#large" ).zoom({ on:'click' });
		});
		
		/*$( "#large" ).zoom({ on:'click' });
		$( "#large" ).css("cursor","url('/bitrix/templates/klavazip/components/bitrix/catalog/bxmaker/bitrix/catalog.element/.default/images/cur_zoom-in.cur')");
		$( "#large" ).click(function(){
			var cursor = $( "#large" ).css("cursor");
			if(cursor.match("cur_zoom-in.cur") != null)
			{
				$( "#large" ).css("cursor","url('/bitrix/templates/klavazip/components/bitrix/catalog/bxmaker/bitrix/catalog.element/.default/images/cur_zoom-out.cur')");
			}
			else
			{
				$( "#large" ).css("cursor","url('/bitrix/templates/klavazip/components/bitrix/catalog/bxmaker/bitrix/catalog.element/.default/images/cur_zoom-in.cur')");
			}
		});*/
		
	});
	$( "#close_button" ).click(function(){
		$( "#div" ).hide();
		$( "#large img" ).remove();
		$( "#sidebar img" ).remove();
		return false;
	});

	$( "#close img" ).hover(function(){
		$(this).attr("src", "/bitrix/templates/klavazip/components/bitrix/catalog/bxmaker/bitrix/catalog.element/.default/images/closehover.png");
	},
	function(){
		$(this).attr("src", "/bitrix/templates/klavazip/components/bitrix/catalog/bxmaker/bitrix/catalog.element/.default/images/close.png");
	});
	
	$(".gallery-item img").click(function(){
		$(prevewimg).hide();
		//$( "#prevew" ).append(loading);
		$(prevewimg).load(function(){
		//$(loading).remove();
		$(this).show();
		});
		
		var position = $(this).position();
		$( "#gallery-item-hover" ).animate({left: position.left},"slow"); //css("left", position.left);
		var src = this.src;
		src = src.replace("small/", "");
		$(".gallery-prevew-image").attr("src", src);
		$(".gallery-prevew-image").attr("class", "gallery-prevew-image");
		$(".gallery-prevew-image").elevateZoom();
	});
	
	$(".gallery-list img").click(function(){
		
		
		
	});
	
	//loading
	$( "#prevew" ).append(loading);
	$(prevewimg).load(function(){
		$(loading).remove();
		$(this).show();
	});
	
});