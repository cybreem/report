<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_attendance extends CI_Controller {
    
    function __Construct()
    {
        parent::__Construct();        
        $this->load->library('excel');
        $this->load->model('daily_attendance_model','dam');
    }
    
    function index()
    {
		$data = array(
            'content'=>$this->load->view('content', '', TRUE),
            'script'=>$this->load->view('content_js', '', TRUE)
        );
        $this->load->view('template', $data);
    }
    function summary()
	{
		$str = '<div class="quick-btn">
                <i class="fa fa-calendar  fa-2x"></i>
				<span>today</span> 
                <span>TOTAL</span> 
              </div> ';
		$summary = $this->dam->summary(date('Y-m-d'),date('Y-m-d'),'daily');
		
		foreach($summary as $row){
			$str .= "<div class='quick-btn'>
                <i class='fa fa-2x'>".$row['total']."</i>
				<span>person(s)</span> 
                <span>".$row['status']."</span> 
              </div> ";
			
		}
		echo $str;
	}
	function getTotal()
	{
		$str = '';
		
		$summary = $this->dam->summary(date('Y-m-d',$this->input->post('start')), (date('m',$this->input->post('start')) != date('m')) ? date('Y-m-t',$this->input->post('start')) : date('Y-m-d'));
		foreach($summary as $row){
			$str .= '<tr><td><div style="height: 45px;">'.$row['total'].'</div></td></tr>';
			// $str .= "<div class='quick-btn'>
                // <i class='fa fa-2x'>".$row['total']."</i>
				// <span>times</span> 
                // <span>".$row['status']."</span> 
              // </div> ";
			
		}
		echo $str;
	}
    function report()
    {
        if(!$this->input->is_ajax_request())
        {
            show_404();
            exit;
        }
		$holi = $this->dam->get_holiday();
        $title  = $this->dam->selectStatusName();
		$data = $this->dam->get_data();
		foreach($data as $key => $row){
			if(in_array($row['dateu'],$holi)){
				$data[$key]['ct'] = '';
			}
		}
      
        $data = array('title' => $title, 'data' =>$data);
        echo json_encode($data);
    }
	function detail_attendance()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->dam->detail_attendance();
	}
	function view_status()
	{
		$status = $this->input->post('status');
		echo $status;
		$header = array();
		if($this->dam->getParent($status)==1||$status==1){
			$header = array('Name','Division','Reason','Login Time','Logout Time');
		}else if($this->dam->getParent($status)==2||$status==2){
			$header = array('Name','Division');
		}else{
			$header = array('Name','Division','Time Out');
		}
		$data['header'] = $header;
		$this->load->view('detail_attendance',$data);
	}
	function export_excel($month = false)
	{
		global $objPHPExcel;
		$this->load->library('excel');
		$objPHPExcel = $this->excel;
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->setActiveSheetIndex(0)->setTitle(date("M Y",strtotime($month)));
		$objPHPExcel->setActiveSheetIndex(0)->getSheetView()->setZoomScale(80);
		$objPHPExcel->setActiveSheetIndex(0)->setShowGridlines(false);
		//name the worksheet
		$objPHPExcel->getActiveSheet()->setTitle(date("M Y",strtotime($month)));
		$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);
		$objPHPExcel->getActiveSheet()->setShowGridlines(false);
		
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1, 'Project : ');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1, 'Digital Marketing');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,1, 'PKWT');
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,1, 'Contract');
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,1, 'Attendance Report'.date("F Y",strtotime($month)));
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(8,1,10,1);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(9);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,3, 'No');
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,3, '1 '.date("M",strtotime($month))." - ".date("t M Y",strtotime($month)));
		$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,4, 'Name');
		$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->freezePane('G5');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,4, 'Batch');
		$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,4, 'Log in ID');
		$objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,4, 'Ext.');
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,4, 'NIP');
		$objPHPExcel->getActiveSheet()->getStyle('F4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F4')->getFont()->setSize(10);
		$styleArray = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  )
		  );
		
		$user_list = $this->dam->user_list();
		$holiday_list =  $this->dam->holiday_list();
		$holiday = array();
		foreach($holiday_list as $holiday_column => $holiday_data){
			$holiday[] = $holiday_data['date'];
		}
		$no = 1;
		$column = 6;
		$row = 5;
		$curr_date = date("Y-m-01",strtotime($month));
		$day = date("t",strtotime($month));
		for($x=0;$x<$day;$x++)
		{
			$col = $column+($x*6);
			$col_date = $col;
			$col_login = $col+1;
			$col_logout = $col+2;
			$col_note = $col+3;
			$col_note2 = $col+4;
			$col_duration = $col+5;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3, date('d-M-y',strtotime($curr_date)));
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_date);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_login,3, 'Login');
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_login);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(9);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_logout,3, 'Logout');
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_logout);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(9);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col_note,3,$col+5,3);
			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note,3, 'Discipline');
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_note);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFill()->getStartColor()->setARGB('fff4b084');
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_note);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_note2);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_duration);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,4, date('D',strtotime($curr_date)));
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_date);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_login,4, 'AM');
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_login);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_logout,4, 'PM');
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_logout);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note,4, 'Note');
			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col_note,4,$col_note2,4);
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_note);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(9);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFill()->getStartColor()->setARGB('fff4b084');
			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_duration,4, 'Duration');
			$letter = PHPExcel_Cell::stringFromColumnIndex($col_duration);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(9);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFill()->getStartColor()->setARGB('fff4b084');
			
			if(date('D',strtotime($curr_date))=="Sat" || date('D',strtotime($curr_date))=="Sun" )
			{
				$letterA = PHPExcel_Cell::stringFromColumnIndex($col_date);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($col_duration);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB.'4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB.'4')->getFill()->getStartColor()->setARGB('ffff0000');
			}
			if(in_array($curr_date,$holiday)){
				$letterA = PHPExcel_Cell::stringFromColumnIndex($col_date);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($col_duration);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB.'4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB.'4')->getFill()->getStartColor()->setARGB('ffff0000');
			}
			
			$curr_date = date("Y-m-d",strtotime($curr_date.'+1 day'));
		}
		
		foreach($user_list as $field => $fill)
		{
		
			${'duration_count'.$row} = 0;
		
			$curr_date = date("Y-m-01",strtotime($month));
			$day = date("t",strtotime($month));
			$id_user = $fill['id'];
			$name = $fill['name'];
			$avaya = $fill['avaya'];
			$nip = $fill['nip'];
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row, $no);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row, $name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row, $avaya);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row, $nip);
			
			$login_list = $this->dam->login_list($id_user,$month);
			$login = array();
			$login_date = array();
			foreach($login_list as $login_column => $login_data)
			{
				$login["key".date("Ymd",strtotime($login_data['date']))]	= array('login' => $login_data['login_time'],
																					'logout' => $login_data['logout_time'], 
																					'status' => $login_data['status'], 
																					'status_b' => $login_data['status_b'], 
																					'reason' => $login_data['reason'], 
																					'manager_note' => $login_data['manager_note'], 
																					'manager_status' => $login_data['manager_status'], 
																					'status_parent' => $login_data['status_parent'], 
																					'late' => $login_data['late']
								);
				$login_date[] = $login_data['date'];
			}
			$holiday_count = 0;
			for($x=0;$x<$day;$x++)
			{
				$col = $column+($x*6);
				$col_date = $col;
				$col_login = $col+1;
				$col_logout = $col+2;
				$col_note = $col+3;
				$col_note2 = $col+4;
				$col_duration = $col+5;
				
				$letterA = PHPExcel_Cell::stringFromColumnIndex($col_note);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($col_duration);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('fff4b084');
				if(!in_array($curr_date,$login_date))
				{
					$letterA = PHPExcel_Cell::stringFromColumnIndex($col_date);
					$letterB = PHPExcel_Cell::stringFromColumnIndex($col_duration);
					if(date('D',strtotime($curr_date))=="Sat" || date('D',strtotime($curr_date))=="Sun")
					{
						$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffff0000');
						$holiday_count++;
					}
					elseif(in_array($curr_date,$holiday)){
						$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffff0000');
						$holiday_count++;
					}elseif(strtotime($curr_date)<=strtotime(date("Y-m-d"))){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_date,$row, "X");
						$letter = PHPExcel_Cell::stringFromColumnIndex($col_date);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_login,$row, 0);
						$letter = PHPExcel_Cell::stringFromColumnIndex($col_login);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_logout,$row, 0);
						$letter = PHPExcel_Cell::stringFromColumnIndex($col_logout);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note,$row, "alpa");
						$letter = PHPExcel_Cell::stringFromColumnIndex($col_note);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note2,$row, "");
						$letter = PHPExcel_Cell::stringFromColumnIndex($col_note2);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_duration,$row, "");
						$letter = PHPExcel_Cell::stringFromColumnIndex($col_duration);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
					}
					$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->applyFromArray($styleArray);
				}else{
					$note = $login["key".date("Ymd",strtotime($curr_date))]['manager_note'];
					$status = $login["key".date("Ymd",strtotime($curr_date))]['status'];
					$status_b = $login["key".date("Ymd",strtotime($curr_date))]['status_b'];
					if($status=="" && $status_b!=""){
						$status = $status_b;
					}
					if($status=="On-Time"){
						$status = "";
					}
					if($status_b=="Absent" && $status=="Overtime"){
						$status = "Overtime";
					}
					if($login["key".date("Ymd",strtotime($curr_date))]['login']=="" || $login["key".date("Ymd",strtotime($curr_date))]['login']=="00:00:00"){
						$varLogin = 0;
					}else{
						$varLogin = date('g:iA',strtotime($login["key".date("Ymd",strtotime($curr_date))]['login']));
					}
					if($login["key".date("Ymd",strtotime($curr_date))]['logout']=="" || $login["key".date("Ymd",strtotime($curr_date))]['logout']=="00:00:00"){
						$varLogout = 0;
					}else{
						$varLogout = date('g:iA',strtotime($login["key".date("Ymd",strtotime($curr_date))]['logout']));
					}
					if($login["key".date("Ymd",strtotime($curr_date))]['late']>0){
						$varLate = date('G:i:00',($login["key".date("Ymd",strtotime($curr_date))]['late']-3600));
					}else{
						$varLate = "";
					}
					
					$letterA = PHPExcel_Cell::stringFromColumnIndex($col_date);
					$letterB = PHPExcel_Cell::stringFromColumnIndex($col_duration);
					if(date('D',strtotime($curr_date))=="Sat" || date('D',strtotime($curr_date))=="Sun")
					{
						$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffff0000');
						$holiday_count++;
					}else{
						if(in_array($curr_date,$holiday)){
							$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffff0000');
						$holiday_count++;
						}else{
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_date,$row, ($login["key".date("Ymd",strtotime($curr_date))]['status_parent']==1||$login["key".date("Ymd",strtotime($curr_date))]['status']=="Overtime")?"V":"X");
							$letter = PHPExcel_Cell::stringFromColumnIndex($col_date);
							$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_login,$row, $varLogin);
							$letter = PHPExcel_Cell::stringFromColumnIndex($col_login);
							$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							if($status=="Late"){
								$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->getColor()->setARGB('ff9c0006');
								$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
								$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFill()->getStartColor()->setARGB('ffffc7ce');
								
							}
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_logout,$row, $varLogout);
							$letter = PHPExcel_Cell::stringFromColumnIndex($col_logout);
							$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note,$row, $status);
							if($status==""){
							}else{
								$letter = PHPExcel_Cell::stringFromColumnIndex($col_note);
								$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
								$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								
								if($login["key".date("Ymd",strtotime($curr_date))]['manager_status']==1){
									$objPHPExcel->getActiveSheet()->getComment($letter.$row)->setAuthor('PHPExcel');
									$objCommentRichText =$objPHPExcel->getActiveSheet()->getComment($letter.$row)->getText()->createTextRun('Author'); 
									$objCommentRichText->getFont()->setBold(true); 
									$objPHPExcel->getActiveSheet()->getComment($letter.$row)->getText()->createTextRun("\r\n"); 
									$objPHPExcel->getActiveSheet()->getComment($letter.$row)->getText()->createTextRun($note);
									$letter = PHPExcel_Cell::stringFromColumnIndex($col_note);
									$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
									$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFill()->getStartColor()->setARGB('ff00b0f0');
								}
							}
							if($status_b=="Late" && $status=="Late"){
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note2,$row, "");
							}
							if($status_b=="Late" && $status=="Late"){
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note2,$row, "");
							}
							if($varLate!="" && $status!="Late"){
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_note2,$row, "Late");
							}
							$letter = PHPExcel_Cell::stringFromColumnIndex($col_note2);
							$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
							
							$letter = PHPExcel_Cell::stringFromColumnIndex($col_duration);
							$objPHPExcel->getActiveSheet()->getStyle($letter.$row)
							->getNumberFormat()->applyFromArray( 
								array( 
									'code' => PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3
								)
							);
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_duration,$row, $varLate);
							if($varLate!=""){
								$letter = PHPExcel_Cell::stringFromColumnIndex($col_duration);
								$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->getColor()->setARGB('ffff0000');
								${'duration_count'.$row} += ($login["key".date("Ymd",strtotime($curr_date))]['late']);
								
							}else{
								${'duration_count'.$row} += 0;
							}
							
						}
					}
					$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->applyFromArray($styleArray);
				}
				if(date('D',strtotime($curr_date))=="Sat" || date('D',strtotime($curr_date))=="Sun")
				{
					$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffff0000');
				}
				if(in_array($curr_date,$holiday)){
					$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffff0000');
				}
				
				
				$curr_date = date("Y-m-d",strtotime($curr_date.'+1 day'));
			}
			$sumSick = $col_duration+1;
			$sumAbsence = $col_duration+2;
			$sumPermit = $col_duration+3;
			$sumTotalAbsent = $col_duration+4;
			$sumLeaves = $col_duration+5;
			$sumOvertime = $col_duration+6;
			$sumPublicHoliday = $col_duration+7;
			$sumSumofLate = $col_duration+8;
			$sumDuration = $col_duration+9;
			$sumGoEarly = $col_duration+10;
			$sumOFF = $col_duration+11;
			$sumAttendance = $col_duration+12;
			$sumNotes = $col_duration+13;
			
			$letterEnd = PHPExcel_Cell::stringFromColumnIndex($col_duration);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumSick,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"Sick")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumSick);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumAbsence,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"alpa")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumAbsence);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumPermit,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"Permit")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumPermit);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
				$letterA = PHPExcel_Cell::stringFromColumnIndex($sumSick);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($sumPermit);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('fff4b084');
			
			
				$letterA = PHPExcel_Cell::stringFromColumnIndex($sumSick);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($sumPermit);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumTotalAbsent,$row, '=SUM('.$letterA.$row.':'.$letterB.$row.')');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumTotalAbsent);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumLeaves,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"leave")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumLeaves);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumOvertime,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"Overtime")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumOvertime);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumPublicHoliday,$row, $holiday_count);
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumPublicHoliday);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
				$letterA = PHPExcel_Cell::stringFromColumnIndex($sumTotalAbsent);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($sumPublicHoliday);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffe7e6e6');
			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumSumofLate,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"Late")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumSumofLate);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumDuration);							
			// $objPHPExcel->getActiveSheet()->getStyle($letter.$row)
			// ->getNumberFormat()->applyFromArray( 
				// array( 
					// 'code' => PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4
				// )
			// );
			$varDuration = date('H:i:s',${'duration_count'.$row}-3600);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumDuration,$row,$varDuration);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumGoEarly,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"Leave Early")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumGoEarly);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
				$letterA = PHPExcel_Cell::stringFromColumnIndex($sumSumofLate);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($sumGoEarly);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffc6e0b4');
				
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumOFF,$row, ($day-$holiday_count));
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumOFF);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumAttendance,$row, '=COUNTIF(G'.$row.':'.$letterEnd.$row.',"V")');
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumAttendance);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
				$letterA = PHPExcel_Cell::stringFromColumnIndex($sumOFF);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($sumAttendance);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->getFill()->getStartColor()->setARGB('ffddebf7');
				
			
			$letter = PHPExcel_Cell::stringFromColumnIndex($sumNotes);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($letter.$row)->getFont()->setSize(10);
			
				$letterA = PHPExcel_Cell::stringFromColumnIndex($sumSick);
				$letterB = PHPExcel_Cell::stringFromColumnIndex($sumNotes);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.$row.':'.$letterB.$row)->applyFromArray($styleArray);
			
			$no++;
			$row++;
			unset($login);
		}
		$sumSick = $col_duration+1;
		$sumAbsence = $col_duration+2;
		$sumPermit = $col_duration+3;
		$sumTotalAbsent = $col_duration+4;
		$sumLeaves = $col_duration+5;
		$sumOvertime = $col_duration+6;
		$sumPublicHoliday = $col_duration+7;
		$sumSumofLate = $col_duration+8;
		$sumDuration = $col_duration+9;
		$sumGoEarly = $col_duration+10;
		$sumOFF = $col_duration+11;
		$sumAttendance = $col_duration+12;
		$sumNotes = $col_duration+13;
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumSick,3, 'Sick');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumSick);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumSick,3,$sumSick,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumAbsence,3, 'Absence');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumAbsence);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumAbsence,3,$sumAbsence,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumPermit,3, 'Permit');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumPermit);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumPermit,3,$sumPermit,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		
			$letterA = PHPExcel_Cell::stringFromColumnIndex($sumSick);
			$letterB = PHPExcel_Cell::stringFromColumnIndex($sumPermit);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->getStartColor()->setARGB('fff4b084');
		
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumTotalAbsent,3, 'Total Absent');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumTotalAbsent);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumTotalAbsent,3,$sumTotalAbsent,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumLeaves,3, 'Leaves');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumLeaves);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumLeaves,3,$sumLeaves,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumOvertime,3, 'Overtime');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumOvertime);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumOvertime,3,$sumOvertime,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumPublicHoliday,3, 'Public Holiday');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumPublicHoliday);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumPublicHoliday,3,$sumPublicHoliday,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(13);
		
			$letterA = PHPExcel_Cell::stringFromColumnIndex($sumTotalAbsent);
			$letterB = PHPExcel_Cell::stringFromColumnIndex($sumPublicHoliday);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->getStartColor()->setARGB('ffe7e6e6');
		
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumSumofLate,3, 'Discipline');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumSumofLate);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumSumofLate,3,$sumDuration,3);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumSumofLate,4, 'Sum of Late');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumSumofLate);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(11);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumDuration,4, 'Duration');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumDuration);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumGoEarly,3, 'Go Early');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumGoEarly);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumGoEarly,3,$sumGoEarly,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(11);
		
			$letterA = PHPExcel_Cell::stringFromColumnIndex($sumSumofLate);
			$letterB = PHPExcel_Cell::stringFromColumnIndex($sumGoEarly);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->getStartColor()->setARGB('ffc6e0b4');
			
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumOFF,3, 'OFF');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumOFF);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumOFF,3,$sumOFF,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumAttendance,3, 'Attendance');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumAttendance);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumAttendance,3,$sumAttendance,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(11);
		
			$letterA = PHPExcel_Cell::stringFromColumnIndex($sumOFF);
			$letterB = PHPExcel_Cell::stringFromColumnIndex($sumAttendance);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getFill()->getStartColor()->setARGB('ffddebf7');
			
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($sumNotes,3, 'Notes');
		$letter = PHPExcel_Cell::stringFromColumnIndex($sumNotes);
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($sumNotes,3,$sumNotes,4);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3:'.$letter.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle($letter.'3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(50);
		
			$letterA = PHPExcel_Cell::stringFromColumnIndex($sumSick);
			$letterB = PHPExcel_Cell::stringFromColumnIndex($sumNotes);
			
		$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle($letterA.'3:'.$letterB."4")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		
		$objPHPExcel->getActiveSheet()->getStyle('A3:F'.($row-1))->applyFromArray($styleArray);
		
		
		$filename=$month.' attendance report.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
		$objWriter->save('php://output');
	}
}