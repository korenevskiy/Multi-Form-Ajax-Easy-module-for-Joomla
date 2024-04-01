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
use Joomla\CMS\Uri\Uri as JUri;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory as JFactory;
use Joomla\Registry\Registry as JRegistry;

use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;
use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
//return;
//echo \Joomla\CMS\Uri\Uri::base(). "<br>";
//echo \Joomla\CMS\Uri\Uri::root(). "<br>";

if(file_exists(__DIR__ . '/functions.php'))
	require_once  __DIR__ . '/functions.php';
// Include the helper.
require_once __DIR__ . '/helper.php';
//require_once JPATH_BASE . '/components/com_content/helpers/route.php'; 
 
$params = new Reg($params);

//new Joomla\CMS\Table\Table;
//$tbl = Joomla\CMS\Table\Table::getInstance('Content');
//$tbl->load(16);
//$tbl->test();
//echo $tbl->introtext;	//a.introtext, a.fulltext,
//echo $tbl->fulltext;	//a.introtext, a.fulltext,

//Проверка условий показов
// <editor-fold defaultstate="collapsed" desc="Проверка условий показов">
//static $dispay_mods;
//if(is_null($dispay_mods))
//    $dispay_mods = [];
if(!empty($module->position)):
if(!modMultiFormHelper::requireWork($params)){
    $pos = $module->position;
    $mod_list_pos = mfModuleHelper::ModeuleDelete($module); 
//    toPrint($mod_list_pos,'$mod_list_pos:'.$module->id);
    
    if(empty($mod_list_pos))
        JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n.container-$pos{\ndisplay:none;}\n"); 
    if(empty($mod_list_pos))
        JFactory::getDocument()->setBuffer(FALSE,'modules',$module->position); 
    
//    $module = NULL;
    unset($module); 
    return FALSE;
}
else{
//    $dispay_mods[$module->id] = TRUE;
}
endif;// </editor-fold>

//if($module->id == 112)
//toPrint($params->recipient_show,'$module->moduleclass_sfx',0,'pre',true);
//toPrint($params->sendtoemail,'$params->sendtoemail User',0,'pre',TRUE);

//$params->set('header_tag', $params->get('head_tag'));
//$params->set('module_tag', $params->get('mod_tag'));
 

//toPrint($params->module_tag,'module_tag'); 
//toPrint($params->mod_tag,'mod_tag'); 

 modMultiFormHelper::constructor($params);
 

//$moduleclass_sfx = htmlspecialchars( $params->moduleclass_sfx );
//$params->set( 'moduleclass_sfx', $params->get( 'moduleclass_sfx' ). ' mfForm ' );
 

if($params->get('popup')){
    $module->showtitle = false;
}

//if($params->get( 'form_use_id')){ 
//    require JModuleHelper::getLayoutPath('mod_multi_form', '_button');
//    return;
//}

//$textbeforeformShow		= $params->get( 'textbeforeformShow' );
//$textbeforeform1			= modMultiFormHelper::getArticle($params->get( 'textbeforeform1',0));
//$textbeforeform2			= $params->get( 'textbeforeform2' );

