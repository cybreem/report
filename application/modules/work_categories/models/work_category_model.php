<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_category_model extends CI_Model {

	function grid_data()
	{
		$this->load->library('datatables');
		$this->datatables->select('id, category, flag')
			->from('categories')
            ->where('flag', 1)
			->or_where('flag', 2)
			->unset_column('id')
			// ->unset_column('flag')
			->add_column('Actions', $this->get_buttons('$1','$2'), 'id,flag');
		return $this->datatables->generate();
	}

	function get_buttons($id,$flag)
	{
		$btn = '<p class="btn-group">';
		$btn .= '<button class="btn btn-xs btn-info tooltips" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
		$btn .= '<button class="btn btn-xs btn-warning block" id="block'.$id.'" title="Block" data-status="'.$flag.'" data-id="'.$id.'" onclick="block_category('.$id.')"><i class="fa fa-minus-circle"></i></button>';	
		$btn .= '<button class="btn btn-xs btn-danger tooltips" title="Delete" onclick="myConfirm(\''.site_url().'work_categories/delete_data/'.$id.'\')"><i class="fa fa-trash"></i></button>';
	    $btn .= '</p>';
	    return $btn;
	}

	function get_data($id)
	{
		$query = $this->db->get_where('categories', array('id'=>$id));
		return $query->row_array();
	}

	function save_data($data)
	{
		if($data['id'])
		{
			$this->db->update('categories', $data, array('id'=>$data['id']));
			$this->session->set_flashdata('alert', 'Success! Data has been edited');
		}
		else
		{
			$this->db->insert('categories', $data);
			$this->session->set_flashdata('alert', 'Success! Data has been added');
		}
	}

	function block_category($id)
	{
		$this->db->update('categories', array('flag'=>2), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! User has been banned');
	}
	
	function unblock_category($id)
	{
		$this->db->update('categories', array('flag'=>1), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! User has been activated');
	}

	function delete_data($id)
	{
		$this->db->update('categories', array('flag'=>0), array('id'=>$id));
		$this->session->set_flashdata('alert', 'Success! Data has been deleted');
	}

}