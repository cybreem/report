<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_assignment_model extends CI_Model {

	function grid_data()
	{
	    $division = $this->session->userdata('division')!="" ? $this->session->userdata('division') : '%';
		$this->load->library('datatables');
		$this->datatables->select('id, project_mng_code, job_code, job_name, classification, start_date, end_date, man_hour, start_date_actual, end_date_actual, status, man_hour_actual, plan_time, actual_time', FALSE)
			 ->from('assignment_view')
			 ->where('classification','LocalProject')
			 ->or_where('classification','OffshoreProject')
			 ->unset_column('id')
             ->unset_column('status')
             ->edit_column('plan_time','$1 Hour','plan_time')
             ->edit_column('actual_time','$1 Hour','actual_time')
			 ->add_column('Actions', $this->get_buttons('$1','$2'), 'id,status');
		return $this->datatables->generate();
	}
	
	function get_chart($job)
	{
	    $division = $this->session->userdata('division')!="" ? $this->session->userdata('division') : '%';
	    $job = $job!="" ? $job : 2;
		$this->load->library('datatables');
		$this->datatables->select('category, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual')
			 ->from('reports_view')
             ->where('id_job', $job)
             ->where('division LIKE ', '"'.$division.'"',false)
			 ->group_by('category');
		return $this->datatables->generate();
	}

	function get_buttons($id_job,$status)
	{
	    $btn = '<p>';
		$btn .= '<button class="btn btn-xs btn-info" title="View Assign Member" onclick="call_modal(\''.$id_job.'\')"><i class="fa fa-edit"></i> View Assign Member</button>';
	    $btn .= '</p>';
		$btn .= '<p>';
		$btn .= '<button class="btn btn-xs status" data-status="'.$status.'" title="Status"></button>';
	    $btn .= '</p>';
	    $btn .= '<p>';
		$btn .= '<button class="btn btn-xs btn-success" title="Edit Project Status" onclick="call_modal_status(\''.$id_job.'\')"><i class="fa fa-edit"></i> Edit Status</button>';
	    $btn .= '</p>';
	    return $btn;	
	}

	function get_data($id_job)
	{
	    $division = $this->session->userdata('division')!="" ? $this->session->userdata('division') : '%';
		$query = $this->db
			->select('id_user, code, name, sum(plan_time) as plan_time, sum(actual_time) as actual_time')
			->from('reports_view')
			->where('id_job',$id_job)
            ->where('division LIKE ', '"'.$division.'"',false)
			->group_by('name')
			->get();
		return $query->result_array();
	}
	
	function get_status($id_job)
	{
	    $query = $this->db->select('(projects.id) as id_project, (projects.id_job) as id, projects.start_date_actual, projects.end_date_actual, projects.status')
		->from('jobs')
		->join('projects','jobs.id=projects.id_job','left')
		->where('jobs.id',$id_job)
		->get();
        return $query->row_array();
	}
	
	function get_job_name($id_job)
	{
		$query = $this->db
			->select('project_mng_code')
			->from('jobs')
			->where('id',$id_job)
			->get();
		return $query->row();
	}

	function get_work($id_job,$id_user)
	{
		$query = $this->db
			 ->select('id, id_job, category, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual, (SUM(actual_time) - SUM(plan_time)) as notes')
			 ->from('reports_view')
			 ->where('id_user', $id_user)
			 ->where('id_job', $id_job)
			 ->group_by('category')
			 ->get();
		return $query->result_array();
	}

	function get_detail($id_user,$category,$id_job)
	{
		$query = $this->db
			 ->select('id, name, id_job, category, description, plan_time, actual_time, (actual_time - plan_time) as notes')
			 ->from('reports_view')
			 ->where('id_user', $id_user)
			 ->where('category like ', $category)
			 ->where('id_job ', $id_job)
			 ->get();
		return $query->result_array();
	}
    
    function show_list($id)
    {
       
        $this->db->select('level');
        $this->db->where('id',$id);
        $level = $this->db->get('user_levels');
        $privileges = array();
        foreach($level->result_array() as $row)
        {
            $privileges[$row['level']] = $this->controllerlist->getControllers();
        }       
        return $privileges;
    }
	
    function save_data_project($data)
    {
        if($data['id_job'])
        {
            $this->db->update('projects', $data, array('id'=>$data['id']));
            $this->session->set_flashdata('alert', 'Success! Data has been edited');
        }
        else
        {
            $this->db->insert('projects', $data);
            $this->session->set_flashdata('alert', 'Success! Data has been added');
        }
    }
}