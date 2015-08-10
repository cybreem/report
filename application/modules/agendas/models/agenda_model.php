<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda_model extends CI_Model {
    
    function get_member()
    {
        $division = ($this->session->userdata('division') != '') ? $this->session->userdata('division') : '%'; 
        $this->db->select('id, name')
        ->where('division like', $division);
        $query = $this->db->get('users_view');
        return $query->result_array();
    }
    
    function get_project()
    {
        $id = array();
        foreach ($this->get_member() as $row) {
            array_push($id, $row['id']);            
        }
        $this->db->where_in('id_user', $id);
        $this->db->where_in('class_id', array(2,3));
        $query = $this->db->get('reports_view');
        return $query->result_array();
    }

}