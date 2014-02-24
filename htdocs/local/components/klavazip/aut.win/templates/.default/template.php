<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="reveal-modal forLogin" id="linkLogin" style="top: 200px; opacity: 1; visibility: hidden;">
    <div class="myModal-inner">
		<div class="openWindow login">
			<a class="close-reveal-modal"></a>
			<h2>Вход</h2>
			<div class="boxLoginForm">
				<div class="loginFormLeft">
					<label>Электронная почта</label>
					<input type="text" id="aut_mail" value="" name="search">
					<div class="clear"></div>		
					<label>Пароль</label>
					<input type="password" id="aut_pass" value="" name="search">
					<div class="clear"></div>
					<div class="loginCheck">
						<input type="checkbox" id="check_login_1" name="check_login_1" class="styled" style="position: absolute; left: -9999px;">
						<label for="check_login_1">Запомнить меня</label><div class="clear"></div>
						<div class="aut-ajax-load" id="aut-ajax-load"></div>
						<input type="submit" onclick="klava.autorize()" value="Войти" class="buttonBuy">
					</div>	
				</div>
				<div class="loginFormRight">
					<?/*?>
						<div class="socialReg">
							<p>Вход через социальные сети:</p>
							<a href="#"><img alt="" src="/bitrix/templates/klavazip_new/img/icon_social_1.png"></a>
							<a href="#"><img alt="" src="/bitrix/templates/klavazip_new/img/icon_social_2.png"></a>
							<a href="#"><img alt="" src="/bitrix/templates/klavazip_new/img/icon_social_3.png"></a>
							<a href="#"><img alt="" src="/bitrix/templates/klavazip_new/img/icon_social_4.png"></a>
							<a href="#"><img alt="" src="/bitrix/templates/klavazip_new/img/icon_social_5.png"></a>
							<div class="clear"></div>	
						</div>
					<? */ ?>
					<div class="linkReg"><a href="/aut/registration/">Регистрация</a></div>
					<div class="linkReg"><a href="/aut/forgotpasswd/">Забыли пароль?</a></div>
				</div>
				<div class="clear"></div>	
			</div>
		</div>
	</div>
</div>
<div class="reveal-modal-bg" style="display: none; cursor: pointer;"></div>