<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->view('welcome_message');
			$customer_data = [	'id'=>145,
						            'first_name'=>"roshani",
						            'last_name'=>"malik",
						            'email'=>"saurab.c@codaemonsoftwares.com",
						            'phone'=>"8080656175",
						            'sent_mail'=>'1',
						            'customer_group_id'=>1,
						            'updated_at'=>date('Y-m-d H:i:s')
						        ];
		$this->send_email($customer_data);
	}
	public function send_email($customer_data=null) {
		$this->load->config('email');
        $this->load->library('email');
        
        $from = $this->config->item('smtp_user');
        $to = $customer_data['email'];
       // $subject = 'Your account is active - ROMAN THOMAS';
 $subject = 'Test Email';
        $message = "
				<html>
				<head>
				<title>Account activation</title>
				</head>
				<body>
				<div style='padding: 0px 20px 20px 20px;'>
				<h2 style='font-size: 22px; padding-bottom: 4px; color: #cc6600; border-bottom: dashed 1px gray;'>Your Account Has Been Activated!</h2>
				<p>Hi ".$customer_data['first_name'].",</p>
				<p>Congratulations! Your account has been successfully activated!</p>
				<p>You can now take advantages of member privileges to enhance your online shopping experience with us.</p>
				<p>To sign in to your account, please visit <a href='https://romanthomas.com/'>Roman Thomas</a> or <a href='https://romanthomas.com/account.php'>click here</a>.</p>
				<p><strong><span class='il'>Roman</span>&nbsp;<span class='il'>Thomas</span>&nbsp;and&nbsp;<span class='il'>Company</span></strong><br /><a href='https://romanthomas.com/' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://romanthomas.com//&amp;source=gmail&amp;ust=1614239474998000&amp;usg=AFQjCNEyiWrKu16jPL6RYAL8vPyJdE0veg'>https://<span class='il'>roman</span>thomas.com/</a></p>
				<p>&nbsp;</p>
				</div>
				</body>
				</html>";

        $this->email->set_newline("\r\n");
       $from = 'customer.help.romanthomas@gmail.com';//$this->config->item('smtp_user');
      //  $to = 'saurab.c@codaemonsoftwares.com';
          $this->email->from($from, 'Roman Thomas'); 
      //  $this->email->from($from);
        $this->email->reply_to($to);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            echo "mail sent";
        } else {
            //show_error($this->email->print_debugger());
           echo "mail not sent";
        }
        echo $this->email->print_debugger();
	}
}
