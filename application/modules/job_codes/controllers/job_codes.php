<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job_codes extends CI_Controller {
    
    function __construct()
    {
        parent:: __construct();
        $this->load->helper('form');
		$this->load->helper('auth');
		auth();
        $this->load->library('form_validation');
        $this->load->model('job_code_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
    }

    function index()
    {
        $item['source'] =  site_url('job_codes/grid_data');
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
        echo $this->job_code_model->grid_data();
    }
    
    function call_form($id = FALSE)
    {
        if($id)
        {
            $data = (array) $this->job_code_model->get_data($id);
        }
        else
        {
            $data = array(
                'id' => FALSE,
                'id_classification' => '',
                'job_name'=> '',
                'job_code' => '',
                'project_mng_code'=>'',
                'id_project'=>'',
                'start_date'=>'',
                'end_date'=>'',
                'man_hour'=>'',
                'status'=>''
            );
        }
        $data['opt_classification'] = $this->job_code_model->opt_classification();
        $this->load->view('form', $data);
    }
    
    function submit_data()
    {
       $this->form_validation->set_rules('classification', 'classification', 'required');
       $this->form_validation->set_rules('job_name', 'job name', 'required');
       $this->form_validation->set_rules('job_code', 'job code', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'id_classification' => $this->input->post('classification'),
                'job_name' => $this->input->post('job_name'),
                'job_code' => $this->input->post('job_code'),
                'project_mng_code' => $this->input->post('project_mng_code'),
                'flag' => 1
            );
            $id_job = $this->job_code_model->save_data($save);
			
			$project = array(
                'id' => $this->input->post('id_project'),
                'id_job' => $id_job,
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'status' => $this->input->post('status'),
                'man_hour' => $this->input->post('man_hour')
            );
            $this->job_code_model->save_data_project($project);
            $json = array('status'=> 1, 'alert'=>'Data has been added');
        }
        echo json_encode($json);
    }
	
	function block_job_code($id)
	{
		$this->job_code_model->block_job_code($id);
		redirect('job_codes');
	}
	
    function unblock_job_code($id)
	{
		$this->job_code_model->unblock_job_code($id);
		redirect('job_codes');
	}
    
    function delete_data($id)
    {
        $this->job_code_model->delete_data($id);
        redirect('job_codes');
    }

}