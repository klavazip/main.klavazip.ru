<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

	<div class="boxMain">
		<h1>Регистрация</h1>
		<div class="border_1"></div>
		<? 
		if( count($arResult['REG_ERROR']) > 0 )
		{
			?><div class="error-block"><?=implode('<br />', $arResult['REG_ERROR'])?></div><?
		}
		
		if($_GET['registr'] == 'Y')
		{
			?><div class="result-block">Вы успешно зарегистрированны</div><?
		}
		else
		{
			?>
			<div class="boxMainCont">
				<div class="boxReg">
					<form  method="post" action="<?=POST_FORM_ACTION_URI?>"  enctype="multipart/form-data" name="regForm">
						<label>Электронная почта</label>
						<div class="blockInputform">
							<input type="text" name="email" value="<?=htmlspecialchars($_POST['email'])?>">
							<p class="comment">Пример: mail@klavazip.ru</p>
						</div>
						<div class="clear"></div>
						<label>Пароль</label>
						<div class="blockInputform">
							<input type="password" name="pas" value="<?=htmlspecialchars($_POST['pas'])?>">
							<p class="comment">Не менее 6 знаков</p>
						</div>
						<div class="clear"></div>
						<label>Еще раз пароль</label>
						<div class="blockInputform">
							<input type="password" name="pass" value="<?=htmlspecialchars($_POST['pass'])?>">
						</div>
						<div class="clear"></div>
						<input class="buttonBuy" type="submit" name="bt" value="Зарегистрироваться">
					</form>
				</div>
			</div>
			<?			
		}
		?>
	</div> 