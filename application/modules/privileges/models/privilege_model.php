<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privilege_model extends CI_Model {

	function grid_data()
	{
		$this->load->library('datatables');
		$this->datatables->select('id, level, privileges')
			->from('user_levels')
			->unset_column('id')
            ->edit_column('privileges', '<a href="'.site_url('privileges/show_list/$1').'" class="btn btn-xs btn-info">Edit Privileges <i class="fa fa-edit"></i></a>','id')
			->add_column('Actions', $this->get_buttons('$1'), 'id');
		return $this->datatables->generate();
	}

	function get_buttons($id)
	{
		$btn = '<p class="btn-group">';
		$btn .= '<button class="btn btn-xs btn-info" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
		$btn .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="return myConfirm(\''.site_url().'privileges/delete_data/'.$id.'\')" ><i class="fa fa-trash"></i></button>';
	    $btn .= '</p>';
	    return $btn;
	}

	function get_data($id)
	{
		$query = $this->db->get_where('user_levels', array('id'=>$id));
		return $query->row_array();
	}

	function save_data($data)
	{
		if($data['id'])
		{
			$this->db->update('user_levels', $data, array('id'=>$data['id']));
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been edited');
		}
		else
		{
			$this->db->insert('user_levels', $data);
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been added');
		}
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
    
    function save_data_privilege($save,$level)
	{
		$this->db->update('user_levels', $save, array('level' => $level));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been saved');
	}
    
	function check_privileges($level,$privilege){
		$query = $this->db->query("select level from user_levels where level = '$level' and privileges like '%$privilege%'");
		return $query->num_rows()>0? "true" : "false";
	}

	function delete_data($id)
	{
		$this->db->delete('user_levels', array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been deleted');
	}

}