//function eventMulti(...$arg){
//    toPrint($arg,'$arg',0,'pre');
////    $arg[] = 'eventMulti';
////    modMultiFormHelper::event($arg);
//};
//        toPrint(JPATH_ROOT. '/events.txt','Event $file',0,'pre');
jimport( 'joomla.application.application' );
//$event = new Joomla\Event\Event(); 
//system ---------------------  
//    JFactory::getApplication()->registerEvent('onAfterInitialise', function(...$arg){toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onAfterRoute', function(...$arg){toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onAfterDispatch', function(...$arg){toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onAfterRender', function(...$arg){$arg[]='!6';toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onBeforeRender', function(...$arg){toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onBeforeCompileHead', function(...$arg){$arg[]='!5';toPrint($arg,'$arg',0,'pre');});
//JFactory::getApplication()->registerEvent('onBeforeCompileHead',       [new modMultiFormHelper,'event']); 
//Field --------------------- 
//JFactory::getApplication()->registerEvent('onContentPrepareForm',       [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onCustomFieldsGetTypes',     [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onCustomFieldsPrepareDom',   [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onCustomFieldsPrepareField', [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentBeforeSave',        [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onCustomFieldsBeforePrepareField',[new modMultiFormHelper,'event']); 
//module --------------------- 
//    JFactory::getApplication()->registerEvent('onRenderModule', function(...$arg){$arg[]='!1,3';toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onAfterRenderModule', function(...$arg){$arg[]='!2,4';toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onPrepareModuleList', function(...$arg){toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onAfterModuleList', function(...$arg){toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onAfterCleanModuleList', function(...$arg){toPrint($arg,'$arg',0,'pre');}); 
//Content ---------------------
//! onContentPrepare
//! onContentAfterTitle
//! onContentBeforeDisplay
//! onContentAfterDisplay
//JFactory::getApplication()->registerEvent('onContentPrepare',       [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentAfterTitle',    [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentBeforeDisplay', [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentAfterDisplay',  [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentBeforeSave',    [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentAfterSave',     [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentPrepareForm',   [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentPrepareData',   [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentBeforeDelete',  [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentAfterDelete',   [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentChangeState',   [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentSearch',        [new modMultiFormHelper,'event']);
//JFactory::getApplication()->registerEvent('onContentSearchAreas',   [new modMultiFormHelper,'event']); 

//    JFactory::getApplication()->registerEvent('onBeforeCompileHead', function(...$arg){toPrint($arg,'$arg',0,'pre');});
//    JFactory::getApplication()->registerEvent('onContentPrepare', [new modMultiFormHelper,'event']);//onPrepareContent  onBeforeRender
    ////onPrepareContent  !onBeforeCompileHead 'eventMulti'
//JFactory::getApplication()->registerEvent('onBeforeCompileHead', 'modMultiFormHelper');//onPrepareContent
//JFactory::getApplication()->registerEvent('onBeforeCompileHead', 'modMultiFormHelper');//onPrepareContent

//$textbuttonpopup = $textcallpopup = $params->get( 'textbuttonpopup',$params->get( 'textcallpopup' ) ); 

//$sendtoemail 			= $params->get( 'sendtoemail' );
//$sendtoemailcc 		= $params->get( 'sendtoemailcc' );
//$sendtoemailbcc 		= $params->get( 'sendtoemailbcc' );

//$sendfromemail 			= $params->get( 'sendfromemail' );

//$clearaftersend			= $params->get( 'clearaftersend' );//Очищать форму clearaftersend

//$subjectofmail 			= $params->get( 'subjectofmail' );
//$textsuccesssend1 		= modMultiFormHelper::getArticle($params->get( 'textsuccesssend1' ));
//$textsuccesssend2 		= $params->get( 'textsuccesssend2' );
//$textsubmit 			= $params->get( 'textsubmit' );
//$textwhensending 		= $params->get( 'textwhensending' );//Тект при отправке

//$colorscheme 			= $params->get( 'colorscheme' );
 
$allparams  = $params->list_fields ?: null;//s->get( 'list_fields' );//json_decode($params->get( 'list_fields' ));
//$allparams->select_editor = $params->select_editor || 'tinymce';   
if(($allparams->typefield??false) && in_array('editor', $allparams->typefield)){
    
//toPrint($allparams->typefield,'$allparams+'.$params->select_editor,0,'pre',true);
    jimport( 'joomla.html.editor' );
    JPluginHelper::importPlugin('editors');
    if(class_exists('Ed') == false){ 
    class Ed extends \Joomla\CMS\Editor\Editor{

        public static function getInstance($editor = 'none') {  
            $signature = serialize($editor);

            if (empty(self::$instances[$signature]))
            {
		self::$instances[$signature] = new Ed($editor);
            }

            return self::$instances[$signature];
        }
	/** Load the editor
	 * @param   array  $config  Associative array of editor config parameters
	 * @return  mixed
	 * @since   1.5
	 */
        public function Load($config = []){
            $this->_loadEditor($config);
            return $this;
        }
    }
	}
	
    $editor = Ed::getInstance($params->select_editor)->Load(); 
    
        
    
    //$fieldB = JEditor::getInstance($params->select_editor || 'tinymce')->initialise();
    //\Joomla\CMS\Editor\Editor::getInstance($params->select_editor || 'tinymce')->initialise();
//$plg = JPluginHelper::getPlugin('editors','tinymce'); //->onInit() ,$params->select_editor || 'tinymce'
//         $methods = get_class_methods($editor);
//         $methods = get_class($plg);
//toPrint($methods,'$methods',0,'pre',true); 
//toPrint($editor,'$editor',0,'pre',true); 

//$plg = new PlgEditorTinymce();
//		\Joomla\CMS\HTML\HTMLHelper::_('behavior.core');
//		\Joomla\CMS\HTML\HTMLHelper::_('behavior.polyfill', array('event'), 'lt IE 9');
//		\Joomla\CMS\HTML\HTMLHelper::script('media/editors/tinymce/tinymce.min.js', array('version' => 'auto'));
//		\Joomla\CMS\HTML\HTMLHelper::script('editors/tinymce/tinymce.min.js', array('version' => 'auto', 'relative' => true));
}else{
	$params->list_fields = [];
}


