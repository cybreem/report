<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_request_model extends CI_Model {


	function grid_data(){
		$this->load->library('datatables');
		$div = $this->get_user_division();
		$a = $this->datatables->select('ur.id,COALESCE((SELECT (id) FROM user_request LIMIT 0),"") as a, ats.status, start_time, end_time,  leader_status, manager_status,ur.id as uid,(SELECT name FROM users WHERE users.id = `leader_by`) lb,(SELECT name FROM users WHERE users.id = `manager_by`) mb,',FALSE)
		->from('user_request ur')
		->join('users u',  'ur.id_user = u.id')
		->join('divisions d',  'u.id_division = d.id')
		->join('attendance_status ats',  'ur.id_status = ats.id_status')
		->order_by('ur.id DESC')
		->where('id_user',$this->session->userdata('id'))
		->unset_column('ur.id');
		if($this->session->userdata('level')=='Leader'){
			$a->where('u.id_division',$div[0]['id_division']);
		}
		if($this->session->userdata('level')=='Admin'){
			$a->where('leader_status','1');
		}
		return $a->generate();
	}
	
	function member_request(){
		$this->load->library('datatables');
		$div = $this->get_user_division();
		$a = $this->datatables->select('ur.id,COALESCE((SELECT (id) FROM user_request LIMIT 0),"") as a, u.name, d.division, ats.status, start_time, end_time,  leader_status, manager_status,ur.id as uid,(SELECT name FROM users WHERE users.id = `leader_by`) lb,(SELECT name FROM users WHERE users.id = `manager_by`) mb,',FALSE)
		->from('user_request ur')
		->join('users u',  'ur.id_user = u.id')
		->join('divisions d',  'u.id_division = d.id')
		->join('attendance_status ats',  'ur.id_status = ats.id_status')
		->order_by('ur.id DESC')
		->where('(u.id_user_level=3||u.id_user_level=4)')
		->unset_column('ur.id');
		if($this->session->userdata('level')=='Leader'){
			$a->where('u.id_division',$div[0]['id_division']);
		}
		if($this->session->userdata('level')=='Admin'){
			$a->where('leader_status','1');
		}
		return $a->generate();
	}
	function get_sign($status)
	{
		
		$btn = '<p class="btn-group">';
		if($status){
			$btn .= 'a';
		}
	    $btn .= '</p>';
		return $btn ;
	
	}
	function get_user_division(){
		$query = $this->db->query('SELECT id_division from users where id='.$this->session->userdata('id'));
		return $query->result_array();
	}
	function get_user_status($status, $addition,$parent=null){
		$str = '';
		if($status == 'parent'){
			$str = 'WHERE status_parent IS NULL';
		}else if($status == 'child'){
			
			$str = 'WHERE status_parent = '.$parent;
		}
		$a = $this->db->query('SELECT * FROM attendance_status '.$str.' '. $addition);
		// var_dump($this->db->last_query());
		return $a->result_array();
	}
		
	function createRequest($post,$rt){
		$a = $this->db->query('INSERT into user_request(id_status,id_user,start_time, end_time, reason) '.
		'VALUES("'.
		$post['status'].'","'.
		$this->session->userdata('id').'","'.
		date("Y-m-d H:i:s", $post['start_time']).'","'.
		date("Y-m-d H:i:s", $post['end_time']).'","'.
		$post['reason'].'")');
		if($rt){
			if($a){
				$json = array('status'=> 1, 'alert'=>'Your Request has been sent');;
				echo json_encode($json);
			}else{
				$json = array('status'=> 0, 'alert'=>'Please fill the blank form');;
				echo json_encode($json);
			}
		}else{
			if($a){
				return $this->db->insert_id();
			}else{
				return false;
			}
		}
	}
	
	function updateRequest($post, $id){
		$data = array(
               // 'id_status' => $post['status'],
               'start_time' => date("Y-m-d H:i:s", $post['start_time']),
               'end_time' => date("Y-m-d H:i:s", $post['end_time']),
			   'reason'=> $post['reason'],
            );

		$this->db->where('id', $id);
		$a = $this->db->update('user_request', $data); 
		
		if($a){
			$json = array('status'=> 1, 'alert'=>'Your Request has been updated');
			echo json_encode($json);
		}else{
			$json = array('status'=> 0, 'alert'=>'Please fill the blank form');
			echo json_encode($json);
		}
	}
	
	function statusRequest($id,$data){
		$user_id = $this->session->userdata('id');
		$id_status = $this->input->post('id_status');
		if($this->input->post('id_status')!=10){
			if($this->session->userdata('level')=='Admin'){
				
				if($this->input->post('id_status') == 4){
					if($this->input->post('decision')==1){
						$id_status = 9;
					}else if($this->input->post('decision')==2){
						$id_status = $this->input->post('id_status');
					}
				}else if($this->input->post('id_status')==5||$this->input->post('id_status')==6||$this->input->post('id_status')==7){
					if($this->input->post('decision')==1){
						$id_status = $this->input->post('id_status');
					}else if($this->input->post('decision')==2){
						$id_status = 8;
					}
				}
				/*=======================================
				SET ATTENDANCE
				=======================================*/
				$dataRequest = $this->get_request_detail($id);
				$firstDate = $dataRequest[0]['start_time'];
				$lastDate = $dataRequest[0]['end_time'];
				$range = $this->createDaterange($firstDate,$lastDate);
				foreach($range as $key => $value){
					$check = $this->db->query('SELECT * FROM attendance WHERE id_user = '.$dataRequest[0]['id_user'].' AND date = "'.$value.'"');
					
					if($check->num_rows()>0){
						$dataUserRequest = array(
							'date'		=>	$value,
							'id_user'	=>	$dataRequest[0]['id_user'],
							'id_status'	=>	$id_status,
							'id_request'=>	$id
						);
						$this->db->where('id_user',$dataRequest[0]['id_user']);
						$this->db->where('date',$value);
						$a = $this->db->update('attendance',$dataUserRequest);
					}else{
						$dataUserRequest = array(
							'date'		=>	$value,
							'id_user'	=>	$dataRequest[0]['id_user'],
							'id_status'	=>	$id_status,
							'id_request'=>	$id
						);
						
						$a = $this->db->insert('attendance',$dataUserRequest);
					}
					if(!$a){
						$json = array('status'=> 0, 'alert'=>'Attendance Cannot be set');
						echo json_encode($json);
						exit();
					}
				}
			}
		}
		
		$data = array(
               $data['field_status'] => $this->input->post('decision'),
               $data['field_by'] => $user_id,
            );
		$data = array_merge($data,$_POST);
		unset($data['decision']);
		$this->db->where('id', $id);
		$a = $this->db->update('user_request', $data); 
		
		
		
		if($a){
			$json = array('status'=> 1, 'alert'=>'Your Request has been updated');;
			echo json_encode($json);
		}else{
			$json = array('status'=> 0, 'alert'=>'Please fill the blank form');;
			echo json_encode($json);
		}
	}
	
	function createDaterange($strDateFrom, $strDateTo){
		$range = array();
		$dateFrom = mktime(1,0,0,substr($strDateFrom,5,2),substr($strDateFrom,8,2),substr($strDateFrom,0,4));
		$dateTo = mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));
		
		if($dateTo>=$dateFrom){
			$_dt = strtotime($dateFrom);
			$_day = date('D',$_dt);
			array_push($range,date('Y-m-d',$dateFrom)); //first entry
			while($dateFrom<$dateTo)
			{
				$dateFrom+=86400; //add 24 hours
				$dt = strtotime($dateFrom);
				$day = date('D',$dt);
				array_push($range,date('Y-m-d',$dateFrom)); 
			}
		}
		return $range;
	}
	
	function get_request_detail($id){
		
		$a = $this->db->query('SELECT *,ats.status_parent as asp, ur.id_status as ids,(select status FROM attendance_status WHERE id_status = asp ) as urs, (select IF( asp IS NULL, ids, asp) FROM attendance_status WHERE id_status = ids ) as status_parent,(select IF( status_parent IS NULL, status, urs) FROM attendance_status WHERE id_status = ids ) as status_parent_name, (select IF( asp IS NULL, NULL, id_status) FROM attendance_status WHERE id_status = ids ) as status_child, (select IF( status_parent IS NULL, NULL, status) FROM attendance_status WHERE id_status = ids ) as status_child_name, ur.id as urid FROM user_request ur JOIN users u ON ur.id_user = u.id JOIN divisions d ON u.id_division = d.id JOIN attendance_status ats ON ur.id_status = ats.id_status WHERE ur.id = '.$id);
		// var_dump($this->db->last_query());
		return $a->result_array();
	}
	////
	


}