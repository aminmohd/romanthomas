<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        //$this->logged_in();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Main_model');
        $this->load->model('Api_model');
        $this->load->model('Category_model');
        $this->load->model('Product_model');
        // check login authenication
        if( ! $this->session->userdata('authenticated')){
            redirect('login');
        }
    }

    /* --- Products Grid View ------
     where admin can change product sequence through the drag and drop feature */
	public function index($category_id=null)
	{
		$data = array(
						'main_CategoryName' =>'',
						'categoryName' =>'',
						'subcatName' =>'',
						'main_CategoryID' => '',
						'categoryID' =>'',
						'subcatID' => '',
					 );
		if(!empty($category_id)) {
			
			// ************** Breadcrumbs (sub category Data) ***********
			$subcatData= json_decode(json_encode($this->Category_model->find_catData($category_id)));
			$subcat_id =  $subcatData->parent_id;
			if($subcat_id == '0'){
				$data['main_CategoryName'] = $subcatData->name;
				$data['main_CategoryID'] = $subcatData->id;
			}else{
			// category Data
			$catData = json_decode(json_encode($this->Category_model->find_catData($subcat_id)));
			$cat_id =  $catData->parent_id;
			if($cat_id == '0'){
					$data['main_CategoryName'] = $catData->name;
					$data['categoryName'] = $subcatData->name;
					$data['main_CategoryID'] = $catData->id;
					$data['categoryID'] = $subcatData->id;
				}else{
					// main category Data
					$mainCatData = json_decode(json_encode($this->Category_model->find_catData($cat_id)));
					$data['main_CategoryName'] = $mainCatData->name;
					$data['categoryName'] =  $catData->name;
					$data['subcatName'] =  $subcatData->name;
					$data['main_CategoryID'] = $mainCatData->id;
					$data['categoryID'] =  $catData->id;
					$data['subcatID'] =  $subcatData->id;
				}
			}

			//check whether we have to show products or category names
			$subcategories = $this->Category_model->get_subchilds($category_id);

			if(empty($subcategories)) {
				$data['products'] = $this->Product_model->get_products_by_category($category_id);
				$data['show_prodcuts'] = 1;
			} else {
				$data['category_as_products'] = $subcategories;
				$data['show_prodcuts'] = 0;
			}
		} else {
			//get first parent category details
			$get_MainCategory = $this->Category_model->get_main_categories();
			$data['category_as_products'] = $this->Category_model->get_subchilds($get_MainCategory->id);
			$data['main_CategoryName'] = $get_MainCategory->name;
			$data['main_CategoryID'] = $get_MainCategory->id;
			$data['show_prodcuts'] = 0;
		}
		
		
		$data['categories'] = $this->Category_model->get_all_category();	
		$data['main_content'] = array('products/index');
		$this->load->view('templates/main_template', $data);
	}

	/* ---- Upate product sequence order in DB and on store -----
      Ajax function - calling from products index view
	*/
	public function update_squence_order()
	{
		if($_POST['position']) {
			$position = $_POST['position'];
			$i=1;

			foreach($position as $k=>$v) {
			    $data = array(
		            'sequence_order'=>$i,
		        );
			    $this->db->where('id', $v);
		        
		        if ($this->db->update('products', $data)) {
		        	
		        	$res = $this->custom_library->get_all_custom_fileds($v);
		        	//$res = $this->Api_model->get_all_custom_fileds($v);
			        $product_custom_fileds=json_decode($res,true);

			        if(isset($product_custom_fileds['errors'])) {
			        	log_message('info', 'Api errors - Get all custom fields : Status -' . $product_custom_fileds['status']. ', Error title -'.$product_custom_fileds['title']);
			        } else {
			        	if(!empty($product_custom_fileds['data'])) {
				         	foreach($product_custom_fileds['data'] as $pk=>$pval) {
				         		if($pval['name']=='sequence_order') {
				         			//update sequence order value
				         			$updated_data = array(
				         				'name' => 'sequence_order',
				         				'value' => strval($i)
				         			);

				         			
				         			$response = $this->custom_library->update_custom_filed($v,$pval['id'],json_encode($updated_data));
				         			// $response = $this->Api_model->update_custom_filed($v,$pval['id'],json_encode($updated_data));

				         			if(isset($response['errors'])) {
				         				log_message('info', 'Api errors - Update custom field sequence order : Status -' . $response['status']. ', Error title -'.$response['title']);
				         			}
				         		}
				         	}
				        }
			        }
		        }
				$i++;
			}
		}
	}

	/* Add products from store into DB */
	public function add_products_into_DB () {
		
		$data['mapping_data'] = $this->custom_library->get_all_products(); 
		//$data['mapping_data'] = $this->Api_model->get_all_products(); 
		$products = json_decode($data['mapping_data'],true);
		
		if(!empty($products['data'])){
			foreach ($products['data'] as $key => $value) {
				$product_img = $this->custom_library->get_all_product_images($value['id']);

				//$product_img = $this->Api_model->get_all_product_images($value['id']);
				$product_img = json_decode($product_img,true);

				foreach($product_img['data'] as $prokey=>$provalue) {
					if($provalue['is_thumbnail']==1){
						$products['data'][$key]['product_images'] = $provalue; 
					}
				}
				
				$Savedata = array(
		            'id'=>$value['id'],
		            'name'=>$value['name'],
		            'sku'=>$value['sku'],
		            'image'=>$products['data'][$key]['product_images']['url_thumbnail'],
		            'page_url'=>$value['custom_url']['url'],
		            'category_id'=>json_encode(implode(",",$value['categories'])),
		            'is_visible'=>$value['is_visible'],
		            'created_at'=>date('Y-m-d H:i:s')
		        );
				
				$response = $this->db->insert('products',$Savedata);
			}
		} else {
			if(isset($products['errors'])) {
				log_message('info', 'Api errors - Add all products from store into DB : Status -' . $products['status'].', Error title -'. $products['title']);
			}
		}
		redirect('products/index');
	}
}
