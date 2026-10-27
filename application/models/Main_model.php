<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model{
    
    public function login($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->where('password', md5($password));
        $query = $this->db->get('users');

        if($query->num_rows() == 1) {
            return $query->row();
        }

        return false;
    }
}
?>