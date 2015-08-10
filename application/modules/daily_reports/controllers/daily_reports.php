<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_reports extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		//auth();
		$this->load->library('form_validation');
		$this->load->model('daily_report_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
	}

	function index()
	{
	    $item['opt_members'] = $this->daily_report_model->opt_members();
		$item['opt_classifications'] = $this->daily_report_model->opt_classifications();
        $item['opt_categories'] = $this->daily_report_model->opt_categories();
		$date_range = $this->rangeWeek(date('Y-m-d'));
		$item['date_range'] = $this->createDaterange($date_range['start'],$date_range['end']);
		$source['source'] =site_url('daily_reports/get_daily_report'); 
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', $source, TRUE),
		);
		$this->load->view('template', $data);
	}
	
/* Range week for iterate date in a week
*/	
	
	function rangeWeek($datestr) {
		date_default_timezone_set(date_default_timezone_get());
		$dt = strtotime($datestr);
		$res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
		$res['end'] = date('N', $dt)==5 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next friday', $dt));
		return $res;
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
		$json = $_POST;
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
					$time = $this->setHour($timeTo, $timeFrom);
					$total_hour+=$time;
					$lastTo = $timeTo;
				}
				
			}
			
		}
		if ($total_hour < 28800 || $total_hour > 28800)
		{
			$json = array('status'=> 0, 'alert'=>'Total Hours must be at 8 hours');
		}
		else
		{
			$json = $this->daily_report_model->save_report_data();   
			
		}
		echo json_encode($json);
	}
	
	function setHour($timeTo,$timeFrom)
	{
		$breakStart	= strtotime('12:00:00');
		$breakEnd	= strtotime('13:00:00');
		$res		= $timeTo - $timeFrom;
		if(($timeTo >= $breakEnd && $timeFrom <= $breakStart))
		{
			$res -= 3600;
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
"<div class='col-lg-12'>
			<div class='col-lg-5'>
				<input type='text' class='timepicker form-control centered text-center' name='actual_from[]' value=''>
            </div>
			<div class='col-lg-2 text-center'><span>To</span></div>
            <div class='col-lg-5 centered text-center'>
                <input type='text' class='timepicker form-control centered text-center' name='actual_to[]' value=''>
				<input type='hidden' name='id_report[]' value='' />
            </div>
			<span class='remove'><a href='javascript:void(0)' id='a' title='delete'><i class='fa fa-minus-circle'></i></a></span>
		</div>
		<script>
		$('.remove').click(function(){
			$(this).parent().parent().parent().remove();
			console.log('aa');
		});
		</script>
		".		'</td></tr>';
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
		$new_arr[0] = "Select Job Code";
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
    
    function call_form_upload()
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
        $this->load->view('form01');
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
	
	function export(){
		global $objPHPExcel;
		$this->load->library('excel');
		$objPHPExcel = $this->excel;
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->setActiveSheetIndex(0)->getSheetView()->setZoomScale(85);
		$objPHPExcel->setActiveSheetIndex(0)->setShowGridlines(false);
		
		/* Set Header */
		$objPHPExcel->getActiveSheet()->setTitle($this->session->userdata('division')!=""?$this->session->userdata('division'):"All".'_Unit');
		$unit = ($this->session->userdata('division')) ? $this->session->userdata('division') : 'All';
		$objPHPExcel->getActiveSheet()->setCellValue('B1', $unit.' Unit');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('B1:C1');

		$objPHPExcel->getActiveSheet()->setCellValue('F1', $this->get_week());
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->mergeCells('F1:H1');
		/* End Header */
		$objPHPExcel->getActiveSheet()->getStyle('B2');
		/* Set Data */
		$data = $this->daily_report_model->get_based_date();
		// echo print_r($data);
		$col = 1;
		$row = 2;
		foreach($data['data'] as $key => $value){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key);
			$coldate = PHPExcel_Cell::stringFromColumnIndex($col);
			$this->cellCustom($coldate.$row, NULL,array('size'=>'11','bold'=>true));
			$cols = $col;
			/* Header Table */
			$field = array('Member Name', 'Work Classification', 'Job Code', 'Work Category', 'Work Description', 'Planned Hours', 'Actual Hours');
			$header_row = $row+1;
			foreach($field as $valuef){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $header_row, $valuef);
				$colheader = PHPExcel_Cell::stringFromColumnIndex($col);
				$this->cellCustom($colheader.$header_row, '95B3D7',array('size'=>'11','bold'=>true));
				$this->cellBorder($colheader.$header_row, 'allborders',PHPExcel_Style_Border::BORDER_THIN);
				$col++;
			}
			
			$c1 = PHPExcel_Cell::stringFromColumnIndex($cols);
			$c2 = PHPExcel_Cell::stringFromColumnIndex($col-1);
			$this->cellBorder($c1.$header_row.':'.$c2.$header_row, 'outline',PHPExcel_Style_Border::BORDER_MEDIUM);
			/* End Header Table */
			
			/* Content */
			
			$content_row = $header_row + 1;
			$cr = $content_row;
			$oe = 1;
			foreach($value['list'] as $name => $value_field){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cols, $content_row, $name);
				$field_row = $content_row;
				$colss = $cols+1;
				$a = 0;
				foreach($value_field as $id_key => $fl){
					
					$colsss = $colss;
					if($fl['classification']!=''){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cols, $content_row, $name);
					}
					foreach($fl as $r => $v){
						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colsss, $content_row, $v);
						$colsss++;
					}
					$content_row++;
					$a++;
				}
				$cn = PHPExcel_Cell::stringFromColumnIndex($colss-1);
				if($oe ==1){
					
					$this->cellCustom($cn.$field_row.':'.$cn.($content_row-1), '366092',array('size'=>11,'bold'=>true, 'color' => array('rgb' => 'ffffff')));
					$oe =0;
				}
				else{
					$this->cellCustom($cn.$field_row.':'.$cn.($content_row-1), '538DD5',array('size'=>11,'bold'=>true, 'color' => array('rgb' => 'ffffff')));
					$oe =1;
					
				}
				$ctx = PHPExcel_Cell::stringFromColumnIndex($colsss-1);
				$this->cellBorder($c1.$field_row.':'.$ctx.($content_row-1), 'inside',PHPExcel_Style_Border::BORDER_THIN);
				$this->cellBorder($c1.$field_row.':'.$ctx.($content_row-1), 'outline',PHPExcel_Style_Border::BORDER_MEDIUM);
				
				$letter = PHPExcel_Cell::stringFromColumnIndex($colsss+2);
				$letterCol = PHPExcel_Cell::stringFromColumnIndex($cols+4);
				for($column = $c1; $column !=$letter; $column++){
					if($column==$letterCol){
						$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(65);
					}else{
						$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
					}
				}
			}
			
			
			/* End Content */ 
			
			/* Total Workload */
			$workload_field = $header_row+1;
			$workload_title = $col+1;
			$workload_value = $col+2;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($workload_title, $header_row, 'Project Workload');
			
			$colString1 = PHPExcel_Cell::stringFromColumnIndex($workload_title);
			$colString2 = PHPExcel_Cell::stringFromColumnIndex($workload_title+1);
			$this->cellCustom($colString1.$header_row, 'FCD5B4',array('size'=>'11','bold'=>true));
			$colString2 = PHPExcel_Cell::stringFromColumnIndex($workload_value);
			
			$objPHPExcel->getActiveSheet()->mergeCells($colString1 .$header_row.':'.$colString2 .$header_row);
			
			foreach($data['workload'] as $wk => $wv){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($workload_title, $workload_field, $wk);
				
				$leter1 = PHPExcel_Cell::stringFromColumnIndex(($workload_title-7)).$cr;
				$leter2 = PHPExcel_Cell::stringFromColumnIndex($workload_title-7).($content_row-1);
				$leter3 = PHPExcel_Cell::stringFromColumnIndex($workload_title).$workload_field;
				$leter4 = PHPExcel_Cell::stringFromColumnIndex(($workload_title-2)).$cr;
				$leter5 = PHPExcel_Cell::stringFromColumnIndex($workload_title-2).($content_row-1);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($workload_value, $workload_field, "=SUMIF(".$leter1.":".$leter2.",".$leter3.",".$leter4.":".$leter5.")");
				$workload_field++;
			}
			$letter = PHPExcel_Cell::stringFromColumnIndex($workload_value);
			$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(12);
			
			$this->cellBorder($colString1.$header_row.':'.$colString2.($workload_field-1), 'allborders',PHPExcel_Style_Border::BORDER_THIN);
			/* End Total Workload */
			
			$col+=4;
		}
		/* End Header */
		$filename='Weekly_reports_'.$this->session->userdata('name').'_'.$this->get_week('title').'.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
		$objWriter->save('php://output');
	}
    
	function get_week($style=''){
		if($style=="title"){
			$start = date('N')==1 ? date('ymd') : date('ymd', strtotime('last monday'));
			$end = date('N')==5 ? date('ymd') : date('ymd', strtotime('next friday'));
			return $start.'_'.$end;
		}else{
			$start = date('N')==1 ? date('d/m/Y') : date('d/m/Y', strtotime('last monday'));
			$end = date('N')==5 ? date('d/m/Y') : date('d/m/Y', strtotime('next friday'));
			return $start.'-'.$end;
		}
	}
	
	function cellCustom($cells,$color, $font = array()){
		global $objPHPExcel;
		if($color){
			$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array(
					 'rgb' => $color
				)
			));
		}
		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFont()->applyFromArray($font);
		
	}
    
    
	function cellBorder($range,$type, $style){
		// echo $range;
		global $objPHPExcel;
		$styleArray = array(
		  'borders' => array(
			$type => array(
			  'style' => $style
			)
		  )
		);

		$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($styleArray);
		unset($styleArray);
	}
    

}