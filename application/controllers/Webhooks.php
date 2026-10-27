<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhooks extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Customer_model');
    }

	public function index() {
		$data = array();
		$fullPostData = file_get_contents('php://input');
		$data = json_decode($fullPostData,true);

		http_response_code(200);
		//file_put_contents("aws-test-testwebhook.txt"," productDetails =".print_r($data,true),FILE_APPEND);

		log_message('info', 'Webhook called from store - Product' . json_encode($data,true));

		if(!empty($data['data']['id'])){
			$productId = $data['data']['id'];
			$productDetails = $this->custom_library->get_produdct_details($productId);
			$productDetails = json_decode($productDetails,true);

			if(!empty($productDetails['data'])) {
				//get product img
				$product_img = $this->custom_library->get_all_product_images($productDetails['data']['id']);
				
				$product_img = json_decode($product_img,true);

				if(!empty($product_img['data'])) {
					foreach($product_img['data'] as $prokey=>$provalue) {
						if($provalue['is_thumbnail']==1){
						 $img_url = $provalue['url_thumbnail'];
						}
					}
				} else {
					$img_url = null;
				}

				$product_data = [
					'id'=>$productDetails['data']['id'],
		            'name'=>$productDetails['data']['name'],
		            'sku'=>$productDetails['data']['sku'],
		            'image'=>$img_url,
		            'page_url'=>$productDetails['data']['custom_url']['url'],
		            'category_id'=>json_encode(implode(",",$productDetails['data']['categories'])),
		            'is_visible'=>$productDetails['data']['is_visible'],
		            'updated_at'=>date('Y-m-d H:i:s')
		        ];

		        if($data['scope']=='store/product/created') {
		        	$res = $this->add_product_webhook($product_data);
		        	echo 'product added successfuly';
		        } 
		        else if($data['scope']=='store/product/updated') {
		        	$res = $this->update_product_webhook($product_data);
		        	echo 'product updated successfuly';
		        }
				//$check_product_exist = $this->Product_model->find_product($productDetails['data']['id']);
			} else {
				if($data['scope']=='store/product/deleted') {
		        	$res = $this->delete_product_webhook($data['data']['id']);
		        	echo 'product deleted successfuly';
		        } else {
		        	log_message('info', 'Webhook Error - No data found');
		        }
			}
			//file_put_contents("aws-test-testwebhook.txt"," productDetails = <br>".print_r($productDetails,true),FILE_APPEND);exit;
		} else {
			log_message('info', 'Webhook Error - No data found');
		}
		die();
	}

	public function update_product_webhook($product_data=null) {
		$res = $this->Product_model->update_product($product_data['id'],$product_data);
        if($res) {
        	log_message('info', 'Webhook Product updated successfuly - Product ID -' . $product_data['id']);
        } else {
        	log_message('info', 'Webhook Product could not be updated - Product ID -' . $$product_data['id']);
        }
        return $res;
	}

	public function add_product_webhook($product_data=null) {
		$res = $this->Product_model->add_product($product_data);
		if($res) {
			log_message('info', 'Webhook Product added successfuly - Product ID -' . $product_data['id']);
			//create custom filed for new product on store
			$updated_data = array(
 				'name' => 'sequence_order',
 				'value' => '0'
 			);
        	$response=$this->custom_library->create_custom_field($product_data['id'],json_encode($updated_data,true));
        	if(isset($response['errors'])) {
 				log_message('info', 'Webhook - create custom field sequence order : Status -' . $response['status']. ', Error title -'.$response['title']);
 			}
        } else {
        	log_message('info', 'Webhook Error - product could not be added' . $product_data['id']);
        }
        return $res; 
	}

	public function delete_product_webhook($product_id=null) {
		$res = $this->Product_model->delete_product($product_id);
		
        if($res) {
        	log_message('info', 'Webhook Product deleted successfuly - Product ID -' . $product_id);
        } else {
        	log_message('info', 'Webhook Product could not be deleted' . $product_id);
        }
        return $res;
	}

	public function customers() {
		$data = array();
		$fullPostData = file_get_contents('php://input');
		$data = json_decode($fullPostData,true);

		log_message('info', 'Webhook called from store - Customer' . json_encode($data,true));

		if($data['scope']=='store/customer/updated') {
			if(!empty($data['data']['id'])){
				$customerId = $data['data']['id'];
				$customerDetails = $this->custom_library->get_customer_details($customerId);
				$customerDetails = json_decode($customerDetails,true);

				if(!empty($customerDetails)) {
					$customer_data = [
						'id'=>$customerDetails['id'],
			            'first_name'=>$customerDetails['first_name'],
			            'last_name'=>$customerDetails['last_name'],
			            'email'=>$customerDetails['email'],
			            'phone'=>$customerDetails['phone'],
			            'sent_mail'=>'0',
			            'customer_group_id'=>$customerDetails['customer_group_id'],
			            'updated_at'=>date('Y-m-d H:i:s')
			        ];

			        if($customer_data['customer_group_id']=='6') {
			        	//check if entry is exist or sent mail flag
			        	$response = $this->Customer_model->find_customer($customerId);
			        
				        if(empty($response)){
				        	$res = $this->add_customer_webhook($customer_data);
				        } else {
				        	if($response->sent_mail==0) {
				        		$this->send_email($customer_data);
				        	} else {
				        		$customer_data = [
									'id'=>$customerDetails['id'],
						            'first_name'=>$customerDetails['first_name'],
						            'last_name'=>$customerDetails['last_name'],
						            'email'=>$customerDetails['email'],
						            'phone'=>$customerDetails['phone'],
						            'sent_mail'=>'1',
						            'customer_group_id'=>$customerDetails['customer_group_id'],
						            'updated_at'=>date('Y-m-d H:i:s')
						        ];
				        		$res = $this->update_customer_webhook($customerId,$customer_data);
				        	}
				        }
			        } else {
			        	//check if entry is exist or sent mail flag
			        	$response = $this->Customer_model->find_customer($customerId);

			        	if(!empty($response)){	
			        		$customer_Updateddata = [
								'id'=>$customerDetails['id'],
					            'sent_mail'=>'0',
					            'updated_at'=>date('Y-m-d H:i:s')
					        	];
				        	$res = $this->update_customer_webhook($customerId,$customer_Updateddata);
				        }
			        }
				}
			}	
		}
	}

	public function add_customer_webhook($customer_data=null) {
		$res = $this->Customer_model->add_customer($customer_data);
    	if($res) {
        	$this->send_email($customer_data);
        	log_message('info', 'Webhook Customer added successfuly - Customer ID -' . $customer_data['id']);
        } else {
        	log_message('info', 'Webhook Customer could not be added' . $customer_data['id']);
        }
	    return $res;
	}

	public function update_customer_webhook($customerId=null,$customer_data=null){
		$res = $this->Customer_model->update_customer($customerId,$customer_data);
    	if($res) {
        	log_message('info', 'Webhook Customer updated successfuly - Customer ID -' . $customerId);
        } else {
        	log_message('info', 'Webhook Customer could not be updated' . $customerId);
        }
        return $res;
	}

	public function send_email($customer_data=null) {
		$this->load->config('email');
        $this->load->library('email');
        
        $from = $this->config->item('smtp_user');
        $to = $customer_data['email'];
        $subject = 'Your account is active - ROMAN THOMAS';

$message = <<<EOD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>romanthomas Email Template</title>
</head>

<body style="margin:0; padding:0; background-color:#f2ece6;">

<div style="display:none; font-size:1px; color:#f2ece6; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;">
  romanthomas — furniture and home décor.
</div>

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f2ece6; padding:24px 12px;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; background:#fffdfa; border:1px solid #ded2c6;">

<!-- Top bar -->
<tr>
<td style="height:6px; background:#b07a53;"></td>
</tr>

<!-- HEADER (UNCHANGED) -->
<tr>
<td style="padding:26px 28px 18px 28px; border-bottom:1px solid #e8ddd1; font-family:Arial;">

<div style="font-size:28px; font-weight:700;">
  <a href="https://romanthomas.com" target="_blank" style="color:#2f241c; text-decoration:none;">
    ROMANTHOMAS
  </a>
</div>

<div style="font-size:13px; color:#8f7d6c; margin-top:4px;">
  Timeless furniture for modern living
</div>

</td>
</tr>

<!-- BODY -->
<tr>
<td style="padding:26px 28px; font-family:Arial; color:#56493d;">

<h1 style="font-size:22px; margin-bottom:15px; color:#2c3e50;">
  Your Account Has Been Activated!
</h1>

<p>Hi {$customer_data['first_name']},</p>

<p>Congratulations! Your account has been successfully activated!</p>

<p>
You can now take advantage of member privileges to enhance your online shopping experience with us.
</p>

<p>
To sign in to your account, please visit 
<a href="https://romanthomas.com/" target="_blank" style="color:#9d6944;">
Roman Thomas
</a>
or 
<a href="https://romanthomas.com/account.php" target="_blank" style="color:#9d6944;">
click here
</a>.
</p>

<br>

<p>
<strong style="color:#2f241c;">Roman Thomas and Company</strong><br>
<a href="https://romanthomas.com/" target="_blank" style="color:#9d6944;">
https://romanthomas.com/
</a>
</p>

</td>
</tr>

<!-- FOOTER (UNCHANGED) -->
<tr>
<td style="padding:22px 28px; border-top:1px solid #e8ddd1; background:#f8f2eb; font-family:Arial;">

<p style="font-size:13px; color:#6f5b49;">
<strong style="color:#2f241c;">Romanthomas Furniture</strong><br>
Furniture and home décor for every room — living, dining, bedroom, and more.<br>
<a href="https://romanthomas.com/" target="_blank" style="color:#9d6944;">
www.romanthomas.com
</a>
</p>

<p>
<a href="https://instagram.com/romanthomasny/" target="_blank">
<img src="https://img.icons8.com/ios-filled/24/b07a53/instagram-new.png" width="24">
</a>
</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
EOD;

        $this->email->set_newline("\r\n");
        //$from = 'dkk@romanthomas.com';//$this->config->item('smtp_user');
        //$to = 'dipak.p@codaemon.com';
         $from = 'customer.help.romanthomas@gmail.com';//$this->config->item('smtp_user');
         $this->email->from($from, 'Roman Thomas'); 
      //  $this->email->from($from);
        $this->email->reply_to('customer.help.romanthomas@gmail.com');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            log_message('info', 'Webhook Customer sent mail successfuly - Customer ID -' . $customer_data['id']);

            //update sent_mail flag for the customer
            $customer_Updatedata = [
				'id'=>$customer_data['id'],
	       		'sent_mail'=>'1',
	            'updated_at'=>date('Y-m-d H:i:s')
	        ];
	        $res = $this->Customer_model->update_customer($customer_data['id'],$customer_Updatedata);
        } else {
            //show_error($this->email->print_debugger());
            log_message('info', 'Webhook Customer sent mail failed - Customer ID -' . $customer_data['id']);
        }
	}

	public function test_mail() {
		$message = "
				<html>
				<head>
				<title>Account activation</title>
				</head>
				<body>
				<div style='padding: 0px 20px 20px 20px;'>
				<h2 style='font-size: 22px; padding-bottom: 4px; color: #cc6600; border-bottom: dashed 1px gray;'>Your Account Has Been Activated!</h2>
				<p>Hi Admin,</p>
				<p>Congratulations! Your account has been successfully activated!</p>
				<p>You can now take advantages of member privileges to enhance your online shopping experience with us.</p>
				<p>To sign in to your account, please visit <a href='https://romanthomas.com/'>Roman Thomas</a> or <a href='https://romanthomas.com/account.php'>click here</a>.</p>
				<p><strong><span class='il'>Roman</span>&nbsp;<span class='il'>Thomas</span>&nbsp;and&nbsp;<span class='il'>Company</span></strong><br /><a href='https://romanthomas.com/' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://romanthomas.com//&amp;source=gmail&amp;ust=1614239474998000&amp;usg=AFQjCNEyiWrKu16jPL6RYAL8vPyJdE0veg'>https://<span class='il'>roman</span>thomas.com/</a></p>
				<p>&nbsp;</p>
				</div>
				</body>
				</html>";

	   	$this->load->config('email');
        $this->load->library('email');
        
        $from = 'dipak.p@codaemon.com';
        //$from = $this->config->item('smtp_user');
        $to = 'priya.s@codaemon.com';
        $subject = 'Your account is active - ROMAN THOMAS';

        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->reply_to('dipak.p@codaemon.com');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

	   if ($this->email->send()) {
	     print "success";
	   } else {
	   	 show_error($this->email->print_debugger());
	     print "Could not send email, please try again later";
	   }
	}

}
