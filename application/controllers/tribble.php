<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tribbles
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */
 
class Tribble extends CI_Controller {
  
  public function __construct()
  {
      parent::__construct();
      //$this->output->enable_profiler(TRUE);
  }

	public function index()
	{
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';    

    $this->load->model('Tribbles_model','trModel');
    $tribble_list = $this->trModel->getNewer();
    
    //print_r($tribble_list);
    
    //foreach($tribble_list as $tribble){
//      echo "<pre>";
//      print_r($tribble);
//      echo "</pre>";
//    }

    $data['tribbles'] = $tribble_list;
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
    $this->load->view('common/header.php',$data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data);        
	}    
   
  public function buzzing()
	{
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';    

    $this->load->model('Tribbles_model','trModel');
    $tribble_list = $this->trModel->getBuzzing();
    
    //print_r($tribble_list);
    
    //foreach($tribble_list as $tribble){
//      echo "<pre>";
//      print_r($tribble);
//      echo "</pre>";
//    }

    $data['tribbles'] = $tribble_list;
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
    $this->load->view('common/header.php',$data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data); 
	}  
  
  public function loved()
	{
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';    

    $this->load->model('Tribbles_model','trModel');
    $tribble_list = $this->trModel->getLoved();
    
    //print_r($tribble_list);
    
    //foreach($tribble_list as $tribble){
//      echo "<pre>";
//      print_r($tribble);
//      echo "</pre>";
//    }

    $data['tribbles'] = $tribble_list;
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
    $this->load->view('common/header.php',$data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data); 
	}
  
  public function view($tribble){       
    
    $this->load->model('Tribbles_model','trModel');
    $tribbleData = $this->trModel->getTribble($tribble);
    $replyData = $this->trModel->getReplies($tribble);
        
    $data['tribble'] = $tribbleData[0];
    $data['replies'] = $replyData;
    
    $data['title'] = 'Tribble - ' . $data['tribble']->title;
    $data['meta_description'] = $data['tribble']->title;
    $data['meta_keywords'] = $data['tribble']->tags;
    
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
    $this->load->view('common/header.php',$data);
		$this->load->view('tribble/view.php',$data);
    if($this->session->userdata('unique')){    
      $this->load->view('tribble/replyform.php',$data);
    }
    $this->load->view('common/page_end.php',$data);         
  }
  
  function reply($tribble){
    if(!$this->session->userdata('unique')){
      redirect('auth/login');
    } else {
      redirect('/tribbles/view/'.$tribble);
    }
  }
  
  public function upload(){
          
   if(!$this->session->userdata('unique')){
    redirect('/auth/login/'.str_replace('/','-',uri_string()));
   } else {
    // check if the form was posted          
    if(isset($_POST['createTribble'])){
      
      // get the uid from the session data and hash it to be used as the user upload folder name       
      $user_hash = do_hash($this->session->userdata('unique'));
      
      // load the tribble model
      $this->load->model('Tribbles_model','trModel');           
        
      // set the upload configuration
      $ulConfig['upload_path'] = './data/'.$user_hash.'/';
  		$ulConfig['allowed_types'] = 'jpg|png';
  		$ulConfig['max_width']  = '400';
  		$ulConfig['max_height']  = '300';
      
      // load the file uploading lib and initialize
  		$this->load->library('upload', $ulConfig);
      $this->upload->initialize($ulConfig);
      
      // check if upload was successful and react        
      if (!$this->upload->do_upload('image')){                  
  			$error = array('error' => $this->upload->display_errors());
  		} else {
  			$data = array('upload_data' => $this->upload->data());       
        // set the data to write in db;
        $imgdata = array('image_path'=>substr($ulConfig['upload_path'].$data['upload_data']['file_name'],1),'image_palette'=>json_encode(getImageColorPalette($data['upload_data']['full_path'])));
        
        $config['image_library'] = 'gd2'; 
        $config['source_image'] = $ulConfig['upload_path'].$data['upload_data']['file_name'];	
        $config['create_thumb'] = TRUE; 
        $config['maintain_ratio'] = TRUE; 
        $config['width'] = 200; 
        $config['height'] = 150; 
        
        $this->load->library('image_lib', $config); 
        $this->image_lib->resize();
        
        if(!$result = $this->trModel->createNewTribble($imgdata)){
          $data['error'] = $result->error;  
        } else {
          $data['success'] = "YAY!";
        }        
		  }
                                     
    }
    
    $data['title'] = 'Tribble - Upload';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';
    
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
    $this->load->view('common/header.php',$data);
		$this->load->view('tribble/upload.php',$data);
    $this->load->view('common/page_end.php',$data);        
       
   }
  }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */