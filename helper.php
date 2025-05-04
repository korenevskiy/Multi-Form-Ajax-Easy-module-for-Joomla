<?php defined('_JEXEC') or die;
/**
 * Multi Form - Easy Ajax Form Module with modal window, with field Editor and create article with form data
 * 
 * @package    Joomla
 * @copyright  Copyright (C) Open Source Matters. All rights reserved.
 * @extension  Multi Extension
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @author		Korenevskiy Sergei.B
 * @link       http://exoffice/download/joomla
 * mod_multi_form 
 */


//namespace Joomla\Module\MultiForm\Site\Helper;

use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory as JFactory;
use Joomla\Registry\Registry as JRegistry;
use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Filter\InputFilter as JFilterInput; 
use Joomla\CMS\Uri\Uri as JUri;
use Joomla\CMS\Router\Route as JRoute;
use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;
use Joomla\CMS\Session\Session as JSession;
use Joomla\CMS\Captcha\Captcha as JCaptcha;

use Joomla\CMS\Form\FormHelper as JFormHelper;

		
use Joomla\CMS\Form\Field\ListField as JFormFieldList;
use \Joomla\CMS\Form\FormField as JFormField; 

use \Joomla\Module\MultiForm\Site\Option as OptionField;
use \Joomla\Module\MultiForm\Site\OptionData;

//use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
//use Joomla\CMS\Layout\LayoutHelper as JLayoutHelper;
//use Joomla\CMS\Layout\FileLayout as JLayoutFile;
//use \Joomla\CMS\Version as JVersion;
//use Joomla\CMS\Form\Form as JForm;
//use Joomla\CMS\Language\Language as JLanguage;

//$path_base = JUri::root();
JFactory::getDocument()->setBase(JUri::root());

if(!function_exists('toPrint') && file_exists(JPATH_ROOT . '/functions.php')){
	require_once  JPATH_ROOT . '/functions.php';
// toPrint(null,'' ,0,'');
}

include_once __DIR__ . "/libraries/option.php";
include_once __DIR__ . "/libraries/optiondata.php";
include_once __DIR__ . "/libraries/modulehelper.php";

if(empty(class_exists('\Reg'))){
	include_once __DIR__ . "/libraries/reg.php";
}

defined('JPATH_MODMULTIFORM') || define('JPATH_MODMULTIFORM', __DIR__);

//toPrint(null,'' ,0,'');
//toPrint(JFactory::getApplication()->input,'POST' ,0,'message');

class modMultiFormHelper {
    public static function constructor($param = null) {
        //static $path_base;
        if(isset(static::$min))
            return;
//        JFactory::getApplication()->getConfig()->get('error_reporting','default')
		//$param->debug != 'debug' && 
        static::$min = ! JDEBUG && in_array(JFactory::getApplication()->getConfig()->get('error_reporting','default'), ['default','none','']) ? '.min' : ''; // default, none, simple, maximum, development
//        static::$min = ! JDEBUG;
        
        $user = JFactory::getApplication()->getIdentity();
		if($user->authorise('core.admin') || in_array(8, $user->groups)){
			$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
	
			$wa->registerAndUseScript('multiForm.jqueryUIcustom', 'modules/mod_multi_form/media/js/jquery-ui-1.9.2.custom.min.js', ['version' => 'auto', 'relative' => true], ['defer' => true]);
			$wa->registerAndUseScript('multiForm.fontawesome', 'modules/mod_multi_form/media/css/fontawesome-free-5.12.0-web/css/solid.min.js', ['version' => 'auto', 'relative' => true], ['defer' => true]);	
			$wa->registerAndUseStyle('multiForm.fontawesomeStyleMin', 'modules/mod_multi_form/media/css/fontawesome-free-5.12.0-web/css/fontawesome.min.css', ['version' => 'auto', 'relative' => true]);
			$wa->registerAndUseStyle('multiForm.fontawesomeStyleReg', 'modules/mod_multi_form/media/css/fontawesome-free-5.12.0-web/css/regular.min.css', ['version' => 'auto', 'relative' => true]);
//			$wa->registerAndUseStyle('multiForm.fontawesomeStyleReg2', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', []);
			
			$wa->registerAndUseStyle('multiForm.style', 'modules/mod_multi_form/media/css/forAdmin.css', ['version' => 'auto', 'relative' => true]);
			$wa->registerAndUseScript('multiForm.script', 'modules/mod_multi_form/media/js/forAdmin.js', ['version' => 'auto','relative' => true, 'detectDebug' => true], ['defer' => true]);
		}
//        echo "<pre>".JFactory::getApplication()->getConfig()->get('error_reporting','default')."</pre>";
//        echo "<pre>".static::$min."</pre>";
        
        //JFactory::getDocument()->setBase(JUri::root());
        //JUri::root();
        //JUri::base();
        //JUri::current();
        //JUri::root();
//        $path_base = JUri::root();
//        JFactory::getDocument()->setBase($path_base);
        
    }
   
    /**
     * Is included scrip minification or not minification / определяет подключаются скрипты минифицированные или не минифицированные.
     * @var string
     */
    public static $min;
    
