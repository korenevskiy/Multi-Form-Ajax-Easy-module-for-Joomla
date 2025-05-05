'use strict';
'ver123';
//https://stackoverflow.com/questions/59903262/how-in-joomla-with-api-with-ajax-get-field-editor-for-form
//https://stackoverflow.com/questions/6547116/joomla-jce-editor-not-loading-in-page-loaded-with-ajax?rq=1
//jQuery(document).on('scroll', function () {
//	scrollFromTop();
//});

/**
 * Растояние до верха сайта
 * @returns {int} растояние высоты
 */
function scrollFromTop() {
	let fff = window.pageYOffset || document.documentElement.scrollTop;
	return fff;
}

/**
 *  
 * @param {string} heightblock 
 * @returns {int}  
 */
function positionAfterSend(heightblock) {
	let winPostionScroll = window.pageYOffset || document.documentElement.scrollTop;
	
	
	let heightpage = window.innerHeight;
	let heightpopupblock = jQuery(heightblock).height();
	let position = heightpage / 2 + heightpopupblock + winPostionScroll;
	return position;
}
 
//function modalPostion(id, modal){
//	modal = document.getElementById(`mfForm_${id}`);
//	
//	let winPostionScroll = window.pageYOffset || document.documentElement.scrollTop;
//	
//	let positiOnScroll = window.innerHeight / 2 - modal.offsetHeight / 2 + winPostionScroll;
//	positiOnScroll = 0;
//	
//	return {};
//}
/**
 *  Событие КЛИКа Прокрутки к форме
 * @param {event} event 
 * @returns {undefined} 
 */
function mfScrollStatic_Click(event) {
	event.preventDefault();

//	console.log('event -> ', event.data);
//	console.log('this -> ', this);
//event.data - Модуль с датой
//this - Модуль с датой 

	for (let field of event.data.fields) {
		if (this[field]) {
			document.getElementById(field).value = this[field].replace('_', ' ');
			continue;
		}
		if (this[field] !== undefined) {
			document.getElementById(field).value = '';
		}
	}


	jQuery('html, body').animate({
		scrollTop: jQuery("#mfForm_" + event.data.id).offset().top - 100
	}, 2000);
	return false;
}

/**
 *  Событие КЛИКа Открытия модального окна
 * @param {event} event 
 * @returns {undefined} 
 */
function mfOpenModal_Click(event) {
	event.preventDefault();
//	console.log('event -> ', event);
//	console.log('event.data.fields -> ', event.data.fields);
//	console.log('this -> ', this);
	
//event.data - Модуль с датой
//this - Модуль с датой


	for (let field of this.fields) {
//			console.log(field,this[field]);
		if (this[field]) {
			document.getElementById(field).value = this[field].replace('_', ' ');
			continue;
		}
		if (this[field] !== undefined) {// если пробел.
			document.getElementById(field).value = '';
		}
	}
  
	document.getElementById('mfForm_' + this.id).showModal();
	return false;




  let modal_id = 'mfForm_' + id; 

  let heightpage = jQuery(window).height();
  let heightpopupblock = jQuery('#' + modal_id).height();


//	console.log('heightpopupblock -> ', heightpopupblock,'>=',heightpage,'heightpage-',heightpopupblock >= heightpage);
//	console.log('modal -> ', '#' + modal_id);
//	console.log('this.id -> ', id);



//	console.log('scrollFT -> ', scrollFT);
//	console.log('positiOnScroll -> ', positiOnScroll);
//	console.log('positiForOK -> ', positiForOK);
//	console.log('modal -> ', '#' +modal_id);


	jQuery('#mfOverlay_' + id).fadeIn(400, // сначала плавно показываем темную подложку
	function () {	// после выполнения предыдущей анимации
				
//				document.getElementById(modal_id).showModal();
		let scrollFT = 0;	 
		let positiOnScroll = 0;
	
//console.log(123,'HaHa');
		 if (heightpopupblock + 50 >= heightpage) {
			scrollFT = scrollFromTop();//положение прокрутки страницы 
			positiOnScroll = 50;// + scrollFT;
//			var positiForOK = heightpage / 2 - heightpopupblock / 2 + scrollFT;
		  } else {
			scrollFT = 0;//scrollFromTop();//положение прокрутки страницы 
			positiOnScroll = heightpage / 2 - heightpopupblock / 2 + scrollFT;
//			var positiForOK = heightpage / 2 - heightpopupblock / 2 + scrollFT;
		}
//				document.getElementById(modal_id).showModal();
				//this.closest('#' + modal_id).close();
				//document.getElementById("mfForm_" + id).close();
	  let modal = jQuery('#' +modal_id);//.css('padding',0);	 
//console.log(modal,'HaHa');	  
//	  modal.modal('show').css('padding-right',0);
//	  jQuery('#' +modal_id).css('display','none').css('opacity',0).css('top','-200%');
//	  modal.fadeIn(400, ( mod )=>{
//console.log(this, 'HaHa 1'); 
//	  });	
		positiOnScroll = '0';	 
		modal.css('display', 'block').animate({opacity: 1, top: positiOnScroll, visibility: 'visible', }, 400, ()=>{
		  let overlay = jQuery(this).next('.mfOverlay');//.css('padding',0).css('padding-right',0);
//console.log(overlay,'HaHa 2');
		  jQuery(this)//.modal('show')
			.css('padding',0).css('padding-right',0);
		}).css('opacity',1).css('top','200');
	  
//let sleep = function (ms) {
//  return new Promise(
//	resolve => setTimeout(resolve, ms)
//  );
//}		   
	  
//	  jQuery('#' +modal_id).css('display','block').css('opacity',1).css('top','50%').css('padding-right',0);
//	  sleep(2000);
//	  jQuery('#' +modal_id).modal().css('padding-right',0);
//console.log(123,'HaHa'); 
//	  jQuery('#' +modal_id).modal('show').css('padding-right',0);
//console.log(123,'HaHa');
//	  jQuery('#' +modal_id).css('padding',0);
						//.css('display', 'block') // убираем у модального окна display: none;
//	  modal.animate({opacity: 1, top: positiOnScroll}, 200); // плавно прибавляем прозрачность одновременно со съезжанием вниз
//jQuery('#mfForm_111').fadeIn().modal('show').modal();
//console.log(123,'HaHa');
	});
  return false;
}

//ОК!
/**
 *  Событие КЛИКа закрытия модального окна
 * @param {event} event 
 * @returns {undefined} 
 */
function mfCloseModal_Click(event) {
	//let id = button.dataset.id;
//console.log(this,event);
	
	event.preventDefault();
	document.getElementById('mfForm_'+this.id).close();
	return;
	
	jQuery("#mfOverlay_" + this.id).fadeOut(()=>{
		jQuery("#mfForm_" + this.id)//.fadeOut(400,'swing',{})
			.animate({top: '-200%'}, 400)//.delay(800)
			.animate({opacity: 0}, 400, function () { // после анимации
				document.getElementById('mfForm_'+this.id).close();
				//this.closest('dialog').close();
//	console.log('modal -> ', 'mfForm_'+this.id);
//				jQuery(this).css('display', 'none'); // делаем ему display: none;
//				jQuery("#mfForm_" + this.id).modal('hide');
//				document.getElementById('mfForm_'+this.id).close();
			}).delay(400).modal('hide');
	}); // скрываем подложку
	
}
var runingCloseModalForm = false;
/** НЕ ИСПОЛЬЗУЕТСЯ!!!
 * Закрытие модального окна. Присваивается для подложки и кнопки КЛИК закрыть. 
 * @returns {undefined}
 */
