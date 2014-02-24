<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="margin-bottom"></div>
<div class="page-title">	
	<h1>Корзина</h1>
</div>

<?
//echo "<pre>"; print_r($arResult); echo "</pre>";
if (StrLen($arResult["ERROR_MESSAGE"])<=0)
{	
	$arUrlTempl = Array(
		"delete" => $APPLICATION->GetCurPage()."?action=delete&id=#ID#",
		"shelve" => $APPLICATION->GetCurPage()."?action=shelve&id=#ID#",
		"add" => $APPLICATION->GetCurPage()."?action=add&id=#ID#",
	);
	?>
	<script>
	function ShowBasketItems(val)
	{
		if(val == 2)
		{
			if(document.getElementById("id-cart-list"))
				document.getElementById("id-cart-list").style.display = 'none';
			if(document.getElementById("id-shelve-list"))
				document.getElementById("id-shelve-list").style.display = 'block';
			//if(document.getElementById("id-na-list"))
				//document.getElementById("id-na-list").style.display = 'none';
		}
		else if(val == 3)
		{
			if(document.getElementById("id-cart-list"))
				document.getElementById("id-cart-list").style.display = 'none';
			if(document.getElementById("id-shelve-list"))
				document.getElementById("id-shelve-list").style.display = 'none';
			//if(document.getElementById("id-na-list"))
				//document.getElementById("id-na-list").style.display = 'block';
		}		
		else
		{
			if(document.getElementById("id-cart-list"))
				document.getElementById("id-cart-list").style.display = 'block';
			if(document.getElementById("id-shelve-list"))
				document.getElementById("id-shelve-list").style.display = 'none';
			//if(document.getElementById("id-na-list"))
				//document.getElementById("id-na-list").style.display = 'none';
		}
	}
	</script>
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form">
		<?
		//if ($arResult["ShowReady"]=="Y")
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");

		//if ($arResult["ShowDelay"]=="Y")
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delay.php");

		//if ($arResult["ShowNotAvail"]=="Y")
			//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_notavail.php");
		?>
	</form>
	
	 <style type="text/css">
   	.fast-order input[type='text']{
   		width:160px;
   		padding: 4px 20px 2px 10px;
   		border:1px solid #ccc;
		background:#fff;
		-moz-border-radius:10px;
		-webkit-border-radius:10px;
		border-radius:10px;
		-moz-box-shadow:0 1px 1px #888 inset;
		-webkit-box-shadow:0 1px 1px #888 inset;
		box-shadow:0 1px 1px #888 inset;		
		color:#333;
		font-size:12px;
		display:inline-block;
		position:relative;
   	}
   	.fast-order input[type='submit']{
   		 background: url("<?=SITE_TEMPLATE_PATH?>/img/button.png") no-repeat scroll right -55px transparent;
    border: medium none;
    color: #fff !important;
    cursor: pointer;
    display: block;
    float: left;
    font-size: 14px;
    font-weight: normal;
    height: 42px;
    line-height: 37px;
    margin: 0 -16px;
    padding: 0 20px;
    position: relative;
    text-decoration: none;
    top: -5px;
   	}
   	.errortext{
   		color:#f00;
   	}	
  	</style>
    <div class="fast-order" style="margin-top: 5px;">
    	<p class="big18">Быстрый заказ</p>
        <p class="italic">Если у вас нет времени на оформление заказа, оставьте нам свои данные — мы вам перезвоним и уточним всю недостающую информацию.</p>
        <?$APPLICATION->IncludeComponent("bitrix:form", "template1", array(
	"START_PAGE" => "new",
	"SHOW_LIST_PAGE" => "N",
	"SHOW_EDIT_PAGE" => "N",
	"SHOW_VIEW_PAGE" => "N",
	"SUCCESS_URL" => "/fastbox.php",
	"WEB_FORM_ID" => "1",
	"RESULT_ID" => $_REQUEST[RESULT_ID],
	"SHOW_ANSWER_VALUE" => "N",
	"SHOW_ADDITIONAL" => "N",
	"SHOW_STATUS" => "Y",
	"EDIT_ADDITIONAL" => "N",
	"EDIT_STATUS" => "Y",
	"NOT_SHOW_FILTER" => array(
		0 => "",
		1 => "",
	),
	"NOT_SHOW_TABLE" => array(
		0 => "",
		1 => "",
	),
	"IGNORE_CUSTOM_TEMPLATE" => "N",
	"USE_EXTENDED_ERRORS" => "N",
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "/personal/cart/",
	"AJAX_MODE" => "Y",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "N",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "N",
	"CACHE_TIME" => "3600",
	"CHAIN_ITEM_TEXT" => "",
	"CHAIN_ITEM_LINK" => "",
	"AJAX_OPTION_ADDITIONAL" => "",
	"VARIABLE_ALIASES" => array(
		"action" => "action",
	)
	),
	false
);?>
</div>
	
	<?/*
	<script>
	<?if($arResult["ShowReady"] != "Y")
	{
		if($arResult["ShowDelay"] != "Y")
		{
			?>ShowBasketItems(3);<?
		}
		else
		{
			?>ShowBasketItems(2);<?
		}
	}
	?>
	</script>
	<?
	*/
	
	
	
}
else{
	echo '<div class="content-bg2">';
	ShowNote($arResult["ERROR_MESSAGE"]);
	echo '</div>';	
}
?>
<script type='text/javascript'>
$('[name=web_form_submit]').live('click',function(e){
    if( !(/^ *\+{0,1} *(?:(?:(?:\d *)+(?:\-{0,1} *\( *(?: *\d+ *\-{0,1} *\d(?:\d+ *)*)+ *\)){0,1})|(?:\( *(?: *\d+ *\-{0,1} *\d(?:\d+ *)*)+ *\)))(?: *\- *\d(?:\d+ *)*)* *(?:\d+ *)* *$/m.test($('[name=form_text_3]').val())) )
	{
		$ertext=$('.errortext');
		if($ertext.length)
			$ertext.html('Поле телефон заполнено неверно.');
		else
			$(this).parents('p:first').prev().prev().before('<div class="errortext">Поле телефон заполнено неверно.</div>');
		e.preventDefault();
	}
})
</script>