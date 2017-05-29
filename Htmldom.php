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
    public function split_content($total_section){
    	$element = $this->sdom->find('//div[@id="content"]',0);
		for($i = 1;$i<=$total_section;$i++){
			if($element != NULL){
				$element->innertext .= '<div class="row" id="section_'.$i.'"></div>';
			}
		}
		$this->sdom->save();
	}
	public function split_column($object_id,$total_column){
		$element = $this->sdom->find('//div[@id="'.$object_id.'"]',0);
		$colum_class = 12/$total_column;
		for($i = 1;$i<=$total_column;$i++){
			if($element != NULL){
				$element->innertext .= '<div class="col-md-'.$colum_class.'" id="'.$object_id.'_col_'.$i.'"> column </div>';
			}
		}
		$this->sdom->save();
	}
	
    public function set_html($obj_id, $str_html){
		$element = $this->sdom->find('//div[@id="'.$obj_id.'"]',0);
        if($element != NULL){
			return $element->innertext = $str_html;
		}
		$this->sdom->save();
	}
	public function add_cssfile($file_name){
		$element = $this->sdom->find('//head',0);
        if($element != NULL){
			$element->innertext .= '<link href="'.$file_name.'" rel="stylesheet" type="text/css">';
		}
		$this->sdom->save();
	}
	public function add_jsfile($file_name){
		$element = $this->sdom->find('//head',0);
        if($element != NULL){			
			$element->innertext .= '<script type="text/javascript" src="'.$file_name.'"></script>';
		}
		$this->sdom->save();
	}
	public function save($file_name=''){
		if($file_name==''){
			$this->sdom->save($this->filename);
		}else{
			$this->sdom->save($file_name);
		}
	}
    
    public function add_slides($obj_id){
		$html ='<h2>Carousel Example</h2>  
				  <div id="myCarousel" class="carousel <!--slide-->" data-ride="carousel" data-interval="2000">
				    <!-- Indicators -->
				    <ol class="carousel-indicators">
				      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				      <li data-target="#myCarousel" data-slide-to="1"></li>
				      <li data-target="#myCarousel" data-slide-to="2"></li>
				    </ol>

				    <!-- Wrapper for slides -->
				    <div class="carousel-inner">
				      <div class="item active">
				        <img src="image/11.png" alt="Los Angeles" style="width:100%;">
				      </div>

				      <div class="item">
				        <img src="image/33.png" alt="Chicago" style="width:100%;">
				      </div>
				    
				      <div class="item">
				        <img src="image/22.png" alt="New york" style="width:100%;">
				      </div>
				    </div>

				    <!-- Left and right controls -->
				    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
				      <span class="glyphicon glyphicon-chevron-left"></span>
				      <span class="sr-only">Previous</span>
				    </a>
				    <a class="right carousel-control" href="#myCarousel" data-slide="next">
				      <span class="glyphicon glyphicon-chevron-right"></span>
				      <span class="sr-only">Next</span>
				    </a>
				  </div>';
		return $this->set_html($obj_id,$html);
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
