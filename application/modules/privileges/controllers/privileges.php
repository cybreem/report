<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privileges extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		auth();
		$this->load->library('form_validation');
		$this->load->library('controllerlist');
		$this->load->model('privilege_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
		
	}

	function index()
	{
		$item['source'] =  site_url('privileges/grid_data');
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', '', TRUE)
		);
		$this->load->view('template', $data);
	}
    
	function grid_data()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->privilege_model->grid_data();
	}
	
	function call_form($id = FALSE)
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
        
        if($id != 0)
        {
            $data = (array) $this->privilege_model->get_data($id);
        }
        else
        {
            $data = array(
                'id' => FALSE,
                'level' => ''
            );
		}
		$this->load->view('form', $data);
    }
    
	function submit_data()
    {
       $this->form_validation->set_rules('level', 'level', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'level' => $this->input->post('level')
            );
			$json = array('status'=> 1, 'alert'=>'Data has been added');
            $this->privilege_model->save_data($save);            
        }
        echo json_encode($json);
    }

    function show_list($id = false)
    {
		$generated = $this->privilege_model->show_list($id);
		$arr_check = array();
		foreach($generated as $key => $set){
			unset($generated[$key]['auth']);
			unset($generated[$key]['dashboard']);
			foreach($set as $it => $val){
				unset($generated[$key][$it][0]);
				$level = $key;
				foreach($val as $field){
					$privilege = $it."_".$field;
					$check = $this->privilege_model->check_privileges($level,$privilege);
					$check=="true"? $arr_check[] = $privilege : false ;
				}
			}
		}
		$item['list_check'] = $arr_check;
		$item['generated'] = $generated;
		$data['content'] = $this->load->view('privileges', $item, TRUE);
		$this->load->view('template', $data);
    }
	
	function save_privilege()
    {
		$form_level = $this->input->post('form_level');
		$get = implode('|', $this->input->post($form_level));
		$save = array('privileges' => $get);
		$this->privilege_model->save_data_privilege($save, $form_level); 
		redirect('privileges');  
    }

	function delete_data($id)
	{
		$this->privilege_model->delete_data($id);
		redirect('privileges');
	}

}