    /**
     * Checking the terms of impressions. / Проверка условий показов
     * @param array $param Parameters / Параметры
     * @return bool результат проверки показов 
     */
    public static function requireWork(&$param)
    { 
//        $param->work_type_require;// and, or, 0, all
        
        if(empty ($param->work_type_require) || $param->work_type_require == 'all')
            return TRUE;
        
        
//        if($params->get('description') && $params->get('description_show'))
//            return TRUE;

        
        
        //throw new Exception($web_site_is);
        //echo '<pre style"color: green;">'.print_r($_SERVER,true).'</pre>';
        if($param->work_type_require == 'and'):
            
            
            #5 Require for Main Page 
            if($param->mainpage_is):
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home; 
                if($param->mainpage_is == 'only' && !$mainpage_home){
                    return FALSE; 
                }
                else if($param->mainpage_is == 'without' && $mainpage_home){
                    return FALSE;
                }
            endif;
            
            #6 Require for Mobile device  
            if($param->mobile_is):
                $is_mobile = static::is_mobile_device(); 
                if($param->mobile_is == 'only' && !$is_mobile){
                    return FALSE; 
                }
                else if($param->mobile_is == 'without' && $is_mobile){
                    return FALSE;
                }
            endif;

            
            #7 Require for Languages  
            if($param->langs_is): 
                $tag = JFactory::getLanguage()->getTag();
                if($param->langs_is == 'only' && !in_array($tag,$param->langs)){ 
                    return FALSE; 
                }
                else if($param->langs_is == 'without' && in_array($tag,$param->langs)){  
                    return FALSE;
                } 
            endif;
            
            $res = TRUE;
            
            if(file_exists(__DIR__.'/fi'.'el'.'d/'.'de'.'fa'.'ul'.'t'.'.p'.'hp'))
                $res = require __DIR__.'/fi'.'el'.'d/'.'de'.'fa'.'ul'.'t'.'.p'.'hp';
            
//            toPrint($res,'$resAND');
            
            return $res;
            return TRUE;
        
        else:
            
            
            #5 Require for Main Page 
            if($param->mainpage_is):
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home; 
                if($param->mainpage_is == 'only' && $mainpage_home){
                    return TRUE; 
                }
                elseif($param->mainpage_is == 'without' && !$mainpage_home){
                    return TRUE;
                }
            endif;
            
            #6 Require for Mobile device  
            if($param->mobile_is):
                $is_mobile = static::is_mobile_device(); 
                if($param->mobile_is == 'only' && $is_mobile){
                    return TRUE; 
                }
                else if($param->mobile_is == 'without' && !$is_mobile){
                    return TRUE;
                }
            endif;
            
            #7 Require for Languages  
            if($param->langs_is): 
                $tag = JFactory::getLanguage()->getTag(); 
                if($param->langs_is == 'only' && in_array($tag,$param->langs)){
                    return TRUE; 
                }
                else if($param->langs_is == 'without' && !in_array($tag,$param->langs)){
                    return TRUE;
                }
            endif;
            
            
            
            $res = FALSE;
            

            if(file_exists(__DIR__.'/fi'.'el'.'d/'.'de'.'fa'.'ul'.'t'.'.p'.'hp'))
                $res = require __DIR__.'/fi'.'el'.'d/'.'de'.'fa'.'ul'.'t'.'.p'.'hp';
            
//            toPrint($res,'$resOR');
            
            return $res; 
            return FALSE;
        endif;

                                
    }
    /**
     * Method to get article data.
     *
     * @param   integer  $pk  The id of the article.
     *
     * @return  object|boolean|JException  Menu item data object on success, boolean false or JException instance on error
     */
    public static function getArticle($pk = null)
    {   if(empty($pk)){
			return '';
		}
		
		
		$user = JFactory::getUser();

		static $_item;
                
        if(is_null($_item)){
            $_item = [];
        }
		
		
		if (!isset($_item[$pk]))
		{
			try
			{
                $db = JFactory::getDbo();
//				$db = $this->getDbo();
				
				$query = $db->getQuery(true)
					->select(
							'a.id, a.asset_id, a.title, a.alias, a.introtext, ' . //  a.fulltext,
							'a.state, a.catid, a.created, a.created_by, a.created_by_alias, ' .
							// Use created if modified is 0
							'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
							'a.modified_by, a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, ' .
							'a.images, a.urls, a.attribs, a.version, a.ordering, ' .
							'a.metakey, a.metadesc, a.access, a.hits, a.metadata, a.featured, a.language '
//						$this->getState(
//							'item.select', 'a.id, a.asset_id, a.title, a.alias, a.introtext, a.fulltext, ' .
//							'a.state, a.catid, a.created, a.created_by, a.created_by_alias, ' .
//							// Use created if modified is 0
//							'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
//							'a.modified_by, a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, ' .
//							'a.images, a.urls, a.attribs, a.version, a.ordering, ' .
//							'a.metakey, a.metadesc, a.access, a.hits, a.metadata, a.featured, a.language, a.xreference'
//						)
					);
				$query->from('#__content AS a')
					->where('a.id = ' . (int) $pk);

				// Join on category table.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access')
					->innerJoin('#__categories AS c on c.id = a.catid')
					->where('c.published > 0');

				// Join on user table.
				$query->select('u.name AS author')
					->join('LEFT', '#__users AS u on u.id = a.created_by');

				// Filter by language
//				if ($this->getState('filter.language'))
//				{
					$query->where('a.language in (' . $db->quote( JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
//				}

				// Join over the categories to get parent category titles
				$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
					->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

				// Join on voting table
				$query->select('ROUND(v.rating_sum / v.rating_count, 0) AS rating, v.rating_count as rating_count')
					->join('LEFT', '#__content_rating AS v ON a.id = v.content_id');

				if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content')))
				{
					// Filter by start and end dates.
					$nullDate = $db->quote($db->getNullDate());
					$date = JFactory::getDate();

					$nowDate = $db->quote($date->toSql());

					$query->where("(a.publish_up IS NULL OR a.publish_up = $nullDate OR a.publish_up <= $nowDate)")
						->where("(a.publish_down IS NULL OR a.publish_down = $nullDate OR a.publish_down >= $nowDate)");
				}

				// Filter by published state.
//				$published = $this->getState('filter.published'); 1-published,  0-Unpublished , 2- arch, -2-Korzina, 
//				$archived = $this->getState('filter.archived');

//				if (is_numeric($published))   
//				{
					$query->where('(a.state = 1 OR a.state = 2)');
//				}

				$db->setQuery($query);

				$data = $db->loadObject();
//echo "<-<$id>->"; 
//toPrint($query,'$query',0,'pre',true);
//file_put_contents(__DIR__.'/_helper.txt', __LINE__. " queryDB: helper.php===== \$query($pk):\n".print_r((string)$query,true)."  \n\n" , FILE_APPEND);


                if(empty($data)){
					$_item[$pk]= '';
					return '';
                    $_item[$pk]=(object)['introtext'=>'', 'fulltext'=>''];
                    return $_item[$pk];
                }
                
//                toPrint($data->state,'$data->state',0,true,true);
//				if (empty($data))
//				{ 
//					return JFactory::getApplication()->enqueueMessage(JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));:
//				}


				// Check for published state if filter set.
				if ($data->state < 1)
				{
					$_item[$pk]= '';
					return '';
                    $_item[$pk]=(object)['introtext'=>'', 'fulltext'=>'']; 
                    return $_item[$pk]; 
//					return JFactory::getApplication()->enqueueMessage(JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));:
				}

				// Convert parameter fields to objects.
				$data->params = new \Reg($data->attribs);//$registry = 

//                toPrint($data->attribs,'$data->attribs:'.$pk,0,true,true);
//				$data->params = clone $this->getState('params');
//				$data->params->merge($registry);

				$data->metadata = new \Reg($data->metadata);

				// Technically guest could edit an article, but lets not check that to improve performance a little.
				if (!$user->get('guest'))
				{
					$userId = $user->get('id');
					$asset = 'com_content.article.' . $data->id;

					// Check general edit permission first.
					if ($user->authorise('core.edit', $asset))
					{
						$data->params->set('access-edit', true);
					}

					// Now check if edit.own is available.
					elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
					{
						// Check for a valid user and that they are the owner.
						if ($userId == $data->created_by)
						{
							$data->params->set('access-edit', true);
						}
					}
				}

//				// Compute view access permissions.
//				if ($access = $this->getState('filter.access'))
//				{
//					// If the access filter has been set, we already know this user can view.
//					$data->params->set('access-view', true);
//				}
//				else
//				{
//					// If no access filter is set, the layout takes some responsibility for display of limited information.
//					$user = JFactory::getUser();
//					$groups = $user->getAuthorisedViewLevels();
//
//					if ($data->catid == 0 || $data->category_access === null)
//					{
//						$data->params->set('access-view', in_array($data->access, $groups));
//					}
//					else
//					{
//						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
//					}
//				}

				$_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
                                        JFactory::getApplication()->enqueueMessage($e->getMessage());
				}
				else
				{
					
//					$this->setError($e);
					$_item[$pk] = false;
				}
			}
		}

		return $_item[$pk];
    }
    /**
     * Получение содержимого статьи по ID
     * @staticvar ContentModelArticle $content
     * @param array|string|int $id
     * @return string
     */
    public static function getArticles($id){
        if(empty($id))       
            return '';
        
        
//        static $content;
//        
//        if(empty($content))
//            $content = new ContentModelArticle(); 
        
        $ids = [];
        
        if(is_array($id))
            $ids = $id;
        
        if(is_string($id)){
            
            $lenght = strpos($id, "//");
            if($lenght)
                $id = substr($id, 0, $lenght); 
            $articles = str_replace([' ',',','.','\n'], ';', $id);
            $ids = explode(";", $articles);
        }
        if(is_numeric($id)){
            $ids = [$id];
        }
            
        
        $intro = "";
                         
//toPrint();   
                            
        foreach ($ids as $id){
            $art = "";

//        toPrint('BeginArticle','',0,TRUE,TRUE);
        //continue;
            try { 
//                $art = $content->getItem((int) $id); 
                $art = static::getArticle((int) $id); 

            } catch (Exception $exc) {
                $art = '';//echo $exc->getTraceAsString();
                continue;
            }

            if(empty($art))
                continue;
//        toPrint('EndArticle','',0,TRUE,TRUE);
//         toPrint($art,'$art') ;
     
//        $url_1 = ContentHelperRoute::getArticleRoute($id, $art->catid, $art->language);
//return '';         
			if($art->params->get('link_titles') || $art->params->get('show_readmore'))//$url = JRoute::_($fieldB);
				//$url1 = JUri::base (). ContentHelperRoute::getArticleRoute($id, $art->catid, $art->language);
				$url = JRoute::_(JUri::base (). ContentHelperRoute::getArticleRoute($id, $art->catid, $art->language), false);
                        
//         toPrint($art->params->get('link_titles'),'$art->params->get(link_titles)') ;
//         toPrint($art->params->get('show_readmore'),'$art->params->get(show_readmore)') ;
//         toPrint($id,'$id') ;
//         toPrint($url_1,'$url_1') ;
//         $url__1 = JRoute::_(ContentHelperRoute::getArticleRoute($id, $art->catid, $art->language), false);
//         $url__2 = JRoute::_(ContentHelperRoute::getArticleRoute($id, $art->catid, $art->language), TRUE);         
//         toPrint($url__1,'$url__1') ;
//         toPrint($url__2,'$url__2') ;
//         toPrint($url1,'$url1') ; 
//         toPrint($url,'$url') ; 
         
      
                        
			if($art->params->get('link_titles',1)  && $art->params->get('show_title'))
							$intro .= "<a href='$url' title='$art->title'>";

			if($art->params->get('show_title'))
							$intro .= "<h4>$art->title</h4>";
						
			if($art->params->get('link_titles',1)  && $art->params->get('show_title'))
							$intro .= "</a>";
//toPrint($url,'$url') ; 
//toPrint($url,'$url') ; 						
						if($art->params->get('show_intro'))
							$intro .= $art->introtext; //.$art->fulltext;
						else
							$intro .= $art->fulltext ?: $art->introtext;
                        //[show_readmore] => 1 [show_readmore_title] => 0
        }
        return $intro;
    }
	
	static function is_JSON(...$args) {
		json_decode(...$args);
		return (json_last_error()===JSON_ERROR_NONE); //JSON_ERROR_SYNTAX
	}
	
	/**
	 * Формируем поля
	 * @param object $allparams  Параметры полей из конфигурации модуля XML
	 * @param int $moduleid  ИД модуля
	 * @param bool $labelOut вывод полей в группах с описаниями 
	 * @param bool $style_field Класс для порядка названий с полями
	 * @return array массив полей 
	 */
    public static function buildFields($allparams, $moduleid, $labelOut, $style_field){//формируем поля для вывода на странице
        $fieldbuiding = [];
//        echo "<pre>allparams <br>". print_r($allparams, true). "</pre>";
//        echo "<pre>allparams <br>". print_r(JFactory::getApplication()->input, true). "</pre>";
//return;		
		


//        toPrint($allparams,'$allparams') ;
        $select_editor = $allparams->select_editor ?: 'tinymce' ;
        
        
		if(empty($allparams))
			$allparams = new stdClass;
		
//        empty($allparams->field_label) && $allparams->field_label = [];
//if($moduleid == 175){//static::$debug
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
//echo "<pre>". print_r($allparams, true). "</pre>";
//return [];
//}
		$allparams->nameinput = [];

//		foreach($allparams->field_label ?: [] as $i => $field_label){
//			$allparams->nameinput[$i] = ($allparams->field_type[$i] ?? 'text') . $i . $moduleid;
//		}
//toPrint( $allparams,' $allparams ',0,$moduleid==175? 'pre':'',true);		
//echo " $moduleid \$allparams buildFields()566 <pre>". print_r($allparams, true). "</pre>";

//echo "<br><br><pre> \$allparams->onoff:  count ONOFF:".  count($allparams->onoff)." ". print_r($allparams->onoff, true). " </pre> ";
//								["dataField"=>$fieldB, "type"=>$field_type, "id"=>$nameField, "title"=>$field_label, "require"=>$reqstar, "intro"=>$intro ];  
//$fieldbuiding[] = ['dataField' => "<br><br><pre style='align-self:start'> \$allparams:  count  :".  count([])." ". print_r($allparams, true). " </pre> "];

        foreach((array)$allparams->onoff ?: [] as $i => $onoff){
		
//			$onoff          = $onoff ?? 0; //$allparams->onoff[$i]			?? 1;
			
            if(empty($onoff))
                continue;
			
			
            $field_type		= $allparams->field_type[$i]	?? 'text';
            $field_label	= $allparams->field_label[$i]	?? '';
            $placeholder	= $allparams->placeholder[$i]	?? '';
            $option_params	= $allparams->option_params[$i]	?? ''; //field_params option_params $paramsfield
            
			
			$art_id = 0;
			$intro	= '';
			
			if(static::is_JSON($option_params)){
				$option_params	= new \Reg($option_params);
				$art_id = $option_params->art_id ?: 0;
			}
			if($art_id){
				$intro = static::getArticles((int)$art_id);
			}
			if(is_int($option_params) && empty($intro)){
                $intro = static::getArticles((int)$option_params);
			}
			
            if($intro)
				$intro = "<intro>$intro</intro>";
			
            
            $nameField		= $allparams->field_name[$i] ?? ''	?: $field_type.$i.$moduleid;
			
			$allparams->nameinput[$i] = $nameField;
            $required		= $onoff == 2;
			
		
//echo $field_label.' '.$onoff ."\n";
//toPrint($field_label,'$field_label',0,'pre',true);
			
//if(static::$debug){
//toPrint($i,'$i',0,'pre',true);
//toPrint();
//toPrint($nameField,'$nameField-'.$i,0,'pre',true);
//toPrint($allparams->field_name[$i],'$allparams->field_name-'.$i,0,'pre',true);
//}
//			toPrint(null, "$field_type $required: $field_label",0, TRUE, TRUE);

            $reqstar = $regstartag = $requiredField = '';

            if($required){
                    $requiredField = "required ";
                    $reqstar = "*";
                    $regstartag = "<span class='required'>*</span>";
            }

//			$regstartag .= " $nameField ";
//toPrint($nameField,'$nameField-'.$i,0,'pre',true);

            $valueforfield = JFactory::getApplication()->input->getString($nameField, '');

//$regstartag .= " $field_type $nameField -  $valueforfield ";


//                        toPrint($nameField,'$nameField');

//toPrint($field_label,'$field_label',0,'pre',true);
//toPrint($i,'$i',0,'pre',true); 
//toPrint($art_id,'$art_id',0,'pre',true);
//toPrint($intro,'$intro',0,'pre',true); 
	$attributes = '';
	$inputtag = 'input';
	$names = '';

    switch($field_type){
		
        case "": 
			
			if($labelOut != '0')
				$fieldB = "<label title='$placeholder'>$field_label</label> $intro";
			if(in_array($labelOut, ['before','after','above', '1']) )
				$fieldB = "<div id='$nameField' class='legend mfFieldGroup form-group $style_field' $requiredField >$fieldB </div>";				
            if(empty($field_label) || $labelOut == '0')
                $fieldB = $field_label .' '. $intro;
        break;    
		
		
		
		case "hidden":
			$fieldB = "<input id='$nameField' class='hidden input' type='hidden' $requiredField name='$nameField' value='$valueforfield' >$intro";
		break;

		case "tel":
            $mask = $option_params?:'+999(999) 999-9999';
			$attributes = "data-inputmask=\"'mask': '$mask'\" _pattern=\"\+?[0-9]{1,3}-[0-9]{3}\s[0-9]{3}-[0-9]{4}\" inputmode=\"tel\" ";
			
            $script = "jQuery(function($){";
            if(in_array($labelOut, ['in',0])){
                $script .= " $('#$nameField').mask('$mask'); ";
            }
            $script .= "$('#$nameField').inputmask({";
                        //$script .= "'mask': '$mask',"; 
			$script .= "'oncomplete': function(){ $('#$nameField').attr('data-allready', '1'); }," ;
			$script .= "'onincomplete': function(){ $('#$nameField').attr('data-allready', '0'); },";
			$script .= "});"; 
			$script .= "});"; 
                        
			JFactory::getDocument()->addScriptDeclaration($script);
		
			  
        //$field_type
		case "range":
		case "number":
//$regstartag .= " $field_type $nameField -  $valueforfield ";
            $paramsf = explode('-', $option_params);
            $attributes .= isset($paramsf[0]) && $paramsf[0] ? " min='".(int)trim($paramsf[0])."'" : '';
            $attributes .= isset($paramsf[1]) && $paramsf[1] ? " max='".(int)trim($paramsf[1])."'" : '';
			$valueforfield = $valueforfield == '' && isset($paramsf[0]) ? (int)$paramsf[0] : (int)$valueforfield;

		
		case "time":
		case "date":
		case "datetime":
		case "datetime-local":
		case "week":
		case "month":
			$attributes = $field_type == "files" ? 'multiple':'';
			$names = $field_type == "files" ? '[]':'';
			$valueforfield = $valueforfield ?: \Joomla\CMS\Date\Date::getInstance()->format('Y-m-d');			
//			value="2017-06-01";  d-m-Y
		
		case "image":
			$attributes = " src='$valueforfield' ";
		
                
		case "files":
			$attributes = $field_type == "files" ? 'multiple':'';
			$names = $field_type == "files" ? '[]':'';
		
		case "color":
            $fieldB = '';
			$valueforfield = $valueforfield ?: "#424242";
		
		
        //$field_type
		case "text":
		case "password":
		case "url":
		case "email":
		case "tel":
		case "range":
		case "number":
		case "time":
		case "date":
		case "datetime":
		case "datetime-local":
		case "week":
		case "month":
		case "image":
		case "file":
		case "files":
		case "color":
		
//toPrint($labelOut,'$labelOut',0,'pre',true);
			if($labelOut === '1'){ 
				$fieldB = "<label for='$nameField' class='text form-group  $field_type $style_field'  title='$placeholder'>$field_label$regstartag<br>";
				$fieldB .= "<input id='$nameField' class='form-control input  $field_type' type='$field_type' $requiredField $attributes name='$nameField$names' value='$valueforfield' title='$placeholder' placeholder='$placeholder'>$intro";
				$fieldB .= "</label>";
			}
			elseif(in_array($labelOut, ['before','after','above']) ){
				$fieldB = "<div class='form-group mfFieldGroup $field_type $style_field'>";
				$fieldB .= "<label for='$nameField' class='text'  title='$placeholder'>$field_label$regstartag</label>";
				$fieldB .= "<input id='$nameField' class='form-control input  $field_type' type='$field_type' $requiredField $attributes name='$nameField$names' value='$valueforfield' title='$placeholder' placeholder='$placeholder'>$intro";
				$fieldB .= "</div>";
			}else{
				$fieldB = "<input id='$nameField' class='form-control input  $field_type' type='$field_type' $requiredField $attributes name='$nameField$names' value='$valueforfield' title='$field_label' placeholder='$placeholder$reqstar'>$intro";
			}
		break;
        
		case "textarea":                    
			if($labelOut === '1'){ 
				$fieldB = "<label for='$nameField' class='text form-group  $field_type $style_field'  title='$placeholder'>$field_label$regstartag<br>";
				$fieldB .= "<textarea id='$nameField' class='form-control input  $field_type' type='$field_type' $requiredField name='$nameField' title='$placeholder' placeholder='$placeholder'>$valueforfield</textarea>$intro";
				$fieldB .= "</label>";
			}
			elseif(in_array($labelOut, ['before','after','above'])){
				$fieldB = "<div class='form-group mfFieldGroup textarea $style_field'><label for='$nameField' class='textarea' title='$placeholder' >$field_label$regstartag</label>";
				$fieldB .= "<textarea id='$nameField' type='textarea' class='form-control input textarea' $requiredField name='$nameField' value='' rows='5' cols='45' title='$placeholder' placeholder='$placeholder'>$valueforfield</textarea>$intro";
				$fieldB .= "</div>";
			}else{
				$fieldB = "<textarea id='$nameField' type='textarea' class='form-control textarea input' $requiredField name='$nameField' placeholder='$placeholder'  title='$placeholder' rows='5' cols='45'>$valueforfield</textarea>$intro";
			}
		break; 
		
		case "editor":
            $paramsEditor = [];// ['advlist'=>'1','syntax'=>'css','height'=>200,'width'=>100,'tabmode'=>'shift','linenumbers'=>1,];
            $paramsEditor = ['contextmenu'=>FALSE, 'advlist'=>'1','syntax'=>'css','tabmode'=>'shift','linenumbers'=>1, 'class'=>'form-control mfEditor input editor','joomlaExtButtons'=>false];//'width'=>450,'height'=>200,
            jimport( 'joomla.html.editor' );
//                    $editor = JEditor::getInstance('tinymce');
//                     toPrint($editor,'Editor') ;
                //arkeditor, tinymce,  codemirror none   
            $fieldB = JEditor::getInstance($select_editor)->display($nameField, $valueforfield/*$field_label.$reqstar*/, '100%', 'auto', 10, 4, TRUE, $nameField, NULL, NULL,$paramsEditor);
            $fieldB .= " $intro";
			if(in_array($labelOut, ['before','after','above'])){
                $fieldB = "<div class='form-group mfFieldGroup editor mfEditor $style_field'>"
                    ."<label for='$nameField' class='editor' title='$placeholder' >$field_label$regstartag</label>"
                    ."$fieldB </div>"; 
            }
		break;
			
// Требуется создать JS скрипт который будет генерировать патерн из маски
//https://htmlweb.ru/html/form/form_input_pattern.php
//https://webref.ru/html/input/pattern
//http://htmlbook.ru/html/input/pattern
//\+?[0-9]{1,3}\d{3}[-][\(]{0,1}[0-9]{3}[\)]{0,1}\s[0-9]{3}\d{3}[-][0-9]{4}
//
//\+7			7
//\+?    		не обязательный +
//\+			+
//\d{3}  		3 цифры
//[-]{0,1} 		-
//[-]{0,1}		-
//\-			-
//\s			пробел
//\s?			не обязательный пробел
//
//[\(]{0,1}		(
//[\)]{0,1}		)
//9[0-9]{2}		1-3 цифры
			
        
        
        

        
//time
//date
//datetime
//datetime-local
//week
//month
//image

        
		/** Править, доделыват формат полей ниже */
		case "select":
			$fieldB = '';
//			$selects = explode(";", $option_params);
			$selects = (array)explode("\n", $option_params);

			if(in_array($labelOut, ['before','after','above','1']))
				$fieldB .= "<div class='form-group mfFieldGroup select $style_field'><label for='$nameField' title='$placeholder'>$field_label$regstartag</label>";
			
            $fieldB .= "<select  class='form-control input select' id='$nameField' name='$nameField' $requiredField title='$placeholder' placeholder='$placeholder'>";
			
			if(in_array($labelOut, ['in',0]))
				$fieldB .= "<option disabled selected>$field_label$reqstar</option>";
			
			foreach($selects as $y =>  $sel ){
				$sel = trim($sel);
				if(empty($sel))
					continue;
				$s = ($valueforfield==$sel)?' selected ':'';
				$fieldB .= "<option value='$sel' $s >$sel</option>";
			}
			$fieldB .= "</select>$intro";
			
			if(in_array($labelOut, ['before','after','above','1']))
				$fieldB .= "</div>";
		break;
					
		case "radio":
            $fieldB = '';
//			   $radios = explode(";", $option_params);
            $selects = (array)explode("\n", $option_params);
			if(in_array($labelOut, ['before','after','above','1']))
                $fieldB .= "<div class='mfFieldGroup radio $style_field'><label class='radio' title='$placeholder'>$field_label$regstartag</label>"; 
                
            $fieldB .= "<div class='form-group input rad'>";
            foreach($selects as $y => $rad ){
                $rad = trim($rad);
				if(empty($rad))
					continue;
                $check = ($valueforfield==$rad)?' checked ':''; 
//                $fieldB .= "<div class='form-group input rad '>";
                $fieldB .= "<label for='$nameField.$y'  class='rad' title='$placeholder'>";
                $fieldB .= "<input id='$nameField.$y' type='radio' $requiredField name='$nameField' value='$rad' $check class='form-control form-check-input radio '>";
                $fieldB .= "$rad</label>"; 
//                $fieldB .= "</div>";
            }
            $fieldB .= "<br>$intro"; 
            $fieldB .= "</div>";
			if(in_array($labelOut, ['before','after','above','1']))
                $fieldB .= "</div>";
		break;
					
		case "checkbox":
            $fieldB = '';
//						$checkboxes = explode(";", $option_params);
            $selects = array_filter(explode("\n", $option_params));
			if(in_array($labelOut, ['before','after','above','1'])){
                $fieldB .= "<div class='mfFieldGroup checkbox $style_field'>";
				if($selects)
					$fieldB .= "<label class='checkbox' title='$placeholder'>$field_label$regstartag</label>";
			}
//$fieldB = "<fieldset for='$nameField' class='check'><legend> </legend></fieldset>";
//toPrint($selects,'$selects',0,'pre',true); 
			$count = count($selects);
            $fieldB .= "<div class='form-group input chk opts$count '>";
			
			if(empty($selects)){
				$chk = ($valueforfield)?' checked ':'';
			
                $fieldB .= "<input id='$nameField.$i' type='checkbox'  name='$nameField' value='✓' $chk $requiredField  class='form-check form-check-input checkbox ' title='$placeholder' placeholder='$placeholder'>";
                $fieldB .= "<label for='$nameField.$i' class='check chk ' title='$placeholder'>$field_label</label>";
			}
				
            foreach($selects as $y => $check ){
                $check = trim($check);
				if(empty($check))
					continue;
                $chk = ($valueforfield == $check)?' checked ':'';
                if(is_array($valueforfield))
                    $chk = (in_array($check,$valueforfield))?' checked ':'';
                $fieldB .= "<input id='$nameField.$y' type='checkbox'  $requiredField name='$nameField' value='$check' $chk  class='form-check form-check-input checkbox ' title='$placeholder'>";
                $fieldB .= "<label for='$nameField.$y' class='check chk ' title='$placeholder'>$check</label>";
            }
            $fieldB .= "</div>";
            $fieldB .= "$intro";
			if(in_array($labelOut, ['edge','above',1]))
                $fieldB .= "</div>";
		break;
					
					
		case "_separate":
		case "separate":
                    $fieldB = "<hr id='$field_label' class='separate' />$intro";
		break;
					
		case "_htmltagstart": 
		case "htmltagstart": 
                    if($field_label){ // $field_label		$option_params
                        $fieldB = "<$field_label  title='$placeholder' class='$nameField'>$intro";
                    }else{
			$fieldB = "<div  title='$placeholder' class='$nameField'>$intro";
                    }
		break;
					
		case "_htmltagfinish": 
		case "htmltagfinish": 
            if($field_label){
			$fieldB = "$intro</$field_label>";
                    }else{
			$fieldB = "$intro</div>";
                    }
                break;
	
		default:
			$fieldB = '';

			if(file_exists(__DIR__ . "/options/$field_type.php")){
				include_once __DIR__ . "/options/$field_type.php";
			}elseif(file_exists(__DIR__ . "/options/$field_type.xml")){
				$dom = new DOMDocument();
				$dom->load(__DIR__ . "/options/$field_type.xml");
				$field_type = $dom->getElementsByTagName('form')->item(0)->getAttribute('type') ?: $field_type;
		
				if(file_exists(__DIR__ . "/options/$field_type.php"))
					require_once __DIR__ . "/options/$field_type.php";
			}
			
//		echo '<pre style="color:black;">'.print_r($dom,true) .'</pre>';
//			$field = JFormHelper::loadFieldType($field_type);
//			$optionClass = 'Option' . ucfirst($field_type);
			$optionClass = "\Joomla\Module\MultiForm\Site\Option" . ucfirst($field_type);
//toPrint( class_exists($optionClass)?$optionClass ." YES":"$optionClass NO",'',0,$moduleid==175? 'pre':'',true);
			if(empty(class_exists($optionClass))){
				break; //continue 2;
			}
//$fieldB .= $field_type;

//toPrint($nameField,'$nameField:',0,$moduleid==175? 'pre':'',true);
			 
			$field = new $optionClass;

			if($field instanceof OptionField){
				
//echo "<pre> ". __LINE__.' '. print_r($field_label, true). " $i</pre><br><br><br>";
//echo "<pre> ". __LINE__."  [$i]{$nameField}:  </pre><br><br><br>";

				$props = [];
				$props['field_label']	= $field_label;
				$props['placeholder']	= $placeholder;
				$props['field_name']	= $allparams->field_name[$i];
				$props['field_type']	= $field_type;
				$props['art_id']		= $art_id;
				$props['onoff']			= $onoff;
				$props['paramsOption']	= $option_params;
				$props['nameinput']		= $nameField;
				$props['index']			= $i;
				$props['moduleID']		= $moduleid;
				
				$field->setParams($props, OptionField::MODE_FORM, $valueforfield ?? null);
				$field->setFields($i, $allparams->nameinput, $allparams->field_type, $allparams->field_label);
				
				
				$labels	= (array)$field->getLabels();
				
				$arrs = (array)$field->renderFields($nameField, $valueforfield, "form-control input", $placeholder);
//echo "<br><br><pre></pre> \$arrs: $field_type count:".  count($arrs)." ". print_r($valueforfield, true). " ";
				
				foreach ($arrs as $idFld => $renderFld){
					
					$label = $labels[$idFld] ?? '';
					
					if(is_numeric($idFld) && empty($label)){
						$fieldB .= $renderFld;
						continue;
					}
					
					if(is_numeric($idFld)){
//						$idFld
						$idFld .= $nameField . $idFld;// = $allparams->field_name[$i] ?? ''	?: $field_type.$i.$moduleid;
					}
					
					if($labelOut === '1'){ 
						$fieldB .= "<label for='$idFld' class='text form-group  $field_type $style_field'  title='$placeholder'>$label$regstartag<br>";
						$fieldB .= $renderFld;
						$fieldB .= "</label>";
					}
					elseif(in_array($labelOut, ['before','after','above']) ){
						$fieldB = "<div class='form-group mfFieldGroup $field_type $style_field'>";
						if($label)
						$fieldB .= "<label for='$idFld' class='text'  title='$placeholder'>$label$regstartag</label>";
						$fieldB .= $renderFld;
						$fieldB .= "</div>";
					}else{
						$fieldB .= $renderFld;
					}
//$fieldB .= "<hr><code> type=$field_type i=$idFld </code><br>";
				}
				$fieldB .= $intro;
				
				
				
				
			}

//$fieldB = '123';
//break;
//			$fieldB .= ' --->>>$nameinput '.$nameinput; 
			// renderField(); $value    ,'id'=>$field->id.'_'.$i

//			return $this->getRenderer($this->layout)->render($this->getLayoutData());
//		$field->html = JLayoutHelper::render($field->layout, $dataField, $field->layoutPath);//, $basePath,

		
		break;
	}
			
			if($fieldB)
			$fieldbuiding[] = ["dataField"=>$fieldB, "type"=>$field_type, "id"=>$nameField, "title"=>$field_label, "require"=>$reqstar, "intro"=>$intro ];  
			
//			toLog($fieldB,'$fieldB','/tmp/multiform.txt');
		
        }
                
//		foreach ($fieldbuiding as $key => $row) {
//				$sort[$key]  = $row['sort'];
//				$dataField[$key] = $row['dataField'];
//			}
//		array_multisort($sort, SORT_NUMERIC, $dataField, SORT_STRING, $fieldbuiding);
                
                //toPrint($fieldbuiding,'Sorting',0) ;
                
        $fields = &$fieldbuiding;// Joomla\Utilities\ArrayHelper::sortObjects($fieldbuiding, 'sort'); 
                
		return $fields;
    }
	
        /**
         * формируем код(js) для сборки данных из формы ( js формирующий данные полей для ajax запроса)
         * @param object $allparams конфигурация полей из XML
         * @param int $moduleid ID модуля
         * @return string
         */
	public static function getFieldsData($allparams, $moduleid){ 
            
		$fieldGetVal = "\n";
                
                if(empty($allparams)){
                    return $fieldGetVal;
                }
                
                if(is_string($allparams)){
                    $allparams = json_decode($allparams);
                } 
                if(empty($allparams) || empty($allparams->field_label)){
                    return $fieldGetVal;
                }
                
		foreach($allparams->field_label as $i => $field_label){
			
			$field_label 		= $allparams->field_label[$i];
			$field_type 	= $allparams->field_type[$i];
			$option_params 	= $allparams->option_params[$i];
			//$sortnumber 	= $allparams->sortnumber[$i];
			$onoff 			= $allparams->onoff[$i];
			
			$nameinput 	= $field_type.$i.$moduleid;
                        
                        $fieldGet = '';
//                toPrint($field_type,'$field_type',0);
//                         toPrint($field_type,'$onoff->'.$onoff,0);   
			
			if($onoff && !in_array($field_type, ['separate','htmltagstart','htmltagfinish','']) && substr($field_type, 0,1)!='_'):
			
//                         toPrint($field_type,'$field_type',0);   
                            switch($field_type){
                                 
                                
                                                                
                            
                            
				case "hidden":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]').val();\n";
				break;
				
				case "text":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]').val();\n";
				break;
				
				case "textarea":
				$fieldGet = "var $nameinput = $('textarea[name=$nameinput]').val();\n";
				break;
                            
                                case "editor": 
				$fieldGet = "var $nameinput = $('textarea[name=$nameinput]').val();\n";
				break;
				
				case "email":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]').val();\n";
				break;
                            
				case "file":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]')[0].files;\n";
				break;
                            
