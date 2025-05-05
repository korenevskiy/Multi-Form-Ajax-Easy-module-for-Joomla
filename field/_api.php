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
class JFormFieldApi extends \Joomla\CMS\Form\FormField // /libraries/src/Form/FormField.php
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $type = 'api'; 

	protected $disabled = true; 
        
	protected $readonly = true; 
	
	public function setup(\SimpleXMLElement $element, $value, $group = null){
		
		$this->value = $value;
		
		return parent::setup($element, $value, $group);
	}
	
	
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
//		$this->class = 'btn-group';
//		$this->parentclass = 'btn-group';
		
		$this->id = $this->form->getValue('id',null,0); 
//		$this->id = 0;
		
		$class_sfx = $this->value ? '' : 'is-invalid';
		
		
//		if(empty($this->value))
//			return "<input type='text' readonly class='form-control form-control form-control-sm controls' value='".JText::_('Please save module for generate Url API.')."'> ";
		
		
				
		$live_site = JFactory::getApplication()->getConfig()->get('live_site');
		
		$value = empty($this->value) ? $value = sha1(time()) : $this->value;
		
		$url = $live_site . "?option=com_ajax&module=multi_form&format=raw&id=$this->id&{$this->type}=$value";
		$title = $this->value ? $url : JText::_('Please save module for generate Url API.');
		$title2 = $this->value ? $this->value : JText::_('Please save module for generate Url API.');
		
//		if(empty($this->value))
//			$this->value = $value;
		
		return "<div class='control-group _btn-group gap-2'>"
		. "<label for={$this->type}multiform$this->id id={$this->type}label$this->id title='$title2' onclick='document.getElementById(\"{$this->type}multiform$this->id\").select(), document.execCommand(\"copy\")'>".JText::_($this->element['label']??'')."</label>"
		. "<button title='".JText::_('Copy url API')."' onclick='document.getElementById(\"{$this->type}multiform$this->id\").select(), document.execCommand(\"copy\")' type='button' class='btn btn-sm btn-outline-secondary bi bi-copy'><i class='bi bi-copy icon-copy fa-copy'></i></button>"
		. "<button title='".JText::_('JTOOLBAR_REFRESH_CACHE')."' "
				. "onclick='"
				. "document.getElementById(\"{$this->type}$this->id\").value=Math.random().toString(16).slice(2)+Math.random().toString(16).slice(2)+Math.random().toString(16).slice(2)"
				. ",document.getElementById(\"{$this->type}multiform$this->id\").value=\"$live_site?option=com_ajax&module=multi_form&format=raw&id=$this->id&{$this->type}=\"+document.getElementById(\"{$this->type}$this->id\").value"
				. (($this->value)?",document.getElementById(\"{$this->type}multiform$this->id\").title = document.getElementById(\"{$this->type}multiform$this->id\").value ":'')
//				. (($this->value)?",document.getElementById(\"{$this->type}multiform$this->id\").title = document.getElementById(\"{$this->type}$this->id\").value ":'')
				. "' type='button' class='btn btn-sm btn-outline-secondary '>&#x21bb;</button>"
		. "<input type='text' readonly value='$url' title='$title' class='$class_sfx form-control form-control-sm controls' id={$this->type}multiform$this->id> 
<input type=hidden name=$this->name  id={$this->type}$this->id value='$value'>
	<script>
var urlField = document.getElementById('{$this->type}multiform{$this->id}');
console.log(' YES: ',urlField);
if(urlField && urlField.value.indexOf('?')==0){
	console.log('IF YES: ',urlField);
	var pos = window.location.href.indexOf('administrator/index.php');
	urlField.value = window.location.href.substr(0,pos) + urlField.value;
}
	</script>
</div>";
		
		"?option=com_ajax&module=multi_form&format=raw&method=options";
		$format = "json|debug|raw";
	}
	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
//	protected function getLabel()
//	{
////            return JText::_('COM_MODULES_MODULE').' ID';
//            return JText::sprintf('Url API',$this->id);
//	}
}
