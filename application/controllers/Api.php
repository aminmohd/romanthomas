<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");



class Api extends CI_Controller {

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
    }

    /* --- Products Grid View ------
     where admin can change product sequence through the drag and drop feature */
	public function get_customer()
	{
		$http_origin = !empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;

 		    $email = $this->input->get_post('email');
        $fullname = $this->input->get_post('fullname');
        $phone = $this->input->get_post('phone');
        $modelno = $this->input->get_post('modelno');
        $company = $this->input->get_post('company');
        $question = $this->input->get_post('question');
       // $fullname = "abcd@gmail.com";
      //  $phone = "abcd@gmail.com";
     //   $modelno = "abcd@gmail.com";
      //  $company = "abcd@gmail.com";
     //   $question =" abcd@gmail.com.";

        $customer_data = [
                  'email'=>$email,
                  'fullname'=>$fullname,
                  'phone'=>$phone,
                  'modelno'=>$modelno,
                  'company'=>$company,
                  'question'=>$question
         ];



        if ($customer_data) {

        	$this->send_email($customer_data);

            echo json_encode([
                'status' => 'success',
                'email' => $customer_data,
                'http_origin' => $http_origin
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email not provided'
            ]);
        }

	}



	public function send_email($customer_data=null) {
		$this->load->config('email');
        $this->load->library('email');
        
        $from = $this->config->item('smtp_user');
        $to = $customer_data['email'];
        $subject = "We've Received Your Inquiry – Thank You!";

        $fullname = $customer_data['fullname'];
        $email = $customer_data['email'];
        $phone = $customer_data['phone'];
        $modelno = $customer_data['modelno'];
        $company = $customer_data['company'];
        $question = $customer_data['question'];
$message = <<<EOD
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="x-apple-disable-message-reformatting" />
  <title>romanthomas Email Template</title>
</head>
<body style="margin:0; padding:0; background-color:#f2ece6;">
  <div style="display:none; font-size:1px; color:#f2ece6; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;">
    romanthomas — furniture and home décor. Visit romanthomas.com
  </div>

  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f2ece6; margin:0; padding:24px 12px;">
    <tr>
      <td align="center">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="width:100%; max-width:600px; background-color:#fffdfa; border:1px solid #ded2c6;">
          <tr>
            <td style="height:6px; background-color:#b07a53; font-size:0; line-height:0;">&nbsp;</td>
          </tr>

          <!-- Reusable Header Start -->
          <tr>
            <td style="padding:26px 28px 18px 28px; border-bottom:1px solid #e8ddd1; background-color:#fffdfa;">
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                  <td align="left" style="font-family:Arial, Helvetica, sans-serif; color:#2f241c;">
                    <div style="font-size:28px; line-height:1.2; font-weight:700; letter-spacing:0.6px;"><a href="https://romanthomas.com" target="_blank" style="color:#2f241c; text-decoration:none;">ROMANTHOMAS</a></div>
                    <div style="font-size:13px; line-height:1.5; color:#8f7d6c; margin-top:4px;">
                      Timeless furniture for modern living
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
          </tr>
          <!-- Reusable Header End -->

          <!-- Email Body — inquiry content from roman_api.html -->
          <tr>
            <td style="padding:26px 28px 10px 28px; font-family:Arial, Helvetica, sans-serif; color:#2f241c;" align="center">
              <h1 style="margin:0; font-size:22px; line-height:1.3; font-weight:700; color:#2c3e50;">We&rsquo;ve Received Your Inquiry &ndash; Thank You!</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:10px 28px 8px 28px; font-family:Arial, Helvetica, sans-serif; color:#56493d;">
              <p style="margin:0 0 10px 0; font-size:15px; line-height:1.65;">Hi {$fullname},</p>
              <p style="margin:0 0 10px 0; font-size:15px; line-height:1.65;">Thank you for reaching out! We&rsquo;ve successfully received your product inquiry with the following details.</p>
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 14px 28px; font-family:Arial, Helvetica, sans-serif;">
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; width:40%; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Name</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">{$fullname}</td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Email Address</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">{$email}</td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Phone Number</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">{$phone}</td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Model Number</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">{$modelno}</td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Company</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">{$company}</td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Question</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">{$question}</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 26px 28px; font-family:Arial, Helvetica, sans-serif; color:#56493d;">
              <p style="margin:0; font-size:15px; line-height:1.65;">
                We appreciate your interest and will get back to you shortly,<br />
                Thanks,<br />
                <strong style="color:#2f241c;">Roman Thomas</strong><br />
                <a href="https://romanthomas.com/" target="_blank" style="color:#9d6944; font-weight:700; text-decoration:underline;">https://romanthomas.com</a>
              </p>
            </td>
          </tr>
          <!-- Email Body End -->

          <!-- Reusable Footer Start -->
          <tr>
            <td style="padding:22px 28px 26px 28px; border-top:1px solid #e8ddd1; background-color:#f8f2eb; font-family:Arial, Helvetica, sans-serif;">
              <p style="margin:0 0 14px 0; font-size:13px; line-height:1.65; color:#6f5b49;">
                <strong style="color:#2f241c;">Romanthomas Furniture</strong><br />
                Furniture and home d&eacute;cor for every room &mdash; living, dining, bedroom, and accents &mdash; with sofas, seating, tables, lighting, storage, rugs, and more (assortment varies online and in store).<br />
                <a href="https://romanthomas.com/" target="_blank" style="color:#9d6944; font-weight:700; text-decoration:underline;">www.romanthomas.com</a>
              </p>

              <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 14px 0;">
                <tr>
                  <td>
                    <a href="https://instagram.com/romanthomasny/" target="_blank" style="text-decoration:none;">
                      <img src="https://img.icons8.com/ios-filled/24/b07a53/instagram-new.png" width="24" height="24" alt="Instagram" style="display:block; border:0; outline:none; text-decoration:none;" />
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!-- Reusable Footer End -->
        </table>
      </td>
    </tr>
  </table>
</body>
</html>

EOD;

        $this->email->set_newline("\r\n");
        $from = 'customer.help.romanthomas@gmail.com';//$this->config->item('smtp_user');
        $this->email->from($from, 'Roman Thomas'); 
        $this->email->reply_to('customer.help.romanthomas@gmail.com');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
          //  log_message('info', 'Webhook Customer sent mail successfuly - Customer ID -' . $customer_data['id']);

        } else {
            //show_error($this->email->print_debugger());
        //    log_message('info', 'Webhook Customer sent mail failed - Customer ID -' . $customer_data['id']);
        }
	}


}