function mfClickCloseModal() {
//	console.log('Click SET Close -> 1 ' );
	if (runingCloseModalForm)
		return;
	runingCloseModalForm = true;
//	console.log('Click SET Close -> 2 ' );
	jQuery(function () {// Закрытие окна по клику крестика и фона
		jQuery('.mfOverlay, .mfClose').click(function (close) {
			close.preventDefault();
//	console.log('Click Close -> ', jQuery(this).attr("data-id"));
			let id = jQuery(this).attr('data-id');
			let modal = "#mfForm_" + id;
			let overley = "#mfOverlay_" + id;
console.log(overley,'HaHa');
			jQuery(modal).animate({top: 0}, 200);
			jQuery(modal)
					.animate({opacity: 0}, 300, function () { // после анимации
//console.log(overley,'HaHa');
						jQuery(this).css('display', 'none'); // делаем ему display: none;
						jQuery(overley).fadeOut(400, function(){
							document.getElementById('mfForm_'+params.id).close();
						}); // скрываем подложку
					}
					);
		});
	});
}



/*For Modal Form - Методы для динамических(Модальных) форм*/
/**
 * Скрыть форму после отправки  
 * @param {int} id
 * @param {html} modal Класс модального окна
 * @param {string} overley Класс подложки
 * @param {string} status Клас  
 * @param {html} response 
 * @returns {undefined}
 */
function hideBlockFormAfterSend(id, modal, overley, status, response) {

//	jQuery('.mfPanelDone.id' + id).html(response);

	let heightpage = jQuery(window).height();
	let heightpopupblock = jQuery('.mfPanelDone.id' + id).height();
	let scrollFT = scrollFromTop();
	let positiOnScroll = heightpage / 2 - heightpopupblock / 2;//+ scrollFT;
	positiOnScroll = 0;
// console.log('heightpopupblock высота модалки ',heightpopupblock);
// console.log('scrollFT позиция прокрученого сайта ',scrollFT);
// console.log('positiOnScroll ',positiOnScroll);
	
	jQuery('#mfForm_' + id).animate({top: positiOnScroll}, 400, function () {
		jQuery('.mfPanelForm.id' + id).fadeOut(400,function(){
//			document.getElementById('mfForm_'+id).close();
		});
		setTimeout(function () {
			jQuery('#mfForm_' + id)
					// плавно меняем прозрачность на 0 и одновременно двигаем окно вверх
					.animate({top: -positiOnScroll}, 400,
							function () { // после анимации 
								jQuery(this).fadeOut(400,function(){
									document.getElementById('mfForm_'+id).close();
								}); // делаем ему display: none;
								jQuery("#mfOverlay_" + id).fadeOut(); // скрываем подложку
							}
					);
		}, 10000);//Задержка отображения окна после отправки		
	});
}


/**
 * Скрыть и очистить форму после отправки 
 * @param {int} id
 * @param {html} modal
 * @param {string} overley
 * @param {string} status
 * @param {html} response 
 * @param {string} textbutton 
 * @returns {undefined}
 */
function hideAndClearFormAfterSend(id, modal, overley, status, response) {

//	jQuery('.mfPanelDone.id' + id).html(response);

	let heightpage = jQuery(window).height();
	let heightpopupblock = jQuery('.mfPanelDone.id' + id).height();
	let scrollFT = scrollFromTop();
	let positiOnScroll = heightpage / 2 - heightpopupblock / 2 + scrollFT;
	positiOnScroll = 0;

	jQuery('#mfForm_' + id).animate({top: positiOnScroll}, 400, function () {
		
		setTimeout(function () {
			jQuery('#mfForm_' + id)
					// плавно меняем прозрачность на 0 и одновременно двигаем окно вверх
					.animate({top: -positiOnScroll}, 400,
							function () { // после анимации 
								jQuery(this).fadeOut(400,function(){
									document.getElementById('mfForm_'+id).close();
								}); // делаем ему display: none;
								jQuery('#mfOverlay_' + id).fadeOut(400, function () { // скрываем подложку 
									jQuery('.mfPanelDone.id' + id).fadeOut();
									jQuery('.mfPanelForm.id' + id).fadeIn();
									jQuery('.mfPanelForm.id' + id).get(0).reset();
									jQuery('#mfForm_' + id + ' input[id^=submit]').button('ready');
								});

							}
					);
		}, 10000);
	});
}

/*For Static Form - Методы для статических форм*/
/**
 * Скрыть форму перед отправкой
 * @param {int} id
 * @param {html} block
 * @param {string} status
 * @param {html} response
 * @returns {undefined}
 */
function hideBlockStaticFormAfterSend(id, block, status, response) {
//console.log('RESPONse',response);
//	jQuery('.mfPanelDone.id' + id).html(response);

	jQuery(".mfPanelForm.id" + id).fadeOut(400, function () {
		jQuery('.mfPanelDone.id' + id).fadeIn(400);
	}); // делаем ему display: none; 
}
/**
 * Скрыть и очистить форму перед отправкой
 * @param {int} id
 * @param {string} block
 * @param {html} status
 * @param {string} response
 * @param {string} textbutton
 * @returns {undefined}
 */
function hideAndClearStaticFormAfterSend(id, block, status, response, textbutton) {

//	jQuery('.mfPanelDone.id' + id).html(response);
	jQuery('.mfPanelForm.id' + id).fadeOut(400, function () {
		jQuery('.mfPanelDone.id' + id).fadeIn(400, function () {
			setTimeout(function () {
				jQuery('.mfPanelDone.id' + id).fadeOut(400, function () {
					jQuery('.mfPanelForm.id' + id).fadeIn(400, function () {
						jQuery('.mfPanelForm.id' + id)[0].reset();
						jQuery('#mfForm_' + id + ' input[id^=submit]').button('ready');
//						document.getElementById('mfForm_'+id).close();
					});
				});
			}, 8000);// Задержка вывода сообщения для статической формы 
		});
	});
}


/* --------- --------------------------------------- */

// -------- Load Form
/**
 * Вызывается после успешной загрузки формы
 * @param string html return html from server
 * @param {type} status
 * @returns {undefined}
 */
