<?php namespace Joomla\Module\MultiForm\Site; defined('_JEXEC') or die;
/**------------------------------------------------------------------------
 * field_cost - Fields for accounting and calculating the cost of goods
 * ------------------------------------------------------------------------
 * author    Sergei Borisovich Korenevskiy
 * Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 * @package  mod_multi_form
 * @license  GPL   GNU General Public License version 2 or later;
 * Websites: //explorer-office.ru/download/joomla/category/view/1
 * Technical Support:  Forum - //fb.com/groups/multimodule
 * Technical Support:  Forum - //vk.com/multimodule
 */

use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;
use Joomla\Registry\Registry as JRegistry;
use Joomla\CMS\Form\Field\ListField as JFormFieldList;
use \Joomla\CMS\Form\FormField as JFormField;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Factory as JFactory;

use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Layout\LayoutHelper as JLayoutHelper;
use Joomla\CMS\Layout\FileLayout as JLayoutFile;
use \Joomla\CMS\Version as JVersion;
use Joomla\CMS\Form\Form as JForm;
use Joomla\CMS\Language\Language as JLanguage;



//toPrint(null,'' ,0,'');
//toPrint(JFactory::getApplication()->input,'POST' ,0,'message');



class OptionCalculator extends \Joomla\Module\MultiForm\Site\Option  { // JFormFieldCalculator  //  extends JFormField  

	public $type			= 'calculator';
	public $stages = 1;
	
	public $path = '';
	public $file = '';	
	
	protected $optionsDatas = [];





//	public function getName($i = '') : string{
//		return  $this->type . $i . $this->moduleID; 
//	}
	
	
	public function getJSON() : string{
		
		$params = $this->paramsOption;
		
		$field_id = 0;
		
		if(is_numeric($params->field_id))
			$field_id = (int)$params->field_id;
		
//		$db = JFactory::getDbo();
		$db = JFactory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);
		$query = "
SELECT name 
FROM #__fields 
WHERE `state` > 0 AND id = $field_id;
		";
		
		$app = JFactory::getApplication();
		
		$articleID = 0;
		
		/** Get the article ID */
		if ($app->input->get('option') === 'com_content' && $app->input->get('view') === 'article') {
			$articleID = $app->input->getInt('id', 0);
		}
//toPrint($sql,'$sql',0);

		$result = [
			'article_id'			=> $articleID,
			'module_id'				=> $this->moduleID,
			'field_name_value'		=> $db->setQuery($query)->loadResult() ?? '', //field_name_value
//			'field_title'	=> $params['field_title'],
			
			'field_label_select'	=> $params->field_label_select,
			'field_label_selected'	=> $params->field_label_selected,
			
			'require'				=> $params->require == '1',
			
			'field_nodelete'		=> $params->field_nodelete == '1',
			'field_selectopencart'	=> $params->field_selectopencart == '1',
			
			
			'field_name'			=> $this->nameinput,
			'onlyone'				=> $params->onlyone == '0' ? false : (string)$params->onlyone,
			
			
			'field_id'				=> $params->field_id,
			'field_type'			=> $params->field_type,
			'field_plusminus'		=> $params->field_plusminus,
			'field_format'			=> $params->field_format,
				'require_message'	=> $params->require_message,
//				'name_quantity'	=> $params->name_quantity,
//				'name_ammount'	=> $params->name_ammount,
			'class_article'			=> $params->class_article,
			'class_field'			=> $params->class_field,
			'class_title'			=> $params->class_title,
			'class_link'			=> $params->class_link,
			'class_label'			=> $params->class_label,
			
			'label_format_1'	=> $params->label_format_1,
			'label_format_2'	=> $params->label_format_2,
			'label_format_3'	=> $params->label_format_3,
			'label_format_4'	=> $params->label_format_4,
			'info_format'		=> $params->info_format,
			'rounding'				=> $params->rounding,
			'field_cart'			=> $params->field_cart,
		];
		

//		$fields = $db->loadAssocList('id', 'name');// loadObjectList();//loadObjectList('id'); ??
		
		if(empty($result['field_name_value']) && ! in_array($params->field_id, ['class','field']))
			return '';
		
