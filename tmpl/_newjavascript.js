
jQuery(window).on('load',  function() {
				new JCaption('img.caption');
			});
(function()
				{
					if(typeof jQuery == 'undefined')
						return;
					
					jQuery(function($)
					{
						if($.fn.squeezeBox)
						{
							$( 'a.modal' ).squeezeBox({ parse: 'rel' });
				
							$( 'img.modal' ).each( function( i, el )
							{
								$(el).squeezeBox({
									handler: 'image',
									url: $( el ).attr( 'src' )
								});
							})
						}
						else if(typeof(SqueezeBox) !== 'undefined')
						{
							$( 'img.modal' ).each( function( i, el )
							{
								SqueezeBox.assign( el, 
								{
									handler: 'image',
									url: $( el ).attr( 'src' )
								});
							});
						}
						
						function jModalClose() 
						{
							if(typeof(SqueezeBox) == 'object')
								SqueezeBox.close();
							else
								ARK.squeezeBox.close();
						}
					
					});
				})();
jQuery(function($){ $('#telephone2251').mask('+999(999) 999-9999'); $('#telephone2251').inputmask({'mask': '+7(999)999-99-99','oncomplete': function(){ $('#telephone2251').attr('data-allready', '1'); },'onincomplete': function(){ $('#telephone2251').attr('data-allready', '0'); },});});
jQuery(function(){mfClickOpenModal(); });
!function(a){"function"==typeof define&&define.amd?define(["jquery","../jquery.validate.min"],a):a(jQuery)}(function(a){a.extend(a.validator.messages,{ required:"Это поле нужно заполнить",	remote:"Введите корректное значение", email:"Введите корректный email", url:"Введите корректный url", date:"Введите корректную дату",	dateISO:"Введите корректную дату ISO", number:"Введите число", digits:"Введите цифры", 	creditcard:"Введите корректный номер банковской карты", equalTo:"Введите значение повторно", extension:"Выберите файл с корректным расширением", maxlength:a.validator.format("Пожалуйста, введите не больше {0} символов."), minlength:a.validator.format("Пожалуйста, введите не меньше {0} символов."), rangelength:a.validator.format("Пожалуйста, введите значение длиной от {0} до {1} символов."), range:a.validator.format("Пожалуйста, введите число от {0} до {1}."), max:a.validator.format("Пожалуйста, введите число, меньшее или равное {0}."), min:a.validator.format("Пожалуйста, введите число, большее или равное {0}.")})});
(function($){
        console.log('Module id 251');    
    // -------- Load F
    function AjaxDoneForm(data, status) {
        console.log('Module id:251 - Load form Success! - Done! status:',status);
        jQuery('body').append(jQuery(data));
        mfClickCloseModal();
    }
    function AjaxFailForm(jqXHR, status,errorThrown) {
        console.log('Module id:251 - Load form Fail! - Disabled button! status:',status);
        jQuery('.button.id_251').hide();
    }
    
    /** Загрузка формы */    
    jQuery(function(){
            console.log('Загрузка формы 251');
        var request = {'format':'raw','data-modid':251,'module':'multi_form','option':'com_ajax','method':'getForm'};
        jQuery.ajax({type:'POST',url:'index.php',dataType:'html',data:request}).done(AjaxDoneForm).fail(AjaxFailForm);
    });
    
    
    // -------- Send F    
    function AjaxDoneSuccess(data, status) {
            console.log("Send Success! data:",data);  
            console.log("Send Success! status:",status);   
        
        var posAfterSend = positionAfterSend('#webfactor_modal_form-251');
                
        jQuery('#webfactor_modal_form-251').animate({top: -posAfterSend}, 400, function(){
            jQuery('#ajax_webfactor_form251, #webfactor_modal_form-251 .webfactor_predtext').fadeOut("500",function(){
								
                    jQuery('.webfactor_ok-status251').fadeIn("500");
											
                    if(0){
											
			hideAndClearFormAfterSend(
					'#webfactor_modal_form-251',
					'#webfactor_overlay-251',
					'#webfactor_modal_form-251 .webfactor_ok-status251',
					data,
					textSubmitButton
			);					
                    }else{
											
			hideBlockFormAfterSend(
					'#webfactor_modal_form-251',
					'#webfactor_overlay-251',
					'#webfactor_modal_form-251 .webfactor_ok-status251',
					data
                        );
                    }
            });
        });
        //jQuery(html).append(jQuery(data));
    }
    
    function ClickSubmit(){
        var textSubmitButton = $('input#submit251').attr('value');
         
        jQuery( "#ajax_webfactor_form251" ).validate({
//            messages: jQuery.validator.messages,
            submitHandler: function(){
                jQuery('#submit251').attr('value','Отправление...').css('text-transform','none');
                
                //Собираем данные с полей формы - js формирующий данные
                
                    
                var request = {
                                'url': 'index.php',
				'option'		: 'com_ajax',
				'module'		: 'ajax_webfactor_form',
				'data-modid'	: '251',
				'modtitle'	: 'Закажите звонок',
				'currentPage'	: document.location.href,
				// Список наименований полей из XML 
                                

				'format'	: 'raw'//raw  json  debug  
                }
                jQuery.ajax({type:'POST',data:request}).done(AjaxDoneSuccess);
            }
        });
    }
        
    jQuery(document).on('click', 'input#submit251', ClickSubmit);
    
    
    
    
    
    
         
})(jQuery)
/* Module:239 */
jQuery(document).ready(function() {
    jQuery('p.chrono_credits>a').hide();
});

