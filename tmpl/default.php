<?php defined('_JEXEC') or die;//https://bootstrap-4.ru/docs/4.0/components/modal/#live-demo  // Переделать как в этой ссылке
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



use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory as JFactory;
use Joomla\Registry\Registry as JRegistry;

$document			=  JFactory::getDocument();
//$configure			=  JFactory::getConfig();

$param = $params->toObject();
//        toPrint($params,'$params',0,'pre');
//        toPrint($param,'$param',0,'pre',true);
//        toPrint($module,'$module',0,'pre',true);
if(empty($param)){
    return;
}

if(empty($fields)){
    return;
}    

//toPrint($style,'$style');
$stylefiles = (array) $param->stylefiles ?: 'default.css';
foreach ($stylefiles as $css_file){
    JHtml::stylesheet("modules/$module->module/css/$css_file");
}
$style = substr(reset($stylefiles), 0, -4);
 

if($param->popup){
     JFactory::getDocument()->addStyleDeclaration(".mfForm_modal.id$module->id{display:none;}");
}
if(empty($param->popup)){
    $param->moduleclass_sfx = $params->set('moduleclass_sfx', '');
}

$tag_form = $param->popup? 'dialog' : 'div';
$class_form = $param->popup? 'modal' : 'static';
$attribute_form = $param->popup? ' role=\'dialog\' aria-modal=\'true\'' : ' ';
$ariaHeader = $param->popup? " aria-labeledby='mfHeader_$module->id'" : ' ';
$ariaDescribe = $param->textbeforeformShow && ($param->textbeforeform1 || $param->textbeforeform2 )?
        " aria-describedby='mfDescribe_$module->id'" : ' ';

$method=$fields_test?' method="post"  enctype="multipart/form-data" ':'';
$show_debug_modal = $params->get('debug')=='debug' ?'display:block; opacity: 0.8;':'';
//echo "<div id='mfOverlay_overlay-$module->id' data-id='$module->id' class='modal fade mfOverlay__overlay overlay_$module->id' aria-labelledby='$module->title'  role='dialog' tabindex='-1'  aria-hidden='true'>";//подложка


echo "<$tag_form id='mfForm_$module->id' "
        . " class='mfForm_{$class_form} -modal-dialog {$class_form}_$module->id id$module->id $param->moduleclass_sfx $param->style style_$style' "
        . " $attribute_form  aria-labeledby='mfHeader_$module->id' $ariaDescribe "
        . " style='$show_debug_modal' data-moduleid='$module->id' data-id='$module->id' >";//само окно    data-sending='$param->textwhensending'
//    echo '<div class="modal-dialog" role="document">';

echo "<div class='{$class_form}-content' role='document' >";
        
 

if($param->popup ){
    if($module->showtitle && $module->title){
        echo "<div class='{$class_form}-header'><$param->header_tag class='{$class_form}-title' id='mfHeader_$module->id'>$module->title</$param->header_tag>
            </div>";
    }else{
//        echo  "<button class='close mfClose btn button' id='mfClose_$module->id' data-id='$module->id' data-dismiss='modal' type='button'  aria-label='".JText::_('JLIB_HTML_BEHAVIOR_CLOSE')."'>" //onClick='mfCloseModal(this);'
//            . ($param->icomoon?"<span class='icon-delete large-icon fa fa-lg fas fa-times ' aria-hidden='true' > </span>":"<span aria-hidden='true'>&times;</span>")//кнопка ЗАКРЫТЬ &times;
//            . "</button>";
    }
}


$action = JUri::root();


if($param->textbeforeformShow && ($param->textbeforeform1 || $param->textbeforeform2 )){
    $param->textbeforeform1 = modMultiFormHelper::getArticles($param->textbeforeform1);
    echo "<div class='mfBeforeForm id$module->id' id='mfDescribe_$module->id'>$param->textbeforeform1 $param->textbeforeform2</div>";
    //echo "<div class='mfBeforeText'>$textbeforeform1".str_replace(array("\r\n", "\r", "\n"), '',  $textbeforeform2)."</div>";
} 
 

echo "<form class='mfStatusForm {$class_form}-body id$module->id  mf' action='$action' $method id='mfForm_form_$module->id' data-id='$module->id'>";

 
foreach($fields as $field){
    echo $field['dataField'];
}

