<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {
  
  public function __construct()
  {
    $this->storeHash = 'p9tmltrvux';
  }
      
  public function call_curl($curl_url=null,$curl_request=null,$curl_httpheader=null,$curl_postfields=null)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $curl_url,
    CURLOPT_CUSTOMREQUEST => $curl_request,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_POSTFIELDS => $curl_postfields,
    CURLOPT_HTTPHEADER => $curl_httpheader
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return $err;
    } else {
      return $response;
    }
  }

  public function get_all_products() {
    $curl_url ='https://api.bigcommerce.com/stores/'.$this->storeHash.'/v3/catalog/products?limit=250'; //&page=2
    $curl_request = "GET";
    $curl_httpheader = array(
              "accept: application/json",
              "content-type: application/json",
              "x-auth-token: lvvh2szy5fiunnbo65lf2n5fx9e72xu"
      );
    $response = $this->call_curl($curl_url,$curl_request,$curl_httpheader);
    return $response;
  }


  //get product image
  public function get_product_image($product_id=null,$image_id=null) {
    $curl_url = 'https://api.bigcommerce.com/stores/'.$this->storeHash.'/v3/catalog/products/'.$product_id.'/images/'.$image_id;
    $curl_request = "GET"; 
    $curl_httpheader = array(
              "accept: application/json",
              "content-type: application/json",
              "x-auth-token: lvvh2szy5fiunnbo65lf2n5fx9e72xu"
      );
        $response = $this->call_curl($curl_url,$curl_request,$curl_httpheader);
    return $response; 
  }

  //get all product images
  public function get_all_product_images($product_id=null) {
    $curl_url = 'https://api.bigcommerce.com/stores/'.$this->storeHash.'/v3/catalog/products/'.$product_id.'/images';
    $curl_request = "GET"; 
    $curl_httpheader = array(
              "accept: application/json",
              "content-type: application/json",
              "x-auth-token: lvvh2szy5fiunnbo65lf2n5fx9e72xu"
      );
        $response = $this->call_curl($curl_url,$curl_request,$curl_httpheader);
    return $response;
  }

  public function get_all_categories() {
    $curl_url ='https://api.bigcommerce.com/stores/'.$this->storeHash.'/v3/catalog/categories';
    $curl_request = "GET";
    $curl_httpheader = array(
              "accept: application/json",
              "content-type: application/json",
              "x-auth-token: lvvh2szy5fiunnbo65lf2n5fx9e72xu"
      );
    $response = $this->call_curl($curl_url,$curl_request,$curl_httpheader);
    return $response;
  }

  public function get_all_custom_fileds($product_id=null) {
    $curl_url ='https://api.bigcommerce.com/stores/'.$this->storeHash.'/v3/catalog/products/'.$product_id.'/custom-fields';
    $curl_request = "GET";
    $curl_httpheader = array(
              "accept: application/json",
              "content-type: application/json",
              "x-auth-token: lvvh2szy5fiunnbo65lf2n5fx9e72xu"
      );
    $response = $this->call_curl($curl_url,$curl_request,$curl_httpheader);
    return $response;
  }

  public function update_custom_filed($product_id=null,$custom_filed_id=null,$update_data=null) 
  {
    $curl_url = 'https://api.bigcommerce.com/stores/'.$this->storeHash.'/v3/catalog/products/'.$product_id.'/custom-fields/'.$custom_filed_id;
    $curl_request = "PUT"; 
    $curl_postfields = $update_data;
    $curl_httpheader = array(
          "accept: application/json",
          "content-type: application/json",
          "x-auth-token: lvvh2szy5fiunnbo65lf2n5fx9e72xu"
    );
    $response = $this->call_curl($curl_url,$curl_request,$curl_httpheader,$curl_postfields);
    return $response;
  }

    /*
    public function delete_product($product_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.bigcommerce.com/stores/338iko5ktv/v3/catalog/products/'.$product_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "DELETE",
          CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/json",
            "x-auth-token: cfm2azogphmfvnyrhm61qcdww66xqm4"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return $err;
        } else {
          return $response;
        }
    }

    public function add_product($product_json) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.bigcommerce.com/stores/338iko5ktv/v3/catalog/products",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $product_json,
          CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/json",
            "x-auth-token: cfm2azogphmfvnyrhm61qcdww66xqm4"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return $err;
        } else {
          return $response;
        }
    }*/

}
?>