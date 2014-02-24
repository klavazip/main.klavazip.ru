<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
if (empty($arResult))
	return;

$lastSelectedItem = null;
$lastSelectedIndex = -1;

foreach($arResult as $itemIdex => $arItem)
{
	if (!$arItem["SELECTED"])
		continue;

	if ($lastSelectedItem == null || strlen($arItem["LINK"]) >= strlen($lastSelectedItem["LINK"]))
	{
		$lastSelectedItem = $arItem;
		$lastSelectedIndex = $itemIdex;
	}
}*/

?>

<table width="100%"> 
  <tbody>
  	<tr>
<?foreach($arResult as $itemIdex => $arItem):?>
<td><a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a></td>	
<?endforeach;?>
		</tr>
   </tbody>
</table>