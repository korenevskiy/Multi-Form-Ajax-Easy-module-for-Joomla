<?php namespace Joomla\Module\MultiForm\Site; defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use \Joomla\Module\MultiForm\Site\Option as OptionField;
use Joomla\CMS\Router\Route as JRoute;
use Joomla\CMS\Uri\Uri as JUri;

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


class OptionRobokassa extends \Joomla\Module\MultiForm\Site\Option   { // JFormFieldCalculator  // extends JFormField 
	
	public $stages = 5;
	
	public $type			= 'robokassa';
	
	private	$allsum		= 0;
//	private	$count		= 0;
//	private	$quantity	= 0;
	private	$optData	= null;
	
	
//	private	$password1 = '';
//	private	$password2 = '';s
	private	$shopID = '';
	
	private $result = null;
	
//	const STATUS_DONE	= 1;
//	const STATUS_CANCEL	= 0;
//	const STATUS_EMPTY	= NULL;
	
	public function getLabels() : string|array {
		return ['hide' => $this->field_label];
	}
	
//	public function renderFields() : string{
//		$this->paramsOption->mode;
//		
//		return "<br>robo: {$this->paramsOption->mode}<br>";
//	}
	
	
	public function getJSON() : string{
//		return "$this->moduleID  $this->paramsOption ";
		return json_encode($this->paramsOption, JSON_FORCE_OBJECT);// "$moduleID  $fieldName $optionParams";
	}
	
	
	public function setParams(array $props = [], $modeApiAjax = '', $value = null, $orderID = 0) : ?int{
		
		$orderID = parent::setParams($props, $modeApiAjax, $value, $orderID);
		
		if(in_array($modeApiAjax, [OptionField::MODE_API, OptionField::MODE_AJAX, OptionField::MODE_FRAME]))
			return JFactory::getApplication()->getInput()->getInt('InvId');
		
		return $orderID;
	}
 
	
//	public function ajaxResultHtmlFirst() : string{
//		
//		
	

//		if($this->modeApiAjax == OptionField::MODE_AJAX){
////			JFactory::getApplication()->redirect(JRoute::_(JUri::root().'index.php'));
//			
////		$uri =	JUri::getInstance();
////		$uri->setVar('order', $this->orderID);
////		$uri->delVar('ajax');
////		$uri->setVar('reload', $this->orderID);
////		$roboPay = $uri->getVar('order');
////		JFactory::getApplication()->redirect(JRoute::_($uri));
//		
//		
//		
////echo "<pre>"
////		. print_r(JUri::getInstance())
////		. "</pre>"
////		. "<pre>"
////		. print_r(JUri::getInstance()->toString())
////		. "</pre>"
////		. "<pre>"
//////		. print_r($this->)
////		. "</pre>";
////
////			return '[{RELOAD}]' ;// static::getAjaxReload($this->moduleID);
//		}
//		
//		return '';
//	}
	
	
	
	/**
	 * Возвращает массив подполей опции класса <b>FieldData</b>
	 * Объект подготовленных данных для платежа
	 * Возвращает Null в случае ошибки(отказа)
	 * @return array|null
	 */
//	public function dataSet($selfDatas = []) : array {
//			
////file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/dataSet.txt', basename (__FILE__).'  '. date('Y-m-d h:i:s', time()).' =====  =====  $modeApiAjax: ' .print_r($this->modeApiAjax ,true). " \n",FILE_APPEND  );//FILE_APPEND
////		if($this->modeApiAjax != OptionField::MODE_AJAX)
////			return [];
////		$app = JFactory::getApplication();
////		$sum = $app->getUserState("multiForm.{$this->moduleID}.robokassa{$this->nameinput}_{$this->orderID}", 0);
//		
////		$this->optData;
//		
//		foreach ($selfDatas as $i => $opData){
//			if($opData->type != $this->type)
//				unset($selfDatas[$i]);
//			continue;
//			
//		}
//		
//		if($this->optData == null)
//			return [];
//		
//		
//		
////file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/dataSet.txt', __LINE__. "  \$allsum:$this->allsum		\$orderID:$this->orderID". "\n",FILE_APPEND  );//FILE_APPEND
//
////		if(! $this->allsum)
////			return [];
//		
////		$app->setUserState("multiForm.{$this->moduleID}.robokassa{$this->nameinput}_{$this->orderID}", $this->allsum);
//		return [$this->optData];
//	}
	
	


