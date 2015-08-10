<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		auth();
		$this->load->library('form_validation');
		$this->load->model('member_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
	}

	function index()
	{
		$item['source'] =  site_url('members/grid_data');
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
		echo $this->member_model->grid_data();
	}
    
    function profile()
    {
        $item = (array) $this->member_model->get_data($this->session->userdata('id'));
        //validations        
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('nip', 'nip', 'required|numeric|min_length[7]');
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('email', 'nip', 'required|valid_email');
        $this->form_validation->set_rules('avaya', 'avaya', 'required|numeric');
        $this->form_validation->set_rules('phone', 'phone', 'required|numeric|min_length[10]');
        $this->form_validation->set_rules('address', 'address', 'required');
    
        
        if($this->form_validation->run() == FALSE)
        {
            $data = array(
                'content' => $this->load->view('member_detail', $item, TRUE),
                'script'=>$this->load->view('content_js', '', TRUE)
            );
            $this->load->view('template', $data);
        }
        else
        {
            $save['id'] = $this->session->userdata('id');
            $save['nip'] = $this->input->post('nip');
            $save['name'] = $this->input->post('name');
            $save['email'] = $this->input->post('email');
            $save['phone'] = $this->input->post('phone');
            $save['avaya'] = $this->input->post('avaya');
            $save['address'] = $this->input->post('address');
            $this->member_model->save_data($save);
            redirect('members/profile');
        }
        
    }
    
	function call_form($id = FALSE)
    {
        if($id != 0)
        {
            $data = (array) $this->member_model->get_data($id);
        }
        else
        {
            $data = array(
                'idu' => FALSE,
                'nip' => '',
                'name' => '',
                'email' => '',
                'avaya' => '',
                'phone' => '',
                'address' => '',
                'id_division'=>'',
                'id_user_level'=>''
            );
		}
		$data['opt_division'] = $this->member_model->opt_division();
		$data['opt_level'] = $this->member_model->opt_level(); 
		$this->load->view('form01', $data);
    }
	
	function submit_data()
    {
       $this->form_validation->set_rules('nip', 'NIP', 'required|numeric');
	   $this->form_validation->set_rules('name', 'name', 'required');
	   $this->form_validation->set_rules('email', 'email', 'required|valid_email');
	   $this->form_validation->set_rules('avaya', 'Avaya', 'required|numeric');
	   $this->form_validation->set_rules('phone', 'Phone', 'required|numeric');
	   $this->form_validation->set_rules('address', 'Address', 'required');
	   $this->form_validation->set_rules('division', 'division', 'required');
	   $this->form_validation->set_rules('level', 'level', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'nip' => $this->input->post('nip'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'avaya' => $this->input->post('avaya'),
                'phone' => $this->input->post('phone'),
                'password' => md5('12345'),
                'address' => $this->input->post('address'),
                'id_division' => $this->input->post('division'),
                'id_user_level' => $this->input->post('level')
            );
			
            $json = $this->member_model->save_data($save);            
        }
        echo json_encode($json);
    }

    function call_form_upload($id = FALSE)
    {
        $this->load->view('form02');
    }
    
	function avatar(){
		$this->load->view('uploadImage');
	}

	function upload_avatar(){
		$config['upload_path'] = './assets/img/user_photo/';
		$config['allowed_types'] = '*';
		$config['file_name'] = $this->session->userdata('nip');
        $config['overwrite'] = 'TRUE';
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('userfile')){
			$json = array('status'=>0, 'alert'=>$this->upload->display_errors());
		}
		else{
			$upload_data = $this->upload->data();
			$result = $this->member_model->update_avatar($upload_data);
			$this->session->set_userdata('image',$upload_data['file_name']);
			$json = $result;
		}
		echo json_encode($json);
	}
    function upload_file()
    {
		$path = './temp_upload/';
		$config['upload_path'] = './temp_upload/';
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
		if(!$this->upload->do_upload('userfile')){
			$json = array('status'=>0, 'alert'=>$this->upload->display_errors());
			$this->session->set_flashdata('msg_excel', 'Insert failed. Please check your file, only .xls file allowed.');
		}
		else{
			$inputFileType = 'Excel5';
			$data = array('error' => false);
			$upload_data = $this->upload->data();
			$inputFileName = $upload_data['full_path'];
			$this->load->library('excel');
			try {
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			} catch(Exception $e) {
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$total_sheets = $objPHPExcel->getSheetCount();
			
			$allSheetName = $objPHPExcel->getSheetNames();
			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = 'G';
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			
			$post_data = array();
			
			for($row = 2; $row<=$highestRow;++$row){
				for($col =0; $col < $highestColumnIndex;++$col){
					$value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();  
					$post_data[$row-1][$col]=$value;
					
				}
			}			
			$json = $this->member_model->upload_data($post_data);
		}        
		echo json_encode($json);
    }

    function call_form_03($id = FALSE)
    {
        $this->load->view('form03');
    }
	function get_division($text){
		$result = $this->member_model->get_division($text);
		return $result;
	}
    function update_pass()
    {
        $this->form_validation->set_rules('new-pass', 'new password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('cof-pass', 'confirm password', 'trim|required|matches[new-pass]');
        
        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
         else
        {
            $save = array(
                'id' => $this->session->userdata('id'),
                'password' => md5($this->input->post('new-pass'))
            );
            $json = array('status'=> 1, 'alert'=>'Data has been added');
            $this->member_model->save_password($save);            
        }
        echo json_encode($json);    
    }

	function delete_data($id)
	{
		$this->member_model->delete_data($id);
		redirect('members');
	}

}