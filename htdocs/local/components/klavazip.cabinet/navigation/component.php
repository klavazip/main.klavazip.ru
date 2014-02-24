<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


/*
$s_CurrentDir = $APPLICATION->GetCurDir();
switch (true)
{
	case ( in_array($s_CurrentDir, array(
										'/cabinet/', 
										'/cabinet/order-detail/', 
										'/cabinet/query-product/', 
										'/cabinet/query-document/', 
										'/cabinet/add-balance/', 
										'/cabinet/pre-orders/', 
										'/cabinet/transits/',
										'/cabinet/order-return-form/'
									))):
		$arResult['TAB_SELECTED'] = 'ACTIVE'; 
		break;
	case ( in_array($s_CurrentDir, array('/cabinet/profile/', '/cabinet/profile-info/', '/cabinet/profile-pass/', '/cabinet/profile-delevery/', '/cabinet/profile-subs/')) ): 
		$arResult['TAB_SELECTED'] = 'PROFILE'; 
		break;
	case ( in_array($s_CurrentDir, array('/cabinet/messages/', '/cabinet/messages-out/', '/cabinet/messages-in/', '/cabinet/messages-bas/')) ): 		
		$arResult['TAB_SELECTED'] = 'MESSAGES'; 
		break;
}
*/


$this->IncludeComponentTemplate();