<?php

namespace App\Controllers;

use App\Libraries\RomanMailer;

/** CI3 debug / test mail controller — preserve behavior. */
class Welcome extends BaseController
{
    protected RomanMailer $mailer;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->mailer = new RomanMailer();
    }

    public function index()
    {
        $testRecipient = env('email.welcomeTestRecipient', config(\Config\Email::class)->welcomeTestRecipient);

        $customerData = [
            'id'                => 145,
            'first_name'        => '',
            'last_name'         => '',
            'email'             => $testRecipient,
            'phone'             => '8080656175',
            'sent_mail'         => '1',
            'customer_group_id' => 1,
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        $this->send_email($customerData);
    }

    public function send_email($customerData = null)
    {
        $to      = $customerData['email'];
        $subject = 'Test Email';

        if ($this->mailer->sendOperationalTemplate(
            $to,
            $subject,
            RomanMailer::TEMPLATE_ACCOUNT_ACTIVATION_SIMPLE,
            ['first_name' => $customerData['first_name'] ?? '']
        )) {
            echo 'mail sent';
        } else {
            echo 'mail not sent';
        }

        if (CI_DEBUG) {
            echo $this->mailer->printDebugger();
        }
    }
}
