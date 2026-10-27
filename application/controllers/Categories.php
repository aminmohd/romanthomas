<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
        // check login authenication
        if( ! $this->session->userdata('authenticated')){
            redirect('login');
        }
    }
    
    /* Add Categories from store into DB*/
	public function add_category_into_DB() {
		
		$data['mapping_data'] = $this->custom_library->get_all_categories();
		//$data['mapping_data'] = $this->Api_model->get_all_categories();

		$categories = json_decode($data['mapping_data'],true);
		if(!empty($categories['data'])){
			foreach ($categories['data'] as $key => $value) { 
				$Savedata = array(
		            'id'=>$value['id'],
		            'parent_id'=>$value['parent_id'],
		            'name'=>$value['name'],
		            'is_parent'=> ($value['parent_id']==0) ? '1' : '0',
		            'image_url'=>$value['image_url'],
		            'is_visible'=>$value['is_visible'],
		            'created_at'=>date('Y-m-d H:i:s')
		        );
				$response = $this->db->insert('categories',$Savedata);
			}
		} else {
			if(isset($categories['errors'])) {
				log_message('info', 'Api errors - Add all categories from store into DB : Status -' . $categories['status'].', Error title -'. $categories['title']);
			}
		}
		redirect('products/index');
	}
}
