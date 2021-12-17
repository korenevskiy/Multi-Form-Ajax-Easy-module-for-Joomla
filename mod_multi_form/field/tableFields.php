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

// Tasks:
//1. ДОбавить тег caption в поле TableFields
//   если есть description и нет caption =  то description в popup и в caption(hide)
//   если есть description и catpion =  то description в description и caption в caption
//   если есть caption и нет description = То caption в caption
//   У Caption есть expanded  оно будет показывать по умолчанию detail расскрыт или не расскрыт.
//   у Caption есть expander оно будет показывать должно оно быть скрываемым или не должно.
//2. XML должен использоваться только в Setup
//3. Fields должны генерироватся только в getInput
//4. Добавить методы AddRow, RemoveRow, AddColumn, RemoveColumn, getNamesColumns
//5. Перенести добавление колонок Index, Remove в getSetup
//

//return;       
//toPrint('Привет ДРУГ!!!!','Привет друг',0,'pre',true);
 
                            //use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Document\Document as JDocument;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Form\FormHelper as JFormHelper;
use Joomla\CMS\Form\Field\ListField as JFormFieldList; 
use \Joomla\CMS\Form\FormField as JFormField;
use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Layout\LayoutHelper as JLayoutHelper;
use Joomla\CMS\Layout\FileLayout as JLayoutFile; 
//use Joomla\CMS\Layout\BaseLayout as JLayoutBase;
//toPrint($this,'$this',0,'pre');

JHtml::_('jquery.framework'); 
JHtml::_('bootstrap.framework');

//JFormHelper::addFieldPath(__DIR__.DIRECTORY_SEPARATOR.'');

JFormHelper::loadFieldClass('field'); 
JFormHelper::loadFieldClass('list'); 

//namespace Joomla\CMS\Form\Field;

//   \Joomla\CMS\Form\FormField
//JFormHelper::loadFieldClass('filelist');
//use Joomla\CMS\Form\Field\FilelistField as JFormFieldFileList;

class JFormFieldTableFields extends JFormField  {//JFormField  //JFormFieldList
//Joomla\CMS\Form\Field\TableFieldsField/
//    JFormFieldTableFields     [type:protected] => Fields

// for "table_Fields"    class Joomla\CMS\Form\Field\Table\FieldsField/ 
// for "table_Fields"    class JFormFieldTable_Fields 
// for "table_Fields"    path  /modules/mod_multi_form/fields/table/
// for "table_Fields"    path  /modules/mod_multi_form/field/table/

//C:\Servers\OSPanel\domains\joomla/administrator/components/com_content/models/fields/modal/
//C:\Servers\OSPanel\domains\joomla/modules/mod_multi_form/fields/
//C:\Servers\OSPanel\domains\joomla/modules/mod_multi_form/fields
//C:\Servers\OSPanel\domains\joomla\administrator/components/com_modules/model/field
//C:\Servers\OSPanel\domains\joomla\administrator/components/com_modules/models/fields
//C:\Servers\OSPanel\domains\joomla/modules/mod_multi_form/field
//C:\Servers\OSPanel\domains\joomla\libraries/cms/form/field
//C:\Servers\OSPanel\domains\joomla\libraries/joomla/form/fields
//C:\Servers\OSPanel\domains\joomla\libraries\src\Form/fields
    
    
    /**
	 * Данные таблицы
	 *
	 * @var    array
	 * @since  3.2
	 */
    protected $value = [];
    
    /**
	 * Значения дынных по умолчанию для таблицы в JSON
	 *
	 * @var    string
	 * @since  3.2
	 */
    protected $default = '';
    
    /**
	 * Строка описания таблицы.
	 *
	 * @var    string
	 * @since  3.2
	 */
    public $caption = '';
    /**
	 * Перевод Caption
	 *
	 * @var    string
	 * @since  3.2
	 */
    public $translateCaption = true;
    
    /**
	 * Строка описания развертывание
	 *
	 * @var    bool
	 * @since  3.2
	 */
    public $captionExpander = true;
    
