<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_request extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		//auth();
		$this->load->library('form_validation');
		$this->load->model('user_request_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
	}

	function index()
	{
		// var_dump($this->session->all_userdata());
		$item['date_range'] = '';
		$item['source'] =site_url('user_request/get_user_request');
		$item['memberRequest'] =site_url('user_request/memberRequest');
		$source['source'] =site_url('user_request/get_user_request');
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', $source, TRUE),
		);
		$this->load->view('template', $data);
	}
	
	function makeRequest()
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
		$a = $this->user_request_model->get_user_status('parent','');
		$request_status_parent = array();
		
		foreach($a as $key => $value){
			$request_status_parent[$value['id_status']] = $value['status'];
		}
		$parent = 1;
		$b = $this->user_request_model->get_user_status('child','AND id_status = 7',$parent);
		$request_status_child = array();
		
		foreach($b as $key => $value){
			$request_status_child[$value['id_status']] = $value['status'];
		}
		
		$data['request_status_parent'] = $request_status_parent;
		$data['request_status_child'] = $request_status_child;
        $this->load->view('form01',$data);
    }
	function requestChange($id_parent){
		
		$a = $this->user_request_model->get_user_status('child',(($id_parent ==1)?'AND id_status = 7':''),$id_parent);
		echo json_encode($a);
	}
	function getDetail($id)
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
		$a = $this->user_request_model->get_request_detail($id);
		$data['data'] = $a;
		
		/////////////////////////
		
		$b = $this->user_request_model->get_user_status('parent','',$a[0]['asp']);
		$request_status_parent = array();
		
		foreach($b as $key => $value){
			$request_status_parent[$value['id_status']] = $value['status'];
		}
		$data['request_status_parent'] = $request_status_parent;
		
		$c = $this->user_request_model->get_user_status('child','',$a[0]['asp']);
		$request_status_child = array();
		
		foreach($c as $key => $value){
			$request_status_child[$value['id_status']] = $value['status'];
		}
		$data['request_status_child'] = $request_status_child;
		
        $this->load->view('form02',$data);
    }
	function statusMember($id, $request = NULL)
    {
		$data['request'] = $request;
		$b = '';
		$c = '';
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
		$a = $this->user_request_model->get_request_detail($id);
		$data['data'] = $a;
		if(($a[0]['ids']) == 10) {
			$b = $this->user_request_model->get_user_status('parent','AND id_status =  10');
		}else{
			$b = $this->user_request_model->get_user_status('parent','','');
		}
		$request_status_parent = array();
		
		foreach($b as $key => $value){
			$request_status_parent[$value['id_status']] = $value['status'];
		}
		$data['request_status_parent'] = $request_status_parent;
		if(($a[0]['ids']) == 10) {
			$c = $this->user_request_model->get_user_status('parent','AND id_status =  10');
		}else{
			$c = $this->user_request_model->get_user_status('child','',$a[0]['asp']);
		}
		$request_status_child = array();
		
		foreach($c as $key => $value){
			$request_status_child[$value['id_status']] = $value['status'];
		}
		$data['request_status_child'] = $request_status_child;
		
		if(($this->session->userdata('level')=='Admin' || $this->session->userdata('level')=='Leader')){
			if($request == 1){
				$this->load->view('form03',$data);
			}else{
				$this->load->view('form02',$data);
			}
		}else{
			$this->load->view('form02',$data);
		}
    }
	function createRequest(){
		date_default_timezone_set("Asia/Jakarta");
		$post = array();
		$json = array();
		foreach($this->input->post() as $key => $value){
			if($value==''){
				$json = array('status'=> 0, 'alert'=>'Please fill the blank form');;
				echo json_encode($json);
				exit();
			}
			else{
				$post[$key] = $value;
			}
		}
		$post['start_time'] = strtotime($this->input->post('start_date').' '.$this->input->post('start_time'));
		$post['end_time'] = strtotime($this->input->post('end_date').' '.$this->input->post('end_time'));
		if($post['start_time'] > $post['end_time']){
			$json = array('status'=> 0, 'alert'=>'Please fill with right time');;
			echo json_encode($json);
			exit();
		}
		// echo print_r($post);
		$this->user_request_model->createRequest($post,true);
	}
	function updateRequest($id){
		date_default_timezone_set("Asia/Jakarta");
		$post = array();
		$json = array();
		foreach($this->input->post() as $key => $value){
			if($value==''){
				$json = array('status'=> 0, 'alert'=>'Please fill the blank form');;
				echo json_encode($json);
				exit();
			}
			else{
				$post[$key] = $value;
			}
		}
		$post['start_time'] = strtotime($this->input->post('start_date').' '.$this->input->post('start_time'));
		$post['end_time'] = strtotime($this->input->post('end_date').' '.$this->input->post('end_time'));
		if($post['start_time'] > $post['end_time']){
			$json = array('status'=> 0, 'alert'=>'Please fill with right time');;
			echo json_encode($json);
			exit();
		}
		// echo print_r($post);
		$this->user_request_model->updateRequest($post,$id);
	}
	function updatePermission($id){
		if($this->session->userdata('level')=='Leader'){
			$data['field_status']= 'leader_status';
			$data['field_by'] = 'leader_by';
		}else if($this->session->userdata('level')=='Admin'){
			$data['field_status'] = 'manager_status';
			$data['field_by'] = 'manager_by';
		}
		$this->user_request_model->statusRequest($id,$data);
	}
	function get_user_request(){
		$data = $this->user_request_model->grid_data();
		echo $data;
	}
	function memberRequest(){
		$data = $this->user_request_model->member_request();
		echo $data;
	}
