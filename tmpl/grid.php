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
 
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory as JFactory;
use Joomla\Registry\Registry as JRegistry;

$document			=  JFactory::getDocument();
//$configure			=  JFactory::getConfig();

$params = new \Reg($params);
$param = &$params;

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
    JHtml::stylesheet("modules/$module->module/media/css/$css_file");
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

$method=$fields_test?' method="post" ':'';

//echo "<div id='mfOverlay_$module->id' data-id='$module->id' class='modal fade mfOverlay overlay_$module->id' aria-labelledby='$module->title'  role='dialog' tabindex='-1'  aria-hidden='true'>";//подложка
echo "<$tag_form id='mfForm_$module->id' class='mfForm_{$class_form} -modal-dialog {$class_form}_$module->id id$module->id $param->moduleclass_sfx $param->style style_$style'"
        . " role='document' style='$show_debug_modal' data-moduleid='$module->id' data-id='$module->id'  "
                . "  >";//само окно    data-sending='$param->textwhensending'
//    echo '<div class="modal-dialog" role="document">';
echo "<div class='{$class_form}-content'>";

 

if($param->popup ){
    if($module->showtitle && $module->title){
        echo "<div class='{$class_form}-header'><$param->header_tag class='{$class_form}-title'>$module->title</$param->header_tag>
            <button class='close mfClose' id='mfClose_$module->id' data-id='$module->id' data-dismiss='modal' type='button' aria-label='".JText::_('JLIB_HTML_BEHAVIOR_CLOSE')."' >"
        . ($param->icomoon?"<span class='icon-remove large-icon' aria-hidden='true' > </span>":"<span aria-hidden='true'>&times;</span>")
        . "</button></div>";
    }else{
        echo  "<button class='close mfClose ' id='mfClose_$module->id' data-id='$module->id' data-dismiss='modal' type='button'  aria-label='".JText::_('JLIB_HTML_BEHAVIOR_CLOSE')."'>" //onClick='mfCloseModal(this);'
            . ($param->icomoon?"<span class='icon-remove large-icon' aria-hidden='true' > </span>":"<span aria-hidden='true'>&times;</span>")//кнопка ЗАКРЫТЬ &times;
            . "</button>";
    }
}

$action = $module->deb ? " action='index.php?option=com_ajax&module=multi_form&format=raw&id=$module->id&deb=$module->deb' " : '';


echo "<form class='mfPanelForm {$class_form}-body id$module->id  mf' $method $action id='mfForm_form_$module->id' data-id='$module->id'> ";



if($param->textbeforeformShow && ($param->textbeforeform1 || $param->textbeforeform2 )){ 
    $textbeforeform1 = modMultiFormHelper::getArticles($params->get( 'textbeforeform1',0));
    echo "<div class='mfBeforeText id$module->id'>$textbeforeform1 $param->textbeforeform2</div>";
    //echo "<div class='mfBeforeText'>$textbeforeform1".str_replace(array("\r\n", "\r", "\n"), '',  $textbeforeform2)."</div>";
} 
 
foreach($fields as $field){
    echo $field['dataField'];
} 
echo  "</form>";

echo  "<div class='mfPanelError {$class_form}-body id$module->id ' style='display: none;'></div>";
echo  "<div class='mfPanelDone {$class_form}-body id$module->id ' style='display: none;'></div>"; 
 
echo  "<div class='mfFieldGroup {$class_form}-footer id$module->id '>  "
    . " <input type='submit' form='mfForm_form_$module->id' value='$param->textsubmit' id='submit_$module->id' class=' $param->classbuttonsubmit  submit form-control' data-ready='$param->textsubmit' data-sending='$param->textwhensending'/>"
    . "</div>"; 

echo '</div>';

//echo  "<div class='mfPanelError {$class_form}-content id$module->id' style='display: none;'><div class='{$class_form}-body id$module->id '></div></div>";
//echo  "<div class='mfPanelDone {$class_form}-content id$module->id' style='display: none;'><div class='{$class_form}-body id$module->id '></div></div>"; 



echo  "</$tag_form>";

//if($onoffpopup): 
//return;
//echo "</div>";//подложка
if($param->popup){
    echo "<div id='mfOverlay_$module->id'  data-id='$module->id' class='mfOverlay id$module->id   overlay_$module->id'  ></div>";//подложка onClick='mfCloseModal(this);'
}
//<script type="text/javascript" src="modules/mod_multi_form/media/js/messages.min.js"></script>
//endif; ?>