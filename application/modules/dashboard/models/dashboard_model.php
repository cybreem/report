<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	function get_data() 
    {
       // $division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%' ;
    	$this->load->library('datatables');
		$this->datatables->select('A.id, A.division, A.leader, count(B.id_division) as total_member')
		
			->from('divisions A')
			->unset_column('A.id')
			->join('users B', 'A.id=B.id_division', 'left')
           // ->like()
			->group_by('A.leader');
		return $this->datatables->generate();
    }
	
	/*-------------------------
		Late list
	-------------------------*/
	function get_late_list(){
		if($this->session->userdata('level')!='Admin'){
			$dateFrom = date('Y-m-01');
			$dateTo = date('Y-m-d');
			$a = $this->db->query('SELECT * FROM daily_attendance WHERE late > 0 AND id_user = '.$this->session->userdata('id').' AND `date` BETWEEN CAST("'.$dateFrom.'" AS DATE) AND CAST("'.$dateTo.'" AS DATE) ');
			return $a->result_array();
		}else{
			$dateFrom = date('Y-m-01');
			$dateTo = date('Y-m-d');
			$a = $this->db->query('SELECT division, (COUNT(late)) as ctl , SUM(late) as slate FROM daily_attendance da JOIN users_view u ON u.id = da.id_user WHERE `date` BETWEEN CAST("'.$dateFrom.'" AS DATE) AND CAST("'.$dateTo.'" AS DATE) GROUP BY division');
			
			return $a->result_array();
		}
	}
	function get_absent_list(){
		$result = array();
		if($this->session->userdata('level')!='Admin'){
			$total = 0;
			foreach ($this->get_list_date() as $row){
				$query = $this->db->query('SELECT date, status, att.id_status, status_parent FROM attendance att JOIN attendance_status ats ON att.id_status = ats.id_status WHERE date = "'.$row.'"  AND id_user = "'.$this->session->userdata('id').'"');
				// var_dump($this->db->last_query());
				$res = $query->result_array();
				if(count($res)>0){
					if($res[0]['id_status']==2||$res[0]['status_parent']==2){
						$a['date'] = $res[0]['date'];
						$a['status'] = $res[0]['status'];
						$result[] = $a;
					}
				}else{
					$a['date'] = $row;
					$a['status'] = $this->get_attendance_status(8);
					$result[] = $a;
				}
				$total++;
			}
			$data = array($total,$result);
		}
		else{
			$data = array();
			$total = 0;
			foreach ($this->get_list_date() as $row){
				$query = $this->db->query("SELECT division, COUNT(*) as ct FROM `users_view` WHERE id NOT IN (SELECT id_user FROM attendance att JOIN attendance_status ats ON att.id_status = ats.id_status WHERE (att.id_status =1  OR ats.status_parent = 1) AND date='$row') and level != 'Admin' and div_flag=1 AND status=1 GROUP BY division ORDER BY `id` ASC  ");
				$res = $query->result_array();
				foreach($res as $val){
					if(isset($data[$val['division']] )){
						$data[$val['division']] += $val['ct'];
					}else{
						$data[$val['division']] = $val['ct'];
					}
				}
				
			}
		}
		return $data;
	}
	function get_attendance_status($id){
		$q = $this->db->query('SELECT status FROM attendance_status WHERE id_status = '.$id);
		$r = $q->result_array();
		return $r[0]['status'];
	}
	function get_list_date(){
		$date = array();
		$first_date = strtotime(date('Y-m-01'));
		$end_date = strtotime(date('Y-m-d'));
		$current_date = $first_date;
		$holiday = $this->get_holiday_arr();
		$total_day = date('j');
		for($a = 1;$a<=$total_day;$a++){
			if(!in_array(date('Y-m-d',$current_date), $holiday) && date('N',$current_date)!=7&&date('N',$current_date)!=6){
				$date[] = date('Y-m-d',$current_date);
			}
			$current_date +=(60 * 60 * 24);
		}
		// var_dump($date);
		return $date;
	}
	function getWorkingDays($startDate,$endDate,$holidays){
		// do strtotime calculations just once
		$first_day_this_month = date('Y-m-01',strtotime($startDate)); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t',strtotime($startDate));
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
	function get_holiday_arr(){
		$holidays = $this->db->query('SELECT date from holiday');
		$holi = array();
		$h = $holidays->result_array();
		foreach($h as $row){
			$holi[] = $row['date'];
		}
		return $holi;
	}
	
	function list_late($division){
		$start = date('Y-m-d',strtotime(date('Y-m-01')));
		$end = date('Y-m-d');
		$query = $this->db->query("select id_user, login_time, logout_time, date, (id_division) from daily_attendance inner join users on daily_attendance.id_user = users.id where status=1 and date >='$start' and date <='$end' and id_division='$division'");
		$data = $query->result_array();
		return $data;
	}
	
	function get_division()
	{
		$query = $this->db->query("select * from divisions where flag=1");
		$data = $query->result_array();
		return $data;
	}
	/*-------------------------
		End Late list
	-------------------------*/
	
	/*-------------------------
		Operation Rates
	-------------------------*/
	function projectWorkload()
	{
		$last_date = $this->get_last_day('reports');
		$str = '';
		$str2 = '';
		if($this->session->userdata('level')=='Member'){
			$str = "AND id = ".$this->session->userdata('id');
			$str2 = "AND id_user = ".$this->session->userdata('id');
		}
		$a = $this->db->query("SELECT '$last_date' date, COALESCE(SUM(actual_time),NULL,0) as pw, (SELECT COUNT(*) FROM users_view WHERE division LIKE '%".$this->session->userdata('division')."%' $str AND status = 1 AND division IS NOT NULL) as u FROM reports_view rv JOIN users_view ON rv.id_user = users_view.id WHERE `date` = '$last_date' AND (class_id = 2 OR class_id = 3) AND rv.division LIKE '%".$this->session->userdata('division')."%' AND rv.division IS NOT NULL $str2");		
		
		$b = $this->db->last_query();
		// var_dump($b);
		$b = $a->result_array();
		return $b;
		
		// return $this->db->last_query();
	}
	
	function get_pw()
	{
		$dateFrom = '';
		$dateTo = '';
		if(date('N') == 1){

			$start_week = strtotime("last monday");
			$end_week = strtotime("last friday");

			$dateFrom = date("Y-m-d",$start_week);
			$dateTo = date("Y-m-d",$end_week);
			
		}else{
			$start_week = strtotime("last monday");
			$end_week = strtotime("yesterday");
			$dateFrom = date("Y-m-d",$start_week);
			$dateTo = date("Y-m-d",$end_week);
		}
		 
		// $dateTo =$this->get_last('reports');
		$date = date('Y-m-d');
			$str = '';
		$str2 = '';
		if($this->session->userdata('level')=='Member'){
			$str = "AND id = ".$this->session->userdata('id');
			$str2 = "AND id_user = ".$this->session->userdata('id');
		}
		// $a = $this->db->query("SELECT COALESCE(SUM(ROUND((TIME_TO_SEC(TIMEDIFF(plan_to, plan_from)) / 3600),2)),0) as pw,(SELECT COUNT(*) FROM users_view WHERE division LIKE '%".$this->session->userdata('division')."%') as u ,(SELECT COUNT(*) FROM(SELECT DISTINCT date FROM reports JOIN users_view ON reports.id_user = users_view.id WHERE `date` BETWEEN CAST('$dateFrom' AS DATE) AND CAST('$dateTo' AS DATE) AND division LIKE '%".$this->session->userdata('division')."%' GROUP BY `date`) as x) as total_day FROM reports JOIN users_view ON reports.id_user = users_view.id WHERE `date` BETWEEN CAST('$dateFrom' AS DATE) AND CAST('$dateTo' AS DATE) AND (id_classification = 2 OR id_classification = 3) AND division LIKE '%".$this->session->userdata('division')."%'");		
		$a = $this->db->query("SELECT CONCAT('$dateFrom', ' - ', '$dateTo') as dt,COALESCE(SUM(actual_time),NULL,0)  as pw,(SELECT COUNT(*) FROM users_view WHERE division LIKE '%".$this->session->userdata('division')."%' $str) as u, (SELECT COUNT(*) FROM(SELECT DISTINCT date FROM reports_view nrv JOIN users_view ON nrv.id_user = users_view.id WHERE `date` BETWEEN CAST('$dateFrom' AS DATE) AND CAST('$dateTo' AS DATE) AND nrv.division LIKE '%".$this->session->userdata('division')."%'  GROUP BY `date`) as x) as total_day FROM reports_view rv  WHERE `date` BETWEEN CAST('$dateFrom' AS DATE) AND CAST('$dateTo' AS DATE) AND (class_id = 2 OR class_id = 3) AND rv.division LIKE '%".$this->session->userdata('division')."%' $str2  ");		
		$b = $a->result_array();
		// $b = $this->db->last_query();
		// var_dump($b);
		return $b;
		// return $this->db->last_query();
	}
	
	///////////////////////////////////
	
	function get_last_day($field){
		$a = $this->db->query("SELECT MAX(date) as md FROM $field WHERE `date` < CURDATE()");
		$b = $a->result_array();
		return $b[0]['md'];
	}
	
	function get_last($field){
		$a = $this->db->query("SELECT MAX(date) as md FROM $field WHERE `date` < CURDATE()");
		$b = $a->result_array();
		return $b[0]['md'];
	}
	
	/*-------------------------
		End Operation Rates
	-------------------------*/
	
	/*-------------------------
		Workload All data
	-------------------------*/
	function global_chart()
	{
	    //$division = $division!=0 ? $this->get_division_name($division) : '%';
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('first day of this month',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('today',strtotime($to_dt)));
		
		$labels = array('January','February','March','April','May','June','July','August','September','October','November','December');

		
			$query = $this->db->select('category')->from('categories')->get();
			$getCategory = $query->result_array();
			$stroke_color = array('rgba(150, 40, 27, 1)','rgba(103, 65, 114, 1)','rgba(65, 131, 215, 1)','rgba(34, 49, 63, 1)','rgba(38, 166, 91, 1)','rgba(211, 84, 0, 1)','rgba(210, 77, 87, 1)','rgba(145, 61, 136, 1)','rgba(31, 58, 147, 1)','rgba(248, 148, 6, 1)','rgba(1, 152, 117, 1)','rgba(149, 165, 166, 1)',);
			$color_trans = array('rgba(150, 40, 27, 0.3)','rgba(103, 65, 114, 0.3)','rgba(65, 131, 215, 0.3)','rgba(34, 49, 63, 0.3)','rgba(38, 166, 91, 0.3)','rgba(211, 84, 0, 0.3)','rgba(210, 77, 87, 0.3)','rgba(145, 61, 136, 0.3)','rgba(31, 58, 147, 0.3)','rgba(248, 148, 6, 0.3)','rgba(1, 152, 117, 0.3)','rgba(149, 165, 166, 0.3)',);
			foreach($getCategory as $key => $val){
				$category = $val['category'];
				foreach($labels as $monthVal){
					$query=$this->db->select('reports_view.id, MONTHNAME(date) as month, COUNT(id_user) as total_user, category, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual, (SUM(actual_time) - SUM(plan_time)) as notes')
						->from('reports_view')
						->join('users','reports_view.id_user=users.id','inner')
						//->where('date >=', '"'.$start.'"',false)
						//->where('date <=', '"'.$end.'"',false)
				       // ->where('division LIKE ', '"'.$division.'"',false)
						->group_by('YEAR(date)')
						->group_by('MONTH(date)')
						->where('category',$category)
						->where('MONTHNAME(date)',$monthVal)
						->get();
						//->group_by('category');
					$resultData = $query->result_array();
					$cek = count($resultData);
					if($cek>0){
						foreach($resultData as $keys=>$row)
						{
							$data[] = $row['total_actual'];
						}
					}else{
						$data[] = null;
					}
				}
				if(isset($data)){
					$chart_data[] = array (
							'label'=>$category,
							'fillColor' => $color_trans[$key],
							'strokeColor' => $stroke_color[$key],
							'pointColor' => $stroke_color[$key],
							'pointStrokeColor' => "#fff",
							'pointHighlightFill' => "#fff",
							'pointHighlightStroke' => "rgba(220,220,220,1)",
							'data' => $data
					);
					$categoryArray[] = array($row['category'] => $chart_data);
					unset($data);
				}
			}
		$data = array('labels' => $labels, 'data' => $chart_data);

		
		return json_encode($data);
	}

	function workload_all_table($division)
	{
	    $division = $division!=0 ? $this->get_division_name($division) : '%';
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('first day of this month',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('today',strtotime($to_dt)));
		$this->load->library('datatables');
		$this->datatables->select('reports_view.id, date, COUNT(id_user) as total_user, category, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual, (SUM(actual_time) - SUM(plan_time)) as notes')
			 ->from('reports_view')
			 ->join('users_view','reports_view.id_user=users_view.id','inner')
			 ->unset_column('reports_view.id')
			 ->edit_column('total_plan', $this->get_hour('$1').' hours', 'total_plan')
			 ->edit_column('total_actual', $this->get_hour('$1').' hours', 'total_actual')
			 ->edit_column('notes', $this->get_hour('$1').' hours', 'notes')
			 ->where('date >=', '"'.$start.'"',false)
			 ->where('date <=', '"'.$end.'"',false)
             ->where('reports_view.division LIKE ', '"'.$division.'"',false)
			 ->where('users_view.division IS NOT ', 'NULL',false)
             ->where('users_view.status',1)
			 ->group_by('category');
		return $this->datatables->generate();
	}
	
	function workload_all_table_detail($division)
	{
	    $division = $division!=0 ? $this->get_division_name($division) : '%';
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('first day of this month',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('today',strtotime($to_dt)));
		$this->load->library('datatables');
		$this->datatables->select('reports_view.id, date, COUNT(id_user) as total_user, classification, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual')
			 ->from('reports_view')
			 ->join('users_view','reports_view.id_user=users_view.id','inner')
			 ->unset_column('reports_view.id')
			 ->unset_column('date')
			 ->edit_column('total_plan', $this->get_hour('$1').' hours', 'total_plan')
			 ->edit_column('total_actual', $this->get_hour('$1').' hours', 'total_actual')
			 ->where('date >=', '"'.$start.'"',false)
			 ->where('date <=', '"'.$end.'"',false)
            ->where('reports_view.division LIKE ', '"'.$division.'"',false)
			 ->where('users_view.division IS NOT ', 'NULL',false)
             ->where('users_view.status',1)
			 ->group_by('classification');
		return $this->datatables->generate();
	}

	function workload_all_table_detail2($division)
	{
	    $division = $division!=0 ? $this->get_division_name($division) : '%';
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('first day of this month',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('today',strtotime($to_dt)));
		$this->load->library('datatables');
		$this->datatables->select('reports_view.id, code, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual')
			 ->from('reports_view')
			 ->join('users','reports_view.id_user=users.id','inner')
			 ->unset_column('reports_view.id')
			 ->unset_column('date')
			 ->edit_column('total_plan', $this->get_hour('$1').' hours', 'total_plan')
			 ->edit_column('total_actual', $this->get_hour('$1').' hours', 'total_actual')
			 ->where('date >=', '"'.$start.'"',false)
			 ->where('code !=', "")
			 ->where('date <=', '"'.$end.'"',false)
             ->where('division LIKE ', '"'.$division.'"',false)
             ->where('division IS NOT ', 'NULL',false)
             ->where('users.status',1)
			 ->group_by('code');
		return $this->datatables->generate();
	}

	function operating_rate()
	{
	    $division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%';
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('first day of this month',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('today',strtotime($to_dt)));
		$this->load->library('datatables');
		$this->datatables->select('reports_view.id, date, classification, SUM(actual_time) as total_actual')
			 ->from('reports_view')
			 ->join('users','reports_view.id_user=users.id','inner')
			 ->unset_column('reports_view.id')
			 ->unset_column('date')
			 ->edit_column('total_actual', $this->get_hour('$1').' hours', 'total_actual')
			 ->where('date >=', '"'.$start.'"',false)
			 ->where('date <=', '"'.$end.'"',false)
             ->where('division LIKE ', '"'.$division.'"',false)
			 ->where('division IS NOT ', 'NULL',false)
             ->where('users.status',1)
			 ->group_by('classification');
		return $this->datatables->generate();
	}
	
	function check_privileges()
	{
	    $division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%';
		$get = $this->db
		->select('id, division, leader')
		->from('divisions')
		->where('division like','"'.$division.'"',false)
		->where('flag',1)
		->order_by('division','asc')
		->get();
		if($this->session->userdata('level')=="Admin"){
			$data[0] = '';
		}
		foreach($get->result_array() as $set){
			$data[$set['id']] = $set['division'];
		}
		return $data;
	}
	
	function get_division_name($division)
	{
		$get = $this->db
		->select('division')
		->from('divisions')
		->where('id',$division)
		->where('flag',1)
		->get();
		$count = count($get->result_array());
		if($division==0 || $count==0){
			$data = '';
		}else{
			foreach($get->result_array() as $set){
				$data = $set['division'];
			}
		}
		return $data;
	}

	
	/*-------------------------
		End Workload data
	-------------------------*/
	
	/*-------------------------
		Start Individual data
	-------------------------*/
	function get_members()
	{
	
		$division = ($this->session->userdata('division')) ? $this->session->userdata('division') : '%%';
		$result = $this->db
				->select('users.id')
				->select('users.name')
				->where('status', 1)
				->from('users')
				->join('divisions','users.id_division = divisions.id','inner')
				->where('divisions.division LIKE ', '"'.$division.'"',false)
				->get();
        
        $data[''] = "";
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['name'];
        }
        return $data;
	}
	
	function individual_table($id_user)
	{
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('last friday',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('yesterday',strtotime($to_dt)));
		$yesterday = date('Y-m-d', strtotime('yesterday'));
		$this->load->library('datatables');
		$this->datatables->select('id, date, (1) as total_user, category, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual, (SUM(actual_time) - SUM(plan_time)) as notes')
			 ->from('reports_view')
			 ->unset_column('id')
			 ->unset_column('date')
			 ->unset_column('total_user')
             ->edit_column('total_plan', $this->get_hour('$1').' hours', 'total_plan')
             ->edit_column('total_actual', $this->get_hour('$1').' hours', 'total_actual')
			 ->edit_column('notes', $this->get_hour('$1').' hours', 'notes')
			 ->where('id_user', $id_user)
			 ->where('date >=', '"'.$start.'"',false)
			 ->where('date <=', '"'.$end.'"',false)
			 ->group_by('category')
			 ->group_by('id_user');
		return $this->datatables->generate();
	}
	
	function individual_table_detail($id_user)
	{
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('last friday',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('yesterday',strtotime($to_dt)));
		$yesterday = date('Y-m-d', strtotime('yesterday'));
		$this->load->library('datatables');
		$this->datatables->select('id, date, classification, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual')
			 ->from('reports_view')
			 ->unset_column('id')
			 ->unset_column('date')
             ->edit_column('total_plan', $this->get_hour('$1').' hours', 'total_plan')
             ->edit_column('total_actual', $this->get_hour('$1').' hours', 'total_actual')
			 ->where('id_user', $id_user)
			 ->where('date >=', '"'.$start.'"',false)
			 ->where('date <=', '"'.$end.'"',false)
			 ->group_by('classification');
		return $this->datatables->generate();
	}

	function individual_table_detail2($id_user)
	{
		$from_dt = ($this->input->get('from_dt')) ? $this->input->get('from_dt') : date('Y-m-d') ;
		$to_dt = ($this->input->get('to_dt')) ? $this->input->get('to_dt') : date('Y-m-d') ;
		$start = date('N',strtotime($from_dt)) ? date('Y-m-d',strtotime($from_dt)) : date('Y-m-d', strtotime('last friday',strtotime($from_dt)));
		$end = date('N',strtotime($to_dt)) ? date('Y-m-d',strtotime($to_dt)) : date('Y-m-d', strtotime('yesterday',strtotime($to_dt)));
		$yesterday = date('Y-m-d', strtotime('yesterday'));
		$this->load->library('datatables');
		$this->datatables->select('id, code, SUM(plan_time) as total_plan, SUM(actual_time) as total_actual')
			 ->from('reports_view')
			 ->unset_column('id')
			 ->unset_column('date')
             ->edit_column('total_plan', $this->get_hour('$1').' hours', 'total_plan')
			 ->edit_column('total_actual', $this->get_hour('$1').' hours', 'total_actual')
			 ->where('id_user', $id_user)
			 ->where('code !=', "")
			 ->where('date >=', '"'.$start.'"',false)
			 ->where('date <=', '"'.$end.'"',false)
			 ->group_by('code');
		return $this->datatables->generate();
	}
	
	function get_hour($hour)
	{
		if($hour=="" || $hour == null){
			return 0;
		}else{
			return $hour;
		}
	}
	
	function get_member_num($division)
	{
	    $division = $division!=0 ? $division : '%';
$query = $this->db
		->select('users.id')
		->from('users')
		->join('divisions','users.id_division=divisions.id','inner')
		->where('id_division like',$division)
		->where('id_division !=', '')
		->where('flag', '1')
		->get();		return $query->num_rows();
	}
	
	function get_holiday()
	{
		$this->db->select('date, holiday_desc');
		$query = $this->db->get('holiday');
		$json = $query->result_array();
		return json_encode($json);		
	}
	
/* Export */
	function get_for_export($data)
	{
	    $division = $data['division']!=0 ? $data['division'] : '%';
		$result = $this->db
				->select('date')
				->select('nip')
				->select('name')
				->select('division')
				->select('classification')
				->select('category')
				->select('job_code')
				->select('code')
				->select('description')
				->select('plan_time')
				->select('actual_time')
				->from('reports_view')
				->where('id_division like', $division)
				->where('date >=', $data['from'])
				->where('date <=', $data['to'])
				->order_by("date", "desc")
				->get();
        return $result->result_array();
	}
}