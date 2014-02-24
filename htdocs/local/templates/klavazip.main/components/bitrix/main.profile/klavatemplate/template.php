<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
//echo "<pre>"; print_r($arResult); echo "</pre>";
//exit();
//echo "<pre>"; print_r($_SESSION); echo "</pre>";

?>
<?=ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
	echo ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>

<style type="text/css">
 .only-yur {display:none;}
 </style>
<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>?" enctype="multipart/form-data" style='margin:0 -20px'>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />

<table class="order-table" cellpadding="0" cellpadding="0">
    	<? /* ?>	<tr>
            	<td class="o1">
                </td>
                <td class="o2">
                	<div class="user-type floatleft">
                        <label class="show-fiz" style="margin-right:30px"><input <?//if ($arResult["arUser"]["UF_PERSON_TYPE"]!=2 ) echo "checked"?> type="radio" name="UF_PERSON_TYPE" value="1"> Физическое лицо</label>
                        <label class="show-yur"><input type="radio" <?//if ($arResult["arUser"]["UF_PERSON_TYPE"]==2) echo "checked" ?>  name="UF_PERSON_TYPE" value="2"> Юридическое лицо</label>
                        <?//if ($arResult["arUser"]["UF_PERSON_TYPE"]==2) echo '<script>$(document).ready(function(){$(".show-yur").click()});</script>' ?>
                    </div>
                </td>
         </tr><? */ ?>
            <tr>
            	<td class="o1">
                	<p>Логин*</p>
                </td>
                <td class="o2">
                	<div class="relative">
                    <p><input type="text" class="text-input w240" name="LOGIN" readonly="readonly" value="<?=$arResult["arUser"]["LOGIN"]?>"><br><span class="tip">не менее 3 символов</span></p>
                    
                    <a href="#" class="change-pass">Изменить пароль</a>
                    
                    </div>
                </td>
            </tr>
            <tr>
            	<td class="o1">
                	<p>Email*</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w240" name="EMAIL" value="<?=$arResult["arForumUser"]["EMAIL"]?>"><span class="wrong-label">поле заполнено неправильно</span><span class="true-label"></span></p>
                </td>
            </tr>
            
            <tr class="only-yur">
            	<td class="o1 italic">
                	<p>Название компании</p>
                </td>
                <td class="o2">
                	<div class="relative" style="min-width: 590px">
                    	<p><input type="text" class="text-input w350" name="WORK_COMPANY" value="<?=$arResult["arUser"]["WORK_COMPANY"]?>"></p>
                    	<!--<div class="company-card">
                        	<p>Загрузить карточку предприятия</p>
                            <p><input type="file" name="UF_CART" size="10"></p>
                        </div>-->
                    </div>
                </td>
            </tr>
            <tr class="only-yur">
            	<td class="o1 italic">
                	<p>Юридический адрес</p>
                </td>
                <td class="o2">
                	<p><textarea style="height:60px" type="text" name="WORK_STREET" class="textarea w350" value="<?=$arResult["arUser"]["WORK_STREET"]?>"></textarea></p>
                </td>
            </tr>
            <tr class="only-yur">
            	<td class="o1 italic b3">
                	<p>Контактное лицо</p>
                </td>
                <td class="o2">
                	<p></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>Имя*</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w350" name="NAME" value="<?=$arResult["arUser"]["NAME"]?>"><span class="wrong-label">поле заполнено неправильно</span><span class="true-label"></span></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>Фамилия*</p>
                </td>
                <td class="o2">
                	<p><input type="text" class=" text-input w350" name="LAST_NAME" value="<?=$arResult["arUser"]["LAST_NAME"]?>"><span class="wrong-label">поле заполнено неправильно</span><span class="true-label"></span></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>Отчество</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w350" name="SECOND_NAME" value="<?=$arResult["arUser"]["SECOND_NAME"]?>"></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>ICQ</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w350" name="PERSONAL_ICQ" value="<?=$arResult["arUser"]["PERSONAL_ICQ"]?>"></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>Контактный телефон</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w240" name="WORK_PHONE" value="<?=$arResult["arUser"]["WORK_PHONE"]?>"></p> 
                </td>
            </tr>
            <tr class="only-yur">
            	<td class="o1 italic">
                	<p>Факс</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w240" name="WORK_FAX" value="<?=$arResult["arUser"]["WORK_FAX"]?>"></p>
                </td>
            </tr>
            <tr class="only-yur">
            	<td class="o1 italic">
                	<p>ИНН</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w240" name="UF_INN" value="<?=$arResult["arUser"]["UF_INN"];?>"></p>
                </td>
            </tr>
            <tr class="only-yur">
            	<td class="o1 italic">
                	<p>КПП</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w240" name="UF_KPP" value="<?=$arResult["arUser"]["UF_KPP"];?>"></p>
                </td>
            </tr>
            <?php /*<tr>
            	<td class="o1 italic">
                	<p class="b3">Адрес доставки</p>
                </td>
                <td class="o2">
                	<p></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>Индекс</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input" style="width:100px" name="WORK_ZIP" value="<?=$arResult["arUser"]["WORK_ZIP"];?>"></p>
                </td>
            </tr> 
            <tr>
            	<td class="o1 italic">
                	<p>Адрес без индекса*</p>
                </td>
                <td class="o2">
                	<p><textarea type="text" class="textarea w350" name="WORK_STREET"><?=$arResult["arUser"]["WORK_STREET"];?></textarea><span class="wrong-label">поле заполнено неправильно</span><span class="true-label"></span></p>
                </td>
            </tr>
            <tr>
            	<td class="o1">
                </td>
                <td class="o2">
                	<p><label><input type="checkbox"> Получать СМС-уведомления о состоянии заказа</label></p>
                </td>
            </tr>
            <tr>
            	<td class="o1">
                </td>
                <td class="o2">
                	<p><label><input type="checkbox"> Согласен с условиями заказа</label></p>
                </td>
            </tr>*/?>
    	</table>
        <p class="center"><span class="button-l"><span class="button-r"><input type="submit" class="button" name="save" value="сохранить изменения" /></span></span></p>
        
 <?//print_r($arResult[arBlogUser]);?>
