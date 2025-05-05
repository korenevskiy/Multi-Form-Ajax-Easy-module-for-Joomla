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
use Joomla\CMS\Filter\InputFilter as JFilterInput; 


//toPrint(null,'' ,0,'');
//toPrint(JFactory::getApplication()->input,'POST' ,0,'message');



class OptionDebit extends \Joomla\Module\MultiForm\Site\Option  { // JFormFieldCalculator  //  extends JFormField  

	public $type	= 'debit';
	public $stages	= 5;
	 
	
	protected $optionsDatas = [];


//	ST00012|Name={Name}|PersonalAcc={PersonalAcc}|BankName={BankName}|BIC={BIC}|CorrespAcc={CorrespAcc}|KPP={KPP}|PayeeINN={PayeeINN}|Purpose={Purpose}|Sum={Sum}|Contract={Contract}|Phone={}|LastName|FirstName


	public function getLabels() : string|array {
		return ['Phone' => 'Телефон','FirstName' => 'Имя','LastName' => 'Фамилия','MiddleName' => 'Отчество',
			'PayerINN' => 'ИНН плательщика','PayerAddress' => 'Адрес плательщика','Company' => 'Компания' ];
	}

//	public function getName($i = '') : string{
//		return  $this->type . $i . $this->moduleID; 
//	}
		
	

	public function renderFields($fldName='', $value='', $fldClass='', $placeholder='') : string| array {
		$style = '';
		
//		$app = JFactory::getApplication();
//		$login	= JFactory::getApplication()->input->getString('maklogin','');
//		$makgame = (int)$app->getUserState("multiForm.{$this->moduleID}.makgame");
//		if($makgame)
		
//return ['Phone'=>"<pre class='message'> ".print_r($value ,TRUE)."</pre>"];

//		addslashes($style);
		$Phone		= htmlspecialchars($value['Phone']		?? '');
		$FirstName	= htmlspecialchars($value['FirstName']	?? '');
		$LastName	= htmlspecialchars($value['LastName']	?? '');
		$MiddleName = htmlspecialchars($value['MiddleName']	?? '');
		$PayerINN	= htmlspecialchars($value['PayerINN']	?? '');

		return [
			'Phone'			=> "<input type='text' name='{$this->nameinput}[Phone]'		 value='$Phone'		 class='form-control input text'>",
			'FirstName'		=> "<input type='text' name='{$this->nameinput}[FirstName]'  value='$FirstName'	 class='form-control input text'>",
			'LastName'		=> "<input type='text' name='{$this->nameinput}[LastName]'   value='$LastName'	 class='form-control input text'>",
			'MiddleName'	=> "<input type='text' name='{$this->nameinput}[MiddleName]' value='$MiddleName' class='form-control input text'>",
			'PayerINN'		=> "<input type='text' name='{$this->nameinput}[PayerINN]'	 value='$PayerINN'	 class='form-control input text'>",
			'PayerAddress'	=> "<input type='text' name='{$this->nameinput}[PayerAddress]'	 value='$PayerINN'	 class='form-control input text'>",
			'Company'		=> "<input type='text' name='{$this->nameinput}[Company]'	 value='$PayerINN'	 class='form-control input text'>",
		];
	}
	
	private $optData = null;
	
	private $dataReg = null;
	