	public function dataCompute(array $optionsData = [], $stage = 0) : ?array {

		
//echo "<pre>\$orderID $orderID</pre>";
		$this->result = static::STATUS_UNDO;
		
		
		if(empty($this->paramsOption))
			return null;
		
		$this->allsum	= 0;
//		$this->count	= 0;
//		$this->quantity = 0;
		$payed = false;
		
		foreach ($optionsData as $optData){
//			if($optData instanceof OptionData)
			
			if($this->type == $optData->type && $this->index == $optData->i){
				$this->optData = $optData;
//				return null;
				continue;
			}
			
			if($optData->sign == '+')
				$this->allsum += $optData->count * $optData->cost;
			
			if($optData->sign == '-')
				$this->allsum -= $optData->count * $optData->cost;
			
			if($optData->sign == '*')
				$this->allsum *= $optData->count * $optData->cost;
			
			if($optData->sign == '/')
				$this->allsum /= $optData->count * $optData->cost;
			
		}
		
		if(//in_array($this->modeApiAjax, [OptionField::MODE_API, OptionField::MODE_AJAX]) && 
			$this->optData && $this->optData->count * $this->optData->cost == $this->allsum){
			
			$this->result = static::STATUS_DONE;
			return null;
		}

				
		/** Загрузка Проверка платежа из API */
		if( ! in_array($this->modeApiAjax, [OptionField::MODE_API, OptionField::MODE_AJAX]))
			return []; 
		
		
		
		$payed = $this->apiResult();
		
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  dataCompute() \$stage:$stage \$payed:$payed   \n\n" , FILE_APPEND);
		
		if(empty($payed))
			return [];
		
		
		parent::dataCompute($optionsData);
		
		
		//$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  dataCompute() \$this->paramsOption:" .print_r($this->paramsOption,true). "   \n\n" , FILE_APPEND);
		
		

		
		$this->result = static::STATUS_DONE;
			
		$this->optData = new \Joomla\Module\MultiForm\Site\OptionData;
		$this->optData->sign		= '-';
		$this->optData->cost		= $this->allsum;
		$this->optData->count		= (int)$payed;
		$this->optData->orderID		= $this->orderID;
		$this->optData->type		= $this->type;
		$this->optData->i			= $this->index;
		$this->optData->field_name	= $this->field_name;
		$this->optData->title		= 'Оплата';
		$this->optData->label		= '';
		$this->optData->description	= ($this->optData->title && $this->optData->label) ? 
				$this->optData->title . ' /' . $this->optData->label : 
				$this->optData->title . $this->optData->label;
		$this->optData->description .= '<br><b>';
		$this->optData->description .= $payed ?
			$this->paramsOption->messagePaid :
			$this->paramsOption->messageCancel;
		$this->optData->description .= '</b>';
		
		$orderID = JFactory::getApplication()->getInput()->getInt('InvId', 0);
		
		return [$this->optData];
	}
	
	public function dataStatus($stage = 0) {
		return $this->result;
	}


