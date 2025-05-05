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

use Joomla\CMS\Factory as JFactory;


/**
 * Description of BaseOption
 *
 * @author koren
 */
class Option { // BaseOption
	/**
	 * Режим при рендере модуля: страница сайта, рендер модуля, подключение скриптов и стилей
	 */
	const MODE_LAYOUT	= 'layout';
	/**
	 * Режим загрузки формы: поля, кнопки, переключатели, текст, калочки
	 */
	const MODE_FORM		= 'form';
	/**
	 * Режим внешнего вызова, без сохранения(новым) SESSION, 
	 * из под нового пользователя API
	 */
	const MODE_API		= 'api';
	/**
	 * Режим внутренего вызова, с сохранением сеccии, для загрузки многостраничных вызовов форм. Без отправки статей и без отправки писем
	 */
	const MODE_AJAX		= 'ajax';
	/**
	 * Режим ajax загрузки после нажатия SUBMIT, так же служит для сохранения статьи и отправки писем.
	 */
	const MODE_SUBMIT	= 'submit';
	/**
	 * Режим ajax загрузки после перезагрузки в IFRAME, так же служит для сохранения статьи и отправки писем.
	 */
	const MODE_FRAME	= 'frame';
	/**
	 * Режим загрузки содержимого с паролем, лужит для просмотра статистики заказа, и продолжения оплаты.
	 */
	const MODE_PASS		= 'pass';
	
	
	public const STATUS_DONE	= '';
	public const STATUS_UNDO	= 'undo';
	public const STATUS_RELOAD	= 'reload';
	
	/** Приоритет 1: Начальные данные опций собираются без 
	 */
	public const STAGE_ORDER	= 1;
	
	/** Приоритет 3: Массив предназначен для предварительной обработки, определения стоимости доставки, проценты, бонусы, скидки.
	 */
	public const STAGE_DELIVERY	= 3;
	
	/** Приоритет 5: Оплата
	 */
	public const STAGE_PAYMENT	= 5;
	
	/** Приоритет 7: Сохранение результатов оплаты
	 */
	public const STAGE_FINALITY	= 7;
	
	/** 
	 * Порядок присвоения массива данных опций.
	 * Приоритет 1: Начальные данные опций собираются без сохранения
	 * Приоритет 3: Предназначен для предварительной обработки, определения стоимости доставки, проценты, бонусы, скидки.
	 * Приоритет 5: Оплата
	 * Приоритет 7: Сохранение результатов оплаты
	 * @var INT
	 */
	public $stages = 0;




	protected $type			= 'text';
	
	protected string $modeApiAjax = '';
	
	protected $fieldNames		= [];
	protected $fieldTypes		= [];
	protected $fieldLabels		= [];
	/**
	 * ID материала из параметров поля
	 * @var ште
	 */
	protected $art_id			= '';

	/**
	 * Параметры поля: Имя поля, Подсказка поля, name, тип, options, МатериалыID, OnOff
	 * <b>(object)</b>field_label,placeholder,field_name,field_type,paramsOption,nameinput,art_id, onoff
	 * @var object
	 */
//	protected $fieldParams		= null;
	
	/**
	 * Параметры окна опции в настройках
	 * @var type
	 */
	protected $paramsOption		= null;
	
	/**
	 * Массив OptionData объектов
	 * Объект результат обработанных данных опциями полей
	 * @var type
	 */
	protected $optionsData		= [];
	
	protected $moduleID			= 0;
	protected $index			= 0;
	
	
	
	protected $field_name		= '';
	protected $nameinput		= '';
	protected $value			= '';
	
	protected $field_label		= '';
	protected $placeholder		= '';
	protected $field_type		= '';
	protected int $orderID		= 0;
	protected int $onoff		= 1;
	
	
	/**
	 * 	 * Присвоение параметров всех полей модуля в опцию.
	 * Вызывается только в рендере полей
	 * @param int $index Порядковый индекс в списке пользвоательских полей.
	 * @param array $fieldNames
	 * @param array $fieldTypes
	 * @param array $fieldLabels
	 */
	public function setFields(int $index = 0, array $fieldNames = [], array $fieldTypes = [], array $fieldLabels = []){
		$this->fieldNames	= $fieldNames;
		$this->fieldTypes	= $fieldTypes;
		$this->fieldLabels	= $fieldLabels;
		
		$this->index		= $index;
		
		
		$this->field_name	= $fieldNames[$index] ?? '';
		$this->nameinput	= $this->field_name	?: $this->type . $index . $this->moduleID;
	}
	
