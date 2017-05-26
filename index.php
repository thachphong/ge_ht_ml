<?php
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/Htmldom.php'); 
require_once(__ROOT__.'/Cssstyle.php'); 
require_once(__ROOT__.'/Cssdom.php');
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
		$css_file = 'css/style.css';
		$css = new Cssdom($css_file);
		$param['background1'] = '#A52A2A';
		$param['background2'] = '#952929';
		$param['color_hover1'] ='#f9fd51';
		$param['font_family'] = "Tahoma,Arial,Times New Roman";	
		$param['font_size'] = '13px';		
		$param['background_hover2'] = '#edabb7';	
		
		$css->set_menu_style($param);
		$style = new Cssstyle();
		$style->width='90%';
		$style->margin='auto';
		$css->set_style('.container',$style);
		/*$style = new Cssstyle();
		$style->background = '#8C6450';
		$style->display="inline-block";
		//$style->padding="5px";
		$style->color="#fff";		
		$css->set_style('#menu_top ul li',$style,TRUE);
		
		$style = new Cssstyle();
		$style->padding="5px";
		$style->color="#fff";	
		$style->line_height='30px';
		$style->background = '#8C6450';	
		$css->set_style('#menu_top ul li a',$style);
		
		$style2 = new Cssstyle();
		$style2->display="none";
		$style2->position="absolute";
		$css->set_style('#menu_top ul li ul',$style2);
		$style3 = new Cssstyle();
		$style3->display="block";
		$style3->padding_left = 0;
		$css->set_style('#menu_top ul li:hover ul',$style3);
		
		$style4 = new Cssstyle();
		$style4->display="list-item";
		//$style4->position='relative';
		$css->set_style('#menu_top ul li ul li',$style4);
		
		$style5 = new Cssstyle();
		$style5->display="none";
		$style5->position="absolute";
		$css->set_style('#menu_top ul li ul li ul',$style5);
		$css->set_style('#menu_top ul li:hover ul li ul',$style5);
		$style3->width ='120';
		$style3->display="block";
		//$style3->top = 0;
		$style3->left = '100%';
		$style3->position = 'absolute';
    	//$style3->margin_top = '-1px';
		$css->set_style('#menu_top ul li ul li:hover ul',$style3);
		$css->set_style('#menu_top ul li ul li ul li',$style4);*/
		$this->add_cssfile('css/bootstrap.min.css');
		$this->add_cssfile('css/style.css');
		//$this->add_row_model($file_name);
	}
	public function add_model($obj_id,$html){
		$md = new Htmldom($this->file_name);
		$md->set_html($obj_id,$html);
		$md->save();
	}
	public function add_cssfile($file_name){
		$md = new Htmldom($this->file_name);
		$md->add_cssfile($file_name);
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
		$ul ="<div id='menu_top'>
			<ul class=\"dropDownMenu\">";
		//for($i = 0; $i < $level;$i++){
			$ul .="<li><a>menu item 1</a>
					<ul>
						<li><a>menu item 1 aaa</a></li>
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
		$ul .="</ul></div>";
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




$md = new Html_generate();
$md->basic_model();
/*$css = new Cssstyle();
$css->border = 1;
$css->display='none';
var_dump($css->convert());
*/