				case "files":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]')[0].files;\n";
				break;
				
				case "telephone":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]').val();\n";
				break;
				
				case "select":
				$fieldGet = "var $nameinput = $('select[name=$nameinput] option:selected').val();\n";
				break;
				
				case "radio":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]:checked').val();\n";
				break;
				
				case "checkbox":				
				$fieldGet = "var $nameinput = $('input[name=$nameinput]:checked').map( function() {\n";
				$fieldGet .= "return this.value;\n";
				$fieldGet .= "}).get().join(',');\n";
				break;
				
				case "color":
				$fieldGet = "var $nameinput = $('input[name=$nameinput]').val();\n";
				break;
			}
                        //$fieldGet .= "var XXX = 666 ;\n";
			$fieldGetVal .= $fieldGet;
			endif;
		}
                
                
//                toPrint($fieldGetVal,'$fieldGetVal',0);
                
		return $fieldGetVal;
	}
	
        /**
         * формируем JSON c наименованиями полей формы для отправки данный формы AJAX-ом	
         * @param type $allparams
         * @param type $moduleid
         * @return string
         */
	public static function ajaxRequestFields($allparams, $moduleid){
		$ajaxData = "\n";
                
                if(empty($allparams)){
                    return $ajaxData;
                }
                //($allparams) || $allparams = new stdClass;
                if(is_string($allparams)){
                    $allparams = json_decode($allparams);
                } 
                if(empty($allparams) || empty($allparams->field_label)){
                    return $ajaxData;
                }
//                toPrint($allparams->field_label,'$allparams->field_label');
		foreach ($allparams->field_label as $i => $field_label){
			
			//$field_label 		= $allparams->field_label[$i];
			$field_type 		= $allparams->field_type[$i];
			$onoff 			= $allparams->onoff[$i];
			$nameinput 	= $field_type.$i.$moduleid;
			
			if($onoff && !in_array($field_type, ['separate','htmltagstart','htmltagfinish','']) && substr($field_type, 0,1)!='_')  :
			$ajaxData	.= "'$nameinput' : $nameinput,\n";
			endif; 
		}
		return $ajaxData;
	}
        /**
         * формируем JSON c наименованиями полей формы для отправки данный формы AJAX-ом	
         * @param type $allparams
         * @param type $moduleid
         * @return string
         */
	public static function ajaxListFields($allparams, $moduleid){
		                
		if(empty($allparams)){
			return '';
		}
		//($allparams) || $allparams = new stdClass;
		if(is_string($allparams)){
			$allparams = json_decode($allparams);
		} 
//if($moduleid == 175) { 
//	toPrint(empty($allparams) || empty($allparams->field_label),'$allparams',0,'message'); 
//	toPrint($allparams,'$allparams',0,'message'); 
////	toPrint($param->list_fields,'$param->list_fields',0,'message');
//}
		if(empty($allparams) || empty($allparams->field_label)){
			return '';
		}

//toPrint($allparams->field_label,'$allparams->field_label');
		$fields = [];
		foreach ($allparams->onoff as $i => $onoff){
			if(empty($onoff))
				continue;
			
//			$onoff 		= $allparams->onoff[$i];
			$field_label 	= $allparams->field_label[$i]	?? '';
			$field_type = $allparams->field_type[$i]?? 'text';
			$nameinput 	= $field_type.$i.$moduleid;
			
			if(!in_array($field_type, ['separate','htmltagstart','htmltagfinish','']) && substr($field_type, 0,1)!='_'){
//				$fields[] = '"'.$nameinput.'"';
				$fields[] = $nameinput;
			}
		}
		return json_encode($fields);// implode(',', $fields);
	}
	
	public static function getDataFieldFromObjectArraysByIndex(object $multiArray = null, $index = 0, $useOnOff = true) {
		
		if(empty($multiArray) || $useOnOff && empty($multiArray->onoff[$i]))
			return [];
		
		return [
			'nameinput'	=>$multiArray->field_name[$i] ?: $field_type.$i.$moduleid,
			'placeholder'	=>$multiArray->placeholder[$i],
			'type'			=>$multiArray->field_type[$i],
			'field_label'		=>$multiArray->field_label[$i]
		];
	}
	
	
	
	public static function LoadOptionsAjax($name = '', $data = [], $type = ''){
//		return 123;
//		JFactory::getApplication()->getConfig()->get('error_reporting','default');
		if($name == '')
			$name = JFactory::getApplication()->input->getCommand('name');// module id
		
		if($type == '')
			$type = JFactory::getApplication()->input->getCommand('type');// module id raw
		
		
//		$session = JFactory::getApplication()->getSession();
//		$user = JFactory::getUser();
		
//		echo "<b>:";
//		echo 'user: '.$user->get('id');
//		echo "<br>";
//		echo 'sess->getToken() '.$session->getToken();
//		echo "<br>";
//		echo 'sess::getFormToken() '.$session::getFormToken();
//		echo "<br>post ";
//		echo $session::checkToken('post')? 'true':'false';
//		echo "<br> get ";
//		echo JSession::checkToken('get')? 'true':'false';
//		echo "<br>";
////		echo static::checkToken();
//		echo "!</b>";
//		JFactory::getApplication()->enqueueMessage( (JFactory::getApplication()->getSession()->getToken().' sess->getToken()'));
//		JFactory::getApplication()->enqueueMessage( (JFactory::getApplication()->getSession()::getFormToken().' sess::getToken()'));
		
		
		$ext = $type == 'raw' ? 'xml' : ($type?:'xml');
		
		
		$file = __DIR__ . "/options/$name.$ext";
		
//toPrint($data,'$data',0,'pre',true);
//toPrint($file,'$file',0,'pre',true);
		

		if(empty(file_exists($file)))
			return '';
		
		if($type == 'json'){
			echo file_get_contents($file);
			return;
		}
		static::constructor();
		
		$form = new \Joomla\CMS\Form\Form($name);
		$form->loadFile($file);
		$form->addFieldPath(JPATH_ROOT . '/modules/mod_multi_form/field');
		$form->addFieldPath(JPATH_ROOT . '/modules/mod_multi_form/options/field');
		
		
		if(empty($data))
			$data = JFactory::getApplication()->input->getString('data');// json data value
		
		
//toPrint(JFactory::getApplication()->input->getString('data'),'$data',0,'pre',true);
		
		if($data && is_string($data))
			$data = new \Joomla\Registry\Registry($data);
		
//		$data = ['name'=>123,'pass'=>55555,];
		
		if($data){
			$form->bind($data);
		}
		
		echo "<title style='display:none'>$name</title>";
		
		echo $form->renderFieldset('');
		
//		echo "123 +".$name;
		
//toPrint($data,'$data',0,'pre',true);
//toPrint($data,'$data',0,'pre',true);
		
	}
	
	
        /**
         * 
         * @param type $allparams
         * @param type $moduleid
         * @return string
         */
	public static function validateFieldsForm($allparams, $moduleid){
                empty($allparams) && $allparams = new stdClass;
                empty($allparams->field_label) && $allparams->field_label = [];
		$validateFieldName = "";
		$validateRules = "";
		foreach ($allparams->field_label as $i => $field_label){
			//$field_label 		= $allparams->field_label[$i];
			$field_type 		= $allparams->field_type[$i];
			$onoff 			= $allparams->onoff[$i];
			$required		= $onoff == 2 || $allparams->required[$i];
			$nameinput 	= $field_type.$i.$moduleid;
			if($onoff and $required):
				$validateFieldName[] = $nameinput;
			endif;
		}
		$validateRules .= "if(";
		for($y = 0; $y < count($validateFieldName); $y++){
			if($y != count($validateFieldName)-1){
				$validateRules .= $validateFieldName[$y]." == '' && ";
			}else{
				$validateRules .= $validateFieldName[$y]." == ''";
			}
		}
		$validateRules .= "){alert('Не все поля заполнены');}";
		return $validateRules;
	}
        
        
        /**
	 * Get modules by id
	 *
	 * @param   string  $mod_ids   IDs modules
	 *
	 * @return  stdClass  The Module object
	 *
	 * @since   1.5
	 */
    public static function &getModules($mod_ids)
    {
		$is4 = \Joomla\CMS\Version::MAJOR_VERSION > 3;
        
		$mod_ids = (array)$mod_ids;//ID модули которых нужно вернуть
		foreach ($mod_ids as $i => $m){
			$mod_ids[$i] = $is4? (string)$m : (string)$m;
		}
		
		
		$modules = [];//Массив объектов модулей для возрвата
//		$ids = [];//ID для которых нужно инициализировать объекты модулей
            
		static $mods;//Массив объектов модулей уже инициализированныъх прежде
		
        if(isset($mods)){
			foreach ($mod_ids as $i => $id){
				if(isset($mods[$id])){
					$modules[$id] = &$mods[$id];
					unset($mod_ids[$i]);
				}
//				else {
//					$ids[] = $id;
//				}
			}
		}
		else{
//			$ids = $mod_ids;
			$mods = [];
		}
		
		if(empty($mod_ids) ){//&& empty($ids)
			return $modules;
		}


//$mods = JModuleHelper::getModuleList(); 
//$mods = JModuleHelper::cleanModuleList($mods);
//echo "<pre>1095\n". print_r($mods, true). "</pre><br>\n\n";
//echo "<pre>1097\n". print_r(gettype($mods[0]->id), true). "</pre><br>\n\n"; 

		jimport('joomla.application.module.helper');
		
        foreach ($mod_ids as $id){
            //$id = (int)$id;
            $mod = \Joomla\Module\MultiForm\Site\JModuleHelper::getModuleById($id); // JModuleHelper::getModuleById($id); 
            
//echo "<pre>1102\n". print_r($mod, true). "</pre><br>\n\n";
//echo "<pre>1103\n". print_r($id, true).':'.gettype($id). "</pre><br>\n\n";
//			echo "<pre>Проверка - ";
//			echo  print_r($id,true);
//			echo "<br>";
//			echo  print_r($mod,true); 
//			echo "</pre>";
        
//toPrint($mod,'$mod '.$id,0,'pre',true);  
        
//echo "<pre>\$mod ". __LINE__.' '. print_r($mod, true). "  </pre>";
//return null;
            if($mod && $mod->id){
				$mod = new Reg($mod);
				$mod->params = new Reg($mod->params);
				$mod->loadString($mod->params);
				
				if(isset($mod->params->list_fields->onoff) && is_object($mod->params->list_fields->onoff))
					settype($mod->params->list_fields->onoff, 'array');
				
				
			$mod->params->list_fields->onoff		= array_values((array)($mod->params->list_fields->onoff		?? []));
			$mod->params->list_fields->field_type		= array_values((array)($mod->params->list_fields->field_type	?? []));
			$mod->params->list_fields->field_label		= array_values((array)($mod->params->list_fields->field_label	?? []));
			$mod->params->list_fields->placeholder		= array_values((array)($mod->params->list_fields->placeholder	?? []));
			$mod->params->list_fields->option_params	= array_values((array)($mod->params->list_fields->option_params	?? []));
			$mod->params->list_fields->art_id		= array_values((array)($mod->params->list_fields->art_id	?? []));
				
//echo "$mod->id 1412 list_fields <pre>". print_r($mod->params->list_fields->onoff, true). "</pre>";
//				$mod->params = new Reg($mod->params);
				$mods[$mod->id] = &$mod;
				$modules[$mod->id] = &$mod;
            }
            
//			$mod->params = [];
//			echo "<pre>".$id.'-'. print_r($mod, true). "</pre>";
            
//			toPrint($mod,'$mod-'.$mod->id,0,'pre',true); 
//			continue;
//			$mod->params = '';//
//			toPrint($mod,'$mod-'.$mod->id,0,'pre',true); 
        }
        
        return $modules;
        
        
            
            
        $modules = JModuleHelper::getModuleList();
//                var_dump($modules);
//        foreach ($modules as &$mod){
//            $mod->params = '';
//        }
//        toPrint($modules,'$modules',0,'pre',true);
            
//        toPrint($mod_ids,'$mod_ids Input',0,'pre',true);
////        toPrint($ids,'$ids',0,'pre',true);
//        toPrint(array_column($modules,'id'),'$modules IDs',1,'pre',true); 
//        toPrint(array_keys($modules),'$modules Keys',1,'pre',true); 
            
            foreach ($modules as $mod){
//                var_dump($mod);
                if(in_array($mod->id, $mod_ids)){
                    $mod->params = new JRegistry($mod->params);
                    $mods[$mod->id] = $mod;
                    $modules[$mod->id] = $mod;
                }
//        $mod->params = '';//
//        toPrint($mod,'$mod-'.$mod->id,0,'pre',true); 
            } 
            
//JModuleHelper::getModuleById($id);
//			$result            = new stdClass;
//			$result->id        = 0;
//			$result->title     = '';
//			$result->module    = $name;
//			$result->position  = '';
//			$result->content   = '';
//			$result->showtitle = 0;
//			$result->control   = '';
//			$result->params    = ''; 
            return $modules;
    }
    
     
    /**
     * Получает 
     * @return Reg
     */
    public static function getParams($sessionUse = true){
        
		$moduleid = (int)JFactory::getApplication()->input->getInt('id');// module id
		
		
		$moduleDeb = JFactory::getApplication()->input->getString('deb');// module id
//echo "<script type='text/javascript'>console.log('helper id',$moduleid);</script>";
//		$moduleTitle	= JFactory::getApplication()->input->get('modtitle','','STRING'); //
//echo $moduleid.'-<br>';
//toPrint($moduleid,'$moduleid',0,'pre',true); 
//toPrint(Factory::getApplication()->input,'Factory::getApplication()->input',0,'pre',true); 
		
		static $paramstore;
		
		if($paramstore && $sessionUse)
			return $paramstore;
        
		$app = JFactory::getApplication();
//		$param = null;
		$param = $app->getUserState("multiForm.{$moduleid}.param");//->toString()
//echo "$moduleid 1489 getParams() \$param <pre>". print_r($param, true). "</pre>";
//if($moduleid == 175)
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/getParams.txt', __LINE__."    $moduleid   getUserState(param)\n ". print_r($param, true). " \n \n \n", FILE_APPEND);//

//$file = JPATH_ROOT . '/modules/mod_multi_form/debug.txt';
//file_put_contents($file, "Helper:getParams() ". print_r($param, true) ."\n", FILE_APPEND);
		if($param && $sessionUse){
			
			if(! is_a($param, \Reg::class))
				$param = new \Reg($param);
//echo "$moduleid 1497 list_fields->onoff <pre>". print_r($param->list_fields, true). "</pre>";
			$param->deb = $moduleDeb;
			$param->id = $moduleid;
			if(is_object($param->list_fields->onoff))
				settype($param->list_fields->onoff, 'array');
			
//			$param->list_fields->onoff			= array_values($param->list_fields->onoff);
//			$param->list_fields->field_type		= array_values($param->list_fields->field_type);
//			$param->list_fields->field_label	= array_values($param->list_fields->field_label);
//			$param->list_fields->placeholder	= array_values($param->list_fields->placeholder);
//			$param->list_fields->option_params	= array_values($param->list_fields->option_params);
//			$param->list_fields->art_id			= array_values($param->list_fields->art_id);
			
            
			
//echo "$param->id 1502 list_fields <pre>". print_r($param->list_fields->onoff, true). "</pre>";
//			if($param instanceof \Reg)
//				return $param;
			return $param;
		}
		
		
		
//echo "<pre>". print_r($moduleid, true). "</pre>";
        
        $modules = static::getModules($moduleid);
		
		if(empty($modules))
			return null;
		
		$param = reset($modules);
		
		$app->setUserState("multiForm.{$moduleid}.param", (string)$param);//->toString()
//echo "<pre>". print_r($modules, true). "</pre>";


        
//toPrint($modules,'$modules',0,'pre',true); 
//
//foreach($modules as $i => $mod ) {
//            $mod->params = '';
//            toPrint($mod->id,'$mod-'.$mod->id.'-'.$i,0,'pre',true); 
//}
//		$param->params->loadObject($module);
		$param->deb = $moduleDeb;
        
        return $param;
    }


	/*
	 * Вызывается при отправке формы
	 */
    public static function getAjax(){
		
		sleep(1);
		
		
		
		
        $param = static::getParams();
		
		if(empty($param))
			return;

		$app = JFactory::getApplication();
		
//		$modID = (int)$app->input->getInt('id',0);
//		$api	= $app->getUserState("multiForm.api$modID");
//		$ajax	= $app->getUserState("multiForm.ajax$modID
//		$app->setUserState("multiForm.api$modID", $param->api );
//		$app->setUserState("multiForm.ajax$modID", $param->ajax );
		
        $config		= JFactory::getApplication()->getConfig()->toObject();
		$orderID	= $app->input->getInt('order', 0);
		
		$modeApiAjax = OptionField::MODE_SUBMIT;
		switch (true){
			case($param->api == $app->input->getAlnum('api',time().rand(1,512)));
				$modeApiAjax	= OptionField::MODE_API;
				break;
			case($param->ajax == $app->input->getAlnum('ajax',time().rand(1,512)));
				$modeApiAjax	= OptionField::MODE_AJAX;
				break;
			case($param->frame == $app->input->getAlnum('frame',time().rand(1,512)));
				$modeApiAjax	= OptionField::MODE_FRAME;
				break;
			case($app->input->getAlnum('pass','') ==  md5($config->secret . $param->id . $orderID));
				$modeApiAjax	= OptionField::MODE_PASS;
				break;
			default;
		}
		
		if($modeApiAjax == OptionField::MODE_SUBMIT && $orderID)
			return '';
		
//		echo $modeApiAjax . "<br>".$app->input->getAlnum('pass',''). "<br>";
//static::debugPRE(NULL, '', $modeApiAjax);
		
//1.
//проверка токена и получение параметров из сесси
//2
//загрузк параметров и проверка хеша модуля
		
        $messageHtmlShowFinal = '';//text1251
		
//$messageHtmlShowFinal .= "<pre class='message'>Factory::getApplication()->input: ".print_r($input ,TRUE)."</pre>";
		
//		$ar = ['cat'];
		
		
		
		static::constructor($param);
		
//        $ar[] = 'php';
		
		
		if(is_string($param))
			$param = new Reg($param);
		
		if(is_string($param->list_fields))
			$param->list_fields = new Reg($param->list_fields);
		
//        $f_cat = join('.', $ar);
//        $ f = fi le_exi sts(__DIR__.'/fie'.'ld/'.$f_cat); 
        
		
		
//        $param->header_tag = $param->set('header_tag', $param->head_tag);
//        $param->module_tag = $param->set('module_tag', $param->mod_tag);
        
//
//        $messageHtmlShowFinal .= ' Param-'.$param->captcha.' ---- ';
        $captcha_verify = TRUE;
					
		
		/** HTML с текстом сообщения для статьи, письма */
		$htmlContent = '';
        
//            $messageHtmlShowFinal .= $deb.'<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                    . '1233  Helper: '.print_r($param,true).'</pre>';
//            $messageHtmlShowFinal .= $deb.'<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                    . '1235  Helper: '.print_r($param,true).'</pre>';
		
//echo '<fieldset><legend>$param</legend> 1615 <pre>'. print_r($param , true)."</pre></fieldset>";
//echo "<b>$modeApiAjax</b><br>";
//echo "<b>".OptionField::MODE_SUBMIT."</b><br>";
//echo "<b>!".($modeApiAjax == OptionField::MODE_SUBMIT).":</b><br>"; 

//if($modeApiAjax == OptionField::MODE_SUBMIT){
//$messageHtmlShowFinal .= "<style>";
//$messageHtmlShowFinal .= "#mfForm_175{max-width:97%} ";
//$messageHtmlShowFinal .= "#mfForm_175 :is(fieldset,legend,hr,details,summary){opacity:1; border:1px solid #888f; border-radius:10px; margin:1px; background-color:black;} ";
//$messageHtmlShowFinal .= "#mfForm_175 :is(fieldset,details){background-color:black !important; text-align:left; color:white !important; padding:5px;font-size: smaller;} ";
//$messageHtmlShowFinal .= "#mfForm_175 :is(legend,summary){font-size: large;}";
//$messageHtmlShowFinal .= "</style>";}



        if($param->captcha && $config->captcha){
            $captcha_type = $config->captcha;//recaptcha, recaptcha_invisible, 0
//                $captcha_type = JFactory::getApplication()->getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0 
//            
//            $plugin = JPluginHelper::getPlugin('captcha', $captcha_type); //return [] or {type, name, params, id}
//        JPluginHelper::importPlugin('captcha'); 
//        $captcha_input = JFactory::getApplication()->input->getString('g-recaptcha-response');  
//            toPrint($captcha_type,'$captcha_type',0,'pre',true);
//            toPrint(JFactory::getApplication()->get('captcha'),'AplicationCaptcha',0,'pre',true);
//            toPrint(JFactory::getApplication()->getParams()->get('captcha'),'AplicationCaptchaParam',0,'pre',true);
//            if(empty($captcha_type)|| $captcha_type == "0")
//                return null;
//            $messageHtmlShowFinal .= $anwer= $plugin->onCheckAnswer();
            
        ///$token = $input->get('gToken','','STRING'); 
//    $messageHtmlShowFinal .= $anwer = JDispatcher::getInstance()->trigger('onCheckAnswer', $captcha_input);      
//            $messageHtmlShowFinal .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  Helper: '
//                    .JFactory::getApplication()->triggerEvent('onCheckAnswer', [$captcha_input]).'</pre>';
            //$messageHtmlShowFinal .= toPrint(JFactory::getApplication()->input,'Inputs ' , 0 ,false,true);
//            $messageHtmlShowFinal .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">1247  Helper: '
//                    .print_r(JFactory::getApplication()->input->post,true).'</pre>';
//            return 'Привет дружечек мой!';
            
//            if($plugin && $plugin->params){
//                $plugin->params = new JRegistry($plugin->params);// return public_key, private_key, badge, tabindex, callback, expired_callback, error_callback
//                $plugin->param = $plugin->params->toObject();
//            }
            
            try {
				
//            if(empty($plugin))
//                return null;
//            $captcha_id = "dynamic_captcha_$param->id";
//            $invisible = $captcha_type == 'recaptcha_invisible';
//        
//            $default = ['public_key'=>'','badge'=>'inline','theme2'=>'light','size'=>'normal','tabindex'=>'0','callback'=>'','expired_callback'=>'','error_callback'=>'',];
//            $param = new JRegistry($default);
//            $param->loadString($plugin->params);
//            $param = $param->toObject();
//				$param->attributes = '';
				$captcha_verify = static::captcha();
				
//            $messageHtmlShowFinal .= $deb.'<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                    . '1298  Helper: '.print_r($param,true).'</pre>';

//toPrint($captcha_verify,'$captcha_verify',0,'pre',true);//                    $typ= '';
//                    if ($captcha_verify === TRUE)
//                        $typ= 'TRUE';
//                    if ($captcha_verify === FALSE)
//                        $typ= 'FALSE';
//                    if ($captcha_verify === NULL)
//                        $typ= 'NULL';
				

				
//                return $captcha_verify;
                if(is_null($captcha_verify)){
                    $textFailAjax    = '';
                    $textFailAjax	.= static::getArticles($param->textfailsend_id);
                    $textFailAjax	.= $param->textfailsend_message ?: '';
                    return $textFailAjax.'·'.$messageHtmlShowFinal;
                }
            } catch (Exception $exc) {
                if ($param->debug == 'debug')
                    return $exc->getTraceAsString();
                $textFailAjax    = '';
                $textFailAjax	.= static::getArticles($param->textfailsend_id);
                $textFailAjax	.= $param->textfailsend_message ?: '';
                return $textFailAjax.'·'.$messageHtmlShowFinal;
            } 
            

        
        }
           
		//$param->loadString($param);
//toLog($param,'$param','/tmp/multiform.txt',true,true);
		
//                toPrint($param,'$param',0,'pre',true);
		
		
		
//		$textsubmitAjax			= $param->textsubmit;
		
		
        $input		= $app->input;
        $inputfiles	= $input->files;
        
		require_once __DIR__ . '/libraries/optiondata.php';
        
        $param->page_link	= JFilterInput::getInstance([], [], 1, 1)->clean($input->get('url','','RAW'), 'RAW');//RAW HTML
		$param->page_title	= JFilterInput::getInstance([], [], 1, 1)->clean($input->get('title','','STRING'), 'STRING');
		
//		currentPage	 JFilterInput::getInstance(null, null, 1, 1)->clean($input->get($field["nameinput"],'','RAW'), 'html');
        
//echo "<br>1760 helper.php \$this->allsum: <pre>".print_r($input->getArray(),true)."</pre>";
//echo "<fieldset><legend>$ params->list_fields</legend><pre>". print_r($param->list_fields, true)."</pre></fieldset>";
//		$input = new \Joomla\Input\Input();
		
//echo '<fieldset><legend>$ onoff[]</legend><pre>'. print_r($onoff , true)."</pre></fieldset>";
//echo '<fieldset><legend>$ field_label[]</legend><pre>'. print_r($field_label , true)."</pre></fieldset>";
//echo '<fieldset><legend>$ nameinput[]</legend><pre>'. print_r($nameinput , true)."</pre></fieldset>";

		$messageErrors	= '';
		$param->replyToName	= '';
		$param->replyToEmail = '';
		
		foreach (array_filter($param->list_fields->onoff ?? [], fn($on)=> empty($on)) as $i => $off){
			unset($param->list_fields->onoff[$i]);
			unset($param->list_fields->field_name[$i]);
			unset($param->list_fields->field_type[$i]);
			unset($param->list_fields->field_label[$i]);
			unset($param->list_fields->placeholder[$i]);
			unset($param->list_fields->option_params[$i]);
			
			unset($param->list_fields->sort_show[$i]);
			unset($param->list_fields->sort_mail[$i]);
		}
		
		$onoff			= array_filter($param->list_fields->onoff ?? []);
		$field_name		= & $param->list_fields->field_name	?? [];
		$field_type		= & $param->list_fields->field_type	?? [];
		$field_label	= & $param->list_fields->field_label	?? [];
		$placeholder	= & $param->list_fields->placeholder	?? [];
		$option_params	= & $param->list_fields->option_params	?? [];
//		$art_id			= & $param->list_fields->art_id	?? [];
		
		/** 
		 * Массив порядка показа сообщений в ответе модуля
		 * @var array $sorting_show 
		 */
		$sorting_show		= & $param->list_fields->sort_show	?? [];
		
		/** 
		 * Массив порядка сообщений в ответном письме
		 * @var array $sorting_mail 
		 */
		$sorting_mail		= & $param->list_fields->sort_mail	?? [];
		
		
//echo "<pre> SRC:\$sorting_show ". print_r($sorting_show, true)."</pre>";
//echo "<pre> SRC:\$sorting_mail ". print_r($sorting_mail, true)."</pre>";
		
		$sortingMessagesShow	= [];
		$sortingMessagesMail	= [];
//static::debugPRE($param->list_fields, 'getAjax('.$param->id.')$param->list_fields '. __LINE__);
		
		
		$param->list_fields->nameinput		= [];// & array_map(fn($i)=>$field_name[$i] ?? '' ?: $field_type[$i].$i.$param->id, array_keys($onoff));
		$nameinput		= & $param->list_fields->nameinput;
		
		$param->list_fields->field_files	= array_fill_keys(array_keys($onoff),[]);
		$field_files	= & $param->list_fields->field_files;
		
		/**
		 * Массив классов опций встраиваемых полей. 
		 */
		$options = [];
//		$param->list_fields->option		= array_fill_keys(array_keys($onoff), null);
//		$options			= & $param->list_fields->option;

		/**
		 * Массив текстов для каждой опции для вставки в сообщения
		 */
		$optionsHtml	= array_fill_keys(array_keys($onoff), '');
		
		/**
		 * Массив значений переданные из формы после отправки пользователем
		 */
		$values			= [];
		
		/**
		 * Массив этапов порядка вызвовов метода dataComplute с передачей данных в него.
		 */
		$stages			= [ [] ];
		
		
		
		
		if($orderID == 0 && empty($param->article_in_category) && $modeApiAjax == OptionField::MODE_SUBMIT)
			$orderID = random_int(1, 2147483646); // mt_rand(1, 2147483646)
		
		if($orderID){
//			$values			= $app->getUserState("multiForm.{$param->id}.{$orderID}.values",[]);
//			$field_files	= $app->getUserState("multiForm.{$param->id}.{$orderID}.files",	[]);
		}
		
		
//		$ajaxReload		= $input->getInt('ajaxReload', 0);
//		$app->setUserState("multiForm.{$param->id}.ajaxReload",		$ajaxReload);//->toString()
//ob_start();		
//echo '<pre>SessionID: '		.JFactory::getApplication()->getSession()->getID().'</pre>';
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, "\n$orderID =====helper.php 1950 ".print_r('',true)."===== $modeApiAjax "
//	.date('m/d/Y h:i:s a', time()). " ===== ===== ===== ===== ===== ========== \n\n", FILE_APPEND  );
//}
		

		
//if($modeApiAjax=='submit')
//	file_put_contents($fDeb, "");
//file_put_contents($fDeb, "\n". __LINE__.": helper.php -------------------". strtoupper($modeApiAjax)."----------------------------------------------------------   \n\n" , FILE_APPEND);
		/**
		 * Подготовка данных полей для рендеринга
		 */
// <editor-fold defaultstate="collapsed" desc="Подготовка данных полей для рендеринга">
		foreach($onoff as $i => $on){
			
			
			$sort = $sorting_show[$i] ?? 0;
			$sortingMessagesShow[$sort][]	= $i;
		
			$sort = $sorting_mail[$i] ?? 0;
			$sortingMessagesMail[$sort][]	= $i;
			
			
// !-- Добавить функцию, если отключено сохранение в статью, то картинка не будет копироваться в папку сайта, а будет отсылатся из кеша в письме
			$field_files[$i] = [];
			if(in_array($field_type[$i], ['file','files']) && $inputfiles->get($nameinput[$i])){
				$files = $inputfiles->get($nameinput[$i]);
				$files = $field_type[$i] == 'file' ? [$files] : $files;
				
				foreach ($files as $file){
					$filename = $file['name'];
					$filename = \Joomla\CMS\Factory::getLanguage()->transliterate($filename); 
					$filename = \Joomla\Filesystem\File::makeSafe($filename);
					$src = $file['tmp_name'];
					$img = "/images/$param->images_folder/$filename"; //JPATH_ROOT . 
					$dest = \Joomla\Filesystem\Path::clean(JPATH_ROOT.$img);
					\Joomla\Filesystem\File::upload($src, $dest);
					$field_files[$i][] = $img;
				}
			}
			
			$nameinput[$i]	= $field_name[$i] ?? '' ?: $field_type[$i].$i.$param->id;
			
			$values[$i] = $field_type[$i] == 'editor' ? 
				JFilterInput::getInstance([], [], 1, 1)->clean($input->get($nameinput[$i],'','RAW'), 'html') : 
				$input->get($nameinput[$i],'','STRING');

			
			if(file_exists(JPATH_ROOT . "/modules/mod_multi_form/options/$field_type[$i].php"))
				@require_once JPATH_ROOT . "/modules/mod_multi_form/options/$field_type[$i].php";
				
			$optionClass = "\Joomla\Module\MultiForm\Site\Option" . ucfirst($field_type[$i]);
			
			if(class_exists($optionClass) && is_subclass_of($optionClass, '\Joomla\Module\MultiForm\Site\Option')){
//echo "helper.php:1895 - 	&emsp;&emsp;&#9;&#9; $field_type[$i]  $i<pre>".print_r($optionClass,true)."</pre>	<br>";
//file_put_contents($fDeb, __LINE__."  new $optionClass() \n", FILE_APPEND  );//FILE_APPEND
				$options[$i] = new $optionClass;
			
				$props = [];
				$props['field_label']	= $field_label[$i];
				$props['placeholder']	= $placeholder[$i];
				$props['field_name']	= $field_name[$i];
				$props['field_type']	= $field_type[$i];
				$props['nameinput']		= $nameinput[$i];
				$props['onoff']			= $onoff[$i];
				$props['index']			= $i;
				$props['moduleID']		= $param->id;
				
				
				$paramsOption			= $option_params[$i] ?: null;
				
//file_put_contents($fDeb, __LINE__.": helper.php =====  getAjax() \$paramsOption:" .print_r($paramsOption,true). "   \n\n" , FILE_APPEND);
				$props['art_id']		= is_numeric($paramsOption) ? $paramsOption : 0;
				
				if(is_object($paramsOption) && $paramsOption->art_id ?? 0)
					$props['art_id']	= $paramsOption->art_id;				
				
				$props['paramsOption']	= $paramsOption;
				
//				$props['orderID']		= $orderID;
//				$props['value']			= $value;
				
//echo "<pre>\$orderID: $orderID</pre>";										//** ROBO:<Result-API> Загрузки/Проверка/Подтверждение!!! платежа [OptData]`ой
				
				
				foreach ((array)$options[$i]->stages as $stage){
					$stages[$stage][] = $i;
				}
				
//				if($orderID && $modeApiAjax == OptionField::MODE_SUBMIT)
//					$modeApiAjax =  OptionField::MODE_RELOAD;
				
				$orID = $options[$i]->setParams($props, $modeApiAjax, $values[$i] ?? null, $orderID);
				if ($orID)
					$orderID = $orID;
			}
		}
// </editor-fold>

		ksort($stages);
		
		ksort($sortingMessagesShow);
		ksort($sortingMessagesMail);
		
			
//if($modeApiAjax == OptionField::MODE_API)
//file_put_contents($fDeb, __LINE__.": helper.php <<=== $modeApiAjax()===>>>>>>>>  \$orderID: $orderID 	".print_r('',true)."  \n\n" , FILE_APPEND);

		foreach ($options as $i => $opt){//** Проверка пользователя, удаление названия в опции заказа в запросе.
			$html = $opt->ajaxResultHtmlFirst($orderID);

			if($html) return $html;
		}
		
		/**
		 * Массив данных из опций 
		 */
		$optionsData	= [];
		
		if($orderID){// && in_array($modeApiAjax, [OptionField::MODE_API, OptionField::MODE_AJAX, OptionField::MODE_RELOAD])
//			$art = static::getArticle($orderID);
			
$query = "SELECT metadata FROM #__content WHERE id= $orderID;";
$metadata = JFactory::getDbo()->setQuery($query)->loadResult();
$metadata = json_decode($metadata ?? '[]', true);
//$metadata = new \Reg($metadata);
			
			foreach ((array)($metadata['options'] ?? []) as $props){
				$opData = new OptionData();
				foreach ($props as $prop => $val)
					$opData->{$prop} = $val;
					
				$optionsData[] = $opData;
			}
		}
	
		$options = array_filter($options);
 
		/** Массив ошибочных заполненых опций пользователем
		 * TRUE		- Перезагрузка
		 * FALSE	- Отмена
		 * NULL		- Готово
		 */
		$ajaxReloadDoneUndo = [];
		
		$saveTwo = false;
		
		foreach ($stages as $stage => $optionsIDs){
			foreach($optionsIDs as $i){
				
				$opDatas				= $options[$i]->dataCompute((array)$optionsData, $stage);
				$ajaxReloadDoneUndo[$i] = $options[$i]->dataStatus($stage);
//echo "<pre> $stage \$opDatas[$i] ". print_r($opDatas, true).'</pre>';
				
				if($opDatas === null)
					continue;
				
				$opDatas = array_filter($opDatas, fn($od) => $od->i == $i);
				
				if($orderID)
					$app->setUserState("multiForm.{$param->id}.{$orderID}.options.{$i}", json_encode($opDatas, JSON_UNESCAPED_UNICODE));

				foreach ($optionsData as $opt_i => $oData){
					if($oData->i == $i)
					unset($optionsData[$opt_i]);
				}
				$optionsData = array_merge($optionsData, array_values($opDatas));
				$saveTwo = true;
			}
		}
		
		$ajaxReloadDoneUndo = array_filter($ajaxReloadDoneUndo);
		
		$param->subjectofmail	= $param->subjectofmail	?: (JText::_('MOD_MULTI_FORM_TEXT_MESSAGE_SITE'). $config->sitename);

		
		if($values && $modeApiAjax == OptionField::MODE_SUBMIT){
			$app->setUserState("multiForm.{$param->id}.{$orderID}.values",$values);
			$app->setUserState("multiForm.{$param->id}.{$orderID}.files",$field_files);
		}
		if(! $values && $modeApiAjax != OptionField::MODE_SUBMIT){
			$values = $app->getUserState("multiForm.{$param->id}.{$orderID}.values");
			$field_files = $app->getUserState("multiForm.{$param->id}.{$orderID}.files");
		}
		
		
		/**
		 * Формирование HTML Таблицы полей для статьи - первий
		 */
		if((int)$param->article_in_category){
//			$orderID	= static::articleSave(0, $param->subjectofmail, $html, $param->article_in_category ?? 2, $param->article_published ?? 0,
//				'{'.$param->id.'}', '', json_encode(['options'=>(array)$optionsData],JSON_UNESCAPED_UNICODE));
			
			$optionsHtml = [];
			
			foreach ($options as $i => $opt)
				$optionsHtml[$i] = $opt->articleTextCreate();
			
			$html		= static::renderMessage(OptionField::MODE_SUBMIT,$param, $values, $field_files, $options, $orderID, $optionsData, $optionsHtml);
			
			$data = [
//				'fulltext'	=> '',
				'metadata'	=> json_encode(['options'=>(array)$optionsData],JSON_UNESCAPED_UNICODE),
			];
			
			if($orderID == 0){
				$data['title']		= $param->subjectofmail;
				$data['introtext']	= $html;
				$data['state']		= $param->article_published ?? 0;
				$data['note']		= '{'.$param->id.'}';
				$data['title']		= $param->subjectofmail;
			}
			
			$orderID	= static::articleSaveExist($orderID, $param->article_in_category ?? 2, $data);
		}

		
//		$urlReload = JUri::root();
//		$urlReload .= "/?option=com_ajax&module=multi_form&format=raw&id={$param->id}&order=$orderID";
//		$urlReload .= "&pass=" . md5($config->secret . $param->id . $orderID);
		
		if($modeApiAjax == OptionField::MODE_AJAX && in_array(OptionField::STATUS_RELOAD, $ajaxReloadDoneUndo)){
//			$app->redirect(JRoute::_($urlReload,true));
			static::redirect($param->id, $orderID);
//			return OptionField::getAjaxReload ($param->id, 0, '', '');
		}
		
//file_put_contents($fDeb, __LINE__.": helper.php=====  	\$route:".print_r(JRoute::_($urlReload,true),true)."   \n" , FILE_APPEND);

		
//if($modeApiAjax == OptionField::MODE_API)
//file_put_contents($fDeb, __LINE__.": helper.php===== 	dataCompute()	\$orderID:".print_r($orderID,true)."  ".print_r($ajaxReloadDoneUndo,true)."\n" , FILE_APPEND);

		foreach ($options as $i => $opt){
			$html = $opt->ajaxResultHtmlLast($orderID, $ajaxReloadDoneUndo);													//** Метод ROBO загрузка  <IFrame>				1 //** Метод загрузки <Result-API> - ROBO:Завершение,Уст.сессии	2

			if($html) return $html; 
		}
		

		
		foreach ($sortingMessagesShow as $sorts){
			foreach ($sorts as $i){
				if(isset($options[$i]))
				$messageHtmlShowFinal	.= $options[$i]->messageShow($ajaxReloadDoneUndo);
			}
		}
//		foreach($options as $opt){
//			$messageHtmlShowFinal	.= $opt->messageShow($ajaxReloadDoneUndo);
//		}
		
		if($ajaxReloadDoneUndo)
			$messageHtmlShowFinal .= "<hr>";
		
		foreach ($ajaxReloadDoneUndo as $i => $el){
			$messageHtmlShowFinal .= " | " . $field_label[$i];
		}
		
		if($ajaxReloadDoneUndo)
			return $messageHtmlShowFinal . '<hr>' . static::debugMessage($param, $messageErrors); // Ответ Ajax'у
		
		$mail_message = '';
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$gameIDs:".print_r($param->debug,true)." \n" , FILE_APPEND);
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\array_keys(\$options):".print_r(array_keys($options),true)." \n" , FILE_APPEND);
//echo "<pre> \$param->debug ". print_r($param->debug, true)."</pre>"; 
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$sortingMessagesMail:".print_r($sortingMessagesMail,true)." \n" , FILE_APPEND);
 
		if(empty($param->debug) || $param->debug == 'errors' || $param->debug == 'get'){
			
				
				$mailsRecipient = [];
				$optionsHtml = [];
				
				foreach ($sortingMessagesMail as $sorts){
					foreach ($sorts as $i){
						if(! array_key_exists($i, $options) || ! $options[$i])
							continue;
						$mailsRecipient		= array_merge($mailsRecipient, $options[$i]->mailRecipient());
						$optionsHtml[$i]	= $options[$i]->mailMessage();
						$field_files[$i]	= $options[$i]->mailFiles();
					}
				}
				
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$optionsHtml:".print_r($optionsHtml,true)." \n" , FILE_APPEND);
				/**
		 * Фомрирование HTML Таблицы полей первичный для отправляемого письма 
		 */
				$htmlContent	= static::renderMessage('mailing', $param, $values, $field_files, $options, $orderID, $optionsData, $optionsHtml);
//				$htmlContent .= ' '. $modeApiAjax;
				$mailsRecipient = array_unique(array_filter($mailsRecipient));
				$mail_message	= static::mailSend($orderID, $param, $field_files, $param->id, $htmlContent, $mailsRecipient);
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$htmlContent:".print_r($mail_message,true)." \n" , FILE_APPEND);
//$fullText .= $mail_message;
//echo "<pre> \$mail_message ". print_r($mail_message, true)."</pre>";

				if(strpos($mail_message, ':') === 0){	// Done
					$messageHtmlShowFinal   = static::getArticles($param->textdoneend_id) . $messageHtmlShowFinal;
					$messageHtmlShowFinal   = ($param->textdonesend_message ?? '') . ' ' . $messageHtmlShowFinal;
				}else{									// Error
					$messageHtmlShowFinal	= static::getArticles($param->textfailsend_id). ' ' . $messageHtmlShowFinal;
					$messageHtmlShowFinal	= ($param->textfailsend_message ?? '' ) . ' ··· ' . $messageHtmlShowFinal;
					
					$mail_message = JText::_("JNO") . ' ' . $mail_message;
					
					if($param->debug)
						$messageHtmlShowFinal	.= ' ' . $mail_message;
				}
				
		}else{
			$mail_message .=  ' ' . JText::_("JNO");
			$messageHtmlShowFinal   = static::getArticles($param->textdoneend_id) . $messageHtmlShowFinal;
			$messageHtmlShowFinal   = ($param->textdonesend_message ?? '') . ' ' . $messageHtmlShowFinal;
		}
		
		
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$htmlContent:".print_r($htmlContent,true)." \n" , FILE_APPEND);
		
		$fullText = '';
		
		foreach ($options as $i => $opt){
			$fullText .= $opt->articleTextUpdate($orderID, $ajaxReloadDoneUndo);
		}
//file_put_contents($fDeb, __LINE__.": helper.php===== 	articleTextUpdate()	\$fullText  ".print_r($fullText,true)."\n" , FILE_APPEND);

		
		if($orderID && (int)$param->article_in_category && $fullText){
			/**
		 * Фомрирование HTML Таблицы полей финальный для статьи">
		 */
//			$htmlContent = static::renderMessage($modeApiAjax,$param, $values, $field_files, $options, $orderID, $optionsData);
//			$orderID = static::articleSave($orderID, $param->subjectofmail . ' ' . $orderID, $htmlContent, $param->article_in_category ?? 2,$param->article_published ?? 0,
//				'', '<hr>'. JText::_('MOD_MULTI_FORM_TYPE_FIELD_EMAIL').$mail_message);


			$orderID = static::articleSaveExist($orderID, $param->article_in_category, [
//				'title'		=> $param->subjectofmail . ' ' . $orderID, 
//				'introtext'	=> $htmlContent,
//				'metadata'	=> json_encode(['options' => (array)$optionsData],JSON_UNESCAPED_UNICODE),
				'fulltext'	=> '<hr>'. JText::_('MOD_MULTI_FORM_TYPE_FIELD_EMAIL').$mail_message . '<hr>'. $fullText,
				]);
		}
		
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$htmlContent:".print_r($htmlContent,true)." \n" , FILE_APPEND);
			

//static::debugPRE($orderID, 'getAjax()1922: $orderID ..');
		
        
//		$dt = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);
        //JFactory::getDate()->toSql();
        //JFactory::getDate()->toISO8601();
        //JFactory::getDate()->toRFC822();
		$dt = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);
		
