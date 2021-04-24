<?php
abstract class SessionList
{
	public $listname;
	public $listvar;
	public $timeout;
	public $starttime;
	public $mode='link'; // or link json -_-
	public $msgbuf;
	public $reload_loc="";
	abstract function createList($params);
	abstract function cleanup();
	abstract function processfunc($key,$value);

	function __construct($name,$timeout,$listparams,$options=[])
	{
		if (isset($options['mode'])){
			$this->mode=$options['mode'];
		}

		if (!$this->reload_loc) {
			$this->reload_loc=$_SERVER['SCRIPT_NAME'];
		}
		
		// есть уже сессия
		//include_once $_SERVER['DOCUMENT_ROOT']."/session.php";

		if (isset($_GET['end'])) {
			$this->finish();
			$this->toNewRequest();
			return;
		}

		$this->timeout=$timeout;

		$this->listname=$name;
		$this->starttime=time();

		if (!isset($_SESSION[$this->listname]) || isset($_GET['new']))
		{
//			$_SESSION[MRConf::$target."filenum"]=0;
			$_SESSION[$this->listname]=array();
			$this->listvar=&$_SESSION[$this->listname];

			$this->listvar = $this->createList($listparams);
			if (!is_array($this->listvar)) {
				echo "Bad list<br>";
				dbg($this->listvar);
				return;
			}

			$_SESSION[$this->listname."_msgbuf"]='';
			$this->msgbuf = &$_SESSION[$this->listname."_msgbuf"];
			
			echo "(New session)<br>";

		}
		else
		{
			$this->listvar=&$_SESSION[$this->listname];
			if (count($this->listvar)===0) {
				$this->finish();
				return;
			}
			$this->msgbuf = &$_SESSION[$this->listname."_msgbuf"];
		}
		echo "Elements in list: ".count($this->listvar)."<br>";

		if ($this->mode=='js') {
			$this->js_button_code();
		}
		if ($this->msgbuf) {
			echo "Text:<br>";
			echo $this->msgbuf."<br><br>";
		}

		if (!isset($options['dont start'])) {
			$this->processList();
		}
	}

	function processList() {
		foreach($this->listvar as $l => $v)
		{
			try {
				if($this->processfunc($l, $v)) {
					unset($this->listvar[$l]);
				}
				else {
					throw new \Exception("Функция обработки не вернула true");
				}
			}
			catch (\Exception $ex) {
				echo "Во время обработки #$l произошло: ".$ex->getMessage().". Исправьте причину и перезагрузите страницу для продолжения.";
				exit();
			}
			$this->CheckTimeOut();
		}
		$this->finish();
	}


	function finish() {
		unset($_SESSION[$this->listname]);
		if ($this->msgbuf) {
			echo "Итоговые сообщения:<br>\n";
			echo $this->msgbuf."<br>\n";
		}
		echo "Обработка завершена<br>";
	}

	function checkTimeOut()
	{
		if (time()-$this->starttime>$this->timeout)
		{
			$this->toNewRequest("intervalId<br>");
			return true;
		}
		return false;
	}

	function triggerTimeOut() {
		$this->timeout=-1;
	}

	function toNewRequest()
	{
		switch ($this->mode) {
			case 'redirect':
				header('Location: '.$_SERVER['SCRIPT_NAME']);
				break;
			case 'js':
				echo "JS Redirect<script>startredirect();</script>\n";
				break;
			case 'link':
				echo "<a href='$_SERVER[SCRIPT_NAME]'>next</a><br>";
				echo "<a href='$_SERVER[SCRIPT_NAME]?new'>new</a><br>";
				break;
		}
		exit();
	}

	public function Count()
	{
		return count($this->listvar);
	}
	public function addmsg($line) {
		$_SESSION[$this->listname."_msgbuf"].=$line."\n";
	}

	public function js_button_code(){
	?>
<script>
	var intervalId;
	var seconds=5;

	function startredirect() {
		document.body.onclick=function(){
			stopbuttonclick(null);
		};
		document.getElementById("jsdiv").innerHTML = '<span id="timer"></span> Click anywhere to stop';
// <button onclick="stopbuttonclick(this);return false">stop</button>
		intervalId=setInterval(function() {
			if (seconds>0) {
				document.getElementById("timer").innerHTML = "Следующий шаг сессии через "+seconds;
				seconds--;
			}
			else {
				document.getElementById("jsdiv").innerHTML = "Обработка в процессе...";
				location.href='<?=$this->reload_loc?>';
				clearInterval(intervalId);
				document.body.onclick=null;
			}
		},1000);
	}


	function stopbuttonclick(button) {
		clearInterval(intervalId);
//		this.enabled=false;
		document.getElementById("jsdiv").innerHTML = "Redirect cancelled";
	}

</script><div id="jsdiv"></div>
<?
	}
}