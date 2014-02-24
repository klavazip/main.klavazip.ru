<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>



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