/* Range week for iterate date in a week
*/	
	
/* End generate range week
*/
	function get_daily_report(){
		$data = $this->daily_report_model->grid_data($this->input->post('date'));
		echo $data;
	}
	function save_report(){
		$total_hour = 0;
		$lastTo = strtotime($_POST['actual_from'][0]);
		$lastFrom = strtotime($_POST['actual_to'][0]);
		$json = array();
		foreach($_POST['actual_from'] as $key => $val){
			// if($val == '' || $_POST['actual_to'][$key]==''){//||!($val == '' && $_POST['actual_to'][$key]=='')){
			if($val == '' Xor $_POST['actual_to'][$key]==''){
				// $json = array('status'=> 0, 'alert'=>$val.' '.$_POST['actual_to'][$key]);
				$json = array('status'=> 0, 'alert'=>'Time should not be empty');
				echo json_encode($json);
				exit;
			}else{
				$timeFrom = strtotime($val);
				$timeTo = strtotime($_POST['actual_to'][$key]);
				if($lastTo!=$timeFrom&&($timeFrom != strtotime('13:00:00'))&&($val == '' Xor $_POST['actual_to'][$key]=='')){
					$json = array('status'=> 0, 'alert'=>'Please fill with right time line');
					echo json_encode($json);
					exit;
				}else{
					$time = $this->setHour($timeTo,$timeFrom);
					$total_hour+=$time;
					$lastTo = $timeTo;
				}
				
			}
			
		}
		if ($total_hour < 8 || $total_hour > 8)
		{
			$json = array('status'=> 0, 'alert'=>'Total Hours must be at 8 hours');
		}
		else
		{
			$json = $this->daily_report_model->save_report_data($_POST);   
			
		}
		echo json_encode($json);
	}
	
	function setHour($timeTo,$timeFrom){
		$breakStart = strtotime('12:00:00');
		$breakEnd = strtotime('13:00:00');
		$res = round(($timeTo-$timeFrom)/3600,2);
		if(($timeTo>=$breakEnd&&$timeFrom<=$breakStart)){
			$res -= 1;
		}
		return $res;
	}
	
	function set_job_code(){
		$id = $this->input->post('job_id');
		$arr = $this->daily_report_model->get_job($id);
		$new_arr = array();
		if(count($arr) > 0){
			foreach($arr as $row){
				$new_arr[$row['id']] = $row['code'];
			}
		}
		else{
			$new_arr[0] = "--No Data--";
		}
		echo json_encode($new_arr);
	}
	
	function get_ajax_edit(){
		$id = $this->input->post('id');
		$class_row = $this->input->post('class_row');
		$dates = $this->input->post('date');
		$data = array();
		$id_classification = $this->daily_report_model->get_class_by_id($id);
		$get_permission = $this->daily_report_model->get_permission($id,$dates);
		$json = array();
		if(!$get_permission){
			switch($class_row){
				case 'id_classification': 
					$selected = $this->daily_report_model->get_selected_class($id);
					$input = $this->get_classification($selected);
					$data['input'] = $input[1];
					break;
				case 'id_job': 
					$selected = $this->daily_report_model->get_selected_job($id);
					$data['input'] = $this->get_job($id_classification,$selected);
					break;
				case 'id_category': 
					$selected = $this->daily_report_model->get_selected_category($id);
					$data['input'] = $this->get_category($selected);
					break;
				case 'description': 
					$data['input'] = $this->get_description($id);
					break;
			}
			$json = $data;
			$json['status'] = 1;
		}
		else{
			$json = array('status'=>0, 'alert'=>'You dont have permission to change value');
		}
		
		
		echo json_encode($json);
		// echo $get_permission;
	}
	function post_inline(){
		$post = $this->input->post();
		// $_check = $this->check_job_code();
		if($post['field'] == 'id_classification'){
			$this->daily_report_model->post_inline($post);
			$result = array('status'=>2, 'alert'=>'You must change job code');
		}else{
			$result = $this->daily_report_model->post_inline($post);
		}
		echo json_encode($result);
	}
	function get_description($id){
		$value = $this->daily_report_model->get_description($id);
		$input = '<textarea name="description[]" class="form-control editable">'.$value.'</textarea>';
		return $input;
	}
	function get_new_report(){
		$classification = $this->get_classification();
		$job = $this->get_job($classification[0]);
		$category = $this->get_category();
		echo '<tr class="odd">'.
		'<td>'.$classification[1].'</td>'.
		'<td>'.$job.'</td>'.
		'<td class="">'.$category.'</td>'.
		'<td class=""><textarea name="description[]" class="form-control"></textarea></td>'.
		'<td class="">0</td>'.
		'<td class="action">'.
		'<div class="timepicker-wrap">'.
		'<input type="text" class="timepicker" name="actual_from[]">'.
		'<span>To</span>'.
		'<input type="text" class="timepicker" name="actual_to[]">'.
		'</div>'.
		'</tr>';
		// echo print_r($classification);
	}
	function get_classification($selected = ""){
		$arr = $this->daily_report_model->get_classification();
		$new_arr = array();
		foreach($arr as $row){
			$new_arr[$row['id']] = $row['classification'];
		}
		$new_sel = ($selected) ? $selected : null;
		return array($arr[0]['id'],form_dropdown('id_classification[]', $new_arr,$new_sel,' class="form-control classification"'));
	}
	function get_job($id,$selected = ""){
		$arr = $this->daily_report_model->get_job($id);
		$new_sel = ($selected) ? $selected : null;
		$new_arr = array();
		if(count($arr) > 0){
			foreach($arr as $row){
				$new_arr[$row['id']] = $row['code'];
			}
		}
		else{
			$new_arr[0] = "--No Data--";
		}
		return form_dropdown('id_job[]', $new_arr,$new_sel,' class="form-control job_code"');
	}
	function get_category($selected = ""){
		$arr = $this->daily_report_model->get_category();
		$new_arr = array();
		$new_sel = ($selected) ? $selected : null;
		foreach($arr as $row){
			$new_arr[$row['id']] = $row['category'];
		}
		return form_dropdown('id_category[]', $new_arr,$new_sel,' class="form-control"');
	}
	function grid_data()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->work_categories_model->grid_data();
	}
    
    
	
	function upload_file()
    {
        $config['upload_path'] = './temp_upload/weekly_plan/';
        $config['allowed_types'] = '*';
                
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile'))
        {
            $json = array('status'=>0, 'alert'=>$this->upload->display_errors());
            $this->session->set_flashdata('alert', 'Insert failed. Please check your file, only .xls file allowed.');
        }
        else
        {
            $data = array('error' => false);
            $upload_data = $this->upload->data();
            $this->load->library('excel_reader');
            $this->excel_reader->setOutputEncoding('CP1251');
            $file =  $upload_data['full_path'];
            $this->excel_reader->read($file);
            error_reporting(E_ALL ^ E_NOTICE);            
            
            // Sheet 1
            $data = $this->excel_reader->sheets[1] ;
            $dataexcel = Array();
            for ($i = 1; $i <= $data['numRows']; $i++)
            {
                if($data['cells'][$i][1] == '') break;
                $dataexcel[$i-1]['date']                = $data['cells'][$i][1];
                $dataexcel[$i-1]['name']                = $data['cells'][$i][2];
                $dataexcel[$i-1]['work_classification'] = $data['cells'][$i][3];
                $dataexcel[$i-1]['job_code']            = $data['cells'][$i][4];
                $dataexcel[$i-1]['work_category']       = $data['cells'][$i][5];
                $dataexcel[$i-1]['description']         = $data['cells'][$i][6];
                $dataexcel[$i-1]['plan']                = $data['cells'][$i][7];
            }
            
            //cek data
            $check = $this->daily_report_model->search_duplicate($dataexcel);
            if (count($check) > 0)
            {
              $this->daily_report_model->update_plan($dataexcel);
                $json = array('status'=>1, 'alert'=>'Update plan list success');
            }
            else
            {
              $this->daily_report_model->insert_plan($dataexcel);
              $json = array('status'=>1, 'alert'=>'Insert plan list success');
            }
        }
        echo json_encode($json);
    }
	
    function test()
    {
        echo $this->daily_report_model->get_user_id();
    }
    
	function call_form($id = FALSE)
    {
        if($id)
        {
            $data = (array) $this->daily_report_model->get_data($id);
        }
        else
        {
            $data = array(
                'id' => FALSE,
                'category' => '',
                'description' => ''
            );
        }
        $data['opt_classifications'] = $this->daily_report_model->opt_classifications();
        $data['opt_categories'] = $this->daily_report_model->opt_categories();
        $this->load->view('form01', $data);
    }

    function get_match_codes($id)
    {
        $opt_codes = $this->daily_report_model->opt_job_codes($id);
        
        if($opt_codes)
        {
            echo form_dropdown('event_result', $opt_codes, '', 'class="form-control"');
        }
        else
        {
            echo form_dropdown('event_result', array(''=>'Job Codes'), '', 'class="form-control"');
        }        
    }
	
	function submit_data()
    {
       $this->form_validation->set_rules('category', 'category', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'category' => $this->input->post('category'),
                'description' => $this->input->post('description')
            );
            $this->work_categories_model->save_data($save);
            $json = array('status'=> 1, 'alert'=>'Data has been added');
        }
        echo json_encode($json);
    }
    
    function delete_data($id)
	{
		$this->work_categories_model->delete_data($id);
		redirect('work_categories');
	}

}