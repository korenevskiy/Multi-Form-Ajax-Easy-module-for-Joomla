<?php namespace Joomla\Module\MultiForm\Site; defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
//https://makigra.ru/joom/?login=koreshs@mail.ru&game=1022#mod175&login=koreshs@mail.ru&gameid=1022

class OptionMakigraUserGame extends \Joomla\Module\MultiForm\Site\Option  { // JFormFieldCalculator  // extends JFormField 
	
	
	public $type			= 'makigrausergame';
	public $stages = 7;
	
//	public $status = 'undo';
	
	public const PAYED = TRUE;
	public const UNDO = FALSE;
	public const RELOAD = 'reload';
	
//	public $html = '';


	
	public function getJSON() : string{
		
		$json_array = [];
		
		$json_array['login']	= JFactory::getApplication()->input->getCommand('login','');
		$json_array['game']		= (int)JFactory::getApplication()->input->getInt('game', 0);
		
		$json_array['moduleID'] = $this->moduleID;// module id raw
		$json_array['fieldName']= $this->nameinput;
		
		$app = JFactory::getApplication();
		$app->setUserState("multiForm.{$this->moduleID}.maklogin", trim($json_array['login']) );
		$app->setUserState("multiForm.{$this->moduleID}.makgame",  (int)$json_array['game'] );
		
		if(empty($json_array['login']) || empty($json_array['game']))
			return '';
		
		$makgame = (int)$json_array['game'];
		
		return json_encode($json_array);
	}
	
	
	public function getDataset() : array  {
		
		$app = JFactory::getApplication();
		
//Array
//(
//    [maklogin] => 
//    [makgame] => Array
//        (
//            [makgame] => 2235
//            [login] => 0
//        )
//
//)
//echo "<br> \$login: <pre>" .print_r($login,true)."</pre>";
		$login = (string)$app->input->getString('login','');
		$gameID = (int)$app->input->getInt('game',0);
		return parent::getDataset() + ['maklogin' => $login, 'makgame' => $gameID];
	}
//	
//	
	// <editor-fold defaultstate="collapsed" desc="------------>> Поля рендера">
//"<script>
//el = document.getElementById('game175');
//for(let i in el.options){
//	if(el.options[i].text == document.title + ' 💰'){ 
//	el.selectedIndex = i;
//	break;}
//}
//</script>";
		
//$html .= "<script>
//console.clear();
//const event = function(){
//	console.clear();
//for(let opt of document.getElementById('game$this->moduleID').options){
//	if(opt.text == document.title + ' 💰')
//	document.getElementById('game$this->moduleID').value = opt.value;
//	console.log(document.title);
//}
//}
//event();
//document.addEventListener('DOMContentLoaded', event);
//document.addEventListener('readystatechange', event);
//document.addEventListener('DOMContentLoaded', event);
//</script>";
	public function getControlGame($allUsers = false) {
//return '<hr>';

		$app = JFactory::getApplication();


		$login = $app->getUserState("multiForm.{$this->moduleID}.maklogin"); //->toString()

		if(empty($login))
		$login	= (string)JFactory::getApplication()->input->getCommand('maklogin','');
//echo "<br> LOGIN: <pre>$login</pre>";
		
		$gameID = (int)$app->input->getInt('makgame', '');
//		$gameID = $app->input->getInt('game', '');
		$title = (string)$app->input->getString('title', '');
		$title = trim($title);
		
		$db = $this->getDatabase();
//		$login = $db->escape($login);


//echo "<br> \$gameID: <pre>$gameID</pre>";
//echo "<br> LOGIN: <pre>$login</pre>";


		$query = "
SELECT g.id, 'game' `type`, g.name `text`, g.date_stop, g.masters, IF($gameID=g.id, 1, 0) sel
FROM sh_games g, 
	(SELECT id, CONCAT(id,',%') id2, CONCAT('%,',id) id3, CONCAT('%,',id,',%') id4, fio FROM sh_users WHERE login = '$login') as u
WHERE (g.masters LIKE u.id OR g.masters LIKE u.id2 OR g.masters LIKE u.id3 OR g.masters LIKE u.id4) AND g.date_stop

UNION

SELECT CONCAT('+',id), 'pole' `type`, title `text`, '' date_stop, '' masters, 0 sel
FROM sh_ru_pages  
WHERE parent = 72
ORDER BY masters DESC, date_stop , `text`
;";

		

		$games = $db->setQuery($query)->loadObjectList(); // _id, type, title, date_stop, masters
//echo "<br> QUERY: <pre>$query</pre>";
//echo "<br> \$select: <pre>count:".count($games).' '.print_r($games,true)."</pre>";
		$html = '';
		
//$html .= "<br>input-getArray(): <code>".print_r($app->input->getArray(),true)."</code>"; 
//$html .= "<br>\$gameID:  $gameID  : <pre>".print_r($app->getInput()->getArray(),true)."</pre>"; 


		
//		https://makigra.ru/joom/platform-games/vstrecha-s-soboj-2?login=koreshs@mail.ru&game=1022
		$html .= "<select id='game$this->moduleID' required='' name='{$this->nameinput}[makgame]' class='form-control input  select form-select'>";

		$date = \Joomla\CMS\Date\Date::getInstance();

		array_unshift($games, (object)['date_stop'=>'0000-00-00','id'=>'','text'=>JText::_('JOPTION_DO_NOT_USE'),'sel'=>0]);
		
		$select = '';
		
		foreach ($games as $game) {
			
			$text = '';
			
//toPrint($value,'$value',0,'pre',true);
			switch (true) {
				case ($game->date_stop == ''): $text = ' 💰';
					break;
				case ($game->date_stop == '0000-00-00'): $text = '';
					break;
				case ($game->date_stop > $date): $text = ' ⌛';
					break;
				case ($game->date_stop < $date): $text = ' ⏰';
					break;
			}
			
			
			$text = trim($game->text) . $text;
			$selected = ($game->id == $gameID || $game->sel || ! $gameID && trim($game->text) == $title ) ? 'selected' : '';
			
			if($selected)
				$select = $game;
			
			$_title = addslashes(trim($game->text));
			$html .= "<option type='text' value='$game->id' data-title='$_title' $selected>$text</option>";
		}
		$html .= "</select>"; 
//echo "<br> \$select: <pre>".print_r($games,true)."</pre>";
//echo "<br> \$select: <pre>$select</pre>";
		return $html;
	}