	/**
	 * Загрузка результата платежа
	 * @return string
	 */
	private function apiResult() : bool {
		
// REQUEST: 
//    [option] => com_ajax
//    [module] => multi_form
//    [format] => raw
//    [id] => 175
//    [ajax] => bd9edea66d0a32198de4d4d3ba8b68c862469787
//    [OutSum] => 1.00
//    [InvId] => 1484
//    [SignatureValue] => 5b779fd2d5645a17fe33b04ccfd9a11b
//    [IsTest] => 1
//    [Culture] => ru

//		$result = "\n $inv_id ==================== \n";

//		$password2;
		$this->shopID;
		
		 
//		$password1	= $this->paramsOption->mode ? $this->paramsOption->pass1_combat : $this->paramsOption->pass1_test;
		$password2	= $this->paramsOption->mode ? $this->paramsOption->pass2_combat : $this->paramsOption->pass2_test;

// чтение параметров
// read parameters
//$this->allsum			= $_REQUEST["OutSum"];
//$invId			= $_REQUEST["InvId"];
////$shp_item		= $_REQUEST["Shp_item"];
//$sign			= $_REQUEST["SignatureValue"];


//s		$this->allsum	= JFactory::getApplication()->getInput()->getFloat('OutSum');
		$this->allsum	= JFactory::getApplication()->getInput()->getCmd('OutSum');
		$invId			= JFactory::getApplication()->getInput()->getInt('InvId');
		$sign			= JFactory::getApplication()->getInput()->getAlnum('SignatureValue','');
		
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  apiResult() \$INPUT:".print_r(JFactory::getApplication()->getInput()->getArray(),true)." \n\n" , FILE_APPEND);
	
		if(empty($invId) || empty($this->allsum) || empty($sign)){
			$this->result = static::STATUS_UNDO;
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  apiResult() \$this->result:".print_r($this->result,true)." \n\n" , FILE_APPEND);
			return FALSE;
		}
		
		
		$sign			= strtoupper($sign);
//$shp_item		= JFactory::getApplication()->getInput()->getAlnum('Shp_item');


//		if($invId == 0)
//			$signatureR = "$this->allsum::$password2";
//		else
			$signatureR = "$this->allsum:$invId:$password2";
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  apiResult() \$signatureR:".print_r($signatureR,true)." \n\n" , FILE_APPEND);
			
		$this->allsum = (float)$this->allsum;
			
		$my_sign = strtoupper(md5($signatureR));
		
		$valid = $my_sign == $sign;
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  apiResult() \$my_sign:".print_r($my_sign,true)." \n\n" , FILE_APPEND);
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  apiResult()"
//	. "\n \$my_sign: $my_sign == $sign :".print_r($sign,true).". \n\n" , FILE_APPEND);

//	file_put_contents($fDeb, __LINE__.": ROBO.php =====  apiResult() \$valid:".print_r($valid,true)." \n\n" , FILE_APPEND);

	
	
		$message = $valid ? $this->paramsOption->messagePaid : $this->paramsOption->messageCancel;
		
		$db = JFactory::getDbo();
//		$db = JFactory::getContainer()->get(Joomla\Database\DatabaseInterface::class);

		
		
		$message = "<table><tr><th colspan=2>$message</th><tr>";
		$message .= "<tr><td colspan=2>Оплата Робокасса API</td></tr>";
		$message .= "<tr><td>Емайл: </td><td>"	.$db->escape(JFactory::getApplication()->getInput()->getString('EMail')).'</td></tr>';
		$message .= "<tr><td>Метод: </td><td>"	.$db->escape(JFactory::getApplication()->getInput()->getString('PaymentMethod')).'</td></tr>';
		$message .= "<tr><td>Способ: </td><td>"	.$db->escape(JFactory::getApplication()->getInput()->getString('IncCurrLabel')).'</td></tr>';
		$message .= "<tr><td>Комиссия: </td><td>".$db->escape(JFactory::getApplication()->getInput()->getFloat('Fee')).'</td></tr>';
		$message .= "<tr><td>Платёж: </td><td>$this->allsum</td></tr>";
		$message .= "</table>";
		
//[out_summ] => 6000
//[OutSum] => 6000
//[inv_id] => 1521
//[InvId] => 1521
//[crc] => 04BEDD08234BA01C404E1FBDABC36788
//[SignatureValue] => 04BEDD08234BA01C404E1FBDABC36788
//[PaymentMethod] => PayButton
//[IncSum] => 6000
//[IncCurrLabel] => SBPPSR
//[IsTest] => 1
//[EMail] => 
//[Fee] => 0.0	Комиссия
		
		$this->result = $valid ? static::STATUS_DONE : static::STATUS_UNDO;

		if(empty($valid))
			return FALSE;
		
		$status = $valid ? 1 : 0;
		$sfx = $valid ? ' 💰' : ' ❌';//✕❌
		
		$query = "
UPDATE `#__content` 
SET title = CONCAT(title ,'$sfx'),   `state` = $status, `fulltext`= CONCAT(`fulltext`, '<hr>".$db->escape($message)."<br>') 
WHERE id = $invId
			";
		$db->setQuery($query)->execute();
		
		
//		$modID = $this->moduleID;
		
		return $valid;
		
		// <<< ----------------------------------------------------------------
		
		
		
		
//		require_once __DIR__ . '/../media/sse.php';
		
		$semId = sem_get($modID, 1);
		
		sem_acquire($semId);				//Захватывает семафор
//$invId
											//Создаёт или открывает сегмент разделяемой памяти
//		$shmId = shm_attach($semId, mb_strlen($message) + 10);
		$shmId = shm_attach($semId, 2000);
//		$shmId = shm_attach($semId);	//Создаёт или открывает сегмент разделяемой памяти
											//Вставляет или обновляет переменную в разделяемой памяти
		shm_put_var($shmId, 0, $valid ? "OK:$invId\n" : "BAD:$invId\n");
		
		shm_put_var($shmId, 0, $message);
		shm_put_var($shmId, 1, array_keys($_SESSION));
		
		sem_release($semId);
		
		sem_remove($semId);
		
//		if (shm_has_var($shmId, 'modMultiForm'.$this->moduleID)) {
//		//	 Если есть, считываем данные
//			$data = (array)shm_get_var($shmId, 'modMultiForm'.$this->moduleID);
//		} 
		

		return $valid ? "OK$invId\n" : "bad sign\n";

//return "OK$inv_id\n";	
		
		
//		$signatureR = ":Shp_item=$shp_item";
$result .= " $ signatureR: $signatureR \n";

//$my_crc = strtoupper(md5("$this->allsum:$invId:$password2:Shp_item=$shp_item"));


$result .= ' REQUEST: '.print_r($_REQUEST, true);

$result .= ' GET: '.print_r($_GET, true);
$result .= ' POST: '.print_r($_POST, true);
$result .= ' SERVER: '.print_r($_SERVER, true);

//$post = JFactory::getApplication()->input->getPost();
//$result .= ''.print_r($post, true);
//$result .= '->Input'.print_r(JFactory::getApplication()->input, true);



$result .= " $ my_sign: $my_sign \n";
$result .= " $ sign: $sign \n";
// проверка корректности подписи
// check signature


//echo "OK$inv_id\n";

		return "OK$inv_id\n";	
//file_put_contents($file, '');
	
	}


