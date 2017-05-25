<?php
require_once(__DIR__.'/Cssstyle.php'); 
include __DIR__.'/Filelib.php';
class Cssdom
{     
	private $xpath ;
    private $sdom ;
    private $log;
    private $filename ;
    
    function __construct($file_name='')
    {
        if(strlen($file_name) > 0){                       
            $this->filename = $file_name;
        }
    }
    public function Load($file_name){
        $this->filename = $file_name;
    }
    
    public function set_style($obj_name,$css_style,$new_flg = FALSE ){
    	if($new_flg){
			if(file_exists($this->filename)){
				unlink($this->filename);
			}
		}
		$list = $css_style->convert();
		$str= $obj_name.'{';
		foreach($list as $key=>$val){
			$str .= $key.": ".$val.";";
		}
		$str .='}';
		$file = new Filelib();
		$file->write_file($this->filename,$str);
	}
}
