<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Model {
    
    function get_number()
    {
		$a = $this->db->query("SELECT * from user_request where manager_status=0 and leader_status=1");
		$result = $a->num_rows();
		return $result;
	}
	function get_number_leader()
    {
		$division = $this->session->userdata('division');
		$a = $this->db->query("SELECT user_request.id from user_request inner join users on user_request.id_user=users.id inner join divisions on users.id_division=divisions.id where leader_status=0 and divisions.division='$division'");
		$result = $a->num_rows();
		return $result;
	}
}