	public function dataCompute(array $optionsData = [], $stage = 0) : ?array{
		
		/** @var OptionData[] $optionsData  Опция */
		
		$this->optionsData = $optionsData;
//		$this->nameinput
		
//		$_SERVER['HTTP_ORIGIN'];//https://xn--80aaaaw4ajc2bfdcki9kra8b.xn--p1ai
//		$_SERVER['HTTP_REFERER'];//https://xn--80aaaaw4ajc2bfdcki9kra8b.xn--p1ai/?deb=multiForm
//		$_SERVER['HTTP_HOST'];//https://xn--80aaaaw4ajc2bfdcki9kra8b.xn--p1ai
//		$_SERVER['SERVER_NAME'];//https://xn--80aaaaw4ajc2bfdcki9kra8b.xn--p1ai
		
		
		
		$Sum = 0;
		
		foreach ($this->optionsData as $optD){
			if($this->index == $optD->i) {
				$this->optData = $optD;
				$this->dataReg = new \Reg($this->optData->dataJSON);
				if($optD->orderID)
					$this->orderID = $optD->orderID;
				continue;
			}
			
			/* @var $optD Joomla\Module\MultiForm\Site\OptionData Опция  */
			if($optD->sign == '+') $Sum = $Sum + ($optD->count * $optD->cost);
			if($optD->sign == '-') $Sum = $Sum - ($optD->count * $optD->cost);
			if($optD->sign == '/') $Sum = $Sum / ($optD->count * $optD->cost);
			if($optD->sign == '*') $Sum = $Sum * ($optD->count * $optD->cost);
			
		}
		
		if(empty($this->optData)){
			$this->optData = new OptionData;
			$this->optData->i = $this->index;
			if($this->orderID)
				$this->optData->orderID = $this->orderID;
		}
		
//echo "<pre>114 \optData ". print_r($this->optData, true).'</pre>';
//echo "<pre>\$optionsData ". print_r($optionsData, true).'</pre>';
//		$data = [];
		
//		$fields = ['Phone','FirstName','LastName','MiddleName','PayerINN','PayerAddress','Company'];
		$data	= [];
		$labels = $this->getLabels();
		
		foreach ((array)(JFactory::getApplication()->input->getString($this->nameinput, [])) as $name => $value){
			
			if(! isset($labels[$name]))
				continue;
			
			if($name == 'PayerINN')
				$data[$name] = JFilterInput::getInstance([], [], 1, 1)->clean($value, 'INT');
			else
				$data[$name] = JFilterInput::getInstance([], [], 1, 1)->clean($value, 'STRING');
			
		}
//['Phone','FirstName','LastName','MiddleName','PayerINN','PayerAddress','Company'];
		if($data){
			$this->optData->dataJSON = json_encode($data, JSON_UNESCAPED_UNICODE);
			$this->dataReg = new \Reg($data);
		}
		
		$this->optData->cost = $Sum;
//echo __LINE__ . "<pre> \DEBIT-> ->dataJSON ". print_r($this->optData->dataJSON, true).'</pre>';
//echo __LINE__ . "<pre> \DEBIT-> data ". print_r($data, true).'</pre>';
//echo __LINE__ . ":debit.php -  OrderID: <pre>".print_r($this->orderID,true)."</pre>	<br>";
		
//		echo "<pre>". print_r(JFactory::getApplication()->input->getString($this->nameinput,''), true).'</pre>';
//		echo "<pre>". print_r(JFactory::getApplication()->input->getString($this->nameinput.'.Phone',''), true).'</pre>';
//		echo "<pre>". print_r(JFactory::getApplication()->input->getArray([$this->nameinput=>'ARRAY']), true).'</pre>';
////		echo "<pre>". print_r(JFactory::getApplication()->input, true).'</pre>';
//		exit();
		
		return [$this->optData];
	}

