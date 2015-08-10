<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_report_model extends CI_Model {

	function grid_data($date)
	{
		$this->load->library('datatables');
		$this->datatables->select('classification,'.
		'CASE '.
		'WHEN (class_id = "2") THEN CONCAT(job_name,":",job_code) '.
		'WHEN (class_id = "3") THEN CONCAT(job_name,"-",job_code) '.
		'ELSE  code '.
		'END  as code, category, description, plan_from, plan_to, category_id, class_id, job_id, actual_from, actual_to, id',FALSE)
			->from('reports_view')
			->order_by('id')
			->where('id_user',$this->session->userdata('id'))
			->where('date',$date)
			->unset_column('plan_from')
			->unset_column('plan_to')
			->unset_column('actual_from')
			->unset_column('actual_to')
			->unset_column('id')
			->unset_column('class_id')
			->unset_column('job_id')
			->unset_column('category_id')
			->add_column('Planned Hours', $this->get_input('$1', '$2', '$3','$4','$5','$6','$7' ,$date,'plan'), 'plan_from,plan_to,id,class_id,job_id,category_id,description' )
			->add_column('Actual Hours', $this->get_input('$1', '$2', '$3','$4','$5','$6','$7' ,$date), 'actual_from,actual_to,id,class_id,job_id,category_id,description' )
			->add_column('DT_ROW_ID', 'row-$1', 'id');
		return $this->datatables->generate();
	}

	function get_input($from, $to, $id, $class_id, $job_id, $category, $description, $date,$type='actual')
	{
		$today = date('Y-m-d');
		
		if($type=='actual'){
			if($date==$today){
				$input = 	"<div class='col-lg-12'>
			<div class='col-lg-5'>
				<input type='text' class='timepicker form-control centered text-center' name='actual_from[]' value='$from'>
            </div>
			<div class='col-lg-2 text-center'><span>To</span></div>
            <div class='col-lg-5 centered text-center'>
                <input type='text' class='timepicker form-control centered text-center' name='actual_to[]' value='$to'>
				<input type='hidden' name='id_report[]' value='".$id."' />
				<input type='hidden' name='id_classification[]' value='".$class_id."' />
				<input type='hidden' name='id_job[]' value='".$job_id."' />
				<input type='hidden' name='id_category[]' value='".$category."' />
				<input type='hidden' name='description[]' value='".$description."' />
            </div>
		</div>";
			}
			else{
				$input = $from . ' - '. $to;
			}
		}
		else{
			$input = $from . ' - '. $to;
		}
		return $input;
	}
	function get_class_by_id($id){
		$query = $this->db->query("SELECT id_classification FROM reports WHERE id = $id");
		$array = $query->result_array();
		return $array['0']['id_classification'];
	}
	function get_permission($id,$date){
		$this->db->select('plan_time')
            ->where('id', $id)
            ->where('date', $date);
        $query = $this->db->get('reports_view')->row_array();   
        return ($query['plan_time']) ? TRUE : FALSE;
		// return $this->db->last_query();
	}
	function get_description($id){
		$this->db->select('description')
            ->where('id', $id);
        $query = $this->db->get('reports')->row_array();   
        return ($query['description']);
	}
	function post_inline($post){
		$query = $this->db->query("UPDATE reports SET $post[field]='$post[value]' WHERE id='$post[id]'");
		if($query){
			return array('status'=> 1, 'alert'=>'Data successfully Added');
		}else{
			return array('status'=> 0, 'alert'=>'Saving data failed');
		}
	}
	function get_selected_class($id){
		$this->db->select('id_classification')
            ->where('id', $id);
        $query = $this->db->get('reports')->row_array();   
        return $query['id_classification'];
	}
	function get_selected_job($id){
		$this->db->select('id_job')
            ->where('id', $id);
        $query = $this->db->get('reports')->row_array();   
        return $query['id_job'];
	}
	function get_selected_category($id){
		$this->db->select('id_category')
            ->where('id', $id);
        $query = $this->db->get('reports')->row_array();   
        return $query['id_category'];
	}
	
	
    function save_report_data(){
		foreach($this->input->post('id_category') as $set => $row){
			
			if($this->input->post('id_report')[$set] != ""){
				$save = array(
								'id_category' => $this->input->post('id_category')[$set],
								'id_classification' => $this->input->post('id_classification')[$set],
								'description' => $this->input->post('description')[$set],
								'id_job' => $this->input->post('id_job')[$set],
								'actual_from' => $this->input->post('actual_from')[$set],
								'actual_to' => $this->input->post('actual_to')[$set]
				);
				$criteria = array(
								'id' => $this->input->post('id_report')[$set]
				);
				$a = 	$this->db->update('reports', $save,$criteria);
				if(!$a){
					return array('status'=> 0, 'alert'=>'Failed when update actual time');
				}
			}else{
				$save = array(
								'id_user' => $this->input->post('id_user'),
								'date' => date('Y-m-d'),
								'id_category' => $this->input->post('id_category')[$set],
								'id_classification' => $this->input->post('id_classification')[$set],
								'description' => $this->input->post('description')[$set],
								'id_job' => $this->input->post('id_job')[$set],
								'actual_from' => $this->input->post('actual_from')[$set],
								'actual_to' => $this->input->post('actual_to')[$set]
				);
				
				$b = $this->db->insert('reports', $save); 
				if(!$b){
					return array('status'=> 0, 'alert'=>'Failed when create new reports');
				}
			}
		}
		return array('status'=> 1, 'alert'=>'Data Successfully saved');
		// return $get_id;
	}
	function get_classification(){
		$a = $this->db->query("SELECT * FROM classifications WHERE flag = 1");
		return $a->result_array();
	}
	function get_job($id){
		$a = $this->db->query('SELECT *,'.
		'CASE '.
		'WHEN (id_classification = "2") THEN CONCAT(job_name,":",job_code) '.
		/*'WHEN (id_classification = "3") THEN CONCAT(job_name,"-",job_code) '.*/
		'ELSE  project_mng_code '.
		'END  as code FROM jobs where id_classification = '.$id.' AND flag = 1');
		return $a->result_array();
	}
	function get_category(){
		$a = $this->db->query("SELECT * FROM categories WHERE flag = 1");
		return $a->result_array();
	}
	function get_daily_report(){
		$query = $this->db->query('SELECT id from reports_view WHERE id_user = '.$this->session->userdata('id').' AND date = "'.date('Y-m-d').'"');
		return $query->result_array();
		// return $this->db->last_query();
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
        $result = $this->db->get('classifications');
        
        $data[''] = 'Work Classifications';
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['classification'];
        }
        return $data;
    }
    
    function opt_job_codes($id)
    {
        $result = $this->db->get_where('jobs', array('id_classification'=>$id));
        
        $data[''] = 'Job Codes';
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['project_mng_code'];
        }
        return $data;
    }
    
    function opt_categories()
    {
        $result = $this->db->get('categories');
        
        $data[''] = 'Work Categories';
        foreach ($result->result_array() as $row)
        {
            $data[$row['id']] = $row['category'];
        }
        return $data;
    }

	function get_data($id)
	{
		$query = $this->db->get_where('work_categories', array('id'=>$id));
		return $query->row_array();
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
            ->like('name', $name);
        $query = $this->db->get('users')->row_array();   
        return $query['id'];
    }
    
    function get_classification_id($classification)
    {
        $this->db->select('id')
            ->like('classification', $classification);
        $query = $this->db->get('classifications')->row_array();   
        return $query['id'];
    }
    
    function get_job_id($code)
    {
        $this->db->select('id')
            ->like('project_mng_code', $code);
        $query = $this->db->get('jobs');
        if($query->num_rows() > 0)
        {
            $result = $query->row_array();
            return $result['id'];    
        }
        else
        {
            return 'null';    
        }   
        
    }
    
    function get_category_id($category)
    {
        $this->db->select('id')
            ->like('category', $category);
        $query = $this->db->get('categories')->row_array();   
        return $query['id'];
    }
    
    function insert_plan($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++)
        {
            $data = array(              
                'date' => date('Y-m-d',strtotime('-1 day',strtotime($dataarray[$i]['date']))),
                'id_user' => $this->get_user_id($dataarray[$i]['name']),
                'id_classification' => $this->get_classification_id($dataarray[$i]['work_classification']),
                'id_job' => $this->get_job_id($dataarray[$i]['id_job']),
                'id_category' => $this->get_job_id($dataarray[$i]['work_category']),
                'description' => $dataarray[$i]['description'],
                'plan_time' => $dataarray[$i]['plan']
            );
            $this->db->insert('reports', $data);
        }
    }
    
    function update_plan($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++)
        {
            $data = array(
                'date' => date('Y-m-d',strtotime('-1 day',strtotime($dataarray[$i]['date']))),
                'id_user' => $this->get_user_id($dataarray[$i]['name']),
                'id_classification' => $this->get_classification_id($dataarray[$i]['work_classification']),
                'id_job' => $this->get_job_id($dataarray[$i]['id_job']),
                'id_category' => $this->get_job_id($dataarray[$i]['work_category']),
                'description' => $dataarray[$i]['description'],
                'plan_time' => $dataarray[$i]['plan']
            );
            $this->db->update('reports', $data, array('date'=>$data['date'], 'id_user' =>$data['id_user']));   
        }
    }

    function search_duplicate($dataarray)
    {
        for($i=1; $i<count($dataarray); $i++)
        {
            $search = array(
                'date' => date('Y-m-d',strtotime('-1 day',strtotime($dataarray[$i]['date']))),
                'id_user'=>$this->get_user_id($dataarray[$i]['name'])
            );
        }                
        $data = array();
        $this->db->limit(1);
        $Q = $this->db->get_where('reports', $search);
        if($Q->num_rows() > 0)
        {
            $data = $Q->row_array();
        }
        $Q->free_result();
        return $data;
    }

	function delete_data($id)
	{
		$this->db->delete('work_categories', array('id'=>$id));
		$this->session->set_flashdata('alert', '<i class="icon-info-sign"></i> Success! Data has been deleted');
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
	
	function get_based_date(){
		$start = date('N')==1 ? date('Y-m-d') : date('Y-m-d', strtotime('last monday'));
		$end = date('N')==5 ? date('Y-m-d') : date('Y-m-d', strtotime('next friday'));
		$str = '';
		$str = "AND `reports`.`id_user` = ".$this->session->userdata('id');
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