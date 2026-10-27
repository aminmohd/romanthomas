<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'smtp.gmail.com', 
    'smtp_port' => 465,
    'smtp_user' => 'developer@codaemonsoftwares.com',
    'smtp_pass' => 'codaemon55',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE
);
*/

//AWS
/*$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'email-smtp.us-east-2.amazonaws.com', 
    'smtp_port' => 587,
    'smtp_user' => 'AKIA2SP4XNESSIPOE7GE',
    'smtp_pass' => 'BGDer7PzNIikPP8s7CoZZrLSmoOmVCcxD4GrtzooW5LR',
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    //'smtp_timeout' => '4', //in seconds
    //'charset' => 'iso-8859-1',
    'newline' => "\r\n",
    'wordwrap' => TRUE
);*/

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


//Mail trap
/*$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'sandbox.smtp.mailtrap.io', 
    'smtp_port' => 465,
    'smtp_user' => '578b99e018b309',
    'smtp_pass' => '7d670d4238cd92',
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE
);
*/