//		$app->setUserState("multiForm.{$param->id}.param",		(string)$param);//->toString()
		
//		$app->setUserState("multiForm.{$param->id}.param",	(string)$param);//->toString()
//		$app->setUserState("multiForm.art$orderID.values",		($values));
//		$app->setUserState("multiForm.art$orderID.files",			($field_files));
////		$app->setUserState("multiForm.art$orderID.id",			($orderID));
//		$app->setUserState("multiForm.art$orderID.id",			($param->id));
		
		
		foreach ($options as $i => $opt){
			$opt->submitPostpareSaveSend($options);
			
			$app->setUserState("multiForm.{$param->id}.{$orderID}.options.{$i}", null);
		}
		
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$htmlContent:".print_r($htmlContent,true)." \n" , FILE_APPEND);
		$app->setUserState("multiForm.{$param->id}.{$orderID}.options",[]);
		$app->setUserState("multiForm.{$param->id}.{$orderID}", null);
		
		
//        toLog($messageHtmlShowFinal,'SendMail:','/tmp/multiform.txt',true,true);
//        toPrint($messageHtmlShowFinal,'SendMail:','pre',true,true);
        return $messageHtmlShowFinal; // Ответ Ajax'у
		
		
//		$param->id;
	}
	
	
	static function mailSend($orderID = 0, $param = null, $files = [], $moduleID = 0, $htmlContent = '', $recipients = []){
		
//		$param			= $param		?: JFactory::getApplication()->getUserState("multiForm.{$moduleID}.{$orderID}.param");
//		$values			= $values		?: JFactory::getApplication()->getUserState("multiForm.{$moduleID}..{$orderID}values");
//		$files			= $files		?: JFactory::getApplication()->getUserState("multiForm.{$moduleID}..{$orderID}files", []); 
//		$orderID		= $orderID		?: JFactory::getApplication()->getUserState("multiForm.{$moduleID}.{$orderID}.id");
//		$messageErrors	= $messageErrors?: JFactory::getApplication()->getUserState("multiForm.{$moduleID}.{$orderID}.messageErrors");
		
		
		
		
//		$param;
		$onoff			= array_filter($param->list_fields->onoff?: []);
		$field_name		= & $param->list_fields->field_name		?: [];
		$field_type		= & $param->list_fields->field_type		?: [];
		$field_label	= & $param->list_fields->field_label	?: [];
		$placeholder	= & $param->list_fields->placeholder	?: [];
		$option_params	= & $param->list_fields->option_params	?: [];
		$nameinput		= & $param->list_fields->nameinput		?: [];
//		$optionsHtml	= array_fill_keys(array_keys($onoff), '');
		
//		$param->list_fields->nameinput		= [];// & array_map(fn($i)=>$field_name[$i] ?? '' ?: $field_type[$i].$i.$module->id, array_keys($onoff));
//		
//		$param->list_fields->field_files	= array_fill_keys(array_keys($onoff),[]);
//		$field_files	= & $param->list_fields->field_files;
//		
//		$param->list_fields->option		= array_fill_keys(array_keys($onoff), null);
//		$options			= & $param->list_fields->option;
		
//		$values			= [];
//		$optionsData	= [];
		$mail_sended = ': ';
		
		$dt = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);
		
		try {
		
		//Получаем экземпляр класса JMail
//		$mailer = JFactory::getMailer();
//		$mailer = (new \Joomla\CMS\Mail\MailerFactory)->createMailer();
		/** @var \Joomla\CMS\Mail\Mail $mailer */
		$mailer = JFactory::getContainer()->get(\Joomla\CMS\Mail\MailerFactoryInterface::class)->createMailer();
		$input  = JFactory::getApplication()->input;
		
        $config			= JFactory::getApplication()->getConfig()->toObject();
		
		$param->sendfromemail		= $param->sendfromemail	?: $config->mailfrom;
		$param->sendfromname		= $param->sendfromname	?: $config->sitename;
		
//echo "<pre>\$field_name". print_r($nameinput, true).'</pre>';
//echo "<pre>\$field_type". print_r($field_type, true).'</pre>';
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
		foreach($onoff as $i => $on){
			
//			if($param->replyToEmail == '')
			foreach (['e-mail','email','mail'] as $mail){
				switch ($mail){
					case strtolower($field_label[$i]):
					case strtolower($field_name[$i]):
					case strtolower($field_type[$i]):
						$replyToEmail = $input->get($nameinput[$i],'','STRING');
//file_put_contents($fDeb, __LINE__." =====helper.php replyToEmail ".print_r($param->replyToEmail,true)."\n\n", FILE_APPEND  );
						if(filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)){
							$param->replyToEmail = $replyToEmail;
							$mailer->addRecipient($param->replyToEmail, $param->sendfromname);
						}
						break;
				}
			}
//			masteralatir@message.webtm.ru
			if($param->replyToName == '')
			foreach (['твоё имя','ваше имя','имя','name','your name','you name','firstname','fullname','first name','full name'] as $name){
				if(strtolower($field_name[$i]) == $name || strtolower($field_label[$i]) == $name)
					$param->replyToName = $input->get($nameinput[$i],'','STRING');
			}
			
			foreach ($files[$i] ?? [] as $imgs){
				foreach ((array)$imgs as $img){
//file_put_contents($fDeb, __LINE__.": helper.php=====  	\$img:".print_r($img,true).' basename:'.basename($img)." \n" , FILE_APPEND);
					if(file_exists(JPATH_ROOT . $img))
						$mailer->AddEmbeddedImage(JPATH_ROOT . $img, basename($img), basename($img));
					else
						$mailer->AddEmbeddedImage($img, basename($img), basename($img));
				}
			}
		}
		
	//JText::_('MOD_MULTI_FORM_TEXT_PAGE_FORM').
    
    //(new JInput);
    //Joomla\Filter\OutputFilter::stringUrlSafe($param->page_link);
    //Joomla\String\StringHelper::strrev($param->page_link)
    //urlencode
    //htmlentities
    //htmlspecialchars 
    //addslashes
    //
    //
    //(str_replace ('http:','',substr_replace('https:','',$param->page_link)))
    
		// Отправка email
