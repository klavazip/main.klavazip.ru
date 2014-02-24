<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	<?
	/*Конвертер даты*/
	function
	convertDate($date)
	{
		$components = explode (" ", $date, 2);
		$monthes    = array
		(
				'января',
				'февраля',
				'марта',
				'апреля',
				'мая',
				'июня',
				'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' );
		$date = explode ( '.', $components [0], 3 );
		
		return (trim ( $date [0], '0' ) . " " . $monthes [(( int ) ($date [1]) - 1)] . " " . $date [2]);
	}
	/*
	 * Конвертер даты END
	 */
	?>
<div class="boxMain">
	<div class="tinymce">
		<h1><?=$arResult['NAME']?></h1>
		<div class="border_1"></div>
		<div class="tinumceText about-company">
				<?
				echo $arResult['PREVIEW_TEXT'];

				if ($arParams['MAP'] == 'Y')
				{	
					$APPLICATION->IncludeComponent("bitrix:map.yandex.view", ".default", array(
					"INIT_MAP_TYPE" => "HYBRID",
					"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.751246811398886;s:10:\"yandex_lon\";d:37.732007979215474;s:12:\"yandex_scale\";i:18;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.732374737434;s:3:\"LAT\";d:55.751150396486;s:4:\"TEXT\";s:0:\"\";}}}",
					"MAP_WIDTH" => "450",
					"MAP_HEIGHT" => "350",
					"CONTROLS" => array(
						0 => "ZOOM",
						1 => "TYPECONTROL",
						2 => "SCALELINE",),
					"OPTIONS" => array(
						0 => "ENABLE_SCROLL_ZOOM",
						1 => "ENABLE_DBLCLICK_ZOOM",
						2 => "ENABLE_DRAGGING",),
					"MAP_ID" => "1"),false);
				}
	 			?> 
		</div>
	</div>
</div>