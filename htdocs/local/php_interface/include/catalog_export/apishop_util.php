<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (check_bitrix_sessid())
{
	echo "<script type=\"text/javascript\">\n";

	$bNoTree = True;
	$bIBlock = false;
	$IBLOCK_ID = IntVal($_REQUEST['IBLOCK_ID']);
	if ($IBLOCK_ID > 0)
	{
		CModule::IncludeModule("iblock");
		$rsIBlocks = CIBlock::GetByID($IBLOCK_ID);
		if ($arIBlock = $rsIBlocks->Fetch())
		{
			$strPerm = CIBlock::GetPermission($IBLOCK_ID);
			if ($strPerm >= 'R')
			{
				echo "window.parent.Tree=new Array();";
				echo "window.parent.Tree[0]=new Array();";

				$bIBlock = true;
				$db_section = CIBlockSection::GetList(array("LEFT_MARGIN"=>"ASC"), array("IBLOCK_ID"=>$IBLOCK_ID));
				while ($ar_section = $db_section->Fetch())
				{
					$bNoTree = False;
					if (IntVal($ar_section["RIGHT_MARGIN"])-IntVal($ar_section["LEFT_MARGIN"])>1)
					{
						?>window.parent.Tree[<?echo IntVal($ar_section["ID"]);?>]=new Array();<?
					}
					?>window.parent.Tree[<?echo IntVal($ar_section["IBLOCK_SECTION_ID"]);?>][<?echo IntVal($ar_section["ID"]);?>]=Array('<?echo CUtil::JSEscape(htmlspecialchars($ar_section["NAME"]));?>', '');<?
				}
			}
		}
	}
	if ($bNoTree && !$bIBlock)
	{
		echo "window.parent.buildNoMenu();";
	}
	else
	{
		echo "window.parent.buildMenu();";
	}

	echo "</script>";
}
?>