	/**
	 * Присвоение параметров этого поля 
	 * Вызывается при модуле и ajax
	 * Используется для проверки/сохранения данных в SESSION.
	 * @param array  $props Массив свойств для объекта опции
	 * @param string $modeApiAjax Тип запроса модуля
	 * @param mix $value	Значение переменной переданой в запросе
	 * @param int $orderID	ID заказа, т.е. ID материала содержащий заказ
	 * @return int Возвращает ID заказа, ID материала в котором сохранён заказ.
	 */
	public function setParams(array $props = [], $modeApiAjax = '', $value = null, $orderID = 0) : ?int {
//echo "option.php 125 - $this->field_type<br>";
		
		foreach($props as $prop => $val){
			if(property_exists($this, $prop))
				$this->$prop = $val;
		}
		
		if(isset($props['paramsOption']))
			$this->paramsOption	= is_string($props['paramsOption']) ? new \Reg($props['paramsOption']) : $props['paramsOption'];//json_decode($paramsOption, false);
		
		
		$this->orderID		= $orderID;
		$this->value		= $value;
		$this->modeApiAjax	= $modeApiAjax;
		
		return $orderID;
	}
	
	/** -------------- >>> Вызов при Render модуль  */
	
	/**
	 * JSON для вывода в параметрах сайта клиента
	 * Вызывается в модуле, JSON подключается к < script type='json' >JSON< /script>
	 * @return string
	 */
	public function getJSON() : string{
		return '';
	}
	/**
	 * Получение массива атрибутов-значений DATA для главного тега модуля
	 * Вызывается в модуле, < button data-item1='' data-item2='' >
	 */
	public function getDataset() : array{
		return [];
	}
	/**
	 * Вызов после подключения скриптов, вывод HTML в конце рендера модуля до вызова AJAX
	 * @return string HTML подключаемый в главном теге модуля до вызова AJAX.
	 */
	public function renderModule(\Joomla\CMS\WebAsset\WebAssetManager $webAsset = null) : string{
		return '';
	}
	
	/** -------------- >>> Вызов при AJAX  */
	
	/**
	 * Возврат отрендеренного поля или массива отрендеренных полей в виде строк.
	 * @return string|array
	 */
	public function renderFields($fldName='', $value='', $fldClass='', $placeholder='') : string|array
	{
		
//			$this->field_label	= $fieldParam->field_label;
//			$this->placeholder	= $fieldParam->placeholder;
//			$this->field_type	= $fieldParam->field_type;
//			$this->art_id		= $fieldParam->art_id;
			$requiredField		= $this->onoff == 2 ? 'required' : '';
			$reqstar			= $this->onoff == 2 ? '*' : '';
			$placeholder		= $placeholder ?: $this->placeholder;
			$fldName			= $fldName ?: $this->field_name;
return  "
<input id='$fldName' name='$fldName' type='hidden' data-type='$this->field_type' value='$value' class='$fldClass $this->field_type' $requiredField 
title='$placeholder' placeholder='$placeholder $reqstar'>" ;
	}
	
	/**
	 * Возврат наименования или списка наименований поля в виде строк, для вывода форме и отчётае(статьи).
	 * @return string|array
	 */
	public function getLabels() : string|array {
		return $this->field_label;
	}
	
	/** -------------- >>> Вызов после button-SUBMIT  */

	
	
	/**
	 * Вызывается по ссылке Ajax при загрузке новой страницы .
	 * Вывод и рендер HTML с завершением в случае возврата текста функцией <br>
	 * Если возврат пустой, запрос будет дальше обрадатыватся для всех полей. <br>
	 * Используется, переадресация, загрузка другого API адреса, Отправка подготовленных данных в платежную систему
	 * @return string - HTML page
	 */
	public function ajaxResultHtmlFirst($orderID = 0) : string{
		$this->orderID = $orderID;
		return '';
	}
	

	/**
	 * Текст для создаваемой статьи, перед сохранением
	 * Вызывается после button-Submit 
	 * @return string
	 */
	public function articleTextCreate() : string{
		return '';
	}
	/**
	 * Передача массива данных Опций.
	 * @param array[OptionData, ...] $prepares
	 * @param int $stage Порядковый этап выполнения
	 * @return array|NULL - 
	 * <b>array -</b> Массив данных опций<br> <<-----	
	 * <b>NULL -</b> Оставить данные опций как есть в массиве
	 */
	public function dataCompute(array $optionsData = [], $stage = 0) : ?array{
		
		$this->optionsData = $optionsData;
		
		return $optionsData;
	}
	
	/**
	 * @param int $stage Порядковый этап выполнения
	 * @return STATUS|NULL - 
	 * <b>STATUS_RELOAD -</b> перезагрузка в AJAX,<br> <<-----	
	 * <b>STATUS_UNDO -</b> сообщение об отказе сохранения формы,<br>
	 * <b>STATUS_DONE -</b> готовность сохранить форму	,<br>
	 * <b>NULL -</b> Отмена дальнейшего выполнения запроса
	 */
	public function dataStatus($stage = 0){
		return '';
	}