	/**
	 * Загрузка результата платежа
	 * @return string
	 */
	private function apiSuccess() {//modules/mod_multi_form/test.php
		

// REQUEST: 
//    [option] => com_ajax
//    [module] => multi_form
//    [format] => raw
//    [id] => 175
//    [ajax] => bd9edea66d0a32198de4d4d3ba8b68c862469787
//    [OutSum] => 1.00
//    [InvId] => 1484
//    [SignatureValue] => 5b779fd2d5645a17fe33b04ccfd9a11b
//    [IsTest] => 1
//    [Culture] => ru
		
//		$result = "\n $inv_id ==================== \n";
		
$password1;
$this->shopID;

// чтение параметров
// read parameters
//$sum			= $_REQUEST["OutSum"];
//$invId			= $_REQUEST["InvId"];
////$shp_item		= $_REQUEST["Shp_item"];
//$sign			= $_REQUEST["SignatureValue"];


//		$sum			= JFactory::getApplication()->getInput()->getFloat('OutSum');
		$invId			= JFactory::getApplication()->getInput()->getInt('InvId', 0);
		$sum			= JFactory::getApplication()->getInput()->getString('OutSum', 0);
		$sign			= JFactory::getApplication()->getInput()->getAlnum('SignatureValue', '');
//		$shp_item		= JFactory::getApplication()->getInput()->getAlnum('Shp_item');

		if(! is_double((double)$sum) || (double)$sum <= 0   ){
			$this->result = static::STATUS_UNDO;
			return 0;
		}
		
//		$shopID		= $this->paramsOption->shopID		?? '';s
		$password1	= $this->paramsOption->mode ? $this->paramsOption->pass1_combat : $this->paramsOption->pass1_test;
//		$password2	= $this->paramsOption->mode ? $this->paramsOption->pass2_combat : $this->paramsOption->pass2_test;
		
		$sign		= strtoupper((string)$sign);

//		if($invId)
			$signatureR = "$sum:$invId:$password1";
//		else
//			$signatureR = "$sum::$password1";
		
		$my_sign = strtoupper(md5($signatureR));
		
		$valid = $my_sign == $sign;
		
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": calc.php =====  apiSuccess() \$valid:" .print_r($valid,true). "   \n\n" , FILE_APPEND);
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/apiSuccess.txt', 
//	"mode:$this->modeApiAjax    $my_sign == $sign =".($valid?'true':'false').'   signMy='.print_r($signatureR ,true). " \n\n", FILE_APPEND  );//FILE_APPEND
		$message = $valid ? $this->paramsOption->messagePaid : $this->paramsOption->messageCancel;
		