		return json_encode($result, JSON_FORCE_OBJECT);// "$moduleID  $fieldName $optionParams";
	}
	 

	public function renderModule(\Joomla\CMS\WebAsset\WebAssetManager $webAsset = null) : string{
		
		
//toPrint();
//toPrint($this->paramsOption,'$result:'.$fieldName,0,'message'); 
		$script = "
console.log('Test 1');
jQuery(function($) {
console.log('Test 2');
$('input[name=\"$this->nameinput\"]').rules('add', {
	required: true,
	messages: {
		required: '".addslashes($this->paramsOption->require_message)."'
	}
});
});		";
		
		if($webAsset && $this->paramsOption->require_message){
			$webAsset->addInlineScript(
				$script,
				[],
				['type'=>'application/javascript','class'=>"modMultiForm calculator id$this->moduleID"],
				['jquery']
			);//'id'=>'modMultiForm'.$type.$module->id,
		}
		
		return parent::renderModule($webAsset);
	}
	
	public function getLabels() : string|array {
		return ['hide' => $this->field_label,'cost' => 'Сумма','count' => 'Количество' ];
	}

	public function renderFields($fldName='', $value='', $fldClass='', $placeholder='') :  string| array
	{
//		$field->html = JLayoutHelper::render($field->layout, $dataField, $field->layoutPath);//, $basePath,$options
//		$field->html = $field->renderFields(['inlineHelp' => true, 'hidden' => true,'hiddenLabel' => true,'hiddenDescription'=>true,'id'=>$field->id]); // renderFields(); $value    ,'id'=>$field->id.'_'.$i
//		public function renderFields($options = [])
//		public function render($layoutId, $data = [])
		
//		JFactory::getApplication()->getDocument();
//		Joomla\CMS\Document\HtmlDocument::addScript($url);
		
//		echo "<br>";
		
//		echo \Joomla\CMS\Uri\Uri::base();
//toPrint();
//toPrint($this->paramsOption->require,'$this->paramsOption-',0,'pre',true);
		$require			= $this->paramsOption->require == 1 ? 'required' : '';
		$require_message = addslashes($this->paramsOption->require_message??'');
		$require_msg = $require_message ? " data-msg='$require_message' " : '';
		
		$fldName = $fldName ?: $this->nameinput;
$testData = '';// "<label><span class='col2'>Годовой доступ</span><span class='col4'>= 6000 ₽</span><input type='hidden' value='1' name='costs175[]' min='0'><input class='option' type='hidden' value='{&quot;count&quot;:1,&quot;cost&quot;:6000,&quot;title&quot;:&quot;Годовой доступ&quot;,&quot;link&quot;:&quot;/platform-games/godovaya-igra&quot;,&quot;label&quot;:&quot;Годовой доступ&quot;,&quot;field_name&quot;:&quot;costs&quot;,&quot;type&quot;:&quot;checkbox&quot;,&quot;indexProd&quot;:1742490116797}' name='costs[options][]'><button class='btn button'>❌</button></label>";
$count = 0;// 1;
$cost = 0;// 6000;		
		$inputCount	= "<input id='count{$this->nameinput}{$this->moduleID}' name='{$fldName}[count]' class=' $fldClass  calculator count' type='text' readonly $require value='$count'  step='any' min='0' placeholder='0' $require_msg>";
		$inputCost	= "<input id='cost{$this->nameinput}{$this->moduleID}' name='{$fldName}[cost]' class='$fldClass  calculator cost' type='text' readonly  value='$cost'  step='any' min='0'>";
		
		$info_format	= $this->paramsOption->info_format ?: "{quantity} {cost}";
		$info_format	= str_ireplace('{count}',	$inputCount,$info_format);
		$info_format	= str_ireplace('{quantity}',$inputCount,$info_format);
		$info_format	= str_ireplace('{cost}',	$inputCost,	$info_format);
		$info_format	= str_ireplace('{ammount}',	$inputCost,	$info_format);
		
		if($require && $require_message)
			$require_message
				= "<label id='count$this->nameinput$this->moduleID-error' for='count$this->nameinput$this->moduleID' class='error'  title='$require_message'>{$this->paramsOption->require_message}</label>";
//				   <label id="count$this->nameinput$this->moduleID-error" class="error" for="count$this->nameinput$this->moduleID" style="display: none;">{$this->paramsOption->require_message}</label>
		
//<info _id='info$this->moduleID'  class='calculatorInfo'></info>
$require_message='';

return $require_message . "<ul id='list$this->moduleID' class='calculatorList'>$testData</ul>
<label id='info$this->moduleID'  class='calculatorInfo calculator' data-idcost='cost$this->nameinput$this->moduleID' data-idcount='count$this->nameinput$this->moduleID'>$info_format</label>";
//		return '123->getInput()name:'.$this->field_name. " fieldname:$this->field_name id:$this->moduleID";
	}
