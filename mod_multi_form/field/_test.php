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
 
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;


        echo "<pre>";
        echo 'checkGet:'.print_r(JSession::checkToken('get'), true).'+<br>';
        echo 'checkPost:'.print_r(JSession::checkToken('post'), true).'+<br>';
        echo 'token:'.print_r(JSession::getFormToken(), true).'<br>';
        echo 'SiteName:'.print_r(JFactory::getApplication()->getName(), true).'<br>';
//        echo 'SiteName:'.print_r(JFactory::getApplication()->getSession(), true).'<br>';
        echo "</pre>";
//        $token = "<br>".JSession::getFormToken()
//                ."<br>+".JSession::checkToken().'+';
//        JFactory::getApplication()->enqueueMessage($token);
/**
 * Form Field class for the Joomla Platform.
 * Provides a one line text box with up-down handles to set a number in the field.
 *
 * @link   http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since  3.2
 */
class JFormFieldTest extends \Joomla\CMS\Form\FormField // /libraries/src/Form/FormField.php
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $type = 'Test'; 

	protected $disabled = true; 
        
	protected $readonly = true; 
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getInput()
	{
		
                

                $this->id = $this->form->getValue('id',null,0); 
                 
                $this->default = $this->default ?JText::_($this->default):JText::_('JAPPLY');
                
		$allowedElement = array('button', 'input', 'a');

		if (in_array($this->element['htmlelement'], $allowedElement))
			$type = $this->element['htmlelement'];
		else
			$type = 'a';
        
        $this->id;
        
        $sql = "SELECT menuid FROM `#__modules_menu` WHERE `moduleid` =$this->id; ";
        $itemids = JFactory::getDbo()->setQuery($sql)->loadColumn();
        
        
        
        if(count($itemids) > 1){
            $itemids = "&Itemid=[". implode(',', $itemids)."]";
        }
        elseif(count($itemids) == 1 && reset($itemids)==0){
            $itemids = "&Itemid=".reset($itemids);
        }
        else{
            $itemids = '';
        }
            

		$class   = $this->element['class'] ? (string) $this->element['class'] : ' btn-medium button-apply btn-success';
		$icon    = $this->element['icon'] ? (string) $this->element['icon'] : ''; 
		$title   = $this->element['title'] ? (string) $this->element['title'] : $this->element['description'];
                $title = $this->id ? JText::_($title) : JText::_('MOD_MULTI_FORM_TEST_MODULE_MESSAGE');
                $title = $title ? "title='$title'":'';

		if($icon){
			$icon = "<span class='icon-$icon'></span>";
		}
        $key = $this->id ? ('&key='.md5(JFactory::getConfig()->get('secret').$this->id)) : '' ;
        $url = JUri::root()."?option=com_ajax&module=multi_form&format=raw&method=getForm$key&id=$this->id&".JSession::getFormToken().'=1&show=1'.$itemids;
        $href = $this->id ? "href='$url'" : '' ;
        $message = $this->id ?'': " onclick=\"alert('".JText::_('MOD_MULTI_FORM_TEST_MODULE_MESSAGE')."')\"";
                
        $text = (string)$this->element['text']?:'MOD_MULTI_FORM_TEST_MODULE_TEXT';
        $text = $this->id ? JText::sprintf($text,$this->id) : $this->default;
        $this->value = htmlspecialchars($text, ENT_COMPAT, 'UTF-8') ;
                
                $style=' style=\'width: 220px; box-sizing: border-box;\'';
                
                $this->disabled = $this->id? '':' disabled ';
                $this->readonly = $this->id? '':' readonly '; 
 
            if($type == 'a'){
                return "<a $title $href id='mod_$this->id' target='_blank' class='btn $class' $this->disabled $this->readonly $style $message>$icon $text</a>";
            }
            
                
            $formaction = $this->id ? "formaction='$url'" : '' ;
            return "<$type id='mod_$this->id' class='btn $class'   $title $message $formaction formmethod='GET' formtarget='_blank'>$icon $this->value </$type>" ;
	 
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
//            return JText::_('COM_MODULES_MODULE').' ID';
            return JText::sprintf('MOD_MULTI_FORM_TEST_MODULE_LABEL',$this->id);
	}
}