	private function getCode() {
		
//		$opt = new OptionData;
//echo "<pre>168 \$this->optData ". print_r($this->optData, true).'</pre>';
		
		if(empty($this->optData))
			return '';
		
		
		/* @var $this->optData Joomla\Module\MultiForm\Site\OptionData Опция  */
		
		$Sum = $this->optData->cost * 100;
		
//echo "<pre>178 \$this->optData ". print_r($this->optData, true).'</pre>';
		
		foreach (['Name','PersonalAcc','BankName','BIC','CorrespAcc',] as $prop){
			if(empty($this->paramsOption) || empty($this->paramsOption->$prop)){
				
//echo "<pre>183 \$prop ". print_r($prop, true).'</pre>';
			return '';
			}
		}
		
		$code = 'ST00012|';
		
		foreach (['Name','BankName','PayeeINN','KPP','Purpose'] as $prop){
//			$this->paramsOption->$prop = str_replace(' ', '', $this->paramsOption->$prop);
			$this->paramsOption->$prop = trim($this->paramsOption->$prop);
		}
		
		foreach (['PersonalAcc','BIC','CorrespAcc','KPP','PayeeINN','PayerINN'] as $prop){
			$this->paramsOption->$prop = str_replace(' ', '', $this->paramsOption->$prop);
		}
		
		foreach (['Name','PersonalAcc','BankName','BIC','CorrespAcc'] as $prop){
			$code .= "|$prop={$this->paramsOption->$prop}";
		}
		
		if(is_string($this->optData->dataJSON))
			$dataFields = new \Reg($this->optData->dataJSON);// json_decode($this->optData->dataJSON,false);
		else
			$dataFields = $this->optData->dataJSON;
//echo "<pre>200 \$dataFields ". print_r($dataFields, true).'</pre>';
		
		$code .= "|Sum=$Sum";
		
if($this->paramsOption->Purpose){
		$date = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->format('Y-m-d');//->toSql(true);//
		$Purpose = str_replace('{date}', $date, $this->paramsOption->Purpose);
		$Purpose = str_replace('{order}', $this->orderID, $Purpose);
		$code .= "|Purpose=$Purpose";
}

		
		if($this->paramsOption->PayeeINN)
		$code .= "|PayeeINN={$this->paramsOption->PayeeINN}";
		
		if($this->paramsOption->PayerINN)
		$code .= "|PayerINN=$dataFields->PayerINN";
//		PaymTerm
//name="PaymPeriod"=day PaymTerm='data'

		if($this->paramsOption->KPP)
		$code .= "|KPP={$this->paramsOption->KPP}";
		
		if($this->orderID)
		$code .= "|DocNo=$this->orderID";
		
		if($this->paramsOption->PaymPeriod){
			$PaymPeriod = $this->paramsOption->PaymPeriod;
			$date = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone());
			$weekDay = $date->format('N');
			if($weekDay + $this->paramsOption->PaymPeriod > 5 && $this->paramsOption->PaymPeriod < 12 )
				$PaymPeriod += 2;
			elseif($this->paramsOption->PaymPeriod >= 12)
				$PaymPeriod += 4;
			elseif($this->paramsOption->PaymPeriod >= 19)
				$PaymPeriod += 6;
			elseif($this->paramsOption->PaymPeriod >= 26)
				$PaymPeriod += 8;
			
			$PaymTerm = $date->add(\DateInterval::createFromDateString("$PaymPeriod day"))->format('Y-m-d');//->toSql(true);
			$code .= "|PaymTerm=" . $PaymTerm; //substr($PaymTerm, 0, 10)
		}
		
		
//		if(JFactory::getApplication()->input->getCmd('opt','') != 'debit')
		
		
		if($this->dataReg || $this->optData && $this->optData->dataJSON){
			
			if(empty($this->dataReg))
				$this->dataReg = new \Reg($this->optData->dataJSON);
			$Phone = $this->dataReg->Phone;
			$Phone = str_replace('-', '', $Phone);
			$Phone = str_replace('+', '', $Phone);
			$Phone = str_replace(' ', '', $Phone);
			$Phone = str_replace('(', '', $Phone);
			$Phone = str_replace(')', '', $Phone);
			$Phone = str_replace('/', '', $Phone);
			$Phone = str_replace('.', '', $Phone);
			$Phone = str_replace(':', '', $Phone);
			
			if($Phone)
				$code .= "|Phone=".(int)$Phone;
		}
		
		
		
		if($this->paramsOption->Contract)
		$code .= "|Contract={$this->paramsOption->Contract}";
		
		if($dataFields->LastName)
		$code .= "|LastName=$dataFields->LastName";
		
		
		if($dataFields->FirstName)
		$code .= "|FirstName=$dataFields->FirstName";
		
		if($dataFields->MiddleName)
		$code .= "|MiddleName=$dataFields->MiddleName";
		
		$secret = JFactory::getConfig()->get('secret');
		$code .= "|TechCode=".md5($this->orderID.$secret);
		
