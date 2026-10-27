<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model{
    
    public function add_customer($customer_data=null){
    	$res = $this->db->insert('customers', $customer_data); 
    	return $res;
    }

    public function update_customer($customer_id=null,$customer_data=null) {
        $this->db->where('id', $customer_id);
        $res = $this->db->update('customers', $customer_data);
        return $res;
    }

    public function find_customer($customer_id){ 
        $q= $this->db->where(['id'=> $customer_id])
            ->get('customers');

        return $q->row();
    }
}
?>