<?	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

	<div class="infPersonalTop">
		<p>Прием карт к оплате на территории Российской Федерации осуществляется компанией ООО КФЦ-Эквайринг, входящей в группу ДеньгиОнлайн. </p>
		<p>ОГРН &ndash; 1127847344954</p>
		<p><a href="#">http://dengionline.com</a></p>
	</div>
	<div class="boxFormBallance">
		<form method="post" name="ballance" action="">
			<label>Выберите тип плательщика</label>
			<div class="blockInputform">
				<div class="lineRadio">
					<input type="radio" name="radio_1" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_1"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_1">Физическое лицо</label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_1" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_2"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_2">Юридическое лицо</label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_1" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_3"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_3">ИП</label>								
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
			<label>Сумма пополнения</label>
			<div class="blockInputform">
				<input type="text" class="summ" onblur="if(this.value==''){this.value='5 000'}" onfocus="if(this.value=='5 000'){this.value=''}" value="5 000" name="q">
				<p class="inputCurrency"><span class="curency">⃏</span></p>
			</div>
			<div class="clear"></div>
			<label>Выберите способ оплаты</label>
			<div class="blockInputform">
				<div class="lineRadio">
					<input type="radio" name="radio_2" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_4"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_4">Пластиковая карта</label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_2" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_5"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_5">Яндекс.Деньги</label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_2" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_6"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_6">QIWI-кошелек</label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_2" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_7"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_7">Web Money</label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_2" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_8"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_8">Переводы Евросеть</label>								
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
			<label>Выберите способ оплаты</label>
			<div class="blockInputform">
				<div class="lineRadio">
					<input type="radio" name="radio_3" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_9"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_9">Visa, MasterCard <img alt="" src="img/08_icon_1.png"></label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_3" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_10"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_10">EasyPay <img alt="" src="img/08_icon_2.png"></label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_3" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_11"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_11">Rapida <img alt="" src="img/08_icon_3.png"></label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_3" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_12"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_12">Liberty Reserve <img alt="" src="img/08_icon_4.png"></label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_3" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_13"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_13">ПромСвязьБанк <img alt="" src="img/08_icon_5.png"></label>								
					<div class="clear"></div>
				</div>
				<div class="lineRadio">
					<input type="radio" name="radio_3" class="styledRadio radioButton" style="position: absolute; left: -9999px;" id="radioButton_14"><span style="display:inline-block" class="radio"></span>
					<label for="radioButton_14">ВТБ 24 <img alt="" src="img/08_icon_6.png"></label>								
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
			<div class="blockSummBottom">
				<p>Сумма к оплате: <span>5 000 <span class="curency">⃏</span></span></p>
				<input type="submit" value="Оплатить" class="buttonBuy">
				<div class="clear"></div>
			</div>
		</form>
	</div>
			