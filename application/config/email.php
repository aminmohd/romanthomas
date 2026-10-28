<?php defined('BASEPATH') OR exit('No direct script access allowed');


$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'smtp.gmail.com', 
    'smtp_port' => 587,
    'smtp_user' => 'customer.help.romanthomas@gmail.com',
    'smtp_pass' => 'zxeamlqsyofoxfts',
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '10', //in seconds
    'charset' => 'UTF-8',
 //   'wordwrap' => TRUE,
 //   'authentication'=>'plain',
   // 'enable_starttls_auto'=>'true',
   // 'openssl_verify_model' => 'none'
);