	public function getControlUser($allUsers = false) {

		$app = JFactory::getApplication();
		$login = $app->getUserState("multiForm.{$this->moduleID}.maklogin", ''); //->toString()
		
		if(empty($login))
		$login	= JFactory::getApplication()->input->getCommand('maklogin','');
		 
//echo "<br> LOGIN render: <pre>$login</pre>";
		
		$html = "<input type='text' pochta-user name='{$this->nameinput}[login]'  value='$login' id='login$this->moduleID' class='form-control input text'>";
		return $html;
	}
	// </editor-fold>

	
//	public function getName($i = '') : string{
//		return  $this->type . $i . $this->moduleID; 
//	}
	public function getLabels() : string|array {
		return ["login$this->moduleID" => 'Логин' ,"login" => 'Логин' ,"game$this->moduleID" => 'Игра'];
	}
	
	public function renderFields($fldName='', $value='', $fldClass='', $placeholder='') : string| array {
		$style = '';
		
		$app = JFactory::getApplication();
//		$makgame = (int)$app->getUserState("multiForm.{$this->moduleID}.makgame");
//		if($makgame)
		$style = "
<style>
#mfForm_form_$this->moduleID .col1{
	display: none;
}
</style>
		";
		
		return ["game$this->moduleID" => $this->getControlGame(), "login$this->moduleID" => $this->getControlUser(), $style];
	}
	
	protected $databaseAwareTraitDatabase;

	protected function getDatabase(): \Joomla\Database\DatabaseInterface {
		if($this->databaseAwareTraitDatabase)
			return $this->databaseAwareTraitDatabase;
		
		if(class_exists('\cfgMakIgra'))
			return \cfgMakIgra::getDbo();
		
		if(file_exists(JPATH_ROOT . '/MakIgra.php'))
			require_once JPATH_ROOT . '/MakIgra.php';

		if(class_exists('\cfgMakIgra'))
			$this->databaseAwareTraitDatabase = \cfgMakIgra::getDbo();
		
		if ($this->databaseAwareTraitDatabase)
			return $this->databaseAwareTraitDatabase;

		throw new \Joomla\Database\Exception\DatabaseNotFoundException('Database not set in ' . \get_class($this));
	}
	
	
	/**
	 * Проверка наличия Юзера
	 * Очистка названия в опции заказа (в Input[Robokassa]).
	 * @param type $orderID
	 * @return string
	 */
	public function ajaxResultHtmlFirst($orderID = 0) : string{
//	{"list_fields":{"field_label":["Пол года","1 месяц","Годовой доступ"],"field_interval":["182.625","30.4375","365.25"]},
//			"field_label":"","field_interval":"","field_calculator":"costs"}	 
		
		$this->orderID = $orderID;
		
		$app = JFactory::getApplication();
		
//		$gameID	= (int)$app->getInput()->getInt("{$this->nameinput}.makgame");
//		$login	= (string)$app->getInput()->getString("{$this->nameinput}.login");
		
//		$login = trim($login);
		
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$_POST\n".print_r($_POST,true)."  \n\n" , FILE_APPEND);
//file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$login:$login \$gameID:$gameID  \n\n" , FILE_APPEND);

		$inputDate = $app->getInput()->getArray([
			$this->nameinput => [
				'makgame'	=> 'STRING',
				'login'		=> 'STRING',
			],
		]);
//file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$inputDate\n".print_r($inputDate,true)."  \n\n" , FILE_APPEND);
		$gameID	= $inputDate[$this->nameinput]['makgame']	?? '';
		$login	= $inputDate[$this->nameinput]['login']		?? '';
		$login = trim($login);
		
		
		
		
		
		
		
		$gameNew = false;
		
		if(strpos($gameID, '+') === 0){
			$gameID = (int)substr($gameID, 1);
			$gameNew = true;
		}
		
		$gameID = (int)$gameID;
		
		
//file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$gameNew:$gameNew \$login:$login \$gameID:$gameID  \n\n" , FILE_APPEND);
//file_put_contents($fDeb, __LINE__.": makigra.php <<===  $this->modeApiAjax \$login:$login  \$gameID:$gameID \n\n" , FILE_APPEND);
		
		if(empty($login) || empty($gameID))
			return '';
//return "<output>$login<pre>".print_r(JFactory::getApplication()->getInput()->getArray(),true)."</pre></output>";
//echo "<br><b>\$loginInput</b><pre>".print_r($login,true)."</pre><br>";
//echo "<br><b>\$loginState</b><pre>".print_r($app->getUserState("multiForm.{$this->moduleID}.maklogin"),true)."</pre><br>";
		
//		if($app->getUserState("multiForm.{$this->moduleID}.maklogin"))
//		   $app->setUserState("multiForm.{$this->moduleID}.maklogin", trim($json_array['login']) );
//			return '';
		
//		return "<br><br><br><b>Пользователь с логином(почта) <span style='white-space:nowrap'>'$login'</span> не найден!!!</b><br> <br><code>Проверьте внимательно написание!!!</code><br><br><br>";
		
		$bd = $this->getDatabase();
		
		
		$login = $bd->escape($login);
			
//return "<output><pre>".print_r($login,true)."</pre></output>";
		$query = "
SELECT id
FROM sh_users 
WHERE login = '$login'
;";
		$userID = $bd->setQuery($query)->loadResult(); // _id, type, title, date_stop, masters
		if(empty($userID)){
			return "<output>Пользователя с логином «<b>{$login}</b>» <br>не существует!</output>";
		}
		
		
		
//		$makgame = (int)JFactory::getApplication()->getInput()->getInt('makgame');
//		$makgame = (int)JFactory::getApplication()->getUserState("multiForm.{$this->moduleID}.makgame");
		$html = '';
// $html .="<br>\$gameID:  $makgame        \$this->field_name: $this->field_name<br>";  
// $html .="<br>POST: <code>".print_r($_POST,true)."</code>"; 
// $html .="<br>paramsOption: <code>".print_r($this->paramsOption,true)."</code>"; 
		
		
		
		if($gameNew){
			$query = "
SELECT title
FROM sh_ru_pages  
WHERE parent = 72 AND id = $gameID
ORDER BY title DESC
			;";
		}else{
			$login = $bd->escape($login);
			$query = "
SELECT g.name
FROM sh_games g, 
	(SELECT id, CONCAT(id,',%') id2, CONCAT('%,',id) id3, CONCAT('%,',id,',%') id4, fio FROM sh_users WHERE login = '$login') as u
WHERE g.date_stop AND g.id=$gameID AND (g.masters LIKE u.id OR g.masters LIKE u.id2 OR g.masters LIKE u.id3 OR g.masters LIKE u.id4) 
			;";
		}
		
		$game_title = $bd->setQuery($query)->loadResult(); // _id, type, title, date_stop, masters
		
		
		$app = JFactory::getApplication();
//		$app->setUserState("multiForm.{$this->moduleID}.maklogin", trim($json_array['login']) );
		$app->setUserState("multiForm.{$this->moduleID}.$gameID.maktitle",  $game_title );
		
		
		
		
		
		$calc = $this->paramsOption->field_calculator;
		
		$optionsReqest = (array)JFactory::getApplication()->getInput()->post->getArray([$calc=>['options'=>'STRING']]);//["{$calc}[options]"=>'array']
//$optionsReqest = $optionsReqest["{$calc}[options]"];
// $html .="<br>post-getArray(): <code>".print_r($optionsReqest,true)."</code>"; 
		$filter = \Joomla\CMS\Filter\InputFilter::getInstance();
		$filter->clean($filter);
		
		
		foreach ($optionsReqest[$calc]['options']??[] as $i => $opt){
			$opt = $filter->clean($opt);
			$data = json_decode($opt,true);
			if(empty($data) && ! is_array($data))
				continue;
			
			$data['title'] = $game_title;
			$optionsReqest[$calc]['options'][$i] = json_encode($data);
//			$options[$i] = json_encode($data);
		}
		$_POST[$calc]['options'] = $optionsReqest[$calc]['options'] ?? [];
		JFactory::getApplication()->getInput()->set($calc, $optionsReqest[$calc]);
		
		
		
		
		
		
		
		
		
		
//file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$_POST\n".print_r($_POST,true)."  \n " , FILE_APPEND);		
//$optionsReqest = (array)JFactory::getApplication()->getInput()->post->getArray([$calc=>'ARRAY']);//["{$calc}[options]"=>'array']
//file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$optionsReqest\n".print_r($optionsReqest,true)."  \n " , FILE_APPEND);


//return $html."<br>input-getArray(): <code>".print_r(JFactory::getApplication()->getInput()->getArray(),true)."</code>";
//return $html."<br>input-getArray(): <pre>".print_r(JFactory::getApplication()->getInput()->getArray(),true)."</pre>";
//		JFilterInput::getInstance();
		
		return '';
	}
	
	private $optData = null;

	/**
	 * Возвращает массив подполей опции класса <b>FieldData</b>
	 * Объект подготовленных данных для платежа
	 * Возвращает Null в случае ошибки(отказа)
	 * @return array|null
	 */
