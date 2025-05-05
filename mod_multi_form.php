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
use \Joomla\Module\MultiForm\Site\Option as OptionField;


//echo \Joomla\CMS\Uri\Uri::base(). "<br>";
//echo \Joomla\CMS\Uri\Uri::root(). "<br>";
//echo 'xxx+'.$module->id;
//toPrint();
//toPrint( $module->id,'$module->id ' ,0, 'pre' ,true);
//echo " <pre>ZZZ $module->id</pre>";

//defined('JPATH_MODMULTIFORM') || define('JPATH_MODMULTIFORM', __DIR__);

if(empty(class_exists('\Reg'))){
	include_once __DIR__ . "/libraries/reg.php";
}
 
if( ! $params instanceof \Reg)
	$params = (new \Reg())->merge($params);
$param	= &$params;


if($param->debug == 'get'){
	$deb = JFactory::getApplication()->input->getString('deb','');
	if(! in_array('multiForm', (array)explode(',', $deb)))
		return;
	
}

if(file_exists(JPATH_ROOT . '/libraries/fof40/Utils/helpers.php'))
	require_once JPATH_ROOT . '/libraries/fof40/Utils/helpers.php';


if(file_exists(__DIR__ . '/functions.php'))
	require_once  __DIR__ . '/functions.php';
// Include the helper.

require_once __DIR__ . '/helper.php';
//require_once JPATH_BASE . '/components/com_content/helpers/route.php'; 


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
if(! \modMultiFormHelper::requireWork($param)){
    $pos = $module->position;
    $mod_list_pos = \Joomla\Module\MultiForm\Site\JModuleHelper::ModeuleDelete($module); 
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
//toPrint($param->recipient_show,'$module->moduleclass_sfx',0,'pre',true);
//toPrint($param->sendtoemail,'$param->sendtoemail User',0,'pre',TRUE);

//$param->set('header_tag', $param->get('head_tag'));
//$param->set('module_tag', $param->get('mod_tag'));
 

//toPrint($param->module_tag,'module_tag'); 
//toPrint($param->mod_tag,'mod_tag'); 

 modMultiFormHelper::constructor($param);


//$moduleclass_sfx = htmlspecialchars( $param->moduleclass_sfx );
//$param->set( 'moduleclass_sfx', $param->get( 'moduleclass_sfx' ). ' mfForm ' );
 

if($param->get('popup')){
    $module->showtitle = false;
}

//if($param->get( 'form_use_id')){ 
//    require JModuleHelper::getLayoutPath('mod_multi_form', '_button');
//    return;
//}

//$textbeforeformShow		= $param->get( 'textbeforeformShow' );
//$textbeforeform1			= modMultiFormHelper::getArticle($param->get( 'textbeforeform1',0));
//$textbeforeform2			= $param->get( 'textbeforeform2' );

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

//$textbuttonpopup = $textcallpopup = $param->get( 'textbuttonpopup',$param->get( 'textcallpopup' ) ); 

//$sendtoemail 			= $param->get( 'sendtoemail' );
//$sendtoemailcc 		= $param->get( 'sendtoemailcc' );
//$sendtoemailbcc 		= $param->get( 'sendtoemailbcc' );

//$sendfromemail 			= $param->get( 'sendfromemail' );

//$clearaftersend			= $param->get( 'clearaftersend' );//Очищать форму clearaftersend

//$subjectofmail 			= $param->get( 'subjectofmail' );
//$textsuccesssend1 		= modMultiFormHelper::getArticle($param->get( 'textdonesend_id' ));
//$textdonesend_message 	= $param->get( 'textdonesend_message' );
//$textsubmit 			= $param->get( 'textsubmit' );
//$textwhensending 		= $param->get( 'textwhensending' );//Тект при отправке

//$colorscheme 			= $param->get( 'colorscheme' );

require_once JPATH_ROOT . '/functions.php';

$fileMD5Sum = JPATH_ROOT . '/modules/mod_multi_form/media/MD5SUM';
$versionScript = $param->debug == 'debug'? time() : ($param->versionScript ?: (file_exists($fileMD5Sum) ? file_get_contents($fileMD5Sum) : ''));
$min = modMultiFormHelper::$min ? '.min' : '';//modMultiFormHelper::$min;
 
//$file = __DIR__ . '/_multi_form.txt';
//file_put_contents($file, "\n\n=$module->id============================\n".print_r($param->list_fields,true)."\n++++++++++++++++++++++++++++++++++++++++++++\n\n", FILE_APPEND);

$param->list_fields = $param->list_fields ? new Reg($param->list_fields,'.',false) : null;//s->get( 'list_fields' );//json_decode($param->get( 'list_fields' ));
$allparams = $param->list_fields;
//toPrint( $allparams,'$allparams '.$module->id,0,($module->id==175? 'message':''),true);
// toPrint(); 
//echo 'HabraCadabra'; 
// toPrint($allparams,'$param',0,'message',true);
// toPrint($allparams,'$param',0,'pre',true);
// toPrint($allparams);
//echo 123;
//return;
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, "\n\n=$module->id============================\n++++++++++++++++++++++++++++++++++++++++++++\n\n");

