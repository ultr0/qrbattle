<?php

include "start.php";
set_time_limit(0);
?>
<h1>Создание кодов</h1>
<a href='create_codes.php?stage=1'>Этап 1</a>
<a href='create_codes.php?stage=2'>Этап 2</a>
<a href='create_codes.php?stage=3'>Этап 3</a>
<p>
	Как сгенерировать коды: <br>
	Нажимайте последовательно ссылки трёх этапов, в каждом этапе подождите надпись "Обработка завершена". После третьего этапа можно будет скачать архив для печати кодов.
	
</p>
<br><br>
<pre>
<?php

if (isset($_GET['stage'])) {
	$stage=$_GET['stage'];
}
else {
	return;
}


include "../inc/phpqrcode/qrlib.php";
include "../inc/sessionlist.php";
/**
 * Class CreateCodes
 * Вообще то это вся замута нужна для первого этапа, потому что в 10 секунд не влезает
 */
class CreateCodes extends SessionList {
	
	private $stage;
	
	function __construct($stage) {
		
		if ($stage==3) {
			$this->stage3();
		}
		
		$this->reload_loc=$_SERVER['SCRIPT_NAME']."?stage=".$stage;
		$this->stage=$stage;
		parent::__construct('createcodes',8,"",['mode'=>'js']);
	}
		
	
	function createList($params) {
		global $db;
		$list=[];
		$res = $db->query("SELECT cid,code,type FROM codes");
		while ($r=$res->fetch_assoc()) {
			$list[]=$r;
		}
		return $list;
	}
	function cleanup() {
	}
	function processfunc($key, $value) {
		$method="stage".$this->stage;
		return $this->$method($value);
	}
	
	function stage1($r) {
		$url=SITE_HTTP_ROOT."?q=".$r['code'];
		QRcode::png($url, "codes/1/{$r['cid']}.png", 'L', 4, 2);
		return true;
	}
	
	function stage2($r) {
		
		$cid=$r['cid'];
		$src="codes/1/$cid.png";
		$dest="codes/2/$cid.png";
		$mask="codes/mask/mask{$r['type']}.png";
		list($w, $h)=getimagesize($src);
		
		$srcim=imagecreatefrompng($src);
		$im=imagecreatetruecolor($w, $h);
		$immask=imagecreatefrompng($mask);
		
		$white=imagecolorallocate($im, 255, 255, 255);
		for($i=0; $i < $w; $i++) {
			for($j=0; $j < $w; $j++) {
				if(imagecolorat($srcim, $j, $i) != 0) {
					imagesetpixel($im, $j, $i, imagecolorat($immask, $j, $i));
				}
				else {
					imagesetpixel($im, $j, $i, $white);
				}
			}
		}
		
		imagepng($im, $dest);
		return true;
	}
	
	function stage3() {
		global $db;
		$res = $db->query("SELECT cid,code,type FROM codes");
		
		$codes=[];
		$i=1;
		while($r=$res->fetch_assoc()) {
			$codes[$i++]=$r;
		}
		
		$rowcount=5;
		$colcount=3;
		list($w1, $h1)=getimagesize("codes/1/1.png");
		
		
		$w=$colcount * $w1;
		$h=$rowcount * $h1;
		
		$n=1;
		for($sheetn=0; $sheetn < 20; $sheetn++) {
			$sheet="codes/3/$sheetn.png";
			$im=imagecreatetruecolor($w, $h);
			
			$white = imagecolorallocate($im, 255, 255, 255);
			imagefilledrectangle($im, 0, 0, $w, $h, $white);
			
			for($i=0; $i < $rowcount; $i++) {
				for($j=0; $j < $colcount; $j++) {
					$cid=$codes[$n]['cid'];
					$src="codes/2/$cid.png";
					$srcim=imagecreatefrompng($src);
					imagecopy(
						$im, $srcim,
						$w1 * $j, $h1 * $i,
						0, 0,
					// бывает какой-то глюк, что некоторые коды другого размера
						imagesx($srcim), imagesy($srcim)
					
					);
					$n++;
				}
			}
			
			imagepng($im, $sheet);
		}
		
		$zip_filename="codes/sheets.zip";
		if (file_exists($zip_filename)) {
			unlink($zip_filename);
		}
		$zip=new ZipArchive();
		$zip->open("codes/sheets.zip",ZipArchive::CREATE);
		
		$zip->addGlob("codes/3/*");
		$zip->close();
		
		echo "Создание листов завершено<br><a href='codes/sheets.zip'>скачать листы</a>";
		exit();
	}
	

}

$createcodes = new CreateCodes($stage);