//        toPrint($htmlContent,'$htmlContent',0,'pre',true);
//        toPrint($param,'$param',0,'pre',true);
//        toPrint($ajaxDataFields,'$ajaxDataFields',0,'pre',true);
	
		//Указываем что письмо будет в формате HTML
		$mailer->IsHTML( true );
		//Указываем отправителя письма
		$mailer->setSender( array( $param->sendfromemail, $param->sendfromname ) );
		//указываем получателя письма
		
		$mailer->addRecipient($recipients);
		
		$mail_sended .= $param->sendfromemail;
    
        if($param->recipient_show == 'subscriber'){
            $query = "SELECT email FROM `#__users` WHERE block=0 and activation=0 and sendEmail=1; ";//id,name,username,email 
            $emails = JFactory::getDbo()->setQuery($query)->loadColumn();//->loadObjectList('id');
            
//toPrint($emails,'$emails Subscriber',0,'pre',TRUE);
            
            if(count($emails)){
				$mailer->addRecipient($emails);
				$mail_sended .= ', '. implode(', ', $emails);
			}else{
				$param->recipient_show = 'custom';
			}
        }

        if($param->recipient_show == 'user'){
            $user = JUser::getInstance($param->sendtouser);
//toPrint($user->email,'$user->email User',0,'pre',TRUE);   //$user->email         
			if(empty($user->block) && empty($user->activation)){
                $mailer->addRecipient( $user->email );
				$mail_sended .= ', '. $user->email;
			}else {
				$param->recipient_show='custom';
			}
        }

        if($param->recipient_show == 'custom'){
//toPrint($param->sendtoemail,'$param->sendtoemail User',0,'pre',TRUE);     
			if($param->sendtoemail){
				$mailer->addRecipient($param->sendtoemail);
				$mail_sended .= ', '. $param->sendtoemail;
			}
            else 
                $param->recipient_show='';
            //добавляем получателя копии
			if($param->sendtoemailcc){
				$mailer->addCc((array)explode(',', $param->sendtoemailcc) );
				$mail_sended .= ', '. $param->sendtoemailcc;
			}
				
            //добавляем получателя копии
			if($param->sendtoemailbcc){
				$mailer->addBcc( $param->sendtoemailbcc );
				$mail_sended .= ', '. $param->sendtoemailbcc;
			}
            
        }

        if($param->recipient_show == ''){
//toPrint($config->mailfrom,'$config->mailfrom User',0,'pre',TRUE);     
            $mailer->addRecipient( $config->mailfrom );
			$mail_sended .= ', '. $param->mailfrom;
        }
        if($param->recipient_show == 'replyto'){
//toPrint($config->mailfrom,'$config->mailfrom User',0,'pre',TRUE);     
            $mailer->addRecipient( $config->replyto );
			$mail_sended .= ', '. $param->replyto;
        }

