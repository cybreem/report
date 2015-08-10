<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job_code_model extends CI_Model {

    function grid_data()
    {
        $this->load->library('datatables');
        $this->datatables->where('a.flag !=', 0)
			->select('a.id, b.classification, a.job_name, a.job_code, a.project_mng_code, a.flag')
            ->from('jobs a')
            ->order_by('a.id','desc')
            //->where('a.flag', 1)
            ->unset_column('a.id')
            ->join('classifications b', 'a.id_classification=b.id')
            ->add_column('Actions', $this->get_buttons('$1','$2'), 'a.id, a.flag');
        return $this->datatables->generate();
    }

    function get_buttons($id,$flag)
    {
        $btn = '<p class="btn-group">';
        $btn .= '<button class="btn btn-xs btn-info" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
		$btn .= '<button class="btn btn-xs btn-warning block" id="block'.$id.'" title="Block" data-id="'.$id.'" onclick="block_job_code('.$id.')"><i class="fa fa-minus-circle"></i></button>';	
        $btn .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="myConfirm(\''.site_url().'job_codes/delete_data/'.$id.'\')"><i class="fa fa-trash"></i></button>';
        $btn .= '</p>';
        return $btn;
    }
    
    function get_data($id)
    {
        $query = $this->db->select('jobs.id, id_classification, job_name,job_code, man_hour, project_mng_code, flag, (projects.id) as id_project, id_job, projects.start_date, projects.end_date, projects.status')->from('jobs')->join('projects','jobs.id=projects.id_job','left')->where('jobs.id',$id)->get();
        return $query->row_array();
    }
    
    function opt_classification()
    {
        $result = $this->db->where('flag' , 1)->get('classifications');
        
        $data[''] = ' -- Work Classification --';
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['classification'];
        }
        return $data;
    }

    function save_data($data)
    {
        if($data['id'])
        {
            $this->db->update('jobs', $data, array('id'=>$data['id']));
            $this->session->set_flashdata('alert', 'Success! Data has been edited');
			return $data['id'];
        }
        else
        {
            $this->db->insert('jobs', $data);
            $this->session->set_flashdata('alert', 'Success! Data has been added');
			return $this->db->insert_id();
        }
    }

    function save_data_project($data)
    {
        if($data['id'])
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
	
	function block_job_code($id)
	{
		$this->db->update('jobs', array('flag'=>2), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! User has been banned');
	}
	
	function unblock_job_code($id)
	{
		$this->db->update('jobs', array('flag'=>1), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! User has been activated');
	}

    function delete_data($id)
    {
        $this->db->update('jobs', array('flag'=>0), array('id'=>$id));
        $this->session->set_flashdata('alert', 'Success! Data has been deleted');
    }

}