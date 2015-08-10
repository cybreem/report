<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_attendance_model extends CI_Model {
    
    function get_status()
    {
		$this->db->select('id_status, status');
		
        $query = $this->db->get('attendance_status');
		
        return $query->result_array();
    }
    
    function get_data()
    {
		$dateFirst = date('Y-m-d',$this->input->post('start'));
		// echo $dateFirst;
		$dateEnd = date('Y-m-d');
		$str = '';
		if($this->session->userdata('level')=='Leader'){
			$str = 'AND uv.division = "'.$this->session->userdata('division').'"';
		}
		$result_all = array();
        
        $query1 = $this->db->query('SELECT date dateu, `attendance`.id_status, count(*) as ct FROM `attendance` JOIN `attendance_status` as ats ON `attendance`.`id_status` = ats.id_status LEFT JOIN users_view uv on uv.id = attendance.id_user   WHERE uv.status = 1 AND attendance.id_status != 8 AND uv.division IS NOT NULL AND status_parent IS NOT NULL AND date between "'.$dateFirst.'" and "'.$dateEnd.'" '.$str.' GROUP BY id_status, date');
		$result1 = $query1->result_array();
		// var_dump($this->db->last_query());
		$str_s = '';
		if($this->session->userdata('level')=='Leader'){
			$str_s = 'WHERE uv.division = "'.$this->session->userdata('division').'"';
		}
		
		
		$query_parent1 = $this->db->query("SELECT '$dateFirst' + INTERVAL a + b DAY dateu,".
		"(SELECT 1) as id_status,".
		"(SELECT (SELECT if(COUNT(*)=0,'',COUNT(*)) from users JOIN users_view uv on uv.id = users.id WHERE uv.status = 1 AND uv.division IS NOT NULL AND id_division IS NOT NULL AND id_user_level != 2 AND users.id IN (SELECT id_user from attendance att JOIN `attendance_status` ats ON att.id_status = ats.id_status WHERE (att.id_status = 1 OR `status_parent` = '1') AND date = dateu $str ) $str) ) as ct FROM ".
		"(SELECT 0 a UNION SELECT 1 a UNION SELECT 2 UNION SELECT 3 ".
		"UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 ".
		"UNION SELECT 8 UNION SELECT 9 ) d, ".
		"(SELECT 0 b UNION SELECT 10 UNION SELECT 20 ".
		"UNION SELECT 30 UNION SELECT 40) as `m` ".
		"WHERE '$dateFirst' + INTERVAL a + b DAY  <=  '$dateEnd' ".
		"ORDER BY a + b ");
		
		$result_parent1 = $query_parent1->result_array();
		// var_dump($this->db->last_query());
		$query_parent2 = $this->db->query("SELECT '$dateFirst' + INTERVAL a + b DAY dateu,".
		"(SELECT 2) as id_status,".
		"(SELECT (SELECT COUNT(*) from users JOIN users_view uv on uv.id = users.id WHERE uv.status = 1 AND uv.division IS NOT NULL AND id_division IS NOT NULL AND id_user_level != 2 AND users.id NOT IN (SELECT id_user from attendance att JOIN `attendance_status` ats ON att.id_status = ats.id_status WHERE (att.id_status = 1 OR `status_parent` = '1') AND date = dateu $str ) $str) ) as ct FROM ".
		"(SELECT 0 a UNION SELECT 1 a UNION SELECT 2 UNION SELECT 3 ".
		"UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 ".
		"UNION SELECT 8 UNION SELECT 9 ) d, ".
		"(SELECT 0 b UNION SELECT 10 UNION SELECT 20 ".
		"UNION SELECT 30 UNION SELECT 40) as `m` ".
		"WHERE '$dateFirst' + INTERVAL a + b DAY  <=  '$dateEnd' ".
		"ORDER BY a + b ");
		
		$result_parent2 = $query_parent2->result_array();
		$result_parent = array_merge($result_parent1,$result_parent2);
		
		
		$str = '';
		if($this->session->userdata('level')=='Leader'){
			$str = 'AND uv.division = "'.$this->session->userdata('division').'"';
		}
		
		$query3= $this->db->query("SELECT '$dateFirst' + INTERVAL a + b DAY dateu,".
		"(SELECT 8) as id_status,".
		// "(SELECT (SELECT COUNT(*) from users JOIN users_view uv on uv.id = users.id WHERE uv.status = 1 AND uv.division IS NOT NULL AND id_division IS NOT NULL AND id_user_level != 2 $str) - (SELECT COUNT(*) from attendance asu JOIN users_view uv on uv.id = asu.id_user JOIN users on users.id = uv.id  WHERE (id_status != 2 AND id_status != 8) AND asu.date = dateu $str) ) as ct FROM ".
		
		"(SELECT COUNT(*) from users JOIN users_view uv on uv.id = users.id WHERE uv.status = 1 AND uv.division IS NOT NULL AND id_division IS NOT NULL AND id_user_level != 2 AND uv.id NOT IN (SELECT id_user from attendance att JOIN `attendance_status` ats ON att.id_status = ats.id_status WHERE (att.id_status = 10 OR (att.id_status != 8 AND att.id_status != 2) ) AND date = dateu) $str) as ct FROM ".
		"(SELECT 0 a UNION SELECT 1 a UNION SELECT 2 UNION SELECT 3 ".
		"UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 ".
		"UNION SELECT 8 UNION SELECT 9 ) d, ".
		"(SELECT 0 b UNION SELECT 10 UNION SELECT 20 ".
		"UNION SELECT 30 UNION SELECT 40) m ".
		"WHERE '$dateFirst' + INTERVAL a + b DAY  <=  '$dateEnd' ".
		"ORDER BY a + b ");
		// var_dump($this->db->last_query());
		$result3 = $query3->result_array();
		// var_dump($result3);
		
		$result_all = array_merge($result1, $result_parent, $result3);
		// $result_all = $result3;
        return $result_all;
    }
	function selectStatusName(){
		
		$this->db->select('id_status, status');
		$this->db->where('id_status !=',10);
		$query = $this->db->get('attendance_status');
		
		$rs = $query->result_array();
		$this->moveElement($rs,1,3);
		$this->moveElement($rs,8,3);
		$this->moveElement($rs,7,3);
		return $rs;
	}
	
	function moveElement(&$array, $a, $b) {
		$out = array_splice($array, $a, 1);
		array_splice($array, $b, 0, $out);
	}
	function set_daily_attendance($att){
		$result = $this->db->insert('daily_attendance',$att);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	function set_attendance($att){
		$count = $this->db->query('SELECT id FROM attendance WHERE attendance.date = "'.$att['date'].'" AND id_user = "'.$att['id_user'].'"');
		$r = $count->result_array();
		if(isset($r[0]['id'])){
			$this->db->where('id',$r[0]['id']);
			$result = $this->db->update('attendance',$att);
		}else{
			$result = $this->db->insert('attendance',$att);
		}
		
		if($result){
			return true;
		}else{
			return false;
		}
	}
	function detail_attendance()
	{
		$date = date('Y-m-d',round($this->input->post('date')/1000));
		$status = $this->input->post('status');
		$division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%%';
		$this->load->library('datatables');
		$a = null;
		
		if($this->getParent($status)==1||$status==1){
			
			$a = $this->datatables->select('uv.name, uv.division, reason,att.id_user as iu,att.date as atd, ( SELECT login_time from daily_attendance da WHERE da.date = atd AND da.id_user = iu ) as login_time,( SELECT logout_time from daily_attendance da WHERE da.date = atd AND da.id_user = iu ) as logout_time');
			
			$a->unset_column('iu');
			$a->unset_column('atd');
			$a->from('attendance att')
			->join('users u','u.id = att.id_user')
			->join('user_request ur','ur.id = att.id_request','left')
			->join('divisions d','d.id = u.id_division')
			->join('attendance_status ats','ats.id_status = att.id_status')
			->where('date=', '"'.$date.'"',false);
			if($this->isParent($status)){
				$a->where('att.id_status', $status);
			}
			else{
				$a->where("(att.id_status='$status' OR status_parent='$status')");
				// $a->or_where('status_parent', );
			}
			
			$a->join('users_view uv','uv.id = att.id_user')
			->where('uv.division LIKE',$division)
			->where('uv.division IS NOT ','NULL',FALSE)
			->where('uv.status =','1');
			
		}elseif($this->getParent($status)==2||$status==2){
			$a = $this->datatables->select('uv.name, uv.division ');
			$a->from('users u');
			
			if($status == 2){
				$a->where('u.id NOT IN (SELECT id_user from attendance JOIN attendance_status ON attendance.id_status = attendance_status.id_status WHERE (attendance.id_status =1 OR status_parent =1) AND date="'.$date.'")');
				
			}else if($status == 8){
				$a->where('u.id NOT IN (SELECT id_user from attendance JOIN attendance_status ON attendance.id_status = attendance_status.id_status WHERE (attendance.id_status = 10 OR (attendance.id_status != 8 AND attendance.id_status != 2) ) AND date="'.$date.'")');
				
			}
			else{
				
				$a->where('u.id IN (SELECT id_user from attendance JOIN attendance_status ON attendance.id_status = attendance_status.id_status WHERE (attendance.id_status = '.$status.') AND date="'.$date.'")');
			}
			
			$a->join('users_view uv','uv.id = u.id')
			->where('uv.division IS NOT ','NULL',FALSE)
			->where('uv.division LIKE',$division)
			->where('uv.status =','1');
		}
		else{
			$a = $this->datatables->select('uv.name, uv.division, end_time');
		}
		return $this->datatables->generate();
	}
	
	function summary($st,$en,$range = 'monthly'){
		date_default_timezone_set("Asia/Jakarta");
		$dateFirst = $st;
		$dateEnd = $en;
		$division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%%';
		// $str = 'AND ';
		$holi = $this->get_holiday();
		// var_dump($holi);
		$result = $this->getWorkingDays((string)$dateFirst,(string)$dateEnd,$holi);
		// var_dump($result);
		$get_total_member = $this->db->query('SELECT COUNT(*) as ct from users_view where division LIKE "'.$division.'" AND users_view.status = 1 ');
		$gtm_result = $get_total_member->result_array();
		// var_dump($result);
		
		$total_now = ($range == 'monthly') ? $result*$gtm_result[0]['ct'] : $gtm_result[0]['ct'];
		
		$query = $this->db->query('SELECT attendance_status.id_status aid, status, '.
		'(
			CASE 
				WHEN attendance_status.id_status = 1 THEN 
					(SELECT COUNT(*) FROM attendance att JOIN attendance_status ats ON att.id_status = ats.id_status JOIN users_view uv ON uv.id = att.id_user WHERE uv.status = 1 AND uv.division IS NOT NULL AND (att.id_status=1 OR status_parent=1) AND division LIKE "%'.$division.'%" AND `date` BETWEEN CAST("'.$dateFirst.'" AS DATE) AND CAST("'.$dateEnd.'" AS DATE))
				WHEN attendance_status.id_status = 2 THEN
					(SELECT ('.$total_now.') - (SELECT COUNT(*) FROM attendance att JOIN attendance_status ats ON att.id_status = ats.id_status JOIN users_view uv ON uv.id = att.id_user WHERE uv.status = 1 AND uv.division IS NOT NULL AND (att.id_status=1 OR status_parent=1) AND division LIKE "%'.$division.'%" AND `date` BETWEEN CAST("'.$dateFirst.'" AS DATE) AND CAST("'.$dateEnd.'" AS DATE)) as ct)
					WHEN attendance_status.id_status = 8 THEN
					(SELECT ('.$total_now.') - (SELECT COUNT(*) FROM attendance att JOIN attendance_status ats ON att.id_status = ats.id_status JOIN users_view uv ON uv.id = att.id_user WHERE uv.status = 1 AND uv.division IS NOT NULL AND (att.id_status = 10 OR (att.id_status != 8 AND att.id_status != 2) ) AND division LIKE "%'.$division.'%" AND `date` BETWEEN CAST("'.$dateFirst.'" AS DATE) AND CAST("'.$dateEnd.'" AS DATE)) as ct)
				ELSE
					(SELECT COUNT(*) FROM attendance att JOIN attendance_status ats ON att.id_status = ats.id_status JOIN users_view uv ON uv.id = att.id_user WHERE att.id_status = aid AND uv.status = 1 AND uv.division IS NOT NULL AND division LIKE "%'.$division.'%" AND `date` BETWEEN CAST("'.$dateFirst.'" AS DATE) AND CAST("'.$dateEnd.'" AS DATE))
		END
		)'.
		' as total FROM attendance_status WHERE id_status != 10 ');
		
		$a = $query->result_array();
		// var_dump($this->db->last_query());
		$this->moveElement($a,1,3);
		$this->moveElement($a,8,3);
		$this->moveElement($a,7,3);
		return $a;
	}
	
	function get_holiday(){
		$holidays = $this->db->query('SELECT date from holiday');
		$holi = array();
		$h = $holidays->result_array();
		foreach($h as $row){
			$holi[] = $row['date'];
		}
		return $holi;
	}
	function getWorkingDays($startDate,$endDate,$holidays){
		// do strtotime calculations just once
		$first_day_this_month = date('Y-m-01',strtotime($startDate)); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-d',strtotime($endDate));
		// echo $first_day_this_month.'-'.$last_day_this_month;
		$endDate = strtotime($last_day_this_month);
		$startDate = strtotime($first_day_this_month);
		
		//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
		//We add one to inlude both dates in the interval.
		$days = ($endDate - $startDate) / 86400 + 1;
		// echo $days;
		// echo $days;
		$no_full_weeks = floor($days / 7);
		$no_remaining_days = fmod($days, 7);
		
		//It will return 1 if it's Monday,.. ,7 for Sunday
		$the_first_day_of_week = date("N", $startDate);
		$the_last_day_of_week = date("N", $endDate);

		//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
		//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($the_first_day_of_week <= $the_last_day_of_week) {
			if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
			if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
		}
		else {
			// (edit by Tokes to fix an edge case where the start day was a Sunday
			// and the end day was NOT a Saturday)

			// the day of the week for start is later than the day of the week for end
			if ($the_first_day_of_week == 7) {
				// if the start date is a Sunday, then we definitely subtract 1 day
				$no_remaining_days--;

				if ($the_last_day_of_week == 6) {
					// if the end date is a Saturday, then we subtract another day
					$no_remaining_days--;
				}
			}
			else {
				// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
				// so we skip an entire weekend and subtract 2 days
				$no_remaining_days -= 2;
			}
		}

		//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
	//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
	   $workingDays = $no_full_weeks * 5;
		if ($no_remaining_days > 0 )
		{
		  $workingDays += $no_remaining_days;
		}
		
		//We subtract the holidays
		foreach($holidays as $holiday){
			$time_stamp=strtotime($holiday);
			//If the holiday doesn't fall in weekend
			if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7){
				$workingDays--;
			}
		}
		
		return $workingDays;
	}
	function isParent($id){
		$a = $this->db->query('SELECT status FROM attendance_status where id_status = '.$id);
		if($a->num_rows()){
			return false;
		}else{
			return true;
		}
	}
	function getParent($id){
		$a = $this->db->query('SELECT status_parent FROM attendance_status where id_status = '.$id);
		$result = $a->result_array();
		return $result[0]['status_parent'];
	}
	function user_list()
	{
		$a = $this->db->query("SELECT id, name, nip, avaya FROM users_view where division not like '' order by name asc");
		$result = $a->result_array();
		return $result;
	}
	function holiday_list()
	{
		$a = $this->db->query("SELECT date from holiday");
		$result = $a->result_array();
		return $result;
	}
	function login_list($id_user,$month)
	{
		$curr_date = date("Y-m-01",strtotime($month));
		$day = date("Y-m-t",strtotime($month));
		
		$a = $this->db->query("SELECT * from attendance_view where id_user='$id_user' and date between '$curr_date' and '$day'");
		$result = $a->result_array();
		return $result;
	}
}