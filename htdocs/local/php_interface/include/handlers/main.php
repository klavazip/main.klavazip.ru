<?
/*
AddEventHandler('main', 'OnBeforeProlog', array('CMainHandlers', 'OnBeforePrologHandler'));
AddEventHandler('main', 'OnAfterUserAdd', array('CMainHandlers', 'OnAfterUserAddHandler'));

class CMainHandlers {
	public static function OnBeforePrologHandler() {
		if (isset($_GET['mid']) && $_GET['subscribe_pid']>0 && (!isset($_SESSION['SIBSCRIBE_POSTINGS']) ||
			is_array($_SESSION['SIBSCRIBE_POSTINGS']) && !in_array(intval($_GET['subscribe_pid']), $_SESSION['SIBSCRIBE_POSTINGS'])) &&
			CModule::IncludeModule('statistic')) {

			if (!isset($_SESSION['SIBSCRIBE_POSTINGS']))
				$_SESSION['SIBSCRIBE_POSTINGS'] = array();
			$_SESSION['SIBSCRIBE_POSTINGS'][] = intval($_GET['subscribe_pid']);
			CStatEvent::AddCurrent('sibscribe_postings', 'posting_'.intval($_GET['subscribe_pid']), $_GET['mid']);
		}
	}
	public static function OnAfterUserAddHandler(&$arFields) {
		if (!empty($_REQUEST['REG_RUB_ID']) && CModule::IncludeModule('subscribe')) {
			$subscr = new CSubscription();
			$rsSub = CSubscription::GetByEmail($arFields['EMAIL']);
			if ($arSub = $rsSub->Fetch()) {
				//if ($arSub['USER_ID'] <= 0) {
				//	$subscr->Update($arSub['ID'], array('SEND_CONFIRM' => 'N', 'USER_ID' => $arFields['ID']));
				//}
			} else {
				$subscr->Add(array(
									'SEND_CONFIRM' => 'N',
									'USER_ID' => $arFields['ID'],
									'ACTIVE' => 'Y',
									'EMAIL' => $arFields['EMAIL'],
									'FORMAT' => 'html',
									'CONFIRMED' => 'Y',
									'RUB_ID' => $_REQUEST['REG_RUB_ID'],
								));
			}
		}
	}
}
*/
?>