function mfAjaxDoneForm(html, status) {
	
	if(html == ''){
		console.log('!!! Ajax aswer: [EMPTY] moduleID:'+this.id+' status:'+status, this);
		return ;
	}
	
//	const modID = this.id;
//		var id = jQuery(this).data('id');
//		var deb = jQuery(this).data('deb');
//		var type = jQuery(this).data('type');
//	this.deb && console.log('🏆 Module id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Success! - Done! status:', status, ' ', this);



	
	const ajaxReload = Math.floor(Math.random() + 10000000);
	document.getElementById('mod_'+this.id).dataset.ajaxReload = ajaxReload;
console.log('ajaxReload:',ajaxReload);
//console.log(this, this.buttons);

	const bc = new BroadcastChannel('modMultiModReload' + this.id + '_' + ajaxReload);
//	bc.onmessage = (e)=>e.data;
	bc.addEventListener("message", function (e) {
		let url = e.data;
		console.log('RELOAD-chanel!!',e.data); 
		const reload = e.data == document.getElementById('mod_'+this.id).dataset.ajaxReload;
		
		if(! url  )
			url = document.baseURI + 'index.php?option=com_ajax&module=multi_form&format=raw&id='+this.id+'&'+Joomla.getOptions('csrf.token')+'=1';
//			url = window.location.origin + '/index.php';
		jQuery.ajax({type: 'GET', url: url, dataType: 'html', data: null, context: this, cache: false, contentType: false, processData: false})
			.done(mfAjaxDoneSuccess).fail(mfAjaxFailForm);
	});
	//Инициализация Validator'а на форме при первой запуске

	if(this.type === 'popup')
		document.body.insertAdjacentHTML('beforeend',html);
	else
		document.getElementById('mod_'+this.id).insertAdjacentHTML('beforeend',html);
	
	this.dialog	= document.getElementById('mfForm_'+this.id);
	this.form	= document.getElementById('mfForm_form_'+this.id);
	
	this.form.querySelector('output').addEventListener('click', function(){
		if(! window.event.shiftKey)	{
			this.innerHTML = '';
		}
	});
	
	
	validateForm(this);
	
console.log('BUTTONS:',this.buttons);

	if (this.type === 'popup'){
//		jQuery('body').append(jQuery(html));
//jQuery('dialog#mfForm_' + this.id + '.mfForm_modal').animate({top: '-200%'}, 400).fadeOut().modal('hide');//.modal('hide'); ****** Анимация
		
		this.dialog.close();
		this.dialog.querySelector('.mfClose').addEventListener('click', mfCloseModal_Click.bind(this));
		
		
		// Привязка события Escape
		document.addEventListener('keydown', event => {
			if(event.code === 'Escape'){
				event.preventDefault();
				mfCloseModal_Click({data: this, preventDefault: ()=>{}});
			}
		});
//		jQuery('#mfOverlay_' + this.id).click(this, mfCloseModal_Click); // ****** Анимация
//		jQuery('.modal-backdrop.show').click(function(){ // ****** Анимация
//			jQuery('.mfOverlay').fadeOut();
//			jQuery(dialog).animate({top: '-200%'}, 400).fadeOut().modal('hide');//.modal('hide');
//			jQuery(this).hide();
//		});
	}

	if (this.buttons.length > 0) {
		for (let btn of this.buttons) {
			console.log(btn);
//			jQuery(btn).click(this, mfScrollStatic_Click);
			btn.addEventListener('click', (this.type === 'popup' ? mfOpenModal_Click.bind(this) : mfScrollStatic_Click.bind(this)));
		}
	}	
	document.getElementById('submit_'+this.id).addEventListener('click', mfButtonSubmit_Click.bind(this));
	
	
	
//console.log(params);
document.getElementById('mod_' + this.id).dispatchEvent(new CustomEvent('modMultiForm_Loaded',{detail: this}));
document.getElementById('mod_' + this.id).dataset.loaded = true;
	
	
	this.deb && console.log('🏆 ButtonSubmit.Click(f()) id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Success! - Done! status:', status, ' ', this);

//		jQuery('#mfForm_form_'+this.id +' input').inputmask();
	this.deb && console.log('🏆 Module id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Success! - Done! status:', status);
}

/**
 * Вызывается после ошибки загрузки формы
 * @param {type} jqXHR
 * @param {type} status
 * @param {type} errorThrown
 * @returns {undefined}
 */
function mfAjaxFailForm(jqXHR, status, errorThrown) {//(jqXHR, status, errorThrown)
//console.clear();

	const div = document.createElement('div');
	div.innerHTML = jqXHR.responseText;
	
//someText = someText.replace(/[\n\r\t]/gm, "")	
//someText = someText.replace(/(\r\n|\n|\r)/gm, "")


	console.log('🚨🚨🚨🚨🚨 Error:',  div.innerText.replaceAll(/(\r\n)/gm, ``).replaceAll(/(\n\n)/gm, ``));
	this.deb && console.log('👎 Module id:' + this.id + ' Tag:(' + this.tag + '), Type:' + this.type + ' - Load form Fail! - Disabled button! status:', status, '\n this:', this, ' jqXHR:', jqXHR,'\n errorThrown:',errorThrown);//, errorThrown,jqXHR
	//this.deb && console.log('👎 Module id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Fail! - Disabled button! status:', status, ' this:', this, ' data:', data);//, errorThrown,jqXHR//
//		jQuery('.button.id'+this.id).hide();

	document.querySelector('#' + this.button).closest('div,label,span,p').insertAdjacentHTML( 'beforeend', '<code>Error Load Form !</code>' );

	jQuery('#' + this.button).hide();
//	jQuery('#' + this.button).attr('error',this.url);
//	console.log(this, status);
}



/**
 * Вызов при клике кнопки Отправить. <br>
 * Но при первом запуске формы, тоже вызывается этот метод, <br>
 *  но с ограничением чтобы только происходила инициализация проверки полей
 * @param {type} e
 * @returns {undefined}
 */
function mfButtonSubmit_Click(e) {
		e.preventDefault();
//		let params = e.data;
//		let e = {data:this};
//	console.log('🏆 func_custom', e, this);
//e.data.deb &&				console.log('--------- --------- Click Send ---------');
//		var fields = params.fields;
//		var textSubmitButton = $('input#submit'+e.data.id).attr('value');
	//отключение события, нужно для инициализации Validator'а на форме при первом запуске
//	if (e.first)
//		e.preventDefault();

	// Вызов пользовательского скрипта из модуля.
	let func_custom = window['funcBefore' + this.id];
// console.log('🏆 func_custom', func_custom);
	if (func_custom && typeof func_custom === 'function') {
		func_custom.apply(this);
	}
	console.log('🏆 mfButtonSubmit_Click ', "#mfForm_form_"+this.id, this, jQuery("#mfForm_form_" + this.id)); 
//console.log('jQuery.validator.messages',jQuery.validator.messages);

	validateForm(this);
}

function validateForm(params){
	
	const form = document.getElementById('mfForm_form_' + params.id);
	
	if(! form)
		return;
	
//console.log(params.validate, params,jQuery("#mfForm_form_" + params.id));
//	if(form.getAttribute('novalidate')){
//		params.validate = '';
//		callSubmit.bind(params);
//		return;
//	}

	/* плагин устанавливает атрибут novalidate для формы чтобы отключить проверку браузера полей формы*/
	
	if(params.validate && params.validate.form()){
		callSubmit.call(params, form);
		return;
	}
	
	params.validate = jQuery(form).validate({
		invalidHandler: function (event, validator) {
			console.log('👎 --------- --------- Validate Faile ---------', validator);
		},
		submitHandler: callSubmit.bind(params),// console.log.bind(params) ,// 
//		messages: jQuery.validator.messages,
		debug: false
//		,showErrors: function(errorMap, errorList) {
//			console.log("Your form contains "
//				+ this.numberOfInvalids()
//				+ " errors, see details below."
//				+ this.defaultShowErrors(),errorMap,errorList,this);
//		}
	});

//	params.validate.form();
}

