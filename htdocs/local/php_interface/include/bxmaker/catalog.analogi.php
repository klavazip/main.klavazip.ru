<?
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "BXMakerCatalogAnalogiClearCache",7000);
/**
 * После обновления проеряется наличие у элемента свойства с перечислением аналогов,
 * если оно есть, то по аждому аналоу скидываем кэш, чтобы отображалось
 * актуальное количество товара - аналога
 * 8 - основной каталог товаров
 * */
function BXMakerCatalogAnalogiClearCache ($arFields)
{
    if(isset($arFields['IBLOCK_ID']) && $arFields['IBLOCK_ID'] == '8' )
    {
        $BXMCP_NAME = 'bxmaker.catalog.analogi';
        $dbr  = CIBlockElement::GetProperty($arFields['IBLOCK_ID'],$arFields['ID'],'sort','asc',array('CODE' =>'CML2_TRAITS'));
        while($ar = $dbr->Fetch())
        {
            if(trim($ar['DESCRIPTION']) == 'Аналоги (XML_ID)')
            {
                global $CACHE_MANAGER;
                
                $arSelect = array('ID','IBLOCK_ID');
                $arFilter = array('IBLOCK_ID'=>$arFields['IBLOCK_ID'],'XML_ID' => explode(',',$ar['VALUE']));
                $dbre = CIBlockElement::GetList(array(),$arFilter,false,false,$arSelect);
                while($are = $dbre->GetNext())
                {
                   $CACHE_MANAGER->ClearByTag($BXMCP_NAME.'|ib_'.$are['IBLOCK_ID'].'|el_'.$are['ID']);
                }
            }
        }
    }
    return true;
}

?>