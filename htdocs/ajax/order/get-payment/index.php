<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$i_PaymentSystem = intval($_POST['user_type_id']);
if($i_PaymentSystem > 0)
{
	ob_start();
		$i = 0;
		$rs_PaySystem = CSalePaySystem::GetList(array("SORT" => "ASC"), array("ACTIVE" => "Y", 'PERSON_TYPE_ID' => $i_PaymentSystem));
		while ($ar_PaySystem = $rs_PaySystem->Fetch())
		{
			$ar_Desc = explode('|', $ar_PaySystem['DESCRIPTION']);
			?>
			<label>
				<input <?=($i == 0) ? 'checked="checked"' : ''?>  type="radio" name="PAYMENT" value="<?=$ar_PaySystem['ID']?>"> 
				<span class="n"><img src="<?=$ar_Desc[0]?>" alt="" /> <?=$ar_PaySystem['NAME']?></span>
			</label>
			<?
			$i++;
		}
		
		# для юр лиц надо показывать все, те которые не привязаны сделаем не активными
		if( $i_PaymentSystem != 1 )
		{
			$rs_PaySystem = CSalePaySystem::GetList(array("SORT" => "ASC"), array("ACTIVE" => "Y", 'PERSON_TYPE_ID' => 1));
			while ($ar_PaySystem = $rs_PaySystem->Fetch())
			{
				$ar_Desc = explode('|', $ar_PaySystem['DESCRIPTION']);
				?>
				<label class="disable">
					<input type="radio" disabled="disabled" name="PAYMENT" value="<?=$ar_PaySystem['ID']?>"> 
					<span class="n">
						<img src="<?=$ar_Desc['0']?>" alt="" />
						<?=$ar_PaySystem['NAME']?> <?=(strlen($ar_Desc[1]) > 0) ? "<i>(коммисия $ar_Desc[1])</i>" : ""?>
					</span>
				</label>
				<?
			}
		}	
		
		$html = ob_get_contents();
	ob_end_clean();
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
}