jQuery(function () {
  jQuery('[data-toggle="tooltip"], .jutooltip').tooltip()
})
jQuery(function(){mfClickOpenModal(); });
jQuery(function ($) {
		initChosen();
		$("body").on("subform-row-add", initChosen);

		function initChosen(event, container)
		{
			container = container || document;
			$(container).find(".advancedSelect").chosen({"disable_search_threshold":10,"search_contains":true,"allow_single_deselect":true,"placeholder_text_multiple":"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0438\u043b\u0438 \u0432\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u043d\u0435\u0441\u043a\u043e\u043b\u044c\u043a\u043e \u0432\u0430\u0440\u0438\u0430\u043d\u0442\u043e\u0432","placeholder_text_single":"\u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u0437\u043d\u0430\u0447\u0435\u043d\u0438\u0435","no_results_text":"\u0420\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442\u044b \u043d\u0435 \u0441\u043e\u0432\u043f\u0430\u0434\u0430\u044e\u0442"});
		}
});
	
jQuery(function($){ initTooltips(); $("body").on("subform-row-add", initTooltips); function initTooltips (event, container) { container = container || document;$(container).find(".hasTooltip").tooltip({"html": true,"container": "body"});} });

jQuery(document).ready(function() {
	var value, searchword = jQuery('#mod-finder-searchword87');

		// Get the current value.
		value = searchword.val();

		// If the current value equals the default value, clear it.
		searchword.on('focus', function ()
		{
			var el = jQuery(this);

			if (el.val() === 'Текст для поиска...')
			{
				el.val('');
			}
		});

		// If the current value is empty, set the previous value.
		searchword.on('blur', function ()
		{
			var el = jQuery(this);

			if (!el.val())
			{
				el.val(value);
			}
		});

		jQuery('#mod-finder-searchform87').on('submit', function (e)
		{
			e.stopPropagation();
			var advanced = jQuery('#mod-finder-advanced87');

			// Disable select boxes with no value selected.
			if (advanced.length)
			{
				advanced.find('select').each(function (index, el)
				{
					var el = jQuery(el);

					if (!el.val())
					{
						el.attr('disabled', 'disabled');
					}
				});
			}
		});
	var suggest = jQuery('#mod-finder-searchword87').autocomplete({
		serviceUrl: '/component/finder/?task=suggestions.suggest&amp;format=json&amp;tmpl=component',
		paramName: 'q',
		minChars: 1,
		maxHeight: 400,
		width: 300,
		zIndex: 9999,
		deferRequestBy: 500
	});
    });
	