//toPrint();
//if($module->id == 175){ 
////toPrint($allparams,'$param->list_fields ccc '.$module->id,0,'message',true);
//toPrint($allparams->field_type,'$param->list_fields->field_type zzz'.$module->id,0,'message',true);
//}

// <editor-fold _defaultstate="collapsed" desc=" -->> Подключение скриптов для поля текстового редактора">

//$allparams->select_editor = $param->select_editor || 'tinymce';   
if (($allparams->field_type ?? false) && in_array('editor', $allparams->field_type)) {


//toPrint($allparams->field_type,'$allparams+'.$param->select_editor,0,'pre',true);
	jimport('joomla.html.editor');
	JPluginHelper::importPlugin('editors');
	if (class_exists('Ed') == false) {


		class Ed extends \Joomla\CMS\Editor\Editor {

			public static function getInstance($editor = 'none') {

				$signature = serialize($editor);

				if (empty(self::$instances[$signature]))             {
					self::$instances[$signature] = new Ed($editor);
				}

				return self::$instances[$signature];
			}

			/** Load the editor
			 * @param   array  $config  Associative array of editor config parameters
			 * @return  mixed
			 * @since   1.5
			 */
			public function Load($config = []) {
				$this->_loadEditor($config);
				return $this;
			}
		}

	}
	$editor = Ed::getInstance($param->select_editor)->Load();
    //$fieldB = JEditor::getInstance($param->select_editor || 'tinymce')->initialise();
	//\Joomla\CMS\Editor\Editor::getInstance($param->select_editor || 'tinymce')->initialise();
//$plg = JPluginHelper::getPlugin('editors','tinymce'); //->onInit() ,$param->select_editor || 'tinymce'
//         $methods = get_class_methods($editor);
//         $methods = get_class($plg);
//toPrint($methods,'$methods',0,'pre',true); 
//toPrint($editor,'$editor',0,'pre',true); 

//$plg = new PlgEditorTinymce();
//		\Joomla\CMS\HTML\HTMLHelper::_('behavior.core');
//		\Joomla\CMS\HTML\HTMLHelper::_('behavior.polyfill', array('event'), 'lt IE 9');
//		\Joomla\CMS\HTML\HTMLHelper::script('media/editors/tinymce/tinymce.min.js', array('version' => 'auto'));
//		\Joomla\CMS\HTML\HTMLHelper::script('editors/tinymce/tinymce.min.js', array('version' => 'auto', 'relative' => true));
}// </editor-fold>


//$fieldB = JEditor::getInstance($param->select_editor)->display($nameinput, $valueforfield/*$namefield.$reqstar*/, '100%', 'auto', 10, 4, TRUE, $nameinput, NULL, NULL,$paramsEditor);
                                  
//                                    $editor = JEditor::getInstance('tinymce');
//                                     toPrint($editor,'Editor') ;
                                //arkeditor, tinymce,  codemirror none   
if($param->jsbeforesend ?: false){
	$jssend  = "function funcBefore$module->id(id){ $param->jsbeforesend }";
	JFactory::getDocument()->addScriptDeclaration($jssend);
} 
if($param->jsaftersend ?: false){
	$jssend  = "function funcAfter$module->id(id){ $param->jsaftersend }";
	JFactory::getDocument()->addScriptDeclaration($jssend);
}

//$allparams  = json_decode($param->get( 'list_fields' ));
//$allparams->select_editor = $param->select_editor || 'tinymce';  


//$moduleid = $module->id;
//$moduleTitle			= $module->title;
//$field          = $fields       = modMultiFormHelper::buildFields($allparams, $moduleid, $onoffpopup, $param->nameInOut);
//$dataField			= modMultiFormHelper::dataFields($allparams, $moduleid);
//$dataField				= modMultiFormHelper::getFieldsData($allparams, $moduleid);

//$component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
$menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
$Itemid = " data-Itemid='$menu_id' ";



//echo "<pre>".$module->id.'-'. print_r($module->assigned, true). "</pre>";
$datasetParams = [];
$optionsFields = [];

//if($module->id == 175 || $param->debug == 'debug') {
//$ajaxRequest			= modMultiFormHelper::ajaxRequestFields($allparams, $moduleid);

