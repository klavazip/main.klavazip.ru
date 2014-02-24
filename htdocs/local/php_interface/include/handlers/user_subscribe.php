<?

AddEventHandler("main", "OnAfterUserRegister", array('CBXMAutoSubscribe', 'BXMOnAfterUserRegisterSubscribe'));
AddEventHandler("main", "OnAfterUserSimpleRegister", array('CBXMAutoSubscribe', 'BXMOnAfterUserRegisterSubscribe'));
Class CBXMAutoSubscribe {
    

    // ================================================== //
    //      Подписка  при регистрации   на все рассылки   //
    // ================================================== //
            
    function BXMOnAfterUserRegisterSubscribe(&$arFields)
    {
        
        if(isset($arFields['ID']) && $arFields['ID'] > 0)
        {
            if(CModule::IncludeModule('subscribe'))
            {
                 $subscr = new CSubscription;
                 $ID = $subscr->Add(
                    array(
                        "USER_ID" => $arFields['ID'],
                        "FORMAT" => "html",
                        "EMAIL" => $arFields['EMAIL'],
                        "ACTIVE" => "Y",
                        "RUB_ID" => Array("7","8",'9'),
                        "CONFIRMED" => "Y",
                        "SEND_CONFIRM" => "N"
                    ));
                
                if($ID>0)
                {
                    $res = $subscr->Update($ID,
                       array(
                         "USER_ID" => $arFields['ID'],
                         "FORMAT" => 'html',
                         "EMAIL" => $arFields['EMAIL'],
                         "RUB_ID" => Array("7","8",'9'),
                         "ACTIVE" => "Y",
                         "ALL_SITES" => 'Y',
                         "CONFIRMED" => "Y",
                        "SEND_CONFIRM" => "N"
                      )
                   );
                }
            }
        }
        return true;
    }
    
} // Class
?>