		$db = JFactory::getDbo();
//		$db = JFactory::getContainer()->get(Joomla\Database\DatabaseInterface::class);
		
		$message = "<table><tr><th colspan=2>$message</th><tr>";
		$message .= "<tr><td colspan=2>Оплата Робокасса AJAX</td></tr>";
		$message .= "<tr><td>Емайл: </td><td>"	.$db->escape(JFactory::getApplication()->getInput()->getString('EMail')).'</td></tr>';
		$message .= "<tr><td>Метод: </td><td>"	.$db->escape(JFactory::getApplication()->getInput()->getString('PaymentMethod')).'</td></tr>';
		$message .= "<tr><td>Способ: </td><td>"	.$db->escape(JFactory::getApplication()->getInput()->getString('IncCurrLabel')).'</td></tr>';
		$message .= "<tr><td>Комиссия: </td><td>".$db->escape(JFactory::getApplication()->getInput()->getFloat('Fee')).'</td></tr>';
		$message .= "<tr><td>Платёж: </td><td>$sum</td></tr>";
		$message .= "</table>";
		
		
		$db = JFactory::getDbo();
//		$db = JFactory::getContainer()->get(Joomla\Database\DatabaseInterface::class);
//		$message = $db->escape($message);
//		$message .= "";
		
		$query = "
UPDATE `#__content` 
SET `fulltext`= CONCAT(`fulltext`, '<hr>', '".$db->escape($message).": $sum <br>')
WHERE id = $invId
			";
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/apiSuccess.txt', date('Y-m-d h:i:s', time()).' =====robo.php===== $_SERVER ' .print_r($query ,true). " \n\n", FILE_APPEND  );//FILE_APPEND
		if($invId)
		$db->setQuery($query)->execute();
		
		$this->result = $valid ? static::STATUS_DONE : static::STATUS_UNDO;
		
		$this->orderID = $invId;
		
