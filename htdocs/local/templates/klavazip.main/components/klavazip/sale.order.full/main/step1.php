<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	<div class="itemsRadio">
		<?
		foreach($arResult["PERSON_TYPE_INFO"] as $ar_Value)
		{
			if( ! isset($ar_Value["CHECKED"]) )
			{
				if($arResult['CURRENT_TYPE'] == $ar_Value['ID'] && count($_POST) == 0)
					$ar_Value['CHECKED'] = 'Y';
			}
			?>
			<div class="lineRadio">
				<input type="radio" id="PERSON_TYPE_<?=$ar_Value["ID"]?>" class="styledRadio radioButton" name="PERSON_TYPE" value="<?=$ar_Value["ID"]?>" <?if ($ar_Value["CHECKED"]=="Y") echo " checked";?> />
				<label for="PERSON_TYPE_<?= $ar_Value["ID"] ?>"><?= $ar_Value["NAME"] ?></label>								
				<div class="clear"></div>
			</div>
			<?
		}
		?>
		<div class="clear"></div>
	</div>