    /**
	 * Строка описания развернуто
	 *
	 * @var    bool
	 * @since  3.2
	 */
    public $captionExpanded = false;


//    /**
//	 * Строки данных.
//	 *
//	 * @var    array
//	 * @since  3.2
//	 */
//	public $rowsLayoutData = [];
    
	/**
	 * Поля
	 *
	 * @var    array
	 * @since  3.2
	 */
//	public $fields = [];
    
	/**
	 * Колонки с полями XML SimpleXMLElement.
     * array SimpleXMLElement
	 *
	 * @var    array
	 * @since  3.2
	 */
	protected $columnsXML = [];
    
	/**
	 * Данные колонок.
     * array FormField  
	 *
	 * @var    array
	 * @since  3.2
	 */
	public $columns = [];
    
	/**
	 * Ручная сортировка строк перетаскиванием
	 *
	 * @var    true
	 * @since  3.2
	 */
	public $movable = true;
    
	/**
	 * Добавление строк
	 *
	 * @var    true
	 * @since  3.2
	 */
	public $creatable = true;
    
	/**
	 * Удаление строк
	 *
	 * @var    true
	 * @since  3.2
	 */
	public $removable = true;

	/**
	 * Allows extensions to create repeat elements
	 *
	 * @var    true
	 * @since  3.2
	 */
	public $repeat = true;
    
    /**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'tableFields'; //table tableFields
    
	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $layout = 'tablefields';
	/**
	 * Layout to render the form field
	 *
	 * @var  string
	 */
//	protected $renderLayout = 'tablefields';

	/**
	 * Layout to render the label
	 *
	 * @var  string
	 */
	protected $renderLabelLayout = 'joomla.form.renderlabel';
	/**
	 * Method to instantiate the form field object.
	 *
	 * @param   Form  $form  The form to attach to the form field object.
	 *
	 * @since   1.7.0
	 */
    public function __construct($form = null){
        
//        $script = "jQuery(function(){
//            
//            });";
        $this->moduleid = $mod_id = JFactory::getApplication()->input->getCmd('id');
//        $this->moduleid = $mod_id = $this->form->getValue('id',null,0); 
        
        
        
        JDocument::getInstance()->addScriptDeclaration("console.log('🚀 Captcha_type-$mod_id:')");
        
        parent::__construct($form);
//toPrint($this->id); 
    }
    
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
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
//toPrint($this,'$this',0,'pre');
    
//        $element['multiple'] = true;

        $result = parent::setup($element, $value, $group);
        
        if(empty($result))
            return false;
        
        $this->id = str_replace('-', '_', $this->id);
        
//toPrint($this->id); 
//toPrint($element,'Setup: $element',0,'pre'); 

        //JDocument::getInstance()->addScriptDeclaration();
        
//toPrint($this);
        
//default="{       &quot;namefield&quot;:[&quot;Name&quot;,&quot;Phone&quot;],       &quot;nameforpost&quot;:[&quot;Name&quot;,&quot;Phone&quot;],       &quot;typefield&quot;:[&quot;text&quot;,&quot;text&quot;],         &quot;paramsfield&quot;:[&quot;&quot;,&quot;&quot;],      &quot;art_id&quot;:[&quot;&quot;,&quot;&quot;],        &quot;onoff&quot;:[&quot;2&quot;,&quot;2&quot;]       }
//value="{"namefield":["Nickname","Name","Phone","Email"],"nameforpost":["Nickname","Name","Phone","Email"],"typefield":["text","text","tel","email"],"paramsfield":["","","+9 (999) 999-99-99",""],"art_id":["","","",""],"onoff":["2","2","2","2"]}"
        
		// Set the group of the field.
		$this->group = $group;
        
		$children = [
			'default', 'field', 'template', 'newLine', 'sql', 'caption', ];
        $attributes = ['movable','creatable','removable', 'default',];
        
        $default = '';
        
        
        if($element['caption']){
            $this->caption = (string)$element['caption'];
        }
        if($element->caption){
            $this->caption = (string)$element->caption;
        }
        
