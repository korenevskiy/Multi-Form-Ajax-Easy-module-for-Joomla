<?php
//https://learn.javascript.ru/server-sent-events
//https://bigboxcode.com/php-server-sent-events-sse

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);




$modID		= (int)($_REQUEST['mod']	?? 0);
$orderID	= (int)($_REQUEST['order']	?? '');
//$api		= $_REQUEST['api']		?? '';
//$ajax		= $_REQUEST['ajax']		?? '';
//$frame		= $_REQUEST['frame']	?? '';
//$type		= $_REQUEST['type']		?? '';
//$timestamp	= 1740085723;

//session_start();
//echo session_id();



if($modID && $api && $order){
	
}


require_once realpath(__DIR__ . '/../../../configuration.php');

$config = new JConfig;

echo $orderID;

// <editor-fold defaultstate="collapsed" desc="SSE - Server Send Events">
if ($modID || $modID && $orderID) {

	
	$config->lifetime; // in minute
		
//		$semId = sem_get($orderID, 1);
//		sem_acquire($semId);				//Захватывает семафор
		
											//Создаёт или открывает сегмент разделяемой памяти
//		$shmId = shm_attach($semId, mb_strlen($message) + 10);
//		$shmId = shm_attach($semId);	//Создаёт или открывает сегмент разделяемой памяти
											//Вставляет или обновляет переменную в разделяемой памяти
//		shm_put_var($shmId, 'modMultiForm.'.$modID.'.article.'.$orderID, $valid ? "OK:$orderID\n" : "BAD:$orderID\n");
//		shm_put_var($shmId, 'modMultiForm.'.$modID.'.message.'.$orderID, $message);
		
		
//		if (shm_has_var($shmId, 'modMultiForm'.$modID)) {
			// Если есть, считываем данные
//			$data = (array)shm_get_var($shmId, 'modMultiForm'.$modID);
//		} 
//		sem_release($semId);
//		sem_remove($semId);
		https://t.me/+79162783272
//	header('proxy_http_version 1.1');
//	header('proxy_set_header Connection ""');
		
//	header('Transfer-Encoding: ""');
//	header('Content-Encoding: ""');
	
//	header('Transfer-Encoding: none');
//	header("Content-Encoding: none");
//	header('Content-Type', 'text/html');
//	header('Connection', 'keep-alive');
//	header('Transfer-Encoding: chunked');
		

//header('Transfer-Encoding: ""');
	header('Content-Type: text/event-stream');
	header('Connection: keep-alive');
	header('Cache-Control: no-store');
	header("Cache-Control: no-cache");
	header("Cache-Control: no-cache,no-store");
	
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
	header('Access-Control-Allow-Origin: *');
	
	
	
	header("X-Accel-Buffering: no");
	date_default_timezone_set("Europe/Moscow");

//	header("Transfer-Encoding: none");  ~!
//	header('Content-Encoding: chunked');
	header('Content-Encoding: none'); 
	header('Transfer-Encoding: chunked');
//	header("Transfer-Encoding: none");
	$counter = 0;

$ref = new DateTime();
$len = 0;
//$semId = sem_get($orderID, 1);
		

	while (true) {
		// Output blank data to make the connection abort detection work


		// or you can send comment instead
		//echo ": your comment here\n";
		
		/** Получает идентификатор семафора */
//$semId = sem_get($modID, 1);
		
		/** Захватывает семафор */
//sem_acquire($semId);
		
		/** Создаёт(открывает) сегмент памяти  */
//$shmId = shm_attach($semId);//shm_attach(int $key, ?int $size=null)
		
//		shm_put_var($shmId, 'modMultiForm.'.$this->moduleID.'.message.'.$invId, $message);
//		shm_put_var($shmId, 'modMultiForm.'.$this->moduleID.'.session.'.$invId, array_keys($_SESSION));
		/** Проверяет, существует ли конкретная запись */

//		if (shm_has_var($shmId, 0)) {
			
//		// Если есть, считываем данные
//			$message = shm_get_var($shmId, 0);
//			if($message){
//				echo "data: orderID -| ".addslashes($message);//htmlspecialchars($message);;
//				echo "\n\n";
//				ob_flush();
//				flush();
//			}
//		}

//		if (shm_has_var($shmId, 'modMultiForm.'.$modID.'.session.'.$orderID)) {
//			
//		// Если есть, считываем данные
//			$session = shm_get_var($shmId, 'modMultiForm.'.$modID.'.session.'.$orderID);
//		
//			echo "data: orderID - ". json_encode($session);
//			echo "\n\n";
//			ob_flush();
//			flush();
//		}
		
//sem_release($semId);
//sem_remove($semId);
		
		
		if (connection_aborted()){

echo "data: stop";
echo "\n\n";
ob_flush(); 
flush(); 
exit(204);
return; 
			break;
		}
		
//echo "data: \n\n";
//		$chunk = str_pad("data: ", 4096); 
//		echo dechex(strlen(7)). "\r\ndata: \n\r\n";
// printf("%x\r\n%s\r\n", strlen($chunk), $chunk);
//echo "\r\ndata: \n\r\n";
// flush();
// Send chunk to browser function send_chunk($chunk) < 
// The chunk must fill the output buffer or php won't send it 
		
		
 $chunk = str_pad("data:   OK WOW!!.. ", 4096); 
// $chunk = "data: $len  OK WOW!!.. ";
 printf("%x\r\n%s\r\n", strlen($chunk), $chunk);
 flush();
// echo "13\r\ndata:  OK WOW!!.. \r\n";
// ob_flush();
 
 $chunk = str_pad('data: ', 4096); 
// $chunk = 'data: \n'; 
 printf("%x\r\n%s\r\n", strlen($chunk), $chunk); 
 flush();
 
//$len = strlen($chunk);
 
// Send your content in chunks for($i=0; $i\r\n"); 
// usleep(500000); > 
// note that if you send an empty chunk 
// the browser won't display additional output echo "0\r\n\r\n"; flush();
		
//$now = new DateTime();
//$diff = $now->diff($ref);

//		$line = "data: $len " . $counter ++ . '. SSE, от - ' . date('Y-m-d H:i:s') . " -> interval: $diff->h:$diff->i:$diff->s\n";

//		echo dechex(strlen($line)+1). "\r\n" . $line . "\r\n";
//		echo strlen($line). "\r\n" . $line . "\r\n";
		

//		ob_flush();	// вывод содержимого буфера и отправляет его в браузер, без завершения
//		flush();	// сброс буфера, с отправкой в браузер
		


		sleep(2);
	}
	return;
}
// </editor-fold>




















