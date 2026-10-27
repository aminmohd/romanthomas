<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model{
    
    public function get_products_by_category($category_id=null)
    { 
        $this->db->from('products');
        $this->db->where('is_visible','1');
        $this->db->like('category_id',$category_id);
        $this->db->order_by("sequence_order", "asc");

        $query = $this->db->get(); 
        return $query->result();
    }

    public function find_product($product_id)
    { 
        $q= $this->db->where(['id'=> $product_id])
            ->get('products');

        return $q->row();
    }

    public function update_product($product_id=null,$product_data=null) {
        $this->db->where('id', $product_id);
        $res = $this->db->update('products', $product_data);
        return $res;
    }

    public function add_product($product_data=null){
    	$res = $this->db->insert('products', $product_data); 
    	return $res;
    }

    public function delete_product($product_id=null) {
        print_r($this->db->last_query());
        $this->db->where('id', $product_id);
        return $this->db->delete('products');
    }
}
?>