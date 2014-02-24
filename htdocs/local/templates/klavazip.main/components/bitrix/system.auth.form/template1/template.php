<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	global $USER;
	if ($USER->IsAuthorized())
	{
		?>
		<div class="contName">
			<a class="nameHidden" href="#"><span><span><?=$arResult["USER_LOGIN"]?></span></span></a>
			<div class="nameRight"></div>
			<div class="subnavNameOpen">
				<div class="subnavName">
					<ul>
						<li class="first"><a href="/cabinet/">Личный кабинет</a></li>
						<li class="last"><a  href="/?logout=yes">Выход</a></li>
					</ul>
					<div class="subnavNameTop"></div>
				</div>
			</div>
		</div>
		<?
	}
	else
	{
		?>
		<div class="contName">
			<a class="nameHidden" href="#"><span><span>Вход</span></span></a>
			<div class="nameRight"></div>
			<div class="subnavNameOpen">
				<div class="subnavName">
					<ul>
						<li class="first"><a rel="linkLogin" href="#">Вход</a></li>
						<li><a href="/aut/registration/">Регистрация</a></li>
						<li class="last"><a href="/aut/forgotpasswd/">Забыли пароль?</a></li>
					</ul>
					<div class="subnavNameTop"></div>
				</div>
			</div>
		</div>
		<?
	}
	?>
			
							

<?/*
<div class="bx-system-auth-form">
<?if($arResult["FORM_TYPE"] == "login"):?>

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>
 
<form class="login-form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
<?foreach ($arResult["POST"] as $key => $value):?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
	<h3 class="popup-title">Вход</h3>
    	<p>
	    <span class="label"><?=GetMessage("AUTH_LOGIN")?>:</span>
	    <input id="authL" class="text-input" type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" />
	</p>
	<p>
	    <span class="label"><?=GetMessage("AUTH_PASSWORD")?></span>
	    <input id="authP" class="text-input" type="password" name="USER_PASSWORD" maxlength="50" size="17" />
	</p>
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon">&nbsp;</div>
				</div>
				<noscript>
				<div class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock">&nbsp;</div>
				</div>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
</script>
<?endif?>
<p style="text-align:center">
  <span class="button-l">
    <span class="button-r">
      <input class="button" type="submit" id="authSend" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />
    </span>
  </span>
</p>
<span class="forgot-password">забыли пароль?</span>
</form>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "", 
	array(
		"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
		"AUTH_URL"=>$arResult["AUTH_URL"],
		"POST"=>$arResult["POST"],
		"POPUP"=>"Y",
		"SUFFIX"=>"form",
	), 
	$component, 
	array("HIDE_ICONS"=>"Y")
);
?>
<?endif?>

<?
//if($arResult["FORM_TYPE"] == "login")
else:
?>

<form action="<?=$arResult["AUTH_URL"]?>">
	<table width="95%">
		<tr>
			<td align="center">
				<?=$arResult["USER_NAME"]?><br />
				[<?=$arResult["USER_LOGIN"]?>]<br />
				<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
			</td>
		</tr>
		<tr>
			<td align="center">
			<?foreach ($arResult["GET"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="logout" value="yes" />
			<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
			</td>
		</tr>
	</table>
</form>
<?endif?>
</div>

<!--<form class="login-form">
    	<h3 class="popup-title">Вход</h3>
    	<p><span class="label">Ваш логин</span> <input type="text" class="text-input"></p>
        <p><span class="label">Ваш пароль</span> <input type="password" class="text-input"></p>
        <p style="text-align:center"><span class="button-l"><span class="button-r"><input type="submit" class="button" value="войти"></span></span></p>
    	<span class="forgot-password">забыли пароль?</span>
</form>-->
*/?>
