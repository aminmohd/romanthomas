<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('main_model');
    }

    private function logged_in()
    {
        if( ! $this->session->userdata('authenticated')){
            redirect('login');
        }
    }

    /* Login */
	public function login()
    {

	if($this->session->userdata('authenticated')){
            redirect('products/index');
        }

        $data['title'] = "Login";
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'required');
        
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        if($this->form_validation->run() == false) {
		$data['main_content'] = array('login-view');
        	$this->load->view('templates/main_template', $data);
        } else {
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->security->xss_clean($this->input->post('password'));
            $user = $this->main_model->login($email, $password);
            
            if($user){
                $userdata = array(
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'authenticated' => TRUE
                );
                
                $this->session->set_userdata($userdata);

                redirect('products/index');
            }
            else {
                $this->session->set_flashdata('message', 'Invalid email or password');
                redirect('login');
            }
        }
    }

    /* Logout */
    public function logout()
    {
    	$this->session->unset_userdata('authenticated');
        $this->session->sess_destroy();
        redirect('login');
    }
}
