<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_classification_model extends CI_Model {

	function grid_data()
	{
		$this->load->library('datatables');
		$this->datatables->select('id,flag,classification')
			->from('classifications')
            ->where('flag', 1)
			->or_where('flag', 2)
			->unset_column('id')
			->unset_column('flag')
			->add_column('Actions', $this->get_buttons('$1','$2'), 'id,flag');
		return $this->datatables->generate();
	}

	function get_buttons($id,$status)
	{
		$btn = '<p class="btn-group">';
		$btn .= '<button class="btn btn-xs btn-info tooltips" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
		$btn .= '<button class="btn btn-xs btn-warning block" id="block'.$id.'" title="Block" data-id="'.$id.'" data-status="'.$status.'" onclick="block_data('.$id.')"><i class="fa fa-minus-circle"></i></button>';	
		$btn .= '<button class="btn btn-xs btn-danger tooltips" title="Delete" onclick="myConfirm(\''.site_url().'work_classifications/delete_data/'.$id.'\')"><i class="fa fa-trash"></i></button>';
	    $btn .= '</p>';
	    return $btn;
	}

	function get_data($id)
	{
		$query = $this->db->get_where('classifications', array('id'=>$id));
		return $query->row_array();
	}

	function save_data($data)
	{
		if($data['id'])
		{
			$this->db->update('classifications', $data, array('id'=>$data['id']));
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been edited');
		}
		else
		{
			$this->db->insert('classifications', $data);
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been added');
		}
	}

	function delete_data($id)
	{
		$this->db->update('classifications', array('flag'=>0), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been deleted');
	}

	function block_data($id)
	{
		$this->db->update('classifications', array('flag'=>2), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been blocked');
	}
	
	function unblock_data($id)
	{
		$this->db->update('classifications', array('flag'=>1), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been activated');
	}

}