$captcha_attr='';
$captcha_class = "";
$captcha_type = ''; 
//echo "<script>"."console.log('🚀 Captcha_type-$module->id:','$param->captcha' )"."</script>";
if($param->captcha){     
    $captcha_type = JFactory::getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0
     
    echo "<script>"."console.log('🚀 ->ConsoleDefault.php - Captcha_type-$module->id:','$captcha_type 🚀' )"."</script>";
if($captcha_type){ 
    $plugin = modMultiFormHelper::captcha_element_attribute($module->id);
//echo '<pre style="color: green;">'. count([]).'----'. strlen($captcha_type).'------'.print_r($plugin,true).'</pre>';
    $captcha_attr = $plugin->attributes;
    $captcha_id = "dynamic_captcha_$module->id";
    $captcha_class = "$captcha_type $plugin->badge -g-recaptcha captcha";
        
//        $plugin->badge;//bottomright, bottomleft, inline
//        $plugin->theme2;//light, dark
//        $plugin->size;//normal, compact
        
//        JPluginHelper::importPlugin('captcha'); 
//        JPluginHelper::importPlugin('captcha', $captcha_type, true);
//        $post = JFactory::getApplication()->input->post;
//        
//        $plugin = JPluginHelper::getPlugin('captcha', $captcha_type);
//        $plugin->params->get('public_key', '');
        
        
        
//    if(in_array($captcha_type, ['recaptcha_invisible','recaptcha',])) {
//        //echo "<div id='dynamic_captcha_$module->id' class='form-control $captcha_class'  $captcha_attr></div>";
//        
////        $captcha_element = JFactory::getApplication()->triggerEvent('onDisplay',[null,$captcha_id,' form-control input ']);
////        echo implode('', $captcha_element) ; 
////echo '<pre style"color: green;">'. count($captcha_element).'----'. strlen($captcha_element[0]).'+'. strlen($captcha_element[1])
////        .'------'.print_r($captcha_element,true).'</pre>';
//    }
        
        
        
//        $captcha_attr  = " data-sitekey='your_site_key' data-callback='onSubmit' ";
        //$captcha_class = " g-recaptcha ";
        
//        JPluginHelper::importPlugin('captcha'); 
//        JFactory::getApplication()->triggerEvent('onInit',$captcha_id);
        
//    if($captcha_type == 'recaptcha') {
//        echo "<div id='dynamic_captcha_$module->id' class='-form-control captcha g-recaptcha $captcha_type '  $plugin->attributes style='transform: scale(0.8);'></div>";
//    }   
//    if($captcha_type == 'recaptcha_invisible') {
//        $captcha_class = " g-recaptcha ";
//        echo "<div id='dynamic_captcha_$module->id' class='-form-control captcha g-recaptcha $captcha_type '  $plugin->attributes style='transform: scale(0.7);'></div>";
//    }   
    //////////////echo "<div id='dynamic_captcha_$module->id' class='-form-control $captcha_class'  $captcha_attr style='transform: scale(0.7);'></div>";
    
        
//    JHtml::_('behavior.keepalive');
//JSif (!grecaptcha.getResponse(0)) {
//JS    $('.recaptcha').addClass("has_error");
//JS    return false;
//JS}
}
}

echo  "</form>";

echo  "<div class='mfStatusError {$class_form}-body id$module->id ' style='display: none;'></div>";
echo  "<div class='mfStatusDone {$class_form}-body id$module->id ' style='display: none;'></div>"; 
 
echo  "<div class='mfFieldGroup {$class_form}-footer id$module->id '>  "
    . "<div id='dynamic_captcha_$module->id' class='-form-control $captcha_class' {$captcha_attr}  style='transform: scale(0.8);'></div>"
    . " <input type='submit' form='mfForm_form_$module->id' value='$param->textsubmit' id='submit_$module->id' " 
        . " class='$param->classbuttonsubmit  submit $captcha_class' {$captcha_attr} data-ready-text='$param->textsubmit' data-sending-text='$param->textwhensending'/>"
    . "</div>"; // $captcha_class $captcha_attr

if($param->popup ){    
    echo "<label class='mfCloseLabel' aria-label='".JText::_('JLIB_HTML_BEHAVIOR_CLOSE')."'>
            <button class='close mfClose  btn button' id='mfClose_$module->id' data-id='$module->id' data-dismiss='modal' type='button' aria-label='".JText::_('JLIB_HTML_BEHAVIOR_CLOSE')."'  rel='modal:close'  >"
        . ($param->icomoon?"<span class='icon-delete large-icon fa fa-lg fas fa-times' aria-hidden='true' > </span>":"<span aria-hidden='true'>&times;</span>")
        . "</button></label>";
}

echo '</div>';

//echo  "<div class='mfStatusError {$class_form}-content id$module->id' style='display: none;'><div class='{$class_form}-body id$module->id '></div></div>";
//echo  "<div class='mfStatusDone {$class_form}-content id$module->id' style='display: none;'><div class='{$class_form}-body id$module->id '></div></div>"; 

//-----------------------------------------------------------
if($param->captcha && $captcha_type ){ 
//    echo "<script type='text/javascript' async-defer> ";
//    echo "  console.log('🚀 Captcha - $module->id 🚀');";
//    echo "  //captcha$module->id();";
//    echo " // grecaptcha.render('dynamic_captcha_$module->id');";
//    echo "  grecaptcha.render('submit_$module->id');"; 
//    echo "</script>";
?>
<script type='text/javascript' async-defer>
console.log('🚀 ->ConsoleDefault.php - Captcha-<?=$module->id?> 🚀');
////grecaptcha.render('submit_<?=$module->id?>');
//grecaptcha.render('dynamic_captcha_<?=$module->id?>');//,{size:'invisible'}
//captcha<?=$module->id?>();
function CapRun(){console.log('🚀 Captcha OK ->CapRun(): ->ConsoleDefault.php  --- 🚀');}
function CapOut(){console.log('⏳Captcha TimeOut ->CapOut(): ->ConsoleDefault.php  --- ⏳');}
function CapErr(){console.log('☠Captcha Error ->CapErr(): ->ConsoleDefault.php  --- ☠');}
</script>
<?php    
}
//-----------------------------------------------------------
//echo '<pre style"color: green;">'. print_r($param->captcha,true).'</pre>';//return'';
//echo '<pre style"color: green;">'. print_r($captcha_type,true).'</pre>';//return'';
//echo '<pre style"color: green;">'. print_r($captcha_attr,true).'</pre>';//return'';

echo  "</$tag_form>";

//if($onoffpopup): 
//return;
//echo "</div>";//подложка
if($param->popup){
    echo "<div id='mfOverlay_$module->id'  data-id='$module->id' class='mfOverlay id$module->id overlay_$module->id modal-backdrop fade in'  ></div>";//подложка onClick='mfCloseModal(this);'
}
//<script type="text/javascript" src="modules/mod_multi_form/js/messages.min.js"></script>
//endif; ?>