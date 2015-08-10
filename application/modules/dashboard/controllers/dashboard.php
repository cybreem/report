<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct()
    {
        parent:: __construct();
        $this->load->library('excel');
		$this->load->helper('form');
		$this->load->helper('auth');
		//auth();
		$this->load->library('form_validation');
        $this->load->model('dashboard_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
    }
	
	/* Login Modal */
    
	function login_modal(){
		$this->load->view('login_modal');
	}
	
	/* End Modal */
	function get_modal($id){
		$this->load->model('auth/auth_model','am');
		$a = $this->am->get_modal($id);
		return $a;
	}
	
	function index()
	{
		$item['modal'] = $this->get_modal($this->session->userdata('id'));
	    if($this->session->userdata('level')!="Member"){
			$item['check_privileges'] =  $this->check_privileges();
		}else{
			$item['check_privileges'] = array();
		}
		$item['global_chart'] =  site_url('dashboard/global_chart');
		$item['workload_all'] =  site_url('dashboard/workload_all');
		$item['workload_all_detail'] =  site_url('dashboard/workload_all_detail');
		$item['workload_all_detail2'] =  site_url('dashboard/workload_all_detail2');

		$item['members'] = $this->dashboard_model->get_members();
		$item['operating_day'] = $this->operating_day();
		$item['operating_weeks'] = $this->operating_weeks();
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', '', TRUE)
		);
		$this->load->view('template', $data);
	}
	function div_list(){
		$division = $this->dashboard_model->get_division();
		$list = array();
		foreach ($division as $key_division => $div){
			$total_late = 0;
			$count_late = 0;
			$late = $this->dashboard_model->list_late($div['id']);
			$detail = array();
			foreach($late as $key => $data)
			{
				$login = $data['login_time'];
				$late_time = (strtotime($login) > strtotime('08:00:00') && strtotime($login) <= strtotime('12:00:00'))?strtotime($login)-strtotime('08:00:00'):"false";
				if($late_time=="false"){
				}
				else
				{
					$total_late = $total_late + $late_time;
					$count_late++;
				}
				array_push($detail,array('data' => $data, 'late_time' => $late_time));
			}
			if($count_late>0)
			{
			array_push($list,array(
											'division' => $div['division'],
											'minutes' => floor($total_late/60),
											'count' => $count_late,
											'detail' => $detail
											)
						);
			}
		}
		return $list;
	}
	
	function late(){
		$late_list = $this->dashboard_model->get_late_list();
		$list = $this->div_list();
		$str = '';
		$total = 0;
		$total_time = 0;
		if($late_list){
			if($this->session->userdata('level')!='Admin'){
		
				$str .='<ul style="height:75px; overflow:auto;">';
				
				$total = 0;
				$total_time = 0;
				if($late_list > 0){
					foreach($late_list as $row){
					
						$str .='<li>'.date('F d, Y',strtotime($row['date'])).'- Late (';
						$total +=1;
						$h = floor($row['late'] / 3600);
						$m = floor(($row['late'] % 3600)/60);
						$s = floor(($row['late'] % 3600)%60);
						if($h > 0){
							$str .= $h .' Hours, '. $m .' Minutes, '. $s .' Second';
						}else{
							$str .= floor(($row['late'] % 3600)/60).' Minutes,  '. $s .' Second';
						}
						
						$total_time += $row['late'];
						$str .=')</li>';
					}
				}
				$str .='</ul><p class="text-right">Total : '.$total.'Times - Late (';
				$h = $total_time / 3600;
				$m = floor(($total_time % 3600)/60);
				$s = floor(($total_time % 3600)%60);
				if($h > 0){
					$str .= floor($h) .' Hours, '. $m.' Minutes, '. $s .' Second';
				}else{
					$str .= floor(($total_time % 3600)/60).' Minutes, '. $s .' Second';
				}
				$str .=')</p>';
			
			}else{
				$str .='<ul style="height:75px; overflow:auto;">';
		
				
				if(count($list>0)){
					foreach($list as $data => $row){ 
					
						$str .= '<li>'. $row['division'].' - '.$row['count'].' Late (';
					
						$total +=$row['count'];
						$total_time +=$row['minutes'];
						$str.= floor($row['minutes']/60) .' Hours, '. ($row['minutes'] - (floor($row['minutes']/60))*60).' Minutes';
						$str .=')</li>';
					
				
					}
				}
				$str .='</ul><p class="text-right">Total : '. $total.' Times - Late (';
				$str .= floor($total_time/60) .' Hours, '. ($total_time - (floor($total_time/60))*60).' Minutes)</p>';
			
			}
		}
		else{
			$str.='<ul style="height:75px; overflow:auto;"></ul><p class="text-right">Total : 0 times Late</p>';
		}
		if($total_time > (10*60)&&$this->session->userdata('level')!='Admin'){
			$str.='<div class="alert alert-warning" role="alert" style="word-break: break-word;word-wrap: normal;white-space: normal;">You\'ve more than 10 minutes in a month. Please report to your Leader</div>';
		}
		echo $str;				
	}
	
	function absent(){
		$str = '';
		$total = 0;
		$absent_list = $this->dashboard_model->get_absent_list();
		if($absent_list){
			if($this->session->userdata('level')!='Admin'){
				$str .= '<ul style="height:75px; overflow:auto;">';
				
				if(count($absent_list[1]>0)){
					foreach($absent_list[1] as $row){
						$total += 1;
						$str .= '<li>'.date('F d, Y',strtotime($row['date'])).' - '.$row['status'].'</li>';
					}
				}
				$str .= '</ul><p class="text-right">Total : '.$total.' times Absent</p>';
						
			}else{
				$str .= '<ul style="height:75px; overflow:auto;">';
				
							
				foreach($absent_list as $key => $row){
					$total += $row;
					$str .= '<li>'. $key.' Unit '.$row .' times </li>';
				}
				$str .= '</ul><p class="text-right">Total : '.$total.' times Absent</p>';
			}
		}
		if($total > 4&&$this->session->userdata('level')!='Admin'){
			$str.='<div class="alert alert-danger" role="alert" style="word-break: break-word;word-wrap: normal;white-space: normal;">You\'ve more than 4 times absent in a month. Please report to your Leader</div>';
		}
		echo $str;		
	}
	
	function check_privileges()
	{
		
		return $this->dashboard_model->check_privileges();
	}
	/*-------------------------
		Attendance Chart
	-------------------------*/
	function table_attendance()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->table_attendance();
	}
	function chart_attendance()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->chart_attendance();
	}
	function detail_attendance()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->detail_attendance();
	}
	function view_attend(){
		$this->load->view('detail_attendance');
	}
	/*-------------------------
		End Attendance Chart
	--------------------------*/
	
	/*-------------------------
		Operation Rates
	-------------------------*/
	function operating_day()
	{
		$projectWorkload = $this->dashboard_model->projectWorkload();
		// echo var_dump($projectWorkload);
		return array(number_format((float)($projectWorkload[0]['pw']/($projectWorkload[0]['u']*8)*100), 2, '.', ''),$projectWorkload[0]['pw'],$projectWorkload[0]['date']);
		// return $projectWorkload;
	}
	
	function operating_weeks(){
		// $projectWorkloadWeeks = $this->dashboard_model->projectWorkloadWeeks();
		$projectWorkloadWeeks = $this->dashboard_model->get_pw();
		// echo var_dump($projectWorkloadWeeks);
		$total_est = ($projectWorkloadWeeks[0]['u'] * 8  );
		$pw = $projectWorkloadWeeks[0]['pw'];
		
		$day = $projectWorkloadWeeks[0]['total_day'];

         
		if($day == 0){
			$res = 0;
		}else{
		$res = $pw / $total_est / $day;
		}
		return array(number_format((float)$res*100, 2, '.', ''),$projectWorkloadWeeks[0]['pw'],$projectWorkloadWeeks[0]['dt']);
		// return array($projectWorkloadWeeks[0]['pw'],$projectWorkloadWeeks[0]['u'],$projectWorkloadWeeks[0]['total_day']);
		// return $projectWorkloadWeeks;
	}
	/*-------------------------
		End Operation Rates
	-------------------------*/
	
	/*****************************
	 * Workload Controllers
	 *****************************/