//	display: inline;
//  width: auto;
//  max-width: unset;
//  min-width: unset;
//  background: rgba(0, 0, 0, 0);
//  border: none;
//  outline: 0;
//  cursor: text;
//  width: min-content;
//  display: inline;
//  resize: none;
//  all: unset;
//  appearance: none;
	
    /**
     * Method to get the data to be passed to the layout for rendering.
     *
     * @return  array
     *
     * @since 3.5
     */
//	protected function getLayoutData(){
//		return [];
//	}
//	
//	
//	public function getLabel() {
//		return '';
//	}
//	public function getTitle() {
//		return '';
//	}
//	public function getId($fieldId, $fieldName) {
//		return '';
//	}
	
	
	
	
	
	/**
	 * Возвращает массив подполей опции класса <b>FieldData</b>
	 * Объект подготовленных данных для платежа
	 * Возвращает Null в случае ошибки(отказа)
	 * @return array|null
	 */
	public function dataCompute($selfDatas = [], $stage = 0) : ?array {
	
//\modMultiFormHelper::debugPRE(null, '', $this->modeApiAjax);

//\modMultiFormHelper::debugPRE('<hr>');
//\modMultiFormHelper::debugPRE('<hr>');
//
//\modMultiFormHelper::debugPRE($this->moduleID, 'calc $this->moduleID');
//\modMultiFormHelper::debugPRE($this->nameinput, 'calc $this->nameinput');
//\modMultiFormHelper::debugPRE($this->orderID, 'calc $this->orderID');
//\modMultiFormHelper::debugPRE($this->paramsOption, 'calc $this->paramsOption');
//
//\modMultiFormHelper::debugPRE('<hr>');
//\modMultiFormHelper::debugPRE('<hr>');
//		if($this->orderID)
//		$opData->orderID = $this->orderID;)
		
		
		
		
		if($selfDatas && empty($this->value)){
			$this->optionsDatas = $selfDatas;
			
//			foreach ($this->optionsDatas as $optD){
//				$count += $optD->count;
//			
//				if($optD->sign == '+') $sum = $sum + ($optD->count * $optD->cost);
//				if($optD->sign == '-') $sum = $sum - ($optD->count * $optD->cost);
//				if($optD->sign == '/') $sum = $sum / ($optD->count * $optD->cost);
//				if($optD->sign == '*') $sum = $sum * ($optD->count * $optD->cost);
//			}
			
			return null;
		}
		
//echo "<pre> \$this->field_name ". print_r($this->nameinput, true).'</pre>';

		$vals = (array)JFactory::getApplication()->input->getString($this->nameinput, []);
//echo "<pre>\$vals ". print_r($vals, true).'</pre>';
		$this->optionsDatas = (array) ($vals['options'] ?? [] );

		
		
		foreach ($this->optionsDatas as $i => $od){
			$od = json_decode($od);
			

			
			$this->optionsDatas[$i] = new \Joomla\Module\MultiForm\Site\OptionData;
			$this->optionsDatas[$i]->sign		= '+';
			$this->optionsDatas[$i]->cost		= $od->cost ?? 0;
			$this->optionsDatas[$i]->count		= $od->count ?? 0;
			$this->optionsDatas[$i]->orderID	= $this->orderID ?? 0;
			$this->optionsDatas[$i]->type		= $this->type;
			$this->optionsDatas[$i]->field_name	= $od->field_name ?? '';
			$this->optionsDatas[$i]->title		= $od->title ?? '';
			$this->optionsDatas[$i]->label		= $od->label ?? '';
			$this->optionsDatas[$i]->description= $od->title . ' /' . $od->label;
			$this->optionsDatas[$i]->i			= $this->index;
			$this->optionsDatas[$i]->value		= $od->count * $od->cost;
			$this->optionsDatas[$i]->articleID	= $od->id;
		}
		return $this->optionsDatas;
		
		$app = JFactory::getApplication();
		$optionsDataSession = json_decode($app->getUserState("multiForm.{$this->moduleID}.calculator$this->nameinput", '[]'));
		
$html = '';	
//$html .= '265 CalcCul getUserState() $optionsDataSession <pre>';
//$html .= print_r($app->getUserState("multiForm.{$this->moduleID}.calculator$this->nameinput", '[]'),true);
//$html .= '</pre><br><br>';

//$html .= '269 CalcCul getUserState() $optionsDataSession <pre>';
//$html .= print_r($optionsDataSession,true);
//$html .= '</pre><br><br>';

//
//$html .= '275 CalcCul inputString() $optionsData <pre>';
//$html .= print_r($optionsData,true);
//$html .= '</pre><br><br>';
//		$optionsData = JFactory::getApplication()->input->get($this->field_name, $optionsDataSession);

//$html .= '280 CalcCul inputGet() $optionsData <pre>';
//$html .= print_r(json_encode($optionsData),true);
//$html .= '</pre><br><br>';



//\modMultiFormHelper::debugPRE($optionsData,'$optionsData');
//		if($optionsData)
//			$app->setUserState("multiForm.{$this->moduleID}.calculator$this->nameinput", json_encode($optionsData));
		
//echo $html;

//\modMultiFormHelper::debugPRE(JFactory::getApplication()->input, 'calc JFactory::getApplication()->input');
//\modMultiFormHelper::debugPRE($optionsData, 'calc $optionsData .: ');

//echo '<b>calculator.php</b>:309 optionsData:<pre>'. print_r($optionsData,true).'</pre><br>';
//$html .= '296 CalcCul getUserState() $optionsData <pre>';
//$html .= print_r($optionsData,true);
//$html .= '</pre><br><br>';
//echo $html;
//\modMultiFormHelper::debugPRE($optionsData, 'calc $optionsData..: ');
//\modMultiFormHelper::debugPRE('<hr>');
//\modMultiFormHelper::debugPRE('<hr>');
//		$this->moduleID		= $moduleID;
//		$this->paramsOption	= json_decode($params_field, false);
//		$this->field_name	= $field_name;
//		$this->orderID	= $orderID;
		
		return array_merge($optionsData, parent::dataCompute());
	}

	public function articleTextCreate() : string{
		
		$html = '';
		return $html;
		
		$html .= "<table>";
		
		foreach ($this->optionsDatas as $od){
			
			$html .= "<tr type=$od->type name=>$od->field_name";
			$html .= "<td>$od->title</td>";
			$html .= "<td>$od->label</td>";
			$html .= "<td>$od->count</td>";
			$html .= "<td>$od->cost</td>";
			$html .= "<td>$od->label</td>";
			$html .= "<td>$od->label</td>";
			$html .= "</tr>";
		}
		
		$html .= "</table>";
		return $html;
		
//			$this->optionsDatas[$i]->sign		= '+';
//			$this->optionsDatas[$i]->cost		= $od->cost;
//			$this->optionsDatas[$i]->count		= $od->count;
//			$this->optionsDatas[$i]->orderID	= $this->orderID;
//			$this->optionsDatas[$i]->type		= $this->type;
//			$this->optionsDatas[$i]->field_name= $od->field_name;
//			$this->optionsDatas[$i]->title		= $od->title;
//			$this->optionsDatas[$i]->label		= $od->label;
//			$this->optionsDatas[$i]->description = $od->title . ' /' . $od->label;
//			$this->optionsDatas[$i]->i			= $this->index;
	}
	
	/**
	 * Текст для сохраняемой статьи, при обновлении
	 * @return string
	 */