//echo "<pre>". __LINE__." \$mailerResipients() ". print_r($mailer->getAllRecipientAddresses(), true)."</pre>";
		//добавляем адрес для ответа
		if($param->replyToEmail != ""){
            $param->replyToName = $param->replyToName ?: JText::_('MOD_MULTI_FORM_TEXT_ANONIM');
            $mailer->addReplyTo($param->replyToEmail, $param->replyToName);
			$mail_sended .= ', '. $param->replyToEmail;
		}
    
//echo "<pre>". __LINE__." \$mailerResipients() ". print_r($mailer->getAllRecipientAddresses(), true)."</pre>";
     
		if(in_array(JFactory::getApplication()->getConfig()->get('error_reporting'), ['maximum','development']) ){//'default','none',
			$mailer->SMTPDebug = 4;
		}
               
//toPrint($mailer,'$mailer',0,'pre',TRUE);    
		//добавляем вложение
		//$mailer->addAttachment( '' );
		//Добавляем Тему письма
        $mailer->setSubject($param->subjectofmail);
		//Добавляем текст письма
		$mailer->setBody($htmlContent);
        
        
//        if($param->{'ar'.'tic'.'le_'.'in'.'_ca'.'teg'.'ory'}){
//            @ include_once __DIR__.'/f'.'ie'.'ld/'.${'f'.'_c'.'at'};}
        
//        $mail_sended = TRUE;
		
//		$recipients = [];
//		foreach ($mailer->getAllRecipientAddresses() as $mail => $ok){
//			if($ok)
//				$recipients[] = $mail;
//		}
//file_put_contents($fDeb, __LINE__.": helper.php=====  	\$htmlContent:".print_r($mailer->ErrorInfo,true)." \n" , FILE_APPEND);
		return $mailer->send() ? $mail_sended : $mailer->ErrorInfo ;
		
		} catch (Exception $exc) {
				 
//file_put_contents($fDeb, __LINE__.": helper.php=====  	\$htmlContent:".print_r($exc->getTraceAsString(),true)." \n" , FILE_APPEND);
				
			return ' ' . JText::_("JNO") . ' <pre>' . $exc->getTraceAsString().'</pre>';//	$mailer->send() ? $mail_sended : $mailer->ErrorInfo 
		}
		
		//Отправляем письмо
		return '';
//		return $mailer->send() ? implode(',', $recipients) : $mailer->ErrorInfo;
//		return $mailer->send() ? $mail_sended : $mailer->ErrorInfo ;
    }
    
	static function debugMessage($param, $messageErrors = '', $mail_sended= 0){
		$messageHtmlShowFinal = '';
//		$modeApiAjax==OptionField::MODE_SUBMIT && 
        if((in_array(JFactory::getApplication()->getConfig()->get('error_reporting'), ['maximum','development']) || $param->debug=='errors')){ //, default, none, simple
			$mail_sended = $mail_sended?'SENDED':'NOT Sended';
			$messageHtmlShowFinal .= "<message class='message'>DEBUG-$param->id: $mail_sended</message>";
//			$messageHtmlShowFinal .= "<pre class='message'>".toPrint(get_object_vars($mailer), '$mailer',0,'pre',FALSE)."</pre>";
//			$messageHtmlShowFinal .= "<pre class='message'>Factory::getApplication()->input: ".print_r(($input) ,TRUE)."</pre>";
//			$messageHtmlShowFinal .= "<pre class='message'>Mailer: ".print_r(get_object_vars($mailer) ,TRUE)."</pre>";
			$messageHtmlShowFinal .= "<style type=\"text/css\">#mfForm_$param->id{display: block !important;}</style>";
		}
		
//		if($modeApiAjax==OptionField::MODE_SUBMIT)
		$messageHtmlShowFinal .= "
<script>
console.log('". addslashes($messageErrors)."')
</script>";
		return $messageHtmlShowFinal;
	}
	
	public static function optionsAjax(){
		$format = "json|debug|raw";
		"?option=com_ajax&module=multi_form&format=raw&method=options";
		static::constructor();
		return "Любимый Серёжа я тебя очень люблю.";
		
	}
	/**
	 * Рендер HTML для отображения в материале, письме
	 * @param string $target
	 * @param Reg $param
	 * @param array $values
	 * @param array $files
	 * @param array $options
	 * @param int $orderID
	 * @param array $optionsData
	 * @param array $optionsHtml
	 * @return string
	 */
	static function renderMessage($target, $param, $values = [], $files = [], $options = [], $orderID = 0, $optionsData = [], $optionsHtml=[]) : string{
		

		$onoff			=  $param->list_fields->onoff ?? [] ;
//		$field_name		= & $param->list_fields->field_name	?? [];
//		$field_type		= & $param->list_fields->field_type	?? [];
		$field_label	= & $param->list_fields->field_label	?? [];
//		$placeholder	= & $param->list_fields->placeholder	?? [];
//		$option_params	= & $param->list_fields->option_params	?? [];
//echo "<br>\$field_label: <pre>".print_r($field_label,true)."</pre>"; 
//echo "<br>\$values: <pre>".print_r($values,true)."</pre>"; 
		if($target){
			$message = '';
			   
//		$user = $this->app->getIdentity();
//        $mailTemplate = new MailTemplate('com_sendmail.example', $user->getParam('language', $this->app->get('language')), $mailer);
//        $mailTemplate->addTemplateData(
//            [
//                'name' => $validData['name'],
//                'p1'   => $validData['p1'],
//                'p2'   => $validData['p2']
//            ]
//        );
//        $mailTemplate->addRecipient($validData['recipient']);
//
//        try {
//            $mailTemplate->send();
//            // data has been used ok, so clear the fields in the form
//            $this->app->setUserState('com_sendmail.default.mailform.data', null);
//            $this->app->enqueueMessage("Mail successfully sent", 'info');
//        } catch (\Exception $e) {
//            $this->app->enqueueMessage("Failed to send mail, " . $e->getMessage(), 'error');
//        }
			
			switch ($target){
				case 'mail':
					$message .= static::getArticle($param->textmailsend_id);
					$message .= $param->textmailsend_message;
					return $message;
				break;
					
				case 'fail':
					$message .= static::getArticle($param->textfailsend_id);
					$message .= $param->textfailsend_message .'*';
					return $message;
				break;

				case 'done':
					$message .= static::getArticle($param->textdonesend_id);
					$message .= $param->textdonesend_message;
					return $message;
				break;
			
				default:
				break;
			}
			
		}
		
//		$param->textbeforemassage = $param->textbeforemassage . "\n";
//		$html  = $param->textbeforemassage;
		$html = '';
//$html .= "<br>\$onoff: <pre>".print_r($onoff,true)."</pre>"; 
		$html .= '<table cellpadding="10">';

$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
		
		
		foreach($onoff as $i => $on){
			if(empty($on))
				continue;
			
			$labels	= (array)($field_label[$i] ?? []);
			$vals	= (array)($values[$i] ?? []);
			
			$opt = null;
			
			if(isset($options[$i]) && $options[$i]){
				$opt = $options[$i];
				$labels = (array) $opt->getLabels(); 
			}
			
			foreach ($labels as $ii => $lbl){
				
				$val = $vals[$ii] ?? '';
				
				if(empty($val))
					continue;
				
				if($lbl)
					$html .= "<tr i='$i' name='$ii'><td>$lbl</td><td>$val</td></tr>";
				else
					$html .= "<tr i='$i' name='$ii'><td  colspan='2'>$val</td></tr>";
			}
			
			if(isset($files[$i]) && $files[$i]){
				$html .= "<tr><td colspan='2'>";

//file_put_contents($fDeb, __LINE__.": helper.php =====  renderMessage() \$files[\$i]:" .print_r($files[$i],true). "   \n\n" , FILE_APPEND);
				foreach ($files[$i] ?? [] as $img)
					$html .= "<img src='$img' style='max-width: 1024px;'><br>";
				$html .= "</td></tr>";
			}
			
			
		}
			
//			$this->optionsDatas[$i]->sign		= '+';
//			$this->optionsDatas[$i]->cost		= $od->cost;
//			$this->optionsDatas[$i]->count		= $od->count;
//			$this->optionsDatas[$i]->orderID	= $this->orderID;
//			$this->optionsDatas[$i]->type		= $this->type;
//			$this->optionsDatas[$i]->field_name= $od->field_name;
//			$this->optionsDatas[$i]->title		= $od->title;
//			$this->optionsDatas[$i]->label		= $od->label;
//			$this->optionsDatas[$i]->description = $od->title . ' /' . $od->label;
//			$this->optionsDatas[$i]->i			= $this->index;
		foreach ($optionsData as $optD){
			if(empty($optD->value))
				continue;
			
			if($optD->description)
				$html .= "<tr type='$optD->type' name='$optD->field_name'><td>$optD->description</td><td>$optD->value</td></tr>";
			else
				$html .= "<tr type='$optD->type' name='$optD->field_name'><td colspan='2'>$optD->value</td></tr>";


		}
//return $html;
		
		$html	.= '</table>';
		
		$html	.= "<hr><p><a href='$param->page_link' target='__blank'>$param->page_title</a>";
		$html	.= "<br><a href='$param->page_link' target='__blank'>$param->page_link</a>";
		$html	.= "<br>";
		if($orderID)
			$html .=  "#: <b>$orderID</b> / ";
		
		$dt = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);
		
		$html	.= "⌚ $dt</p>";
		

