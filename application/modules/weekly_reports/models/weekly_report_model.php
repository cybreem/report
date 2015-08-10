<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Weekly_report_model extends CI_Model {

	function grid_data($date = false)
	{
		$division = ($this->session->userdata('division'))?$this->session->userdata('division'):'%';
		$this->load->library('datatables');
		$this->datatables->select('(users.id) as id_user, users.name, divisions.division')
			->from('users')
			->join('divisions', 'users.id_division=divisions.id', 'inner')
			->join('reports', 'users.id=reports.id_user', 'left')
			->where('flag=1 and status=1 and division like','"'.$division.'"',false)
			->unset_column('id_user')
			->add_column('Actions',  $this->get_edit_button('$1','$2'), 'id_user,'.$date.'')
            ->group_by('users.id');
		return $this->datatables->generate();
	}
	
	function get_edit_button($id,$date){
		$get_date = (string)$date;
		$ymd = strtotime($get_date);
		$btn = '<p class="btn-group">';
		$btn .= '<button class="btn btn-xs btn-info tooltips" title="Edit" onclick=call_modal('.$id.',"'.$get_date.'")><i class="fa fa-edit"></i> Edit Plan</button>';
	    $btn .= '</p>';
		return $btn ;
	}
    
    function get_member()
    {
        $query = $this->db->get_where('users_view', array('division'=>$this->session->userdata('division')));
        return $query->result_array();
    }
	
	function opt_members()
    {
        $result = $this->db->get('users');
        
        $data[''] = 'Name';
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['name'];
        }
        return $data;
    }
    
    function opt_classifications()
    {
        $result = $this->db->where('flag', 1)->get('classifications');
        
        $data[''] = 'Work Classifications';
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['classification'];
        }
        return $data;
    }

    
    function opt_job_codes($id)
    {
        $result = $this->db->where('flag', 1)->get_where('jobs', array('id_classification'=>$id));
        
        $data[''] = 'Job Codes';
        foreach ($result->result_array() as $row)
        {
        	if($row['id_classification'] == 2)
			{
				$data[$row['id']] = $row['project_mng_code'];
			}
			else if($row['id_classification'] == 3)
			{
				$data[$row['id']] = $row['project_mng_code'];
			}
			else
			{
				$data[$row['id']] = $row['job_name'];
			}
            
        }
        return $data;
    }
    
    function opt_categories()
    {
        $result = $this->db->where('flag', 1)->get('categories');
        
        $data[''] = 'Work Categories';
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['category'];
        }
        return $data;
    }

	function get_name($id)
	{
		$this->db->where('id',$id);
		$this->db->select('name');
		$query = $this->db->get('users')->row_array();
		return $query['name'];
	}
	
	function get_report($id, $date)
	{
		$this->db->select('id, name, classification, id_job, code, category, description, plan_from, plan_to, actual_from, actual_to');
		$this->db->where(array('id_user'=>$id, 'date'=>$date));
		$this->db->from('reports_view');
		return $this->db->get()->result();
	}
    
	function update_attendance($save,$criteria)
	{
		if($criteria['id']!="")
		{
			return $this->db->update('attendance', $save, $criteria);
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been edited');
		}
		else
		{
			return $this->db->insert('attendance', $save);
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been added');
		}
	}
	
	function save_data($data)
	{
		if($data['id'])
		{
			$this->db->update('work_categories', $data, array('id'=>$data['id']));
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been edited');
		}
		else
		{
			$this->db->insert('work_categories', $data);
			$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been added');
		}
	}
    
    function get_user_id($name)
    {
        $this->db->select('id')
            ->where('name', $name);
        $query = $this->db->get('users')->row_array();   
        return $query['id'];
    }
    
    function get_classification_id($classification)
    {
        $this->db->select('id')
			->where('flag', '1')
            ->like('classification', $classification);
        $query = $this->db->get('classifications')->row_array();   
        return $query['id'];
    }
    
    function get_category_id($category)
    {
        $this->db->select('id')
			->where('flag', '1')
            ->like('category', $category);
        $query = $this->db->get('categories')->row_array();   
        return $query['id'];
    }
    
    function get_job_id($job)
    {
        if($job == '')
        {
           return '';
        }
        else
        {
            $this->db->select('id')
			//->where('flag', '1')
            ->like('project_mng_code', $job);
            $query = $this->db->get('jobs')->row_array();
            return $query['id'];
        }
        
    }
    
    function call_atendance($data)
    {
        $this->db->where($data);
        $query = $this->db->get('attendance')->row_array();   
        return $query;
    }
    
    function search_duplicate($dataarray)
    {
    	$count = 0;
        for($i=1; $i<count($dataarray); $i++)
        {
            $search = array(
                'date' => date('Y-m-d',strtotime('-1 day',strtotime($dataarray[$i]['date']))),
                'id_user'=>$this->get_user_id($dataarray[$i]['name'])
            );
        	$Q = $this->db->get_where('cc', $search);
			if($Q->num_rows() > 0)
			{
				$this->db->delete('reports', $search);
				$count++;
			}
        }   
        return $count;
    }
    
    function insert_plan($dataarray)
    {
    	$count = 0;
    	for($i=1; $i<count($dataarray); $i++)
        {
            $data = array(              
                'date' => date('Y-m-d',strtotime('-1 day',strtotime($dataarray[$i]['date']))),
                'id_user' => $this->get_user_id($dataarray[$i]['name']),
                'id_classification' => $this->get_classification_id($dataarray[$i]['work_classification']),
                'id_job' => $this->get_job_id($dataarray[$i]['job_code']),
                'id_category' => $this->get_category_id($dataarray[$i]['work_category']),
                'description' => $dataarray[$i]['description'],
                'plan_from' => $dataarray[$i]['plan_from'],
                'plan_to' => $dataarray[$i]['plan_to']
            );
            $this->db->insert('reports', $data);
            $this->db->affected_rows() > 0 ? $count++ : $count+1;
        }
        return $count;
    }
    
    function update_plan($dataarray)
    {
    	//$count = 0;
    	for($i=1; $i<count($dataarray); $i++)
        {
            $data = array(              
                'date' => date('Y-m-d',strtotime('-1 day',strtotime($dataarray[$i]['date']))),
                'id_user' => $this->get_user_id($dataarray[$i]['name']),
                'id_classification' => $this->get_classification_id($dataarray[$i]['work_classification']),
                'id_job' => $this->get_job_id($dataarray[$i]['job_code']),
                'id_category' => $this->get_category_id($dataarray[$i]['work_category']),
                'description' => $dataarray[$i]['description'],
                'plan_from' => $dataarray[$i]['plan_from'],
                'plan_to' => $dataarray[$i]['plan_to']
            );
            //$this->db->delete('cc', array('date'=>$data['date'], 'id_user'=>$data['id_user']));
            $this->db->insert('reports', $data);
		}
        return count($this->db->insert_id());
    }
    
    function update_data($save, $criteria)
    {
        return $this->db->update('reports', $save, $criteria); 
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been updated'); 
	}
	
    function insert_data($save)
    {
        return $this->db->insert('reports', $save); 
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been updated'); 
	}
      
	function delete_data($id)
	{
		$this->db->delete('reports', array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been deleted');
	}

	
	/*==================================
	Fetching Export data from database
	==================================*/
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
	
	function get_based_date(){
		$start = date('N')==1 ? date('Y-m-d') : date('Y-m-d', strtotime('last monday'));
		$end = date('N')==5 ? date('Y-m-d') : date('Y-m-d', strtotime('next friday'));
		$str = '';
		if($this->session->userdata('level') == 'Admin'){
			$str = '';
		}else if($this->session->userdata('level')=='Leader'){
			$str = "AND `divisions`.`division` LIKE '%".$this->session->userdata('division')."%'";
		}
		$range = $this->createDaterange($start, $end);
		$result = array();
		foreach($range as $value){
			$query_weekly = $this->db->query("SELECT id_user, name FROM `reports` JOIN `users` ON `reports`.`id_user` = `users`.`id` JOIN `divisions` ON `divisions`.`id` = `users`.`id_division` WHERE date = '$value' $str GROUP BY id_user");
			$result_weekly = $query_weekly->result_array();
			foreach($result_weekly as $key_weekly => $value_weekly){
				$query_person = $this->db->query("SELECT classification, code, category, description, plan_time, actual_time FROM `reports_view` WHERE date = '$value' AND id_user=$value_weekly[id_user]");
				$result_person = $query_person->result_array();
				$result['data'][$value]['list'][$value_weekly['name']] = $result_person;
				$nr = $query_person->num_rows();
				for($a = $nr; $a<=9; $a++){
					$result['data'][$value]['list'][$value_weekly['name']][] = array('classification'=>'', 'code'=>'', 'category'=>'', 'description'=>'','plan_time'=>'', 'actual_time'=>'');
				}
				$result_person = $query_person->result_array();
			}
		}
		
		/* classification */
		$classification = $this->db->query("SELECT * FROM `classifications` WHERE `flag` = 1");
		$workload = array();
		foreach($classification->result_array() as $row){
			$workload[$row['classification']] = '';
		}
		$result['workload'] = $workload;
		/* end classification */
		return $result;
	}
	
}