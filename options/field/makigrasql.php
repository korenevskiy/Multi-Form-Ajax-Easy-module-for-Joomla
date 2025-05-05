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
use Joomla\CMS\Form\Field\ListField as JFormFieldList;
use Joomla\CMS\Form\Field\SqlField as JFormFieldSql;

use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModelLegacy;

/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @since  1.6
 */
class JFormFieldMakigraSql extends JFormFieldSql // JFormFieldList // JFormFieldCategory // JFormField
{ 
	
	protected function getDatabase(): \Joomla\Database\DatabaseInterface
	{
		if(file_exists(JPATH_ROOT . '/MakIgra.php'))
			require_once JPATH_ROOT . '/MakIgra.php';

		if(class_exists('\cfgMakIgra'))
			$this->databaseAwareTraitDatabase = \cfgMakIgra::getDbo();
		
		if ($this->databaseAwareTraitDatabase)
			return $this->databaseAwareTraitDatabase;

		throw new \Joomla\Database\Exception\DatabaseNotFoundException('Database not set in ' . \get_class($this));
	}
	
	
 	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
//	public $type = 'Cat';

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
	public function getOptions()
	{
		$options = []; 
                                
		if (isset($this->element['show_empty']) && !in_array($this->element['show_empty'], ['false','0','','FALSE','empty','none',]))
			$options[]= JHtml::_('select.option', '', JText::_('JOPTION_DO_NOT_USE'));//JHtml
		
		if (isset($this->element['show_root']) && empty($this->element['show_root']))
			unset($this->element['show_root']);
		
		$options = array_merge($options,parent::getOptions() );
		
		return $options;    

        
	}
}


