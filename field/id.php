<?php defined('_JEXEC') or die;
/**
 * Multi Form - Easy Ajax Form Module with modal window, with field Editor and create article with form data
 * 
 * @package    Joomla
 * @copyright  Copyright (C) Open Source Matters. All rights reserved.
 * @extension  Multi Extension
 * @subpackage Modules
 * @license    GNU General Public License version 2 or later; see LICENSE
 * @link       http://exoffice/download/joomla
 * mod_multi_form 
 */

use Joomla\CMS\Language\Text as JText;

/**
 * Form Field class for the Joomla Platform.
 * Provides a one line text box with up-down handles to set a number in the field.
 *
 * @link   http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since  3.2
 */
class JFormFieldId extends \Joomla\CMS\Form\FormField // /libraries/src/Form/FormField.php
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $type = 'Id'; 

	protected $readonly = true; 
        /**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.7.0
	 */
	protected function getInput()
	{ 
            
            $this->readonly = true;
            $this->disabled = true;
//                $module_id = $this->form
            
            static $i;
            
            if(empty($i)){
                $i = 0;
            }
            ++$i;
            $option = [];
            $option['name'] = $this->name ?: 'fieldId'.($i);
            $option['id'] = $this->id ?:'fieldId'.($i);
            $option['options'] = false;
            $option['autocomplete'] = false;
            $option['disabled'] = true;
            $option['hint'] = 0;
            $option['onchange'] = false;
            $option['required'] = false;
            $option['autofocus'] = false;
            $option['spellcheck'] = true;
            $option['dirname'] = true; 
            $option['readonly'] = true; 
            
            if(empty($this->default)){ 
                $option['value'] = $this->default = $this->form->getValue('id',null,JText::_('JAPPLY'));
            }
            
		return $this->getRenderer('joomla.form.field.text')->render($option);
	}	
        /**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
            return JText::_('COM_MODULES_MODULE').' ID';
	}
}
