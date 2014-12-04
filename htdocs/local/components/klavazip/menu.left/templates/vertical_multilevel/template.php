<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<style>
.catalogNav{
	display:none;
	position:absolute;
	left:0px; top:100%;
	background:#FFFFFF;
	width:198px;
	border-radius: 0 0 3px 3px; moz-border-radius: 0 0 3px 3px; webkit-border-radius: 0 0 3px 3px;
	behavior: url(css/PIE.htc);
	-moz-box-shadow:0 2px 3px 0 #5f5e5e;-webkit-box-shadow:0 2px 3px 0 #5f5e5e;box-shadow:0 2px 3px 0 #5f5e5e;
	padding:0 1px 0 1px;
	z-index:10000;}
.mainPage .catalogNav{
	display:block;}
.catalogNav > ul{
	padding:0 0 0 0;
	width:198px;
	position:relative;}
.catalogNav > ul > li{
	list-style-type:none;
	margin:0 0 0 0;
	line-height:13px;
	border-bottom:1px solid #f2f2f2;
	}
.catalogNav > ul > li.last{ border-bottom:none;}
.catalogNav > ul > li > a{
	display:block;
	font:13px Arial;
	color:#0d5da0;
	line-height:13px;
	text-decoration:none;
	padding:12px 13px 12px 19px;}
.catalogNav > ul > li:hover > a, .catalogNav > ul > li.active > a{color:#dc7374;}
.catalogNav > ul > li.withSubnav:hover > a, .catalogNav > ul > li.withSubnav.active > a{color:#dc7374; background:#fff2f2; border-right:4px solid #e36366; padding:12px 9px 12px 19px; width:167px;}


.subnavOpen{
	display:none;
	position:absolute;
	left:199px; top:-2px;
	background:#f4f4f4;
	width:760px; min-height:99.5%;
	border-top:2px solid #e36366;
	border-radius: 0 0 4px 0; -moz-border-radius: 0 0 4px 0; -webkit-border-radius: 0 0 4px 0;
	-moz-box-shadow:0 3px 3px 0px rgba(0,0,0,0.3);-webkit-box-shadow:0 3px 3px 0px rgba(0,0,0,0.3);box-shadow:0 3px 3px 0px rgba(0,0,0,0.3);	
	behavior: url(css/PIE.htc);
	z-index:1000;}
.catalogNav > ul > li:hover .subnavOpen{ display:block;}
.subnavOpen ul{
	padding:0 0 0 0;}
.subnavOpen ul li{
	list-style-type:none;
	padding: 9px 0 5px 0}
.subnavOpen ul li a{
	display:block;
	font:bold 12px/16px Arial;
	color:#0d5da0;
	text-decoration:none;}
.subnavOpen ul > li:hover > a{color:#dc7374;}
.subnavOpen ul li ul{
	padding:4px 0 0 10px;
	margin-bottom:-4px;}
.subnavOpen ul li ul li{
	list-style-type:none;
	padding: 0  0 4px 0}
.subnavOpen ul li ul li a{
	display:block;
	font:12px/16px Arial;
	color:#0d5da0;
	text-decoration:none;}
.subnavOpen ul li ul li a:hover{color:#dc7374;}
.oneColSubnav{
	float:left;
	padding:13px 0 0 20px;
	width:160px;}

</style>

<?if (!empty($arResult['menu'])):?>
<div class="catalogNav"  <?=($APPLICATION->GetCurDir() !== '/') ? 'style="display: none;"' : '' ?>>
<ul>

<?
$previousLevel = 0;
foreach($arResult['menu'] as $arItem):?>



	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"] || !empty($arResult['SUBSECTIONS'][$arItem["UF_SECTION_ID"]] )  ):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<li class="withSubnav"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a>
				<div class="subnavOpen">
				
					
				
				
				<div class="oneColSubnav">
				
			
				
				
				<ul >
				
				
					<?
					if (!empty($arResult['SUBSECTIONS'][$arItem["UF_SECTION_ID"]] )){?>
					
				
					<?
							
							$col_items=0;
							foreach($arResult['SUBSECTIONS'][$arItem["UF_SECTION_ID"]] as $subsect){
							$col_items++;
							?>
							
							<li><a href="<?=$subsect['SECTION_PAGE_URL']?>"><?=$subsect['NAME']?></a></li>
							
							
									<?
									if ($col_items>6){
									?>
										</ul>
										</div>
										
										<div class="oneColSubnav">
										<ul>
									
									
									<?
									$col_items=0;
									}
									
									?>
							
							
							<?
							}?>
					
					</ul>
				<?	}
				?>
				
				
				
				<?
				$col_items=0;
				?>
		<?else:?>
			<?
				$col_items++;
				?>
				<?
				if ($col_items>3){
				?>
					</ul>
					</div>
					
					<div class="oneColSubnav">
					<ul>
				
				
				<?
				$col_items=1;
				}
				
				?>
			<li><a href="<?=$arItem["LINK"]?>" class="parent<?if ($arItem["SELECTED"]):?> item-selected<?endif?>"><?=$arItem["TEXT"]?>   <?/*=$arItem["UF_PROPERTY_ID"]?>  <?=$arItem["UF_SECTION_ID"]*/?></a>
			
				<?
					if (!empty($arResult['props_by_section'][$arItem["UF_SECTION_ID"]][$arItem["UF_PROPERTY_ID"]])){
					
					?>
						<ul>
					
						<?
						
							foreach($arResult['props_by_section'][$arItem["UF_SECTION_ID"]][$arItem["UF_PROPERTY_ID"]] as $submenu){
							
								if ($submenu['LINK'] ){
							
							?>
							
							
							<li><a href="<?=$submenu['LINK']?>"><?=$submenu['NAME']?></a></li>
							
							
							<?
								}
							}
							?>

						</ul>
						<?
					}
				?>
				
				
				
				
				
				
				<ul>
		<?endif?>

	<?else:?>

	

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<?
				$col_items++;
				?>
				
				
				
				<li><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?>  </a></li> 
				
			<?else:?>
				<?
				$col_items++;
				?>
				
				<?if ($arItem["DEPTH_LEVEL"] == 2){?>
				
						<?
						if ($col_items>6 || (!empty($arResult['props_by_section'][$arItem["UF_SECTION_ID"]][$arItem["UF_PROPERTY_ID"]]) &&  $col_items>1)){
						?>
							</ul>
							</div>
							
							<div class="oneColSubnav">
							<ul>
						
						
						<?
						$col_items=1;
						}
						
						?>
				<?}?>
				<li><a href="<?=$arItem["LINK"]?>" <?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><?=$arItem["TEXT"]?> </a>
				
				<?
					if (!empty($arResult['props_by_section'][$arItem["UF_SECTION_ID"]][$arItem["UF_PROPERTY_ID"]])){
					
					?>
						<ul>
					
						<?
						
							foreach($arResult['props_by_section'][$arItem["UF_SECTION_ID"]][$arItem["UF_PROPERTY_ID"]] as $submenu){
							
								if ($submenu['LINK'] ){
							
							?>
							
							
							<li><a href="<?=$submenu['LINK']?>"><?=$submenu['NAME']?></a></li>
							
							
							<?
								}
							}
							?>

						</ul>
						<?
					}
				?>
				
				
				
				</li>
				
							
				
				
			<?endif?>
			
			

		

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel ==2)://close last item tags?>
	<?=str_repeat("</ul></div></div></li>", ($previousLevel-1) );?>
<?endif?>

<?if ($previousLevel ==3)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</ul>
</div>
<?endif?>