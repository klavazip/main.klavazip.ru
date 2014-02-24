<?

/**
 * Отправка извещения операторам о добавленном отзыве
 * 
 * */

AddEventHandler("iblock", "OnAfterIBlockElementAdd", "BXMCustomerReviewsSendToMailHandler");

function BXMCustomerReviewsSendToMailHandler($arFields)
{
    
    //если это добавлен конечно отзыв то продолжим
    if(($CEvent = new CEvent()) &&  (IntVal($arFields['IBLOCK_ID']) == 10) )
    {
       $dbProperty = CIBlockElement::GetProperty($arFields['IBLOCK_ID'],$arFields['ID']);
       while($arProperty =  $dbProperty->Fetch())
       {
            $arProp[$arProperty['CODE']] = $arProperty['VALUE'];
       }
       // сформируем письмо
       $CEvent->Send(
            'CUSTOMER_REVIEWS', //тип почтового сообщения
            SITE_ID, // иденнтификатор сайта
            array( // массив значений
                'USER_EMAIL'=> ($arProp['email'] ? $arProp['email'] : 'не указано'), // емэйл пользователя'
                'USER_NAME' => ($arProp['name'] ? $arProp['name'] : 'не указано'), // имя
                'USER_REVIEW'=> ($arProp['text'] ? $arProp['text'] : 'не указано'), // отзыв
                'USER_PAGE_URL' => (strpos($arProp['URL'],'http://') === false ? 'http://' : '').( strpos($arProp['URL'],'klavazip.ru') === false ? 'klavazip.ru' : '').$arProp['URL'] , // url адрес
                'USER_REVIEW_ID' => $arFields['ID'], // ID отзыва
                'UNIQ_STR' => ($arProp['UNIQ_STR'] ? $arProp['UNIQ_STR'] : '') // 
            )
       );
       
       
    }
    return true;
}

?>