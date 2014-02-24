<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//������� �������� ��������
$this->SetViewTarget('catalog_page_title_box');echo '<h1 class="cat-title">'.GetMessage('CT_SECTIONS_CATALOG_LABEL').'</h1>';$this->EndViewTarget();

$TOP_DEPTH = $arResult["SECTION"]["DEPTH_LEVEL"];
$AIM_DEPTH = $TOP_DEPTH+1; // ������� � ������� �����������, ����� ������ ��� �� ��������� �� ������ ������ �������� �������
$CURRENT_DEPTH = $TOP_DEPTH;
$CurSectionID = $arResult['SECTION']['ID'];
foreach($arResult["SECTIONS"] as $arSection):
    // ��������� ������� ����������� ����� 2
    if($arSection["DEPTH_LEVEL"] > $AIM_DEPTH+1) continue;
    
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
	
    
    if($arSection["DEPTH_LEVEL"] == $AIM_DEPTH)
    {
        // �������� ������� �������, �������� ���� ������� ���������� �������, � ������
        if($CURRENT_DEPTH > $arSection["DEPTH_LEVEL"])
        {
                    echo str_repeat('</div><!-- .catalog-section-childs --></div><!-- .catalog-section-info --></div><!-- .catalog-section --><div class="catalog-section-separator" ></div>',$CURRENT_DEPTH - $arSection["DEPTH_LEVEL"]);
        }
                
        ?><div class="catalog-section"><?
            ?><div class="catalog-section-info" ><?
                
                // ������� �������� �������
                if($arSection['ID'] != $CurSectionID)
                {
                    ?><div class="catalog-section-title"><a href="<?=$arSection['SECTION_PAGE_URL'];?>" title="<?=$arSection['NAME'];?>" ><h2><?=$arSection['NAME'];?></h2></a></div><?
                }
    }
           if($arSection['DESCRIPTION'] && $arSection["DEPTH_LEVEL"] == $AIM_DEPTH)
           {
                ?><div class="catalog-section-description" ><?=( $arSection['DESCRIPTION_TYPE'] == 'TEXT' ? $arSection['DESCRIPTION'] : $arSection['~DESCRIPTION']);?></div><?
           }
           // ���� ��� �� ������� �������
           if($arSection["DEPTH_LEVEL"] > $CURRENT_DEPTH)
           {
                // �� ������ ��������, �������������� ���� �������� ����
                ?><div class="catalog-section-childs"><?
           }
           
           // ���� ��� �� ������ ������� ������ ����� �������� ����� � ���� ������������
           if($arSection["DEPTH_LEVEL"] > $AIM_DEPTH)
           {
                //���� ������ ���������, ������ ��� ������������, � ���������� �������� �����������
                if($arSection["DEPTH_LEVEL"] == $CURRENT_DEPTH){ ?><span class="delimetr">|</span><? }
                // ������ ������� �������� ������ ���������
                ?><a href="<?=$arSection['SECTION_PAGE_URL'];?>" title="<?=$arSection['NAME'];?>" id="<?=$this->getEditAreaId($arSection['ID']);?>" ><?=$arSection['NAME'];?><?=($arParams['COUNT_ELEMENTS'] ? '&nbsp;('.$arSection['ELEMENT_CNT'].')' : '');?></a><?
           }
           $CURRENT_DEPTH = $arSection["DEPTH_LEVEL"];
endforeach;

// � ����������, ��������� ���������� ����, � ������� � ��� ���������� ������������ ����� �������
// �������� ������� �������, �������� ���� ������� ���������� �������, � ������
if($CURRENT_DEPTH > $AIM_DEPTH)
{
    echo str_repeat('</div><!-- .catalog-section-childs --></div><!-- .catalog-section-info --></div><!-- .catalog-section --><div class="catalog-section-separator" ></div>',$CURRENT_DEPTH - $AIM_DEPTH);
}
?>