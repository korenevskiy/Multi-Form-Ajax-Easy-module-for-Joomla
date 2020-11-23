<?php defined('_JEXEC') or die; 
/**------------------------------------------------------------------------
 * mod_multi - Modules Conatinier 
 * ------------------------------------------------------------------------
 * author    Sergei Borisovich Korenevskiy
 * Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 * @package  mod_multi
 * @license  GPL   GNU General Public License version 2 or later;  
 * Websites: //explorer-office.ru/download/joomla/category/view/1
 * Technical Support:  Forum - //fb.com/groups/multimodule
 * Technical Support:  Forum - //vk.com/multimodule
 */ 

toPrint('Привет ДРУГ!!!!','Привет друг',0,'pre',true);
 
use Joomla\CMS\Form\FormHelper as JFormHelper;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Document\Document as JDocument;

JFormHelper::loadFieldClass('list'); 


//JFormHelper::loadFieldClass('filelist');

use Joomla\CMS\Form\Field\ListField as JFormFieldList;          
//use Joomla\CMS\Form\Field\FilelistField as JFormFieldFileList;

class JFormFieldTableFields extends JFormFieldList  {//JFormField

    
	/**
	 * Method to instantiate the form field object.
	 *
	 * @param   Form  $form  The form to attach to the form field object.
	 *
	 * @since   1.7.0
	 */
    public function __construct($form = null){
        
        $script = "jQuery(function(){
            
            });";
        
        
        JDocument::getInstance()->addScriptDeclaration("console.log('🚀 Captcha_type-$mod_id:',$captcha_type)");
        
        parent::__construct($form);
    }
    
 	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Languages';

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
        return '123';
        $opts = []; 
        $options = []; 
        
        $opts['*']= ['*', '- - '.JText::_('JALL_LANGUAGE'). ": ★ " , '✔ - -'];//JHtml ✔
                   
        foreach (Joomla\CMS\Language\LanguageHelper::getKnownLanguages() as $opt){ //4
            $opts[$opt['tag']]= [$opt['tag'], "$opt[nativeName]: $opt[tag]" , ' ◯'];//JHtml ✔
        } 
        foreach (Joomla\CMS\Language\LanguageHelper::getContentLanguages() as $opt){//3
            //$options[$opt->lang_code] .= $opt->published
            $opts[$opt->lang_code]= [$opt->lang_code, "$opt->title: $opt->lang_code", $opt->published?'✔':'◯'];//JHtml
        } 
        
        foreach($opts as $opt){            
            $options[$opt[0]]= JHtml::_('select.option', $opt[0], "$opt[1]: $opt[2]");//JHtml ✔✔✔✓✔
        }
        
        //toPrint($ops,'$ops',0, true, true);    

	 
        $options = array_merge($options,parent::getOptions() );
        
        return $options;
        
    }
}