        $caption = (string)$this->element['caption'] ?: (string) $this->element->caption ?: $this->label ?: '';
        if(isset($this->element['translate_caption']) || isset($this->element->caption['translate_caption']) || isset($this->element->caption['translate'])||
           isset($this->element['translateCaption']) || isset($this->element->caption['translateCaption']) || isset($this->element->caption['translate']))    
        $this->translateCaption = (bool)($this->element['translate_caption']?:$this->element->caption['translate_caption']?:$this->element['translateCaption'] ?: $this->element->caption['translateCaption'] ?: $this->element->caption['translate']);
        
        $caption = $caption && $this->translateCaption ? JText::_($caption) : $caption;
        
        
        if(isset($element->caption['expander'])){
            $this->captionExpander = (bool)$element->caption['expander'];
        }
        if($element->caption['expanded']){
            $this->captionExpanded = (bool)$element->caption['expanded'];
        } 
        
        
//		$this->default = isset($element['value']) ? (string) $element['value'] : $this->default;
        if($element->default){
            $this->default = (string)$element->default;
        }
        if($element->value){
            $this->default = (string)$element->value;
        }
        
//toPrint($default,'$default',0,'pre');
//toPrint($value,'$value',0,'pre');

		// Set the field default value.
		if (is_string($value) && is_array(json_decode($value, true)))
		{
			$this->value = (array) json_decode($value);
		}
		else
		{
			$this->value = $value;
		}
        
        if(empty($this->value) && $this->default && is_string($this->default)){
			$this->value = (array) json_decode($this->default);
        }
//        if($this->value && is_string($this->value)){
//			$this->value = (array) json_decode($this->value);
//        }
//toPrint($this->default,'$this->default',0,'pre');
        
//toPrint($this->value,'$this->value',0,'pre');
        $this->movable   = (bool) ($element['movable']  ?? true); 
        $this->creatable = (bool) ($element['creatable'] ?? true);
        $this->removable = (bool) ($element['removable'] ?? true);
        
//toPrint($this->value,'Value',0,'pre');
        $this->columnsXML = $element->xpath('field');//->children();
        
        foreach ($this->columnsXML as $k => $xml){ 
            if((string)$xml['name']){
                $this->columnsXML[(string)$xml['name']] = $xml;
                unset($this->columnsXML[$k]);
            }
//toPrint($column);
             
            $column = static::createColumn($xml, $k);
            
            $this->columns[$column->name] = $column;
        }
        
		return true;
	}
//toPrint($field,'$field',0,'pre'); //->children()
//toPrint($fieldXML,'$fieldXML',0,'pre'); //->children()
//toPrint($this->rowsLayoutData,'$this->rowsLayoutData',0,'pre'); 
//        $fieldText = new Joomla\CMS\Form\Field\TextField;
//toPrint((array)$element->xpath('field'),'$element',0,'pre'); //->children()
//toPrint($this->columns,'$element',0,'pre'); //->children()
//toPrint($this->columnsXML,'$element',0,'pre'); //->children()
        
//toPrint((string)$element->default,'Default: $element',0,'pre');
//toPrint($element,'Setup: $element',0,'pre');
//toPrint($this->columnsLayoutData,'Setup: $element',0,'pre');
        


    
//	protected $layout = 'joomla.form.field.table';


     

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
        return [];
        return parent::getOptions();
        
