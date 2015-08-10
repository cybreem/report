<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Weekly_reports extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		//$this->load->helper('auth');
		//auth();
		$this->load->library('form_validation');
		$this->load->model('weekly_report_model');
       /*  if(!$this->session->userdata('logged_in'))
        {
            show_404();
        } */
	}

	function index()
	{
	    $item['source'] =  site_url('weekly_reports/grid_data');
		$date_range = $this->rangeWeek(date('Y-m-d'));
		$item['date_range'] = $this->createDaterange($date_range['start'],$date_range['end']);
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', '', TRUE)
		);
		$this->load->view('template', $data);
	}
	
	function grid_data()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->weekly_report_model->grid_data($this->input->post('date'));
	}
    
    function call_form_upload()
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
        $this->load->view('form02');
    }

    function upload_file()
    {
        $config['upload_path'] = './temp_upload/weekly_plan/';
        $config['allowed_types'] = '*';                
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile'))
        {
            $json = array('status'=>0, 'alert'=>$this->upload->display_errors());
            //$this->session->set_flashdata('alert', 'Insert failed. Please check your file, only .xls file allowed.');
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
            $data = $this->excel_reader->sheets[0];
            $dataexcel = Array();
            for ($i=1; $i<=$data['numRows']; $i++)
            {
                if($data['cells'][$i][1] == '') break;
                $dataexcel[$i-1]['date']                = format_ymd($data['cells'][$i][1]);
                $dataexcel[$i-1]['name']                = $data['cells'][$i][2];
                $dataexcel[$i-1]['work_classification'] = $data['cells'][$i][3];
                $dataexcel[$i-1]['job_code']            = $data['cells'][$i][4];
                $dataexcel[$i-1]['work_category']       = $data['cells'][$i][5];
                $dataexcel[$i-1]['description']         = $data['cells'][$i][6];
                $dataexcel[$i-1]['plan_from']           = $data['cells'][$i][7];
                $dataexcel[$i-1]['plan_to']             = $data['cells'][$i][8];
            }
            
            //cek data
            $check = $this->weekly_report_model->search_duplicate($dataexcel);
			
            if ($check > 0)
            {
              $ss = $this->weekly_report_model->update_plan($dataexcel);
              $json = array('status'=>1, 'alert'=>'Update plan list success');
            }
            else
            {
               $ss = $this->weekly_report_model->insert_plan($dataexcel);
               $json = array('status'=>1, 'alert'=>'Insert plan list success');
            }
        }
        echo json_encode($json);
    }

    function get_sample_data()
    {
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Weekly Plan');
        $members = $this->weekly_report_model->get_member();
        $heading = array('Date', 'Member Name', 'Work Classification', 'Job Code', 'Work Category', 'Work Description', 'Start', 'End');
        //Loop Heading
        $rowNumberH = 1;
        $colH = 'A';             
        foreach($heading as $h)
        {
            $this->excel->getActiveSheet()->getStyle($colH.$rowNumberH)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->setCellValue($colH.$rowNumberH, $h);
            $colH++;    
        }
        $loop    = 2;
        foreach($members as $row)
        {
        	$this->excel->getActiveSheet()->getStyle('A'.$loop)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
            $this->excel->getActiveSheet()->setCellValue('A'.$loop, date('d/m/Y', strtotime('next monday')));
            $this->excel->getActiveSheet()->setCellValue('B'.$loop, $row['name']);
            $this->excel->getActiveSheet()->setCellValue('C'.$loop, '');
            $this->excel->getActiveSheet()->setCellValue('D'.$loop, '');
            $this->excel->getActiveSheet()->setCellValue('E'.$loop, '');
            $this->excel->getActiveSheet()->setCellValue('F'.$loop, '');
            $this->excel->getActiveSheet()->setCellValue('G'.$loop, '');
            $this->excel->getActiveSheet()->setCellValue('H'.$loop, '');
            $loop++;
        }
        $filename = 'sample_'.date('Ymd').'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    function call_form($id = FALSE, $date = false)
    {
        $data['name'] = $this->weekly_report_model->get_name($id);
        $data['date'] = date('d/m/Y',strtotime($date));
        $data['dateHidden'] = date('Y-m-d',strtotime($date));
		$data['id'] = $id;
		$data['category'] = '';
		$data['description'] = '';		
		$data['plan'] = $this->weekly_report_model->get_report($id, $date);
		foreach($data['plan'] as $set)
		{
			$id= $set->id;
			$classification = $set->classification;
			$category = $set->category;
			$data['classification_id'.$id] = $this->weekly_report_model->get_classification_id($classification);
			$data['code'.$id] = $this->get_codes($data['classification_id'.$id],($set->id_job));
			$data['category_id'.$id] = $this->weekly_report_model->get_category_id($category);
		}
		
        $data['opt_classifications'] = $this->weekly_report_model->opt_classifications();		
        $data['opt_categories'] = $this->weekly_report_model->opt_categories();		
        $this->load->view('form01', $data);
    }

    function get_match_codes($id = false)
    {
        $opt_codes = $this->weekly_report_model->opt_job_codes($id);
        
        if($opt_codes)
        {
            echo form_dropdown('job_code[]', $opt_codes, '', 'class="form-control"');
        }
        else
        {
            echo form_dropdown('job_code[]', array(''=>'Job Codes'), '', 'class="form-control"');
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
	
	function update_data()
    {
			$sum_plan = 0;
			foreach($this->input->post('plan_from') as $set => $row){
				$plan_from = strtotime($this->input->post('plan_from')[$set]);
				$plan_to = strtotime($this->input->post('plan_to')[$set]);
				$sum_plan = $sum_plan + ($plan_to-$plan_from);
				$from_time = strtotime($this->input->post('plan_from')[$set]);
				$to_time = strtotime($this->input->post('plan_to')[$set]);
				if($from_time<=strtotime("12:00:00") && $to_time>=strtotime("13:00:00")){
					$decrease = strtotime("01:00:00")-strtotime("00:00:00");
				}
			}
			if(isset($decrease)){
				$sum_plan = ($sum_plan - $decrease) / 3600;
			}else{
				$sum_plan = $sum_plan / 3600;
			}
			if($sum_plan>8 || $sum_plan<8){
				echo "false".$sum_plan;
			}else{
			
				foreach($this->input->post('category') as $set => $row){
				
					if($this->input->post('id_plan')[$set] != ""){
						$save = array(
										'id_category' => $this->input->post('category')[$set],
										'id_classification' => $this->input->post('classification')[$set],
										'description' => $this->input->post('description')[$set],
										'plan_from' => $this->input->post('plan_from')[$set],
										'plan_to' => $this->input->post('plan_to')[$set],
										'id_job' => $this->input->post('job_code')[$set],
										'actual_from' => $this->input->post('actual_from')[$set],
										'actual_to' => $this->input->post('actual_to')[$set]
						);
						$criteria = array(
										'id' => $this->input->post('id_plan')[$set]
						);
						$query = $this->weekly_report_model->update_data($save,$criteria);
					}else{
						$save = array(
										'id_user' => $this->input->post('id'),
										'date' => $this->input->post('dateHidden'),
										'id_category' => $this->input->post('category')[$set],
										'id_classification' => $this->input->post('classification')[$set],
										'description' => $this->input->post('description')[$set],
										'plan_from' => $this->input->post('plan_from')[$set],
										'plan_to' => $this->input->post('plan_to')[$set],
										'id_job' => $this->input->post('job_code')[$set],
										'actual_from' => $this->input->post('actual_from')[$set],
										'actual_to' => $this->input->post('actual_to')[$set]
						);
						$query = $this->weekly_report_model->insert_data($save);
					}
				}
				echo"true";
			}
    }
    
    function delete_data($id)
	{
		$this->weekly_report_model->delete_data($id);
		echo "true";
	}
	
    function get_codes($id,$job)
    {
        $opt_codes = $this->weekly_report_model->opt_job_codes($id,$job);
        
        if($opt_codes)
        {
            return form_dropdown('job_code[]', $opt_codes, $job, 'class="form-control"');
        }
        else
        {
            return form_dropdown('job_code[]', array(''=>'Job Codes'), '', 'class="form-control"');
        }        
    }
	
	function rangeWeek($datestr) {
		date_default_timezone_set(date_default_timezone_get());
		$dt = strtotime($datestr);
		$res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
		$res['end'] = date('N', $dt)==5 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next friday', $dt));
		return $res;
    }
	
	function call_atendance($id,$date) {
		$data = array('date' => $date,
						'id_user' => $id
						);
		$set['set'] =  $this->weekly_report_model->call_atendance($data);
		array_push($set['set'],$data);
		$this->load->view('form03', $set);
    }

	function update_attendance()
    {
				$save = array(
								'attend' => $this->input->post('attend'),
								'id_user' => $this->input->post('id_user'),
								'date' => $this->input->post('date'),
								'description' => $this->input->post('description')
				);
				$criteria = array(
								'id' => $this->input->post('id_attend'),
								'id_user' => $this->input->post('id_user'),
								'date' => $this->input->post('date')
				);
				$query = $this->weekly_report_model->update_attendance($save,$criteria);
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
		$data = $this->weekly_report_model->get_based_date();
		//echo print_r($data);
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
				}			}
			
			
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
				$letter = PHPExcel_Cell::stringFromColumnIndex($workload_value);
				$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(12);
				$workload_field++;
			}
			
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
	function testing(){
		$data = $this->weekly_report_model->get_based_date();
		echo print_r($data['2015-04-20']);
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
	
	
}