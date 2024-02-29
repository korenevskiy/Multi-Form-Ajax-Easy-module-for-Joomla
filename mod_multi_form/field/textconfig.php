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

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory as JFactory;
 
class JFormFieldTextConfig extends JFormFieldText //JFormField
{ 
 
  	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   3.2
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
            
            $result = parent::setup($element, $value, $group); 
//return;
                
            if($this->value && $this->value === $this->default) {//mailfrom
                $this->value = JFactory::getConfig()->get($this->default, JText::_ ($this->default));
            }
             
            //mailfrom
            return $result;
        
        }
  
}