/**
 * Происходит после успешной валидации полей на содержимое
 * @returns {undefined}
 */
function callSubmit(form) {//,event //recaptcha_invisible, recaptcha

	const params = this;
params.deb &&		 console.log('--------- --------- ---------');
params.deb &&		 console.log('(I)  :-)  - submitHandlerValidate!!!!!->  ()  Captcha:',params.captcha?'Yes🌟':'No🚫', params.captcha, params);
//console.log('🏆 Captcha !!!	<--------- captcha:' , params.captcha, '  grecaptcha:', params.grecaptcha);
	
	if (params.captcha == 'recaptcha'  && params.grecaptcha !== false) {
		//'dynamic_captcha_'+params.id
		//grecaptcha.execute(params.grecaptcha)
		//grecaptcha.execute('widget_captcha_'+params.id)
//		grecaptcha.ready(function() {
//		console.log('22222222222')
		params.response = grecaptcha.getResponse(params.WidgetId);
		if(params.response){
			console.log('(II) Execute() -submitHandler-CallBack!!!!!-> Token', ' This:',this,' Module:',params  );
			submitHandlerSubmit.call(params, form);
			
			if(params.xCallback && params.xCallback.trim()){
				try {
					let callback = params.xCallback.trim();
					eval(callback+'()');
					//callback();
					//eval(module.xCallback);
				}catch(e){
				}
			}
		}
		return false;
//						});
	}
	if (params.captcha == 'recaptcha_invisible'  && params.grecaptcha !== false) {
						//'dynamic_captcha_'+params.id
						//grecaptcha.execute(params.grecaptcha)
						//grecaptcha.execute('widget_captcha_'+params.id)
//						grecaptcha.ready(function() {
//							console.log('3333333333333')
		let rdy = this;
							
		grecaptcha.execute(params.grecaptcha).then(function(token){

			params.token = token; 
			console.log('(II) Execute() -callSubmit-CallBack!!!!!-> Token', token,' This:',this,' Module:',params  );//,', form:', form
//			document.getElementById('mfForm_'+params.id).close();
			return;
								
//			submitHandlerSubmit.call(params, form);
		});
//						});
		return;
	}
	else if(params.captcha == 'recaptcha_invisible' && params.grecaptcha === false){//
params.deb && console.log('(II) Valid() -callSubmit-CallBack!!!!!-> ',' This:',this,' Module:',params  );//, ', form:', form
//		console.log('4444444444')
//		submitHandlerSubmit.call(params, form);
//		document.getElementById('mfForm_'+params.id).close();
		return;
	}else if(!params.captcha || params.grecaptcha === false){//Капча не настроена, выполняем без капчи
params.deb && console.log('(II) Valid() -callSubmit-CallBack!!!!!-> ',' This:',this,' Module:',params );//, ', form:', form 
//		console.log('555555555555')
		submitHandlerSubmit.call(params, form);
//		document.getElementById('mfForm_'+params.id).close();
		return;
	}else{
params.deb && console.log('👎 Captcha NULL !!!	<--------- captcha:' , params.captcha, '  grecaptcha:', params.grecaptcha );
		/* Message ERROR! Alert: Please Please contact us in another way in contacts.*/
		/* Message ERROR! Alert: Пожалуйста обратитесь другим способом в контактах.*/
						
//		document.getElementById('mfForm_'+params.id).close();
	}
}


// -------- Submit Form
/**
 * Выполняется после Recaptcha и валидации полей
 * Отправка данных формы
 * @param {type} form
 * @returns {undefined}
 */
function submitHandlerSubmit(form = null){

//				let data = this;//form.dataset;

//				var capth_exe = grecaptcha.execute();

//				console.log('capth_exe',capth_exe);
//				console.log('form',form);

this.deb && console.log('(III)  submitHandlerSubmit() -Execute-CallBack!!!!!-> ', this);
//	return;

//				jQuery(form).submit(function(e) {
//					 e.preventDefault();
//				});
//				form.preventDefault();
//				console.log('🏆 Validated!!!!');
//				console.log('🏆 Validated!!!! data', e.data);
//				console.log('🏆 Validated!!!! Event', event);
//				console.log('🏆 Validated!!!! Form', form);

//				throw new Error("Something went badly wrong!");

//	const btnSubmit = document.getElementById('submit_' + this.id);
//	btnSubmit.style.textTransform = 'none';
//	btnSubmit.style.transform = 'none';
//	btnSubmit.classList.add('active');
	
//	Тект кнопки при отправке
	jQuery('#submit_' + this.id).button('sending')
			.css('text-transform', 'none').css('transform', 'none').addClass('active'); //Тект кнопки при отправке

	/*
	 * var text1251 = $('input[name=text1251]').val();
	 * var telephone2251 = $('input[name=telephone2251]').val();
	 */
	/*
	 * '0251':0251,
	 * 'text1251':text1251,
	 * 'telephone2251':telephone2251,
	 */
	let itemid = 0;
	for (let clss of document.body.classList) {
		if (clss.toString().substr(0, 7) === 'itemid-') {
			itemid = clss.toString().substr(7);
		}
	}

//form.addEventListener('formdata', (e) => {
//  let data = e.formData;
//  for (var value of data.values()) {
//  }
//});
//				var form = document.getElementById('mfForm_form_'+e.data.id)
//				console.log('form',form);
	
	if(! form)
		form = document.createElement('form');

	var data = new FormData(form);//  mfForm_form_249

//this.deb &&		 console.log(' Form :', form);
//this.deb &&		 console.log('Data Form :', data);
//				data.append('method	 ', 'getForm');
	
	const ajaxReload = document.getElementById('mod_'+this.id).dataset.ajaxReload;

	data.append('ajaxReload', ajaxReload);
	data.append('option', 'com_ajax');
	data.append('module', 'multi_form');
	data.append('format', 'raw');//raw  json  debug
	data.append('id', this.id);
	data.append('url', document.location.href);
	data.append('title', document.title);
	data.append('Itemid', itemid);
	data.append(Joomla.getOptions('csrf.token'), 1);
	
	if(this.token)
		data.set('gToken', this.token);
	if(this.response)
		data.set('gToken', this.response);
	if(this.sitekey !== undefined)
		data.append('sitekey', this.sitekey);
	if(this.grecaptcha !== undefined)
		data.append('grecaptcha', this.grecaptcha);

	//document.querySelector( ".mfPanelForm.id312 .g-recaptcha-response" );
	if(this.response){//this.captcha &&  
		data.append('g-recaptcha-response', this.response);// 
		//data.set('g-recaptcha-response', this.response);//		 
	}
	
//this.deb &&		 console.log('Data Form :', data);
//				console.log('Itemid', itemid);
//				var data = {
//								//'method':'getForm',
//								option	  : 'com_ajax',
//				module	  : 'multi_form',
//				format	  : 'raw',//raw  json  debug
//				id		  : e.data.id,
//				currentPage : document.location.href,
//								title	   : document.title
//				};

//			console.log('request',data,data.values(),form,' Captcha:',this.captcha,' Token:',this.token, ' Response:',this.response);


//			for(let k in data.values())
//				console.log('key',k);
//			console.log('---------');
this.deb &&		 console.log('--------- --------- ---------');
//			for(let v of data.values())
//				console.log('val:',v);
//			return;


//jQuery('#file')[0].files
//jQuery("#file").prop('files')[0]


//data.append('img', $input.prop('files')[0]);
//data.append('file-'+i, file);
//request = fd;
//-----------------------								
	//Собираем данные с полей формы - js формирующий данные
	// Список наименований полей из XML 
	for (let field of this.fields) {
		if (field.substring(0, 4) === 'file') {
			for (let file of  document.getElementById(field).files) {//jQuery('#'+field)[0].files
				//data[field] = jQuery('.input[name='+field+']').val();
				data.append(field, file); //+'-'+i
			}
		} else {
			//data[field] = jQuery('#'+field).val();//data[field] = jQuery('.input[name='+field+']').val();
//						data.append(field, jQuery('#'+field).val());
		}
	}
//-----------------------				

	// $( this ).serializeArray() ); // https://api.jquery.com/serializeArray/
//				console.log('e.data.fields',e.data.fields); 
let vals = data.values();
let etrs = data.entries();
console.log('data:',vals,' etrs:',Object.fromEntries(etrs)); 
//return false;
//window['data'+this.id] = data;
//console.log(this.deb);

let url = window.location.origin + '/index.php';
	url = document.baseURI + 'index.php';
this.deb && console.log('Url Submit:', url);
this.deb && console.log('Ajax request ModuleID:', this.id, ' ', data);
this.deb && console.log(data);
	jQuery.ajax({type: 'POST', url: url, dataType: 'html', data: data, context: this, cache: false, contentType: false, processData: false})
			.done(mfAjaxDoneSuccess).fail(mfAjaxFailForm);
}