/* Workload ALl Report Configuration Controller */	

	function global_chart()
	{
		/*if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}*/
		echo $this->dashboard_model->global_chart();
	} 
	 
	function workload_all($division = false)
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->workload_all_table($division);
	}
	
	function workload_all_detail($division = false)
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->workload_all_table_detail($division);
	}
	
	function workload_all_detail2($division = false)
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->workload_all_table_detail2($division);
	}
	
	function operating_rate_all()
	{
		
		echo $this->dashboard_model->operating_rate_all();
	}

	

/* End Workload ALl Report Configuration Controller */

/* Workload Individual Report Configuration Controller */
	function individual_content($id_user = false)
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->individual_table($id_user);
	}
	
	function individual_detail($id_user = false)
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->individual_table_detail($id_user);
	}
	
	function individual_detail2($id_user = false)
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dashboard_model->individual_table_detail2($id_user);
	}
	
/* End Workload Individual Report Configuration Controller */
	
	function get_member_num($division = false)
	{
		echo $this->dashboard_model->get_member_num($division);
	}
	
	function get_holiday()
	{
		
		echo $this->dashboard_model->get_holiday();
	}
	
/* Export */
	function export($division, $from, $end)
	{
	
		$objPHPExcel = new PHPExcel();
		
		$worksheetcount=0;
		$excelSheet = $this->excel->setActiveSheetIndex($worksheetcount);
		//name the worksheet
		$excelSheet->setTitle("Report");
		$excelSheet->getSheetView()->setZoomScale(80);
		$excelSheet->setShowGridlines(false);
		
		$excelSheet->setCellValueByColumnAndRow(0,1, 'Date');
		$excelSheet->getStyle('A1')->getFont()->setSize(11);
		$excelSheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$excelSheet->getStyle('A1')->getFill()->getStartColor()->setARGB('ff000000');$excelSheet->getStyle('A1')->getFont()->getColor()->setARGB('ffffffff');
		
		$excelSheet->setCellValueByColumnAndRow(1,1, date("Y/m/d",strtotime($from))." - ".date("Y/m/d",strtotime($end)));
		$excelSheet->getStyle('B1')->getFont()->setSize(11);
		$excelSheet->mergeCellsByColumnAndRow(1,1,2,1);
		$styleArray = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  )
		  );
		$excelSheet->getStyle('B1:C1')->applyFromArray($styleArray);
		
		$excelSheet->setCellValueByColumnAndRow(0,3, 'No');
		$excelSheet->getStyle('A3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(1,3, 'Date');
		$excelSheet->getStyle('B3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(2,3, 'NIP');
		$excelSheet->getStyle('C3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(3,3, 'Name');
		$excelSheet->getStyle('D3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(4,3, 'Division');
		$excelSheet->getStyle('E3')->getFont()->setSize(11);
		$excelSheet->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$excelSheet->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('ff000000');$excelSheet->getStyle('A3:E3')->getFont()->getColor()->setARGB('ffffffff');
		
		$excelSheet->setCellValueByColumnAndRow(5,3, 'Work Classification');
		$excelSheet->getStyle('F3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(6,3, 'Work Category');
		$excelSheet->getStyle('G3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(7,3, 'Job Code');
		$excelSheet->getStyle('H3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(8,3, 'Project Management');
		$excelSheet->getStyle('I3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(9,3, 'Work Description');
		$excelSheet->getStyle('J3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(10,3, 'Planned Time');
		$excelSheet->getStyle('K3')->getFont()->setSize(11);
		
		$excelSheet->setCellValueByColumnAndRow(11,3, 'Actual Time');
		$excelSheet->getStyle('L3')->getFont()->setSize(11);
		
		$excelSheet->getStyle('F3:L3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$excelSheet->getStyle('F3:L3')->getFill()->getStartColor()->setARGB('ff82a5d0');
		
		
		$excelSheet->getColumnDimension('A')->setWidth(9);
		$excelSheet->getColumnDimension('B')->setWidth(16);
		$excelSheet->getColumnDimension('C')->setWidth(11);
		$excelSheet->getColumnDimension('D')->setWidth(28);
		$excelSheet->getColumnDimension('E')->setWidth(24);
		$excelSheet->getColumnDimension('F')->setWidth(20);
		$excelSheet->getColumnDimension('G')->setWidth(20);
		$excelSheet->getColumnDimension('H')->setWidth(12);
		$excelSheet->getColumnDimension('I')->setWidth(22);
		$excelSheet->getColumnDimension('J')->setWidth(30);
		$excelSheet->getColumnDimension('K')->setWidth(15);
		$excelSheet->getColumnDimension('L')->setWidth(15);
			
		$data = $this->dashboard_model->get_for_export(array('division' => $division, 'from' => $from,  'to' => $end));
		$x = 1;
		foreach($data as $key => $value){
		
		$y = 3 + $x;
		$excelSheet->setCellValueByColumnAndRow(0,$y, $x);
		
		$excelSheet->setCellValueByColumnAndRow(1,$y, $value['date']);
		
		$excelSheet->setCellValueByColumnAndRow(2,$y, $value['nip']);
		
		$excelSheet->setCellValueByColumnAndRow(3,$y, $value['name']);
		
		$excelSheet->setCellValueByColumnAndRow(4,$y, $value['division']);
		
		$excelSheet->setCellValueByColumnAndRow(5,$y, $value['classification']);
		
		$excelSheet->setCellValueByColumnAndRow(6,$y, $value['category']);
		
		$excelSheet->setCellValueByColumnAndRow(7,$y, $value['job_code']);
		
		$excelSheet->setCellValueByColumnAndRow(8,$y, $value['code']);
		
		$excelSheet->setCellValueByColumnAndRow(9,$y, $value['description']);
		
		$excelSheet->setCellValueByColumnAndRow(10,$y, $value['plan_time']);
		
		$excelSheet->setCellValueByColumnAndRow(11,$y, $value['actual_time']);
		
		$x++;
		}
		
		$excelSheet->getStyle('A3:L'.$y)->applyFromArray($styleArray);
		$filename=date("Y/m/d",strtotime($from))." - ".date("Y/m/d",strtotime($end)).' report.xlsx'; //save our workbook as this file name
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
					 
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output'); 
	}

}