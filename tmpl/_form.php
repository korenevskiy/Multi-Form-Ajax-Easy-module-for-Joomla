<?php defined('_JEXEC') or die;
/**
 * Multi Form - Easy Ajax Form Module with modal window, with field Editor and create article with form data
 * 
 * @package    Joomla
 * @copyright  Copyright (C) Open Source Matters. All rights reserved.
 * @extension  Multi Extension
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://exoffice/download/joomla
 * mod_multi_form 
 */



use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml; 
use \Joomla\CMS\Plugin\PluginHelper as JPluginHelper;
use \Joomla\CMS\Uri\Uri as JUri;
//use Joomla\Registry\Registry as JRegistry;
 

//$param = $params->toObject();

$params = new Reg($params);
$param = &$params;

if($param->captcha){ 
    //JHtml::_('behavior.keepalive');
    JPluginHelper::importPlugin('captcha'); 
    JFactory::getApplication()->triggerEvent('onInit',["dynamic_captcha_$module->id"]);//будет		
//    JEventDispatcher::getInstance()->trigger('onInit',"dynamic_captcha_$module->id");
//    JHtml::_('behavior.keepalive');
    
//JFactory::getApplication()->triggerEvent('onContentPrepare',[]);//будет		
//JEventDispatcher::getInstance()->trigger('onContentPrepare', []);// есть 
//JDispatcher::getInstance()->trigger('onContentPrepare', []);//Было

        
        
    //echo "<div id='dynamic_captcha_$module->id'></div>";
//    JHtml::_('behavior.keepalive');
//JSif (!grecaptcha.getResponse(0)) {
//JS    $('.recaptcha').addClass("has_error");
//JS    return false;
//JS}

//JFactory::getDocument()->addScriptDeclaration("document.body.recaptcha = console.log('moduleID: $module->id');");
//grecaptcha.render('dynamic_captcha_312',{sitekey: "6LdmpUgUAAAAAPOqF0bVF24OEgp21ERcuRAqftAD" }); ///******
//grecaptcha.render('dynamic_captcha_312',{sitekey: "6LeDuNgSAAAAAI11qF909zU2w2b-paPjYhdiJ8Dx" });
//Recaptcha.create("6LeDuNgSAAAAAI11qF909zU2w2b-paPjYhdiJ8Dx", "dynamic_captcha_312", {theme: "clean",lang : 'en',tabindex: 0});
}

// async="async" defer="defer"     defer  loading="lazy" 


JText::script("MOD_MULTI_FORM_VALIDATE_RQUIRED",true);
JText::script("MOD_MULTI_FORM_VALIDATE_REMOTE",true);
JText::script("MOD_MULTI_FORM_VALIDATE_EMAIL",true);
JText::script("MOD_MULTI_FORM_VALIDATE_URL",true);
JText::script("MOD_MULTI_FORM_VALIDATE_DATE",true);
JText::script("MOD_MULTI_FORM_VALIDATE_DATE_ISO",true);
JText::script("MOD_MULTI_FORM_VALIDATE_NUMBER",true);
JText::script("MOD_MULTI_FORM_VALIDATE_DIGITS",true);
JText::script("MOD_MULTI_FORM_VALIDATE_CREDIT_CARD",true);
JText::script("MOD_MULTI_FORM_VALIDATE_EQUAL_TO",true);
JText::script("MOD_MULTI_FORM_VALIDATE_EXTENSION",true);
JText::script("MOD_MULTI_FORM_VALIDATE_MAX_LENGHT",true);
JText::script("MOD_MULTI_FORM_VALIDATE_MIN_LENGHT",true);
JText::script("MOD_MULTI_FORM_VALIDATE_RANGE_LENGHT",true);
JText::script("MOD_MULTI_FORM_VALIDATE_RANGE",true);
JText::script("MOD_MULTI_FORM_VALIDATE_MAX",true);
JText::script("MOD_MULTI_FORM_VALIDATE_MIN",true);

//echo " <!-- " . JFactory::getConfig()->get('error_reporting','default') . " --> ";
//echo " <!-- " . (in_array(JFactory::getConfig()->get('error_reporting','default'), ['default','none','']) ? '.min' : '.max') . " --> ";

//$wa = new \Joomla\CMS\WebAsset\WebAssetManager;
$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();


if($param->icomoon){
//    JHtml::stylesheet('media/jui/css/icomoon.css');
	
	$wa->registerAndUseStyle('icomoon','media/jui/css/icomoon.css');
}

