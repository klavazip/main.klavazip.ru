<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 

	function _getUrlPagen($i_Page)
	{
		global $APPLICATION;
		if ($i_Page!==1)
		{
			return $APPLICATION->GetCurPageParam('PAGEN_1='.$i_Page, array('PAGEN_1'));
		}
		else
		{
			return $APPLICATION->GetCurPageParam('', array('PAGEN_1'));
		}
	}
	

	if($arResult['nStartPage'] < $arResult['nEndPage'])
	{
		?>
		<div class="boxPaging">
			<?
			if ($arResult["NavPageNomer"] > 1)
			{
				?><a class="pagingLeft" href="<?=_getUrlPagen( ($arResult["NavPageNomer"] - 1) )?>">Назад</a><?
			}
			?>
			<div class="listPaging">
				<ul>
					<?
					while($arResult["nStartPage"] <= $arResult["nEndPage"])
					{	
						if ($arResult["nStartPage"] == $arResult["NavPageNomer"])
						{	
							?><li class="active"><a href="#"><?=$arResult["nStartPage"]?></a></li><?
						}
						elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false)
						{
							?><li><a href="<?=_getUrlPagen(1)?>"><?=$arResult["nStartPage"]?></a></li><?
						}
						else
						{
							?><li><a href="<?=_getUrlPagen($arResult["nStartPage"])?>"><?=$arResult["nStartPage"]?></a></li><?
						}
						$arResult["nStartPage"]++;
					}
					?>
				</ul>
			</div>
			<?
			if($arResult["NavPageNomer"] < $arResult["NavPageCount"])
			{
				?><a class="pagingRight" href="<?=_getUrlPagen( ($arResult["NavPageNomer"] + 1) )?>">Далее</a><?
			}
			?>
			<div class="clear"></div>
		</div>
		<? 
	}
	?>