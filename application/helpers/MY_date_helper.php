<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
function format_date($date)
{	
	if ($date != '' && $date != '0000-00-00')
	{
		$d = explode('-', $date);
		$m = Array(
			'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		return $d[2].' '.$m[$d[1]-1].' '.$d[0];
	}
	else
	{
		return false;
	}
}

function reverse_format($date)
{
	if(empty($date))
	{
		return;
	}
		$d = explode('-', $date);
		return "{$d[1]}-{$d[2]}-{$d[0]}";
}

function format_ymd($date)
{
	if(empty($date) || $date == '00/00/0000')
	{
		return '';
	}
	else
	{
		$d = explode('/', $date);
		return $d[2].'-'.$d[1].'-'.$d[0];
	}
}

function format_dmy($date)
{
	if(empty($date) || $date == '0000-00-00')
	{
		return '';
	}
	else
	{
		return date('d/m/Y', strtotime($date));
	}
}