//echo "<pre>JConfig ";
//echo print_r(new JConfig,true);
//echo "</pre>";
$config->live_site;
$config->sitename;

$config->user;
$config->password;
echo	$config->db;
$config->dbprefix;

$config->host;
$config->password;
//$config->offline_message;

// <editor-fold defaultstate="collapsed" desc="Параметры модуля из БАЗЫ">
if (false) {
	$db = @mysqli_connect($config->host, $config->user, $config->password, $config->db) or die("Ошибка X!<br>");
	//mysqli_set_charset ( $db , "utf8" ) or die ( 'Не установлена кодировка');
	$query = "SELECT id, title, params FROM `{$config->dbprefix}modules` WHERE id = $modID; ";

		$res = mysqli_query($db, $query);
	$data = mysqli_fetch_all($res, MYSQLI_ASSOC);
	//mysql_close($db);

	$module = new stdClass;

	foreach ($data as $mod) {
		$module->id = $mod['id'] ?? 0;
		$module->title = $mod['title'] ?? '';

		$param = $mod['params'] ?? '{}';

		$param = @json_decode($param, false);

				$module->param = $param ?? null;
		$module->list_fields = &$param->list_fields ?? null;
		;
		$module->frame = &$param->frame ?? null;
		$module->api = &$param->api ?? null;
		$module->ajax = &$param->ajax ?? null;
		//	$module->id = $mod['id'] ?? 0;
	}

	$type = '';
	if ($api == $module->api)
		$type = 'API';
	if ($ajax == $module->ajax)
		$type = 'API';
	if ($frame == $module->frame)
		$type = 'API';

	if (empty($data) || empty($module->param))
		$type = '';

}// </editor-fold>


//echo "<pre>\$query ";
//echo print_r($query,true);
//echo "</pre>";

//echo "<pre>\$data ";
//echo print_r($module,true);
//echo "</pre>";
//echo "<pre>\$list_fields ";
//echo print_r($module->list_fields,true);
//echo "</pre>";
//echo "<pre>\$frame ";
//echo print_r($module->frame,true);
//echo "</pre>";
//echo "<pre>\$api ";
//echo print_r($module->api,true);
//echo "</pre>";
//echo "<pre>\$ajax ";
//echo print_r($module->ajax,true);
//echo "</pre>";


//echo "<pre>";
//echo print_r($_SERVER,true);
//echo "</pre>";

//echo "<pre>\$_REQUEST ";
//echo print_r($_REQUEST,true);
//echo "</pre>";

//session_start();
//echo session_id();

//echo "<!DOCTYPE html>";
//echo "<pre>\$_REQUEST ";
////echo print_r(session_id(),true);
//echo "</pre>";
//return;

// index.php
session_start([1=>1]);
session_id();
?>
<!DOCTYPE html>
<title>SSE</title>
<h1><a href="//<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>">SSE</a> - <?= date('Y-m-d H:i:s');?></h1>
<br><br>
<pre  > $_SESSION 
<?=print_r($_SESSION,true)?>
</pre>
<br><br>
<pre  >session_id	<?=print_r(session_id(),true)?>		
session_name	<?=print_r(session_name(),true)?>
</pre>
<br>

<br><br>
<pre  >$_REQUEST 
<?=print_r($_REQUEST,true)?>
</pre>
<br>
<ul id="modList" data-live-site="<?=$config->live_site?>" data-mod="<?=$modID ?: 175?>" data-order="<?=$orderID?>">
</ul>

<script>
</script>
<style>
	#modList,pre{
	  border-radius: 5px;
	  border: 1px solid gray;
	}
</style>

<script src="<?=$config->live_site?>/modules/mod_multi_form/media/sse.js"  ></script>