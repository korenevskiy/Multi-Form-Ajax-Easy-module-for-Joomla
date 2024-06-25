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

		
use Joomla\CMS\Form\Field\ListField as JFormFieldList;
use \Joomla\CMS\Form\FormField as JFormField; 

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

class modMultiFormHelper {
    public static function constructor($param = []) {
        //static $path_base;
        if(isset(static::$min))
            return;
//        JFactory::getApplication()->getConfig()->get('error_reporting','default')
		//$param->debug != 'debug' && 
        static::$min = ! JDEBUG && in_array(JFactory::getConfig()->get('error_reporting','default'), ['default','none','']) ? '.min' : ''; // default, none, simple, maximum, development
//        static::$min = ! JDEBUG;
        
        
//        echo "<pre>".JFactory::getConfig()->get('error_reporting','default')."</pre>";
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
    {   if(empty($pk))
             return '';
        
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
                $paramsfield = substr($id, 0, $lenght); 
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

        /**
         * Формируем поля
         * @param object $allparams  Параметры полей из конфигурации модуля XML
         * @param int $moduleid  ИД модуля
         * @param bool $labelOut вывод полей в группах с описаниями 
         * @param bool $style_field Класс для порядка названий с полями
         * @return array массив полей 
         */
    public static function buildFields($allparams, $moduleid, $labelOut, $style_field){//формируем поля для вывода на странице
        $fieldbuiding = array();
        $poryadokF = 0;
        
//        echo "<pre>allparams <br>". print_r($allparams, true). "</pre>";
//        echo "<pre>allparams <br>". print_r(JFactory::getApplication()->input, true). "</pre>";
//return;		


//        toPrint($allparams,'$allparams') ;
        $select_editor = $allparams->select_editor ?: 'tinymce' ;
        
        
		if(empty($allparams))
			$allparams = new stdClass;
		
//        empty($allparams->namefield) && $allparams->namefield = [];
//if(static::$debug){
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
//echo "<pre>". print_r($allparams, true). "</pre>";
//return [];

//toPrint();
//toPrint($i,'$i',0,'pre',true); 
//toPrint($allparams,'$allparams',0,'pre',true); 
//toPrint($allparams->typefield,'$allparams->typefield',0,'pre',true); 
//toPrint($allparams->art_id,'$allparams->art_id',0,'pre',true); 
//toPrint($list_fields,'$layout',0,'pre',true); 
	
//}
//echo "<pre>". print_r($allparams, true). "</pre>";
        foreach($allparams->namefield ?: [] as $i => $namefield){

            $namefield      = $allparams->namefield[$i]		?? $namefield;
            $nameforpost    = $allparams->nameforpost[$i]	?? $namefield;
            $typefield      = $allparams->typefield[$i]		?? 'text';
            $paramsfield    = $allparams->paramsfield[$i]	?? '';
            $art_id         = $allparams->art_id[$i]		?? 0; 
            $onoff          = $allparams->onoff[$i]			?? 1;
            $required		= $onoff == 2;
//if(static::$debug){
//toPrint();
//toPrint($i,'$i',0,'pre',true); 
//toPrint($typefield,'$typefield',0,'pre',true); 
//	
//}
    //        toPrint(null, "$typefield $required: $namefield",0, TRUE, TRUE);

            $reqstar = $regstartag = $requiredField = '';

            if($required){
                    $requiredField = "required ";
                    $reqstar = "*";
                    $regstartag = "<span class='required'>*</span>";
            }

            $nameforfield 	= $typefield.$i.$moduleid;
//			$regstartag .= " $nameforfield ";

            $valueforfield = JFactory::getApplication()->input->getString($nameforfield, '');

//$regstartag .= " $typefield $nameforfield -  $valueforfield ";
            $intro = "$art_id";

            if(empty($onoff)) 
                continue;

    //        if($paramsfield && in_array($typefield, ['text','textarea','string','editor','legend']))
    //            $intro = "<intro>$paramsfield</intro>";

            if($art_id){ //$paramsfield && in_array($typefield, ["article","text_art","textarea_art","editor_art"])

                $intro = static::getArticles($art_id);

                if($intro)
                    $intro = "<intro>$intro</intro>";
            }
//                        toPrint($nameforfield,'$nameforfield');

//toPrint($i,'$i',0,'pre',true); 
//toPrint($art_id,'$art_id',0,'pre',true);
//toPrint($intro,'$intro',0,'pre',true); 
    switch($typefield){
		
        case "": 
            if(empty($namefield))
                $fieldB = $intro;
            elseif($paramsfield)
                $fieldB = "<$paramsfield id='$nameforfield' class='legend mfFieldGroup form-group $style_field' $requiredField ><label >$namefield$regstartag</label>$intro</$paramsfield>";
            else
                $fieldB = "<div id='$nameforfield' class='legend mfFieldGroup form-group $style_field' $requiredField ><label  for='$nameforfield'>$namefield$regstartag</label>$intro</div>";
        break;    
                        
                
                    
		case "hidden":
			$fieldB = "<input id='$nameforfield' class='hidden input' type='hidden' $requiredField name='$nameforfield' value='$valueforfield' placeholder='$namefield$regstar'>$intro";
		break;
                
    
        //$typefield
		case "password":
		case "url":
		case "text":
			if(in_array($labelOut, ['edge','above',1]) ){
                                $fieldB = "<div class='form-group mfFieldGroup $typefield $style_field'><label for='$nameforfield' class='text'>$namefield$regstartag</label>";
				$fieldB .= "<input id='$nameforfield' class='form-control input  $typefield' type='$typefield' $requiredField name='$nameforfield' value='$valueforfield' >$intro";
				$fieldB .= "</div>";
			}else{
                                $fieldB = "<input id='$nameforfield' class='form-control input  $typefield' type='$typefield' $requiredField name='$nameforfield' value='$valueforfield' placeholder='$namefield$reqstar'>$intro";
			}
		break;
                
		case "textarea":                    
			if(in_array($labelOut, ['edge','above',1])){
                                $fieldB = "<div class='form-group mfFieldGroup textarea $style_field'><label for='$nameforfield' class='textarea'>$namefield$regstartag</label>";
				$fieldB .= "<textarea id='$nameforfield' type='textarea' class='form-control input textarea' $requiredField name='$nameforfield' value='' rows='5' cols='45'>$valueforfield</textarea>$intro";
				$fieldB .= "</div>";
			}else{
				$fieldB = "<textarea id='$nameforfield' type='textarea' class='form-control textarea input' $requiredField name='$nameforfield' value='' placeholder='$namefield$reqstar' rows='5' cols='45'>$valueforfield</textarea>$intro";
			}
		break; 
		case "editor":
            $paramsEditor = [];// ['advlist'=>'1','syntax'=>'css','height'=>200,'width'=>100,'tabmode'=>'shift','linenumbers'=>1,];
            $paramsEditor = ['contextmenu'=>FALSE, 'advlist'=>'1','syntax'=>'css','tabmode'=>'shift','linenumbers'=>1, 'class'=>'form-control mfEditor input editor','joomlaExtButtons'=>false];//'width'=>450,'height'=>200,
            jimport( 'joomla.html.editor' );
//                    $editor = JEditor::getInstance('tinymce');
//                     toPrint($editor,'Editor') ;
                //arkeditor, tinymce,  codemirror none   
            $fieldB = JEditor::getInstance($select_editor)->display($nameforfield, $valueforfield/*$namefield.$reqstar*/, '100%', 'auto', 10, 4, TRUE, $nameforfield, NULL, NULL,$paramsEditor);
            $fieldB .= " $intro";
			if(in_array($labelOut, ['edge','above',1])){
                $fieldB = "<div class='form-group mfFieldGroup editor mfEditor $style_field'>"
                    ."<label for='$nameforfield' class='editor'>$namefield$regstartag</label>"
                    ."$fieldB </div>"; 
            }
		break;
			
		case "tel":
            $mask = $paramsfield?:'+999(999) 999-9999';
			if(in_array($labelOut, ['edge','above',1])){
                $fieldB = "<div class='form-group mfFieldGroup tel $style_field'><label for='$nameforfield'>$namefield$regstartag</label>";
                $fieldB .= "<input id='$nameforfield' name='$nameforfield' value='$valueforfield' type='tel' class='form-control input tel' $requiredField  data-inputmask=\"'mask': '$mask'\" _pattern=\"\+?[0-9]{1,3}-[0-9]{3}\s[0-9]{3}-[0-9]{4}\" inputmode=\"tel\">"; //data-inputmask='\"mask\": \"$mask\"'
                $fieldB .= " $intro</div>";
            }else{
                $fieldB = "<input id='$nameforfield' name='$nameforfield' value='$valueforfield' type='tel' placeholder='$namefield$reqstar' class='form-control input tel' data-allready='0' $requiredField  data-inputmask=\"'mask': '$mask'\" _pattern=\"\+?[0-9]{1,3}-[0-9]{3}\s[0-9]{3}-[0-9]{4}\" inputmode=\"tel\">"; //  data-inputmask='\"mask\": \"$mask\"'
                $fieldB .= " $intro";
            }
         
            $script = "jQuery(function($){";
            if(in_array($labelOut, ['in',0])){
                $script .= " $('#$nameforfield').mask('$mask'); ";
            }
            $script .= "$('#$nameforfield').inputmask({";
                        //$script .= "'mask': '$mask',"; 
			$script .= "'oncomplete': function(){ $('#$nameforfield').attr('data-allready', '1'); }," ;
			$script .= "'onincomplete': function(){ $('#$nameforfield').attr('data-allready', '0'); },";
			$script .= "});"; 
			$script .= "});"; 
                        
                        JFactory::getDocument()->addScriptDeclaration($script);
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
			
		case "email":
            if(in_array($labelOut, ['edge','above',1])){
                $fieldB = "<div class='form-group mfFieldGroup email $style_field'><label for='$nameforfield'>$namefield$regstartag</label>";
                $fieldB .= "<input id='$nameforfield' class='form-control input email' type='email' $requiredField name='$nameforfield' value='$valueforfield' >";
                $fieldB .= "$intro</div>";
            }else{
                $fieldB = "<input id='$nameforfield' class='form-control input email' type='email' $requiredField name='$nameforfield' value='$valueforfield' placeholder='$namefield$reqstar'>$intro";
            }
		break;
          
        //$typefield
		case "range":
		case "number":
//$regstartag .= " $typefield $nameforfield -  $valueforfield ";
            $paramsf = explode('-', $paramsfield);
            $min = isset($paramsf[0]) ? (int)trim($paramsf[0]) : '';
            $max = isset($paramsf[1]) ? (int)trim($paramsf[1]) : '';
			$valueforfield = $valueforfield == '' ? (int)$min : (int)$valueforfield;
//$regstartag .= "$paramsfield - $min/$max -  $valueforfield $ <pre>".$max.'</pre> ' ;
//$dd = "<pre>xxx $min $valueforfield</pre>";
            $min =  " min='$min' ";
            $max = $max ? " max='$max' " : '';
            if(in_array($labelOut, ['edge','above',1])){
                $fieldB = "<div class='form-group mfFieldGroup $typefield $style_field'><label for='$nameforfield'>$namefield$regstartag</label>";
				$fieldB .= "<input id='$nameforfield' $min $max class='form-control input  $typefield' type='$typefield' $requiredField name='$nameforfield' value='$valueforfield' >$intro";
				$fieldB .= "</div>";
			}else{
                $fieldB = "<input id='$nameforfield' $min $max class='form-control input  $typefield' type='$typefield' $requiredField name='$nameforfield' value='$valueforfield' placeholder='$namefield$reqstar'>$intro";
			}
		break;
        
        

        
		case "time":
		case "date":
		case "datetime":
		case "datetime-local":
		case "week":
		case "month":
			$multiple = $typefield == "files" ? 'multiple':'';
			$arr = $typefield == "files" ? '[]':'';
			$valueforfield = $valueforfield ?: \Joomla\CMS\Date\Date::getInstance()->format('Y-m-d');			
//			value="2017-06-01";  d-m-Y
			
            if(in_array($labelOut, ['edge','above',1])){
                $fieldB = "<div class='form-group mfFieldGroup $typefield $style_field'><label for='$nameforfield'>$namefield$regstartag</label>";
                $fieldB .= "<input id='$nameforfield' src='' class='form-control input $typefield' type='$typefield' $multiple $requiredField name='$nameforfield$arr' title='$namefield$reqstar' value='$valueforfield' >";
                $fieldB .= "$intro</div>";
                    }else{
                        $fieldB = "<input id='$nameforfield' src='' class='form-control input $typefield' type='$typefield' $multiple $requiredField name='$nameforfield$arr' value='$valueforfield' placeholder='$namefield$reqstar' title='$namefield$reqstar'>$intro";
                    }
		break;
//time
//date
//datetime
//datetime-local
//week
//month
//image

        
		case "image":
			$multiple = $typefield == "files" ? 'multiple':'';
			$arr = $typefield == "files" ? '[]':'';
			
            if(in_array($labelOut, ['edge','above',1])){
                $fieldB = "<div class='form-group mfFieldGroup image $style_field'><label for='$nameforfield'>$namefield$regstartag</label>";
                $fieldB .= "<input id='$nameforfield' src='' class='form-control input image' type='image' $multiple $requiredField name='$nameforfield$arr' title='$namefield$reqstar' value='$valueforfield' >";
                $fieldB .= "$intro</div>";
                    }else{
                        $fieldB = "<input id='$nameforfield' src='' class='form-control input image' type='image' $multiple $requiredField name='$nameforfield$arr' value='$valueforfield' placeholder='$namefield$reqstar' title='$namefield$reqstar'>$intro";
                    }
		break;
                
		case "file":
		case "files":
                    $multiple = $typefield == "files" ? 'multiple':'';
                    $arr = $typefield == "files" ? '[]':'';
			if(in_array($labelOut, ['edge','above',1])){
                $fieldB = "<div class='form-group mfFieldGroup file $style_field'><label for='$nameforfield'>$namefield$regstartag</label>";
                $fieldB .= "<input id='$nameforfield' class='form-control input file' type='file' $multiple $requiredField name='$nameforfield$arr' title='$namefield$reqstar' value='$valueforfield' >";
                $fieldB .= "$intro</div>";
                    }else{
                        $fieldB = "<input id='$nameforfield' class='form-control input file' type='file' $multiple $requiredField name='$nameforfield$arr' value='$valueforfield' placeholder='$namefield$reqstar' title='$namefield$reqstar'>$intro";
                    }
		break;
			
		case "select":
			$fieldB = '';
//			$selects = explode(";", $paramsfield);
			$selects = (array)explode("\n", $paramsfield);

			if(in_array($labelOut, ['edge','above',1]))
				$fieldB .= "<div class='form-group mfFieldGroup select $style_field'><label for='$nameforfield'>$namefield$regstartag</label>";
			
            $fieldB .= "<select  class='form-control input select' id='$nameforfield' name='$nameforfield' $requiredField>";
			
			if(in_array($labelOut, ['in',0]))
				$fieldB .= "<option disabled selected>$namefield$regstartag</option>";
			
			foreach($selects as $y =>  $sel ){
				$sel = trim($sel);
				if(empty($sel))
					continue;
				$s = ($valueforfield==$sel)?' selected ':'';
				$fieldB .= "<option value='$sel' $s >$sel</option>";
			}
			$fieldB .= "</select>$intro";
			
			if(in_array($labelOut, ['edge','above',1]))
				$fieldB .= "</div>";
		break;
					
		case "radio":
            $fieldB = '';
//			   $radios = explode(";", $paramsfield);
            $selects = (array)explode("\n", $paramsfield);
			if(in_array($labelOut, ['edge','above',1]))
                $fieldB .= "<div class='mfFieldGroup radio $style_field'><label class='radio'>$namefield$regstartag</label>"; 
                
            $fieldB .= "<div class='form-group input rad'>";
            foreach($selects as $y => $rad ){
                $rad = trim($rad);
				if(empty($rad))
					continue;
                $check = ($valueforfield==$rad)?' checked ':''; 
//                $fieldB .= "<div class='form-group input rad '>";
                $fieldB .= "<label for='$nameforfield.$y'  class='rad'>";
                $fieldB .= "<input id='$nameforfield.$y' type='radio' $requiredField name='$nameforfield' value='$rad' $check class='form-control form-check-input radio '>";
                $fieldB .= "$rad</label>"; 
//                $fieldB .= "</div>";
            }
            $fieldB .= "<br>$intro"; 
            $fieldB .= "</div>";
			if(in_array($labelOut, ['edge','above',1]))
                $fieldB .= "</div>";
		break;
					
		case "checkbox":
            $fieldB = '';
//						$checkboxes = explode(";", $paramsfield);
            $selects = array_filter(explode("\n", $paramsfield));
			if(in_array($labelOut, ['edge','above',1])){
                $fieldB .= "<div class='mfFieldGroup checkbox $style_field'>";
				if($selects)
					$fieldB .= "<label class='checkbox'>$namefield$regstartag</label>";
			}
//$fieldB = "<fieldset for='$nameforfield' class='check'><legend> </legend></fieldset>";
//toPrint($selects,'$selects',0,'pre',true); 
			$count = count($selects);
            $fieldB .= "<div class='form-group input chk opts$count '>";
			
			if(empty($selects)){
				$chk = ($valueforfield)?' checked ':'';
			
                $fieldB .= "<input id='$nameforfield.$i' type='checkbox'  name='$nameforfield' value='✓' $chk $requiredField  class='form-check form-check-input checkbox '>";
                $fieldB .= "<label for='$nameforfield.$i' class='check chk '>$namefield</label>";
			}
				
            foreach($selects as $y => $check ){
                $check = trim($check);
				if(empty($check))
					continue;
                $chk = ($valueforfield == $check)?' checked ':'';
                if(is_array($valueforfield))
                    $chk = (in_array($check,$valueforfield))?' checked ':'';
                $fieldB .= "<input id='$nameforfield.$y' type='checkbox'  $requiredField name='$nameforfield' value='$check' $chk  class='form-check form-check-input checkbox '>";
                $fieldB .= "<label for='$nameforfield.$y' class='check chk '>$check</label>";
            }
            $fieldB .= "</div>";
            $fieldB .= "$intro";
			if(in_array($labelOut, ['edge','above',1]))
                $fieldB .= "</div>";
		break;
					
		case "color":
                    $fieldB = '';
                    $col = $valueforfield ?: "#424242";
			if(in_array($labelOut, ['edge','above',1])){
                        $fieldB .= "<div class='mfFieldGroup color $style_field'><label for='$nameforfield' class='color '>$namefield$regstartag</label>";                    
                    }
				$fieldB .= "<div class='form-group input col'>";
				$fieldB .= "<input id='$nameforfield'  class='form-control  color ' type='color' value='$col'  name='$nameforfield' placeholder='$namefield$reqstar'>"; 
                                            
			if(in_array($labelOut, ['in',0]))
					$fieldB .= "<label for='$nameforfield' class='col '>$namefield$regstartag</label>"; 
				$fieldB .= "<br>$intro"; 
				$fieldB .= "</div>";
                                            
			if(in_array($labelOut, ['edge','above',1])){
                        $fieldB .= "</div>";
                    }                                
		break;
					
		case "_separate":
		case "separate":
                    $fieldB = "<hr id='$namefield' class='separate' />$intro";
		break;
					
		case "_htmltagstart": 
		case "htmltagstart": 
                    if($namefield){
                        $fieldB = "<$namefield class='$nameforpost'>$intro";
                    }else{
			$fieldB = "<div class='$nameforpost'>$intro";
                    }
		break;
					
		case "_htmltagfinish": 
		case "htmltagfinish": 
            if($namefield){
			$fieldB = "$intro</$namefield>";
                    }else{
			$fieldB = "$intro</div>";
                    }
                break;
			}
            $fieldbuiding[$poryadokF] = array( "dataField"=>$fieldB, "type"=>$typefield, "id"=>$nameforfield, "title"=>$namefield, "require"=>$reqstar, "intro"=>$intro);  
			
            $poryadokF++;
//			toLog($fieldB,'$fieldB','/tmp/multiform.txt');
        }
                
//		foreach ($fieldbuiding as $key => $row) {
//				$sort[$key]  = $row['sort'];
//				$dataField[$key] = $row['dataField'];
//			}
//		array_multisort($sort, SORT_NUMERIC, $dataField, SORT_STRING, $fieldbuiding);
                
                //toPrint($fieldbuiding,'Sorting',0) ;
                
        $fields = $fieldbuiding;// Joomla\Utilities\ArrayHelper::sortObjects($fieldbuiding, 'sort'); 
                
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
                if(empty($allparams) || empty($allparams->namefield)){
                    return $fieldGetVal;
                }
                
		foreach($allparams->namefield as $i => $namefield){
			
			$namefield 		= $allparams->namefield[$i];
			$typefield 		= $allparams->typefield[$i];
			$paramsfield 	= $allparams->paramsfield[$i];
			//$sortnumber 	= $allparams->sortnumber[$i];
			$onoff 			= $allparams->onoff[$i];
			
			$nameforfield 	= $typefield.$i.$moduleid;
                        
                        $fieldGet = '';
//                toPrint($typefield,'$typefield',0);
//                         toPrint($typefield,'$onoff->'.$onoff,0);   
			
			if($onoff && !in_array($typefield, ['separate','htmltagstart','htmltagfinish','']) && substr($typefield, 0,1)!='_'):
			
//                         toPrint($typefield,'$typefield',0);   
                            switch($typefield){
                                 
                                
                                                                
                            
                            
				case "hidden":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]').val();\n";
				break;
				
				case "text":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]').val();\n";
				break;
				
				case "textarea":
				$fieldGet = "var $nameforfield = $('textarea[name=$nameforfield]').val();\n";
				break;
                            
                                case "editor": 
				$fieldGet = "var $nameforfield = $('textarea[name=$nameforfield]').val();\n";
				break;
				
				case "email":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]').val();\n";
				break;
                            
				case "file":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]')[0].files;\n";
				break;
                            
				case "files":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]')[0].files;\n";
				break;
				
