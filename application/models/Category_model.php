<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model{
    
    public function get_category_details($category_id=null) {
        $this->db->select('id,name,parent_id,image_url');
        $this->db->from('categories');
        $this->db->where('id',$category_id,'is_visible','1');
        $query = $this->db->get(); 
        $category_data = $query->result();

        return $category_data;
    }

    public function find_catData($id)
    { 
        $q= $this->db->where(['id'=> $id])
            ->get('categories');

        return $q->row();   
    }

    /* check category has subchilds or not */
    public function get_subchilds($category_id=null) {
    	$this->db->select('id,name,parent_id,image_url');
		$this->db->from('categories');
		$this->db->where('parent_id',$category_id);
		$this->db->where('is_visible','1');
		$query = $this->db->get(); 
		return $query->result();
    }

    public function get_main_categories() {
    	$this->db->from('categories');
		$this->db->where('parent_id','0');
		$query = $this->db->get(); 
		return $query->row();
    }

    public function get_parent_categories() {
    	$this->db->from('categories');
		$this->db->where('parent_id','0');
		$query = $this->db->get(); 
		return $query->result();
    }

    /*creating category array for showing in menus */
    public function get_all_category() {
		$categories = $this->get_parent_categories();
		
		$childs = array();

		foreach($categories as $ck=>$cat) {
			$sub_categories_level1 = $this->get_subchilds($cat->id);

			$childs[$ck]['id'] = $cat->id;
			$childs[$ck]['name'] = $cat->name;
			$childs[$ck]['is_parent'] = $cat->parent_id;

			if(!empty($sub_categories_level1)) {
				//$childs[$ck]['childs'] = $sub_categories_level1;
				
				foreach($sub_categories_level1 as $sk=>$scat) {
					$sub_categories_level2 = $this->get_subchilds($scat->id);

					$childs[$ck]['childs'][$sk]['id'] = $scat->id;
					$childs[$ck]['childs'][$sk]['name'] = $scat->name;
					$childs[$ck]['childs'][$sk]['parent_id'] = $scat->parent_id;

					//$childs[$ck]['childs'][$sk]['subchilds'] = (array)$sub_categories_level2;

					if(!empty($sub_categories_level2)) {
						foreach($sub_categories_level2 as $skey=>$schild) { 
							$childs[$ck]['childs'][$sk]['subchilds'][$skey]['id'] = $schild->id;
							$childs[$ck]['childs'][$sk]['subchilds'][$skey]['name'] = $schild->name;
							$childs[$ck]['childs'][$sk]['subchilds'][$skey]['parent_id'] = $schild->parent_id;
						}
					}
					
				}
			} else {
				$childs[$ck]['childs'] = array();
			}
		}

		return $childs;
	}
}
?>