//use 'esversion: 6';
// jshint esversion: 6
/*jshint esversion: 6 */
jQuery(function ($) {
    if($('#jform_params_default option[value="default.php"]:selected').length) {
        $('.mfFree, .mfJed').hide();//Pro
    } 
    if($('#jform_params_default option[value=""]:selected, #jform_params_jed option[value=""]:selected').length == 2) {
        $('.mfPro, .mfJed').hide();//Free
    } 
    if($('#jform_params_default option[value=""]:selected, #jform_params_jed option[value="jed.php"]:selected').length == 2) {
        $('.mfPro, .mfFree').hide();//Jed
    }
    
	function iconfiled(listTypeOne) {
		let adClas = '';
		switch (listTypeOne) {
			case "text":
			case "text_art":
			  adClas = "icon-pencil-2  large-icon";
			  break;
			case "textarea":
			case "textarea_art": 
			case "EditorTranslate":
			case "editor":
			  adClas = "icon-edit  large-icon";
			  break;
			case "telephone":
			  adClas = "icon-phone-2 large-icon";
			  break;
			case "email":
			  adClas = "icon-mail  large-icon";
			  break;
			case "select":
			  adClas = "icon-menu-3  large-icon";
			  break;
			case "radio":
			  adClas = "icon-radio-checked  large-icon";
			  break;
			case "checkbox":
			  adClas = "icon-checkbox  large-icon";
			  break;
			case "color":
			  adClas = "icon-color-palette  large-icon";
			  break;
			case "hidden":
			  adClas = "icon_multiform-hidden large-icon";
			  break;
			case "_separate":
			case "separate":
			  adClas = "icon-minus-2  large-icon";
			  break;
			case "_htmltagstart":
			case "htmltagstart":
			  adClas = "icon-minus-2 large-icon";
			  break;
			case "_htmltagfinish":
			case "htmltagfinish":
			  adClas = "icon-minus-2 large-icon";
			  break;
			default:
			  adClas = "icon-info-circle large-icon";
			  break;
			}
			return adClas;	
	}
	
//	function radioValueChanged() {
//            return;
//        radioValue = $(this).val();
//        if($(this).is(":checked") && radioValue == "yes") {
//            $(this).parent('fieldset').parent('td').removeClass('redRadio');
//            $(this).parent('fieldset').parent('td').parent('tr').addClass('onTrRadio');
//        } else {
//            $(this).parent('fieldset').parent('td').addClass('redRadio');
//			$(this).parent('fieldset').parent('td').parent('tr').removeClass('onTrRadio');
//        }
//	}

//	function sortionTR() {
//            return;
//		var oneThisTDInput = $('#jform_params_list_fields_modal tbody tr');
//		$.each(oneThisTDInput, function (index, value) {
//			var str = $(this).find('td:nth-child(6) input');
//                        console.log(str.attr('name') + ' ' +str.val());
//			$(this).find('td:nth-child(6) input').val(index);                        
//		});
//                
//                        console.log( ' ');
//	}
	$(window).ready(function () {
//		/*Скрытие и показ полей, относящихся к PopUp*/
//		var popupYesNoLabel = $('#jform_params_popup .active').attr('for');
//		var popupYesNoInput = $('#'+popupYesNoLabel).val();
//		var textPopupBtn = $('#jform_params_textcallpopup').parents('.control-group');
//		var textBeforeField = $('#jform_params_textbeforeCallButton').parents('.control-group');
//		if(popupYesNoInput == 0) {
//			$(textPopupBtn).css('display', 'none');
//			$(textBeforeField).css('display', 'none');
//		}else{
//			$(textPopupBtn).css('display', 'block');
//			$(textBeforeField).css('display', 'block');
//		}
//		$('.popup-yes-no input').click(function () {
//			var popupYesNoLabel = $('#jform_params_popup .active').attr('for');
//			var popupYesNoInput = $('#'+popupYesNoLabel).val();
//			if(popupYesNoInput == 0) {
//				$(textPopupBtn).hide(300);
//				$(textBeforeField).hide(300);
//			}else{
//				$(textPopupBtn).show(300);
//				$(textBeforeField).show(300);
//			}
//		});
		
                        
		/*Открытие списка полей*/
		$('#jform_params_list_fields_button').on('click',function () {
//			
			/*Смена иконки типа поля*/
			$('#jform_params_list_fields_modal tbody tr td select[id $=-jform_formfields_field_type]').on('click',function () {//'',mouseout change select
                            //console.log(":)");
                            $('select[name="platform"] :selected').attr('class');
                            var option_class = $(this).find(':selected').attr('class');
                            $(this).attr('class', 'select_type ' + option_class);
                            
                            var parentTD = $(this).parent('td');
                            var parentTR = $(this).parent('td').parent('tr');
                            
                            $(parentTD).attr('class', 'change icon_multiform-' + option_class);
                            $(parentTR).attr('class', 'row_type ' + option_class);
                             
                            if(option_class == 'telephone' || option_class == 'phone')
				$(parentTR).find('.params_type').fadeIn(300).val("+999(999) 999-9999");
                            if(option_class == 'select' || option_class == 'radio' || option_class == 'checkbox')
                            	$(parentTR).find('.params_type]').fadeIn(300).val("params1; params2; params3");
                            if(option_class == '' || option_class.substring(0,1)=='_')
                            	$(parentTR).find('.params_type]').fadeOut(300);
                            else
                                $(parentTR).find('.params_type]').fadeIn(300);
//icon_multiform-mail icon_multiform-telephone icon_multiform-text icon_multiform-checkbox icon_multiform-dropdownlist icon_multiform-radio icon_multiform-separate icon_multiform-textarea icon_multiform-color icon_multiform-hidden icon_multiform-startblock icon_multiform-finishblock
                            return;
//				var oneThisSpan = $(this);
//				$(parentDiv).addClass('change');
//				if(parentDiv.hasClass('change')) {
//					var actualType		= $(parentDiv).attr("data-type-field");
//					var listTypesOne	= $(this).val();
//					
//					if(listTypesOne == 'color' || listTypesOne == 'hidden' || listTypesOne == 'email' || listTypesOne == 'separate' || listTypesOne == 'htmltagstart' || listTypesOne == 'htmltagfinish' || listTypesOne.substring(0,1)=='_') {
//						$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').fadeOut(300);
//					}else{
//						$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').fadeIn(300);
//						if(listTypesOne == 'telephone') {
//							$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').val("+38(099)999-99-99");
//						}
//						if(listTypesOne == 'select' || listTypesOne == 'radio' || listTypesOne == 'checkbox') {
//							$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').val("params1; params2; params3");
//						}
//					}
//					
//					/*Условие для блокирования полей типа DIV*/
//					if(listTypesOne == '_htmltagstart') {
//						$(parentTR).find('td input[id$=jform_formfields_namefield]').val("div").prop( "disabled", true );
//						$(parentTR).find('td input[id$=jform_formfields_placeholder]').prop( "disabled", false );
//					}else if(listTypesOne == '_htmltagfinish') {
//						$(parentTR).find('td input[id$=jform_formfields_namefield]').val("div").prop( "disabled", true );
//						$(parentTR).find('td input[id$=jform_formfields_placeholder]').val("").prop( "disabled", true );
//					}else{
//						/*$(parentTR).find('td input[id^=jform_formfields_namefield]').val("").prop( "disabled", false );
//						$(parentTR).find('td input[id^=jform_formfields_placeholder]').val("").prop( "disabled", false );*/
//					}
//					
//					$(parentDiv).removeClass('icon_multiform-mail icon_multiform-telephone icon_multiform-text icon_multiform-checkbox icon_multiform-dropdownlist icon_multiform-radio icon_multiform-separate icon_multiform-textarea icon_multiform-color icon_multiform-hidden icon_multiform-startblock icon_multiform-finishblock').addClass(iconfiled(listTypesOne)).attr('data-type-field',listTypesOne);
//				}
			});
			$('#jform_params_list_fields_modal tbody tr td select[id $=-jform_formfields_field_type]').mouseout();
                        
                        
//			var oneThis = $('#jform_params_list_fields_modal tbody tr');
//			var heightTR = $( "#jform_params_list_fields_table tbody tr td" ).height();
//			$( "#jform_params_list_fields_table tbody tr" ).css('height',heightTR);
//			
//			$.each(oneThis, function (index, value) {
//				/*var actualType		= $(this).find('td select').val();*/
//				var listTypesOne	= $(this).find('td select[id $=-jform_formfields_field_type]').val();
//				var parentDiv = $(this)
//					/*.children('td:nth-child(3)')
//					.children('select');*/
//					.children('td:nth-child(3)');
//				if(listTypesOne == 'color' || listTypesOne == 'hidden' || listTypesOne == 'email' || listTypesOne == 'separate' || listTypesOne == 'htmltagstart' || listTypesOne == 'htmltagfinish' || listTypesOne.substring(0,1)=='_') {
//					$(this).find('td textarea[id$=jform_formfields_paramsfield]').css('display','none');
//				}
//				
//				$(parentDiv).removeClass('icon_multiform-mail icon_multiform-telephone icon_multiform-text icon_multiform-checkbox icon_multiform-dropdownlist icon_multiform-radio icon_multiform-separate icon_multiform-textarea icon_multiform-color icon_multiform-hidden icon_multiform-startblock icon_multiform-finishblock').addClass(iconfiled(listTypesOne)).attr("data-type-field",listTypesOne);
//				
//				/*Условие для блокирования полей типа DIV*/
//				if(listTypesOne == 'htmltagstart' || listTypesOne == '_htmltagstart') {
//					$(this).find('td input[id^=jform_formfields_namefield]').val("div").prop( "disabled", true );
//					$(this).find('td input[id^=jform_formfields_placeholder]').prop( "disabled", false );
//				}else if(listTypesOne == 'htmltagfinish' || listTypesOne == '_htmltagfinish') {
//					$(this).find('td input[id^=jform_formfields_namefield]').val("div").prop( "disabled", true );
//					$(this).find('td input[id^=jform_formfields_placeholder]').val("").prop( "disabled", true );
//				}
//			});
//			
		});
		
		/*Сортировка*/
//		$( "#jform_params_list_fields_table tbody" ).sortable({
//			//stop: sortionTR
//		});
		
		/*Подсветка радио кнопок*/
//		$('#jform_params_list_fields_modal tr td fieldset input[name$=onoff]').on('change',radioValueChanged);
//		
//		setInterval(function () {
//			var RVCh = $("#jform_params_list_fields_modal tr td fieldset input[name$=onoff]");
//			
//			$(RVCh).each(function () {
//				if($(this).is(':checked') && $(this).val() == "no") {
//					$(this).parent('fieldset').parent('td').addClass('redRadio');
//					$(this).parent('fieldset').parent('td').parent('tr').addClass('desabled-field');
//					$(this).parent('fieldset').parent('td').parent('tr').removeClass('onTrRadio');
//				}
//				
//				if($(this).is(':checked') && $(this).val() == "yes") {
//					$(this).parent('fieldset').parent('td').removeClass('redRadio');
//					$(this).parent('fieldset').parent('td').parent('tr').removeClass('desabled-field');
//					$(this).parent('fieldset').parent('td').parent('tr').addClass('onTrRadio');
//				}
//			});
//		}, 1000);

//		/*Добавление нового поля*/
//		$('#jform_params_list_fields_modal tr th:last-child div a.button').on('click', function () {
////			sortionTR();
//			/*var actualType		= $(this)
//									.parent('div')
//									.parent('th')
//									.parent('tr')
//									.parent('thead')
//									.parent('table')
//									.find('td select[id $=-jform_formfields_field_type]')
//									.val();*/
//			var listTypesOne	= $(this)
//									.parent('div')
//									.parent('th')
//									.parent('tr')
//									.parent('thead')
//									.parent('table')
//									.find('td select[id $=-jform_formfields_field_type]')
//									.val();
//			var parentDiv = $(this)
//								.parent('div')
//								.parent('th')
//								.parent('tr')
//								.parent('thead')
//								.parent('table')
//								.children('tbody')
//								.children('tr:last-child')
//								.children('td:nth-child(3)');
//			if(listTypesOne == 'text' || listTypesOne == 'textarea' || listTypesOne == 'color' || listTypesOne == 'hidden' || listTypesOne == 'email' || listTypesOne == 'separate' || listTypesOne == 'htmltagstart' || listTypesOne == 'htmltagfinish' || listTypesOne.substring(0,1)=='_') {
//				$(this).parent('div').parent('th').parent('tr').parent('thead').parent('table').find('tr:last-child td textarea[id$=jform_formfields_paramsfield]').css('display','none');
//			}
//			$(parentDiv).removeClass('icon_multiform-mail icon_multiform-telephone icon_multiform-text icon_multiform-checkbox icon_multiform-dropdownlist icon_multiform-radio icon_multiform-separate icon_multiform-textarea icon_multiform-color icon_multiform-hidden icon_multiform-startblock icon_multiform-finishblock').addClass('icon_multiform-text').attr('data-type-field','text');
//		});

		/*$( "#jform_params_list_fields_table tbody" ).disableSelection();*/
		return;
		/*Отслеживание наличия парных блоков НЕ ГОТОВО!!!*/
		setInterval(function () {
			
			var countStart = 0;
			var countFinish = 0;
			var countStartOn = 0;
			var countFinishOn = 0;
			
			var countStart = $("select[id$=jform_formfields_field_type] option[value=_htmltagstart]:selected").length;
			var countFinish = $("select[id$=jform_formfields_field_type] option[value=_htmltagfinish]:selected").length;
			
			var countStartOn = $("tr.onTrRadio select[id$=jform_formfields_field_type] option[value=_htmltagstart]:selected").length;
			var countFinishOn = $("tr.onTrRadio select[id$=jform_formfields_field_type] option[value=_htmltagfinish]:selected").length;


			/*Прячем или показываем кнопку SAVE в зависимости от соблюдения условия парности полей типа DIV*/
			if(countStartOn != countFinishOn) {
				if(countStartOn || countFinishOn) {
					/*alert("Внимание! В форме есть не закрытые теги блоков!");*/
					if(!$("div").is("#undabledBlocks")) {
						$("#jform_params_list_fields_modal .modal-footer").prepend('<div id="undabledBlocks"></div>');
					}
					$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeOut();
					$("#undabledBlocks").html("Имеются не закрытые блоки DIV или они не включены!");
				}
				$("#filedOrderBlocks").remove();
			}else{
				$("#undabledBlocks").remove();
				$("#filedOrderBlocks").remove();
				/*$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeIn();*/
				/*$("#undabledBlocks").html("");*/	
				
				/*Неверный порядок блоков DIV*/
				var lastEl = $("tr.onTrRadio select[id$=jform_formfields_field_type] option[value^=_htmltag]:selected").last().val();
				var firstEl = $("tr.onTrRadio select[id$=jform_formfields_field_type] option[value^=_htmltag]:selected").first().val();
				if(lastEl && lastEl != '_htmltagfinish') {
					
					/*alert(lastEl);*/
					/*alert("Внимание! В форме есть не закрытые теги блоков!");*/
					if(!$("div").is("#filedOrderBlocks")) {
						$("#jform_params_list_fields_modal .modal-footer").prepend('<div id="filedOrderBlocks"></div>');
					}
					$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeOut();
					$("#filedOrderBlocks").html("Неверный порядок. Закрывающий блок находиться до открывающего!");
				}else if(lastEl && lastEl != '_htmltagfinish' &&  firstEl && firstEl != '_htmltagstart') {
					$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeIn();
					$("#filedOrderBlocks").remove();
				}else{
					$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeIn();
				}
			}
		}, 1000);
			/*
	//	$('#jform_params_list_fields_modal .save-modal-data').hover(sortionTR);
			 */
	});
	jQuery('#module-form .form-inline-header').append('<div>'+Joomla.JText._('MOD_MULTI_TOP')+'</div>');
});