		return $valid ? $sum : 0;
	}
	
	
	/**
	 * Вывод и рендер HTML, завершение в случае возврата текста функцией
	 * Используется, переадресация, загрузка другого API адреса, Отправка подготовленных данных в платежную систему
	 * Отправка подготовленных данных в платежную систему
	 * @param int $orderID 
	 * @param string $status_result Статусы выполненых запросов
	 * @return string
	 */
	public function ajaxResultHtmlLast($orderID = 0, $statuses_result = []) : string {
		

	
//		$uri =	JUri::getInstance();
//		$uri->delVar('ajax');
//		$roboPay = $uri->getVar('robo', true);
		
		$this->orderID = $orderID;
		
		/** Загрузка < IFrame >  --> Reload */
		if($this->allsum && $this->modeApiAjax == OptionField::MODE_SUBMIT)
			return $this->loadFrame(); // < IFrame > --> Reload
		
		
		

		if($this->modeApiAjax == OptionField::MODE_AJAX){
		}
		
		
		
//		date_default_timezone_set(JFactory::getUser()->getTimezone());
		$_SESSION['orderDT']	= JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);//date('Y-m-d h:i:s a', time());
		$_SESSION['orderID']	= $this->orderID;
		$_SESSION['moduleID']	= $this->moduleID;
		$_SESSION['orderPaid']	= $this->paramsOption->messagePaid;
		$_SESSION['orderCancel']= $this->paramsOption->messageCancel;
		$_SESSION['orderStatus']= $this->result;
		
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  ajaxResultHtmlLast()ok/bad  \$this->modeApiAjax:$this->modeApiAjax \n\n" , FILE_APPEND);
		
		if($this->modeApiAjax == static::MODE_API && $this->result == static::STATUS_DONE && ! in_array(static::STATUS_UNDO, $statuses_result))
			return "OK$orderID\n";
		
		if($this->modeApiAjax == static::MODE_API)
			return "bad sign\n";
		
		
//		if(in_array(OptionField::STATUS_RELOAD, $statuses_result))
//			return "";
//		if(in_array(OptionField::STATUS_RELOAD, $statuses_result))
//			return '';
		
