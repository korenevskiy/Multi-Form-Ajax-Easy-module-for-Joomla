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
class JFormFieldOptions extends \Joomla\CMS\Form\Field\ListField // /libraries/src/Form/FormField.php \Joomla\CMS\Form\FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $type = 'format';  
    
	/**
	 *  
	 *
	 * @var    bool
	 * @since  3.2
	 */
	protected $formating = false; 
    
	
	
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
		$options = parent::getOptions();
		
		
		$options[] = ['disable'  => true, 'text'=> '_________', 'value'=>'','class'    => 'disable'];
		
//toPrint( realpath(__DIR__ . "/../options/").'/*.php' ,'realpath',0,'message',true);
//toPrint((array)glob(realpath(__DIR__ . "/../options/").'/*.php'),'realpath',0,'message',true);
//toPrint((array)glob(realpath(__DIR__ . "/../options/*.php")),'(array)glob',0,'message',true);
		
		$options_XML = (array)glob(realpath(__DIR__ . "/../options/").'/*.xml');
		$options_PHP = (array)glob(realpath(__DIR__ . "/../options/").'/*.php');
		
		
		foreach (array_unique(array_merge($options_XML,$options_PHP)) as $filename) {
//			echo "$filename размер " . filesize($filename) . "\n";
			
			$file = basename($filename,'.php');
			$file = basename($file,'.xml');
			
//toPrint($filename,'$filename',0,'pre',true);
//toPrint($file,'$filename',0,'message',true);

			if(str_starts_with($file, '_') || str_ends_with($file, '_'))
				continue;
			
            $options[$file] = (object)[
                    'value'    => $file,
                    'text'     => ucfirst(JText::alt($file, $this->fieldname)),
                    'disable'  => false,
                    'class'    => 'options ',// . (string)$this->fieldname,
                    'option.class'    => 'options ' . (string)$this->fieldname,
//					'onclick'  => "console.log('$file)",
//                    'selected' => false,
//                    'checked'  => false,
            ];
		}
		
        return $options;
    }
	
	
	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  3.5
	 */
//	protected $layout;
	/**
	 * Layout to render the form field
	 *
	 * @var  string
	 */
//	protected $renderLayout = 'joomla.form.renderfield';

	/**
	 * Layout to render the label
	 *
	 * @var  string
	 */
//	protected $renderLabelLayout = 'joomla.form.renderlabel';
    
	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.7.0
	 */
//	public function setup(\SimpleXMLElement $element, $value, $group = null)
//    {
//        $setup = parent::setup($element, $value, $group);
//        
//        if(empty($setup))
//            return false;
//        
//        $this->property = (string)$element['property'];
//        $this->formating = (string)$element['formating'];
//        
//        
//        $element->children();
//    }
    
    public $object;
    
    public function setObject($object) {
        $this->object;
    }
    
    public function getObject($object) {
        return $this->object;
    }
    
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
//	public function getInput()
//	{ 
//        $content = (string) $this->element;//->children()
//        
////toPrint((string)$this->element,'$this->element',0);
////toPrint($content,'$content',0);
////return '';
//         
//        extract($this->object);
//        
//        $content = $this->formating?JText::_($content):$content;
//        
//        $html = trim(vsprintf($content, (array)$this->object));
//        
//        return $html;
//        
//	}
}
