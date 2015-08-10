<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Division_model extends CI_Model {

	function grid_data()
	{
		$this->load->library('datatables');
		$this->datatables->select('A.id, A.division, A.leader, count(B.id_division) as total_member,flag')
			->from('divisions A')
			->where('flag', 1)
			->or_where('flag', 2)
			->unset_column('A.id')
			->join('users B', 'A.id=B.id_division', 'left')
			->group_by('A.division')
			->add_column('Actions', $this->get_buttons('$1','$2'), 'A.id,flag')
			->unset_column('flag');
		return $this->datatables->generate();
	}
    
    function get_buttons($id,$status)
    {
        $btn = '<p class="btn-group">';
        $btn .= '<button class="btn btn-xs btn-info tooltips" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
		$btn .= '<button class="btn btn-xs btn-warning block" id="block'.$id.'" title="Block" data-id="'.$id.'" data-status="'.$status.'" onclick="block_data('.$id.')"><i class="fa fa-minus-circle"></i></button>';	
        $btn .= '<button class="btn btn-xs btn-danger tooltips" title="Delete" onclick="myConfirm(\''.site_url().'divisions/delete_data/'.$id.'\')" ><i class="fa fa-trash"></i></button>';
        $btn .= '</p>';
        return $btn;
    }
	
	function get_chart() 
	{
		$this->db->select('A.division, A.leader, A.color_label, count(B.id_division) as total_member')
			->from('divisions A')
			->join('users B', 'A.id=B.id_division', 'left')
			->group_by('A.leader');
		$result = $this->db->get();
		
		$color_array = array('#f00','#3f0','#c0c','#003','30f');
		foreach($result->result_array() as $key=>$row)
		{
			$chart_data[] = array (
				'value' => (int) $row['total_member'],
				//'color' => $color_array[$key],
				'color' => $row['color_label'],
				'highlight' => FALSE,
				'label'=> $row['division']
			);
		}
		return json_encode($chart_data);
	}
	
	function opt_member()
    {
        $result = $this->db->get_where('users_view', array('level'=>'Leader'));

        $data[null] = 'Select Leader';
        foreach($result->result_array() as $row)
        {
            $data[$row['name']] = $row['name'];
        }
        return $data;
    }

	function get_data($id)
	{
		$query = $this->db->get_where('divisions', array('id'=>$id));
		return $query->row_array();
	}

	function save_data($data)
	{
		if($data['id'])
		{
			$this->db->update('divisions', $data, array('id'=>$data['id']));
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been edited');
		}
		else
		{
			$this->db->insert('divisions', $data);
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been added');
		}
	}

	function delete_data($id)
	{
		$this->db->where('id',$id);
		$this->db->update('divisions', array('flag'=>0));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been deleted');
	}

	function block_data($id)
	{
		$this->db->update('divisions', array('flag'=>2), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been blocked');
	}
	
	function unblock_data($id)
	{
		$this->db->update('divisions', array('flag'=>1), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been activated');
	}
}