//$fieldB = JEditor::getInstance($params->select_editor)->display($nameforfield, $valueforfield/*$namefield.$reqstar*/, '100%', 'auto', 10, 4, TRUE, $nameforfield, NULL, NULL,$paramsEditor);
                                  
//                                    $editor = JEditor::getInstance('tinymce');
//                                     toPrint($editor,'Editor') ;
                                //arkeditor, tinymce,  codemirror none   
if($params->jsbeforesend ?: false){
    $jssend  = "function funcBefore$module->id(id){ $params->jsbeforesend }";
    JFactory::getDocument()->addScriptDeclaration($jssend);
} 
 if($params->jsaftersend ?: false){
    $jssend  = "function funcAfter$module->id(id){ $params->jsaftersend }";
    JFactory::getDocument()->addScriptDeclaration($jssend);
 }

//$allparams  = json_decode($params->get( 'list_fields' ));
//$allparams->select_editor = $params->select_editor || 'tinymce';  


//$moduleid = $module->id;
//$moduleTitle			= $module->title;
//$field          = $fields       = modMultiFormHelper::buildFields($allparams, $moduleid, $onoffpopup, $params->nameInOut);
//$dataField			= modMultiFormHelper::dataFields($allparams, $moduleid);
//$dataField				= modMultiFormHelper::getFieldsData($allparams, $moduleid);

//$component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
$menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
$Itemid = " data-Itemid='$menu_id' ";
 


//echo "<pre>".$module->id.'-'. print_r($module->assigned, true). "</pre>";

//$ajaxRequest			= modMultiFormHelper::ajaxRequestFields($allparams, $moduleid);

//toPrint($field,'$field',0);
//toPrint($params->get( 'list_fields' ),'list_fields',0);
//toPrint($allparams,'$allparams',0);
//toPrint($dataField,'$dataField',0);
//toPrint($ajaxRequest,'$ajaxRequest',0);

//$ajaxDataFields		= modMultiFormHelper::ajaxDataField($allparams, $moduleid);
//$validateFieldsForm	= modMultiFormHelper::validateFieldsForm($allparams, $moduleid);

// Instantiate global document object

//static $js;
//
//if(empty($js)){
//    $js = "jQuery(function(){mfOpenModalClick(); });";
//    JFactory::getDocument()->addScriptDeclaration($js);
//}
if(empty(modMultiFormHelper::isJ4()))
	JHtml::stylesheet(JUri::base().'/media/jui/css/icomoon.css'); 

//if($params->get( 'popup' )){ 
require JModuleHelper::getLayoutPath($module->module, '_form');
//}
//else{
////    $path = JModuleHelper::getLayoutPath($module->module, '_static');
////    echo $path;
//    require JModuleHelper::getLayoutPath($module->module, '_static');
//}
 
//require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));



//JFactory::getDocument()->addScriptDeclaration($crutch);



 return;

$cacheparams = new \stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'Joomla\Module\MultiForm\Site\Helper\modMultiFormHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

$list = JModuleHelper::moduleCache($module, $params, $cacheparams);

require JModuleHelper::getLayoutPath('mod_multi_form','_form');