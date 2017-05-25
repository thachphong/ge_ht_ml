<?php

include __DIR__.'/simple_html_dom.php';

class Htmldom
{     
	private $xpath ;
    private $sdom ;
    private $log;
    private $filename ;
    function __construct($file_name='')
    {
        if(strlen($file_name) > 0){            
            $this->sdom = file_get_html($file_name);
            $this->filename = $file_name;
        }
    }
    public function Load($file_name){
        if(strlen($file_name) > 0){            
            $this->sdom = file_get_html($file_name);
            $this->filename = $file_name;
        }
        if( $this->sdom  != FALSE){
			return TRUE;
		}
		return FALSE;
    }
    public function set_html($obj_id, $str_html){
		$element = $this->sdom->find('//div[@id="'.$obj_id.'"]',0);
        if($element != NULL){
			return $element->innertext = $str_html;
		}
		$this->$sdom->save();
	}
	public function add_cssfile($file_name){
		$element = $this->sdom->find('//head',0);
        if($element != NULL){
			return $element->innertext .= '<link href="'.$file_name.'" rel="stylesheet" type="text/css">';
		}
		$this->$sdom->save();
	}
	public function save($file_name=''){
		if($file_name==''){
			$this->sdom->save($this->filename);
		}else{
			$this->sdom->save($file_name);
		}
	}
    public function get_text($condition){
        /*$arr_con = explode(';',$condition);
        $res ='';
        foreach($arr_con as $item){
            $elements = $this->xpath->query($item);
            
    		foreach ($elements as $element) { 
    			//$res .= $element->nodeValue.'<br>' ;
                $res.= $this->get_innerHTML($element);
    		}    
        }
        return $res;*/
        $element = $this->sdom->find($condition,0);
        if($element != NULL){
			return $element->plaintext;
		}
        return  '';
    }
    public function get_innerHTML($condition){
        /*$res ='';        
        foreach($this->sdom->find('div[id$="main_article"]',1) as $input){
             $res .= $input->innertext().'<br />';
        }
         return $res;*/
        //return  $this->sdom->find('div#main_article article',0)->innertext();
        $element = $this->sdom->find($condition,0);
        if( $element !=  NULL){
			return $element->innertext();
		}
		return  '';
    }
    public function file_save($data,$filename)
	{		
   		// Open a file for writing.
	    $fh = fopen($filename, 'w');
	   
	   if(!$fh) {
	     return 'faild';
		}
		else {
	       fwrite($fh,$data);
	       fclose($fh);
	       return true;
	   }
	}
    public function remove_element($remove_con,$count=NULL){
        //$remove_con= "div#mobile_byline;div#alh-postdate;div#alh-byline;div#bb";
        //$logger = new FileAdapter("app/logs/test.log");        
        $arr_con = explode(';',$remove_con);  
        //$this->logger->info($arr_con[0]);
        if($count=='1'){
            //$count = 0; // remove first item
            foreach($arr_con as $item_rem){
                $item =$this->sdom->find($item_rem,0) ;
                //$parent = $item->parentnode;
                //$parent->removeChild($item);
                //$this->logger->info('outer: '.$item->outertext());
                if($item != null){
					$item->outertext = '';
				}
                
                /*foreach($this->sdom->find($item_rem) as $item) {
                	$this->log->info('outer: '.$item->outertext);
				    $item->outertext = '';
				}*/
            }
        }else{
            foreach($arr_con as $item_rem){
                foreach($this->sdom ->find($item_rem) as $item) {
                    //$parent = $item->parentnode;
                    //$parent->removeChild($item);
                    $item->outertext = '';
                }
            }
        }
        
        $this->sdom->save();
    }
    public function Xpath_Remove($remove_con){
        $res ='';
        $arr_con = explode(';',$remove_con);        
        foreach($arr_con as $item){
            $elements = $this->xpath->query($item);            
    		foreach ($elements as $element) {                
                $element->parentNode->removeChild($element);
    		}    
        }       
    }
    public function get_attributes($condition){
        
        /*$elements = $this->xpath->query($condition);
		foreach ($elements as $element) {
			$array = array();
            foreach ($element->attributes as $attr) { 
                $array['@'.$attr->localName] = $attr->nodeValue; 
            }
            return $array;
		}*/
		$element =  $this->sdom->find($condition,0);
		if($element !=NULL){
			return $element->src;
		}
        return  '';
    }
    public function get_link($condition,$url =''){
        $arr_con = explode(';',$condition);   
        $result = array();     
        foreach($arr_con as $item){
            $elements = $this->sdom->find($item);    
            $this->log->info('item: '.$item);        
    		foreach ($elements as $element) {   
    			$img = $element->find('img',0);  
    			if($img != NULL){
	                $data['link'] = $element->href ;  
	                $this->log->info('img: '.$img->src); 
	                $data['img_link'] = preg_replace('/[\?].+/','', $img->src);              
	                if(strlen($url)>0){
						if(strpos($url,$data['link']) === FALSE){
							$data['link'] = $url.$data['link'];
						}
					}
	                $data['title'] = $element->title ;
	                if(isset($data['title']) && strlen($data['title']) > 0){
						$result[] = $data;	
					}
                }
    		}    
        }  
        return  $result;
    }
    public function get_img($condition,$url=''){
        $year = date('Y');
        $month = date('m');
        $y_path = IMG_DATA_PATH.'/'. $year;
        $m_path = $y_path .'/'. $month;
        if(!is_dir($y_path)){
            mkdir($y_path);
        }
        if(!is_dir($m_path)){
            mkdir($m_path);
        }
        $src =$this->get_attributes($condition);
        /*if(strpos($src,'http')===false){
			$src="http:".$src;
		}*/
        $extension = pathinfo($src, PATHINFO_EXTENSION);
        $file_name = $year.'/'.$month.'/'. uniqid(TRUE).'.'.$extension;
        $file_full = IMG_DATA_PATH.'/'.$file_name;
        //$attr = $this->get_attributes($condition);
        //echo $src;
        //echo $file_full;die;
        if( strpos($src, 'http')===FALSE){
			$src = $url.$src ;
		}
		$src = str_replace(' ','%20',$src);
        $data = $this->GetData_Url($src);
        //var_dump($data); 
        $this->file_save($data,$file_full);
        return $file_name;
    }
    public function get_img_byurl($src)
    {
        $year = date('Y');
        $month = date('m');
        $y_path = IMG_DATA_PATH.'/'. $year;
        $m_path = $y_path .'/'. $month;
        if(!is_dir($y_path)){
            mkdir($y_path);
        }
        if(!is_dir($m_path)){
            mkdir($m_path);
        }
        
        $extension = pathinfo($src, PATHINFO_EXTENSION);
        $file_name = $year.'/'.$month.'/'. uniqid(TRUE).'.'.$extension;
        $file_full = IMG_DATA_PATH.'/'.$file_name;
        $src = str_replace(' ','%20',$src);
        $data = $this->GetData_Url($src);        
        $this->file_save($data,$file_full);
        return $file_name;
    }
    public function GetTitle($condition){
        /*$elements = $this->xpath->query('//'.$condition);
		foreach ($elements as $element) {
			return $element->nodeValue ;
		}*/
		//$this->log->info('condition: '.$condition);
		$element = $this->sdom->find($condition,0);
		if($element != NULL){
			return $element->plaintext;
		}
        return  '';
    }
    public function Xpath_GetContent($obj_xpath,$condition){
        $res ='';
        $arr_con = explode(';',$condition);
        //$arr_con = explode(';',$condition);
        /*$el_remove = $xpath->query("//div[@id='alh-byline']");
        foreach ($el_remove as $rem) {
            $rem->parentNode->removeChild($rem);
        }*/
        
        foreach($arr_con as $item){
            $elements = $obj_xpath->query($item);
            
    		foreach ($elements as $element) {
                
                //foreach ($el_remove as $rem) {
                    //$elements->removeChild($el_remove);
                //}
                
    			$res .= $element->nodeValue.'<br>' ;
    		}    
        }
        return $res;
    }
    public function  get_content($condition,$url=''){
        $html = $this->get_innerHTML($condition);
        //$html = trim($this->replace_img_src($html,$url));
        $html = trim($this->remove_link_img($html));
        $html = $this->remove_link_relate($html);
        if(substr($html,0,5)=='<br/>' ){
            $html = substr($html,5,strlen($html));
        }
        return $html;
    }
    public function replaceString($lememt , $from_string,$to_string){
        foreach($this->sdom ->find($lememt) as $item){                    
            if(strpos($item->href, $from_string)!== FALSE)
            {
                $item->href = preg_replace($from_string,$to_string,$item->href);
            }
        }
        $this->sdom->save();
    }
    public function get_tag($lememt , $from_string,$to_string =''){
    	$tags = array();
    	$index =0;
        foreach($this->sdom ->find($lememt) as $item) {                    
            //if(strpos($item->href, $from_string)!== FALSE)
            //{
                //$tag_no = str_replace($from_string,$to_string,$item->href);
                //$tag_no = str_replace('/','',$tag_no);
                
                $tags[$index]['tag_name'] = $item->plaintext;
                $tags[$index]['tag_no'] = $this->to_slug($item->plaintext);
                $index++;
            //}
        }
        return $tags;
    }
    public function replace_img_src($html,$url=''){
        $year = date('Y');
        $month = date('m');
        $this->check_folder_download($year,$month);
        $htmldom = str_get_html($html);
        $list_img = $htmldom->find('img');
        foreach($list_img as $img)
        {
            $src = $img->src;
            $this->log->info('src1: '.$src);
            if( strpos($src, 'http')===FALSE){
				$src = $url.$src ;
			}
			$this->log->info('src2: '.$src);
			
            $data = $this->GetData_Url($src);           
            $extension = pathinfo($src, PATHINFO_EXTENSION);
            $img_name =$year.'/'.$month.'/'. uniqid(TRUE).'.'.$extension;
            //$file_name = $path_dl.'/'. $img_name;         
            $src_new = IMG_DATA_PATH.'/'.$img_name;   
            $this->file_save($data,$src_new);
            $img->src = '../../images/'.$img_name;
        }
        return $htmldom->save();
    }
    public function remove_link_img($html){
        if($html == NULL || strlen($html) == 0){
			return '';
		}
        $htmldom = str_get_html($html);
        $list_img = $htmldom->find('img');
        foreach($list_img as $img)
        {            
            $img->parentNode()->href='';
        }
        return $htmldom->save();
    }
    public function remove_link_relate($html){
        $vt1 = strpos($html,'&gt;&gt;');
        
        while($vt1){
	        $html = substr_replace($html,'',$vt1,8);
			$vt2 = strpos($html,'<a',$vt1 );
			$vt3 = strpos($html,'</a>',$vt2 );
			$html = substr_replace($html,'',$vt2,$vt3-$vt2);
			$vt1 = strpos($html,'&gt;&gt;');
		}
		return $html;
    }
    public function check_folder_download($year,$month){
        $y_path = IMG_DATA_PATH.'/'. $year;
        $m_path = $y_path .'/'. $month;		
        if(!is_dir($y_path)){
            mkdir($y_path);
        }
        if(!is_dir($m_path)){
            mkdir($m_path);
        }
        //return $m_path;
    }
    public function getstructure_bylink($link) {
        try{
            
            $sql = "SELECT * FROM download_structure WHERE ref_link = :ref_link";
            return $this->query($sql, array("ref_link" => $link));
        } catch (Exception $e) {
            VNLog::debug_var($this->log_name, $e->getMessage());
            return false;
        }
    }
    function to_slug($str) {
    	$str = html_entity_decode($str );
	    $str = trim(mb_strtolower($str));
	    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
	    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
	    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
	    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
	    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
	    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
	    $str = preg_replace('/(đ)/', 'd', $str);
	    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
	    $str = preg_replace('/([\s]+)/', '-', $str);
	    return $str;
	}
}
