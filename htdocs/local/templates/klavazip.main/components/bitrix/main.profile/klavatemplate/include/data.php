 <style type="text/css">
 .only-yur {display:none;}
 </style>
<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>?" enctype="multipart/form-data">
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
<table class="order-table" cellpadding="0" cellpadding="0">
    		<tr>
            	<td class="o1">
                </td>
                <td class="o2">
                	<div class="user-type floatleft">
                        <label class="show-fiz" style="margin-right:30px"><input <?if ($arResult["arUser"]["UF_PERSON_TYPE"]!=2 ) echo "checked"?> type="radio" name="UF_PERSON_TYPE" value="1"> Физическое лицо</label>
                        <label class="show-yur"><input type="radio" <?if ($arResult["arUser"]["UF_PERSON_TYPE"]==2) echo "checked" ?>  name="UF_PERSON_TYPE" value="2"> Юридическое лицо</label>
                    </div>
                </td>
            </tr>
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
                	<p><input type="text" class="text-input w240" name="EMAIL" value="<?=$arResult["arForumUser"]["EMAIL"]?>"></p>
                </td>
            </tr>
            
            <tr class="only-yur">
            	<td class="o1 italic">
                	<p>Название компании*</p>
                </td>
                <td class="o2">
                	<div class="relative">
                    	<p><input type="text" class="text-input w350" name="WORK_COMPANY" value="<?=$arResult["arUser"]["WORK_COMPANY"]?>"></p>
                    	<div class="company-card">
                        	<p>Загрузить карточку предприятия</p>
                            <p><input type="file" name="UF_CART" size="10"></p>
                        </div>
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
                	<p><input type="text" class="text-input w350" name="NAME" value="<?=$arResult["arUser"]["NAME"]?>"></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>Фамилия*</p>
                </td>
                <td class="o2">
                	<p><input type="text" class=" text-input w350" name="LAST_NAME" value="<?=$arResult["arUser"]["LAST_NAME"]?>"></p>
                </td>
            </tr>
            <tr>
            	<td class="o1 italic">
                	<p>Отчество*</p>
                </td>
                <td class="o2">
                	<p><input type="text" class="text-input w350" name="SECOND_NAME" value="<?=$arResult["arUser"]["SECOND_NAME"]?>"></p>
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
            <tr>
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
                	<p><textarea type="text" class="textarea w350" name="WORK_STREET" value="<?=$arResult["arUser"]["WORK_STREET"];?>"></textarea></p>
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
            </tr>
    	</table>
        <p class="center"><span class="button-l"><span class="button-r"><input type="submit" class="button" name="save" value="сохранить изменения"></span></span></p>
        <?echo '<div style="display:none; visibility:hidden;">';print_r($arResult); echo "</div>";?>
 <?//print_r($arResult[arBlogUser]);?>
</form>      
 <?
 	/*echo "<pre>";
	print_r($arResult);
	echo "</pre>";*/
 ?>      