jQuery(function ($) {
function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

/*console.clear();*/

/*document.getElementById('module-form').dispatchEvent(new CustomEvent('selectfield_type',{detail:{name:this.value,id:this.id}})); */
/*document.getElementById('module-form').addEventListener('selectfield_type', ()=>0); */
document.getElementById('module-form').btnClose_Click = function(dialog){
		
		dialog.close();
		
		
		const isFloat = (n) => n === +n && n !== (n|0);
		const isInteger = (n) => n === +n && n === (n|0);
		
//console.log(dialog,this);
		const txtField		= dialog.querySelector('.valOption');
		const form			= dialog.querySelector('form');
		
		if(form.innerHTML == '' || form == null)
			return false;
		
		
		const obj = Array.prototype.reduce.call( form.elements,
			(acc, n) => {
				const isCheckbox = n.type === 'checkbox';
				const isRadio = n.type === 'radio';
				
				const keys = Array.from(n.name.matchAll(/\w+|\[(\w*)\]/g), n => n[1] ?? n[0]);
				const key = isCheckbox ? null : keys.pop();
				const isArr = !key;
				const values = keys.reduce((p, c, i, a) => {
					return p[c] ??= (-~i === a.length && isArr ? [] : {});
				}, acc);
				
				let value = n.value.trim();
				value  = isFloat(value) ? parseFloat(value) : isInteger(value) ? parseInt(value) : value;

				if (!isCheckbox || n.checked) {
					values[isArr ? values.length : key] ??= !isRadio || n.checked ? value : null;
				}
				return acc;
			},{}
		);
		delete obj['undefined'];
		
//		const obj = {};
//		new FormData(form).forEach((value, key) => obj[key] = value);
		txtField.value = JSON.stringify(obj);
		
//console.log('388 object',object);		
//console.log('389 txtField.value',txtField.value);		
//console.log('new FormData(form)',form, new FormData(form));
//console.log('new FormData(form)',form, object);
//console.log('object',object);
//console.log('JSON.stringify(object)',JSON.stringify(object));
//console.log('txtField.value',txtField.value);
		
		/* form.innerHTML = '';*/
		return false;
};
//for(const btnClose of Array.from(document.getElementById('module-form').querySelectorAll('.gridFields.table button.close'))){
//	btnClose.addEventListener('click', function(e) {
//	});
//}

document.getElementById('module-form').addEventListener('submit',function(){
	for(const form of Array.from(this.querySelectorAll('form.modal-dialog.modal-content'))){
		form.innerHTML = '';
		form.dataset.type = '';
		form.remove();
	}
});
 

document.getElementById('module-form').btnOption_Click = function (_id, name) {
	/*const modID = new URLSearchParams(window.location.search).get('id'); */
	
	const baseUrl	= (document.querySelector('base[href]') ?? {'href':''}).href; 
	
	const id		= _id.replace('btn_','');
	
//console.log(_id, id);
//console.clear();
	
	const selectType	= document.getElementById(id.replace('option_params','field_type'));
	const txtField		= document.getElementById('txt_' + id);
	
	let popContent	= document.querySelector('#txt_' + id + ' + form.modal-dialog.modal-content');
	if(popContent == null){
		popContent	= document.createElement('form');
		popContent.classList.add('modal-dialog');
		popContent.classList.add('modal-content');
		popContent.classList.add('modal-dialog-scrollable');
		popContent.classList.add('modal-dialog-centered');
		popContent.dataset.type = '';
//console.log(_id, id);
		document.getElementById('pop_' + id).appendChild(popContent);
//		document.getElementById('pop_' + id).innerHTML = popContent;
	}
 
	
	
	
	
//jform_params_list_fields_0_field_type_5	-- 
//jform_params_list_fields_0_paramsfield_
//jform_params_list_fields_0_field_type_

// btn_jform_params_list_fields_0_paramsfield_3
//	   jform_params_list_fields_0_field_type_3
// form_jform_params_list_fields_0_paramsfield_3	
// form_jform_params_list_fields_0_paramsfield_
//console.log(_id, id);
//console.log('#txt_' + id + ' + form.modal-dialog.modal-content', id);

	if(selectType == null){
		console.log('ERROR: document.getElementById(',id.replace('option_params','field_type'), ') = null -- поле Селект не найден!!! ',selectType);
		return;
	}

	if(!popContent || popContent.dataset.type == selectType.value)
		return;
	
//	const token = Joomla.getOptions('csrf.token');
//console.log('token',token);

	if(txtField && txtField.value == '') {
		
//console.log('token1',token);
		Joomla.request({
			url: baseUrl + `index.php?option=com_ajax&module=multi_form&format=raw&method=LoadOptions&type=json&name=${selectType.value}`,//&${token}=1
			method: 'GET',
			headers: {
				'Cache-Control' : 'no-cache',
				'Your-custom-header' : 'custom-header-value'
			},
			onError: function(xhr){
				
			},
			onSuccess: function(json) {

//console.log('json',json);
				txtField.value = json;
			}
		});
	}
	
//console.log('token2',token);
	
//console.log(baseUrl + index.php?option=com_ajax&module=multi_form&format=raw&method=LoadOptions&type=xml&name=${selectType.value}`);
	
	Joomla.request({
		url: baseUrl + `index.php?option=com_ajax&module=multi_form&format=raw&method=LoadOptions&type=xml&name=${selectType.value}`,//&${token}=1
		method: 'GET',
		headers: {
			'Cache-Control' : 'no-cache',
			'Your-custom-header' : 'custom-header-value'
		},
		onError: function(xhr){
//console.log(xhr);
			
		},
		onSuccess: function (html) {
			popContent.dataset.type = selectType.value;
	
			if(html == ''){
				txtField.style.display = "block";
				/*
		//		txtField.type = 'textarea';
		//		txtField.nodeName = 'TEXTAREA';
		//		txtField.name = 123;
		//		txtField.value = 123;
				 */

				popContent.innerHTML = '';
				return;
			}
//console.log('html',html);
	
			popContent.innerHTML = html; 
			/*
			// .insertAdjacentHTML('afterEnd', modal);
			//	popContent.appendChild(html);
			 */
	 
			txtField.style.display = "none";
		/*
		//	txtField.type = 'hidden';
		//	txtField.nodeName = 'INPUT';
		//	txtField.name = 123;
		//	txtField.value = 123;
		 */

			let valueFields = null;

			try {
				if(txtField.value){
					valueFields = JSON.parse(txtField.value);
//console.log('valueFields',valueFields);
				}
				else return;
			} catch (e) {
		/*
		//		txtField.type = 'textarea';
		//		txtField.nodeName = 'TEXTAREA';
		//		txtField.name = 123;
		//		txtField.value = 123;
		//		popContent.innerHTML = '';
					 */
				return;
			}
			
			
/**
 * 
 *  discuss at: https://locutus.io/php/is_scalar/
 *  original by: Paulo Freitas
 *  example 1: is_scalar(186.31)
 *  returns 1: true
 *  example 2: is_scalar({0: 'Kevin van Zonneveld'})
 *  returns 2: false
 * @param {mixed} mixedVar
 * @returns {Boolean}
 */
const is_scalar = mixedVar => /boolean|number|string/.test(typeof mixedVar);


//			Array.from(valueFields)

			let valuesF = [];

			for(const i in valueFields){
				let v = valueFields[i];
				if(is_scalar(v)){
					valuesF[i] = v; continue;
				}
				if(Array.isArray(v)){
					valuesF[i + '[]'] = v; continue;
				}
				for(const ii in v){
					let vv = v[ii];
					if(is_scalar(vv)){
						valuesF[i+'['+ii+']'] = vv; continue;
					}
					if( Array.isArray(vv)){
						valuesF[i+'['+ii+'][]'] = vv; continue;
					}
					
					for(const iii in vv){
						let vvv = vv[iii];
						if(is_scalar(vvv)){
							valuesF[i+'['+ii+']'+'['+iii+']'] = vvv; continue;
						}
						if(Array.isArray(vvv)){
							valuesF[i+'['+ii+']'+'['+iii+'][]'] = vvv; continue;
						}
					
						for(const iiii in vvv){
							let vvvv = vvv[iiii];
							if(is_scalar(vvvv)){
								valuesF[i+'['+ii+']'+'['+iii+']'+'['+iiii+']'] = vvvv; continue;
							}
							if(Array.isArray(vvvv)){
								valuesF[i+'['+ii+']'+'['+iii+']'+'['+iiii+'][]'] = vvvv; continue;
							}
					
						}
					}
				}
			}
			

//console.log('valuesF',valuesF);
//			let getChilds = (elem) => {
//				
//			};
//			valueFields = valuesF;
			valueFields = [];
//let valuesF = valueFields;
			
//console.log('txtField.value',txtField.value);
//console.log('valueFields',valueFields);
			const controls = Array.from(popContent.querySelectorAll(':is(input,textarea,select):not([disabled]):not([type="submit"])'));
//console.log('controls',controls);
			for(const control of controls){
//console.log('------- congtrol:type:',control.type, ' name:', control.name,' fildInValues:', valuesF.hasOwnProperty(control.name));
				if(valuesF.hasOwnProperty(control.name)){
						
//					valueFields = [];
//console.log('valuesF:',valuesF);
					valueFields[control.name] = Array.isArray(valuesF[control.name]) ? valuesF[control.name].shift() : valuesF[control.name];
//console.log('isArray:',Array.isArray(valuesF[control.name]), valueFields[control.name],valueFields );
						
//console.log('-------',control);
					if(control.hasAttribute('multiple')){
//console.log(control.classList.contains('choices__input'));
						/* загрузка данных в этот контрол с мульти селектом не работает layout="joomla.form.field.list-fancy-select" 
						 * Сделать загрузку через Ajax в форму с пересылкой значений в запросе
						 */
						if(control.classList.contains('choices__input')){
							let i = 0;
							for(const div of control.closest('div.choices').querySelector('.choices__list[aria-multiselectable="true"]').querySelectorAll('div')){
								if(! valueFields[control.name].includes(div.dataset.value))
									continue;
									
								const value = Number.parseInt(div.dataset.value);
								control[i++] = new Option(div.innerText, value, true);
									
								const opt = document.createElement('option');
								opt.value = div.dataset.value;//.setAttribute('value', div.dataset.value);
								opt.innerText = div.innerText;
								control.appendChild(opt);
								opt.selected = true;
//		popContent	= document.createElement('form');
//		popContent.classList.add('modal-dialog');
//		popContent.classList.add('modal-content');
//		popContent.classList.add('modal-dialog-scrollable');
//		popContent.classList.add('modal-dialog-centered');
//		popContent.dataset.type = '';
//		document.getElementById('pop_' + id).appendChild(popContent);
//		document.getElementById('pop_' + id).innerHTML = popContent;
							}
						}else{
							for(const opt of control.querySelectorAll('option')){
								if(valueFields[control.name] == (opt.value))
									opt.setAttribute('selected', 'selected');
								else
									opt.removeAttribute('selected');
							}
						}
							
//console.log('opt',opt,opt.getAttribute('selected'));
//console.log('valueFields[control.name]',valueFields[control.name]);
					}
					else{
						control.value = valueFields[control.name];
					}
							
//console.log('valueFields[control.name]',valueFields[control.name]);
//console.log('control.name',control.name);
//console.log('control.value',control.value);
				}
			}
		}
	});
};
});


