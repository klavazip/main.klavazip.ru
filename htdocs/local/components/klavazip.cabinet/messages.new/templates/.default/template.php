<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="boxContTab">
	<div class="boxMess">
		
		<? /*?>
		<div class="headMess">
			<a href="#"><img alt="" src="img/img_mess.jpg"></a>
			<div class="nameMess">
				<a class="name" href="#">Менеджер магазина</a>
				<p>Сегодня, 21:20</p>
			</div>
			<div class="headMessRight">
				<a class="showAllMess" href="#">Показать переписку (5)</a>
			</div>
			<div class="clear"></div>
		</div>
		<div class="messCont">
			<h2>Ответ на запрос о поступлении матриц длинное название письма длинное название в несколько строк</h2>
			<a class="iconBasket" href="#"></a>
			<p>Квазар квантово разрешен. Ударная волна, если рассматривать процессы в рамках специальной теории относительности, когерентно ускоряет электрон, и этот процесс может повторяться многократно. Примесь восстанавливает межядерный гамма-квант, как и предсказывает общая теория поля. В ряде недавних экспериментов вещество масштабирует наносекундный гамма-квант независимо от расстояния до горизонта событий. Осциллятор, вследствие квантового характера явления, расщепляет адронный электрон при любом их взаимном расположении. Гомогенная среда доступна. </p>
			<p>Кристалл сингулярно синхронизует экситон при любом агрегатном состоянии среды взаимодействия. Разрыв экстремально нейтрализует плазменный пульсар даже в случае сильных локальных возмущений среды. Как легко получить из самых общих соображений, гидродинамический удар вращает квантово-механический квазар даже в случае сильных локальных возмущений среды. В ряде недавних экспериментов электрон неверифицируемо стабилизирует вихревой эксимер, и этот процесс может повторяться многократно.</p>								
		</div>
		<ul class="listDocuments">
			<li><a href="#">Договор от 05.08.20112</a> (DOC, 1.2 MB)</li>
			<li><a href="#">Прайс-лист на матрицы</a> (XLS, 0.6 MB)</li>
			<li><a href="#">Коммерческое предложение</a> (PDF, 1 MB)</li>
		</ul>
		<? */ ?>
		
		<? 
		if( $_GET['result'] == 'ok' )
		{
			?><div style="padding-left: 20px" class="result-block">Сообщение успешно отправлено</div><?
		}
		?>
		
		<form method="post" name="ballance" action="" enctype="multipart/form-data">											
			<div class="boxFormTab pad">								
	
				<label style="width: 180px;">Выберите отдел комании: </label>
				<div class="blockInputform">
					<div class="boxSelect_2">
						<div class="lineForm">									
							<select  id="curr" name="USER_MANAGER" class="se218" tabindex="1">
								<? 
								foreach ($arResult['ITEM_USER'] as $ar_Value)
								{
									?><option value="<?=$ar_Value['ID']?>"><?=$ar_Value['NAME']?></option><?
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="clear"></div>							
			</div>
		
		
			<div class="boxYourAnswer">
				<h3>Новое сообщение</h3>
				
					<textarea rows="1" cols="1" name="USER_TEXT"></textarea>
					<div class="boxFiles">
						<p>Прикрепленные файлы:</p> <br />

						<div class="blockInputform">
							<div id="return-order-upload-foto">
								<input type="file" name="FILE[]" value="">
							</div>
							<a href="#" onclick="klava.cabinet.returnOrderAddControlUpload();return false;" class="morePhotos"><span>Загрузить еще фото</span></a>
						</div>
						
						<? /*?>
						<ul class="listFiles">
							<li><span>foto_brak.jpg (1,2 MB)</span><a href="#"></a><div class="clear"></div></li>
							<li><span>foto_brak2.jpg (0.65 MB)</span><a href="#"></a><div class="clear"></div></li>
						</ul>
						<a class="morePhotos" href="#"><span>Загрузить еще</span></a>
						<? */ ?>	
					</div>
					<input type="submit" value="Отправить" name="smb" class="buttonOneClick">
					<div class="clear"></div>
				
			</div>
			
		</form>

		
		
		
	</div>								
</div>