/**
 * Вызывается после Успешной отправки данных формы
 * Send F	-/ Метод выполняется при получении HTML от AJAX запроса
 * @param string html
 * @param {type} status
 * @returns bool
 */
function mfAjaxDoneSuccess(html, status) {
	this.deb && console.log("Send Success! html:",[html]);  
	this.deb && console.log("Send Success! status:", status);

	// Вызов пользовательского скрипта из модуля. Который пользователь прописал в параметрах модуля
	let func_custom = window['funcAfter' + this.id];
	if (func_custom && typeof func_custom === 'function') {
		func_custom.apply(this);//, this.id
	}
	
	const dialog = document.getElementById('mfForm_' + this.id);
	
	const panelForm		= dialog.querySelector('.mfPanelForm');
	const panelFrame	= dialog.querySelector('.mfPanelFrame');
	const panelStatus	= dialog.querySelector('.mfPanelDone');
	const panelError	= dialog.querySelector('.mfPanelError');
	const output		= dialog.querySelector('output');
	
	let params = this;
	
	let animatePanel = null;
	
	if(location.hash.startsWith('#mod',0)){
		location.hash = '' 
	}
//console.clear();
console.log(html);

	if(panelForm && html.indexOf('<form') > -1){
		const div = document.createElement('div');
		div.innerHTML = html;
		panelForm.innerHTML = div.querySelector('form').innerHTML;
		animatePanel = panelForm;
		return;
	}

	
	
	if(panelForm && html.indexOf('http') == 0){
		
		jQuery.ajax({type: 'POST', url: html, dataType: 'html', context: params, cache: false, contentType: false, processData: false})
			.done(mfAjaxDoneSuccess).fail(mfAjaxFailForm);
		animatePanel = panelForm;
		return;
	}
//	const div = document.createElement('div');
//	div.innerHTML = html;
	
//	jQuery('.mfPanelDone.id' + this.id).html(html);
//	document.querySelector('.mfPanelDone.id' + this.id).appendChild(iframe);
//	const panelStatus = document.querySelector('.mfPanelDone.id' + this.id);
	
	if(window.event.altKey){
		console.clear();
		console.log(html);
	}else{
//		if()
		if(panelFrame && html.indexOf('<output') == -1){
//console.log(html);
			panelFrame.innerHTML = html;
//			jQuery(panelFrame).fadeIn("500");
			panelFrame.style.display = 'block';
			animatePanel = panelFrame;
		}
		else if(output){
//console.log(html);
			const div = document.createElement('div');
			div.innerHTML = html;
			output.innerHTML = div.querySelector('output').innerHTML;
			
			animatePanel = panelForm;
		}
		
	}
	
	
	
	const iframe = dialog.querySelector(':scope  .mfPanelFrame > iframe, :scope  output > iframe');
//console.log(iframe);
	if(iframe){
		
		jQuery(dialog.querySelector('form')).fadeOut("500");
		
//document.baseURI
console.log(this,'iframe->load:',iframe,' iframe.contentDocument:',iframe.contentDocument);
//console.log('iframe:',iframe);
		iframe.addEventListener("load", function(){
//console.clear();// this = iframe
console.log('iframe->LOADED !!! :',iframe,' iframe.contentDocument:', iframe.contentDocument);

			const contentDocument = iframe.contentDocument;
			if(contentDocument && (document.location.hostname == contentDocument.location.hostname
				|| contentDocument.URL == '') && contentDocument.readyState == "complete"){
console.log('iframe->LOADED->textContent ::: :', iframe.contentDocument.textContent);
				 
				 

//const url = new URL(document.querySelector('#mfForm_175 iframe').contentDocument.location.href);
//url_string = "https://example.com?options[]=one&options[]=two";
//url = new URL(url_string);
//options = url.searchParams.getAll("options[]");
//url.searchParams.delete('ajax');
//url.searchParams.append('reboot','');
//url.href
//append()
//delete()
//entries()
//get
//set
//has()
//keys()
//values()
//console.log(options);
// order=123&pass=lsakdjflaskdjfalksdjflaskjeizxkcvjo
//console.log(' iframe.contentDocument:',iframe.contentDocument);

				animatePanel = iframe.closest('.form, .mfPanelFrame');
				jQuery(animatePanel).fadeIn("500");
				
				let reload = iframe.dataset.reload ?? '';
				let order = iframe.dataset.order ?? 0;
console.log("Iframe LOADED !!",order,reload);

				if(reload)
				jQuery.ajax({type: 'POST', url: reload, dataType: 'html', context: params, cache: false, contentType: false, processData: false})
					.done(mfAjaxDoneSuccess).fail(mfAjaxFailForm);
				return;
				
				
				let href = contentDocument.location.href;
				
console.log("Iframe LOADED !",href);
				
				if(href == '' || href == '[{RELOAD}]' || contentDocument.body.innerText.indexOf('[{RELOAD}]') > -1){
					href = document.baseURI + '?option=com_ajax&module=multi_form&format=raw&id='+params.id;
				}else{
					const url = new URL(contentDocument.location.href);
					url.searchParams.delete('ajax');
					href = url.href; //contentDocument.URL;//contentDocument.location.href;
				}
				
console.log("Iframe LOADED !!",order,href);

				if(reload)
				jQuery.ajax({type: 'POST', url: href, dataType: 'html', context: params, cache: false, contentType: false, processData: false})
					.done(mfAjaxDoneSuccess).fail(mfAjaxFailForm);
			
			}
		});
	
		return;
	}
	
	
//	document.getElementById(`.mfPanelDone.id${this.id} > iframe`).onload
//	document.querySelector('.mfPanelDone.id' + this.id).contentDocument;
//	document.querySelector('.mfPanelDone.id175 > iframe').src
//	document.querySelector('.mfPanelDone.id175 > iframe').baseURI
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.documentURI
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.baseURI
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.URL 
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.location 
//	document.querySelector('.mfPanelDone.id' + this.id).contentDocument.body;
//	var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
	
	const iform = panelStatus.querySelector(':scope > form');
	if(iform){
		
		return true;
		if(! iform.id)
			iform.id = Date.now();
		
		
		jQuery('#submit_' + this.id).button('ready')
			.css('text-transform', '').css('transform', '').addClass('active').attr('form', 'mfForm_form_'+iform.id); //Тект кнопки при отправке
		
		
		return true;
	}
	
	
//	html = div.innerHTML;
	
		
		
	
	
//			htmlObject.innerHTML = s;
//htmlObject.getElementById("myDiv").style.marginTop = something;
	
	
//	document.querySelector('.mfPanelDone.id' + this.id).appendChild(iframe);
//	jQuery('.mfPanelDone.id' + this.id).html(html);
	
	
	
//	document.getElementById(`.mfPanelDone.id${this.id} > iframe`).onload
//	document.querySelector('.mfPanelDone.id' + this.id).contentDocument;
//	document.querySelector('.mfPanelDone.id175 > iframe').src
//	document.querySelector('.mfPanelDone.id175 > iframe').baseURI
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.documentURI
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.baseURI
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.URL 
//		document.querySelector('.mfPanelDone.id175 > iframe').contentDocument.location 
//	document.querySelector('.mfPanelDone.id' + this.id).contentDocument.body;
//	var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
	
//console.clear();
//console.log('# '+this.id);
	let posAfterSend = positionAfterSend('#mfForm_' + this.id);
//	posAfterSend = 0;
//console.log('posAfterSend',posAfterSend);
//console.log('# '+this.id,' FormDone.');
	
	

//console.log(this);
	if (this.type === 'popup') {
		
//		if(! iframe)
//		html
//		params.clearaftersend


			jQuery('.mfPanelForm.id' + params.id).fadeOut("1000");
			jQuery('.modal-footer.id' + params.id).fadeOut("16000");
			jQuery('.mfBeforeText.id' + params.id).fadeOut("16000");
			jQuery('.mfPanelDone.id' + params.id).fadeOut("16000");
//			jQuery('.mfPanelFrame.id' + params.id).fadeOut("500");
			let animClose = function () {
				
				
//console.log(this.type,' notFrame FormDone .',animatePanel);
	
		dialog.close(); 
//				jQuery('.mfPanelDone.id' + params.id).fadeIn("500");
//				jQuery(animatePanel).fadeIn("12000");

//				if (params.clearaftersend) {////Очищать форму $param->clearaftersend
//					hideAndClearFormAfterSend(
//							params.id,
//							'#mfForm_' + params.id,
//							'#mfOverlay_' + params.id,
//							'.mfPanelDone.id' + params.id,
//							html
//							);
//				} else {
//
//					hideBlockFormAfterSend(
//							params.id,
//							'#mfForm_' + params.id,
//							'#mfOverlay_' + params.id,
//							'.mfPanelDone.id' + params.id,
//							html
//							);
//				}
			};
			setTimeout(animClose, 19000);
			
		
		
//		const funAnimateClose = function () {
//			jQuery('.modal-footer.id' + params.id).fadeOut("500");
//			jQuery('.mfBeforeText.id' + params.id).fadeOut("500");
//			
//			jQuery('.mfPanelForm.id' + params.id).fadeOut("500", function () {
//				
//				jQuery('.mfPanelDone.id' + params.id).fadeIn("500");
//
//				if (params.clearaftersend) {////Очищать форму $param->clearaftersend
//					
//					hideAndClearFormAfterSend(
//							params.id,
//							'#mfForm_' + params.id,
//							'#mfOverlay_' + params.id,
//							'.mfPanelDone.id' + params.id,
//							html
//							);
//				} else {
//
//					hideBlockFormAfterSend(
//							params.id,
//							'#mfForm_' + params.id,
//							'#mfOverlay_' + params.id,
//							'.mfPanelDone.id' + params.id,
//							html
//							);
//				}
//			});
//		};
//		
//		if(iframe)
//			jQuery('#mfForm_' + this.id).animate({top: -posAfterSend}, 400, funAnimateClose);
//		else
//			funAnimateClose();
	}
	if (this.type === 'static') {

		
		
		jQuery('.mfPanelForm.id' + params.id).fadeOut("500", function () {
			
			
			jQuery('.mfPanelDone.id' + params.id).fadeOut("500");
			jQuery('.static-footer.id' + params.id).fadeOut("500");
			jQuery('.mfBeforeText.id' + params.id).fadeOut("500");
			jQuery('.mfPanelFrame.id' + params.id).fadeOut("500");
			
			jQuery(animatePanel).fadeIn("500");
			
			
			

			if (params.clearaftersend) {
				hideAndClearStaticFormAfterSend(
						params.id,
						'#mfForm_' + params.id,
						'.mfPanelDone.id' + params.id,
						html,
						jQuery('input#submit' + params.id).attr('value')
						);
			} else {
				hideBlockStaticFormAfterSend(
						params.id,
						'#mfForm_' + params.id,
						'.mfPanelDone.id' + params.id,
						html
						);
			}
			mfScrollStatic_Click.call(params, {data: params, first: true, preventDefault: function () {
					params.deb && console.log('🏆 mfScrollStatic_Click ', "#mfForm_form_" + params.id, params);
					return true;
				}});
		});
	}

	
}