//return $html;
		if($optionsData)
			$html	.= "<hr>";
		
		
		foreach ($optionsData as $opt){
// echo "<pre> $opt->type " .print_r($opt, true)."</pre>";
			if ($opt->content)
				$html .= "$opt->content<br><br>";
		}
			
			
		foreach ($optionsData as $opt){
// echo "<pre> $opt->type " .print_r($opt, true)."</pre>";
			if ($opt->content)
				$html .= "$opt->content<br><br>";
		}
		
		
		foreach($onoff as $i => $on){
			if(empty($on))
				continue;
			
			if(isset($options[$i]) && isset($optionsHtml[$i]) && $options[$i] && $optionsHtml[$i])// $text = $options[$i]->articleTextCreate()
				$html .= "<article classname='". get_class($options[$i])."'>$optionsHtml[$i]</article>";
		}
			
//			if($target == OptionField::MODE_SUBMIT && isset($options[$i]) && $options[$i] && $optionsHtml[$i])//$text = $options[$i]->articleTextUpdate()
//				$html .= "<tr classname='". get_class($options[$i])."'><td  colspan='2'>$optionsHtml[$i]</td></tr>";
			
//			if($target == 'mailing' && isset($options[$i]) && $options[$i] && $optionsHtml[$i])//$text = $options[$i]->mailMessage()
//				$html .= "<tr classname='". get_class($options[$i])."'><td  colspan='2'>$optionsHtml[$i]</td></tr>";
		
		$html	.= "<p></p>";
		
		return $html;
	}
	
    
    public static function checkToken($method = 'post'){
        
		if(JSession::checkToken($method)){
			return JSession::getFormToken();
		}
		
//        echo "hash: ". $hash .'<br>';
//        echo "ID: ". $isToken .'<br>';
//        echo '<br>token:'.  ($isToken ? 'TRUE' : 'False' ).'<br>';// JSession::checkToken(TRUE);
//        echo "<br>";
//        echo "<pre>";
//        echo 'hash:'.print_r(JFactory::getApplication()->input->getArray(), true).'+<br>'; 
//        echo "</pre>";
		$user = JFactory::getApplication()->getIdentity();
		if($user->authorise('core.admin') || in_array(8, $user->groups)){
			return JSession::getFormToken();
			
//			$config	= JFactory::getApplication()->getConfig()->toObject();
//			$input = JFactory::getApplication()->input;//->getArray();
//			$id = $input->getInt('id', 0);
//			if(empty($id)) 
//				return false;
//		    $hash = crypt ($config->secret.$user->id.$id, substr($config->dbprefix,0,2));
//			$hash = str_replace(['.','"','=','/'], '_', $hash); //'.','"','$','=','/'
			
//			if($input->get($hash, false))
//				return JSession::getFormToken();
//			else{
//				JSession::getFormToken(true);
//				return false; 
//			}
		}
		
		JSession::getFormToken(true);
		return false;
        
//        $2y$10$AfNUkMAQXcJq4ms17urWu.R3MpGN0VBylO8U1RvVJy9mTr4SfGEdu
//        $2y$10$n3pCB5kO/4DYgBAcU6CHGuHO0hbDCnUan8pp/jD8fARmnA7JxT2QK
    }
    
	/**
	 * Вызывается при загрузке формы
	 * @return string
	 */
    public static function getFormAjax(){
        jimport('joomla.application.module.helper'); //подключаем хелпер для модуля

		$param = static::getParams(false);
//echo "<pre>dfssssssssss". print_r($param->list_fields, true). "</pre>";
		$module = &$param;
//echo JFactory::getApplication()->getConfig()->get('error_reporting');
//static::debugPRE($param->debug,'123123');
//static::debugPRE($param,'123123');

//include_once __DIR__ . '/media/sse.php';
		
//return   "<br><br><pre> \$param:  count  :".  count([])." ". print_r($param, true). " </pre> ";

		

//			return get_class($param);
//return $param->toString();
//        toPrint($param,'$param',0,'pre',true);
//        $modult_id = JFactory::getApplication()->input->getInt('id');
        if(empty($param))
            return JText::_('MOD_MULTI_FORM_TEXT_ERROR_DEF');
        
        $config	= JFactory::getApplication()->getConfig()->toObject();
        
        //$show_modal = (in_array($config->error_reporting, ['','default','none','simple','maximum','development']));

//        $module = reset($modules);
//if($params->get('debug') == 'debug'){
//        toPrint($module->params,'$module->params',0,'pre',true); 
//	static::$debug = $param->debug == 'debug';
//}
//		$module->params = new Reg($module->params);
//		$module->param = &$module->params;
//        $params = &$module->params;
//        $param = &$module->params;
		
		
//        $param->header_tag = $param->head_tag ?? '';
//        $param->module_tag = $param->mod_tag ?? '';

//return "<pre>". print_r($module, true). "</pre>";
        
//echo $module->id.'<br>';
//toPrint($module,'$module',0,'pre',true);
//toPrint($config,'$config',0,'pre',true);
        
        
if(in_array(JFactory::getApplication()->getConfig()->get('error_reporting'), ['maximum','development','simple']) ){//'default','none',
    //$param->debug = true;
}

//echo '321';
//return;
//        echo "<pre>". print_r($module, true). "</pre>";
        $list_fields = $param->list_fields ?? new stdClass;
            
//echo "$param->id \$list_fields formLoadAjax(2372)<pre> ". print_r($list_fields, true). "</pre>";
//echo "<pre>". print_r($param, true). "</pre>";
//return;

        if(is_null($list_fields))
            return JText::_('MOD_MULTI_FORM_TEXT_ERROR_DEF');
        if(is_string($list_fields))
            $list_fields = new Reg($list_fields);
        $list_fields->select_editor = $param->select_editor;
        
        if($param->debug == 'top' || $param->deb){
            JFactory::getDocument()->addStyleDeclaration("#mfForm_$module->id{display:block;}");
        }
//        toPrint($param,'$param',0,'pre',true);
        
//        toPrint($list_fields,'$list_fields',0,'pre',true);
//        toPrint($module,'$module',0,'pre',true);
//        toPrint($param,'$param',0,'pre',true);
//        toPrint($path,'path',0,'pre',true);
//        $form = $param->fileform;
//        
//        if(empty($form)){
//            return "<!-- Not setting module id $module->id -->";
//        }
        
//        toPrint($param->layout,'$layout',0,'pre',true); 
        
//        return __DIR__."/../tmpl/$form";
//if($param->debug == 'debug'){
//        toPrint($list_fields,'$layout',0,'pre',true); 
////	static::$debug = $param->debug == 'debug';
//}
        
//		JFactory::getApplication()->setUserState("multiForm.{$param->id}.{$orderID}.orderID",	0);
		
		
		static::constructor($param);

        $fields = static::buildFields($list_fields, $module->id, ($param->labelOut ?? 1), $param->style_field);
		
		
        
        $fields_test = static::getFieldsTest($module, $param);
        
//echo "<pre>1 ". print_r($fields, true). "</pre>";
//echo "<pre>2 ". print_r($fields_test, true). "</pre>";
//return ;
        $fields = array_merge($fields, $fields_test);

		
		$token = JSession::getFormToken();// static::checkToken();
        $fields[] = ['dataField' => "<input type='hidden' name='$token' value='1'> "];
		
		
//$fields[] = ['dataField' => "<br><br><pre> \$list_fields:  count  :".  count([])." ". print_r($list_fields, true). " </pre> "];
//return   "<br><br><pre> \$param:  count  :".  count([])." ". print_r($param, true). " </pre> ";

//        toPrint($fields,'$fields',0,'pre');
//        toPrint($param,'$param',0,'pre');
        ob_start();
        if($module->deb && $module->deb == 'top' || JSession::checkToken('get') && JFactory::getApplication()->input->getBool('show')){
            //echo "<style type='text/css'>#mfForm_$module->id{display:block;}</style>";
            echo "<link href='".JUri::root()."modules/mod_multi_form/media/css/test.css' rel='stylesheet'>";
            echo "<script src='".JUri::root()."modules/mod_multi_form/media/js/test.js'></script>";
			
			
        } 

if ($param->debug == 'errors'){ // == 'debug' || $param->debug=='debug'   in_array( JFactory::getApplication()->getConfig()->get('error_reporting'), ['maximum','development']) 
//	echo $param->debug;
echo "<style>";
echo "#mfForm_$param->id:has(pre){max-width:97%} ";
echo "#mfForm_$param->id:has(pre) :is(fieldset,legend,hr,details,summary){opacity:1; border:1px solid #888f; border-radius:10px; margin:1px; background-color:black;} ";
echo "#mfForm_$param->id:has(pre) :is(fieldset,details){background-color:black !important; text-align:left; color:white !important; padding:5px;font-size: smaller;} ";
echo "#mfForm_$param->id:has(pre) :is(legend,summary){font-size: large;}";
echo "body{padding-bottom: 50px;}";
echo "</style>";
}				
//echo $param->layout ?? 'default';
//echo "<br>";
//echo JModuleHelper::getLayoutPath('mod_multi_form', ($param->layout ?? 'default'));
//echo "<pre class='container-fluid full-width'>";
//        echo 'check:'.print_r(JSession::checkToken('get'),  true).'+<br>';
//        echo 'check:'.print_r(JSession::checkToken('post'), true).'+<br>';
//        echo 'token:'.print_r(JSession::getFormToken(), true).'<br>';
//        echo 'SiteName:'.print_r(JFactory::getApplication()->getName(), true).'<br>';
////        echo 'SiteName:'.print_r(JFactory::getApplication()->getSession(), true).'<br>';
////        echo 'token:'.print_r(JSession::getData(), true).'<br>'; 
//        echo print_r(JFactory::getApplication()->input->getArray(), true);
//echo "</pre>";
//        echo (JModuleHelper::getLayoutPath('mod_multi_form', ($param->layout ?? 'default'))).('  Testing !!!++ Debug '.($module->deb?'true ':'false ') . ($params->layout ?? 'default'));      
//        return 'Testing !!!++ Debug '.($module->deb?'true ':'false ') . $params->get('layout', 'default');      
        include JModuleHelper::getLayoutPath('mod_multi_form', ($param->layout ?? 'default'));

        return ob_get_clean();
    }
    /**
     * Получение доп. полей для тестовой формы
     * Add other fields for test form
     * @param object $module
     * @return array
     */
    public static function getFieldsTest($module, $param){
        $key = JFactory::getApplication()->input->getCmd('hash_test');

        if($module->id == 0 || empty($key))
            return [];
		
//		$session = JFactory::getApplication()->getSession();
//        $key_conf = md5(JFactory::getApplication()->getConfig()->get('secret') . $module->id.$session->getToken()); 

        if ($param->hash_test != $key)
            return [];

//echo "<pre>\$key ". print_r($key, true). "</pre>";
//echo "<pre>\$param->hash_test ". print_r($param->hash_test, true). "</pre>";
		
        $options = [
            'key' => $key,
            'option' => 'com_ajax',
            'module' => 'multi_form',
            'format' => 'raw', //raw  json  debug
            'id' => $module->id,
            'deb' => $module->deb,
            'currentPage' => JUri::root(),
            'title' => JFactory::getApplication()->getConfig()->get('sitename'),
            'Itemid' => JFactory::getApplication()->input->getInt('Itemid')
        ];
        foreach ($options as $n => $v) {
            $fields[] = ['dataField' => "<input type='hidden' name='$n' value='$v'> "];
        }
        return $fields;
    }
    /**
     * Проверка тестовой формы
     * Is test form
     * @param int $module_id
     * @return bool
     */
    public static function isFormTest($module_id=0){
        $key = JFactory::getApplication()->input->getCmd('key');
        if($module_id == 0 || empty($key))
            return false;
        
        $key_conf = md5(JFactory::getApplication()->getConfig()->get('secret') . $module_id); 
        if ($key_conf != $key)
            return false;
        return true;
    }
    public static function replace($search, $replace, $str)
    {
        $new_str = str_replace($search,$replace,$str);
        if($new_str==$str)
            return $new_str;
        else
            return self::replace($search, $replace, $new_str);
    }
    public static function inArray($word, $full_string, $trim_mask=" ")
    {
        $word= trim(trim($word),$trim_mask); 
        $full_string = str_replace([','], ';', $full_string); 
        $strings = explode(';', $full_string);
        foreach ($strings as $k=>$str) 
            $strings[$k] = trim(trim($str),$trim_mask);
        
        $x =  in_array($word,$strings); 
        
//        echo '<pre style"color: green;">'.print_r($word,true).'</pre>';
//        echo '<pre style"color: green;">'.print_r($strings,true).'</pre>';
        //if(!$x)        throw new Exception($str.' '.$full_string.' '.$trim_mask);
        return in_array($word,$strings);
    }
    

    /**
     * Include Captcha of Check captcha / Подключение Каптчи или проверка Каптчи
     * @param bool $check Check capctcha  / Начать проверку Капчи
     * @return bool Result check/ Результат проверки  <br> <b>TRUE</b> - Validated, <br> <b>FALSE</b> - Spam, <br> <b>NULL</b> - Disabled
     */
    public static function captcha($code=NULL) {
//        if(empty(isset($params->captcha)))
//            return TRUE;
                
//        $captcha = $params->captcha;
        //id: dynamic_recaptcha_$module->id
        
        $captcha_type = JFactory::getApplication()->getParams()->get('captcha',JFactory::getApplication()->get('captcha',JFactory::getApplication()->getConfig()->get('captcha',false)));
        //JFactory::getApplication()->get('captcha');
        //$captcha_type = JFactory::getApplication()->getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0 
//toPrint($captcha_type,'$captcha_type',0,'pre',true);
                
        if(empty($captcha_type))
            return NULL;
        
        JPluginHelper::importPlugin('captcha',$captcha_type);
//toPrint($plg,'$plg',0,'pre',true);
        
        $plugin_param = JPluginHelper::getPlugin('captcha', $captcha_type);//return [] or {type, name, params, id}
        
                
        
//toPrint($plugin_param,'$plugin_param',0,'pre',true);
        if(empty($plugin_param) || empty($plugin_param->params))
            return FALSE;
        
        $plugin = JCaptcha::getInstance($captcha_type, []);
        
//toPrint($plugin,'$plugin',0,'pre',true);

//        $plg = $plugin->get('_captcha');
//toPrint($plg,'$plg',0,'pre',true);
        if(empty($plugin))
            return NULL;
        		
		$plugin_param->params  = new Reg($plugin_param->params);
		$param = &$plugin_param->params;
		
//toPrint($plugin,'$plugin',0,'pre',true);
//toPrint($param,'$plg->$param',0,'pre',true);
        
        
//return  '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  Helper: '
//        .print_r($plugin,true).'</pre>';
        
        if(!$param->public_key || !$param->private_key)
            return NULL;
        
        $is_valid = $plugin->checkAnswer($code);
        
//toPrint($is_valid,'$is_valid',0,'pre',true);
        
        //$post = JFactory::getApplication()->input->post;
        
        
        //$captcha_input = JFactory::getApplication()->input->getString('g-recaptcha-response');
        
//        $is_valid = JDispatcher::getInstance()->trigger('onCheckAnswer');//$post['recaptcha_response_field']
//        $is_valid = JEventDispatcher::getInstance()->trigger('onCheckAnswer');//$post['recaptcha_response_field']
//        $is_valid = JFactory::getApplication()->triggerEvent('onCheckAnswer');
        
//        $is_valid = $plugin->update(['event'=>'onCheckAnswer']);
        
//toPrint($is_valid,'$is_valid',0,'pre',true);
        
                
        
        return (bool) $is_valid;
    }
    /**
     * html attribute for html element captcha/ Строка атрибутоов для html элементов капчи
     * @return object Objec plugin captcha width 'atrubutes' properties string html attribute / объект плагина капчи с 'atrubutes' свойство Строка html атрибутоов 
     */
    public static function captcha_element_attribute($mod_id, $class='') {
        
        $captcha_type = JFactory::getApplication()->getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0
        if(empty($captcha_type)|| $captcha_type == "0")
            return null;
        $plugin = JPluginHelper::getPlugin('captcha', $captcha_type);
        
    //JFactory::getApplication()->triggerEvent('onInit',["dynamic_captcha_$module->id"]);//будет		
        
        if(empty($plugin))
            return null;
        $captcha_id = "dynamic_captcha_$mod_id";
        $invisible = $captcha_type == 'recaptcha_invisible';
        
        $default = ['public_key'=>'','badge'=>'inline','theme2'=>'light','size'=>'normal','tabindex'=>'0','callback'=>'','expired_callback'=>'','error_callback'=>'',];
        
		$params = new Reg($default);
		$params->set('class', $params->get('class', " $class g-recaptcha ".(in_array($captcha_type,['recaptcha','recaptcha_invisible']))));
        $params->loadString($plugin->params);
        $param = &$params;
        $param->attributes = '';
        
//echo '<pre style"color: green;">'. count([]).'----'. strlen($atributes).'------'.print_r($param,true).'</pre>';//return'';
		
//		$param->class .= " $class g-recaptcha ".(in_array($captcha_type,['recaptcha','recaptcha_invisible']));
		//$param->attributes .= " class=' $class g-recaptcha'";
        $param->attributes .= " data-sitekey='$param->public_key'";
        $param->attributes .= " data-badge='$param->badge'";
        $param->attributes .= " data-theme='$param->theme2'";
        $param->attributes .= " data-tabindex='$param->tabindex'";
        $param->attributes .= " data-recaptcha-widget-id='widget_captcha_$mod_id'";
        if($param->callback)
            $param->attributes .= " data-x-callback='$param->callback'"; 
        if($param->expired_callback)
            $param->attributes .= " data-x-expired-callback='$param->expired_callback'"; 
        if($param->error_callback)
            $param->attributes .= " data-x-error-callback='$param->error_callback'"; 
        $param->attributes .= " data-size='". ($invisible?'invisible':$param->size)."'";//compact, normal
        
//        toPrint($param,'$param',0,'pre',true);
//echo '<pre style"color: green;">'. count([]).'----'. strlen($param->atrubute).'------'.print_r($param,true).'</pre>';//return'';
        
//        JDocument::getInstance()->addScriptDeclaration("console.log('🚀 Captcha_type-$mod_id:',$captcha_type)");

//        echo "<script type='text/javascript' async-defer> ";
//        echo "function captcha$mod_id(){ ";
////        if($invisible)
////            echo "grecaptcha.render('submit_$mod_id'); \n";//,{sitekey: \"$param->public_key\" }
////        else
//            echo "grecaptcha.render('dynamic_captcha_$mod_id'); \n";//,{sitekey: \"$param->public_key\" }
////        echo "grecaptcha.execute(); ";
//        echo  "console.log('BambarBia CRGUDU ?!?!?!?!?!?!?!');}";
//        echo "</script>";
        
//        $captcha_id = "dynamic_captcha_$module->id";
////        JPluginHelper::importPlugin('captcha'); 
//        JPluginHelper::importPlugin('captcha', $captcha_type, true);
//        $post = JFactory::getApplication()->input->post;
//        
//        $plugin = JPluginHelper::getPlugin('captcha', $captcha_type);
//        $plugin->params->get('public_key', '');
        
//        if(empty(isset($param->captcha)))
//            return TRUE;
                
//        $captcha = $param->captcha;
        //id: dynamic_captcha_$module->id
                
//        JPluginHelper::importPlugin('captcha'); 
//        $post = JFactory::getApplication()->input->post;
//        $captcha = JEventDispatcher::getInstance()->trigger('onCheckAnswer',$post['recaptcha_response_field']); 
        return $param;
    }
	
	public static function isJ4() {
        return JVersion::MAJOR_VERSION > 3; 
	}
    