if($param->ui_bulma ?? FALSE){
//    JHtml::stylesheet( JUri::root() . '/modules/mod_multi_form/media/'.$param->ui_bulma);
	$wa->registerAndUseStyle('ui_bulma', JUri::root() . '/modules/mod_multi_form/media/'.$param->ui_bulma);
}

$min = modMultiFormHelper::$min ? '.min' : '';//modMultiFormHelper::$min;
//$captcha_type = $param->captcha ? JFactory::getConfig()->get('captcha',false) : '';//recaptcha, recaptcha_invisible, 0

echo " <!-- " . $min . " --> ";

$stylefiles = (array)($param->stylefiles ?:'default.css');
foreach ($stylefiles as $css_file){
	$css_file = $min ?  $css_file : str_replace('.css', '.min.css', $css_file);
//    JHtml::stylesheet("modules/$module->module/media/css/$css_file");
	$wa->registerAndUseStyle($module->module.'-'.$css_file, "modules/$module->module/media/css/$css_file");
}
$style = substr(reset($stylefiles), 0, -4);
//if($param->onoffjquery){
////    JHtml::script("https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js",[],[ 'defer' => 'defer']); //'async' => 'async',
//	$wa->registerAndUseScript('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', [], ['defer' => true]);
//}else{
    JHtml::_('jquery.framework'); 
    JHtml::_('bootstrap.framework');
$wa->useScript('jquery');	
$wa->useScript('jquery-noconflict');
$wa->useScript('jquery-migrate');
//$wa->registerAndUseScript('jquery', 'media/vendor/jquery/js/jquery.js', [], ['defer' => true]);
//    JHtml::_('bootstrap.loadCss', true);
//}

//if(is_null(modMultiFormHelper::$min)){
//    modMultiFormHelper::$min = in_array(JFactory::getConfig()->get('error_reporting','default'), ['default','none',''])?'.min':''; // default, none, simple, maximum, development
//}
//$min = modMultiFormHelper::$min;

//JHtml::script('jquery.form.js', 'modules/$module->module/media/js/');
$wa->registerAndUseScript('jquery.form', "modules/$module->module/media/js/jquery.form$min.js", [], ['defer' => true]);
//JHtml::script("modules/$module->module/media/js/jquery.form$min.js",[],[ 'defer' => 'defer']);//'async' => 'async',
JHtml::script("modules/$module->module/media/js/jquery.validate.min.js",[],[ 'defer' => 'defer']);//'async' => 'async',
JHtml::script("modules/$module->module/media/js/messages.min.js",[],['defer' => 'defer']);//'async' => 'async',
//JHtml::script("modules/$module->module/media/js/jquery.maskedinput$min.js",[],[ 'defer' => 'defer']); //'async' => 'async',
//JHtml::script("modules/$module->module/media/js/inputmask.js",[],[ 'defer' => 'defer']);//'async' => 'async',
JHtml::script("modules/$module->module/media/js/jquery.inputmask$min.js",[],[ 'defer' => 'defer']);//'async' => 'async',
//JHtml::script("modules/$module->module/media/js/jquery.inputmask.bundle.min.js",[],['defer' => 'defer']); //'async' => 'async',
JHtml::script("modules/$module->module/media/js/url$min.js",[],[ 'defer' => TRUE]);//'async' => 'async',


//$wa->registerAndUseScript('Instascan', 'https://rawgit.com/schmich/instascan-builds/master/instascan.min.js', [], ['defer' => true]);
//$wa->registerAndUseStyle('slideshowck','modules/mod_multi/media/slideshowCK/administrator/themes/default/css/camera.css');

//toPrint(JUri::root(). "modules/$module->module/media/js/form$param->scriptver$min.js",'path',true, 'message');//Joomla\CMS\Uri\Uri::root()
//JHtml::script("modules/$module->module/media/js/form$param->scriptver$min.js",['detectDebug'=>true],[ 'defer' => 'defer' ]); //'async' => 'async','defer' => 'defer'
//if($param->debug == 'debug') {
//	$min = '';
//	toPrint();
//	toPrint(array_keys(get_defined_vars()),'$module',0,'message');
//	toPrint($module,'$module',0,'message');
//	toPrint($param,'$param',0,'message');
//	$param->scriptver;
//}

