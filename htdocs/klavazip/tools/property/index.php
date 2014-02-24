<? 	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("klavazip admin panel");
	?>
	<table class="table table-bordered table-hover">
		<tr class="warning">
			<th>ID</th>
			<th>Название</th>
			<th>Символьный код</th>
			<th>Тип</th>
		</tr>
		<? 	
		$i_Count = 0;
		$properties = CIBlockProperty::GetList(array('name' => 'asc'), Array("IBLOCK_ID" => 8));
		while ($prop_fields = $properties->GetNext())
		{
			?>
			<tr>
				<td><?=$prop_fields["ID"]?></td>
				<td><?=$prop_fields["NAME"]?></td>
				<td><?=$prop_fields["CODE"]?></td>
				<td><?=$prop_fields["PROPERTY_TYPE"]?></td>
			</tr>
			<?
			$i_Count++;
		}
		?>
		<tr>
			<td colspan="3">
				Кол-во: <b><?=$i_Count?></b>
			</td>
		</tr>
	</table>
	<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");