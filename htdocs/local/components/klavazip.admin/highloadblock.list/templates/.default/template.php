<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

//$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/js/highloadblock/css/highloadblock.css');

//$GLOBALS['APPLICATION']->SetTitle('Highloadblock List');


//echo '<pre>', print_r($arResult).'</pre>';
?>



<table class="table table-bordered table-hover">
	<tr class="warning">
		<th>Старая ссылка</th>
		<th>Новая ссылка</th>
	</tr>
	<? 
	foreach ($arResult['ITEMS'] as $ar_Value)
	{
		?>
		<tr>
			<td><?=$ar_Value['UF_OLD_LINK']?></td>
			<td><?=$ar_Value['UF_NEW_LINK']?></td>
		</tr>
		<?		
	}
	?>
</table>


<? /*?>

<div class="reports-result-list-wrap">
<div class="report-table-wrap">
<div class="reports-list-left-corner"></div>
<div class="reports-list-right-corner"></div>
<table cellspacing="0" class="reports-list-table" id="report-result-table">
	<!-- head -->
	<tr>
		<? $i = 0; foreach(array_keys($arResult['tableColumns']) as $col): ?>
		<?
		$i++;

		if ($i == 1)
		{
			$th_class = 'reports-first-column';
		}
		else if ($i == count($arResult['viewColumns']))
		{
			$th_class = 'reports-last-column';
		}
		else
		{
			$th_class = 'reports-head-cell';
		}

		// title
		$arUserField = $arResult['fields'][$col];
		$title = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $col;

		// sorting
		$defaultSort = 'DESC';
		//$defaultSort = $col['defaultSort'];

		if ($col === $arResult['sort_id'])
		{
			$th_class .= ' reports-selected-column';

			if($arResult['sort_type'] == 'ASC')
			{
				$th_class .= ' reports-head-cell-top';
			}
		}
		else
		{
			if ($defaultSort == 'ASC')
			{
				$th_class .= ' reports-head-cell-top';
			}
		}

		?>
		<th class="<?=$th_class?>" colId="<?=htmlspecialcharsbx($col)?>" defaultSort="<?=$defaultSort?>">
			<div class="reports-head-cell"><?if($defaultSort):
				?><span class="reports-table-arrow"></span><?
			endif?><span class="reports-head-cell-title"><?=htmlspecialcharsex($title)?></span></div>
		</th>
		<? endforeach; ?>
	</tr>

	<!-- data -->
	<? foreach ($arResult['rows'] as $row): ?>
	<tr class="reports-list-item">
		<? $i = 0; foreach(array_keys($arResult['tableColumns']) as $col): ?>
		<?
		$i++;
		if ($i == 1)
		{
			$td_class = 'reports-first-column';
		}
		else if ($i == count($arResult['viewColumns']))
		{
			$td_class = 'reports-last-column';
		}
		else
		{
			$td_class = '';
		}

		//if (CReport::isColumnPercentable($col))
		if (false) // numeric rows
		{
			$td_class .= ' reports-numeric-column';
		}

		$finalValue = $row[$col];

		if ($col === 'ID')
		{
			$url = str_replace(
				array('#ID#', '#BLOCK_ID#'),
				array($finalValue, intval($arParams['BLOCK_ID'])),
				$arParams['DETAIL_URL']
			);

			$finalValue = '<a href="'.htmlspecialcharsbx($url).'">'.$finalValue.'</a>';
		}

		?>
		<td class="<?=$td_class?>"><?=$finalValue?></td>
		<? endforeach; ?>
	</tr>
	<? endforeach; ?>

</table>

<?php echo $arResult["NAV_STRING"]?>


<form id="hlblock-table-form" action="" method="get">
	<input type="hidden" name="BLOCK_ID" value="<?=htmlspecialcharsbx($arParams['BLOCK_ID'])?>">
	<input type="hidden" name="sort_id" value="">
	<input type="hidden" name="sort_type" value="">
</form>

<script type="text/javascript">
	BX.ready(function(){
		var rows = BX.findChildren(BX('report-result-table'), {tag:'th'}, true);
		for (i in rows)
		{
			var ds = rows[i].getAttribute('defaultSort');
			if (ds == '')
			{
				BX.addClass(rows[i], 'report-column-disabled-sort')
				continue;
			}

			BX.bind(rows[i], 'click', function(){
				var colId = this.getAttribute('colId');
				var sortType = '';

				var isCurrent = BX.hasClass(this, 'reports-selected-column');

				if (isCurrent)
				{
					var currentSortType = BX.hasClass(this, 'reports-head-cell-top') ? 'ASC' : 'DESC';
					sortType = currentSortType == 'ASC' ? 'DESC' : 'ASC';
				}
				else
				{
					sortType = this.getAttribute('defaultSort');
				}

				var idInp = BX.findChild(BX('hlblock-table-form'), {attr:{name:'sort_id'}});
				var typeInp = BX.findChild(BX('hlblock-table-form'), {attr:{name:'sort_type'}});

				idInp.value = colId;
				typeInp.value = sortType;

				BX.submit(BX('hlblock-table-form'));
			});
		}
	});
</script>

</div>
</div>

<?*/?>