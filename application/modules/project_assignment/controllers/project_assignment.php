<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_assignment extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		auth();
		$this->load->library('form_validation');
		$this->load->library('controllerlist');
		$this->load->model('project_assignment_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
		
	}

	function index()
	{
		redirect('project_assignment/project');
	}

	function project()
	{
		$item['source'] =  site_url('project_assignment/grid_data/');
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', '', TRUE)
		);
		$this->load->view('template', $data);
	}
	
	function grid_data($project = false)
	{
		echo $this->project_assignment_model->grid_data($project);
	}
	
	function get_chart($job = false)
	{
		echo $this->project_assignment_model->get_chart($job);
	}
	
	function call_form($code = FALSE)
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
        
        $list = $this->project_assignment_model->get_data($code);
		foreach($list as $set => $get){
			$id_user =  $get['id_user'];
			$data['work_'.$id_user] = (array) $this->project_assignment_model->get_work($code,$id_user);
		}
		$data['job'] =  $this->project_assignment_model->get_job_name($code);
		$data['id_job'] =  $code;
        $data['list'] = $list;
		$this->load->view('form', $data);
    }
	
	function call_detail($id_user = FALSE,$category = false,$id_job = false)
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
        
        $data['list'] = $this->project_assignment_model->get_detail($id_user,$category,$id_job);
		$data['id_job'] = $id_job;
		$this->load->view('form3', $data);
    }
	
	function call_status($code = FALSE)
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
        $data = (array) $this->project_assignment_model->get_status($code);
		$this->load->view('form_2', $data);
    }
    
	function save_status()
	{
			$project = array(
                'id' => $this->input->post('id_project'),
                'id_job' => $this->input->post('id'),
                'start_date_actual' => $this->input->post('start_date_actual'),
                'end_date_actual' => $this->input->post('end_date_actual'),
                'status' => $this->input->post('status'),
            );
            $this->project_assignment_model->save_data_project($project);
            $json = array('status'=> 1, 'alert'=>'Data has been added');
	}
    function show_list($id = false)
    {
		$generated = $this->project_assignment_model->show_list($id);
		$arr_check = array();
		foreach($generated as $key => $set){
			unset($generated[$key]['auth']);
			unset($generated[$key]['dashboard']);
			foreach($set as $it => $val){
				unset($generated[$key][$it][0]);
				$level = $key;
				foreach($val as $field){
					$privilege = $it."_".$field;
					$check = $this->project_assignment_model->check_privileges($level,$privilege);
					$check=="true"? $arr_check[] = $privilege : false ;
				}
			}
		}
		$item['list_check'] = $arr_check;
		$item['generated'] = $generated;
		$data['content'] = $this->load->view('privileges', $item, TRUE);
		$this->load->view('template', $data);
    }
	
}