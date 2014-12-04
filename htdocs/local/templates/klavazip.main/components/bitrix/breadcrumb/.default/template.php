<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); 

/*print "<pre>";
print_r($arResult);
print "</pre>";*/

__IncludeLang(dirname(__FILE__) . '/lang/' . LANGUAGE_ID . '/' . basename(__FILE__));

$curPage = $GLOBALS['APPLICATION']->GetCurPage($get_index_page = false);

if ($curPage != SITE_DIR && count($arResult)<2 && !strpos($curPage,"filter")) {
	if (empty($arResult) || $curPage != $arResult[count($arResult) - 1]['LINK'])
		$arResult[] = array('TITLE' => htmlspecialcharsback($GLOBALS['APPLICATION']->GetTitle(false, true)), 'LINK' => $curPage);
}

if (empty($arResult))
	return "";


ob_start();

?>
<div class="boxBreadCrumbs">
	<div class="breadCrumbs">
		<a href="<?=SITE_DIR?>">Главная</a>
		
		<? 
		$i_Count = count($arResult);
	
		if($i_Count > 0)
		{
			?><div class="markerNext"></div><? 
		}
		else
		{
			?><div class="markerNext"></div><?
		}
		
		
		foreach ($arResult as $i_key => $ar_Value)
		{
			if( strlen($ar_Value['LINK'])  > 0)
			{
				?><a href="<?=$ar_Value['LINK']?>"><?=$ar_Value['TITLE']?></a><?
				if( ($i_key + 1) == $i_Count )
				{
					?><div class="markerFirst"></div><? 
				}
				else
				{
					?><div class="markerNext"></div><? 
				}
			}
		}
		
		?>
	</div>
	<div class="clear"></div>		
</div>

<?
//$strReturn = '<div class="breadcrumb"><a title="' . GetMessage('BREADCRUMB_MAIN') . '" href="' . SITE_DIR . '">Главная</a>';

$content = ob_get_contents();
ob_end_clean();

return $content;
//echo '<pre>', print_r($arResult).'</pre>';

/*



for ($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);
    if ($index != 0)
	$prevLink = $arResult[$index - 1]["TITLE"];
    else {
	$prevLink = "";
    }
   // if ($arResult[$index]["TITLE"] <> $prevLink) {
	$strReturn .= '<span class="arrow"></span>';

	if ($arResult[$index]["LINK"] <> "" )
	    $strReturn .= '<a href="' . $arResult[$index]["LINK"] . '" title="' . $title . '">' . $title . '</a>';
	else
	    $strReturn .= '<span>' . $title . '</span>';
   // }
    if ($title == 'Пользователи') {
	$strReturn .='<span></span>';
	break;
    }
}

$strReturn .= '</div>';

return $strReturn;
*/
?>