//$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//if($this->modeApiAjax != 'submit')
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  ResultHtmlLast() \$orderID:$this->orderID  \$this->modeApiAjax:$this->modeApiAjax \n\n" , FILE_APPEND);
		return "";
	}
	
	/**
	 * Загрузка IFRAME
	 * @return string HTML
	 */
	private function loadFrame() : string {
		
		$desc = '';
		
		$this->allsum;
//		$this->optData->cost;
		
//$result = "------------------------------------------- \n Robokassa:apiLoad() " . $this->modeApiAjax . '    ' . date('Y-m-d H:i:s') . "";
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/ajaxSubmit.txt', basename (__FILE__).date('Y-m-d h:i:s', time()).$result);//FILE_APPEND


//		option_params
//		$this->shopID	;
//		$password1;
//		$password2;
//		$this->paramsOption->mode	?? false;//test,work
//		$this->paramsOption->IncCurrLabel;
//		$this->paramsOption->Culture;
//		$this->paramsOption->Encoding;
//		$this->paramsOption->password1;
		
		
		$shopID		= $this->paramsOption->shopID		?? '';
		$password1	= $this->paramsOption->mode ? $this->paramsOption->pass1_combat : $this->paramsOption->pass1_test;
//		$password2	= $this->paramsOption->mode ? $this->paramsOption->pass2_combat : $this->paramsOption->pass2_test;
		
		
		$signatureR = '';
		$signatureH = '';
		
		//:900:1148:
		if($this->orderID > 0)
			$signatureR = "$shopID:$this->allsum:$this->orderID:$password1";
		else
			$signatureR = "$shopID:$this->allsum::$password1";
		
		$signatureH = md5($signatureR);
//makigra.ru-joom:900:1149:Sv9xlSA8VqFsTCT4oe56
		$apiRobokassa = "https://auth.robokassa.ru/Merchant/Index.aspx?"
				. "MerchantLogin={$shopID}&OutSum={$this->allsum}&SignatureValue={$signatureH}";
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/ajaxSubmit.txt', "\n".'$apiRobokassa:'.$apiRobokassa, FILE_APPEND);//, FILE_APPEND
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/ajaxSubmit.txt', "\n".'$signatureR:'.$signatureR, FILE_APPEND);//, FILE_APPEND

//https://auth.robokassa.ru/Merchant/Index.aspx
//?MerchantLogin=makigra.ru-joom
//&OutSum=6000
//&SignatureValue=ac33728b005b4fc6f0805597bcfc8b7e
//&InvId=1223
//&Description= Тот кто играет меня /Годовой достуsп
//&&Culture=ru

		if($this->orderID)
			$apiRobokassa .= "&InvId=$this->orderID";
		
		$apiRobokassa .= "&order=$this->orderID";
		
		if($this->paramsOption->mode == '0')
			$apiRobokassa .= "&IsTest=1";
		
		if($desc)
			$apiRobokassa .= "&Description=$desc";
		
//		$apiRobokassa .= "&Culture=ru";
		
//		JFactory::getApplication()->redirect($apiRobokassa);
//$apiRobokassa = "https://makigra.ru/joom/modules/mod_multi_form/test.php";
		
//		$app = JFactory::getApplication();
//		$app->enqueueMessage('Текст сообщения, который увидит посетитель после редиректа');
//		$app->redirect(JRoute::_($apiRobokassa, false));

		
//echo "<br>1760 helper.php \$this->allsum: <pre>".print_r($this->optionsData,true)."</pre>";
//echo "<br>1760 helper.php \$this->allsum: <pre>".print_r(JFactory::getApplication()->getInput()->getArray(),true)."</pre>";
		
		$redirectLink = \modMultiFormHelper::redirectLink($this->moduleID, $this->orderID);
		
		return "<iframe data-order=$this->orderID id=robo{$this->moduleID}_{$this->index} allowfullscreen=false allowpaymentrequest=true "
. " Xsandbox='allow-scripts allow-forms allow-same-origin' "
. " Xallow=\"geolocation 'src'  camera  payment  fullscreen 'none' \"   "
		. " browsingtopics eager "
		. " src='$apiRobokassa' data-reload='$redirectLink' scrolling=auto height=100% loading=lazy style='height: calc((100vh - 6px) - 2em);margin: auto;width: 100%;'></iframe>" ;
	}
	
	
	
	
	/**
	 * Текст для сохраняемой статьи, при создании
	 * @return string
	 */
	public function articleTextCreate() : string{
		return "Эквайринг выбран <a href='https://robokassa.com/' target='_blank'>Robokassa.com</a></b>";
	}
	
	/**
	 * Текст для сохраняемой статьи, при обновлении
	 * @return string
	 */
	public function articleTextUpdate($orderID = 0, $ajaxReloadDoneUndo = []) : string{
		
		return $this->result == static::STATUS_DONE ? $this->paramsOption->messagePaid : $this->paramsOption->messageCancel;
	}
	
	/**
	 * Текст для отправки в письме
	 * @return string
	 */
	public function mailMessage(): string{
		
		return $this->result == static::STATUS_DONE ? $this->paramsOption->messagePaid : $this->paramsOption->messageCancel;
	}
	
	/**
	 * Сообщение в модуле для отображения в ответе после отправки
	 * @param int $article_id
	 * @return string
	 */
	public function messageShow($article_id = 0) : string{
		
//$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": ROBO.php =====  dataCompute() \$this->paramsOption:" .print_r($this->paramsOption,true). "   \n\n" , FILE_APPEND);

		return $this->result == static::STATUS_DONE ? $this->paramsOption->messagePaid : $this->paramsOption->messageCancel;
	}
	
//	public function getName($i = '') : string{
//		return  $this->type . $i . $this->moduleID; 
//	}
	

}