/*
 * Получение объекта всех значений из хеша URL
 * @returns {Boolean|mfGetUrlHash.hash}
 */
function mfGetUrlHash(url) {

	let is_mod = false;
	let id = 0;

	let hash = {};
	Object.assign(hash, jQuery.url('#', url));

//	console.log('hash',hash);
//	console.log('url',jQuery.url());
	let str_hash = jQuery.url('hash', url) || '';
	let amp = str_hash.indexOf("&");

	amp < 1 && (amp = undefined); //str_hash.length
//console.log(amp,' ',str_hash);
//	console.log(id);
	id = str_hash.startsWith("mfForm_form_") ? str_hash.substring(12, amp) : id;
	id = str_hash.startsWith("mfForm_") ? str_hash.substring(7, amp) : id;
	id = str_hash.startsWith("mod_") ? str_hash.substring(4, amp) : id;
	id = str_hash.startsWith("mod") ? str_hash.substring(3, amp) : id;
	hash.id = id || false;
	hash.path = jQuery.url('path', url);
	hash.hostname = jQuery.url('hostname', url);
	hash.query = jQuery.url('query', url);
	hash.url = jQuery.url('', url);

	//console.log('%c '+id+' ! ','background: #222; color: #bada55');
//	console.warn('id:',id,' amp:',amp,'  ',str_hash,'   ',url);
//	console.log('hash',id,hash,url);
//	console.log(url);

	return hash;
}
/**
 * Получение данных полей из модуля
 * @param {number} id
 * @returns {Object|Boolean}
 */