				case "telephone":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]').val();\n";
				break;
				
				case "select":
				$fieldGet = "var $nameforfield = $('select[name=$nameforfield] option:selected').val();\n";
				break;
				
				case "radio":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]:checked').val();\n";
				break;
				
				case "checkbox":				
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]:checked').map( function() {\n";
				$fieldGet .= "return this.value;\n";
				$fieldGet .= "}).get().join(',');\n";
				break;
				
				case "color":
				$fieldGet = "var $nameforfield = $('input[name=$nameforfield]').val();\n";
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
                if(empty($allparams) || empty($allparams->namefield)){
                    return $ajaxData;
                }
//                toPrint($allparams->namefield,'$allparams->namefield');
		foreach ($allparams->namefield as $i => $namefield){
			
			//$namefield 		= $allparams->namefield[$i];
			$typefield 		= $allparams->typefield[$i];
			$onoff 			= $allparams->onoff[$i];
			$nameforfield 	= $typefield.$i.$moduleid;
			
			if($onoff && !in_array($typefield, ['separate','htmltagstart','htmltagfinish','']) && substr($typefield, 0,1)!='_')  :
			$ajaxData	.= "'$nameforfield' : $nameforfield,\n";
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
                if(empty($allparams) || empty($allparams->namefield)){
                    return '';
                }
//                toPrint($allparams->namefield,'$allparams->namefield');
                $fields = [];
		foreach ($allparams->namefield as $i => $namefield){
			
			//$namefield 		= $allparams->namefield[$i];
			$typefield 		= $allparams->typefield[$i];
			$onoff 			= $allparams->onoff[$i];
			$nameforfield 	= $typefield.$i.$moduleid;
			
                    if($onoff && !in_array($typefield, ['separate','htmltagstart','htmltagfinish','']) && substr($typefield, 0,1)!='_'){
			$fields[] = '"'.$nameforfield.'"';
                    }
		}
		return implode(',', $fields);
	}
	
        /**
         * Получаем массив называний полей для формы
         * @param object $allparams Объект полей формы из параметров модуля XML
         * @param int $moduleid
         * @return array
         */
	public static function ajaxDataField($allparams, $moduleid){
		
		$allparams = (object)$allparams;
		
		$ajaxDataFields = [];
		
//                toLog($allparams,'$allparams','/tmp/multiform.txt',true, true);
//                toPrint($allparams,'$allparams',0,true,true);
//                empty($allparams) && $allparams = new stdClass;
//                empty($allparams->namefield) && $allparams->namefield = [];
//                if($allparams instanceof \Joomla\Registry\Registry){
//                    $allparams = $allparams->toObject ();
//                }
//                if($allparams->namefield && is_string($allparams->namefield)){
//                    $allparams->namefield = (new \Joomla\Registry\Registry)->loadString($allparams->namefield);
//                }
//toPrint($allparams->typefield,'$allparams->typefield',0,'pre');
//static::$error .= toPrint($allparams->typefield,'$params->typefield',0,TRUE);//"<pre>".print_r($allparams->typefield,true)."</pre>";
//static::$error .= toPrint($allparams,'$params!',0,TRUE);//"<pre>".print_r($allparams->typefield,true)."</pre>";
		
		if(empty($allparams->namefield))
			return [];
		
		
		foreach ($allparams->typefield as $i => $namefield){
			
			//$namefield 		= $allparams->namefield[$i];
			$typefield 		= $allparams->typefield[$i];
			$onoff 			= $allparams->onoff[$i];
			$nameforpost	= $allparams->nameforpost[$i];
			$nameforfield 	= $typefield.$i.$moduleid;
			
			if($onoff && $typefield != "separate" && $typefield != "htmltagstart" && $typefield != "htmltagfinish" && substr($typefield, 0,1)!='_'){
				$ajaxDataFields[] = ["nameforfield"=>$nameforfield, "nameforpost"=>$nameforpost, 'type'=>$typefield];
			}
		}
		return $ajaxDataFields;
	}
	
        /**
         * 
         * @param type $allparams
         * @param type $moduleid
         * @return string
         */
	public static function validateFieldsForm($allparams, $moduleid){
                empty($allparams) && $allparams = new stdClass;
                empty($allparams->namefield) && $allparams->namefield = [];
		$validateFieldName = "";
		$validateRules = "";
		foreach ($allparams->namefield as $i => $namefield){
			//$namefield 		= $allparams->namefield[$i];
			$typefield 		= $allparams->typefield[$i];
			$onoff 			= $allparams->onoff[$i];
			$required		= $onoff == 2 || $allparams->required[$i];
			$nameforfield 	= $typefield.$i.$moduleid;
			if($onoff and $required):
				$validateFieldName[] = $nameforfield;
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

            
        $results = [];//Массив объектов модулей для возрвата
//            $ids = [];//ID для которых нужно инициализировать объекты модулей
            
        static $mods;//Массив объектов модулей уже инициализированныъх прежде
            
        if(isset($mods)){
                foreach ($mod_ids as $i => $id){
                    if(isset($mods[$id])){
                        $results[$id] = $mods[$id];
                        unset($mod_ids[$i]);
                    }
//                    else {
//                        $ids[] = $id;
//                    }
                }
        }
        else{
//                $ids = $mod_ids;
                $mods = [];
        }
               
        if(empty($mod_ids) ){//&& empty($ids)
                return $results;
        }


//$mods = JModuleHelper::getModuleList(); 
//$mods = JModuleHelper::cleanModuleList($mods);
//echo "<pre>1095\n". print_r($mods, true). "</pre><br>\n\n";
//echo "<pre>1097\n". print_r(gettype($mods[0]->id), true). "</pre><br>\n\n"; 

            jimport('joomla.application.module.helper');
        foreach ($mod_ids as $id){
            //$id = (int)$id;
            $mod = mfModuleHelper::getModuleById($id); // JModuleHelper::getModuleById($id); 
            
//echo "<pre>1102\n". print_r($mod, true). "</pre><br>\n\n";
//echo "<pre>1103\n". print_r($id, true).':'.gettype($id). "</pre><br>\n\n";
//        echo "<pre>Проверка - ";
//        echo  print_r($id,true);
//        echo "<br>";
//        echo  print_r($mod,true); 
//        echo "</pre>";
        
//toPrint($mod,'$mod '.$id,0,'pre',true);  
        
            if($mod){
                $mod->params = new Reg($mod->params);
                $mods[$mod->id] = $mod;
                $results[$mod->id] = $mod;
            }
            
//            $mod->params = [];
//        echo "<pre>".$id.'-'. print_r($mod, true). "</pre>";
            
//        toPrint($mod,'$mod-'.$mod->id,0,'pre',true); 
//        continue;
//        $mod->params = '';//
//        toPrint($mod,'$mod-'.$mod->id,0,'pre',true); 
        }
        
        return $results;
        
        
            
            
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
                    $results[$mod->id] = $mod;
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
            return $results;
    }
    
    
    /**
     * Получает 
     * @return type
     */
    public static function getParams(){
        
		$moduleid = JFactory::getApplication()->input->getInt('id');// module id
    
	
		$moduleid = (int)$moduleid;
		$moduleDeb = JFactory::getApplication()->input->getString('deb');// module id
//echo "<script type='text/javascript'>console.log('helper id',$moduleid);</script>";
//		$moduleTitle	= JFactory::getApplication()->input->get('modtitle','','STRING'); //
//echo $moduleid.'-<br>';
//toPrint($moduleid,'$moduleid',0,'pre',true); 
//toPrint(Factory::getApplication()->input,'Factory::getApplication()->input',0,'pre',true); 

    
        
//echo "<pre>". print_r($moduleid, true). "</pre>";
        
        $modules = self::getModules($moduleid);
        
        
//echo "<pre>". print_r($modules, true). "</pre>";


        
//toPrint($modules,'$modules',0,'pre',true); 
//
//foreach($modules as $i => $mod ) {
//            $mod->params = '';
//            toPrint($mod->id,'$mod-'.$mod->id.'-'.$i,0,'pre',true); 
//}
        
        foreach ($modules as &$mod){ 
			$mod->deb = $moduleDeb;
			$mod->param = &$mod->params; //$mod->params->toObject();//$mod->params = $mod->params->toObject();
            if($mod->param->list_fields && is_string($mod->param->list_fields)){
//                $mod->param->list_fields = new Reg($mod->param->list_fields);
            }
//            if($mod->params instanceof Reg || $mod->params instanceof Registry || $mod->params instanceof Joomla\Registry\Registry || $mod->params instanceof JRegistry){
//                
//            }
        }
//toPrint($modules,'$modules',0,'pre',true); 
//return;
//        toPrint($moduleid,'$moduleid',0,'pre',true);
//        toPrint($modules,'$modules',0,'pre',true);
        return $modules;        
    }


    public static function getAjax(){
        //JSession::checkToken('get') && JFactory::getApplication()->input->getBool('show')
        //"&".JSession::getFormToken().'=1&show=1';
        
        
		$bodymail = "";
        
        $textsuccesssendAjax = '';//text1251
        
//        $input = JFactory::getApplication()->input;
//$textsuccesssendAjax .= "<pre class='message'>Factory::getApplication()->input: ".print_r($input ,TRUE)."</pre>";


        $config			= JFactory::getConfig()->toObject();
        //$sitenameForForm	= $config->sitename;
        //$mailfromForForm	= $config->mailfrom;
            
        $ar = ['cat'];
                
        
		
        $modules = self::getParams();
        
        
        
        if(empty($modules)){                    
            return;
        }
        $ar[] = 'php';
        $module = reset($modules);
		
//$bodymail .= toPrint($module->params->list_fields,'$module->params',0,TRUE);
        
		if(is_string($module->params))
			$module->params = new Reg($module->params);
		
		if(is_string($module->params->list_fields))
			$module->params->list_fields = new Reg($module->params->list_fields);
		
		$param = &$module->params;
		$params = &$module->params;
//$bodymail .= toPrint($module->params->list_fields,'$module->params',0,TRUE);
		
        $f_cat = join('.', $ar);
        $f = file_exists(__DIR__.'/fie'.'ld/'.$f_cat); 
        
//        $param->header_tag = $params->set('header_tag', $param->head_tag);
//        $param->module_tag = $params->set('module_tag', $param->mod_tag);
        
//
//        $textsuccesssendAjax .= ' Param-'.$param->captcha.' ---- ';
        $captcha_verify = TRUE;
        
//            $textsuccesssendAjax .= $deb.'<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                    . '1233  Helper: '.print_r($module,true).'</pre>';
//            $textsuccesssendAjax .= $deb.'<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                    . '1235  Helper: '.print_r($param,true).'</pre>';
            


        if($params->captcha && $config->captcha){
            $captcha_type = $config->captcha;//recaptcha, recaptcha_invisible, 0
//            
//            toPrint($captcha_type,'$captcha_type',0,'pre',true);
//            toPrint(JFactory::getApplication()->get('captcha'),'AplicationCaptcha',0,'pre',true);
//            toPrint(JFactory::getApplication()->getParams()->get('captcha'),'AplicationCaptchaParam',0,'pre',true);
//            if(empty($captcha_type)|| $captcha_type == "0")
//                return null;
//            $plugin = JPluginHelper::getPlugin('captcha', $captcha_type);
//            $textsuccesssendAjax .= $anwer= $plugin->onCheckAnswer();
            
        ///$token = $input->get('gToken','','STRING'); 
//        $captcha_input = JFactory::getApplication()->input->getString('g-recaptcha-response');  
//        JPluginHelper::importPlugin('captcha'); 
//    $textsuccesssendAjax .= $anwer = JDispatcher::getInstance()->trigger('onCheckAnswer', $captcha_input);      
//            $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  Helper: '
//                    .JFactory::getApplication()->triggerEvent('onCheckAnswer', [$captcha_input]).'</pre>';
            //$textsuccesssendAjax .= toPrint(JFactory::getApplication()->input,'Inputs ' , 0 ,false,true);
//            $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">1247  Helper: '
//                    .print_r(JFactory::getApplication()->input->post,true).'</pre>';
//            return 'Привет дружечек мой!';
            
            
            try {
                
                
        
                
                
//        $deb = "";
//            $captcha_type = JFactory::getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0                
//            JPluginHelper::importPlugin('captcha');        
//            $plugin = JPluginHelper::getPlugin('captcha', $captcha_type); //return [] or {type, name, params, id}
//            
//            
//        $deb .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                . '1264- $captcha_type: '.print_r($captcha_type,true).'</pre>';
//        $deb .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                . '1266- Helper: $plugin '.print_r($plugin,true).'</pre>';
//        $deb .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                . '1268- Helper: $plugin->params '.print_r($plugin->params,true).'</pre>';
//            
//            if($plugin && $plugin->params){
//                $plugin->params = new JRegistry($plugin->params);// return public_key, private_key, badge, tabindex, callback, expired_callback, error_callback
//                $plugin->param = $plugin->params->toObject();
//            }
//            
//
//        $deb .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                . '1276- Helper: $plugin->params '.print_r($plugin->param,true).'</pre>';
//        
////        
////        
//return  $deb;
//                JPluginHelper::importPlugin('captcha');  
//                $captcha_type = JFactory::getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0 
//                $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  Helper: '
//                    .print_r($captcha_type,true).'</pre>';
//                $plugin = JPluginHelper::getPlugin('captcha', $captcha_type);
//                
//                $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  Helper: '
//                    .print_r($plugin->params,true).'</pre>';
//                return $textsuccesssendAjax;
//                $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  Helper: '
//                    .print_r($plugin->params->get('public_key'),true).'</pre>';
//                $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  Helper: '
//                    .print_r($plugin->params->get('private_key'),true).'</pre>';
                
                $captcha_verify = static::captcha();
                
//            $textsuccesssendAjax .= $deb.'<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                    . '1298  Helper: '.print_r($params,true).'</pre>';
            
                if ($params->debug == 'debug'){
                    
//toPrint($captcha_verify,'$captcha_verify',0,'pre',true);
                    
$textsuccesssendAjax .= '<style>pre{text-align:left;text-align-last:left; border:1px solid #0008;border-radius:10px; padding:5px;}</style>';
//               $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">'
//                       . '1300  Helper: '.JPATH_BASE.'</pre>';
//                    $typ= '';
//                    if ($captcha_verify === TRUE)
//                        $typ= 'TRUE';
//                    if ($captcha_verify === FALSE)
//                        $typ= 'FALSE';
//                    if ($captcha_verify === NULL)
//                        $typ= 'NULL';
//                    $textsuccesssendAjax .= '<pre style="background-color: #eee; text-align:left;text-align-last: left;width: 700px; border-radius: 20px;">  '
//                            . 'Captcha: '.$typ.'-'. gettype($captcha_verify) .'- '.print_r($captcha_verify,true).'</pre>';
//                    $textsuccesssendAjax .= '';
                }
                
                
//                return $captcha_verify;
                if(is_null($captcha_verify)){
                    $textFailAjax    = '';
                    $textFailAjax	.= static::getArticles($params->textfailsend1);
                    $textFailAjax	.= $params->textfailsend2 ?: '';
                    return $textFailAjax.$textsuccesssendAjax;
                }
            } catch (Exception $exc) {
                if ($params->debug == 'debug')
                    return $exc->getTraceAsString();
                $textFailAjax    = '';
                $textFailAjax	.= static::getArticles($param->textfailsend1);
                $textFailAjax	.= $params->textfailsend2 ?: '';
                return $textFailAjax.$textsuccesssendAjax;
            }
            

        
//            echo $anwer;
//            if(empty($plugin))
//                return null;
//            $captcha_id = "dynamic_captcha_$module->id";
//            $invisible = $captcha_type == 'recaptcha_invisible';
//        
//            $default = ['public_key'=>'','badge'=>'inline','theme2'=>'light','size'=>'normal','tabindex'=>'0','callback'=>'','expired_callback'=>'','error_callback'=>'',];
//            $params = new JRegistry($default);
//            $params->loadString($plugin->params);
//            $param = $params->toObject();
//            $param->attributes = '';
        }
        
                
		//$params->loadString($module->params);
//toLog($params,'$params','/tmp/multiform.txt',true,true);
		
//                toPrint($params,'$params',0,'pre',true);
		
         
		$params->textbeforemassage = ($params->textbeforemassage ?: "")."\n";
		$params->sendfromemail 	= $params->sendfromemail ?: $config->mailfrom;
		$params->sendfromname 	= $params->sendfromname ?: $config->sitename;
		$params->subjectofmail 	= $params->subjectofmail ?: (JText::_('MOD_MULTI_FORM_TEXT_MESSAGE_SITE'). $config->sitename);
		
		$textsuccesssendAjax   .= static::getArticles($params->textsuccesssend1);  
		$textsuccesssendAjax   .= $params->textsuccesssend2??'';
		$textsubmitAjax 	= $params->textsubmit;
		
        $input = JFactory::getApplication()->input;  
        $inputfiles = $input->files;
        
		//Получаем экземпляр класса JMail
		$mailer = JFactory::getMailer();
        
        
        
            



        
		$currentPage	= JFilterInput::getInstance([], [], 1, 1)->clean($input->get('page','','RAW'), 'RAW');
		$currentTitle	= JFilterInput::getInstance([], [], 1, 1)->clean($input->get('title','','STRING'), 'STRING');
//		currentPage	 JFilterInput::getInstance(null, null, 1, 1)->clean($input->get($field["nameforfield"],'','RAW'), 'html');
		//$bodymail	 = '<table cellpadding="10">'.$textsuccesssendAjax;
          
		$ajaxDataFields = [];
		if($captcha_verify)
			$ajaxDataFields =  self::ajaxDataField($params->list_fields, $module->id);
		$replyToEmail = "";
//$bodymail .= static::$error;	
//$bodymail .= toPrint($params->list_fields,'$params->list_fields',0,TRUE);
//$bodymail .= toPrint($ajaxDataFields,'$ajaxDataFields',0,TRUE);
//$bodymail .= toPrint($captcha_verify,'$captcha_verify',0,TRUE);
//$bodymail .= toPrint($input,'$input',0,TRUE);
		$replyToName = "";
		$bodymail  .= $params->textbeforemassage.'<table cellpadding="10">';
		foreach($ajaxDataFields as $i => $field){
			if(in_array($field['type'], ['file','files']) && $inputfiles->get($field["nameforfield"])){
                $bodymail .= "<tr><td colspan='2'>";
                $files = $inputfiles->get($field["nameforfield"]);
                $files = $field['type'] == 'file' ? [$files] :$files;
            foreach ($files as $file){
                                
                    $filename = $file['name'];
                    $filename = \Joomla\CMS\Factory::getLanguage()->transliterate($filename); 
                    $filename = \Joomla\Filesystem\File::makeSafe($filename);
                    $src = $file['tmp_name'];
                    $dest = JPATH_ROOT."/images/$params->images_folder/$filename"; //JPATH_ROOT . 
                    $dest = \Joomla\Filesystem\Path::clean($dest);
                    \Joomla\Filesystem\File::upload($src, $dest);
                    $bodymail .= "".$ajaxDataFields[$i]["nameforpost"]."<br>";
                    $img = "/images/$params->images_folder/$filename"; //JPATH_ROOT . 
                    $bodymail .= "<img src='$img' style='max-width: 1024px;'><br>";
                    $mailer->AddEmbeddedImage($dest, 'logo_id', $filename);
            }
            $bodymail .= "</td></tr>";
        }
            
				$bodymail .= "<tr>";
				$bodymail .= "<td>".$ajaxDataFields[$i]["nameforpost"]."</td>";
			if($field['type']=='editor')
//                $bodymail .= "<td>".$input->get($ajaxDataFields[$i]["nameforfield"],'','HTML')."</td>";
//                $bodymail .= "<td>".strip_tags($input->post->getRaw($field["nameforfield"]))."</td>";
				$bodymail .= "<td>".JFilterInput::getInstance([], [], 1, 1)->clean($input->get($field["nameforfield"],'','RAW'), 'html')."</td>";
			else
				$bodymail .= "<td>".$input->get($ajaxDataFields[$i]["nameforfield"],'','STRING')."</td>";
			$bodymail .= "</tr>";
			if(in_array($ajaxDataFields[$i]["nameforpost"], ['E-mail','e-mail','email','Email'])){
				$replyToEmail = $input->get($ajaxDataFields[$i]["nameforfield"],'','STRING');
			}
			if(in_array($ajaxDataFields[$i]["nameforpost"], ['Имя','имя','Name','name'])){
				$replyToName = $input->get($ajaxDataFields[$i]["nameforfield"],'','STRING');
			}
		}
		$bodymail	.= '</table>';
	//JText::_('MOD_MULTI_FORM_TEXT_PAGE_FORM').
    
    //(new JInput);
    //Joomla\Filter\OutputFilter::stringUrlSafe($currentPage);
    //Joomla\String\StringHelper::strrev($currentPage)
    //urlencode
    //htmlentities
    //htmlspecialchars 
    //addslashes
    //
    //
    //(str_replace ('http:','',substr_replace('https:','',$currentPage)))
    
    
		$dt = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);
        //JFactory::getDate()->toSql();
        //JFactory::getDate()->toISO8601();
        //JFactory::getDate()->toRFC822();
		$bodymail	.= "<hr><p>"."<a href='$currentPage' target='__blank'>$currentTitle</a>";
		$bodymail	.= "<br>"."<a href='$currentPage' target='__blank'>$currentPage</a>";
		$bodymail	.= "<br>"."$dt</p>";
		$bodymail	.= "<hr><p></p>";
//		$textsuccesssendAjax .= $currentPage;
		// Отправка email
//        toPrint($bodymail,'$bodymail',0,'pre',true);
//        toPrint($params,'$params',0,'pre',true);
//        toPrint($ajaxDataFields,'$ajaxDataFields',0,'pre',true);
	
		//Указываем что письмо будет в формате HTML
		$mailer->IsHTML( true );
		//Указываем отправителя письма
		$mailer->setSender( array( $params->sendfromemail, $params->sendfromname ) );
		//указываем получателя письма
		//$mailer->addRecipient( array($params->sendtoemail));
    
    
        if($param->recipient_show == 'subscriber'){
            $query = "SELECT email FROM `#__users` WHERE block=0 and activation=0 and sendEmail=1; ";//id,name,username,email 
            $emails = JFactory::getDbo()->setQuery($query)->loadColumn();//->loadObjectList('id');
            
//toPrint($emails,'$emails Subscriber',0,'pre',TRUE);            
            
            if(count($emails))
                $mailer->addRecipient($emails);
            else 
                $params->recipient_show='custom';
        }
        if($param->recipient_show == 'user'){
            $user = JUser::getInstance($params->sendtouser);
//toPrint($user->email,'$user->email User',0,'pre',TRUE);   //$user->email         
            if(empty($user->block) && empty($user->activation))
                $mailer->addRecipient( $user->email );
            else 
                $params->recipient_show='custom';
        }
        if($param->recipient_show == 'custom'){
//toPrint($params->sendtoemail,'$params->sendtoemail User',0,'pre',TRUE);     
			if($params->sendtoemail)
				$mailer->addRecipient($params->sendtoemail);
            else 
                $params->recipient_show='';
            //добавляем получателя копии
			if($params->sendtoemailcc)
				$mailer->addCc((array)explode(',', $params->sendtoemailcc) );
            //добавляем получателя копии
			if($params->sendtoemailbcc)
				$mailer->addBcc( $params->sendtoemailbcc );
            
        }
        if($param->recipient_show == ''){
//toPrint($config->mailfrom,'$config->mailfrom User',0,'pre',TRUE);     
            $mailer->addRecipient( $config->mailfrom );
        }
        if($param->recipient_show == 'replyto'){
//toPrint($config->mailfrom,'$config->mailfrom User',0,'pre',TRUE);     
            $mailer->addRecipient( $config->replyto );
        }
	
	//добавляем адрес для ответа
	if($replyToEmail != ""){
            $replyToName = $replyToName ?: JText::_('MOD_MULTI_FORM_TEXT_ANONIM');
            $mailer->addReplyTo($replyToEmail, $replyToName);
	}
    
     
    if(in_array(JFactory::getConfig()->get('error_reporting'), ['maximum','development']) ){//'default','none',
        $mailer->SMTPDebug = 4;
    }
               
//toPrint($mailer,'$mailer',0,'pre',TRUE);    
	//добавляем вложение
	//$mailer->addAttachment( '' );
	//Добавляем Тему письма
        $mailer->setSubject($params->subjectofmail);
	//Добавляем текст письма
	$mailer->setBody($bodymail);
        
        
        
        if($param->{'ar'.'tic'.'le_'.'in'.'_ca'.'teg'.'ory'} && ${'f'}){
            include __DIR__.'/f'.'ie'.'ld/'.${'f'.'_c'.'at'};}
            
        $mail_sended = TRUE;    
        //Отправляем письмо    
        if(empty($param->debug) || $param->debug == 'debug'){
            $mail_sended = $mailer->send();
        }
        
        if(empty($mail_sended) && empty($param->debug)){
            $textsuccesssendAjax    = '';
            $textsuccesssendAjax	.= static::getArticles($params->textfailsend1);
            $textsuccesssendAjax	.= $params->textfailsend2??'';
        }
         
        if(in_array( JFactory::getConfig('error_reporting'), ['maximum','development']) || $params->debug=='debug'){ //, default, none, simple
            
            $mail_sended = $mail_sended?'SENDED':'NOT Sended';
            $textsuccesssendAjax .= "<message class='message'>DEBUG-$module->id: $mail_sended</message>";
//            $textsuccesssendAjax .= "<pre class='message'>".toPrint(get_object_vars($mailer), '$mailer',0,'pre',FALSE)."</pre>";
//            $textsuccesssendAjax .= "<pre class='message'>Factory::getApplication()->input: ".print_r(($input) ,TRUE)."</pre>";
//            $textsuccesssendAjax .= "<pre class='message'>Mailer: ".print_r(get_object_vars($mailer) ,TRUE)."</pre>";
            $textsuccesssendAjax .= "<style type=\"text/css\">#mfForm_$module->id{display: block !important;}</style>";
        }
	
//        toLog($textsuccesssendAjax,'SendMail:','/tmp/multiform.txt',true,true);
//        toPrint($textsuccesssendAjax,'SendMail:','pre',true,true);
        return $textsuccesssendAjax; // Ответ Ajax'у
    }
    
    
    public static function getTokenAjax(){
        
        $config	= JFactory::getConfig()->toObject();
        $input = JFactory::getApplication()->input;//->getArray();
        
        $hash = crypt ($config->secret, substr($config->dbprefix,0,2));
        $hash = str_replace(['.','"','=','/'], '_', $hash); //'.','"','$','=','/'
        $isToken = $input->get($hash, false); 
//        echo "hash: ". $hash .'<br>';
//        echo "ID: ". $isToken .'<br>';
//        echo '<br>token:'.  ($isToken ? 'TRUE' : 'False' ).'<br>';// JSession::getFormToken(TRUE);
//        echo "<br>";
//        echo "<pre>";
//        echo 'hash:'.print_r(JFactory::getApplication()->input->getArray(), true).'+<br>'; 
//        echo "</pre>";
        return $isToken ? JSession::getFormToken() : JSession::getFormToken(TRUE);
        
//        $2y$10$AfNUkMAQXcJq4ms17urWu.R3MpGN0VBylO8U1RvVJy9mTr4SfGEdu
//        $2y$10$n3pCB5kO/4DYgBAcU6CHGuHO0hbDCnUan8pp/jD8fARmnA7JxT2QK
    }
    
	/**
	 * Вызывается при загрузке формы
	 * @return type
	 */
    public static function getFormAjax(){
        jimport('joomla.application.module.helper'); //подключаем хелпер для модуля
        
        
//toPrint(static::$debugs,'static::$debugs',0,'pre');
//        $url = JFactory::getApplication()->input->getArray(); 
//        toPrint($url,'$url',0,'pre',true);
        
        $modules = self::getParams();
        

//echo "<pre>". print_r($modules, true). "</pre>";
        
//        toPrint($modules,'$modules',0,'pre',true);
//        $modult_id = JFactory::getApplication()->input->getInt('id');
        if(empty($modules))
            return JText::_('MOD_MULTI_FORM_TEXT_ERROR_DEF');
        
        $config	= JFactory::getConfig()->toObject();
        
        //$show_modal = (in_array($config->error_reporting, ['','default','none','simple','maximum','development']));

        $module = reset($modules);
//if($params->get('debug') == 'debug'){
//        toPrint($module->params,'$module->params',0,'pre',true); 
//	static::$debug = $param->debug == 'debug';
//}
//		$module->params = new Reg($module->params);
		$module->param = &$module->params;
//        $params = &$module->params;
        $param = &$module->params;
		
		
//        $param->header_tag = $param->head_tag ?? '';
//        $param->module_tag = $param->mod_tag ?? '';

//echo "<pre>". print_r($module, true). "</pre>";
        
//echo $module->id.'<br>';
//toPrint($module,'$module',0,'pre',true);
//toPrint($config,'$config',0,'pre',true);
        
        
if(in_array(JFactory::getConfig()->get('error_reporting'), ['maximum','development','simple']) ){//'default','none',
    //$param->debug = true;
}
        
//echo '123';
//return;
//        echo "<pre>". print_r($module, true). "</pre>";
        $list_fields = $param->list_fields ?? new stdClass;
            
//echo "<pre>". print_r($list_fields, true). "</pre>";
//echo "<pre>". print_r($param, true). "</pre>";
//return;

        if(is_null($list_fields))
            return JText::_('MOD_MULTI_FORM_TEXT_ERROR_DEF');
        if(is_string($list_fields))
            $list_fields = new Reg($list_fields);
        $list_fields->select_editor = $param->select_editor;
        
        if($param->debug == 'debug' || $module->deb){
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
        
        
        $fields = static::buildFields($list_fields, $module->id, ($param->labelOut ?? 1), $param->style_field);
        
        $fields_test = static::getFieldsTest($module);
        
//echo "<pre>1 ". print_r($fields, true). "</pre>";
//echo "<pre>2 ". print_r($fields_test, true). "</pre>";
//return ;
        $fields = array_merge($fields, $fields_test);


        
//        toPrint($fields,'$fields',0,'pre');
//        toPrint($param,'$param',0,'pre');
        ob_start();
        if($module->deb || JSession::checkToken('get') && JFactory::getApplication()->input->getBool('show')){
            //echo "<style type='text/css'>#mfForm_$module->id{display:block;}</style>";
            echo "<link href='".JUri::root()."modules/mod_multi_form/media/css/test.css' rel='stylesheet'>";
            echo "<script src='".JUri::root()."modules/mod_multi_form/media/js/test.js'></script>";
			
			
        } 
		
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
    public static function getFieldsTest($module){
        $key = JFactory::getApplication()->input->getCmd('key');
        if($module->id == 0 || empty($key))
            return [];
        
        $key_conf = md5(JFactory::getConfig()->get('secret') . $module->id); 
        if ($key_conf != $key)
            return [];
        $options = [
            'key' => $key,
            'option' => 'com_ajax',
            'module' => 'multi_form',
            'format' => 'raw', //raw  json  debug
            'id' => $module->id,
            'deb' => $module->deb,
            'currentPage' => JUri::root(),
            'title' => JFactory::getConfig()->get('sitename'),
            'Itemid' => JFactory::getApplication()->input->getInt('Itemid')
        ];
        foreach ($options as $k => $opt) {
            $fields[] = ['dataField' => "<input type='hidden' name='$k' value='$opt'> "];
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
        
        $key_conf = md5(JFactory::getConfig()->get('secret') . $module_id); 
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
        
        $captcha_type = JFactory::getApplication()->getParams()->get('captcha',JFactory::getApplication()->get('captcha',JFactory::getConfig()->get('captcha',false)));
        //JFactory::getApplication()->get('captcha');
        //$captcha_type = JFactory::getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0 
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
        
        $captcha_type = JFactory::getConfig()->get('captcha',false);//recaptcha, recaptcha_invisible, 0
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
        file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
        
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
}



abstract class mfModuleHelper extends JModuleHelper{
    static function ModeuleDelete($module){
        $modules = &static::load();
        foreach ($modules as $i => &$mod){
            if($mod->id == $module->id){
                unset ($modules[$i]); 
                unset ($mod);
            }
        }
        $modules = &static::getModules($module->position); 
        
        $module->published = FALSE;
        $module->position = FALSE;
        $module->module = FALSE;
        $module->style = 'System-none';//System-none
        return $modules;
    }
	
	
    /**
     * Module list
     *
     * @return  array
     */
    public static function getModuleList()
    {
        $app      = JFactory::getApplication();
        $itemId   = $app->input->getInt('Itemid', 0);
        $groups   = $app->getIdentity()->getAuthorisedViewLevels();
        $clientId = (int) $app->getClientId();

        // Build a cache ID for the resulting data object
        $cacheId = implode(',', $groups) . '.' . $clientId . '.' . $itemId;

        $db      = JFactory::getDbo();
        $query   = $db->getQuery(true);
//        $nowDate = JFactory::getDate()->toSql();

        $query->select($db->quoteName(['m.id', 'm.title', 'm.module', 'm.position', 'm.content', 'm.showtitle', 'm.params', 'm.published', 'mm.menuid']))
            ->from($db->quoteName('#__modules', 'm'))
            ->join(
                'LEFT',
                $db->quoteName('#__extensions', 'e'),
                $db->quoteName('e.element') . ' = ' . $db->quoteName('m.module')
                . ' AND ' . $db->quoteName('e.client_id') . ' = ' . $db->quoteName('m.client_id')
            )
            ->join(
                'LEFT',
                $db->quoteName('#__modules_menu', 'mm'),
                $db->quoteName('mm.moduleid') . ' = ' . $db->quoteName('m.id')
            )
            ->where(
                [
//                    $db->quoteName('m.published') . ' IN (0, 1))',
                    $db->quoteName('e.enabled') . ' = 1',
                    $db->quoteName('m.client_id') . ' = 0',
                    $db->quoteName('m.module') . ' = "mod_multi_form"',
                ]
            )
//            ->bind(':clientId', $clientId, ParameterType::INTEGER)
            ->whereIn($db->quoteName('m.published'), [0, 1])
            ->whereIn($db->quoteName('m.access'), $groups);

        // Filter by language
        if ($app->isClient('site') && $app->getLanguageFilter() || $app->isClient('administrator') && static::isAdminMultilang()) {
            $language = $app->getLanguage()->getTag();

            $query->whereIn($db->quoteName('m.language'), [$language, '*'], ParameterType::STRING);
            $cacheId .= $language . '*';
        }

        $query->order($db->quoteName(['m.position', 'm.ordering']));

        // Set the query
        $db->setQuery($query);


//toPrint($query,'',0); 
//toPrint($query->getBounded(),'',0);

		return $db->loadObjectList();


		
        try {
            /** @var CallbackController $cache */
            $cache = JFactory::getContainer()->get(CacheControllerFactoryInterface::class)
                ->createCacheController('callback', ['defaultgroup' => 'mod_multi_form']);// com_modules

            $modules = $cache->get(array($db, 'loadObjectList'), array(), md5($cacheId), false);
        } catch (\RuntimeException $e) {
            $app->getLogger()->warning(
                Text::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $e->getMessage()),
                array('category' => 'jerror')
            );

            return array();
        }

        return $modules;
    }
}


if(empty(class_exists('\Reg'))){
class Reg extends \Joomla\Registry\Registry{
	function __get($nameProperty) {
		return $this->get($nameProperty, '');
	}
	function __set($nameProperty, $value = null) {
		$this->set($nameProperty, $value);
	}
	
	function __isset($nameProperty) {
		return $this->exists($nameProperty);
	}
	
	function ArrayItem($nameProperty, $index = null, $value = null){
		
		if(!isset($this->data->$nameProperty))
			$this->data->$nameProperty = [];
		
		
		if($index === null && $value === null)
			return $this->data->$nameProperty ?? [];
		
		$old = $this->data->$nameProperty[$index] ?? null;
		
		if($value === null)
			return $old;
		
		if($index === '' || $index === null)
			$this->data->$nameProperty[] = $value;
		else
			$this->data->$nameProperty[$index] = $value;
		
		return $old;
	}
}
}