</form>
<? /*
$error = "";
	$msg = "";
	$fileElementName = 'UF_CART';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}else 
	{
		$File = $_FILES["UF_CART"];
		$File["del"]="Y";
		$File["MODULE_ID"]="user";
		
		$res=CFile::SaveFile($File,"user");	
				   
		$rsUser = CUser::GetByID($USER->GetId());
		$arUser = $rsUser->Fetch();
		$old = $arUser["UF_CART"];
		if ($old>0 )CFile::Delete($old);
		$USER->Update($USER->GetId(),Array("UF_CART"=>$res));
		
		$id = $USER->LAST_ERROR;
		$src = CFile::GetPath($res);		
	}
	
	?>
<script type="text/javascript" >
$(document).ready(
function ()
{
	$('[name="save"]').live('click',function(){
		var errors=[];
		$('[name=form1] table tr:visible').each(function(){
		    $td1=$(this).find('td').eq(0);
		    var fieldName='';
		    var fieldVal='';
		    if($td1.html().indexOf('*')>0)
		    {
		        fieldName = $td1.find('p').html().replace('*','');
		
		        $ta=$(this).find('textarea');
		        $inp=$(this).find('input');
		        
		        if($ta.length) fieldVal=$ta.val();
		        if($inp.length) fieldVal=$inp.val();
		
		        if(($ta.length||$inp.length)&&$.trim(fieldVal).length==0)
		            errors.push(fieldName);
		    }
		    
		})
		if(errors.length)
		{
		    alert('Заполните поля: "'+errors.join('", "')+'".');
		    return false;
		}
		else
		    return true;
	})
	
	<?
	global $USER;
	$arGroups = CUser::GetUserGroup($USER->GetId());
	if(isset($_POST['UF_PERSON_TYPE']))
	{
		foreach($arGroups as $grk=>$grp) if($grp==9||$grp==10) unset($arGroups[$grk]);
		$grId=$_POST['UF_PERSON_TYPE'];
		$arGroups[]=$_POST['UF_PERSON_TYPE']==1?9:10;
		$user = new CUser;
		$user->Update($USER->GetId(), array( "GROUP_ID" => $arGroups));
		$strError .= $user->LAST_ERROR;
	}
	else
		$grId=in_array('10',$arGroups)?'2':'1';?>
	$('[name=UF_PERSON_TYPE][value=<?=$grId?>]').click();
	
	$('.order-table tr:has(.wrong-label)').each(function(){ $(this).find('input, textarea').live('input',function(){ checkTr($(this).parents('tr:first')) }) });
});

var mailRegExp=/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
function checkTr($tr){
	$inp = $tr.find('input, textarea');
	var v1=$inp.val();
    var wrong=false;
    switch($inp.attr('name'))
    {
        case 'EMAIL':
            wrong=!mailRegExp.test(v1);
            break;
        case 'NAME':
            wrong=$.trim(v1).length==0;
            break;
        case 'LAST_NAME':
            wrong=$.trim(v1).length==0;
            break;
		case 'WORK_STREET':
            wrong=$.trim(v1).length==0;
            break;
		default:
			return;
    }
    if(wrong) $tr.removeClass('reg-table-right-tr').addClass('reg-table-wrong-tr');
    else $tr.removeClass('reg-table-wrong-tr').addClass('reg-table-right-tr');
    return wrong;
}
</script><? */?>