function mfGetModuleById(id) {
	return (jQuery('.mfForm.id' + id).data()) || false;
}
/**
 * Переход, перемотка к форме, или  открытие формы диалогового окна
 * @param {object} module
 * @param {array} hash
 * @returns {object}
 */
function mfGotoModule(module, hash) {
	for (let field of module.fields) {
		if (hash[field]) {
			document.getElementById(field).value = hash[field];
		}
	}

	if (module.type === 'static')
		mfScrollStatic_Click.call(module, {data: module, first: true, preventDefault: function () {
				module.deb && console.log('🏆 mfScrollStatic_Click ', "#mfForm_form_" + module.id, module);
				return true;
			}});

	if (module.type === 'popup')
		mfOpenModal_Click.call(module, {data: module, first: true, preventDefault: function () {
				module.deb && console.log('🏆 mfOpenModal_Click ', "#mfForm_form_" + module.id, module);
				return true;
			}});
}
/**
 * Получение всех ссылок сайта на модуль
 * @returns array
 */
function mfGetAllActions() {
	return (jQuery('a:not(.mfGo)')
			.filter(function () {
				let href = this.getAttribute("href");
				if (!href)
					return false;
				if (href.indexOf("#") === -1 || href.length < 4)
					return false;
				return true;
			}).map(function () {
		let hash = mfGetUrlHash(this.getAttribute("href"));
		hash.control = this;
		return hash;
	}).filter(function () {
		return this.id;
	}).get());
}
/**
 * Получение всех модулей на странице
 * @returns array
 */
function mfGetAllModules() {
	let modules = {};
//	let mods = jQuery('.mfForm').map(function () {
//		let data = jQuery(this).data();
//		data.control = this;
//		return data;
//	});
//	
	for(let module of jQuery('.mfForm')){
		jQuery(module).data().control = module;
		
		if (module.type)
			modules[module.id] = module;
	}
//	for (let module of mods) {
//		if (module.type)
//			modules[module.id] = module;
//	}
	return modules;
//	return mods;
}
/**
 * Инициализация элементов управления после загрузки всех форм
 * Выполнение ReCaptcha, Загрузка MCE Tiny, Назначение ссылок для вызова форм
 * @param {type} p1
 * @returns {undefined} 
 */
function mfAjaxCompleteAllForm(p1) {

	let mods = Array.isArray(this)? this : [this];
//return;
console.log('mfAjaxCompleteAllForm()',mods);

	/**
	 * Инициализация Recaptcha для форм.
	 */
	for (let module of mods) {
		if (! module.captcha) 
			continue;
		
		let captcha_type = module.captcha;//recaptcha_invisible, recaptcha
//console.log('-----> Captcha-RENDER() !!!!!->', 'dynamic_captcha_' + module.id, module.captcha, module);
		Object.assign(module,jQuery('#dynamic_captcha_'+module.id).data());
//		module.control.dataset.WidgetId = 
		module.WidgetId = module.grecaptcha = false;
//data-recaptcha-widget-id			
		let Captcha_Render = ()=>{
//			return;
//window.JoomlaInitReCaptcha2();
//window.JoomlaInitReCaptchaInvisible();
			//module.control.dataset.recaptchaWidgetId = 
			//grecaptcha.render() return  0 или 1 или 2
//			module.control.dataset.WidgetId = 
			module.WidgetId = module.grecaptcha = grecaptcha.render(
				'dynamic_captcha_' + module.id, //'dynamic_captcha_' + module.id //'submit_' + module.id
				{
				'callback': function (response) { //Выполняется при успешной проверке, возвращает маркер
					//return;
					   module.response = response;
					   //let form = document.querySelector("#mfForm_form_" + module.id); //mfForm_form_313
					   let form = document.getElementById("mfForm_form_" + module.id)
			console.log('(II) CallBack() !!!!!->', '#dynamic_captcha_' + module.id, module.captcha,  module, ' Form:',form);//module.response,
						if(!response)
							return;
						
						if(module.captcha == 'recaptcha'){ 
							mfButtonSubmit_Click.call(module, {data: module, first: true, preventDefault: function () {
									this.deb && console.log('🏆 mfButtonSubmit_Click ', "#mfForm_form_" + this.id, this);
									return true;
							}});
							return;
					   }else{
							submitHandlerSubmit.call(module, form);
					   }
					
			console.log('(IV)  Response-CallBack!!!!! --> R:', response, ' M:', module);
					if(module.xCallback && module.xCallback.trim()){
					   try {
					   let callback = module.xCallback.trim(); 
					   eval(callback+'()'); 
					   //callback();
						//eval(module.xCallback);
					   }catch(e){
					   }
					}
						
					//submitHandlerSubmit.call(params,form);
					//submitHandlerSubmit.call(data,form,event); 
				},
				'expired-callback': function (response) {//Выполняется при истечении срока действия ответа reCaptcha и требует новой проверки.
					if(module.xExpiredCallback && module.xExpiredCallback.trim()){
					   try {
					   let callback = module.xExpiredCallback.trim(); 
					   eval(callback+'()');
//					   callback();
//						eval(module.xExpiredCallback);
					   }catch(e){
					   }
					}
					console.log('(-II)  Delay-CallBack!!!!!-->R:', response, ' M:', module, 'this:', mods);
					//submitHandlerSubmit.call(data,form,event); 
				},
				'error-callback': function (response) {//Выполняется при ошибке проверки, обычно это отсутсвие сети, нужно информировать юзера о повторном подключении(проверке).
					if(module.xErrorCallback && module.xErrorCallback.trim()){
					   try {
					   let callback = module.xErrorCallback.trim(); 
					   eval(callback+'()');
//					   callback(); 
//						eval(module.xErrorCallback);
					   }catch(e){
					   }
					}
					console.log('(-II)  Error-CallBack!!!!!-->R:', response, ' M:', module, 'this:', mods);
					//submitHandlerSubmit.call(data,form,event); 
				}
			});
//			module.response = grecaptcha.getResponse(params.WidgetId);
//			grecaptcha.getResponse(module.WidgetId);
//			grecaptcha.execute(module.WidgetId);
//console.log('-----> Captcha-RENDER() !!!!!->', 'dynamic_captcha_' + module.id, module.captcha, module);
			};
			
		let timerId = 0;
//		
//		const dialog = document.getElementById('mfForm_175'+this.id)
		
//		setTimeout(function, delay);
//		setInterval(function, delay);
		setInterval( ()=>{
			
			if(!grecaptcha)
				return;
			
			clearInterval(timerId);
			
			if(module.grecaptcha)
				return;
			
			Captcha_Render();
			
		}, 1000);
//https://www.google.com/recaptcha/api.js?onload=JoomlaInitReCaptchaInvisible&render=explicit&hl=ru-RU
//https://www.google.com/recaptcha/api.js?onload=JoomlaInitReCaptcha2&render=explicit&hl=ru-RU
//			console.log('----->2 CaptchaAllForm!!!!!->', 'dynamic_captcha_' + module.id, module.captcha, module);
			//'sitekey':mods.
			// 1. Создать функцию CaptchRender для классов g-captcha
			// 2. Создать обработчик CallBak для Render.
			// 3. Создать PHP Проверку.
		
//console.log('66666666666 ',mods );
	}




	console.log('jQuery.validator.messages',jQuery.validator.messages );
//	jQuery("form").validate({messages:jQuery.validator.messages});  

	/**
	 * Инициализация Редактора TinyMCE
	 */
//	jQuery.getScript(document.location.origin+'/media/editors/tinymce/tinymce.min.js',function(){	});
	let tinyeditor = () => false;
	if (typeof tinymce === 'object') {
		tinyeditor = function () {
			tinymce.init({selector: '.joomla-editor-tinymce', menubar: false,
				toolbar: 'undo redo | formatselect | bold italic backcolor strikethrough | removeformat  pastetext | alignleft aligncenter alignright alignjustify   | bullist numlist table outdent indent ',
				plugins: ['advlist autolink lists link image charmap print preview anchor', 'searchreplace visualblocks code fullscreen', 'insertdatetime media table paste code   wordcount']});
		};
	}
	let userLang = navigator.language || navigator.userLanguage;
	let edit_path = Joomla.iframeButtonClick ? '/media/vendor/tinymce/tinymce.min.js' :
			document.location.origin + '/media/editors/tinymce/tinymce.min.js';
	let lang_path = Joomla.iframeButtonClick ? '/media/vendor/tinymce/langs/' + userLang + '.js' :
			document.location.origin + '/media/editors/tinymce/langs/' + userLang + '.js';
//	console.log(lang_path);
	jQuery.getScript(edit_path).always(_ => jQuery.getScript(lang_path).always(tinyeditor));




	let hash = mfGetUrlHash(); // URL
//console.log('HASH',hash);

	let module = hash.id ? mfGetModuleById(hash.id) : false; // Модуль из URL
	module && mfGotoModule(module, hash);// Переход к модулю указанному в URL и присвоение полей


	let actions = mfGetAllActions();
	let modules = mfGetAllModules();
 
	let thisTrue = true;
//	console.log('actions ',actions);
//	console.log('modules ',modules);
//	console.log('hostname','path','query','id',' ------ Actions Count:',actions.length);
//	console.log('HASH ',hash);
	for (let action of actions) {
//		var action = actions[i];
		if (!(action.id in modules))
			continue;
		let module = modules[action.id];
		action.module = module;
//		console.log(i+' action ',action);

		if(action.url.startsWith("#") || 
			(hash.hostname === action.hostname || action.hostname === '')
			&&
			(hash.path === action.path || (['', '/', 'index.php', '/index.php'].includes(hash.path) && ['', '/', 'index.php', '/index.php'].includes(action.path)))
			&&
			hash.query === action.query
		){
		 
				//&& ((console.log(' --------Type==PopUp',modules[action.id].type==='popup'))|| true )
				//&& ((console.log(' --------action',action.id,modules[action.id].type,action))|| true )
				
				if(modules[action.id].type === 'static'){
					jQuery(action.control).click(function (e) {
						e.preventDefault();
						mfScrollStatic_Click.call(action, {data: modules[action.id], first: true, preventDefault: ()=>true});
						return false;
					});
				}
				
				if(modules[action.id].type === 'popup'){
					jQuery(action.control).click(function (e) {
						e.preventDefault();
						mfOpenModal_Click.call(action, {data: modules[action.id], first: true, preventDefault: ()=>true});
						return false;
					});
				}
				 
								//&& true 
								//&& console.log(' --------action',action);
								
						//ПРИСВОИТЬ КЛИК для ссылок этой страницы

		}
	}
//	return;
//	console.log('actions ',actions);
//	console.log(p1);
		console.log('🏆🏆🏆🏆🏆🏆🏆🏆 Module :',mods);
					//$( mods ).serializeArray()

					//var hash = location.hash.substring(1); 
	jQuery(":input").inputmask();
}

