<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	function grid_data()
	{
		$division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%%';
		$this->load->library('datatables');
		$this->datatables->select('id, nip, name, level, status')
			->from('users_view')
			->where('division LIKE ', '"'.$division.'"',false)
			->unset_column('id')
			->add_column('Actions', $this->get_buttons('$1','$2'), 'id,status');
		return $this->datatables->generate();
	}

	function get_buttons($id,$status)
	{  
		$btn = '<p class="btn-group">';
		$btn .= '<button class="btn btn-xs btn-info" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
		$btn .= '<button class="btn btn-xs btn-warning block" id="block'.$id.'" title="Block" data-id="'.$id.'" onclick="block_user('.$id.')"><i class="fa fa-minus-circle"></i></button>';		
		$btn .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="return myConfirm(\''.site_url().'members/delete_data/'.$id.'\')" ><i class="fa fa-trash"></i></button>';
	    $btn .= '</p>'; 
	    return $btn;
	}
	
	function opt_division()
    {
        $result = $this->db->get('divisions');

        $data[null] = 'Select Division';
        foreach($result->result_array() as $row)
        {
            $data[$row['id']] = $row['division'];
        }
        return $data;
    }
	
	function opt_level()
    {
        $result = $this->db->get('user_levels');

        $data[null] = 'Select Level';
        foreach($result->result_array() as $row)
        {
            $data[$row['id']] = $row['level'];
        }
        return $data;
    }

	function get_data($id)
	{
		$query = $this->db->get_where('members', array('id'=>$id));
		return $query->row_array();
	}

	function save_data($data)
	{
		if($data['id'])
		{
			$this->db->update('members', $data, array('id'=>$data['id']));
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been edited');
		}
		else
		{
			$this->db->insert('members', $data);
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been added');
		}
	}

	function block_user($id)
	{
		$this->db->update('users', array('status'=>0), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! User has been banned');
	}
	
	function unblock_user($id)
	{
		$this->db->update('users', array('status'=>1), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! User has been activated');
	}

}