$fileMD5Sum = JPATH_ROOT . '/modules/mod_multi_form/media/MD5SUM';
$versionScript = $param->debug == 'debug'? time() : ($param->versionScript ?: file_exists($fileMD5Sum) ? file_get_contents($fileMD5Sum) : '');
$wa->registerAndUseScript('multiForm', "modules/$module->module/media/js/form$min.js",
		['version' => ($versionScript?:'auto'), 'relative' => false, 'detectDebug' => ($min?:true)], ['defer' => true]);
//JHtml::script("modules/$module->module/media/js/typed.js"); 
//[]
//echo " <!-- " . "modules/$module->module/media/js/form$param->scriptver$min.js" . " --> ";

//echo "modules/$module->module/media/js/jquery.form$min.js<br>";
//echo "modules/$module->module/media/js/form$param->scriptver$min.js";

//        echo "<pre>Min-".modMultiFormHelper::$min."</pre>";
//        echo "<pre>"."modules/$module->module/media/js/form$param->scriptver$min.js"."</pre>";
//echo '<style type="text/css">.debug.pre{grid-column: 1/5;}</style>';

if($param->css)
    JFactory::getDocument()->addStyleDeclaration($min?:"/* Mod$module->id */ ".$param->css);

$param->debug &&  JFactory::getDocument()->addScriptDeclaration("console.log('ModMultiForm ID: $module->id');");
$style_not = in_array($param->style, [NULL,'','0','System-none','none']);

$debug = $param->debug?" data-deb='1' ":'';
$popup = $param->popup?"popup":"static";
$class_form = $param->popup? 'modal' : 'static';  
$module_tag	= $style_not?$param->module_tag:'div'; 

$form_use_id = $param->form_use_id ?: $module->id;
//Список плей.
$ajaxListFields = modMultiFormHelper::ajaxListFields($param->list_fields??false,$module->id);

$params->set('moduleclass_sfx', "$param->moduleclass_sfx modMF $popup id$module->id mod_$module->id pos_$module->position $style $param->style ");


$captcha_type = $param->captcha ? JFactory::getConfig()->get('captcha',false) : '';//recaptcha, recaptcha_invisible, 0

//if($module->id == 133)
//toPrint($param,'$param',0,'pre',true);

//if($param->popup && $style_not){
//    echo "<$module_tag class=' popup id$module->id mod_$module->id pos_$module->position $param->style $style' $debug data-type='$popup'  >";   
//}


if($param->textBeforeModule_show && ($param->textBeforeModule_1 || $param->textBeforeModule_2 )){ 
    $param->textBeforeModule_1 = modMultiFormHelper::getArticles($param->textBeforeModule_1);
    echo "<div class='mfBeforeModule id$module->id'>$param->textBeforeModule_1 $param->textBeforeModule_2</div>"; 
}
  

if($param->popup && empty($param->form_use_id)){
    echo "<button ($min) id='mod_$module->id' $debug href='#mfForm_$form_use_id' alt='$module->title' title='$module->title' data-captcha='$captcha_type'  rel='modal:open' " 
        . "data-id='$form_use_id' data-type='$popup' data-fields='[$ajaxListFields]' data-afterclear='$param->clearaftersend' data-toggle='{$class_form}'  data-target='-#mfForm_{$class_form}_$module->id'  "
        . "class='mfForm mfGo link id$module->id mod_$module->id pos_$module->position $param->style $style $param->classbuttonpopup v$param->scriptver'>$param->textbuttonpopup</button>";
}
if($param->form_use_id){
    echo "<a id='mod_$module->id' $debug href='#mfForm_$form_use_id' alt='$module->title' title='$module->title'  rel='modal:open'  "
        . "data-id='$form_use_id' data-type='$popup' data-fields='[$ajaxListFields]' data-afterclear='$param->clearaftersend' data-toggle='{$class_form}' data-target='-#mfForm_{$class_form}_$module->id'  "
        . "class='mfForm mfGo link id$module->id mod_$module->id pos_$module->position $param->style $style $param->classbuttonpopup v$param->scriptver'>$param->textbuttonpopup</a>";
}
if( empty($param->popup) && empty($param->form_use_id) ){ //Static Form
    echo "<$module_tag id='mod_$module->id' $debug data-captcha='$captcha_type' "
        . "data-id='$module->id' data-type='$popup' data-fields='[$ajaxListFields]' data-afterclear='$param->clearaftersend' "
        . "class='mfForm static style_width id$module->id mod_$module->id pos_$module->position $param->style $style v$param->scriptver' >"
        . "</$module_tag>";
}


$crutch = <<< crutch
 //document.setAttribute('data-base', '$module->module');
crutch;


 
