<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");



$s_Code = $_POST['code'];

if($s_Code !== 'CITY')
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/lib/kladr.php");
	$api = new Kladr\Api(KLADR_KEY_1, KLADR_KEY_2);
}


if($s_Code == 'CITY')
{
	$rs_City = CSaleLocation::GetList(array(), array("LID" => LANGUAGE_ID, "%CITY_NAME" => $_POST['s']), false, false, array());
	
	if( $rs_City->SelectedRowsCount() > 0)
	{
		ob_start();
	
		while ($ar_City = $rs_City->Fetch())
		{
			?>
				<a 
					href="#" 
					data-delevery="<?=$_POST['delevery']?>"
					data-id="<?=$ar_City['ID']?>"
					data-type="city"
					data-val="<?=$ar_City['CITY_NAME']?>"
					onclick="klava.order.setLocation(this); return false;"
					>
						<?=$ar_City['CITY_NAME']?>  
				</a>
				<?
			
			}
			
			
		$html = ob_get_contents();
		ob_end_clean();
		
		echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
	}
	else 
		echo CUtil::PhpToJSObject(array('st' => 'error'));
}
else if( $s_Code == 'STREET' || $s_Code == 'USTREET' )
{
	
	// Формирование запроса
	$queryCity = new Kladr\Query();
	$queryCity->ContentName = $_POST['cityName'];
	$queryCity->ContentType = Kladr\ObjectType::City;
	$queryCity->WithParent = false;
	$queryCity->Limit = 1;
	
	// Получение данных в виде ассоциативного массива
	$ar_ResultCity = $api->QueryToArray($queryCity);
	
	if(count($ar_ResultCity) > 0)
	{
		$queryStreet = new Kladr\Query();
		$queryStreet->ContentName = trim($_POST['s']);;
		$queryStreet->ParentId = $ar_ResultCity[0]['id'];
		$queryStreet->ContentType = Kladr\ObjectType::Street;
		$queryStreet->ParentType = Kladr\ObjectType::City;
		$queryStreet->WithParent = false;
		$queryStreet->Limit = 10;

		$ar_ResultStreet = $api->QueryToArray($queryStreet);
	
	}
	
	if(count($ar_ResultStreet) > 0)
	{
		ob_start();
	
		foreach ($ar_ResultStreet as $ar_Value)
		{
			?>
					<a 
						href="#" 
						data-delevery="<?=$_POST['delevery']?>"
						data-id="<?=$ar_Value['id']?>"
						data-type="street"
						data-val="<?=$ar_Value['name']?>"
						onclick="klava.order.setLocation(this); return false;"
						>
							<?=$ar_Value['name']?> <span>[<?=$ar_Value['type']?>]</span> 
					</a>
					<?
				}
			
			$html = ob_get_contents();
			ob_end_clean();
			
			echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
		}
		else 
			echo CUtil::PhpToJSObject(array('st' => 'error'));
	
}	
else
{	
	$query = new Kladr\Query();
	$query->ContentName = trim($_POST['s']);
	
	$s_Code = $_POST['code'];
	
	switch (true)
	{
		case ($s_Code == 'REGION' ||$s_Code == 'UREGION'):
			$s_Type = Kladr\ObjectType::Region;
			break;
	
		case ($s_Code == 'RAION' || $s_Code == 'URAION') :
			$query->ParentType = Kladr\ObjectType::Region;
			$query->ParentId = intval($_POST['id']);
			$s_Type = Kladr\ObjectType::District;
	
			break;
			
			
		case ($s_Code == 'UCITY'):
			$s_Type = Kladr\ObjectType::City;
			$query->ParentId = intval($_POST['id']);
			$query->ParentType = Kladr\ObjectType::District;
		
			break;
			
	}
	
	$query->ContentType = $s_Type;
	$query->Limit = 10;
	
	$ar_Result = $api->QueryToArray($query);
	
	
	if(count($ar_Result) > 0)
	{
		ob_start();
	
		foreach ($ar_Result as $ar_Value)
		{
			?>
				<a 
					href="#" 
					data-delevery="<?=$_POST['delevery']?>"
					data-id="<?=$ar_Value['id']?>"
					data-type="<?=$s_Type?>"
					data-val="<?=$ar_Value['name']?>"
					onclick="klava.order.setLocation(this); return false;"
					>
						<?=$ar_Value['name']?> <span>[<?=$ar_Value['type']?>]</span> 
				</a>
				<?
			}
		
		$html = ob_get_contents();
		ob_end_clean();
		
		echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
	}
	else 
		echo CUtil::PhpToJSObject(array('st' => 'error'));
	
}






//city_name


/*

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/lib/kladr.php");


$api = new Kladr\Api('528346f031608ff225000021', '80f42c5af90206bbcf1629e5c338d72a9951c659');

$query = new Kladr\Query();
$query->ContentName = trim($_POST['s']);

$s_Code = $_POST['code'];

switch (true)
{
	case ($s_Code == 'REGION' ||$s_Code == 'UREGION'):
		$s_Type = Kladr\ObjectType::Region;
		break;

	case ($s_Code == 'RAION' || $s_Code == 'URAION') :
		$query->ParentType = Kladr\ObjectType::Region;
		$query->ParentId = intval($_POST['id']);		
		$s_Type = Kladr\ObjectType::District;
		
		break;
	
	case ($s_Code == 'CITY' || $s_Code == 'UCITY'):
		$s_Type = Kladr\ObjectType::City;
		$query->ParentId = intval($_POST['id']);
		$query->ParentType = Kladr\ObjectType::District;
		
		break;
	
	case ($s_Code == 'STREET' || $s_Code == 'USTREET'):
		$s_Type = Kladr\ObjectType::Street;
		$query->ParentId = intval($_POST['id']);
		$query->ParentType = Kladr\ObjectType::City;

		break;
}




$query->ContentType = $s_Type;
//$query->WithParent = true;
$query->Limit = 10;

$ar_Result = $api->QueryToArray($query);


if(count($ar_Result) > 0)
{
	ob_start();
	
		foreach ($ar_Result as $ar_Value)
		{
			?>
			<a 
				href="#" 
				data-delevery="<?=$_POST['delevery']?>"
				data-id="<?=$ar_Value['id']?>"
				data-type="<?=$s_Type?>"
				data-val="<?=$ar_Value['name']?>"
				onclick="klava.order.setLocation(this); return false;"
				>
					<?=$ar_Value['name']?> <span>[<?=$ar_Value['type']?>]</span> 
			</a>
			<?
		}
	
	$html = ob_get_contents();
	ob_end_clean();
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
}
else 
	echo CUtil::PhpToJSObject(array('st' => 'error'));

*/