jQuery(function () {

	let mfButtons = {};
	let itemid = 0;

	for (let clss of document.body.classList) {
		if (clss.toString().substr(0, 7) === 'itemid-') {
			itemid = clss.toString().substr(7);
		}
	}
//console.clear();
//console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');

	jQuery.when(...jQuery('.mfForm')//.get().reverse()
		.filter(function (i, form_mod) {
			let params = jQuery(form_mod).data();
			if (!params.id) {
				return false;
			}
//if(params.id!=175)
//return false;
//console.log(i,form_mod );
			
			
			params.button = form_mod.id;//jQuery(form_mod).get(0).id;
			params.tag = form_mod.tagName;

//console.log(i,'-form ',params.id,' ',params.tag,' ----------', params.button );
//console.log(i,'-form ',params.id,' params:',params,' ----------', params.button );

			if (!(params.id in mfButtons)) {
				mfButtons[params.id] = [];
			}

			if (['a', 'button', 'A', 'BUTTON'].includes(params.tag)) {
				mfButtons[params.id].push(form_mod);//params.button
			}

			if ('mod_' + params.id !== params.button)
				return false;
			
			params.buttons = mfButtons[params.id];
			form_mod.params = params;
//console.log(i,'-form ',params.id,' params:',params,' ----------', params.button );
//console.log(i,form_mod );
			return true;
		})
		.sort((a,b)=>a.dataset.captcha.length - b.dataset.captcha.length)
		.map(function (index, form_mod) {//.popup
//console.log(index);
//console.log(form_mod);
//			var params = form_mod.params; 
//			form_mod.params.deb && console.log('moduleID:'+form_mod.params.id,form_mod.params);
								//params.buttons = mfButtons[params.id];
//			params.deb = true;
//			console.log('params:',form_mod.params);
//			params.id = jQuery(form_mod).data('id');
//			params.deb = jQuery(form_mod).data('deb');
//			params.type = jQuery(form_mod).data('type');

			let url = window.location.origin + '/index.php';
				url = document.baseURI + 'index.php';
//console.log(url);
			
//			let request = {id: form_mod.params.id, format: 'raw', module: 'multi_form', option: 'com_ajax', method: 'getForm', Itemid: itemid};
			let request = Object.assign({}, form_mod.dataset);
			request.id = form_mod.params.id;
			request.format = 'raw';
			request.module = 'multi_form';
			request.option = 'com_ajax';
			request.method = 'getForm';
			request.Itemid = itemid;
			request.title  = document.title;
			request.url	   = document.url;
			
//console.log('form_mod.params.button.dataset',form_mod, form_mod.dataset);
			
//console.log('!!!',{type: 'POST', url: url, dataType: 'html', data: request, context: form_mod.params});
			return jQuery.ajax({type: 'POST', url: url, dataType: 'html', data: request, context: form_mod.params})
					.done(mfAjaxDoneForm)
					.fail(mfAjaxFailForm);
		})
	)
//	.then(function(){console.log('GOOD !!!!');},function(){console.log('BAD !!!!');})
//	.done(function(){console.log('GOOD !!!!');})
	.done(mfAjaxCompleteAllForm);
//	console.log(mfButtons);




//		console.log('mfButtons:',mfButtons);
					//hidden text textarea editor telephone email select radio checkbox color 
});