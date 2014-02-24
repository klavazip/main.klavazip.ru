<?

/**
 * Извещение пользователей о поступлении товаров на продажу
 * 
 * */


AddEventHandler("main", "OnEpilog", "BXMNoticeOfAvailabilityOfGoodsHandler");


function BXMNoticeOfAvailabilityOfGoodsHandler()
{
    
    $start_period = '3600'; //секунды
    $start_time = COption::GetOptionString("main","time_next_notice_availability_goods",'0');
    $cur_time = time();
    if($cur_time >= $start_time)
    {
        COption::SetOptionString("main","time_next_notice_availability_goods",$cur_time + $start_period);
    
        $F_ID = '5';
        $F_STATUS_ID = '6'; // одидает
        $F_STATUS_COMPLETE_ID = '7'; //  после оповещения переводит в статус
        if(CModule::IncludeModule('form'))
        {
            $dbrFR = CFormResult::GetList(
                $F_ID ,
                ($by='s_id'),
                ($order='asc'),
                array(
                    'STATUS_ID'=>$F_STATUS_ID,
                    'TIMESTAMP_2' => date('d.m.Y H:i:s',strtotime('-1 day'))
                ),
                $bFiltered,
                'N',
                '500'
            );
            while($arFR = $dbrFR->Fetch())
            {
                
                //сменить ли статус
                $bComplete = false;
                
                $arResult = CFormResult::GetDataByID($arFR['ID']);
                //echo '<pre>'; print_r($arResult); echo '</pre>';
                
                $email = isset($arResult['notice_oaog_email'][0]['USER_TEXT']) ? trim($arResult['notice_oaog_email'][0]['USER_TEXT']) : '' ;
                $product_id = isset($arResult['notice_oaog_product_id'][0]['USER_TEXT']) ? intval($arResult['notice_oaog_product_id'][0]['USER_TEXT']) : 0;
                $product_name = isset($arResult['notice_oaog_product_name'][0]['USER_TEXT']) ? trim($arResult['notice_oaog_product_name'][0]['USER_TEXT']) : '';
                $user_name = isset($arResult['notice_oaog_name'][0]['USER_TEXT']) ? trim($arResult['notice_oaog_name'][0]['USER_TEXT']) : '';
                $phone = isset($arResult['notice_oaog_phone'][0]['USER_TEXT']) ? trim($arResult['notice_oaog_phone'][0]['USER_TEXT']) : '' ;
                
                
                //если email корректный отправим письмо пользователю если товар появился
                if(preg_match('/^([a-z\d\_\-\.]+)@([\w\d\.]+)([\w]{2,4})$/i',$email,$match))
                { 
                    // проверяем товар
                    if($product_id > 0)
                    { 
                       
                        if(CModule::IncludeModule('catalog'))
                        { 
                            $arProduct = GetCatalogProduct($product_id);
                            if(intval($arProduct['QUANTITY']) > 0)
                            {
                                // если количество больше нуля
                                // отправим на почту
                                $arFields = array(
                                        'FROM' => 'info@klavazip.ru',
                                        'EMAIL_TO' => $email,
                                        'USER_NAME' => $user_name,
                                        'PRODUCT_NAME' => $product_name,
                                        'ID_REQUEST' => $arFR['ID']
                                );
                                $event = new CEvent();
                                $event->Send('NOTICE_AVAILABILITY_GOOD',  SITE_ID, $arFields, "Y" );
                                //echo '<pre>'; print_r($arFields); echo '</pre>';
                                // разрешим сменить статус
                                $bComplete = true;
                            }
                            
                        }
                    }
                    else
                    {
                        // нет ID 
                        $bComplete = true;
                    }
                }
                else
                {
                    // раз почта не качественна то сменим статус
                    $bComplete = true;
                }
                
                if(CModule::IncludeModule('rarus.sms4b'))
                {
                    global $SMS4B;
                    
                    $bNoticeSend = false;
                                        
                    if($product_id > 0)
                    { 
                       
                        if(CModule::IncludeModule('catalog'))
                        { 
                            $arProduct = GetCatalogProduct($product_id);
                            if(intval($arProduct['QUANTITY']) > 0)
                            {
                                // названеи
                                $product_name = $arProduct['NAME'];
                                // можно отправить сообщение
                                $bNoticeSend = true;
                            }
                            else
                            {
                                // извещение не удалось отправить
                                $bComplete = false;
                            }
                        }
                    }
                    else
                    {
                        // нет ID 
                        $bComplete = true;
                    }
                    
                    if(strlen($product_name) > 0 && $bNoticeSend)
                    {
                        $message = 'Товар '.$product_name.' поступил в продажу';
                        $bComplete = $SMS4B->SendSMS($message,$phone);
                    }
                                       
                }
                
                // если можно то сменим статус
                if($bComplete)
                {
                    // завершен
                    CFormResult::SetStatus($arFR['ID'],$F_STATUS_COMPLETE_ID);
                    //echo 'yes';
                }
                else
                {
                    // обновляем, для того чтобы не проходить повторно по тем же 
                    // смена статуса на окончательный
                    CFormResult::SetStatus($arFR['ID'],$F_STATUS_ID);
                    //echo 'no';
                }
                
            }
            
        }
    }
    return true;
}




?>