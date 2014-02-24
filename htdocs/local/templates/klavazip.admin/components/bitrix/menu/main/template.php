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


          <ul class="nav navbar-nav">
          	<? 
          	foreach ($arResult as $ar_Value)
          	{
          		?><li <?=($ar_Value['SELECTED']) ? 'class="active"' : ''?>><a href="<?=$ar_Value['LINK']?>"><?=$ar_Value['TEXT']?></a></li><?
          	}

          	/*
          	?>
            <li class="active"><a href="/klavazip/">Главная</a></li>
            <li><a href="/klavazip/cabinet/">Кабинет</a></li>
            <li><a href="#">Заказы</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Contact</a></li>
            <?
			*/
           
          //echo '<pre>', print_r($arResult).'</pre>';
          ?>
          </ul>

          

          <? /*?>
<table width="100%"> 
  <tbody>
  	<tr>
		<?foreach($arResult as $itemIdex => $arItem):?>
			<td><a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a></td>	
		<?endforeach;?>
		</tr>
   </tbody>
</table>
<? */ ?>