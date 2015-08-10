<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class RenderData implements PHPExcel_Reader_IReadFilter{
		private $_startRow = 0;
		
		private $_endRow = 0;
		public $inputFileName;
		
		public function readCell($column, $row, $worksheetName=""){
			if($row >=2 && $row<=8){
				return true;
			}
			return false;
		}
	}
 ?>