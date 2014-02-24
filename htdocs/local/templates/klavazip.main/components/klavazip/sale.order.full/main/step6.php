<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="oneBlockStepInf">
	<h4><b>Заказ сформирован</b></h4>
</div>


<div class="final-order-step">
	
	<table>
		<tr>
			<td valign="top" width="60%">
			
				Заказ сформирован номер вашего заказа <b><?=$arResult["ORDER_ID"]?></b> <br /> 
				
				<?//=GetMessage("STOF_CONTACT_ADMIN")?>
			
				<?
				if (!empty($arResult["ORDER"]))
				{
					if (!empty($arResult["PAY_SYSTEM"]))
					{
						?>
						<b><?//echo GetMessage("STOF_ORDER_PAY_ACTION")?></b><br /><br />
		
						<table class="sale_order_full_table">
							<tr>
								<td>
									<?echo GetMessage("STOF_ORDER_PAY_ACTION1")?> <?= $arResult["PAY_SYSTEM"]["NAME"] ?>
								</td>
							</tr>
							<?
							if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
							{
								?>
								<tr>
									<td>
										<?
										if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
										{
											?>
											<script language="JavaScript">
												window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?= $arResult["ORDER_ID"] ?>');
											</script>
											<?= str_replace("#LINK#", $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$arResult["ORDER_ID"], GetMessage("STOF_ORDER_PAY_WIN")) ?>
											<?
										}
										else
										{
											if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
											{
												include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
											}
										}
										?>
									</td>
								</tr>
								<?
							}
							?>
						</table>
						<?
					}
				}
				else
				{
					?>
					<b><?echo GetMessage("STOF_ERROR_ORDER_CREATE")?></b><br /><br />
		
					<table class="sale_order_full_table">
						<tr>
							<td>
								<?=str_replace("#ORDER_ID#", $arResult["ORDER_ID"], GetMessage("STOF_NO_ORDER"))?>
								<?=GetMessage("STOF_CONTACT_ADMIN")?>
							</td>
						</tr>
					</table>
					<?
				}
				?>
			</td>
		</tr>
	</table>

</div>