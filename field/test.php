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


JHtml::_('jquery.framework'); 
JHtml::_('bootstrap.framework');
//JHtml::_('bootstrap.loadCss', true);
require_once JPATH_ROOT . '/modules/mod_multi_form/helper.php';

modMultiFormHelper::constructor();
//        echo "<pre>";
//        echo 'checkGet:'.print_r(JSession::checkToken('get'), true).'+<br>';
//        echo 'checkPost:'.print_r(JSession::checkToken('post'), true).'+<br>';
//        echo 'token:'.print_r(JSession::getFormToken(), true).'<br>';
//        echo 'SiteName:'.print_r(JFactory::getApplication()->getName(), true).'<br>';
////        echo 'SiteName:'.print_r(JFactory::getApplication()->getSession(), true).'<br>';
//        echo "</pre>";
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
//        $this->id = JFactory::getApplication()->input->getInt('id',0); 
		
        $this->id = $this->form->getValue('id',null,0); 
        
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
        
        
//		$user = JFactory::getApplication()->getIdentity();
		
		$token = modMultiFormHelper::checkToken();
		
        
        $html = '';
		
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
		$session = JFactory::getApplication()->getSession();
		
        $config	= JFactory::getConfig()->toObject(); 
//        $key = $this->id ? ('&key='.md5($config->secret.$this->id.$session->getToken())) : '' ;
        $key = $this->id ? "&key=$this->value" : '' ;
        
//        $input = JFactory::getApplication()->input;//->getArray();
        
//		$id = $input->getInt('id', 0);
//		if(empty($id)) 
//			return false;
//		$hash = crypt ($config->secret.$user->id.$id, substr($config->dbprefix,0,2));
//        $hash = str_replace(['.','"','=','/'], '_', $hash); //'.','"','$','=','/'
		
        
//        $isToken = $input->get($hash, false);
        //$isToken = in_array($word, $full_string);
        
//        $token = $isToken ? JSession::getFormToken() : JSession::getFormToken(TRUE);
		
//		$hash = modMultiFormHelper::checkToken();
        
//        $urlToken = JUri::root(). "?{$key}&option=com_ajax&module=multi_form&format=raw&method=getToken&$hash=$this->id";
        
//        $root = JUri::root();
        
        //$urlForm = JUri::root(). "/?option=com_ajax&module=multi_form&format=raw&method=getForm&id=$this->id&$hash=1&show=1&Itemid=0";
		
       
   
			

        
//        echo "<pre>";
//        //echo '$root/?option=com_ajax&module=multi_form&format=raw&method=getForm&id=$this->id$itemids&'+ request$this->id.responseText + '=1$key'<br>'; 
//        echo 'hash:'.print_r($hash, true).'+<br>'; 
//        echo 'key:'.print_r($key, true).'+<br>'; 
//        echo "</pre>";
        
        
        
                 
                $this->default = $this->default ?JText::_($this->default):JText::_('JAPPLY');
                
		$allowedElement = array('button', 'input', 'a');

		if (in_array($this->element['htmlelement'], $allowedElement))
			$type = $this->element['htmlelement'];
		else
			$type = 'a';
        
        

		$class   = $this->element['class'] ? (string) $this->element['class'] : ' btn-medium button-apply btn-success';
		$icon    = $this->element['icon'] ? (string) $this->element['icon'] : ''; 
		$title   = $this->element['title'] ? (string) $this->element['title'] : $this->element['description'];
                $title = $this->id ? JText::_($title) : JText::_('MOD_MULTI_FORM_TEST_MODULE_MESSAGE');
                $title = $title ? "title='$title'":'';

		if($icon){
			$icon = "<span class='icon-$icon'></span>";
		}
        $message = $this->id ?'': " onclick=\"alert('".JText::_('MOD_MULTI_FORM_TEST_MODULE_MESSAGE')."')\"";
                
        $text = (string)$this->element['text'] ?: 'MOD_MULTI_FORM_TEST_MODULE_TEXT';
        $text = $this->id ? JText::sprintf($text,$this->id) : $this->default;
        $text = htmlspecialchars($text, ENT_COMPAT, 'UTF-8') ;
                
                $style=' style="min-width: 220px; box-sizing: border-box;" ';
                
		$this->disabled = $this->id && $this->value? '':' disabled ';
		$this->readonly = $this->id && $this->value? '':' readonly ';
		$url = '#';
		if($this->id && $this->value)
			$url = JUri::root()."?option=com_ajax&module=multi_form&format=raw&method=getForm&$this->fieldname=$this->value&show=1&id=$this->id&$itemids";
//		$url .= JUri::root()."?option=com_ajax&module=multi_form&format=raw&method=getForm$key&id=$this->id&".JSession::getFormToken().'=1&show=1'.$itemids;
        
		
		$name = $this->name ?: "hash_gen";
		$html = "<input type=hidden id=fldTestForm$this->id name=$name> ".($this->id && $this->value? '': JText::_('Please save module for active.'));

		$html .= "<script>
document.getElementById('fldTestForm$this->id').value=Math.random().toString(16).slice(2)+Math.random().toString(16).slice(2)+Math.random().toString(16).slice(2);
</script>
<style>
 a.disabled{
	pointer-events: none !important;
	cursor: default;
	background-color: transparent !important;
	opacity: 0.3;
}
</style>
";
		
		$len = strlen($this->value);
			
        if($type == 'a'){
			return "<a  len=$len $title href='$url' id='btnOpenTestForm$this->id' target='_blank' class='btn $class $this->disabled' $this->disabled $this->readonly $style $message>$icon $text</a>$html";
		}
            
			
		$formaction = $this->id ? " formaction='$url' " : '' ;
		return "<$type id='btnOpenTestForm$this->id' class='btn $class' $this->disabled $this->readonly   $title $message $formaction formmethod='GET' formtarget='_blank'>$icon $text</$type>$html" ;
	 
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