//$wa = new \Joomla\CMS\WebAsset\WebAssetManager;
$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();

//toPrint();
//toPrint($min,'$min',0,'message');
//toPrint($allparams,'$allparams->option_params && $allparams->option_params[$i]',0,'message');

//toPrint();
//toPrint( $allparams,'$allparams '.$module->id,0,($module->id==175? 'message':''),true);
//toPrint($param,'$param',0,'pre',true);
//echo 123;
//echo "<pre>".$module->id.'-'. print_r($allparams->field_type, true). "</pre>";
//toPrint($allparams->namefield,$module->id.'$allparams->namefield',0,'pre',true);
//return;
//toPrint($allparams->onoff,'$allparams->onoff',0,'message'); 


foreach (array_filter((array)($allparams->onoff ?? [])) as $i => $onoff){		//typefield field_type
	
	$type = ((array)$allparams->field_type)[$i] ?? '';
	
//toPrint($allparams->onoff[$i],$i,0,'message');
//toPrint($type,$i,0,'message');
//toPrint( $type,'$type '.$module->id,0,($module->id==175? 'message':''),true);
	
//	$allparams->option_params;
//	$allparams->field_type[];
//	echo '<pre>'.print_r($type,true) .'</pre>';
	
	
	if(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$type$min.css"))
		$wa->registerAndUseStyle('mod_multi_form.style.'.$type,  \Joomla\CMS\Uri\Uri::base() . "modules/mod_multi_form/options/$type$min.css");
	
	if(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$type$min.js"))
		$wa->registerAndUseScript('mod_multi_form.script.'.$type, \Joomla\CMS\Uri\Uri::base() . "modules/mod_multi_form/options/{$type}$min.js",
		['version' => ($versionScript?:'auto'), 'relative' => false, 'detectDebug' => ($min?:true)], ['defer' => true,'async' => true]);
	
	if(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$type.php")){
		require_once JPATH_ROOT . "/modules/mod_multi_form/options/$type.php";
	}
	elseif(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$type.xml")){
		$dom = new DOMDocument();
		$dom->load(JPATH_ROOT . "/modules/mod_multi_form/options/$type.xml");
		$type = $dom->getElementsByTagName('form')->item(0)->getAttribute('type') ?: $type;
		
		if(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$type.php"))
			require_once JPATH_ROOT . "/modules/mod_multi_form/options/$type.php";
		
		if(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$type$min.css"))
			$wa->registerAndUseStyle('mod_multi_form.style.'.$type,  \Joomla\CMS\Uri\Uri::base() . "modules/mod_multi_form/options/$type$min.css");
	
		if(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$type$min.js"))
			$wa->registerAndUseScript('mod_multi_form.script.'.$type, \Joomla\CMS\Uri\Uri::base() . "modules/mod_multi_form/options/{$type}$min.js",
			['version' => ($versionScript?:'auto'), 'relative' => false, 'detectDebug' => ($min?:true)], ['defer' => true,'async' => true]);
		
	}
	
	
//	$wa = new \Joomla\CMS\WebAsset\WebAssetManager;

//toPrint();
//toPrint($module->id);
//echo 123;
//toPrint($allparams,'$allparams '.$module->id,0,($module->id==175? 'message':''),true);
//toPrint( $allparams->option_params,'$allparams->option_params',0,$module->id==175? 'pre':'',true);
//toPrint("/modules/mod_multi_form/options/$type$min.js",''.$type.': ',0,'message');
		if($allparams->option_params && $allparams->option_params[$i]){
			$_type = 'text/plain';
			$start = substr($allparams->option_params[$i], 0, 1);
			$stop  = substr($allparams->option_params[$i], -1, 1);
			if($start == '{' && $stop == '}')
				$_type = 'application/json';
			elseif($start ==  '['  && $stop == ']' )
				$_type = 'application/json';
			elseif($start == '<'  && $stop == '>' )
				$_type = 'text/html';
			
			$paramsField = '';
//toPrint($_type,$type.$i,0,'message');
			
			$optionClass = "\Joomla\Module\MultiForm\Site\Option" . ucfirst($type);
//echo $optionClass.'<br>'	;
//toPrint();
//toPrint( class_exists($optionClass)?$optionClass ." YES":"$optionClass NO",'',                       0,($module->id==175? 'pre':''),true);
//toPrint( class_exists($optionClass)?$optionClass ." YES":"$optionClass NO",'$allparams '.$module->id,0,($module->id==175? 'message':''),true);
//toPrint( is_subclass_of($optionClass, 'BaseOption')?$optionClass ." YES":"$optionClass NO",'$allparams '.$module->id,0,($module->id==175? 'message':''),true);
			if(class_exists($optionClass) && is_subclass_of($optionClass, '\Joomla\Module\MultiForm\Site\Option')){ //JFormFieldCalculator
//toPrint($optionClass,'$optionClass',0,'message');
				
//			$allparams->nameForFields[$i] = ($allparams->field_type[$i] ?? 'text') . $i . $moduleid;
//				$this->type . $i . $this->moduleID
				
//echo $optionClass .'<br> ';
				/** @var OptionField $optionField */
				
				$optionField	= new $optionClass;
				
				$optionField->property('field_label',	);
				$optionField->property('placeholder',	);
				$optionField->property('field_name',		);
				$optionField->property('field_type',		);
//				$optionField->property('option_params',	$allparams->option_params[$i]);
				$optionField->property('art_id',			);
				$optionField->property('onoff',			);
				
				$nameinput		= $allparams->field_name[$i] ?: $allparams->field_type[$i] . $i . $module->id;
				
				$props = [];
				$props['field_label']	= $allparams->field_label[$i];
				$props['placeholder']	= $allparams->placeholder[$i];
				$props['field_name']	= $allparams->field_name[$i];
				$props['field_type']	= $allparams->field_type[$i];
				
				$paramsOption			= $allparams->option_params[$i] ?: null;
				
				$props['art_id']		= is_numeric($paramsOption) ? $paramsOption : 0;
				
				if(is_object($paramsOption) && $paramsOption->art_id ?? 0)
					$props['art_id']	= $paramsOption->art_id;
				
				$props['onoff']			= ((array)$allparams->onoff)[$i];
				$props['nameinput']		= $nameinput;
				$props['paramsOption']	= $paramsOption;
				$props['index']			= $i;
				$props['moduleID']		= $module->id;
				
				$optionField->setParams($props, OptionField::MODE_FORM);
				
//toPrint( $allparams->field_name[$i] ?? '','$nameinput '.$module->id, 0, ($module->id==175? 'message':''),true);
//toPrint( $allparams->field_name[$i] ?? '','$nameinput '.$module->id, 0, ($module->id==175? 'message':''),true);
				$optionsFields[$i] = $optionField;
//toPrint( $allparams,'$allparams '.$module->id,0,($module->id==175? 'message':''),true);
				$paramsField	= (string)$optionField->getJSON();

				if($paramsField)
					$wa->addInlineScript($paramsField,[],['type'=>$_type,'class'=>"modMultiForm $type json id$module->id"]);//'id'=>'modMultiForm'.$type.$module->id,
//echo  " \$paramsField ".print_r($paramsField,true)."<br>";

				
				$datasetParams += (array) $optionField->getDataset();
//echo  " \$datasetParams ".print_r($datasetParams,true)."<br>";
			}
			
		}
//		text/plain;charset=UTF-8, text/html, text/markdown, text/csv, image/svg+xml
}
//$ajaxDataFields		= modMultiFormHelper::ajaxDataField($allparams, $moduleid);
//$validateFieldsForm	= modMultiFormHelper::validateFieldsForm($allparams, $moduleid);

// Instantiate global document object

//$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_multi_form.txt';
//file_put_contents($fDeb, "\ =====_makgira.php 410 =====   \n"
//	.print_r($datasetParams,true). " \n\n", FILE_APPEND  );//FILE_APPEND
//toPrint();
//if($module->id == 175){ 
//toPrint($params,'$params'.$module->id,0,'message',true);
////toPrint($allparams,'$param->list_fields->field_type zzz'.$module->id,0,'message',true);
//}
//static $js;
//
//if(empty($js)){
//    $js = "jQuery(function(){mfOpenModalClick(); });";
//    JFactory::getDocument()->addScriptDeclaration($js);
//}
if(empty(modMultiFormHelper::isJ4()))
	JHtml::stylesheet(JUri::base().'/media/jui/css/icomoon.css'); 

//if($param->get( 'popup' )){ 
require JModuleHelper::getLayoutPath($module->module, '_form');
//}
//else{
////    $path = JModuleHelper::getLayoutPath($module->module, '_static');
////    echo $path;
//    require JModuleHelper::getLayoutPath($module->module, '_static');
//}
 
//require JModuleHelper::getLayoutPath($module->module, $param->get('layout', 'default'));



//JFactory::getDocument()->addScriptDeclaration($crutch);



 return;

$cacheparams = new \stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'Joomla\Module\MultiForm\Site\Helper\modMultiFormHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $param;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

$list = JModuleHelper::moduleCache($module, $param, $cacheparams);

require JModuleHelper::getLayoutPath('mod_multi_form','_form');