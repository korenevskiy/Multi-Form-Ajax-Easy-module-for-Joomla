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
$s = DIRECTORY_SEPARATOR;
require_once JPATH_LIBRARIES."{$s}src{$s}Form{$s}Field{$s}EditorField.php";

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Form\FormHelper as JFormHelper;
use Joomla\CMS\Form\Field\EditorField as JFormFieldEditor; 


JFormHelper::loadFieldClass('editor');

 
class JFormFieldEditorTranslate extends JFormFieldEditor //JFormField
{//   JFormFieldEditorTranslate
    
    
//JFormFieldEditorTranslate
//EditorTranslateField
    
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	public $type = 'EditorTranslate'; 
    
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
            
//            toPrint(" ->default:".$this->default, "FIRST ->value:".$this->value); 
            
            $result = parent::setup($element, $value, $group);
            
            if(trim($this->value) === trim($this->default))  //mailfrom
                $this->value = JText::_($this->default);
            
            if(trim($this->value) === "<p>$this->default</p>")  //mailfrom
                $this->value = JText::_($this->default);
            
            if(empty($this->value))
                $this->value = JText::_($this->default);
            

//            toPrint(" ->default:".$this->default, "Last ->value:".$this->value); 
            
//            toPrint($this->value,'$this->value');
//            toPrint($this->default,'$this->default');
            
            return $result;
	} 
//        
//	/**
//	 * Method to get the field input markup for the editor area
//	 *
//	 * @return  string  The field input markup.
//	 *
//	 * @since   1.6
//	 */
//	protected function getInput()
//	{
//            return "<b>123</b>";
//		// Get an editor object.
//		$editor = $this->getEditor();
//		$params = array(
//			'autofocus' => $this->autofocus,
//			'readonly'  => $this->readonly || $this->disabled,
//			'syntax'    => (string) $this->element['syntax'],
//		);
//
//		return $editor->display(
//			$this->name,
//			htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8'),
//			$this->width,
//			$this->height,
//			$this->columns,
//			$this->rows,
//			$this->buttons ? (is_array($this->buttons) ? array_merge($this->buttons, $this->hide) : $this->hide) : false,
//			$this->id,
//			$this->asset,
//			$this->form->getValue($this->authorField),
//			$params
//		);
//	}
//        
//  	/**
//	 * Get the rendering of this field type for static display, e.g. in a single
//	 * item view (typically a "read" task).
//	 *
//	 * @since 2.0
//	 *
//	 * @return  string  The field HTML
//	 */
//	public function getStatic()
//	{
//            return "<b>123</b>";
//		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
//
//		return '<div id="' . $this->id . '" ' . $class . '>' . $this->value . '</div>';
//	}
//
//	/**
//	 * Get the rendering of this field type for a repeatable (grid) display,
//	 * e.g. in a view listing many item (typically a "browse" task)
//	 *
//	 * @since 2.0
//	 *
//	 * @return  string  The field HTML
//	 */
//	public function getRepeatable()
//	{
//            return "<b>123</b>";
//		$class = $this->element['class'] ? (string) $this->element['class'] : '';
//
//		return '<div class="' . $this->id . ' ' . $class . '">' . $this->value . '</div>';
//	}
}
