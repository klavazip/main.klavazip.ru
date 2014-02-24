<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (count($arResult["SECTIONS"]) == 0):?>
	<?=GetMessage('CATALOG_EMPTY_CATALOG');?>
<?endif;?>
<div class="margin-bottom"></div>
<div class="page-title">
	<h1 class="cat-title">
    	Каталог
    </h1> 
</div>
<div class="content-bg2">
<div class="catalog-section-list">
	
<?

$CURRENT_DEPTH=$arResult["SECTION"]["DEPTH_LEVEL"]+1;
foreach($arResult["SECTIONS"] as $arSection):
	if ($arSection['NAME'] == 'Программаторы') continue;
	if ($arSection['NAME'] == 'Услуги доставки') continue;
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT"));
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CATALOG_SECTION_DELETE_CONFIRM')));	

	$bHasPicture = is_array($arSection['PICTURE_PREVIEW']);
	$bHasChildren = is_array($arSection['CHILDREN']) && count($arSection['CHILDREN']) > 0;
?>
	<div class="catalog-section">

		<div class="catalog-section-info" style="margin-bottom: 15px;">
		<?if ($arSection['NAME'] && $arResult['SECTION']['ID'] != $arSection['ID']):?>
			<div class="catalog-section-title"><a style="color:#000; font-size:14px;" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></div>
		<?endif;?>
		<?if ($arSection['DESCRIPTION']):?>
			<div class="catalog-section-desc"><?=$arSection['DESCRIPTION_TYPE'] == 'text' ? $arSection['DESCRIPTION'] : $arSection['~DESCRIPTION']?></div>
		<?endif;?>

		<?if ($bHasChildren):?>
			<div class="catalog-section-childs">
				<?
				$k=false;
				foreach ($arSection['CHILDREN'] as $key => $arChild):
				?>
					
			
						<?if ($k==true) echo '<span class="delimetr">|</span>'; else $k=true;?> <a href="<?=$arChild["SECTION_PAGE_URL"]?>"><?=$arChild['NAME']?></a>
					
				<?endforeach?>	
			</div>
		<?endif;?>
		</div>

	</div>
	<div class="catalog-section-separator"></div>
<?endforeach;?>
</div>
</div>

<?
@define("MY_ER_REDIR","Y");
?>
