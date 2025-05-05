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

use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModelLegacy;
use Joomla\CMS\Form\Form as JForm;

/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @since  1.6
 */
class JFormFieldCat extends JFormFieldCategory //JFormField
{ 
 	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Cat';

	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @return  array    The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
            $options = []; 
			
            if (isset($this->element['show_empty']) && (bool)$this->element['show_empty'])
            {
                $options[]= JHtml::_('select.option', '', JText::_('JOPTION_DO_NOT_USE'));//JHtml
            }
            if (isset($this->element['show_root']) && empty($this->element['show_root']))
            {
                unset($this->element['show_root']);
            }
                                
            
	 
            $options = array_merge($options,parent::getOptions() );

            return $options;
        
        }
 
  
}


    
    
//$data = [];
//$data['catid'] = $param->article_in_category;
//$data['state'] = $param->article_published;// 1-Published, 0-Unpublished, 2-Archive, -2-Trash
//$data['title'] = $param->subjectofmail;
//$data['id'] = 0;
//$data['access'] = 1;
//$data['language'] = '*';
//$data['articletext'] = $bodymail;
//$data['alias'] = '';
//$data['introtext'] = $bodymail;

//Factory::getApplication()->input->set('id',0);
//Factory::getApplication()->input->set('task','save2new');

        
//$model_article = JPATH_BASE. "/administrator/components/com_content/models/article.php";
//include_once $model_article;
//$model_article = new ContentModelArticle();
//$parent = get_parent_class($model_article);
//toPrint($model_article,'$parent '.$parent,0,'pre',true);
////$result = $model_article->save($data);



//$result = Joomla\CMS\Table\Table::getInstance('Content','JTable','id')->save($data);
//$result = Joomla\CMS\Table\Table::getInstance('Content','JTable','id')->save($data);

//toPrint($result,'$result ',0,'pre',true);


//$parent = get_parent_class($model);
//toPrint($model_article,'$parent '.$data['id'],0,'pre',true);
//toPrint($data,'$data '.$data['id'],0,'pre',true);
return;
