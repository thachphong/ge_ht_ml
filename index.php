<?php
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/Htmldom.php'); 
require_once(__ROOT__.'/Cssstyle.php'); 
class Html_generate
{
	private $header="header";
	private $content="content";
	private $footer="footer";
	private $file_name = "index.html";
	public function basic_model(){
		$file_name ="index.html";
		$str ='<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					</head>
					<body>
						<div class="row"><div class="container" id="header"></div></div>
						<div class="row"><div class="container" id="content"></div></div>
						<div class="row"><div class="container" id="footer"></div></div>
					</body>
				</html>';
		if(file_exists($file_name)){
			unlink($file_name);
		}
		$this->write_file($file_name,$str);
		//$this->add_row_model($file_name,'body',3);
		$this->add_model('header',$this->get_menu());
		//$this->add_row_model($file_name);
	}
	public function add_model($obj_id,$html){
		$md = new Htmldom($this->file_name);
		$md->set_html($obj_id,$html);
		$md->save();
	}
	
	public function add_row_model($file_name/*,$model,$row*/){
		//$html = file_get_html($file_name);
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		$doc->loadHTMLFile($file_name);
		/*$xpath = new DOMXPath($doc);
		$body = $xpath->query('//'.$model);
		if($body != null){
			if($body->length > 0){
				$node = $body->item(0);				
				
				for($i = 1;$i<=$row;$i++){
					$newText = $doc->createElement("div");
					$newText->setAttribute('class','row');
					$node->appendChild($newText); 
				}
			}
		}		*/
		$doc->saveHTMLFile($file_name);	
		//unset($xpath);
		unset($doc);
	}
	public function get_menu(){
		$ul ="<ul>";
		//for($i = 0; $i < $level;$i++){
			$ul .="<li><a>menu item 1</a>
					<ul>
						<li><a>menu item 1</a></li>
						<li><a>menu item 1</a></li>
						<li><a>menu item 1</a></li>
					</ul>
				 </li>
				 <li><a>menu item 2</a>
					<ul>
						<li><a>sub item 1</a></li>
						<li><a>sub item 2</a></li>
						<li><a>sub item 3</a>
							<ul>
								<li><a>sub sub item 1</a></li>
								<li><a>sub sub item 2</a></li>
								<li><a>sub sub item 3</a></li>
							</ul>
						</li>
					</ul>
				 </li>
				 <li><a>menu item 3</a></li>
				 <li><a>menu item 4</a></li>";
		//}
		$ul .="</ul>";
		return $ul;
	}	
	private function write_file($filepath, $str)
	{
		//$filepath = mb_convert_encode($filepath,'HTML-ENTITIES','UTF-8'); 
		if (($fp = @fopen($filepath, "a")) === false) {
			return;
		}

		if (!flock($fp, LOCK_EX)) {
			@fclose($fp);
			return;
		}

		if (fwrite($fp,  $str ) === false) {
			@flock($fp, LOCK_UN);
			@fclose($fp);
			return;
		}

		if (!fflush($fp)) {
			@flock($fp, LOCK_UN);
			@fclose($fp);
			return;
		}

		if (!flock($fp, LOCK_UN)) {
			@fclose($fp);
			return;
		}

		if (!fclose($fp)) {
			return;
		}
	}
}




//$md = new Html_generate();
//$md->basic_model();
$css = new Cssstyle();
$css->border = 1;
$css->display='none';
var_dump($css->convert());

