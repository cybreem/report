<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday_model extends CI_Model {

	function grid_data()
    {
		$this->load->library('datatables');
		$this->datatables->select('id, date, holiday_desc' )
			 ->from('holiday')
			 ->unset_column('id')
			 ->add_column('Actions', $this->get_buttons('$1', '2'), 'id');
		return $this->datatables->generate();
}

    function get_buttons($id)
    {
        $btn = '<p class="btn-group">';
        $btn .= '<button class="btn btn-xs btn-info" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
        $btn .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="myConfirm(\''.site_url().'holiday/delete_data/'.$id.'\')"><i class="fa fa-trash"></i></button>';
        $btn .= '</p>';
        return $btn;
    }

	function get_data($id)
    {
        $query = $this->db->get_where('holiday', array('id'=>$id));
        return $query->row_array();
    }
	
	function save_data($data)
    {
        if($data['id'])
        {
            $this->db->update('holiday', $data, array('id'=>$data['id']));
            $this->session->set_flashdata('alert', 'Success! Holiday date has been edited');
        }
        else
        {
            $this->db->insert('holiday', $data);
            $this->session->set_flashdata('alert', 'Success! Holiday date has been added');
        }
    }

	function delete_data($id)
    {
        $this->db->delete('holiday', array('id'=>$id));
        $this->session->set_flashdata('alert', 'Success! Holiday date has been deleted');
    }
}