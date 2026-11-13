<?php

namespace App\Controllers;

use App\Libraries\BigCommerceClient;
use App\Libraries\RomanMailer;
use App\Models\CustomerModel;
use App\Models\ProductModel;

class Webhooks extends BaseController
{
    protected ProductModel $productModel;

    protected CustomerModel $customerModel;

    protected BigCommerceClient $bigCommerce;

    protected RomanMailer $mailer;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->productModel  = model(ProductModel::class);
        $this->customerModel = model(CustomerModel::class);
        $this->bigCommerce   = service('bigcommerce');
        $this->mailer        = new RomanMailer();
    }

    public function index()
    {
        $fullPostData = $this->request->getBody();
        $data         = json_decode($fullPostData, true);

        $this->response->setStatusCode(200);

        log_message('info', 'Webhook called from store - Product' . json_encode($data));

        if (! empty($data['data']['id'])) {
            $productId      = $data['data']['id'];
            $productDetails = json_decode($this->bigCommerce->getProdudctDetails($productId), true);

            if (! empty($productDetails['data'])) {
                $productImg = json_decode($this->bigCommerce->getAllProductImages($productDetails['data']['id']), true);
                $imgUrl     = null;

                if (! empty($productImg['data'])) {
                    foreach ($productImg['data'] as $provalue) {
                        if ($provalue['is_thumbnail'] == 1) {
                            $imgUrl = $provalue['url_thumbnail'];
                        }
                    }
                }

                $productData = [
                    'id'          => $productDetails['data']['id'],
                    'name'        => $productDetails['data']['name'],
                    'sku'         => $productDetails['data']['sku'],
                    'image'       => $imgUrl,
                    'page_url'    => $productDetails['data']['custom_url']['url'],
                    'category_id' => json_encode(implode(',', $productDetails['data']['categories'])),
                    'is_visible'  => $productDetails['data']['is_visible'],
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];

                if ($data['scope'] === 'store/product/created') {
                    $this->add_product_webhook($productData);

                    echo 'product added successfuly';
                } elseif ($data['scope'] === 'store/product/updated') {
                    $this->update_product_webhook($productData);

                    echo 'product updated successfuly';
                }
            } elseif ($data['scope'] === 'store/product/deleted') {
                $this->delete_product_webhook($data['data']['id']);

                echo 'product deleted successfuly';
            } else {
                log_message('info', 'Webhook Error - No data found');
            }
        } else {
            log_message('info', 'Webhook Error - No data found');
        }

        exit;
    }

    public function update_product_webhook($productData = null)
    {
        $res = $this->productModel->update_product($productData['id'], $productData);

        if ($res) {
            log_message('info', 'Webhook Product updated successfuly - Product ID -' . $productData['id']);
        } else {
            log_message('info', 'Webhook Product could not be updated - Product ID -' . $productData['id']);
        }

        return $res;
    }

    public function add_product_webhook($productData = null)
    {
        $res = $this->productModel->add_product($productData);

        if ($res) {
            log_message('info', 'Webhook Product added successfuly - Product ID -' . $productData['id']);

            $updatedData = [
                'name'  => 'sequence_order',
                'value' => '0',
            ];
            $response = $this->bigCommerce->createCustomField($productData['id'], json_encode($updatedData, true));

            if (isset($response['errors'])) {
                log_message('info', 'Webhook - create custom field sequence order : Status -' . $response['status'] . ', Error title -' . $response['title']);
            }
        } else {
            log_message('info', 'Webhook Error - product could not be added' . $productData['id']);
        }

        return $res;
    }

    public function delete_product_webhook($productId = null)
    {
        $res = $this->productModel->delete_product($productId);

        if ($res) {
            log_message('info', 'Webhook Product deleted successfuly - Product ID -' . $productId);
        } else {
            log_message('info', 'Webhook Product could not be deleted' . $productId);
        }

        return $res;
    }

    public function customers()
    {
        $fullPostData = $this->request->getBody();
        $data         = json_decode($fullPostData, true);

        log_message('info', 'Webhook called from store - Customer' . json_encode($data));

        if (($data['scope'] ?? null) === 'store/customer/updated') {
            if (! empty($data['data']['id'])) {
                $customerId      = $data['data']['id'];
                $customerDetails = json_decode($this->bigCommerce->getCustomerDetails($customerId), true);

                if (! empty($customerDetails)) {
                    $customerData = [
                        'id'                => $customerDetails['id'],
                        'first_name'        => $customerDetails['first_name'],
                        'last_name'         => $customerDetails['last_name'],
                        'email'             => $customerDetails['email'],
                        'phone'             => $customerDetails['phone'],
                        'sent_mail'         => '0',
                        'customer_group_id' => $customerDetails['customer_group_id'],
                        'updated_at'        => date('Y-m-d H:i:s'),
                    ];

                    if ($customerData['customer_group_id'] == '6') {
                        $response = $this->customerModel->find_customer($customerId);

                        if (empty($response)) {
                            $this->add_customer_webhook($customerData);
                        } elseif ($response->sent_mail == 0) {
                            $this->send_email($customerData);
                        } else {
                            $customerData = [
                                'id'                => $customerDetails['id'],
                                'first_name'        => $customerDetails['first_name'],
                                'last_name'         => $customerDetails['last_name'],
                                'email'             => $customerDetails['email'],
                                'phone'             => $customerDetails['phone'],
                                'sent_mail'         => '1',
                                'customer_group_id' => $customerDetails['customer_group_id'],
                                'updated_at'        => date('Y-m-d H:i:s'),
                            ];
                            $this->update_customer_webhook($customerId, $customerData);
                        }
                    } else {
                        $response = $this->customerModel->find_customer($customerId);

                        if (! empty($response)) {
                            $customerUpdateddata = [
                                'id'         => $customerDetails['id'],
                                'sent_mail'  => '0',
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            $this->update_customer_webhook($customerId, $customerUpdateddata);
                        }
                    }
                }
            }
        }
    }

    public function add_customer_webhook($customerData = null)
    {
        $res = $this->customerModel->add_customer($customerData);

        if ($res) {
            $this->send_email($customerData);
            log_message('info', 'Webhook Customer added successfuly - Customer ID -' . $customerData['id']);
        } else {
            log_message('info', 'Webhook Customer could not be added' . $customerData['id']);
        }

        return $res;
    }

    public function update_customer_webhook($customerId = null, $customerData = null)
    {
        $res = $this->customerModel->update_customer($customerId, $customerData);

        if ($res) {
            log_message('info', 'Webhook Customer updated successfuly - Customer ID -' . $customerId);
        } else {
            log_message('info', 'Webhook Customer could not be updated' . $customerId);
        }

        return $res;
    }

    public function send_email($customerData = null)
    {
        $to      = $customerData['email'];
        $subject = 'Your account is active - ROMAN THOMAS';

        if ($this->mailer->sendOperationalTemplate(
            $to,
            $subject,
            RomanMailer::TEMPLATE_ACCOUNT_ACTIVATION,
            ['first_name' => $customerData['first_name'] ?? '']
        )) {
            log_message('info', 'Webhook Customer sent mail successfuly - Customer ID -' . $customerData['id']);

            $customerUpdatedata = [
                'id'         => $customerData['id'],
                'sent_mail'  => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->customerModel->update_customer($customerData['id'], $customerUpdatedata);
        } else {
            log_message('info', 'Webhook Customer sent mail failed - Customer ID -' . $customerData['id']);
        }
    }

    public function test_mail()
    {
        $emailConfig = config(\Config\Email::class);
        $from        = env('email.testMailFrom', $emailConfig->testMailFrom);
        $to          = env('email.testMailTo', $emailConfig->testMailTo);
        $replyTo     = env('email.testMailReplyTo', $emailConfig->testMailReplyTo);
        $subject     = 'Your account is active - ROMAN THOMAS';

        if ($from === '' || $to === '') {
            log_message('error', 'Webhooks test_mail: email.testMailFrom and email.testMailTo must be set in .env');

            throw new \RuntimeException('Test mail is not configured.');
        }

        if ($this->mailer->sendTemplate(
            $to,
            $subject,
            RomanMailer::TEMPLATE_ACCOUNT_ACTIVATION_SIMPLE,
            ['first_name' => 'Admin'],
            $from,
            '',
            $replyTo ?: $from
        )) {
            echo 'success';
        } else {
            log_message('error', 'Webhooks test_mail send failed.');
            throw new \RuntimeException('Could not send test email.');
        }
    }
}