	/**
	 * Вывод и рендер HTML с завершением в случае возврата текста функцией
	 * Если возврат пустой, запрос будет дальше обрадатыватся для всех полей.
	 * Используется, переадресация, загрузка другого API адреса, Отправка подготовленных данных в платежную систему
	 * @param int $orderID 
	 * @param string $statuses_result Статусы выполненых запросов
	 * @return string - HTML page
	 */
	public function ajaxResultHtmlLast($orderID = 0, $statuses_result = []) : string{
		$this->orderID = $orderID;
		return '';
	}
	
	
	
	
	/**
	 * Текст для обновления статьи, перед сохранением
	 * Вызывается после button-Submit 
	 * @return string - HTML article
	 */
	public function articleTextUpdate($orderID = 0, $ajaxReloadDoneUndo = []) : string{
		return '';
	}
	
	
	/**
	 * Текст для отправки в письме
	 * @return string - HTML mail
	 */
	public function mailMessage() : string{
		return '';
	}
	
	/**
	 * Текст для отправки в письме
	 * @return string - HTML mail
	 */
	public function mailFiles() : array{
		return [];
	}
	
	public function mailRecipient() : array{
		return [];
	}
	
//	public function loadPage, AjaxRaw, requestHtml, ajaxHtml, 
	
	
	
//	/**
//	 * Вызывается по ссылке Ajax из формы при загрузке новой страницы .
//	 * @return string
//	 */
//	public function getAjaxJson() : string{
//		return '';
//	}
//	
//	/**
//	 * Вызывается по ссылке Api из внешнего источника при внешней загрузке.
//	 * @return string
//	 */
//	public function getAjaxApi() : string{
//		return '';
//	}
	/**
	 * Возвращает URL загрузка которого возвращает результат AJAX вызова этого модуля с этим заказом
	 * @return string
	 */
	public function getUrlPass() : string{
		
		if(empty($this->orderID) || empty($this->moduleID))
			return '';
		
		$secret = JFactory::getConfig()->get('secret');
		$PASS = md5($secret . $this->moduleID . $this->orderID);
		
		return "/?option=com_ajax&module=multi_form&format=raw&pass=$PASS&id=$this->moduleID&order=$this->orderID";
	}
	
	
	/**
	 * Финальные действия для опции: сохранение результата об выполненых платежах, заказах.
	 * Отправка подготовленных данных в платежную систему
	 * Удаление данных из SESSION 
	 * @param array $prepares
	 * @return bool
	 */
	public function submitPostpareSaveSend(array $options = []) : void{
		return ;
	}
	/**
	 * Сообщение в модуле для отображения в ответе после отправки
	 * @param int $article_id
	 * @return string
	 */
	public function messageShow($article_id = 0) : string{
		return '';
	}
	
	
	/**
	 * Получение имени или если пусто то уникального имени для поля опции 
	 */
//	public function getName() : string;
	
//	public $nameinput;
//	public string $type;
//	public string $name;
//	public string $value;
	
	/**
	 * Пристваивает свойство только 1 раз
	 * Возвращает значение свойства
	 * @param string $property
	 * @param mix $value
	 * @return mix
	 */
	public function property(string $property, $value = null){
//if(!isset($this->$property))
//echo "<br>\$option: $this->type -> $property </pre>"; 
		
		if(isset($this->$property) && $property && empty($this->$property))
			$this->$property = $value;
		
		return $this->$property ?? '';
	}
	
	
		
	public static function getAjaxReload(int $moduleID, int $second = 0, string $html = '', string $url = '') : string{
		
		$ajaxReload = JFactory::getApplication()->getUserState("multiForm.{$moduleID}.ajaxReload");//->toString()
		ob_start();
?>
<div class="reload status" id="ajaxReload<?=$moduleID?>">
<?=$html?>
<script modID="<?=$moduleID?>">
setTimeout(function(){
const bc = new BroadcastChannel('modMultiModReload<?=$moduleID?>_<?=$ajaxReload?>');
bc.postMessage('<?=$url?>');
bc.onmessage = function (ev) { console.log(ev); }
}, <?=$second * 1000?>);
</script>
</div>
<?php
		return ob_get_clean();
	}	
	public static function ajaxReload(int $moduleID, int $second = 0, string $html = '', string $url = '') : string{
		
//		order=123&pass=lsakdjflaskdjfalksdjflaskjeizxkcvjo
//		return ob_get_clean();
	}
}