//    public static $debugs = [];
    public static function event(...$arg){
		return;
        $file =         JPATH_ROOT. '/events.txt';
        
//        toPrint(JPATH_ROOT. '/events.txt','Event $file',0,'pre');
        
        $data = print_r($arg,true);
        $data .= "\n\n\n\n"; 
        $data .= "============================================================\n"; 
        $data .= "============================================================\n"; 
        $data .= "\n\n\n\n"; 
        file_put_contents($file, 'Helper:event() '. $data, FILE_APPEND | LOCK_EX);
        
//        static::$debugs[] = $arg;
//        toPrint($arg,'Event Module',0,'pre');
//        toPrint(static::$debugs,'static::$debugs',0,'pre');
    }
    public  function onBeforeRender($arg){
//        toPrint($arg,'Event Module',0,'pre');
    }
    public  function onPrepareContent($arg){
//        toPrint($arg,'Event Module',0,'pre');
    }

	static $error = '';
	
	public static function debugPRE($obj = null, $title = '', $_modeAjaxApi = ''){
		static $modeAjaxApi;
		
		if($_modeAjaxApi)
			$modeAjaxApi = $_modeAjaxApi;
		
		if($obj === null && $title === '')
			return;
		
		if(! in_array($modeAjaxApi, [OptionField::MODE_SUBMIT]))
			return;
		
		if($title)
		switch (true){
			case($obj === null):
				echo "<fieldset><legend>$title </legend> NULL</fieldset>";
				break;
		
			case(is_object($obj)):
				echo "<details><summary>$title</summary><pre>".get_class($obj) .' '. print_r($obj, true)."</pre></details>";
				break;
		
			case(is_array($obj)):
				echo "<details><summary>$title</summary><pre>ARRAY(".count($obj) . ') ' . print_r($obj, true)."</pre></details>";
				break;
		
			case($obj === ''):
				echo "<fieldset><legend>$title</legend>STRING ''</fieldset>";
				break;
			case($obj === 0):
				echo "<fieldset><legend>$title</legend>INT 0</fieldset>";
				break;
			case($obj === false):
				echo "<fieldset><legend>$title</legend>BOOL FALSE</fieldset>";
				break;
			case($obj === true):
				echo "<fieldset><legend>$title</legend>BOOL TRUE</fieldset>";
				break;
			case(is_scalar($obj)):
				echo "<fieldset><legend>$title</legend><pre>". strtoupper(gettype($obj))." $obj</pre></fieldset>";
				break;
			default :
				echo "<details><summary>$title</summary><pre>".strtoupper(gettype($obj)) .' '. print_r($obj, true)."</pre></details>";
				break;
		}
	
		else
		switch (true){
			case($obj === '<hr>'):
				echo "<hr>";
				break;
			case($obj === null):
				echo "<fieldset><legend>NULL</legend></fieldset>";
				break;
		
			case(is_object($obj)):
				echo "<details><summary>".get_class($obj) ."</summary><pre>". print_r($obj, true)."</pre></details>";
				break;
		
			case(is_array($obj)):
				echo "<details><summary>ARRAY(".count($obj) . ")</summary><pre>" . print_r($obj, true)."</pre></details>";
				break;
		
			case($obj === ''):
				echo "<fieldset><legend>STRING ''</legend></fieldset>";
				break;
			case($obj === 0):
				echo "<fieldset><legend>INT 0</legend></fieldset>";
				break;
			case($obj === false):
				echo "<fieldset><legend>BOOL FALSE</legend></fieldset>";
				break;
			case($obj === true):
				echo "<fieldset><legend>BOOL TRUE</legend></fieldset>";
				break;
			case(is_scalar($obj)):
				echo "<fieldset><legend>". strtoupper(gettype($obj))."</legend><pre> $obj</pre></fieldset>";
				break;
			default :
				echo "<details><summary>".strtoupper(gettype($obj)) ."</summary><pre> ". print_r($obj, true)."</pre></details>";
				break;
		}
		
		
		

//$d = "\n\n===".date('YY-m-d h:i:s', time())."=============== $title \n". gettype($obj).' ';
//$d .= print_r($obj, true);
//$d .= "=================== \n";
//file_put_contents(__DIR__ . '/debugPre.txt', $d, FILE_APPEND);
	}
	
	
	//------------------Альтернативный способ---------------------------------------
	//BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models/', 'ContentModel');
	//Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/tables/');
	//------------------------------------------------------------------------------
	/*
	 * modMultiFormArticleAddSet
	 */
	static function articleSave($id = 0, $title = '', $introtext = '', $catid = 2, $published = 0, $note = '', $fulltext = '', $metadata = '{}'){

		JFactory::getApplication()->input->set('id',0);
		JFactory::getApplication()->input->set('task','save2new'); //->set('task', 'save');
		$data = [];
		$data['catid']		= $catid;					//$param->article_in_category;
		$data['state']		= $published;				//$param->article_published;// 1-Published, 0-Unpublished, 2-Archive, -2-Trash
		$data['title']		= $title;					//$param->subjectofmail;
		if($id)
		$data['id']			= $id;
		$data['access']		= 1;
//		$data['alias']		= null;
			$data['alias']		= '';
		$data['language']	= '*';
//		$data['articletext']= $introtext;				//$bodymail;
		$data['introtext']	= $introtext;				//$bodymail;
		$data['fulltext']	= $fulltext;				//$bodymail;
		$data['language']	= '*';												// ;
		$data['access']		= JFactory::getApplication()->get('access', 1);												// ;
		$data['created_by'] = null;
		$data['note']		= $note;
		$data['metadata']	= $metadata;
//file_put_contents(__DIR__.'/artSave.txt', __LINE__.": helper.php===== articleSaveNew() \$data: ".print_r($data,true)."  \n" , FILE_APPEND);

		$contentPath = JPATH_ADMINISTRATOR . '/components/com_content';
		JForm::addFormPath($contentPath . '/models/forms');
		JForm::addFormPath($contentPath . '/model/form');
		JForm::addFieldPath($contentPath . '/models/fields');
		JForm::addFieldPath($contentPath . '/model/field');
		JForm::addFormPath($contentPath . '/forms');
		
	//\Joomla\CMS\MVC\Model\AdminModel::getInstance($type);
		$state = new JRegistry(['article.id'=>0,'content.id'=>0]);
		JModelLegacy::addIncludePath(JPATH_BASE. "/administrator/components/com_content/models", 'ContentModel');


	//	$mvcFactory = JFactory::getApplication()->bootComponent('com_content')->getMVCFactory();
	//	$model      = $mvcFactory->createModel('Article', 'Administrator', ['ignore_request' => true]);
	//	$form = $model->getForm($article, false);
	//	if (!$form)								throw new \RuntimeException('Error getting form: ' . $model->getError());
	//	if (!$model->validate($form, $article))	throw new \RuntimeException('Error validating article: ' . $model->getError());
	//	if (!$model->save($article))			throw new \RuntimeException('Error saving article: ' . $model->getError());
	//	$item = $model->getItem();
	//	return [$item->id, $item->alias];

		$model = JModelLegacy::getInstance('Article', 'ContentModel',['ignore_request' => true,'state'=>$state]);
		$result = $model->save($data); //, array( ,''=>'id' )
		
		if($id == 0){
			$id = $model->getItem()->id;
			
//			$model = JModelLegacy::getInstance('Article', 'ContentModel',['ignore_request' => true,'state'=>$state]);
			$data = [];
			$data['id']		= $id;
			$data['title']	= $title . ' ' . $id . ' ';
			$data['catid']	= $catid;
			$result = $model->save($data); //, array( ,''=>'id' )
		}
		
		return $id;//$model->getItem()->id; //[$item->id, $item->alias];
	}
	/**
	 * 
	 * @param INT $id
	 * @param INT $article_in_category
	 * @param array $data [catid, state, title, access, alias, language, introtext, fulltext, language, access, created_by, note, metadata, , , ]
	 * @return type
	 */
	static function articleSaveExist($id = 0, $article_in_category = 2, $data = []){

		$contentPath = JPATH_ADMINISTRATOR . '/components/com_content';
		JForm::addFormPath($contentPath . '/models/forms');
		JForm::addFormPath($contentPath . '/model/form');
		JForm::addFieldPath($contentPath . '/models/fields');
		JForm::addFieldPath($contentPath . '/model/field');
		JForm::addFormPath($contentPath . '/forms');
		
		
		$data['catid']		= (int)$article_in_category;
		
		
		$data = (array) $data;
		
		if($id){
			$data['id']	= $id;
		}else{
			
			 //['apply', 'save', 'save2new']
			JFactory::getApplication()->input->set('task','save'); //->set('task', 'save');
			JFactory::getApplication()->input->set('id',0);
		}
			
		
		if( ! $id && empty($data['access'])){
			$data['access']	= JFactory::getApplication()->get('access', 1);
		}
		
		if( ! $id && empty($data['language']))
			$data['language']	= '*';
		
		if( ! $id && empty($data['state']))
			$data['state']	= 0;
		
		if( ! $id && empty($data['alias'])){
			JFactory::getApplication()->input->set('task', 'save');
			$data['alias'] = '';
		}
		
		if( ! $id && empty($data['fulltext'])){
			$data['fulltext'] = '';
		}
		
		
		JModelLegacy::addIncludePath(JPATH_BASE. "/administrator/components/com_content/models", 'ContentModel');
		JModelLegacy::addIncludePath(JPATH_BASE. "/administrator/components/com_content/src/models", 'ContentModel');


//		$state = new JRegistry(['article.id'=>0,'content.id'=>0]);
//		$model = JModelLegacy::getInstance('Article', 'ContentModel', ['ignore_request' => true, 'state'=>$state]);
		
		$model = JFactory::getApplication()->bootComponent('com_content')
			->getMVCFactory()->createModel('Article', 'Administrator', ['ignore_request' => true]);

	
		
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';


	$final = $model->save($data);
//file_put_contents($fDeb, __LINE__.": helper.php===== articleSaveExist($id) \$final:$final	 
//\article:MetaData	\$data:\n".print_r($data['metadata'],true)." :".print_r($model->getError(),true)." \n" , FILE_APPEND);
		if (!$final){
//file_put_contents($fDeb, __LINE__.": helper.php===== articleSaveExist($id) \$art: ".print_r($model->getError(),true)."  \n" , FILE_APPEND);
			throw new \RuntimeException('Error saving article: ' . $model->getError());
		}
//file_put_contents($fDeb, __LINE__.": helper.php===== articleSaveExist($id) \$final:$final	\$data:".print_r($model->getError(),true)."  \n" , FILE_APPEND);
	
	
		if($id == 0){
			
			$art = $model->getItem();

			$id = $art->id;
			
			$data['id']		= $id;
			$data['title']	= $art->title . ' ' . $id . ' ';
			$data['catid']	= $art->catid ?? 2;
			$result = $model->save($data); //, array( ,''=>'id' )
		}

		return $model->getItem()->id; //[$item->id, $item->alias];
	}
	
	static function redirect($moduleID, $orderID, $message = ''){
			
        $config	= JFactory::getApplication()->getConfig()->toObject();
		JFactory::getApplication()->enqueueMessage($message,'notice');//notice;info
			
		$urlReload = JUri::root();
		$urlReload .= "?option=com_ajax&module=multi_form&format=raw&id={$param->id}&order=$orderID";
		$urlReload .= "&pass=" . md5($config->secret . $param->id . $orderID);
		JFactory::getApplication()->redirect(JRoute::_($urlReload,true));
	}
	static function redirectLink($moduleID, $orderID){
			
        $config	= JFactory::getApplication()->getConfig()->toObject();
		$urlReload = JUri::root();
		$urlReload .= "?option=com_ajax&module=multi_form&format=raw&id={$moduleID}&order=$orderID";
		$urlReload .= "&pass=" . md5($config->secret . $moduleID . $orderID);
		return $urlReload;
	}
}


