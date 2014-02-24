<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

	<div class="boxMain">	
		
		<h1>Личный кабинет</h1>	
		<div class="tabPersonal">
			<? /*?>
			<ul>
				<li><a href="/cabinet/" <?=($arResult['TAB_SELECTED'] == 'ACTIVE') ? 'class="current"' : '' ?> >Активность <span></span></a></li>
				<li><a href="/cabinet/messages/" <?=($arResult['TAB_SELECTED'] == 'MESSAGES') ? 'class="current"' : '' ?>>Сообщения <span>2</span></a></li>
				<li><a href="/cabinet/profile/" <?=($arResult['TAB_SELECTED'] == 'PROFILE') ? 'class="current"' : '' ?>>Профиль <span></span></a></li>
			</ul>
			<? */ ?>
			<?
			$APPLICATION->IncludeComponent("bitrix:menu", "cabinet_top_menu",
				array(
					"ROOT_MENU_TYPE" 	 	=> "cabinettop",
					"MAX_LEVEL" 		 	=> "1",
					"CHILD_MENU_TYPE" 	 	=> "top",
					"USE_EXT" 			 	=> "Y",
					"DELAY" 			 	=> "N",
					"ALLOW_MULTI_SELECT" 	=> "N",
					"MENU_CACHE_TYPE" 		=> "N",
					"MENU_CACHE_TIME" 		=> "3600",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" 	=> array()
				)
			);
			?>
			<div class="clear"></div>		
		</div>
		
		<div class="blockCont">
			<div class="contentLeft">
				<div class="boxSubnav">
									
					<?
					$APPLICATION->IncludeComponent("bitrix:menu", "cabinet_left_menu", array(
						"ROOT_MENU_TYPE" 		=> "cabinetleft",	// Тип меню для первого уровня
						"MAX_LEVEL" 			=> "1",	// Уровень вложенности меню
						"CHILD_MENU_TYPE" 		=> "",	// Тип меню для остальных уровней
						"USE_EXT" 				=> "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
						"DELAY" 				=> "N",	// Откладывать выполнение шаблона меню
						"ALLOW_MULTI_SELECT" 	=> "N",	// Разрешить несколько активных пунктов одновременно
						"MENU_CACHE_TYPE" 		=> "N",	// Тип кеширования
						"MENU_CACHE_TIME" 		=> "3600",	// Время кеширования (сек.)
						"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
						"MENU_CACHE_GET_VARS" 	=> "",	// Значимые переменные запроса
						),
						false
					);?>
					
					<? /*?>
					<ul>
						<? 
						if($arResult['TAB_SELECTED'] == 'ACTIVE')
						{
							?>
							<li <?=KlavaCabinet::selectedItem('/cabinet/')?>><a href="/cabinet/">История заказов <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/query-product/')?>><a href="/cabinet/query-product/">Запросы на товары <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/query-document/')?>><a href="/cabinet/query-document/">Запросы на документы <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/transits/')?>><a href="/cabinet/transits/">Транзиты <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/pre-orders/')?>><a href="/cabinet/pre-orders/">Ваши предзаказы <span>2</span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/add-balance/')?>><a href="/cabinet/add-balance/">Пополнение баланса <span></span></a></li>
							<?
						}
						
						if($arResult['TAB_SELECTED'] == 'MESSAGES')
						{
							?>
							<li <?=KlavaCabinet::selectedItem('/cabinet/messages/')?>><a href="/cabinet/messages/">Входящие <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/messages-out/')?>><a href="/cabinet/messages-out/">Исходящие <span>2</span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/messages-bas/')?>><a href="/cabinet/messages-bas/">Корзины <span></span></a></li>
							<?
						}

						if($arResult['TAB_SELECTED'] == 'PROFILE')
						{
							?>
							<li <?=KlavaCabinet::selectedItem('/cabinet/profile/')?>><a href="/cabinet/profile/">Тип плательщика <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/profile-info/')?>><a href="/cabinet/profile-info/">Личные данные <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/profile-pass/')?>><a href="/cabinet/profile-pass/">Смена пароля <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/profile-delevery/')?>><a href="/cabinet/profile-delevery/">Информация о доставке <span></span></a></li>
							<li <?=KlavaCabinet::selectedItem('/cabinet/profile-subs/')?>><a href="/cabinet/profile-subs/">Подписка на рассылки <span></span></a></li>
							<?
						}
						?>
					</ul>
					<? */ ?>
				</div>
			</div>
			<div class="contentRight2">
			
				<? /*?>
				<div class="boxPersonalCabinet">
					<div class="blockPersonalInf">
						<img src="<?=SITE_TEMPLATE_PATH?>/img/icon_personal_inf.png" alt="" />
						<div class="personalRight">
							<p>Личный счет: <span>250 310 <span class="curency">&#8399;</span></span><br/><a href="/cabinet/add-balance/">Пополнение</a></p>
							<p>Ваша скидка: <span>7%</span></p>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<? */ ?>
		