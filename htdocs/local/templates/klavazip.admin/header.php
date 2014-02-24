<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? 
if( ! $USER->IsAdmin())
	LocalRedirect('/');
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>
		<?//$APPLICATION->ShowProperty("T2");?>
		<?$APPLICATION->ShowTitle(false);?>
	    <?//$APPLICATION->ShowProperty("Page_title");?>
	</title>

	<?$APPLICATION->ShowHead();?>	
	
	
	
	<link href="<?=SITE_TEMPLATE_PATH?>/css/bootstrap.css" rel="stylesheet">
	<link href="<?=SITE_TEMPLATE_PATH?>/css/bootstrap-theme.css" rel="stylesheet">
	<link href="<?=SITE_TEMPLATE_PATH?>/css/main.css" rel="stylesheet">
	
</head> 

<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div role="navigation" class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="/klavazip/" class="navbar-brand">www.klavazip.ru .::. Admin Panel</a>
        </div>
        <div class="collapse navbar-collapse">
			<?
          	$APPLICATION->IncludeComponent("bitrix:menu", "main",
				array(
					"ROOT_MENU_TYPE" => "top",
					"MAX_LEVEL" => "1",
					"CHILD_MENU_TYPE" => "top",
					"USE_EXT" => "N",
					"DELAY" => "N",
					"ALLOW_MULTI_SELECT" => "N",
					"MENU_CACHE_TYPE" => "N",
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array()
				)
			);
          	?>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">
		
		


        <?$APPLICATION->IncludeComponent("bitrix:menu", "left",
			Array(
				"ROOT_MENU_TYPE" 	 	=> "left",
				"MAX_LEVEL" 		 	=> "1",
				"CHILD_MENU_TYPE" 	 	=> "left",
				"USE_EXT" 			 	=> "N",
				"DELAY" 			 	=> "N",
				"ALLOW_MULTI_SELECT" 	=> "N",
				"MENU_CACHE_TYPE" 	 	=> "N",
				"MENU_CACHE_TIME" 		=> "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" 	=> ""
			),
		false
		);?>
        
        
        <div class="col-xs-12 col-sm-9">
        
        	
        	<? /*?>
          <p class="pull-right visible-xs">
            <button data-toggle="offcanvas" class="btn btn-primary btn-xs" type="button">Toggle nav</button>
          </p>
          <div class="jumbotron">
            <h1>Hello, world!</h1>
            <p>This is an example to show the potential of an offcanvas layout pattern in Bootstrap. Try some responsive-range viewport sizes to see it in action.</p>
          </div>
          <div class="row">
            <div class="col-6 col-sm-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a role="button" href="#" class="btn btn-default">View details »</a></p>
            </div><!--/span-->
            <div class="col-6 col-sm-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a role="button" href="#" class="btn btn-default">View details »</a></p>
            </div><!--/span-->
            <div class="col-6 col-sm-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a role="button" href="#" class="btn btn-default">View details »</a></p>
            </div><!--/span-->
            <div class="col-6 col-sm-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a role="button" href="#" class="btn btn-default">View details »</a></p>
            </div><!--/span-->
            <div class="col-6 col-sm-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a role="button" href="#" class="btn btn-default">View details »</a></p>
            </div><!--/span-->
            <div class="col-6 col-sm-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a role="button" href="#" class="btn btn-default">View details »</a></p>
            </div><!--/span-->
          </div><!--/row-->
          <? */ ?>
          
        