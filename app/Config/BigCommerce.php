<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * BigCommerce API — credentials only via .env (bigcommerce.*).
 */
class BigCommerce extends BaseConfig
{
    public string $storeHash = '';

    public string $authToken = '';

    public string $clientId = '';

    public function __construct()
    {
        parent::__construct();

        $this->storeHash = env('bigcommerce.storeHash', $this->storeHash) ?? '';
        $this->authToken = env('bigcommerce.authToken', $this->authToken) ?? '';
        $this->clientId  = env('bigcommerce.clientId', $this->clientId) ?? '';
    }
}
