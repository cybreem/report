<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_model extends CI_Model {

	function grid_data()
	{

		$division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%%';		
		$this->load->library('datatables');
		$this->datatables->select('id, nip, name, email, division')
			->from('users_view')
			->where('division LIKE ', '"'.$division.'"',false)
			->unset_column('id')
			->add_column('Actions', $this->get_buttons('$1'), 'id')
            ->where('division LIKE ', '"'.$division.'"',false);
		return $this->datatables->generate();
	}

	function get_buttons($id)
	{
		$btn = '<p class="btn-group">';
		$btn .= '<button class="btn btn-xs btn-info tooltips" title="Edit" onclick="call_modal('.$id.')"><i class="fa fa-edit"></i></button>';
		$btn .= '<button class="btn btn-xs btn-danger tooltips" title="Delete" onclick="return myConfirm(\''.site_url().'members/delete_data/'.$id.'\')" ><i class="fa fa-trash"></i></button>';
	    $btn .= '</p>';
	    return $btn;
	}
    
    function get_data($id)
    {
      
		$query = $this->db->query('SELECT *,users.id as idu from users JOIN divisions ON divisions.id = users.id_division JOIN user_levels ON user_levels.id = users.id_user_level WHERE users.id='.$id);
        $a =  $query->result_array();
		return $a[0];
    }
    	
	

	function save_data($data)
	{
		if($data['id'])
		{
			$a = $this->db->update('users', $data, array('id'=>$data['id']));
			if($a){
				return array('status'=> 1, 'alert'=>'Data has been edited');
			}
			else{
				return array('status'=> 0, 'alert'=>validation_errors());
			}
		}
		else
		{
			$a = $this->db->insert('users', $data);
			if($a){
				return array('status'=> 1, 'alert'=>'Data has been added');
			}
			else{
				return array('status'=> 0, 'alert'=>validation_errors());
			}
		}
	}
    
    
    function insert_list($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++)
        {
            $data = array(
                'nip' => $dataarray[$i]['nip'],
                'name' => $dataarray[$i]['name'],
                'email' => $dataarray[$i]['email'],
                'avaya' => $dataarray[$i]['avaya'],
                'phone' => $dataarray[$i]['phone'],
                'address' => $dataarray[$i]['address'],
                'password' => md5('1sampai9'),
                'id_division' => $this->session->userdata('id'),
                'id_user_level' => 4,
                'status' => 1
            );
            $this->db->insert('users', $data);
        }
    }
    
    function update_list($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++)
        {
            $data = array(
                'nip' => $dataarray[$i]['nip'],
                'name' => $dataarray[$i]['name'],
                'email' => $dataarray[$i]['email'],
                'avaya' => $dataarray[$i]['avaya'],
                'phone' => $dataarray[$i]['phone'],
                'address' => $dataarray[$i]['address'],
                'password' => md5('1sampai9'),
                'id_division' => $this->session->userdata('id'),
                'id_user_level' => 4,
                'status' => 1
            );
           return $this->db->update('users', $data, array('id'=>$dataarray[$i]['id']));   
        }
    }

    function search_duplicate($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++)
        {
            $search = array(
                'nip'=>$dataarray[$i]['nip'],
                'name'=>$dataarray[$i]['name']
            );
        }
                
        $data = array();
        $this->db->limit(1);
        $Q = $this->db->get_where('users', $search);
        if($Q->num_rows() > 0)
        {
            $data = $Q->row_array();
        }
        $Q->free_result();
        return $data;
    }
    
    function save_password($data)
    {
        $this->db->update('users', $data, array('id'=>$data['id']));
        $this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been edited');
    }

	function delete_data($id)
	{
		$this->db->update('users', array('status'=>2), array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been deleted');
	}
	function get_division($text){
		$query = $this->db->query("SELECT id FROM divisions WHERE division = '$text'");
		$result = $query->result_array();
		return $result;
	}
	function upload_data($data){
		
		foreach($data as $key => $row){
			$id_division = $this->get_division($row[6]);
			$array = array('nip' => $row[0], 'name' => $row[1], 'email' => $row[2],'avaya' => $row[3],'phone' => $row[4], 'address' => $row[5],'id_division'=>$id_division[0]['id'],'id_user_level'=>'4','password'=>md5('12345'),'status'=>1,'image'=>'avatar.png');
			$check_nip = $this->check_nip($row[0]);
			
			if($check_nip>0){
				if(!$this->db->update('users',$array,array('nip'=>$row[0]))){
					return array('status'=>0, 'alert'=>$this->db->_error_message());
				}
			}else{
				$this->db->set($array);
				if(!$this->db->insert('users')){
					return array('status'=>0, 'alert'=>$this->db->_error_message());
				}
			}
		}
		return array('status'=>1, 'alert'=>'Success');
	}
	
	function check_nip($nip){
		$query = $this->db->query("SELECT id from users WHERE nip = $nip");
		return $query->num_rows();
	}
	
	function update_avatar($data){
		$id = $this->session->userdata('id');
		$query = $this->db->query("UPDATE users SET image='$data[file_name]' WHERE id = $id");
		if($query){
			//echo $query;
			return array('status'=>1, 'alert'=>'Success','file'=>$data['file_name']);
		}
		else{
			return array('status'=>0, 'alert'=>"Uploading fail");
		}
		
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
}