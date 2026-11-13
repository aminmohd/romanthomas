<?php

namespace App\Libraries;

use Config\BigCommerce;

/**
 * CI3: system/libraries/Custom_library.php (CI_Custom_library), autoloaded as custom_library.
 */
class BigCommerceClient
{
    protected string $storeHash;

    protected string $authToken;

    protected string $clientId;

    public function __construct(?BigCommerce $config = null)
    {
        $config ??= config(BigCommerce::class);

        $this->storeHash  = (string) $config->storeHash;
        $this->authToken  = (string) $config->authToken;
        $this->clientId   = (string) $config->clientId;
    }

    /** CI3: call_curl */
    public function callCurl(?string $curlUrl = null, ?string $curlRequest = null, ?array $curlHttpheader = null, $curlPostfields = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $curlUrl,
            CURLOPT_CUSTOMREQUEST  => $curlRequest,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS     => $curlPostfields,
            CURLOPT_HTTPHEADER     => $curlHttpheader,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        if ($err) {
            return $err;
        }

        return $response;
    }

    public function getAllProducts()
    {
        $curlUrl     = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/products?limit=250';
        $curlRequest = 'GET';
        $curlHeaders = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders);
    }

    public function getProductImage($productId = null, $imageId = null)
    {
        $curlUrl     = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/products/' . $productId . '/images/' . $imageId;
        $curlRequest = 'GET';
        $curlHeaders = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders);
    }

    public function getAllProductImages($productId = null)
    {
        $curlUrl     = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/products/' . $productId . '/images';
        $curlRequest = 'GET';
        $curlHeaders = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders);
    }

    public function getAllCategories()
    {
        $curlUrl     = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/categories';
        $curlRequest = 'GET';
        $curlHeaders = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders);
    }

    /** CI3 spelling: get_all_custom_fileds */
    public function getAllCustomFileds($productId = null)
    {
        $curlUrl     = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/products/' . $productId . '/custom-fields';
        $curlRequest = 'GET';
        $curlHeaders = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders);
    }

    /** CI3 spelling: update_custom_filed — returns raw response string (CI3 did not json_decode before error checks). */
    public function updateCustomFiled($productId = null, $customFiledId = null, $updateData = null)
    {
        $curlUrl        = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/products/' . $productId . '/custom-fields/' . $customFiledId;
        $curlRequest    = 'PUT';
        $curlPostfields = $updateData;
        $curlHeaders    = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders, $curlPostfields);
    }

    /** CI3 spelling: get_produdct_details */
    public function getProdudctDetails($productId = null)
    {
        $curlUrl     = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/products/' . $productId;
        $curlRequest = 'GET';
        $curlHeaders = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-client:' . $this->clientId,
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders);
    }

    public function createCustomField($productId = null, $updateData = null)
    {
        $curlUrl        = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3/catalog/products/' . $productId . '/custom-fields';
        $curlRequest    = 'POST';
        $curlPostfields = $updateData;
        $curlHeaders    = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders, $curlPostfields);
    }

    public function getCustomerDetails($customerId = null)
    {
        $curlUrl     = 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v2/customers/' . $customerId;
        $curlRequest = 'GET';
        $curlHeaders = [
            'accept: application/json',
            'content-type: application/json',
            'x-auth-token:' . $this->authToken,
        ];

        return $this->callCurl($curlUrl, $curlRequest, $curlHeaders);
    }
}