//        $opts = []; 
//        $options = []; 
//        
//        $opts['*']= ['*', '- - '.JText::_('JALL_LANGUAGE'). ": ★ " , '✔ - -'];//JHtml ✔
//                   
//        foreach (Joomla\CMS\Language\LanguageHelper::getKnownLanguages() as $opt){ //4
//            $opts[$opt['tag']]= [$opt['tag'], "$opt[nativeName]: $opt[tag]" , ' ◯'];//JHtml ✔
//        } 
//        foreach (Joomla\CMS\Language\LanguageHelper::getContentLanguages() as $opt){//3
//            //$options[$opt->lang_code] .= $opt->published
//            $opts[$opt->lang_code]= [$opt->lang_code, "$opt->title: $opt->lang_code", $opt->published?'✔':'◯'];//JHtml
//        } 
//        
//        foreach($opts as $opt){            
//            $options[$opt[0]]= JHtml::_('select.option', $opt[0], "$opt[1]: $opt[2]");//JHtml ✔✔✔✓✔
//        }
//        
//        //toPrint($ops,'$ops',0, true, true);    
//
//	 
//        $options = array_merge($options,parent::getOptions() );
//        
//        return $options;
        
    }

     
	/**
	 * Simple method to get the value
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function getValue()
	{
		return $this->value;
	}
    
	/**
	 * Метод получения разметки метки поля.
	 *
	 * @return  string  Разметка метки поля.
	 *
	 * @since   1.7.0
	 */
	protected function getLabel()
	{ 
        
		if ($this->hidden)
		{
			return '';
		}

		$data = $this->getLayoutData();

		// Forcing the Alias field to display the tip below
		$position = $this->element['name'] == 'alias' ? ' data-placement="bottom" ' : '';

		// Here mainly for B/C with old layouts. This can be done in the layouts directly
		$extraData = array(
			'text'        => $data['label'],
			'for'         => $this->id,
			'classes'     => explode(' ', $data['class']),
			'labelclasses'=> explode(' ', $data['labelclass']),
			'position'    => $position,
		);

        
		return $this->getRenderer($this->renderLabelLayout)->render(array_merge($data, $extraData));
         
    }
    
	/**
	 * Метод для рендеринга атрибутов данных в html.
	 *
	 * @return  string  A HTML Tag Attribute string of data attribute(s)
	 *
	 * @since  4.0.0
	 */
	public function renderDataAttributes(){
        
        $dataAttributes = [];
        if(method_exists($this, 'getDataAttributes')){
            $dataAttributes = $this->getDataAttributes();
        }
        
		$dataAttribute  = '';

		if (!empty($dataAttributes))
		{
			foreach ($dataAttributes as $key => $attrValue)
			{
				$dataAttribute .= ' ' . $key . '="' . htmlspecialchars($attrValue, ENT_COMPAT, 'UTF-8') . '"';
			}
		}

		return $dataAttribute;
	} 
    
    public static function loadField($type, $value = null, $data = [], $XMLElement = null) {
        
        $field = JFormHelper::loadFieldType($type);
        
		if ($field === false){
			$field = JFormHelper::loadFieldType('text');
		}
        
        $field->renderLabelLayout = 'joomla.form.renderlabel';
        
//toPrint($field,'$field',0,'pre');
//toPrint($data,'$data',0,'pre');
        
        if($XMLElement){
            $field->setup($XMLElement, $value);
        }
//toPrint((array)$data,'$data',0,'pre');
//try {
        foreach ((array) $data as $prop => $val) {
            if(in_array($prop, ['classes','labelclasses']))
                continue;
            $field->__set($prop, $val);
        }
        
//        if($data['fieldname'])
//toPrint($field,'$field',0,'pre');
//toPrint($field->name,'$field->name : '.$field->type,0,'pre');
//} catch (Exception $exc) {
////    echo $exc->getTraceAsString();
//    toPrint($exc->getTraceAsString(),'ERROR',0,'pre');
//}

//        return $field;
        
//        if(empty($field->name) && empty($XMLElement['name'])){
//            $field->name = $field->type;
//            $field->fieldname = $field->type;
//        }
        
//        $field->translateLabel = (bool) ($XMLElement['translateLabel'] ??  true);
//        if(empty($data->translateLabel) && $data->translate_label)
//            $data->translateLabel = (bool)$data->translate_label;
//        if($data->translateLabel)
//            $data->label = JText::_($data->label);
//        
//        $data->translateDescription = (bool) ($XMLElement['translateDescription'] ??  true);
//        if(empty($data->translateDescription) && $data->translate_description)
//            $data->translateDescription = (bool)$data->translate_description;
//        if($data->description && $data->translateDescription)
//            $data->description = \JText::_($data->description);
        
        
        
        return $field;
            
            [ 'element','field','group','repeat','type','value','dataAttribute','dataAttributes','text','for','classes','classCell','classHeader','position',
            'fieldname','sortable', ];
            [ 'element','field','group','repeat',                                               'text','for','classes','classCell','classHeader','position',
            'fieldname','sortable',  ];
    }
    public function getLayoutColumns() {
         
        $columnsField = [];
        
        foreach ($this->columns as $column){ 
            
            if ($column->default && in_array($column->translateDefault, ['true','1',true,1]) ){
                $lang = JFactory::getLanguage();

                if ($lang->hasKey($field->default)){
                    $debug = $lang->setDebug(false);
                    $column->default = JText::_($column->default);
                    $lang->setDebug($debug);
                }
                else{
                    $column->default = JText::_($column->default);
                }
            }
            
//            if(empty($column->name) && empty($column->element['name'])){
//                $column->name = $column->type;
//                $column->fieldname = $column->type;
//            }
            if(empty($column->value))
                unset ($column->value);
            
//toPrint($column->default,'$column->default',0,'pre');
            $columnsField[$column->name] = static::loadField($column->type, $column->default, $column, $column->element);
            
//            $this->value = $this->getValue((string) $this->element['name'], $group, $default);
        }
        return $columnsField;
    }
    
    public function getLayoutFields() {
        
        $value = $this->value;
        $data = [];
        $fields = [];
        
//toPrint($this->columns,'$this->columns',1,'pre');
//toPrint('$val FIELDS -----------------------!','',0,'pre');
        foreach ($this->columns as $column){
            $name = $column->name;
//toPrint($name,'$val',0,'pre');
//            $colData = $value[$name];
            if(empty($column->name) || empty($this->value[$column->name]) || is_array($this->value[$column->name]=== false)){
//                $column->translateLabel = true;
//                $column->translateDescription = true;
//                $column->translateDescription = true;
//                $column->translateDefault = false;
//                $value = $column->velue ?: $column->default;
//                $value = empty($column->value) && $column->default && $column->translateDefault ? JText::_($column->default) : $column->default;
//                $fields[$i][$column->name] = $column;
                continue;
            }
                
            
            foreach ($this->value[$column->name] as $i => $val){
                $data[$i][$column->name] = $val; //$this->value[$column->name][$i]; 
                $param = [
                    'id'    =>$this->id. '_'.$column->name. '_'.$i,
                    'name'  =>$this->name.'['.$column->name.'][]',
                    'row'   =>$i,
                    ];
//toPrint($param,'$param',0,'pre');
//toPrint($val,'$val',0,'pre');
                $field = static::loadField($column->type, $val, $param, $column->element);
                $fields[$i][$column->name] = $field;
//                $field = JFormHelper::loadFieldType($column->type);
//                $field->setup($column->element, $val);
//                $field->id = $this->id. '_'.$field->name. '_'.$i; 
//                $field->name = $this->name.'['.$field->name.'][]';//.'[]' jform[params][textsubmit]   $this->name.'[]'   nameforpost[]
//                $field->row = $i;
//$field->label .= $field->name;            jform[params][tbl][nameforpost][]

//toPrint($field->name,'$field->name',0,'pre');
//                $field->html = $field->render('default');// renderField(); $value
                $field->html = $field->renderField(['hiddenLabel'=>true]);// renderField(); $value
//toPrint($field->name,'$field->name',0,'pre');
//                $column->fields[$i] = $field;
//                $fields[] = $field;
//toPrint($field->id,'$field->id',0,'pre');
            }
//toPrint($column,'$column',0,'pre');
        }
//toPrint($fields,'$this->value[$column->name]',1,'pre');
        return $fields;
    }

    /**
     * 
     * @param type $dataColumn object created static::createColumn($xml, $k);
	 * @return  int return index in array columns
     */
    public function addColumn($dataColumn, $key = -1) { 
        
        if(empty($dataColumn))
            unset($this->columns[$key]);
        elseif($key != -1)
            $this->columns[$key] = $dataColumn;
        elseif($dataColumn->name)
            $this->columns[$dataColumn->name] = $dataColumn;
        else
            $this->columns[] = $dataColumn; 
        
        if($key == -1)
            return array_search($dataColumn, $this->columns);
        return $key;
    }
    
    /**
     * 
     * @param type $dataRow object with data rows, where property is names columns
     * @param  $key string||int key for position in array data values, Default -1 where element will be last position
	 * @return  int return index in array columns
     */
    public function addRow($dataRow, $key = -1) {
        if($key == -1){ //Определение максимального ключа массивов из всех колонок
            foreach ($this->columns as $column){
                foreach (array_keys($column) as $k){
                    if(is_int($k) && $max_count < $k){
                        $key = $k;
                    }
                }
            }
        }
        
        foreach($this->columns as $column){
            $name = $column->name;
            if(empty($dataRow))
                unset ($this->value[$name][$key]);
            else
                $this->value[$name][$key] = $dataRow->$name ?: '';
        }
        
        return $key;
    }
    
    /**
	 * Метод получения данных FIELD для  макета для рендеринга. 
	 * @param   SimpleXMLElement  $XMLElement  Load  data from $XMLElement. Or load Default data 
	 * @param   string||int  $defaultName  The form to attach to the form field object.
	 *
	 * @return  array
	 *
	 * @since 3.5
	 */
    public static function createColumn(\SimpleXMLElement $XMLElement = null, $defaultName = ''){ 
                   
        $atributes = [
            'autocomplete','autofocus','class','description','default','disabled',
            'element','field','group','hidden','hint',
            'id','label','labelclass','multiple','showon',
            'name','onchange','onclick','pattern','validationtext','readonly','repeat','required','size','spellcheck',
            'type','validate','value','dataAttribute','dataAttributes',
            'text','for','classes','classCell','classHeader','position',
            'fieldname','translateLabel','translateDescription','translateHint', 'translateDefault',
            'sortable', 'noname',
            'creatable','movable','removable',
//            'translate_label', 'translate_description', 'translate_default', 
            ];
        
//        $column = new class{ 
//        };
        
        $data = (object)array_fill_keys($atributes, '');
        
        $data->noname = false;
        $data->translateLabel = true;
        $data->translateDescription = true;
        $data->translateDescription = true;
        $data->translateDefault = false;
        $data->name = $defaultName;
        
        if(empty($XMLElement)){
            return $data;
        }
        
        foreach($XMLElement->attributes() as $attr => $val){
            $data->$attr = (string)$val; 
        }
        
        
        $data->noname = empty($XMLElement['name']); 
        $data->type = $data->type ?: 'text';
        $data->name = $data->name ?: $defaultName;
        
        $data->value = $data->value ?: (string)$XMLElement['value'] ?: '';
        $data->default = $data->default ?: (string)$XMLElement['default'] ?: '';
        

        
        $data->element = $XMLElement;
        
        
        if(empty($data->label))
            $data->label = $data->name;
            
        $data->translateLabel = (bool) ($XMLElement['translateLabel'] ??  true);
        if(empty($data->translateLabel) && $data->translate_label)
            $data->translateLabel = (bool)$data->translate_label;
        if($data->translateLabel)
            $data->label = JText::_($data->label);
        
        $data->translateDescription = (bool) ($XMLElement['translateDescription'] ??  true);
        if(empty($data->translateDescription) && $data->translate_description)
            $data->translateDescription = (bool)$data->translate_description;
        if($data->description && $data->translateDescription)
            $data->description = \JText::_($data->description);
        
        
        $value = empty($data->value) && $data->default && $data->translateDefault ? JText::_($data->default) : $data->default;
        $data->html = $data->value ?: $value;
        
        $alt = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $data->fieldname);
        
        $data->hint = $data->translateHint ? JText::alt($data->hint, $alt) : $data->hint;
        
        $data->required = (bool) $data->required;
        $data->field = NULL;
        $data->classes = explode(' ', $data->class);
        $data->text = $data->label;
        $data->for = $data->id;
        $data->dataAttributes = [];
        $data->dataAttribute = '';
        $data->position = $data->name == 'alias' ? ' data-placement="bottom" ' : '';
        return $data;
    }
    
    /**
	 * Метод получения данных FIELD для  макета для рендеринга. 
     * 
	 * @param   object  $field  The form to attach to the form field object.
	 *
	 * @return  array
	 *
	 * @since 3.5
	 */
    function getLayoutData(){
        

            $label = $this->label ?: (string)$this->element['label'] ?: (string) $this->element['name'] ?: $this->fieldname ?: '';
//toPrint($label,'$label',0,'pre');
//toPrint($this,'$this',0,'pre');
//toPrint($this->label,'$this->label',0,'pre');      
            if(isset($this->element['translate_label']))
                $this->translateLabel = (bool)$this->element['translate_label'];
            if(isset($this->element['translateLabel']))
                $this->translateLabel = (bool)$this->element['translateLabel'];
            $label = $this->translateLabel ? JText::_($label) : $label;
      
            $description = $this->description ?: (string)$this->element['description'] ?: (string) $this->element->description ?: '';
            if(isset($this->element['translate_description']))
                $this->translateDescription = (bool)$this->element['translate_description'];
            if(isset($this->element['translateDescription']))
                $this->translateDescription = (bool)$this->element['translateDescription'];
            $description = $description && $this->translateDescription ? JText::_($description) : $description;
            
            $caption = $this->caption ?: (string)$this->element['caption'] ?: (string) $this->element->caption ?: $label ?: '';
            if(isset($this->element['translate_caption']) || isset($this->element->caption['translate_caption']) || isset($this->element->caption['translate']))
                $this->translateCaption = (bool)($this->element['translate_caption']?:$this->element->caption['translate_caption']?:$this->element->caption['translate']);
            if(isset($this->element['translateCaption']) || isset($this->element->caption['translateCaption']) || isset($this->element->caption['translate']))
                $this->translateCaption = (bool)($this->element['translateCaption'] ?: $this->element->caption['translateCaption'] ?: $this->element->caption['translate']);
            $caption = $caption && $this->translateCaption ? JText::_($caption) : $caption;
             
            
            $captionExpander = (bool)($this->captionExpander && $description);
            
            $alt = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);   
            
            $position = (string)$this->element['name'] == 'alias' ? ' data-placement="bottom" ' : '';
            return [
                
                'autocomplete'   => $this->autocomplete,
                'autofocus'      => $this->autofocus,
                'class'          => $this->class,
                'description'    => $description,
                'disabled'       => $this->disabled,
                'field'          => $this,
                'group'          => $this->group,
                'hidden'         => $this->hidden,
                'hint'           => $this->translateHint ? JText::alt($this->hint, $alt) : $this->hint, 
                'id'             => $this->id,
                'label'          => $label,
                'labelclass'     => $this->labelclass,
                'multiple'       => $this->multiple,
                'name'           => $this->name,
                'onchange'       => $this->onchange,
                'onclick'        => $this->onclick,
                'pattern'        => $this->pattern,
                'validationtext' => $this->validationtext,
                'readonly'       => $this->readonly,
                'repeat'         => $this->repeat,
                'required'       => (bool) $this->required,
                'size'           => $this->size,
                'spellcheck'     => $this->spellcheck,
                'type'           => $this->type,
                'validate'       => $this->validate,
                'value'          => $this->value, 
                'dataAttribute'  => $this->renderDataAttributes(),
                'dataAttributes' => $this->dataAttributes,
                'text'        => $label,
                'for'         => $this->id,
//                'classes'     => explode(' ', $this->labelclass),
                'position'    => $position,
                
                
                'captionExpander' => $this->captionExpander,
                'captionExpanded' => $captionExpander,
                'caption'   => $caption,
                'creatable' => (bool) ($this->creatable ?? true),
                'movable'   => (bool) ($this->movable   ?? true),
                'removable' => (bool) ($this->removable ?? true), 
            ]; 
            

 
    }
    
    
 
    
    /**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.7.0
	 */
	protected function getInput()
	{
        $data = $this->getLayoutData();
        
        
        if(true){
            $columnIndex = static::createColumn(null); 
            $columnIndex->translateLabel = false;
            $columnIndex->label = '#';
            $columnIndex->default = '::';
            $columnIndex->type = 'index';
            $columnIndex->name = 'i';
            $columnIndex->id = 'i';  
            $columnIndex->classHeader = '';
            $columnIndex->html = '';  
            $columnIndex->movable = $this->movable; 
            $columnIndex->classCell = ' ';  
        }
        if($this->creatable || $this->removable){
            $columnRemove = static::createColumn(null); 
            $columnRemove->translateLabel = false;
            $columnRemove->label      = '+';
            $columnRemove->default    = 'X';
            $columnRemove->type       = 'new_del';
            $columnRemove->name       = 'new_del';
            $columnRemove->id         = 'new_del';
            $columnRemove->classHeader = ''; 
            $columnRemove->html = '';
            $columnRemove->creatable  = $this->creatable;
            $columnRemove->removable  = $this->removable; 
            $columnRemove->classCell  = ' red';
        }
        
        
		
//            [
//            'autocomplete','autofocus','class','description','default','disabled',
//            'element','field','group','hidden','hint',
//            'id','label','labelclass','multiple','showon',
//            'name','onchange','onclick','pattern','validationtext','readonly','repeat','required','size','spellcheck',
//            'type','validate','value','dataAttribute','dataAttributes',
//            'text','for','classes','classCell','classHeader','position',
//            'fieldname','translateLabel','translateDescription','translateHint',
//            'translate_label', 'translate_description',
//            'sortable'
//            ]; 
        
//toPrint($this,'$this',0,'pre');
//        toPrint($this->value,'$this->value',0,'pre');
//        toPrint($this->id,'$this->layout',0,'pre');
//        $html .= "funInput: - <br>Default: <b>$this->default</b>  <br> ID: <b>$this->id</b>  <br> Type: <b>$this->type</b> <br> Name: <b>$this->name</b> <br><br>";

        if(empty($this->layout))
            $this->layout = $this->type;
        
        $data['id'] = $this->id;
        
        $data['data'] = $data;
        
        $data['columns'] = $this->getLayoutColumns();
        
//toPrint($data['columns'],'$data[columns]',0,'pre');
        foreach ($data['columns'] as $col){
            $col->html = $col->noname ? $col->html : $col->renderField(['hiddenLabel'=>true]);// renderField(); $value
        }
        
        
        $data['fields'] = $this->getLayoutFields();
         
//toPrint($this->label,'$label',0,'pre');
        $data['columns'] = array_merge([], ['i'=>$columnIndex],$data['columns']);
        
        if($this->creatable || $this->removable)
            $data['columns']['coldel'] = $columnRemove;
        
        foreach ($data['fields'] as $row) { 
            foreach ($row as $field) {
                $field->html = $field->renderField(['hiddenLabel' => true]); // renderField(); $value
            }
        }
        
        
        $layoutPath1 = realpath(__DIR__.'/../layouts/');  
        $layoutPath2 = realpath(__DIR__.'/layouts/');     
        return $this->getRenderer($this->layout)->addIncludePath($layoutPath1)->addIncludePath($layoutPath2)->render($data);  
        return parent::getInput();
        
        $data['rowsLayoutData'] = $this->rowsLayoutData;
 

//toPrint($data['fields'],'Fields',1,'pre');
//        try {
//toPrint($data['columns'],'Fields',0,'pre');
//		$data = $this->getLayoutData(); 
//		$data['options'] = (array) $this->getOptions();
//		return $this->getRenderer($this->layout)->render($data);
	}
    
}



?> 