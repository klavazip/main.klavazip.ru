<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Карта сайта");?>
<div class="margin-bottom"></div>
<div class="page-title">
  <h1>Карта сайта</h1>
</div>
<div class="content-bg2">
  <table style="width:100%" cellpadding="0" cellspacing="0" class="sitemap">
    <tr>
      <td style="width:50%">
      <h3 class="sitemap-title">Каталог</h3>

		<?

			global $arFilter;

			$arFilter = array("IBLOCK_ID"=>"8");

			function getStruct($DEPTH_LEVEL,$SECTION_ID)

			{

				global $arFilter;

				$arFilter["DEPTH_LEVEL"]=$DEPTH_LEVEL;

				$arFilter["SECTION_ID"]=isset($SECTION_ID)?$SECTION_ID:'';

				$catSects = CIBlockSection::GetList( array ("SORT"=>"ASC"), $arFilter ,false, false );

				if($catSects->SelectedRowsCount())

					while($ar_result = $catSects->GetNext())

					{
						if($DEPTH_LEVEL==1){?><p class='myp'><a href="<?=$ar_result["SECTION_PAGE_URL"]?>"><?=$ar_result["NAME"]?></a></p><ul><?}
						else {?><li><a href="<?=$ar_result["SECTION_PAGE_URL"]?>"><?=$ar_result["NAME"]?></a></li><?}
						

						getStruct($DEPTH_LEVEL+1,$ar_result["ID"]);
						

						if($DEPTH_LEVEL==1){?></ul><?}

					}

			}

			?>
	
	     <div style="width:50%" class="floatleft floatleft1">
	      
	     </div>
	     <div style="width:50%" class="floatleft">
	     		<? @getStruct(1,NULL); ?>
	     </div>
        
      </td>
      <td style="width:50%">
      	<h3 class="sitemap-title" style="border-color:#fff">&nbsp;</h3>
        <div style="width:50%" class="floatleft">
        		<?$APPLICATION->IncludeComponent("bitrix:main.map", ".default", array(
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"SET_TITLE" => "Y",
					"LEVEL" => "4",
					"COL_NUM" => "1",
					"SHOW_DESCRIPTION" => "Y"
					),
					false
				);?>
        </div>
        <div style="width:50%" class="floatleft">
        	 <?if(!$USER->IsAuthorized()) {?>
        	 	<p><a href="/login/">Вход</a></p>
         	<p><a href="/login/?register=yes">Регистрация</a></p>
          <?}else{?><p><a href="/personal/">Личный кабинет</a></p><?}?>
          <p>&nbsp</p>
        	 
          <p><a target="_blank" href="http://vk.com/club20242050">Мы вконтакте</a></p>
          <p><a target="_blank" href="http://www.facebook.com/groups/346072022080941/">Мы на фейсбуке</a></p>
          <p><a target="_blank" href="https://twitter.com/#!/KlavaZip">Мы в твиттере</a></p>
			 <p><a target="_blank" href="http://klavazip.livejournal.com/ ">Мы в ЖЖ</a></p>
        </div>
      
      </td>
    </tr>
  </table>
</div>
<script type="text/javascript" >
	$(document).ready(function(){
		$('.myp').each(function(){
			if($(this).find('a').html()=='Матрицы')
			{
				$('.floatleft1').html('<p>'+$(this).html()+'</p><ul>'+$(this).next().html()+'</ul>');
				$(this).next().remove();
				$(this).remove();
				$('.map-level-0 a').css({fontWeight: 'normal'});
			}
		});
		$('.map-level-0 div').remove();
		
	})
</script>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>