//	public function articleTextUpdate() : string{
//		
//		$app = JFactory::getApplication();
//		$app->setUserState("multiForm.{$this->moduleID}.calculator$this->nameinput", '');
//		
//		return '';
//	}
	
	
	public function messageShow($article_id = 0) : string{
		

		$quantity = 0;
		$count = 0;
		$sum = 0;
		
		/** @var OptionData[] $optD  Опция */
		
		foreach ($this->optionsDatas as $optD){
			if($optD->i != $this->index)
				continue;
			$count += $optD->count;
			$quantity += 1;
//$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": calc.php =====  messageShow() \$optD:" .print_r($optD,true). "   \n\n" , FILE_APPEND);
			
			if($optD->sign == '+') $sum = $sum + ($optD->count * $optD->cost);
			if($optD->sign == '-') $sum = $sum - ($optD->count * $optD->cost);
			if($optD->sign == '/') $sum = $sum / ($optD->count * $optD->cost);
			if($optD->sign == '*') $sum = $sum * ($optD->count * $optD->cost);
		}
		
		
		ob_start();
		?>
<p>Ваш заказ № <b><?= $this->orderID ?></b></p>
<p>Заказано <b><?= $quantity ?></b> наименования(е) в количестве <b><?= $count ?></b> штук.</p>
<p>На сумму <b><?= $sum ?></b> ₽.</p>

<p></p>
		<?php		
		$message = ob_get_clean();
//		exit();
		
		return $message;
	}
	
}