		return $code;
//"ST00012|Name=$prm->Name|PersonalAcc=$prm->PersonalAcc|BankName=$prm->BankName|BIC=$prm->BIC|CorrespAcc=$prm->CorrespAcc|KPP=$prm->KPP|PayeeINN=$prm->PayeeINN|Purpose=$prm->Purpose
//|Sum=$Sum|Phone=$dataFields->Phone|Contract=$this->orderID|LastName=$dataFields->LastName|FirstName=$dataFields->FirstName|MiddleName=$dataFields->MiddleName";
	}

	private $fileQR = '';

	public function mailFiles($webpath =  false) : array{
		
		if($this->fileQR)
			return [$webpath ? '/tmp/' . $this->fileQR : JPATH_ROOT . '/tmp/' . $this->fileQR];
		
		
		require_once JPATH_MODMULTIFORM . '/media/qr/phpqrcode/qrlib.php';
		
		
		/*  @var $opt OptionData   */
		/** @var OptionData $opt Опция 	 */
		
		
		$this->orderID;
		$this->moduleID;
		$secret = JFactory::getConfig()->get('secret');
//		sys_get_temp_dir() . DIRECTORY_SEPARATOR . 
		$this->fileQR		= md5($this->orderID.$this->moduleID.$secret).'.png';
		$filePath	= JPATH_ROOT . '/tmp/' . $this->fileQR;

//echo "<pre>\$fileQR ". print_r($this->fileQR, true).'</pre>';
//echo "<pre>\$fileQR ". print_r($filePath, true).'</pre>';
//echo "<pre>\getCode() ". print_r($this->getCode(), true).'</pre>';

		$qr = \QRcode::png($this->getCode(), $filePath, 'L', 3, 2);
		
//echo "<img src='/tmp/". $this->fileQR ."'><br>";	
//echo "<pre>\$qr". print_r($qr, true).'</pre>';
//try {
//		\Joomla\Filesystem\File::copy(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $fileQR, JPATH_ROOT . '/tmp');
//} catch (Exception $exc) {
//		echo "<pre>\$fileQR ". print_r($exc->getTraceAsString(), true).'</pre>';
//}


//		return [ $this->index => $filePath];
		return [ $webpath ? '/tmp/' . $this->fileQR : JPATH_ROOT . '/tmp/' . $this->fileQR];
	}
	
	public function ajaxResultHtmlLast($orderID = 0, $statuses_result = []) : string{
		
		$this->orderID = $orderID;
		
		ob_start();
		?>

		<?php		
		$message = ob_end_clean();
		
		
		
		if(JFactory::getApplication()->input->getCmd('opt','') != 'debit')
			return '';
		
		$image = $this->mailFiles(true);
		return $this->renderMessage($image[0], true);
	}
	
	
	public function messageShow($article_id = 0) : string{
		
		$image = $this->mailFiles(true);
//echo "<pre>\$image". print_r($image, true).'</pre>';
//echo "<pre>\$this->fileQR ". print_r($this->fileQR, true).'</pre>';
		
		
		
		$app = JFactory::getApplication();
		
		
		$secret = JFactory::getConfig()->get('secret');
		$hash = md5($this->orderID.$secret);
		
//		$app->input->getAlnum('pass','') ==  md5($config->secret . $param->id . $app->input->getInt('order',0))
		
		$hash = md5($secret . $this->moduleID . $this->orderID);
		
		$urlPass = $this->getUrlPass();
		
		ob_start();
//echo $urlPass.' ';
//echo $hash.' ';
//echo $this->moduleID.' ';
//echo $this->orderID.' ';
		?> 
<img src="<?=$image[0]?>" style="float:left;">
		<?php
		
		if($urlPass){
		?>
<p style="text-align: center;">
	<a href='<?=$urlPass?>&opt=debit' class='btn btn-primary button' target='_blank'>Просмотр/Печать счёта</a>
</p>
		<?php
		}
		
		return ob_get_clean();
	}
	
		 
	public function mailMessage() : string{
		
		$cach = new \Joomla\CMS\Cache\CacheControllerFactory;
//		$cach->createCacheController()->
		
//		$ca = new \Joomla\CMS\Cache\Cache();
//		$ca->
		
//		JFactory::getContainer()->get(CacheControllerFactoryInterface::class)->createCacheController($handler, $options);
		JFactory::getCache();
		
//		$mailer = JFactory::getContainer()->get(\Joomla\CMS\Mail\MailerFactoryInterface::class)->createMailer();
		$mailer = JFactory::getMailer();
//		$mailer = (new \Joomla\CMS\Mail\MailerFactory)->createMailer();
//		$mailer->addStringEmbeddedImage($string, $cid);
		
		
		$image = $this->mailFiles(true);
		
		
		return $this->renderMessage('cid:'.basename($image[0] ?? ''), false);
	}
	
	private function renderMessage($imagePath = '', $btnPrintShow = false) : string{
	
//		$btnPrintShow
		
		
//		if(! file_exists($imagePath))
//			$imagePath = "" . $imagePath;
		
		
		$Sum = 0;
		
		foreach ($this->optionsData as $optD){
			if($this->index == $optD->i) {
				$this->optData = $optD;
				continue;
			}
			
			/* @var $optD Joomla\Module\MultiForm\Site\OptionData Опция  */
			if($optD->sign == '+') $Sum = $Sum + ($optD->count * $optD->cost);
			if($optD->sign == '-') $Sum = $Sum - ($optD->count * $optD->cost);
			if($optD->sign == '/') $Sum = $Sum / ($optD->count * $optD->cost);
			if($optD->sign == '*') $Sum = $Sum * ($optD->count * $optD->cost);
			
		}
		
		if(empty($this->dataReg) && $this->optData && $this->optData->dataJSON)	
			$this->dataReg = new \Reg($this->optData->dataJSON);
		
		$inpData = $this->dataReg ?? new \Reg();
		
//		echo "<pre>";
//		print_r((array)$this->optData);
//		echo "</pre>";
		
//		echo "<pre>";
//		print_r($this->paramsOption);
//		echo "</pre>";
		
		
		ob_start();
?>
<div class="main" style="<?php if(! $btnPrintShow):?>page-break-before: always;_page-break-after: always; _page-break-inside: avoid;<?php endif; ?>">
<table width="100%" style="font-family: Arial;">
	<tr>
		<td style="width: 68%; padding: 0px 0;text-align: center;">Оплату необходимое произвести до 19 января 2025</td>
		<td rowspan="2" style="width: 32%; text-align: center; padding: 0;">
			<?php if($imagePath) { ?>
				<img src="<?= $imagePath ?>" style="width: 80%; ">
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td style="width: 68%; padding: 20px 0;">
			<div style="text-align: justify; font-size: 11pt;"><?= str_replace("\n", "<br>", $this->paramsOption->descriptionBefore ?? '')  ?></div>
        </td>
    </tr>
</table>


<table width="100%" border="2" style="border-collapse: collapse; width: 100%; font-family: Arial;" cellpadding="2" cellspacing="2">
    <tr style="">
        <td colspan="2" rowspan="2" style="min-height:13mm; width: 105mm;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="height: 13mm;">
                <tr>
                    <td valign="top">
                        <div><?= str_replace("\n", "<br>", $this->paramsOption->BankName ?? '')  ?></div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" style="height: 3mm;">
                        <div style="font-size:10pt;">Банк получателя</div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="min-height:7mm;height:auto; width: 25mm;">
            <div>БИК</div>
        </td>
        <td rowspan="2" style="vertical-align: top; width: 60mm;">
            <div style=" height: 7mm; line-height: 7mm; vertical-align: middle;"><?= $this->paramsOption->BIC ?? '' ?></div>
            <div><?= $this->paramsOption->CorrespAcc ?? '' ?></div>
        </td>
    </tr>
    <tr>
        <td style="width: 25mm;">
            <div>Сч. №</div>
        </td>
    </tr>
    <tr>
        <td style="min-height:6mm; height:auto; width: 50mm;">
            <div>ИНН <?= $this->paramsOption->PayeeINN ?? '' ?></div>
        </td>
        <td style="min-height:6mm; height:auto; width: 55mm;">
            <div>КПП <?= $this->paramsOption->KPP ?? '' ?></div>
        </td>
        <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 25mm;">
            <div>Сч. №</div>
        </td>
        <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 60mm;">
            <div><?= $this->paramsOption->PersonalAcc ?? '' ?></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="min-height:13mm; height:auto;">

            <table border="0" cellpadding="0" cellspacing="0" style="height: 13mm; width: 105mm;">
                <tr>
                    <td valign="top">
                        <div><?= str_replace("\n", "<br>", $this->paramsOption->Name ?? '')  ?></div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" style="height: 3mm;">
                        <div style="font-size: 10pt;">Получатель</div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<br/>

<div style="font-weight: bold; font-size: 25pt; padding-left:5px; font-family: Arial;">
    Счет № <?= $this->orderID ?> от <?= JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->format('d.m.Y') ?></div>
<br/>

<div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>

<table width="100%" style="font-family: Arial;">
    <tr>
        <td style="width: 30mm; vertical-align: top;">
            <div style=" padding-left:2px; ">Поставщик:    </div>
        </td>
        <td>
            <div style="font-weight:bold;  padding-left:2px;">
               <?= $this->paramsOption->Name ?? '' ?>, <nobr>ИНН <?= $this->paramsOption->PayeeINN ?? '' ?></nobr><?= $this->paramsOption->KPP ? ', <nobr>КПП '. $this->paramsOption->KPP.'</nobr>' : '' ?> <?= $this->paramsOption->supplier ? ',': '' ?><br>
<span style="font-weight: normal;"><?= str_replace("\n", "<br>", $this->paramsOption->supplier ?? '')  ?></span></div>
        </td>
    </tr>
    <tr>
        <td style="width: 30mm; vertical-align: top;">
            <div style=" padding-left:2px;">
				<?php if($inpData->Company && $inpData->PayerINN && $inpData->PayerAddress && $inpData->FirstName && $inpData->MiddleName && $inpData->LastName && $inpData->Phone){ ?>
				Покупатель:
				<?php } ?>
			</div>
        </td>
        <td>
            <div style="font-weight:bold;  padding-left:2px;">
<?php 
//['Phone','FirstName','LastName','MiddleName','PayerINN','PayerAddress','Company'];
 $inpData?>
				<?=$inpData->Company?>
				<?=$inpData->Company&&$inpData->PayerINN? ',':''?>
				<?=$inpData->PayerINN? ' <nobr>ИНН '.$inpData->PayerINN.'</nobr>':''?>
				<?php //=$inpData->Company&&$inpData->PayerINN? ', <nobr>КПП '.$inpData->PayerKPP.'</nobr>':''?>
				<br>
				<span style="font-weight: normal;">
					<?=$inpData->PayerAddress ? $inpData->PayerAddress.'<br>' : ''?>
					<?= ($inpData->FirstName && $inpData->MiddleName && $inpData->LastName) ? $inpData->FirstName .' '.$inpData->MiddleName .' '.$inpData->LastName .'' : ''?><?= $inpData->Phone? ',':''?>
					<?=$inpData->Phone? '<br> <nobr>тел.: '.$inpData->Phone.'</nobr>':''?>
				</span>
			</div>
        </td>
    </tr>
</table>


<table border="2" width="100%" cellpadding="2" cellspacing="2" style="border-collapse: collapse; width: 100%; font-family: Arial;">
    <thead>
    <tr>
        <th style="width:13mm; ">№</th>
       
        <th>Товары (работы, услуги)</th>
        <th style="width:20mm; ">Кол-во</th>
        <th style="width:17mm; ">Ед.</th>
        <th style="width:27mm;  ">Цена</th>
        <th style="width:27mm;  ">Сумма</th>
    </tr>
    </thead>
    <tbody >
		
<?php $summAll = 0;
$count = 0;

foreach ($this->optionsData as $i => $optD){
	/** @var  \Joomla\Module\MultiForm\Site\OptionData $optD */
	if($this->index == $optD->i)
		continue;
	
	$sum =  $optD->cost * $optD->count;	
	$summAll += $sum;
	$count += 1;
	
	echo "<tr>";
	echo "<td style='width:13mm; '>$i</td>";
	echo "<td>$optD->title / $optD->label</td>";
	echo "<td style='width:20mm; '>$optD->count</td>";
	echo "<td style='width:17mm; '>Шт.</td>";
	echo "<td style='width:27mm; text-align: center; '>$optD->cost</td>";
	echo "<td style='width:27mm; text-align: center; '>$sum</td>";
	echo "</tr>";
}
$summAll;
$summTotal = $summAll + ($summAll / 100 * ($this->paramsOption->NDS ?? 0));
?>
    </tbody>
</table>

<table style="font-family: Arial;" border="0" width="100%" cellpadding="1" cellspacing="1">
    <tr>
        <td></td>
        <td style="width:27mm; font-weight:bold;  text-align:right;">Итого:</td>
        <td style="width:27mm; font-weight:bold;  text-align: center; "><?= number_format((float)$summAll, 2, '.', '')  ?></td>
    </tr>
  <tr>
        <td></td>
        <td style="width:27mm; font-weight:bold;  text-align:right;">Итого НДС:</td>
        <td style="width:27mm; font-weight:bold;  text-align: center; "><?= $this->paramsOption->NDS ?? 0 ?></td>
    </tr>
  <tr>
        <td></td>
        <td style="width:37mm; font-weight:bold;  text-align:right;">Всего к оплате:</td>
        <td style="width:27mm; font-weight:bold;  text-align: center; "><?= number_format((float)$summTotal, 2, '.', '')  ?></td>
    </tr>
</table>

<br />
<div style="font-family: Arial;">
Всего наименований <?=$count?> на сумму <?= number_format((float)$summTotal, 2, '.', '')  ?> рублей.<br />
<?= mb_ucfirst($this->num2str(floor($summTotal))) ?></div>
<br /><br />
<div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>
<br/>
  <div style="font-family: Arial; font-size: 10pt;">
	<?= str_replace("\n", "<br>", $this->paramsOption->descriptionAfter ?? '')  ?>
  </div>
  <br /><br />
<div style="background: _url('<!--url печати в png сюда-->');  background-repeat: no-repeat; padding: 30px 10px; width: 400px; height: 250px;">
<div>Руководитель ______________________ </div>
<br/>  <br /><br />

<div>Главный бухгалтер ______________________</div>
<br/>

<div style="width: 85mm;text-align:center;">М.П.</div>
<br/>
  </div>
  <br/>  <br /><br /><br/>  <br /><br /><br/>  <br /><br />
</div>
<?php if($btnPrintShow){?>
<button onclick='window.print();' class='btnPrint hide' style='position:fixed;top:3px;left:3px; border-radius: 4px; padding: 12px;font-weight: bold;font-size:larger;border:1px solid black;cursor:pointer;opacity:0.8'>Печать</button>
<?php }?>
<style>
html,body{
    height: 297mm;
    width: 210mm;
	margin: 0;
	padding: 0;
	margin: auto;
		
}
@media print {
	.btnPrint.hide{
		display: none;;
	}
.left_column {
	background: white; 
	color: black; 
	border-right: solid 1px #00578a;
  }
}
</style>
<?php
		return ob_get_clean();
	}
	
	/**
 * Возвращает сумму прописью
 * @author runcore
 * @uses morph(...)
 */
	function num2str($num) {
		$nul	= 'ноль';
		$ten	= [
				['','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'],
				['','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'],
		];
		$a20	= ['десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать'];
		$tens	= [2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто'];
		$hundred= ['','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот'];
		$unit	= [ // Units
			['копейка' ,'копейки' ,'копеек',	 1],
			['рубль'   ,'рубля'   ,'рублей'    ,0],
			['тысяча'  ,'тысячи'  ,'тысяч'     ,1],
			['миллион' ,'миллиона','миллионов' ,0],
			['миллиард','милиарда','миллиардов',0],
		];
	//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out	= [];
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) 
					continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) 
					$out[] = $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else 
					$out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) 
					$out[] = $this->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else 
			$out[] = $nul;
		$out[] = $this->morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
		$out[] = $kop.' '.$this->morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}
	function morph($n, $f1, $f2, $f5) {
		$n = abs(intval($n)) % 100;
		if ($n > 10 && $n < 20) 
			return $f5;
		$n = $n % 10;
		if ($n > 1 && $n < 5) 
			return $f2;
		if ($n == 1) 
			return $f1;
		return $f5;
	}
}