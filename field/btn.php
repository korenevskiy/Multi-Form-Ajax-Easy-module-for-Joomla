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

/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @since  1.6
 */
class JFormFieldBtn extends Joomla\CMS\Form\FormField // Field\SpacerField //JFormFieldCategory //JFormField
{ 
 	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
//	public $type = 'Btn';
	
	
	static $one = 0;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.7.0
	 */
	protected function getInput()
	{ 
            
//            $this->readonly = true;
//            $this->disabled = true;
////                $module_id = $this->form
//            
//            static $one;
            
//            if(empty($i)){
//                $i = 0;
//            }
//            ++$i;
//            $option = [];
//            $option['name'] = $this->name ?: 'fieldId'.($i);
//            $option['id'] = $this->id ?:'fieldId'.($i);
//            $option['options'] = false;
//            $option['autocomplete'] = false;
//            $option['disabled'] = true;
//            $option['hint'] = 0;
//            $option['onchange'] = false;
//            $option['required'] = false;
//            $option['autofocus'] = false;
//            $option['spellcheck'] = true;
//            $option['dirname'] = true; 
//            $option['readonly'] = true; 
            
//            if(empty($this->default)){ 
//                $option['value'] = $this->default = $this->form->getValue('id',null,JText::_('JAPPLY'));
//            }
		$label = JText::_($this->element['label']);
			
		$this->element['click'];
		$this->element['hover'];
		$this->element['load'];
		$this->element['focus'];
		
		$html = '';
		
		
//		if((static::$one)){
			$html .= "
<style>
.Btn   button.btnOption + dialog{
overflow: scroll;
border-width: 1px;
border-color: #fff4;
border-image: none;
box-sizing: border-box;
box-shadow: 0 0 20px black;
}
.Btn   button.btnOption + dialog form{
background-color: transparent;
}
</style>
			";
//			static::$one++;
//        }
		
		
			
//		$html .= "<label>";
		
		
		$html .= "";
		
//        $user = JFactory::getUser();
//		JFactory::getApplication()->enqueueMessage('user: '.$user->get('id'));
//		JFactory::getApplication()->enqueueMessage(JSession::getFormToken());
//		JFactory::getApplication()->enqueueMessage( (JFactory::getApplication()->getSession()->getToken().' sess->getToken()'));
//		JFactory::getApplication()->enqueueMessage( (JFactory::getApplication()->getSession()::getFormToken().' sess::getToken()'));
		
		$rand = rand(1, 1111);
		$html .= "<button  name='form[params][del][$rand]' \n  
type='button' id='btn_$this->id' class='{$this->element['class']} btnOption' style='{$this->element['style']}'  
onclick=\"document.getElementById('module-form').btnOption_Click(this.id, '$this->name')\" 
\n data-bs-target='#pop_$this->id' _data-bs-toggle='modal' _value='".( '*')."' title='$label' >"
			. ((string)$this->element[0] ?? '') . "</button>";
		
		
//		$html .= "</label>";
		
//		pop_jform_params_list_fields_0_0_paramsfield
//		$html .= "<input id='$this->id' type='hidden' name='$this->fieldname' class='valOption' value='".addslashes($this->value)."'>";
//		$html .= "<input id='$this->id' type='hidden' name='$this->name' class='valOption' value='".addslashes($this->value)."' >";
		
		
		$html .= "
<dialog id='pop_$this->id'  class='_modal _fade ' tabindex='-1' aria-labelledby='title_{$this->id}' aria-hidden='true' style='overflow:scroll' style='box-shadow: 0 0 3px white,0 0 10px black;'>
	<h5 id='title_{$this->id}'>$label</h5>
	<button style='float: right; position: absolute;top:0;right:0' class='btn close' 
	_onclick=\"document.getElementById('module-form').btnClose_Click(); return false\">❌</button>
	<textarea id='txt_$this->id' type='textarea' name='$this->name'  class='valOption			ga-spine form-control' rows='8' cols='25' >$this->value</textarea>
</dialog>
		";

		
		
		
//		$this->name = '_' . $this->name;
//		$this->filter = 'html';
		
$inps = JFactory::getApplication()->input->getArray();

//toPrint();
//toPrint($inps,'$inps',0,'message');
//toPrint($inps,'[css]',0,'message');
		
		$script = "";
		
		if($this->element['click']){
			$script .= "btn.addEventListener('click', function(e) {{$this->element['click']}});\n";
		}
		if($this->element['hover']){
			$script .= "btn.addEventListener('mouseover', function(e) {{$this->element['hover']}});\n";
		}
		if($this->element['load']){
			$script .= "btn.addEventListener('DOMContentLoaded', function(e) {{$this->element['load']}});\n";
		}
		if($this->element['focus']){
			$script .= "btn.addEventListener('focus', function(e) {{$this->element['focus']}});\n";
		}
		if($this->element['open']){
			$script .= "btn.popOpen = function(id, name, fieldname) { return {$this->element['open']}};\n";
		}
		
		$html .= "<script>
			
(function(){
	
const pop = document.getElementById('pop_$this->id');
const popContent = pop.querySelector('.modal-content');


	
const btn = document.getElementById('btn_$this->id');
btn.fieldid = '$this->id';
btn.fieldname = '$this->fieldname';
btn.name = 'form[params][del]['+(Math.ceil(Math.random()+100000))+']';//'$this->name';
btn.addEventListener('click',function(e){
	pop.showModal();
	
	btn.closest('tr').style = 'border: 3px solid gray;';

	pop.querySelector('button.btn.close').addEventListener('click',
		function(e){
			btn.closest('tr').style = '';
			document.getElementById('module-form').btnClose_Click(pop);
			e = e || window.event;
			e.preventDefault();
			return false;
		}
	);
	

	

	const field = document.getElementById('$this->id');	

	if(btn.popOpen){
		
		let html = btn.popOpen('$this->id', '$this->name', '$this->fieldname', popContent, '');
		if(html)
			popContent.innerHTML = html;
	}
	
	pop.querySelector('textarea:not([disabled]), select:not([disabled]), input:not([disabled]):not([type=\"submit\"]):not([type=\"hidden\"])').focus();

});

console.log('!  ',popContent);
$script				

				})();</script>";
		
		
		
//$this->fieldname;		btn
//$this->name;			jform[params][list_fields][btn][]; 
//$this->id;			jform_params_list_fields_0_0_btn; 
            
        return $html;
	}	
 
	public $translateLabel = true;
  
    /**
     * Method to get the field label markup.
     *
     * @return  string  The field label markup.
     *
     * @since   1.7.0
     */
    protected function getLabel()
	{
		if ($this->hidden) {
            return '';
        }
//echo "<br>\$label: <pre>".JText::_((string)$this->element['label'])."</pre>"; 
//echo "<br>\$label: <pre>".print_r((string)$this->element['label'],true)."</pre>"; 
		return JText::_((string)$this->element['label']);
	}
    protected function getTitle()
	{
//echo "<br>\$label: <pre>".JText::_((string)$this->element['label'])."</pre>"; 
//echo "<br>\$label: <pre>".print_r((string)$this->element['label'],true)."</pre>"; 
		return JText::_((string)$this->element['label']);
	}
}
?>

 