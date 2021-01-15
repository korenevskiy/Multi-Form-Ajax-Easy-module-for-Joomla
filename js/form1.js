'use strict';
//https://stackoverflow.com/questions/59903262/how-in-joomla-with-api-with-ajax-get-field-editor-for-form
//https://stackoverflow.com/questions/6547116/joomla-jce-editor-not-loading-in-page-loaded-with-ajax?rq=1
//jQuery(document).on('scroll', function () {
//    scrollFromTop();
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
    let heightpage = jQuery(window).height();
    let heightpopupblock = jQuery(heightblock).height();
    let scrollFT = scrollFromTop();
    let position = heightpage / 2 + heightpopupblock + scrollFT;
    return position;
}
 
/**
 *  Событие КЛИКа Прокрутки к форме
 * @param {event} event 
 * @returns {undefined} 
 */
function mfScrollStatic_Click(event) {
    event.preventDefault();

//    console.log('event -> ', event.data);
//    console.log('this -> ', this);
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
//    console.log('event -> ', event);
//    console.log('event.data.fields -> ', event.data.fields);
//    console.log('this -> ', this);

//event.data - Модуль с датой
//this - Модуль с датой


    for (let field of event.data.fields) {
//            console.log(field,this[field]);
        if (this[field]) {
            document.getElementById(field).value = this[field].replace('_', ' ');
            continue;
        }
        if (this[field] !== undefined) {// если пробел.
            document.getElementById(field).value = '';
        }

    }

    let id = event.data.id;
    let modal_id = 'mfForm_' + id; 

    let heightpage = jQuery(window).height();
    let heightpopupblock = jQuery('#' + modal_id).height();


//    console.log('heightpopupblock -> ', heightpopupblock,'>=',heightpage,'heightpage-',heightpopupblock >= heightpage);
//    console.log('modal -> ', '#' + modal_id);
//    console.log('this.id -> ', id);



//    console.log('scrollFT -> ', scrollFT);
//    console.log('positiOnScroll -> ', positiOnScroll);
//    console.log('positiForOK -> ', positiForOK);
//    console.log('modal -> ', '#' +modal_id);


    jQuery('#mfOverlay_' + id).fadeIn(400, // сначала плавно показываем темную подложку
            function () { // после выполнения предыдущей анимации
                
//                document.getElementById(modal_id).showModal();
    let scrollFT = 0;     
    let positiOnScroll = 0;
    
    if (heightpopupblock + 50 >= heightpage) {
        scrollFT = scrollFromTop();//положение прокрутки страницы 
        positiOnScroll = 50;// + scrollFT;
//        var positiForOK = heightpage / 2 - heightpopupblock / 2 + scrollFT;
    } else {
        scrollFT = 0;//scrollFromTop();//положение прокрутки страницы 
        positiOnScroll = heightpage / 2 - heightpopupblock / 2 + scrollFT;
//        var positiForOK = heightpage / 2 - heightpopupblock / 2 + scrollFT;
    }
                 
                
                
//                document.getElementById(modal_id).showModal();
                //this.closest('#' + modal_id).close();
                //document.getElementById("mfForm_" + id).close();
                jQuery('#' +modal_id).fadeIn().modal('show').css('padding',0)
                        //.css('display', 'block') // убираем у модального окна display: none;
                        .animate({opacity: 1, top: positiOnScroll}, 200); // плавно прибавляем прозрачность одновременно со съезжанием вниз
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
    event.preventDefault();
    let id = event.data.id;
    let param = this;
                jQuery("#mfOverlay_" + id).fadeOut(); // скрываем подложку
    jQuery("#mfForm_" + id)//.fadeOut(400,'swing',{})
            .animate({top: '-50%'}, 400)//.delay(800)
            .animate({opacity: 0}, 400, function () { // после анимации
        
                //this.closest('dialog').close();
//    console.log('modal -> ', 'mfForm_'+id);
//                jQuery(this).css('display', 'none'); // делаем ему display: none;
//                jQuery("#mfForm_" + id).modal('hide');
//                document.getElementById('mfForm_'+id).close();
            }).delay(400).modal('hide');
}
var runingCloseModalForm = false;
/** НЕ ИСПОЛЬЗУЕТСЯ!!!
 * Закрытие модального окна. Присваивается для подложки и кнопки КЛИК закрыть. 
 * @returns {undefined}
 */
function mfClickCloseModal() {
//    console.log('Click SET Close -> 1 ' );
    if (runingCloseModalForm)
        return;
    runingCloseModalForm = true;
//    console.log('Click SET Close -> 2 ' );
    jQuery(function () {// Закрытие окна по клику крестика и фона
        jQuery('.mfOverlay, .mfClose').click(function (close) {
            close.preventDefault();
//    console.log('Click Close -> ', jQuery(this).attr("data-id"));
            let id = jQuery(this).attr('data-id');
            let modal = "#mfForm_" + id;
            let overley = "#mfOverlay_" + id;
            jQuery(modal).animate({top: 0}, 200);
            jQuery(modal)
                    .animate({opacity: 0}, 300, function () { // после анимации
                        jQuery(this).css('display', 'none'); // делаем ему display: none;
                        jQuery(overley).fadeOut(400); // скрываем подложку
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

    jQuery('.mfStatusDone.id' + id).html(response);

    let heightpage = jQuery(window).height();
    let heightpopupblock = jQuery('.mfStatusDone.id' + id).height();
    let scrollFT = scrollFromTop();
    let positiOnScroll = heightpage / 2 - heightpopupblock / 2;//+ scrollFT;

// console.log('heightpopupblock высота модалки ',heightpopupblock);
// console.log('scrollFT позиция прокрученого сайта ',scrollFT);
// console.log('positiOnScroll ',positiOnScroll);


    jQuery('#mfForm_' + id).animate({top: positiOnScroll}, 400, function () {
        jQuery('.mfStatusForm.id' + id).fadeOut();
        setTimeout(function () {
            jQuery('#mfForm_' + id)
                    // плавно меняем прозрачность на 0 и одновременно двигаем окно вверх
                    .animate({top: -positiOnScroll}, 400,
                            function () { // после анимации 
                                jQuery(this).fadeOut(); // делаем ему display: none;
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
function hideAndClearFormAfterSend(id, modal, overley, status, response, textbutton) {

    jQuery('.mfStatusDone.id' + id).html(response);

    let heightpage = jQuery(window).height();
    let heightpopupblock = jQuery('.mfStatusDone.id' + id).height();
    let scrollFT = scrollFromTop();
    let positiOnScroll = heightpage / 2 - heightpopupblock / 2 + scrollFT;


    jQuery('#mfForm_' + id).animate({top: positiOnScroll}, 400, function () {
        setTimeout(function () {
            jQuery('#mfForm_' + id)
                    // плавно меняем прозрачность на 0 и одновременно двигаем окно вверх
                    .animate({top: -positiOnScroll}, 400,
                            function () { // после анимации 
                                jQuery(this).fadeOut(); // делаем ему display: none;
                                jQuery('#mfOverlay_' + id).fadeOut('', function () { // скрываем подложку 
                                    jQuery('.mfStatusDone.id' + id).fadeOut();
                                    jQuery('.mfStatusForm.id' + id).fadeIn();
                                    jQuery('.mfStatusForm.id' + id).get(0).reset();
                                    jQuery('#mfForm_' + id + ' input[id^=submit]').attr('value', textbutton);
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
    jQuery('.mfStatusDone.id' + id).html(response);

    jQuery(".mfStatusForm.id" + id).fadeOut(400, function () {
        jQuery('.mfStatusDone.id' + id).fadeIn(400);
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

    jQuery('.mfStatusDone.id' + id).html(response);
    jQuery('.mfStatusForm.id' + id).fadeOut(400, function () {
        jQuery('.mfStatusDone.id' + id).fadeIn(400, function () {
            setTimeout(function () {
                jQuery('.mfStatusDone.id' + id).fadeOut(400, function () {
                    jQuery('.mfStatusForm.id' + id).fadeIn(400, function () {
                        jQuery('.mfStatusForm.id' + id)[0].reset();
                        jQuery('#mfForm_' + id + " input[id^=submit]").attr('value', textbutton);
                    });
                });
            }, 8000);// Задержка вывода сообщения для статической формы 
        });
    });
}


/* --------- --------------------------------------- */

// -------- Send F    -/ Метод выполняется при получении HTML от AJAX запроса
function mfAjaxDoneSuccess(data, status) {
    this.deb && console.log("Send Success! data:",[data]);  
    this.deb && console.log("Send Success! status:", status);

    // Вызов пользовательского скрипта из модуля.
    let func_custom = window['funcAfter' + this.id];
    if (func_custom && typeof func_custom === 'function') {
        func_custom.apply(this);//, this.id
    }

//console.clear();
//console.log('# '+this.id);
    let posAfterSend = positionAfterSend('#mfForm_' + this.id);
//console.log('posAfterSend',posAfterSend);
//console.log('# '+this.id);
    let params = this;

//console.log(this);
    if (this.type === 'popup') {
        jQuery('#mfForm_' + this.id).animate({top: -posAfterSend}, 400, function () {
            jQuery('.mfStatusForm.id' + params.id + ',.modal-footer.id' + params.id + ', .mfBeforeText.id' + params.id).fadeOut("500", function () {
                jQuery('.mfStatusDone.id' + params.id).fadeIn("500");

                if (params.clearaftersend) {////Очищать форму $param->clearaftersend

                    hideAndClearFormAfterSend(
                            params.id,
                            '#mfForm_' + params.id,
                            '#mfOverlay_' + params.id,
                            '.mfStatusDone.id' + params.id,
                            data,
                            jQuery('input#submit' + params.id).data('ready')//textSubmitButton
                            );
                } else {

                    hideBlockFormAfterSend(
                            params.id,
                            '#mfForm_' + params.id,
                            '#mfOverlay_' + params.id,
                            '.mfStatusDone.id' + params.id,
                            data
                            );
                }
            });
        });
    }
    if (this.type === 'static') {

        jQuery('.mfStatusForm.id' + params.id + ',.static-footer.id' + params.id + ', .mfBeforeText.id' + params.id).fadeOut("500", function () {


            jQuery('.mfStatusDone.id' + params.id).fadeIn("500");

            if (params.clearaftersend) {
                hideAndClearStaticFormAfterSend(
                        params.id,
                        '#mfForm_' + params.id,
                        '.mfStatusDone.id' + params.id,
                        data,
                        jQuery('input#submit' + params.id).attr('value')
                        );
            } else {
                hideBlockStaticFormAfterSend(
                        params.id,
                        '#mfForm_' + params.id,
                        '.mfStatusDone.id' + params.id,
                        data
                        );
            }
            mfScrollStatic_Click.call(params, {data: params, first: true, preventDefault: function () {
                    params.deb && console.log('🏆 mfScrollStatic_Click ', "#mfForm_form_" + params.id, params);
                    return true;
                }});
        });
    }
}

function mfButtonSubmit_Click(e) {
//        let params = e.data;
//        let e = {data:this};
    //console.log('🏆 func_custom', e);
    //return;
    let params = e.data;
//        var params = this;
//        console.log(1212121212,e.data,params,this);
    //----------
//        var fields = params.fields;
//        var textSubmitButton = $('input#submit'+e.data.id).attr('value');
    //отключение события, нужно для инициализации Validator'а на форме при первом запуске
    if (e.first)
        e.preventDefault();

    // Вызов пользовательского скрипта из модуля.
    let func_custom = window['funcBefore' + params.id];
// console.log('🏆 func_custom', func_custom);
    if (func_custom && typeof func_custom === 'function') {
        func_custom.apply(params);
    }
// console.log('🏆 mfButtonSubmit_Click ', "#mfForm_form_"+e.data.id, e.data); 
//        jQuery( "#mfForm_form_"+params.id ).validate();
//        
//        return;
//console.log('jQuery.validator.messages',jQuery.validator.messages);

//this.deb &&                console.clear();
//        console.log(11111111,"#mfForm_form_"+params.id,params,this);
    jQuery("#mfForm_form_" + params.id)
//                .each(function(callback){
//            //this.deb && 
////                    console.log('🏆 OnClick for Submit ', 'input#submit_'+this.id,el, this);
//                    console.log('ElementValidstor', this);
//        })
            .validate({

//            messages: jQuery.validator.messages,
                debug: false,
//            showErrors: function(errorMap, errorList) { 
//                console.log("Your form contains "
//                    + this.numberOfInvalids()
//                    + " errors, see details below."
//                    + this.defaultShowErrors(),errorMap,errorList);
//            },
                invalidHandler: function (event, validator) {
                    console.log('👎 _validator');
                    console.log('👎 _validator', validator);
//                console.log('👎 _validator<FORM>',this);
                },
                submitHandler: function (form) {//,event //recaptcha_invisible, recaptcha
                    e.preventDefault();
                    console.log('(I)  :-)  - submitHandlerValidate!!!!!->  ()  Captcha:',params.captcha?'Yes🌟':'No🚫', params.captcha, params);
                    if (params.captcha == 'recaptcha'  && params.grecaptcha !== false) {
                        //'dynamic_captcha_'+params.id
                        //grecaptcha.execute(params.grecaptcha)
                        //grecaptcha.execute('widget_captcha_'+params.id)
//                        grecaptcha.ready(function() {
                            //console.log('22222222222')
                            let rdy = this;
                            params.response = grecaptcha.getResponse(params.WidgetId);
                            if(params.response){
                                console.log('(II) Execute() -submitHandler-CallBack!!!!!-> Token', ' This:',this,' Module:',params  );
                                submitHandler.call(params, form);
                                
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
//                        });
                    }
                    if (params.captcha == 'recaptcha_invisible'  && params.grecaptcha !== false) {
                        //'dynamic_captcha_'+params.id
                        //grecaptcha.execute(params.grecaptcha)
                        //grecaptcha.execute('widget_captcha_'+params.id)
//                        grecaptcha.ready(function() {
                            //console.log('22222222222')
                            let rdy = this;
                            
                            grecaptcha.execute(params.grecaptcha).then(function(token){

                                params.token = token; 
                                console.log('(II) Execute() -submitHandler-CallBack!!!!!-> Token', token,' This:',this,' Module:',params  );
                                return;
                                
                                submitHandler.call(params, form);
                            });
//                        });
                        return;
                    }
                    else if(params.captcha == 'recaptcha_invisible' && params.grecaptcha === false){//
console.log('(II) Valid() -submitHandler-CallBack!!!!!-> ',' This:',this,' Module:',params  );
//                        submitHandler.call(params, form);
                        return;
                    }
                    else if(!params.captcha || params.grecaptcha === false){//Капча не настроена, выполняем без капчи
console.log('(II) Valid() -submitHandler-CallBack!!!!!-> ',' This:',this,' Module:',params  );
                        submitHandler.call(params, form);
                        return;
                    }
                    else{
                        /* Message ERROR! Alert: Please Please contact us in another way in contacts.*/
                        /* Message ERROR! Alert: Пожалуйста обратитесь другим способом в контактах.*/
                    }
                }
            });

//    console.log(55555555, "#mfForm_form_" + params.id, params, this);
//        e.preventDefault();
}


// -------- Submit Form
function submitHandler(form) {

//                let data = this;//form.dataset;

//                var capth_exe = grecaptcha.execute();

//                console.log('capth_exe',capth_exe);
//                console.log('form',form);

    console.log('(III)  submitHandler() -Execute-CallBack!!!!!-> ', this);
//    return;

//                jQuery(form).submit(function(e) {
//                     e.preventDefault();
//                });
//                form.preventDefault();
//                console.log('🏆 Validated!!!!');
//                console.log('🏆 Validated!!!! data', e.data);
//                console.log('🏆 Validated!!!! Event', event);
//                console.log('🏆 Validated!!!! Form', form);

//                throw new Error("Something went badly wrong!");
    jQuery('#submit_' + this.id).attr('value', jQuery('#submit_' + this.id).data('sending'))
            .css('text-transform', 'none').css('transform', 'none').addClass('active'); //Тект кнопки при отправке
//                return false;
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

//                var form = document.getElementById('mfForm_form_'+e.data.id)
//                console.log('form',form);
    var data = new FormData(form);//  mfForm_form_249

//                data.append('method     ', 'getForm');
    data.append('option', 'com_ajax');
    data.append('module', 'multi_form');
    data.append('format', 'raw');//raw  json  debug
    data.append('id', this.id);
    data.append('page', document.location.href);
    data.append('title', document.title);
    data.append('Itemid', itemid);
    if(this.token)
        data.set('gToken', this.token);
    if(this.response)
        data.set('gToken', this.response);
    if(this.sitekey !== undefined)
        data.append('sitekey', this.sitekey);
    if(this.grecaptcha !== undefined)
        data.append('grecaptcha', this.grecaptcha);

    //document.querySelector( ".mfStatusForm.id312 .g-recaptcha-response" );
    if(this.response){//this.captcha &&  
        data.append('g-recaptcha-response', this.response);// 
        //data.set('g-recaptcha-response', this.response);//         
    }
//                console.log('Itemid', itemid);
//                var data = {
//                                //'method':'getForm',
//                                option      : 'com_ajax',
//				module      : 'multi_form',
//				format      : 'raw',//raw  json  debug
//				id          : e.data.id,
//				currentPage : document.location.href,
//                                title       : document.title
//                };

//            console.log('request',data,data.values(),form,' Captcha:',this.captcha,' Token:',this.token, ' Response:',this.response);


//            for(let k in data.values())
//                console.log('key',k);
            console.log('---------');
//            for(let v of data.values())
//                console.log('val:',v);
//            return;


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
//                        data.append(field, jQuery('#'+field).val());
        }
    }
//-----------------------                

    // $( this ).serializeArray() ); // https://api.jquery.com/serializeArray/
//                console.log('e.data.fields',e.data.fields); 



    let url = window.location.origin + '/index.php';
    url = document.baseURI + 'index.php';
    this.deb && console.log('Ajax request ModuleID:', this.id, ' ', data);
    jQuery.ajax({type: 'POST', url: url, dataType: 'html', data: data, context: this, cache: false, contentType: false, processData: false})
            .done(mfAjaxDoneSuccess).fail(mfAjaxFailForm);//.fail(mfAjaxFailForm);
}

// -------- Load F 

function mfAjaxDoneForm(data, status) {
//        var id = jQuery(this).data('id');
//        var deb = jQuery(this).data('deb');
//        var type = jQuery(this).data('type');
//    this.deb && console.log('🏆 Module id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Success! - Done! status:', status, ' ', this);

    let params = this; 


//        console.log(this);
//        console.log(data);
//            console.log('🏆'+this.id);
//            console.log('🏆'+this.id);
//            console.log('#'+this.button, this.type);
//            console.log(this.buttons);
    if (this.type === 'popup') 
    {
        jQuery('body').append(jQuery(data));
        jQuery('#mfClose_' + this.id).click(this, mfCloseModal_Click);
        jQuery('#mfOverlay_' + this.id).click(this, mfCloseModal_Click);
        jQuery('.modal-backdrop.show').click(function(){ 
            jQuery('.mfOverlay').fadeOut();
            jQuery('dialog.mfForm_modal').animate({top: '-50%'}, 400).fadeOut();//.modal('hide'); 
            jQuery(this).hide(); });
        if (this.buttons.length > 0) {
            for (let btn of this.buttons) {
//                 console.log(btn);
                jQuery(btn).click(this, mfOpenModal_Click);
            }
        }
        // Привязка события Escape 
        let mod = {data: this, preventDefault: ()=>{}};
        document.addEventListener('keydown', event => {
            if(event.code === 'Escape'){ 
                event.preventDefault();
                mfCloseModal_Click(mod);
            }
        });
        
        
        
    } else {
        jQuery('#mod_' + this.id).append(jQuery(data));
        if (this.buttons.length > 0) {
            for (let btn in this.buttons) {
//                console.log(btn);
                jQuery(btn).click(this, mfScrollStatic_Click);
            }
        }
    }
    this.deb && console.log('🏆 ButtonSubmit.Click(f()) id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Success! - Done! status:', status, ' ', this);
    //Инициализация Validator'а на форме при первого запуска
    mfButtonSubmit_Click.call(this, {data: this, first: true, preventDefault: function () {
        this.deb && console.log('🏆 mfButtonSubmit_Click ', "#mfForm_form_" + this.id, this);
        return true;
    }});

    jQuery('input#submit_' + this.id).each(function (i, el) {
        //this.deb && 
//                    console.log('🏆 OnClick for Submit ', 'input#submit_'+this.id,el, this);
//                    console.log(123, params);
    }).click(this, mfButtonSubmit_Click);

//        jQuery('#mfForm_form_'+this.id +' input').inputmask();
    this.deb && console.log('🏆 Module id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Success! - Done! status:', status);
}

function mfAjaxFailForm(jqXHR, status, errorThrown) {//(jqXHR, status, errorThrown)
console.clear();
    this.deb && console.log('👎 Module id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Fail! - Disabled button! status:', status, '\n this:', this, ' jqXHR:', jqXHR,'\n errorThrown:',errorThrown);//, errorThrown,jqXHR
    //this.deb && console.log('👎 Module id:' + this.id + ' Tag:' + this.tag + ' Type:' + this.type + ' - Load form Fail! - Disabled button! status:', status, ' this:', this, ' data:', data);//, errorThrown,jqXHR//
//        jQuery('.button.id'+this.id).hide();

    jQuery('#' + this.button).hide();
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

//    console.log('hash',hash);
//    console.log('url',jQuery.url());
    let str_hash = jQuery.url('hash', url) || '';
    let amp = str_hash.indexOf("&");

    amp < 1 && (amp = undefined); //str_hash.length
//console.log(amp,' ',str_hash);
//    console.log(id);
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
//    console.warn('id:',id,' amp:',amp,'  ',str_hash,'   ',url);
//    console.log('hash',id,hash,url);
//    console.log(url);

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
 * 
 * @returns {undefined}
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
function mfGetAllModules() {
    let modules = {};
    let mods = jQuery('.mfForm').map(function () {
        let data = jQuery(this).data();
        data.control = this;
        return data;
    });

    for (let module of mods) {
        if (module.type)
            modules[module.id] = module;
    }
    return modules;
    return mods;
}
/**
 * Выполнение ReCaptcha, Загрузка MCE Tiny, Назначение ссылок для вызова форм
 * @param {type} p1
 * @returns {undefined} 
 */
function mfAjaxCompleteAllForm(p1) {

    let mods = Array.isArray(this)? this : [this];
//return;
//console.log('mfAjaxCompleteAllForm()',mods);

    /**
     * Инициализация Recaptcha для форм.
     */
    for (let module of mods) {
        if (module.captcha) {
            
            
            let captcha_type = module.captcha;//recaptcha_invisible, recaptcha
//console.log('-----> Captcha-RENDER() !!!!!->', 'dynamic_captcha_' + module.id, module.captcha, module);
            Object.assign(module,jQuery('#dynamic_captcha_'+module.id).data());
//            module.control.dataset.WidgetId = 
            module.WidgetId = module.grecaptcha = false;
//data-recaptcha-widget-id            
            let Captcha_Render = ()=>{
//                return;
//window.JoomlaInitReCaptcha2();
//window.JoomlaInitReCaptchaInvisible();
            //module.control.dataset.recaptchaWidgetId = 
            //grecaptcha.render() return  0 или 1 или 2
//            module.control.dataset.WidgetId = 
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
                            submitHandler.call(module, form);
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
                        
                    //submitHandler.call(params,form);
                    //submitHandler.call(data,form,event); 
                },
                'expired-callback': function (response) {//Выполняется при истечении срока действия ответа reCaptcha и требует новой проверки.
                    if(module.xExpiredCallback && module.xExpiredCallback.trim()){
                       try {
                       let callback = module.xExpiredCallback.trim(); 
                       eval(callback+'()');
//                       callback();
//                        eval(module.xExpiredCallback);
                       }catch(e){
                       }
                    }
                    console.log('(-II)  Delay-CallBack!!!!!-->R:', response, ' M:', module, 'this:', mods);
                    //submitHandler.call(data,form,event); 
                },
                'error-callback': function (response) {//Выполняется при ошибке проверки, обычно это отсутсвие сети, нужно информировать юзера о повторном подключении(проверке).
                    if(module.xErrorCallback && module.xErrorCallback.trim()){
                       try {
                       let callback = module.xErrorCallback.trim(); 
                       eval(callback+'()');
//                       callback(); 
//                        eval(module.xErrorCallback);
                       }catch(e){
                       }
                    }
                    console.log('(-II)  Error-CallBack!!!!!-->R:', response, ' M:', module, 'this:', mods);
                    //submitHandler.call(data,form,event); 
                }
            });
//            module.response = grecaptcha.getResponse(params.WidgetId);
//            grecaptcha.getResponse(module.WidgetId);
//            grecaptcha.execute(module.WidgetId);
//console.log('-----> Captcha-RENDER() !!!!!->', 'dynamic_captcha_' + module.id, module.captcha, module);
            };
            
            let timerId = 0;            
            timerId = setInterval( ()=>{
                
            
            if(!grecaptcha)
                return;
            
            clearInterval(timerId);
            
            if(module.grecaptcha)
                return;
            
            Captcha_Render();
            
            }, 1000);
//https://www.google.com/recaptcha/api.js?onload=JoomlaInitReCaptchaInvisible&render=explicit&hl=ru-RU
//https://www.google.com/recaptcha/api.js?onload=JoomlaInitReCaptcha2&render=explicit&hl=ru-RU
//            console.log('----->2 CaptchaAllForm!!!!!->', 'dynamic_captcha_' + module.id, module.captcha, module);
            //'sitekey':mods.
            // 1. Создать функцию CaptchRender для классов g-captcha
            // 2. Создать обработчик CallBak для Render.
            // 3. Создать PHP Проверку.
        }else{
//console.log('555555 ',module );
        }
//console.log('66666666666 ',mods );
    }




    console.log('jQuery.validator.messages',jQuery.validator.messages );
//    jQuery("form").validate({messages:jQuery.validator.messages});  

    /**
     * Инициализация Редактора TinyMCE
     */
//    jQuery.getScript(document.location.origin+'/media/editors/tinymce/tinymce.min.js',function(){    });
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
//    console.log(lang_path);
    jQuery.getScript(edit_path).always(_ => jQuery.getScript(lang_path).always(tinyeditor));




    let hash = mfGetUrlHash(); // URL
//console.log('HASH',hash);

    let module = hash.id ? mfGetModuleById(hash.id) : false; // Модуль из URL
    module && mfGotoModule(module, hash);// Переход к модулю указанному в URL и присвоение полей

    let actions = mfGetAllActions();
    let modules = mfGetAllModules();

//    console.log('actions ',actions);
//    console.log('modules ',modules);
//    console.log('hostname','path','query','id',' ------ Actions Count:',actions.length);
//    console.log('HASH ',hash);
    for (let action of actions) {
//        var action = actions[i];
        if (!(action.id in modules))
            continue;
        let module = modules[action.id];
        action.module = module;
//        console.log(i+' action ',action);
        (
                action.url.startsWith("#") ||
                (hash.hostname === action.hostname || action.hostname === '') &&
                (hash.path === action.path || (['', '/', 'index.php', '/index.php'].includes(hash.path) && ['', '/', 'index.php', '/index.php'].includes(action.path))) &&
                hash.query === action.query
                )
                //&& ((console.log(' --------Type==PopUp',modules[action.id].type==='popup'))|| true )
                //&& ((console.log(' --------action',action.id,modules[action.id].type,action))|| true )
                &&
                (
                        (
                                //(console.log(' -------Set-ClickStatic',action)||true) &&
                                        (modules[action.id].type === 'static') &&
                                        ((jQuery(action.control).click(function (e) {
                                            e.preventDefault();
                                            mfScrollStatic_Click.call(action, {data: modules[action.id], first: true, preventDefault: function () {
                                                    return true;
                                                }});
                                            return false;
                                        })
                                                ) || true)
                                        )
                                ||
                                (
                                        //(console.log(' -------Set-ClickPopUp',action)||true) && 
                                                (modules[action.id].type === 'popup') &&
                                                ((jQuery(action.control).click(function (e) {
                                                    e.preventDefault();
                                                    mfOpenModal_Click.call(action, {data: modules[action.id], first: true, preventDefault: function () {
                                                            return true;
                                                        }});
                                                    return false;
                                                })
                                                        ) || true)
                                                )
                                        )
                                //&& true 
                                //&& console.log(' --------action',action);
                                ;
                        //ПРИСВОИТЬ КЛИК для ссылок этой страницы


                    }
//    return;
//    console.log('actions ',actions);
//    console.log(p1);
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

            params.button = jQuery(form_mod).get(0).id;
            params.tag = jQuery(form_mod).get(0).tagName;

//            console.log(i,'-form ',params.id,' ',params.tag,' ----------', params.button );

            if (!(params.id in mfButtons)) {
                mfButtons[params.id] = [];
            }

            if (['a', 'button', 'A', 'BUTTON'].includes(params.tag)) {
                if (mfButtons[params.id]) {
                    mfButtons[params.id].push(form_mod);//params.button
                }
            }

            if ('mod_' + params.id !== params.button)
                return false;

            params.buttons = mfButtons[params.id];
            form_mod.params = params;
            return true;
        })
        .sort((a,b)=>a.dataset.captcha.length - b.dataset.captcha.length)
        .map(function (index, form_mod) {//.popup
//console.log(index);
//console.log(form_mod);
//            var params = form_mod.params; 
//            form_mod.params.deb && console.log('moduleID:'+form_mod.params.id,form_mod.params);
                                //params.buttons = mfButtons[params.id];
//            params.deb = true;
//            console.log('params:',form_mod.params);
//            params.id = jQuery(form_mod).data('id');
//            params.deb = jQuery(form_mod).data('deb');
//            params.type = jQuery(form_mod).data('type');

            let url = window.location.origin + '/index.php';
                url = document.baseURI + 'index.php';
//console.log(url);
            let request = {id: form_mod.params.id, format: 'raw', module: 'multi_form', option: 'com_ajax', method: 'getForm', Itemid: itemid};
//console.log(request);
            return jQuery.ajax({type: 'POST', url: url, dataType: 'html', data: request, context: form_mod.params})
                    .done(mfAjaxDoneForm)
                    .fail(mfAjaxFailForm);
        })
    )
//    .then(function(){console.log('GOOD !!!!');},function(){console.log('BAD !!!!');})
//    .done(function(){console.log('GOOD !!!!');})
    .done(mfAjaxCompleteAllForm);
//    console.log(mfButtons);




//        console.log('mfButtons:',mfButtons);
                    //hidden text textarea editor telephone email select radio checkbox color 
});