<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {

	function check_detail()
    {
        $username = $this->input->post('nip');
        $password = $this->input->post('password');
        
        $this->db->select('id, nip, name, password, privileges, image, division, level')
            ->from('users_view')
            ->where(array('nip'=>$username, 'password'=>$password, 'status'=>1));
        $result = $this->db->get();
        // var_dump($this->db->last_query());
        if($result->num_rows() > 0)
        {
            $ess_ = array('logged_in'=>TRUE);
            return  array_merge($ess_, $result->row_array());
        }
        else
        {
            return FALSE;    
        }
    }
	function get_modal($id){
		date_default_timezone_set("Asia/Jakarta");
		$a = $this->db->query('SELECT * FROM daily_attendance where id_user = '.$id.' AND date = "'.date('Y-m-d').'"');
		// var_dump($this->db->last_query());
		if($a->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	function logout($data){
		date_default_timezone_set("Asia/Jakarta");
		$a = $this->db->query('UPDATE daily_attendance SET logout_time = "'.date('H:i:s').'" where id_user = '.$data['id'].' AND date = "'.date('Y-m-d').'"');
		if($a){
			$json = array('status'=>1,'result'=>'Success Record Your Check Out time');
		}else{
			$json = array('status'=>0,'result'=>'Fail to record your logout time');
		}
		return $json;
		
	}
	function isCheckOut(){
		date_default_timezone_set("Asia/Jakarta");
		$query = $this->db->query('SELECT logout_time FROM daily_attendance WHERE id_user = "'.$this->session->userdata('id').'" AND date = "'.date('Y-m-d').'"');
		// var_dump($this->db->last_query());
		$result = $query->result_array();
		if(isset($result[0])){
			if($result[0]['logout_time'] == '00:00:00'){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
}