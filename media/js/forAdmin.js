jQuery(function($){
    if($('#jform_params_default option[value="default.php"]:selected').length){
        $('.mfFree, .mfJed').hide();//Pro
    } 
    if($('#jform_params_default option[value=""]:selected, #jform_params_jed option[value=""]:selected').length == 2){
        $('.mfPro, .mfJed').hide();//Free
    } 
    if($('#jform_params_default option[value=""]:selected, #jform_params_jed option[value="jed.php"]:selected').length == 2){
        $('.mfPro, .mfFree').hide();//Jed
    }
    
	function iconfiled(listTypeOne){
		switch (listTypeOne) {
			case "text":
			case "text_art":
			  var adClas = "icon-pencil-2  large-icon";
			  break
			case "textarea":
			case "textarea_art": 
			case "EditorTranslate":
			case "editor":
			  var adClas = "icon-edit  large-icon";
			  break
			case "telephone":
			  var adClas = "icon-phone-2 large-icon";
			  break
			case "email":
			  var adClas = "icon-mail  large-icon";
			  break
			case "select":
			  var adClas = "icon-menu-3  large-icon";
			  break
			case "radio":
			  var adClas = "icon-radio-checked  large-icon";
			  break
			case "checkbox":
			  var adClas = "icon-checkbox  large-icon";
			  break
			case "color":
			  var adClas = "icon-color-palette  large-icon";
			  break
			case "hidden":
			  var adClas = "icon_multiform-hidden large-icon";
			  break
			case "_separate":
			case "separate":
			  var adClas = "icon-minus-2  large-icon";
			  break
			case "_htmltagstart":
			case "htmltagstart":
			  var adClas = "icon-minus-2 large-icon";
			  break
			case "_htmltagfinish":
			case "htmltagfinish":
			  var adClas = "icon-minus-2 large-icon";
			  break
			default:
			  var adClas = "icon-info-circle large-icon";
			  break
			}
			return adClas;	
	}
	
//	function radioValueChanged(){
//            return;
//        radioValue = $(this).val();
//        if($(this).is(":checked") && radioValue == "yes"){
//            $(this).parent('fieldset').parent('td').removeClass('redRadio');
//            $(this).parent('fieldset').parent('td').parent('tr').addClass('onTrRadio');
//        } else {
//            $(this).parent('fieldset').parent('td').addClass('redRadio');
//			$(this).parent('fieldset').parent('td').parent('tr').removeClass('onTrRadio');
//        }
//	}

//	function sortionTR(){
//            return;
//		var oneThisTDInput = $('#jform_params_list_fields_modal tbody tr');
//		$.each(oneThisTDInput, function(index, value){
//			var str = $(this).find('td:nth-child(6) input');
//                        console.log(str.attr('name') + ' ' +str.val());
//			$(this).find('td:nth-child(6) input').val(index);                        
//		});
//                
//                        console.log( ' ');
//	}
	$(window).ready(function(){
//		/*Скрытие и показ полей, относящихся к PopUp*/
//		var popupYesNoLabel = $('#jform_params_popup .active').attr('for');
//		var popupYesNoInput = $('#'+popupYesNoLabel).val();
//		var textPopupBtn = $('#jform_params_textcallpopup').parents('.control-group');
//		var textBeforeField = $('#jform_params_textbeforeCallButton').parents('.control-group');
//		if(popupYesNoInput == 0){
//			$(textPopupBtn).css('display', 'none');
//			$(textBeforeField).css('display', 'none');
//		}else{
//			$(textPopupBtn).css('display', 'block');
//			$(textBeforeField).css('display', 'block');
//		}
//		$('.popup-yes-no input').click(function(){
//			var popupYesNoLabel = $('#jform_params_popup .active').attr('for');
//			var popupYesNoInput = $('#'+popupYesNoLabel).val();
//			if(popupYesNoInput == 0){
//				$(textPopupBtn).hide(300);
//				$(textBeforeField).hide(300);
//			}else{
//				$(textPopupBtn).show(300);
//				$(textBeforeField).show(300);
//			}
//		});
		
                        
		/*Открытие списка полей*/
		$('#jform_params_list_fields_button').on('click',function(){
//			
			/*Смена иконки типа поля*/
			$('#jform_params_list_fields_modal tbody tr td select[id $=-jform_formfields_typefield]').on('click',function(){//'',mouseout change select
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
//				if(parentDiv.hasClass('change')){
//					var actualType		= $(parentDiv).attr("data-type-field");
//					var listTypesOne	= $(this).val();
//					
//					if(listTypesOne == 'color' || listTypesOne == 'hidden' || listTypesOne == 'email' || listTypesOne == 'separate' || listTypesOne == 'htmltagstart' || listTypesOne == 'htmltagfinish' || listTypesOne.substring(0,1)=='_'){
//						$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').fadeOut(300);
//					}else{
//						$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').fadeIn(300);
//						if(listTypesOne == 'telephone'){
//							$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').val("+38(099)999-99-99");
//						}
//						if(listTypesOne == 'select' || listTypesOne == 'radio' || listTypesOne == 'checkbox'){
//							$(parentTR).find('td textarea[id$=jform_formfields_paramsfield]').val("params1; params2; params3");
//						}
//					}
//					
//					/*Условие для блокирования полей типа DIV*/
//					if(listTypesOne == '_htmltagstart'){
//						$(parentTR).find('td input[id$=jform_formfields_namefield]').val("div").prop( "disabled", true );
//						$(parentTR).find('td input[id$=jform_formfields_nameforpost]').prop( "disabled", false );
//					}else if(listTypesOne == '_htmltagfinish'){
//						$(parentTR).find('td input[id$=jform_formfields_namefield]').val("div").prop( "disabled", true );
//						$(parentTR).find('td input[id$=jform_formfields_nameforpost]').val("").prop( "disabled", true );
//					}else{
//						/*$(parentTR).find('td input[id^=jform_formfields_namefield]').val("").prop( "disabled", false );
//						$(parentTR).find('td input[id^=jform_formfields_nameforpost]').val("").prop( "disabled", false );*/
//					}
//					
//					$(parentDiv).removeClass('icon_multiform-mail icon_multiform-telephone icon_multiform-text icon_multiform-checkbox icon_multiform-dropdownlist icon_multiform-radio icon_multiform-separate icon_multiform-textarea icon_multiform-color icon_multiform-hidden icon_multiform-startblock icon_multiform-finishblock').addClass(iconfiled(listTypesOne)).attr('data-type-field',listTypesOne);
//				}
			});
                        $('#jform_params_list_fields_modal tbody tr td select[id $=-jform_formfields_typefield]').mouseout();
                        
                        
//			var oneThis = $('#jform_params_list_fields_modal tbody tr');
//			var heightTR = $( "#jform_params_list_fields_table tbody tr td" ).height();
//			$( "#jform_params_list_fields_table tbody tr" ).css('height',heightTR);
//			
//			$.each(oneThis, function(index, value){
//				/*var actualType		= $(this).find('td select').val();*/
//				var listTypesOne	= $(this).find('td select[id $=-jform_formfields_typefield]').val();
//				var parentDiv = $(this)
//					/*.children('td:nth-child(3)')
//					.children('select');*/
//					.children('td:nth-child(3)');
//				if(listTypesOne == 'color' || listTypesOne == 'hidden' || listTypesOne == 'email' || listTypesOne == 'separate' || listTypesOne == 'htmltagstart' || listTypesOne == 'htmltagfinish' || listTypesOne.substring(0,1)=='_'){
//					$(this).find('td textarea[id$=jform_formfields_paramsfield]').css('display','none');
//				}
//				
//				$(parentDiv).removeClass('icon_multiform-mail icon_multiform-telephone icon_multiform-text icon_multiform-checkbox icon_multiform-dropdownlist icon_multiform-radio icon_multiform-separate icon_multiform-textarea icon_multiform-color icon_multiform-hidden icon_multiform-startblock icon_multiform-finishblock').addClass(iconfiled(listTypesOne)).attr("data-type-field",listTypesOne);
//				
//				/*Условие для блокирования полей типа DIV*/
//				if(listTypesOne == 'htmltagstart' || listTypesOne == '_htmltagstart'){
//					$(this).find('td input[id^=jform_formfields_namefield]').val("div").prop( "disabled", true );
//					$(this).find('td input[id^=jform_formfields_nameforpost]').prop( "disabled", false );
//				}else if(listTypesOne == 'htmltagfinish' || listTypesOne == '_htmltagfinish'){
//					$(this).find('td input[id^=jform_formfields_namefield]').val("div").prop( "disabled", true );
//					$(this).find('td input[id^=jform_formfields_nameforpost]').val("").prop( "disabled", true );
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
//		setInterval(function(){
//			var RVCh = $("#jform_params_list_fields_modal tr td fieldset input[name$=onoff]");
//			
//			$(RVCh).each(function(){
//				if($(this).is(':checked') && $(this).val() == "no"){
//					$(this).parent('fieldset').parent('td').addClass('redRadio');
//					$(this).parent('fieldset').parent('td').parent('tr').addClass('desabled-field');
//					$(this).parent('fieldset').parent('td').parent('tr').removeClass('onTrRadio');
//				}
//				
//				if($(this).is(':checked') && $(this).val() == "yes"){
//					$(this).parent('fieldset').parent('td').removeClass('redRadio');
//					$(this).parent('fieldset').parent('td').parent('tr').removeClass('desabled-field');
//					$(this).parent('fieldset').parent('td').parent('tr').addClass('onTrRadio');
//				}
//			});
//		}, 1000);

//		/*Добавление нового поля*/
//		$('#jform_params_list_fields_modal tr th:last-child div a.button').on('click', function(){
////			sortionTR();
//			/*var actualType		= $(this)
//									.parent('div')
//									.parent('th')
//									.parent('tr')
//									.parent('thead')
//									.parent('table')
//									.find('td select[id $=-jform_formfields_typefield]')
//									.val();*/
//			var listTypesOne	= $(this)
//									.parent('div')
//									.parent('th')
//									.parent('tr')
//									.parent('thead')
//									.parent('table')
//									.find('td select[id $=-jform_formfields_typefield]')
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
//			if(listTypesOne == 'text' || listTypesOne == 'textarea' || listTypesOne == 'color' || listTypesOne == 'hidden' || listTypesOne == 'email' || listTypesOne == 'separate' || listTypesOne == 'htmltagstart' || listTypesOne == 'htmltagfinish' || listTypesOne.substring(0,1)=='_'){
//				$(this).parent('div').parent('th').parent('tr').parent('thead').parent('table').find('tr:last-child td textarea[id$=jform_formfields_paramsfield]').css('display','none');
//			}
//			$(parentDiv).removeClass('icon_multiform-mail icon_multiform-telephone icon_multiform-text icon_multiform-checkbox icon_multiform-dropdownlist icon_multiform-radio icon_multiform-separate icon_multiform-textarea icon_multiform-color icon_multiform-hidden icon_multiform-startblock icon_multiform-finishblock').addClass('icon_multiform-text').attr('data-type-field','text');
//		});

		/*$( "#jform_params_list_fields_table tbody" ).disableSelection();*/
		return;
		/*Отслеживание наличия парных блоков НЕ ГОТОВО!!!*/
		setInterval(function(){
			
			var countStart = 0;
			var countFinish = 0;
			var countStartOn = 0;
			var countFinishOn = 0;
			
			var countStart = $("select[id$=jform_formfields_typefield] option[value=_htmltagstart]:selected").length;
			var countFinish = $("select[id$=jform_formfields_typefield] option[value=_htmltagfinish]:selected").length;
			
			var countStartOn = $("tr.onTrRadio select[id$=jform_formfields_typefield] option[value=_htmltagstart]:selected").length;
			var countFinishOn = $("tr.onTrRadio select[id$=jform_formfields_typefield] option[value=_htmltagfinish]:selected").length;


			/*Прячем или показываем кнопку SAVE в зависимости от соблюдения условия парности полей типа DIV*/
			if(countStartOn != countFinishOn){
				if(countStartOn || countFinishOn){
					/*alert("Внимание! В форме есть не закрытые теги блоков!");*/
					if(!$("div").is("#undabledBlocks")){
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
				var lastEl = $("tr.onTrRadio select[id$=jform_formfields_typefield] option[value^=_htmltag]:selected").last().val();
				var firstEl = $("tr.onTrRadio select[id$=jform_formfields_typefield] option[value^=_htmltag]:selected").first().val();
				if(lastEl && lastEl != '_htmltagfinish'){
					
					/*alert(lastEl);*/
					/*alert("Внимание! В форме есть не закрытые теги блоков!");*/
					if(!$("div").is("#filedOrderBlocks")){
						$("#jform_params_list_fields_modal .modal-footer").prepend('<div id="filedOrderBlocks"></div>');
					}
					$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeOut();
					$("#filedOrderBlocks").html("Неверный порядок. Закрывающий блок находиться до открывающего!");
				}else if(lastEl && lastEl != '_htmltagfinish' &&  firstEl && firstEl != '_htmltagstart'){
					$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeIn();
					$("#filedOrderBlocks").remove();
				}else{
					$("#jform_params_list_fields_modal .modal-footer button.save-modal-data").fadeIn();
				}
			}
		}, 1000);
		
	//	$('#jform_params_list_fields_modal .save-modal-data').hover(sortionTR);
	});
	jQuery('#module-form .form-inline-header').append('<div>'+Joomla.JText._('MOD_MULTI_TOP')+'</div>');
});