<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>


<?/*
<div class="catalogNav" <?=($APPLICATION->GetCurDir() !== '/') ? 'style="display: none;"' : '' ?>>

	<?
	function subMenuRecursion($ar_Section)
	{
		$s_ResultString = null;
		foreach ($ar_Section as $ar_Value)
		{
			if (is_array($ar_Value['CHILDS']))
			{
				usort($ar_Value['CHILDS'], "__sectionSort");
				$s_ResultString .= '<li onmouseover="klava.leftmenu.showSubMenu(\'sub_menu_'.$ar_Value['ID'].'\', this);"><a data-dep="'.$ar_Value['DEPTH_LEVEL'].'" href="'.$ar_Value['SECTION_PAGE_URL'].'">'.$ar_Value['NAME'].'<i></i></a><ul class="js-sub-section seb-secton-depth-'.$ar_Value['DEPTH_LEVEL'].'" id="sub_menu_'.$ar_Value['ID'].'">'.subMenuRecursion($ar_Value['CHILDS']).'</ul></li>';
			}
			else
			{
				$s_ResultString .= '<li><a href="'.$ar_Value['SECTION_PAGE_URL'].'">'.$ar_Value['NAME'].'</a></li>';
			}
		}
		
		return $s_ResultString;
	}
	?>
	
	<ul>
		<? 
		foreach ($arResult['SECTION'] as $ar_Value)
		{
			$b_SubSection = (isset($ar_Value['CHILDS']) && count($ar_Value['CHILDS']) > 0);
			?>
			<li class="js-sub-section"
				<? 
				if($b_SubSection)
				{
					?>onmouseover="klava.leftmenu.showSubMenu('sub_menu_<?=$ar_Value['ID']?>', this); return false;"<?
				}
				
				?>
				data-dep="<?=$ar_Value['DEPTH_LEVEL']?>"
				>
				<a href="<?=$ar_Value['SECTION_PAGE_URL']?>">
						<?=$ar_Value['NAME']?>
					
					<? 
					if($b_SubSection)
					{
						?><b>»</b><?
					}
					?>
					
				</a>
				
				<? 
				
				if(isset($ar_Value['CHILDS']) && count($ar_Value['CHILDS']) > 0)
				{
					usort($ar_Value['CHILDS'], "__sectionSort");
					?><ul class="js-sub-section seb-secton-depth-<?=$ar_Value['DEPTH_LEVEL']?>" id="sub_menu_<?=$ar_Value['ID']?>" style="display: none;"><?
						echo subMenuRecursion($ar_Value['CHILDS']);
					?></ul><?
				}
				?>
				
			</li>
			<?
		}
		?>
		
		<li class="last"> &nbsp </li>
	</ul>

		
</div>
*/?>

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

	<div class="catalogNav">
					<ul>
						<li><a href="#">Акции</a></li>
						<li class="withSubnav"><a href="#">Аккумуляторы по<br/> моделям</a>
							<div class="subnavOpen">
								<div class="oneColSubnav">
									<ul>
										<li><a href="#">Аккумуляторные ячейки</a></li>
										<li><a href="#">Видеокарты</a>
											<ul>
												<li><a href="#">Ati</a></li>
												<li><a href="#">NVidia</a></li>
											</ul>
										</li>
										<li><a href="#">Кабели</a></li>
										<li><a href="#">Переходники</a></li>
										<li><a href="#">Корпусные детали</a></li>
										<li><a href="#">Разъемы</a></li>
										<li><a href="#">Разъемы питания</a></li>
									</ul>
								</div>
								<div class="oneColSubnav">
									<ul>										
										<li><a href="#">Шлейфы для матриц</a>
											<ul>
												<li><a href="#">ACER</a></li>
												<li><a href="#">APPLE</a></li>
												<li><a href="#">ASUS</a></li>
												<li><a href="#">COMPAQ</a></li>
												<li><a href="#">DELL</a></li>
												<li><a href="#">Fujitsu</a></li>
												<li><a href="#">GATEWAY</a></li>
												<li><a href="#">HP</a></li>
												<li><a href="#">IBM</a></li>
												<li><a href="#">LENOVO</a></li>
												<li><a href="#">MSI</a></li>
												<li><a href="#">SAMSUNG</a></li>
												<li><a href="#">SONY</a></li>
												<li><a href="#">TOSHIBA</a></li>
											</ul>
										</li>										
									</ul>
								</div>
								<div class="oneColSubnav">
									<ul>										
										<li><a href="#">Петли для матриц</a>
											<ul>
												<li><a href="#">ACER</a></li>
												<li><a href="#">ASUS</a></li>
												<li><a href="#">HP</a></li>
												<li><a href="#">IBM</a></li>
												<li><a href="#">LENOVO</a></li>
												<li><a href="#">Toshiba</a></li>												
											</ul>
										</li>										
									</ul>
								</div>
								<div class="oneColSubnav">
									<ul>										
										<li><a href="#">Системы охлаждения</a>
											<ul>
												<li><a href="#">Acer</a></li>
												<li><a href="#">Apple</a></li>
												<li><a href="#">Asus</a></li>
												<li><a href="#">Dell</a></li>
												<li><a href="#">Fujitsu</a></li>
												<li><a href="#">Gateway</a></li>
												<li><a href="#">Toshiba</a></li>
												<li><a href="#">HP</a></li>
												<li><a href="#">IBM</a></li>
												<li><a href="#">Lenovo</a></li>
												<li><a href="#">Другие</a></li>
												<li><a href="#">MSI</a></li>
												<li><a href="#">Samsung</a></li>												
											</ul>
										</li>										
									</ul>
								</div>
								<div class="clear"></div>
							</div>
						</li>
						<li><a href="#">Аккумуляторы по<br/> ноутбукам</a></li>
						<li><a href="#">Микросхемы</a></li>
						<li><a href="#">Видеокарты</a></li>
						<li><a href="#">Клавиатуры</a></li>
						<li><a href="#">Кабели</a></li>
						<li><a href="#">Матрицы</a></li>
						<li><a href="#">Переходники</a></li>
						<li><a href="#">Корпусные детали</a></li>
						<li><a href="#">Разъемы</a></li>
						<li class="last"><a href="#">Системы охлаждения</a></li>
					</ul>
				</div>
