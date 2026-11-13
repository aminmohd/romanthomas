<?php

namespace App\Controllers;

use App\Libraries\RomanMailer;

class Api extends BaseController
{
    protected RomanMailer $mailer;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->mailer = new RomanMailer();
    }

    public function get_customer()
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        if ($this->request->getMethod() === 'OPTIONS') {
            return $this->response->setStatusCode(204);
        }

        $httpOrigin = $this->request->getHeaderLine('Origin') ?: null;

        $customerData = [
            'email'    => $this->request->getPost('email') ?? $this->request->getGet('email'),
            'fullname' => $this->request->getPost('fullname') ?? $this->request->getGet('fullname'),
            'phone'    => $this->request->getPost('phone') ?? $this->request->getGet('phone'),
            'modelno'  => $this->request->getPost('modelno') ?? $this->request->getGet('modelno'),
            'company'  => $this->request->getPost('company') ?? $this->request->getGet('company'),
            'question' => $this->request->getPost('question') ?? $this->request->getGet('question'),
        ];

        if ($customerData) {
            $this->send_email($customerData);

            return $this->response->setJSON([
                'status'      => 'success',
                'email'       => $customerData,
                'http_origin' => $httpOrigin,
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Email not provided',
        ]);
    }

    public function send_email($customerData = null)
    {
        $to      = $customerData['email'];
        $subject = "We've Received Your Inquiry – Thank You!";

        $this->mailer->sendOperationalTemplate(
            $to,
            $subject,
            RomanMailer::TEMPLATE_PRODUCT_INQUIRY,
            [
                'fullname' => $customerData['fullname'],
                'email'    => $customerData['email'],
                'phone'    => $customerData['phone'],
                'modelno'  => $customerData['modelno'],
                'company'  => $customerData['company'],
                'question' => $customerData['question'],
            ]
        );
    }
}
