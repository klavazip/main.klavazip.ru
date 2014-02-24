<?
AddEventHandler("form", "OnBeforeResultAdd", array('CBXMWebFormAntiSpam', 'BeforeResultAdd'));
Class CBXMWebFormAntiSpam {

    // перед сохранением резульатат
    function BeforeResultAdd($WEB_FORM_ID, $arFields, $arrVALUES)
    {
       global $APPLICATION,$USER;
        // действие обработчика распространяется только на форму с ID=4
        // ОБРАТНЫЙ ЗВОНОК
        if ($WEB_FORM_ID == 4) 
        {
            if(preg_match('/[\:\/]+/iu',$arrVALUES['form_text_11']) || preg_match('/[\:\/]+/iu',$arrVALUES['form_text_9']))
            {
               // если значение не подходит - отправим ошибку.
               $APPLICATION->ThrowException('Антиспамбот! Недопустимые символы в поле Имя или Телефон');
            }
        }
        //ЗАПРОСЫ НА УВЕДОМЛЕНИЕ
        if ($WEB_FORM_ID == 5) 
        {
            
            if(preg_match('/[\:\/]+/iu',$arrVALUES['form_text_14']) || preg_match('/[\:\/]+/iu',$arrVALUES['form_text_13']))
            {
               // если значение не подходит - отправим ошибку.
               $APPLICATION->ThrowException('Антиспамбот! Недопустимые символы в поле Email или Телефон');
            }
        }
        return true;
    }

}
?>