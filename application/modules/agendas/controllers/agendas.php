<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agendas extends CI_Controller {
    
    function __Construct()
    {
        parent::__Construct();        
        $this->load->model('agenda_model');
    }
    
    function index()
    {
        $data = array(
            'content'=>$this->load->view('content', '', TRUE),
            'script'=>$this->load->view('content_js', '', TRUE)
        );
        $this->load->view('template', $data);
    }
    
    function project()
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
        $member  = $this->agenda_model->get_member();
        $project = $this->agenda_model->get_project();
        $data = array('member' => $member, 'project' =>$project);
        echo json_encode($data);
    }

}