//	public function dataSet($selfDatas = []) : array {
////		return parent::dataSet($selfDatas);
//			
//		
//		if($selfDatas){
//			$this->optData = reset($selfDatas);
//		}
//		
//		$app = JFactory::getApplication();
//		$inputDate = $app->getInput()->getArray([
//			$this->nameinput => [
//				'makgame'	=> 'STRING',
//				'login'		=> 'STRING',
//			],
//		]);
//		$gameID	= $inputDate[$this->nameinput]['makgame']	?? '';
//		$login	= $inputDate[$this->nameinput]['login']		?? '';
//		$login = trim($login);
//		
//		
//		if(empty($gameID) || empty($login))
//			return $selfDatas;
//		
//		$gameNew = false;
//		
//		if(strpos($gameID, '+') === 0){
//			$gameID = substr($gameID, 1);
//			$gameNew = true;
//		}
//		$gameID = (int)$gameID;
//		
////file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$inputDate\n".print_r($inputDate,true)."  \n\n" , FILE_APPEND);
////file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/_getOptionsDatas.txt', basename (__FILE__).'  '. date('Y-m-d h:i:s', time()).' =====  =====  $modeApiAjax: ' .print_r($this->modeApiAjax ,true). " \n",FILE_APPEND  );//FILE_APPEND
////		if($this->modeApiAjax != OptionField::MODE_AJAX)
////			return [];
////		$sum = $app->getUserState("multiForm.{$this->moduleID}.robokassa{$this->nameinput}_{$this->orderID}", 0);
//		
//		
//		$gameTitle = $app->getUserState("multiForm.{$this->moduleID}.maktitle.$gameID", '');
//		
//		if($gameTitle == '')
//			return $selfDatas;
//			
//		
//			
//		$optData = new \Joomla\Module\MultiForm\Site\OptionData;
//		$optData->sign		= '+';
//		$optData->cost		= 0;
//		$optData->count		= 1;
//		$optData->orderID	= $this->orderID;
//		$optData->type		= $this->type;
//		$optData->i			= $this->index;
//		$optData->field_name= $this->field_name;
//		$optData->title		= $gameTitle;
//		$optData->label		= '';
//		$optData->description= $gameTitle;
//		$optData->dataJSON	= json_encode(['gameID'=>$gameID,'login'=>$login,'gameNew'=>$gameNew,]);
//		
//		$this->optData = &$optData;
//		
//		return [$this->optData];
//	}
	
	public $messages = '';
	
	public $status = '';

	public function dataStatus($stage = 0){
		return $this->status == static::PAYED ? static::STATUS_DONE : static::STATUS_UNDO;
	}
	private $mailRecipient = [];
	
	/**
	 * Продление игр(ы) согласно оплаченным суммам
	 * @param array[Option, OptionData, ...] $optionsData
	 * @param int $stage Порядковый этап выполнения
	 * @return string - FALSE позволит вызвать сообщение об отказе формы, TRUE готовность сохранить форму	<<-----		
	 */
	public function dataCompute(array $optionsData = [], $stage = 0) : ?array {
		
		$this->status = static::UNDO;
//		parent::dataCompute($optionsData);
		
$fDeb = JPATH_ROOT . '/modules/mod_multi_form/_helper.txt';
//file_put_contents($fDeb, __LINE__.": makigra.php===== 	   \n" , FILE_APPEND);
//		if($optionsData){
//			$this->optData = reset($optionsData);
//		}
		
		$app = JFactory::getApplication();
		$inputDate = $app->getInput()->getArray([
			$this->nameinput => [
				'makgame'	=> 'STRING',
				'login'		=> 'STRING',
			],
		]);
		$gameID	= $inputDate[$this->nameinput]['makgame']	?? '';
		$login	= $inputDate[$this->nameinput]['login']		?? '';
		$login = trim($login);
		
		$gameNew = false;
		
		if(strpos($gameID, '+') === 0){
			$gameID = substr($gameID, 1);
			$gameNew = true;
		}
		$gameID = (int)$gameID;
		
		if($login)
			$this->mailRecipient[] = $login;
		
//file_put_contents($fDeb, __LINE__.": maksigra.php===== \$stage:$stage \$index:$this->index	\$type:$this->type   \n" , FILE_APPEND);
		
		
		if($this->modeApiAjax == static::MODE_SUBMIT){
			
//			$gameTitle = $app->getUserState("multiForm.{$this->moduleID}.$gameID.maktitle", '');
			$opData = new \Joomla\Module\MultiForm\Site\OptionData; 
			$opData->sign		= '+';
			$opData->cost		= 0;
			$opData->count		= 0;
			$opData->orderID	= $this->orderID;
			$opData->type		= $this->type;
			$opData->i			= $this->index;
			$opData->field_name	= $this->field_name;
//			$opData->title		= $gameTitle;
			$opData->label		= '';
//			$opData->description= $gameTitle;
			$opData->dataJSON	= json_encode(['gameID'=>$gameID,'login'=>$login,'gameNew'=>$gameNew,'message'=>''],JSON_UNESCAPED_UNICODE);
			
			return [$opData];
		}
		
		if(empty($optionsData) )
			return [];
		
		$opData = null;
		
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$gameIDs:".print_r(__METHOD__,true)." \n" , FILE_APPEND);
		foreach ($optionsData as $optData){
			if($this->index == $optData->i && $this->type == $optData->type){
				$opData = $optData;
				
				$optJson		= json_decode($optData->dataJSON,true);
				$gameID			= $optJson['gameID']	?? '';
				$login			= $optJson['login']		?? '';
				$gameNew		= $optJson['gameNew']	?? '';
				$this->messages	= $optJson['message']	?? '';
				
				if($login)
					$this->mailRecipient[] = $login;
				
				$this->status = $optData->count ? static::PAYED : static::UNDO;
//file_put_contents($fDeb, __LINE__.": makigra.php=====:$this->status count:$optData->count  \$gameID:$gameID	\$login:$login  \n" , FILE_APPEND);
			}
		}
//file_put_contents($fDeb, __LINE__.": makigra.php=====  	\$gameIDs:".print_r(__METHOD__,true)." \n" , FILE_APPEND);

		
		if($opData && $opData->count == 1 || $this->status == static::PAYED)
			return NULL;
		
		if(empty($opData) || empty($gameID) || empty($login))
			return [];
		
//file_put_contents($fDeb, __LINE__.": makigra.php===== \$gameID:$gameID	\$login:$login   \n" , FILE_APPEND);
		
		
		
//file_put_contents($fDeb, __LINE__.": makigra.php <<=== \$inputDate\n".print_r($inputDate,true)."  \n\n" , FILE_APPEND);
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/_getOptionsDatas.txt', basename (__FILE__).'  '. date('Y-m-d h:i:s', time()).' =====  =====  $modeApiAjax: ' .print_r($this->modeApiAjax ,true). " \n",FILE_APPEND  );//FILE_APPEND
//		if($this->modeApiAjax != OptionField::MODE_AJAX)
//			return [];
//		$sum = $app->getUserState("multiForm.{$this->moduleID}.robokassa{$this->nameinput}_{$this->orderID}", 0);
		
		
		
//		if($gameTitle == '')
//			return $selfDatas;
			
		
		
		$allsum = 0;
		$payed = static::UNDO;

//if($this->modeApiAjax != 'submit')
//file_put_contents($fDeb, __LINE__.": MAK.php =====  setOptionsDatas()   	\$optionsData ".print_r($optionsData,true)."  \n" , FILE_APPEND);

		
		$gameIDs	= [];
		$articleIDs	= [];
		
		$sumPayed	= 0;
		
		$dataJSON	= $this->optData ? json_decode($this->optData->dataJSON) : null;
		
//		$makTitle	= $gameTitle;//$this->optData ? $this->optData->title : '';
//		$gameID		= $dataJSON ? $dataJSON->gameID	: 0;
//		$login		= $dataJSON ? $dataJSON->login	: '';
//		$gameNew	= $dataJSON ? $dataJSON->gameNew: FALSE;
		
		
		
//		$optData = new \Joomla\Module\MultiForm\Site\OptionData;
		foreach ($optionsData as $optData){
			
			if($optData->sign == '+')
				$allsum += $optData->count * $optData->cost;
			
			if($optData->sign == '-'){
				$allsum -= $optData->count * $optData->cost;
				$sumPayed += $optData->count * $optData->cost;
			}
			
			if($optData->sign == '*')
				$allsum *= $optData->count * $optData->cost;
			
			if($optData->sign == '/')
				$allsum /= $optData->count * $optData->cost;
			
			if($optData->type == 'calculator' && $optData->articleID){
				$articleIDs[] = $optData->articleID;
			}
			if($optData->type == 'calculator' && $optData->dataJSON){
				$gameIDs[] = json_decode($optData->dataJSON)->gameID ?? 0;
			}
		}
		
		$payed = $allsum <= 0;
		
//file_put_contents($fDeb, __LINE__.": makigra.php===== \$payed:$payed \$gameID:$gameID		\$gameIDs:".print_r($gameIDs,true)." \n" , FILE_APPEND);

		
		if(empty($payed) || empty($gameID) && empty($gameIDs)){
			$db = JFactory::getDbo();
			$this->messages .= "\n<br>Отмена оплаты.";
			$this->messages .= "\n<br>Недоплата на сумму $allsum, оплачено только $sumPayed";
			$this->messages .= "\n<br>GameID: $gameID [". implode(',', $gameIDs).']';
			$query = "
UPDATE `#__content` 
SET `fulltext`= CONCAT(`fulltext`, '<hr>', '<br>" . $db->escape($this->messages) . "')
WHERE id = $this->orderID 
		";
//file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/apiSuccess.txt', date('Y-m-d h:i:s', time()).' =====robo.php===== $_SERVER ' .print_r($query ,true). " \n\n", FILE_APPEND  );//FILE_APPEND
			if($this->orderID && $sumPayed)
				$db->setQuery($query)->execute();
		
//if($this->modeApiAjax != 'submit')
//file_put_contents($fDeb, __LINE__.": MAK.php =====  setOptionsDatas() \$gameID:$gameID  \$payed:UNDO \n\n" , FILE_APPEND);
			$this->status = static::STATUS_UNDO;
			return [];
		}
		
		if($gameIDs && empty($gameID)){
			$gameID = reset($gameIDs);
		}
		
		if($articleIDs && empty($gameIDs)){
			$query = "
SELECT c.id, mak_game.value game
FROM #__fields_values mak_game , #__content c  
WHERE mak_game.field_id = 25 AND mak_game.item_id = c.id
	AND c.id IN (". implode(',', array_keys($articleIDs)).");"; // 27-IDполя со списком ID колод, 28-категория игр
//$html .= "<br> QUERY: <pre>$query</pre>";
			$gameIDs = JFactory::getDbo()->setQuery($query)->loadAssocList('id', 'game');
		
//			foreach($optionsData as $optData){
//				$gID = (int)($gameIDs[$optData->articleID] ?? 0);
//			
//				if($gameID && $gID && $gameID == $gID)
//					$optData->dataJSON = json_encode((object)['gameID' => $gID]);
//			}
		}

		if($gameID && empty($gameIDs)){
			foreach($optionsData as $optData){
				if($optData->type == 'calculator'){
					$optData->dataJSON = json_encode((object)['gameID' => $gameID]);
					break;
				}
			}
		} 
		

		$user = null;

//		$login = $app->getUserState("multiForm.{$this->moduleID}.maklogin",'');		
//		$login = $app->getInput()->getString("{$this->nameinput}[login]");
		
		if($login){
			$db = $this->getDatabase();
			$login = $db->escape(trim($login));
			$query = "
SELECT login, id, fio FROM sh_users WHERE login = '$login';
			";
//echo "<br> QUERY: <pre>$query</pre>";
			$user = $db->setQuery($query)->loadObject(); // _id, type, title, date_stop, masters
//echo "<br> \$login: <pre>$login</pre>";
			if(empty($user)){
//				$app->setUserState("multiForm.{$this->moduleID}.maklogin",'');
				$this->messages .= '<br>Пользователя с таким логином нет';
				$this->status = static::STATUS_UNDO;
				return [];
			}
			$login = $user->login;
			$user->id;
			$user->fio;
		}

		\modMultiFormHelper::debugPRE($this->paramsOption, 'Макигра - Оплачено');



//		return static::STATUS_DONE;
		
		
 
		/**
		 * Проверка
		 */
		$html = '';
//		$html .=   '  MakIgra->messageShow()<br>';
//		$html .= ' $paramsOption<pre>';
//		$html .= print_r($this->paramsOption,true);
//		$html .= '</pre><br><br>';
		
		
//		$html .= ' $optionsData<pre>';
//		$html .= print_r($optionsData,true);
//		$html .= '</pre><br><br>';
		
		
//echo "<br> QUERY: <pre>$query</pre>";
		
		$db = $this->getDatabase();
		
		$login	 = $db->escape($login);
		$gameID	 = $db->escape($gameID);
		$payed	 = $db->escape($payed);
		
		
//$html .= " \$login: <pre>$login</pre><br><br>";
//$html .= " \$gameID: <pre>$gameID</pre><br><br>";
//$html .= " \$payed: <pre>$payed</pre><br><br>";
//$html .= " \$gameNew: <pre>$gameNew</pre><br><br>";
//return $html;

//		$optData->label;
//		$optData->title;
//		$optData->description;
//		$optData->type;
						

		
		
//if($this->modeApiAjax != 'submit')
//file_put_contents($fDeb, __LINE__.": MAK.php =====  setOptionsDatas()  \$gameNew:$gameNew \n\n" , FILE_APPEND);
		
		
		$field_label = $this->paramsOption->list_fields->field_label;   // [Пол года,	1 месяц, Годовой доступ]
		$field_interval = $this->paramsOption->list_fields->field_interval;// [182.625,	30.4375, 365.25]
		
//return "$html<br><b class='makgame'>Игра продлена!!!</b><br>";
//echo "<br>507 makigra.php optionsData: <pre>".print_r($optionsData,true)."</pre>"; 
//echo "<br>$html";

//file_put_contents($fDeb, __LINE__."=====mak.php===== setOptionsDatas()  \$login:$login    \$gameID:$gameID  \$payed:$payed \$gameNew:$gameNew \n\n" , FILE_APPEND);
//file_put_contents($file, "=====mak.php===== 511 message:\n".print_r($optionsData,true)." \n\n" , FILE_APPEND);
		
		$description = '';
		 
		
		foreach ($optionsData as $i => $optData){
			if($optData->type != 'calculator' || empty($gameID)){
//				unset($optionsData[$i]);
				continue;
			}
			
			
			
//			if(empty($gameIDs[$optData->articleID])){
//				$this->messages .= "\n<br>Игры с artID:$optData->articleID не существует. ТРЕБУЕТСЯ ПРОВЕРКА этого ID.!!!  "
//								  . "Возврат суммы: " . ($optData->cost * $optData->count);
//				continue;
//			}
//if($this->modeApiAjax != 'submit')
//file_put_contents($fDeb, __LINE__.": MAK.php =====  setOptionsDatas()  \$optData:".print_r($optData,true)." \n\n" , FILE_APPEND);
			
			$i_interv = array_search($optData->label, $field_label);
			$days = $field_interval[$i_interv] ?? '';
			$days = ceil($days);
			
//			if(empty($days)){
//				file_put_contents(JPATH_ROOT . '/modules/mod_multi_form/_days.txt', __LINE__. ' makigrausergame.php/  НЕ существующий интервал: '  .print_r($optData->label ,true). " \n\n"  );//FILE_APPEND
//				continue;
//			}
			
			$title = '';
			
//echo "<b>\$gameID: $gameID</b> \$gameNew $gameNew<br>";
//continue;
//			$query = "
//SELECT g.id gameID , g.name , u.id userID, u.login, g.date_stop , DATE_ADD(g.date_stop, INTERVAL $days DAY) 
//FROM `sh_games` g,
//	(SELECT id, CONCAT(id,',%') id2, CONCAT('%,',id) id3, CONCAT('%,',id,',%') id4, fio FROM sh_users WHERE login = '$login') as u
//WHERE g.name = '$optData->title' AND 
//	g.masters LIKE u.id OR g.masters LIKE u.id2 OR g.masters LIKE u.id3 OR g.masters LIKE u.id;
//			";
			
			
//$html .= "<br> \$gameNew: <pre>$gameNew</pre>";

//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas() $this->modeApiAjax 
// 	\$this->allsum:$this->allsum   \$gameNew:$gameNew   \$valid:$payed  \n\n" , FILE_APPEND);

			
// Проверка существование игры.
			if($gameNew){
				
				
				$query = "
SELECT c.id , c.title, c.alias, c.introtext, c.fulltext, c.catid , cardpacks.value cardpacks
FROM #__fields_values mak_game , #__content c  
LEFT JOIN #__fields_values cardpacks ON  cardpacks.item_id = c.id   AND cardpacks.field_id = 27
WHERE c.catid = 28 AND mak_game.field_id = 25 
    AND mak_game.item_id = c.id
	AND mak_game.value = $gameID ;
				";
				
				$game = JFactory::getDbo()->setQuery($query)->loadObject();
				
				if( ! $game){
					
					$query = "
SELECT title, title name, id, id gameID, '' date_stop
FROM `sh_ru_pages`
WHERE id = $gameID;
					";
					$game = $this->getDatabase()->setQuery($query)->loadObject();
				}
				
				
				if($game->title)
					$optData->title = (string)$game->title;
				
//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas() \$game: ".print_r($game ,true)."\n" , FILE_APPEND);
//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas() \$query: ".print_r($query ,true)."\n" , FILE_APPEND);
//				$query = "
//SELECT id gameID , page_title, title, price, content, pic, description, url, path
//FROM `sh_ru_pages` 
//WHERE cat = 72 AND id = $gameID;
//				";
//				$game = $db->setQuery($query)->loadObject();
//				list($gameID, $optData->title) = $game;
// Добавление новой игры с известными колодами.
//				$query = "
//INSERT INTO `sh_games`(`id,name,about,game_stop,wait_msg,game,situation,masters,players,online,cardpacks,attributes,time,colors,date,date_stop) 
//SELECT 0, $game->title, '', 0, '' wait_msg, p.pic, '', u.id, u.id, '' online, '$game->cardpacks' cardpacks, '', 1, 'FFFF00,CCCC00,FFCC00,FF9900,FF6600,CC6600,FF3300,CC0000,CC0033,', DATE(NOW()), DATE_ADD(DATE(NOW()), INTERVAL $days DAY)
//FROM `sh_ru_pages` p, `sh_users` u
//WHERE u.login = '$login' AND p.cat = 72 AND p.title = '$optData->title'
//				";
				$title =  $db->escape($game->title ?? $gameTitle);
				
				
				$query = "
INSERT INTO `sh_games` 
(id_page,id_article, name, about,game_stop,			wait_msg,game,situation,masters,players,			online,cardpacks,attributes,time,date,							date_stop,colors) 
SELECT $gameID, $game->id, '$title', '', 0,		'' wait_msg, p.pic, '', $user->id, $user->id,		'' online, '$game->cardpacks' cardpacks, '', 1, DATE(NOW()),	 DATE_ADD(DATE(NOW()), INTERVAL $days DAY), 'FFFF00,CCCC00,FFCC00,FF9900,FF6600,CC6600,FF3300,CC0000,CC0033,'
FROM `sh_ru_pages` p
WHERE  p.cat = 72 AND p.id = $gameID 
				";
//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas()  
// 	\$this->allsum:$this->allsum   \$query:$query\n\n" , FILE_APPEND);
//$html .= "<br> QUERY: <pre>$query</pre>";
				$this->getDatabase()->setQuery($query)->execute();
				$gameID = $this->getDatabase()->insertid();
//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas()  
// 	\GameNewID:$gamID\n\n" , FILE_APPEND);
				$description .= "<br><b class='makgame'>Игра добавлена!!!</b><br>";
				$description .= $optData->title .' /'.$optData->label .'<br>';
				
				
				$this->messages .= "\n<br>Новая игра создана {$optData->title} ";
				
				$query = "
SELECT id, date_stop 
FROM `sh_games` 
WHERE id = $gameID;";
				$game = $this->getDatabase()->setQuery($query)->loadObject();
				$this->messages .= "\n<br>До: {$game->date_stop}";
				
				
			}else{

//$html .= "<br>\$gameID: <code>".print_r($gameID,true)."</code>"; 
//$html .= "<br>\$optData->title: <code>".print_r($optData->title ,true)."</code>"; 
				
//				 JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);//date('Y-m-d h:i:s a', time());
//				$datetime = JFactory::getDate()->setTimezone(JFactory::getUser()->getTimezone());//date('Y-m-d h:i:s a', time()); 
//				$tz = JFactory::getUser()->getTimezone(); 
//				$dtLast = JFactory::getDate($game->date_stop); 
//				if($dtLast < JFactory::getDate())
//					$dtLast = JFactory::getDate(); 
//				$datetime = $dtLast->setTimezone(JFactory::getUser()->getTimezone())->toSql(true);

				$db = $this->getDatabase();
				
				$query = "
SELECT id, date_stop 
FROM `sh_games` 
WHERE id = $gameID;";
				$game = $db->setQuery($query)->loadObject();
//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas()  
// 	\$this->allsum:$this->allsum   \$query:$query\n\n" , FILE_APPEND);
				
				$this->messages .= "\n<br> Была до: {$game->date_stop}";
				
// Обновление(продление) даты в текущей игре.
				$query = "
UPDATE  `sh_games` g 
SET g.date_stop = IF(g.date_stop > NOW(), DATE_ADD(g.date_stop, INTERVAL $days DAY), DATE_ADD(NOW(), INTERVAL $days DAY))
WHERE g.id = $gameID  AND g.date_stop AND g.date_stop != '0000-00-00';
				";
//$html .= "<br> QUERY: <pre>$query</pre>";
				
//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas()  
// 	\$this->allsum:$this->allsum   \$query:$query\n\n" , FILE_APPEND);

				$db->setQuery($query)->execute();
				

				$query = "
SELECT name title, name, id, id gameID,  date_stop 
FROM `sh_games` 
WHERE id = $gameID;";
				$game = $db->setQuery($query)->loadObject();
//file_put_contents($fDeb, __LINE__. ":  MAKIGRA.php--------> setOptionsDatas()  
//\$this->allsum:$this->allsum   \$query:$query\n\n" , FILE_APPEND);
				
				$this->messages .= "\n<br> Продлена до: {$game->date_stop}";
				
				if($game->title)
					$optData->title = (string)$game->title;
				
				$description .= "<br><b class='makgame'>Игра продлена!!!</b><br>";
				$description .= $optData->title .' /'.$optData->label .'<br>';
				
				
				$this->messages .= "\n<br>Игра {$optData->title} продлена ";
			}
			$no = 999999 - (int)$gameID;
			$this->messages .= "\n<br> Игра: <a href='https://makigra.ru/game/$no' target='_blank'>$optData->title</a>";
			$this->messages .= "\n<br><a href='https://makigra.ru/game/$no' target='_blank'>https://makigra.ru/game/$no</a><br>";
		}
		
	
//		$db = JFactory::getDbo();
	
//		$linkgame = $db->escape($linkgame);
		
//		$this->messages .= "\n<br>".$linkgame;
		$this->messages .= "\n<br> Оплата на сумму $sumPayed";
		
		
		
		
		
//		$query = "
//UPDATE `#__content` 
//SET `fulltext`= CONCAT(`fulltext`, '<hr>$linkgame<br><br>', '" . $db->escape( $this->messages) . " <br>')
//WHERE id = $this->orderID
//		";
//
//		if ($this->orderID)
//			$db->setQuery($query)->execute();
 

		
		$this->status = static::PAYED;
		$opData->count = 1;
		$opData->orderID = $this->orderID;
		
		
		$opData->dataJSON	= json_encode(['gameID'=>$gameID,'login'=>$login,'gameNew'=>$gameNew,'message'=>$this->messages],JSON_UNESCAPED_UNICODE);
		$opData->content	= $this->messages;
		
		return [$opData];// "$html<br><b class='makgame'>Игра продлена!!!</b><br>$description";
		
		
//		$this->status = static::STATUS_UNDO;
		
		// "$html<br><b>Игра НЕ продлена!!!</b><br>";
	}
	
	public function mailMessage() : string{
		return  $this->messages ;
	}
	public function articleTextCreate($orderID = 0, $ajaxReloadDoneUndo = []) : string{
		return  $this->messages ;
	}
	public function articleTextUpdate($orderID = 0, $ajaxReloadDoneUndo = []) : string{
		return  $this->messages ;
	}
	
	
//	Встреча с собой “2.0”
		/**
	 * Сообщение в модуле для отображения в ответе после отправки
	 * @param int $article_id
	 * @return string
	 */
	public function messageShow($article_id = 0) : string{
		return "<script>
setTimeout(function(){
window.opener.location.reload(false);
window.close();
},5000);
		</script>";
	}
	
	
	public function mailRecipient() : array{
		
		return $this->mailRecipient;
	}
}