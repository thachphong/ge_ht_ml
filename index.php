<?php
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/simple_html_dom.php'); 
class Html_generate
{
	public function basic_model(){
		$file_name ="index.html";
		$str ='<html>'."\r";
		$str  .='	<head>'."\r";
		$str  .='		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\r";
		$str  .='	</head>'."\r";
		$str  .='	<body>'."\r";
		$str  .='	</body>'."\r";
		$str  .='</html>'."\r";
		if(file_exists($file_name)){
			unlink($file_name);
		}
		$this->write_file($file_name,$str);
		$this->add_row_model($file_name,'body',3);
	}
	public function add_row_model($file_name,$model,$row){
		//$html = file_get_html($file_name);
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		$doc->loadHTMLFile($file_name);
		$xpath = new DOMXPath($doc);
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
		}		
		$doc->saveHTMLFile($file_name);	
		unset($xpath);
		unset($doc);
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




$md = new Html_generate();
$md->basic_model();

