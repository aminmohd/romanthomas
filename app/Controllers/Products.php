<?php

namespace App\Controllers;

use App\Libraries\BigCommerceClient;
use App\Models\CategoryModel;
use App\Models\ProductModel;

class Products extends BaseController
{
    protected CategoryModel $categoryModel;

    protected ProductModel $productModel;

    protected BigCommerceClient $bigCommerce;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->categoryModel = model(CategoryModel::class);
        $this->productModel  = model(ProductModel::class);
        $this->bigCommerce   = service('bigcommerce');
    }

    public function index($categoryId = null)
    {
        $data = [
            'main_CategoryName' => '',
            'categoryName'      => '',
            'subcatName'        => '',
            'main_CategoryID'   => '',
            'categoryID'        => '',
            'subcatID'          => '',
        ];

        if (! empty($categoryId)) {
            $subcatData = json_decode(json_encode($this->categoryModel->findCatData($categoryId)));
            $subcatId   = $subcatData->parent_id;

            if ($subcatId == '0') {
                $data['main_CategoryName'] = $subcatData->name;
                $data['main_CategoryID']   = $subcatData->id;
            } else {
                $catData = json_decode(json_encode($this->categoryModel->findCatData($subcatId)));
                $catId   = $catData->parent_id;

                if ($catId == '0') {
                    $data['main_CategoryName'] = $catData->name;
                    $data['categoryName']      = $subcatData->name;
                    $data['main_CategoryID']   = $catData->id;
                    $data['categoryID']        = $subcatData->id;
                } else {
                    $mainCatData               = json_decode(json_encode($this->categoryModel->findCatData($catId)));
                    $data['main_CategoryName'] = $mainCatData->name;
                    $data['categoryName']      = $catData->name;
                    $data['subcatName']        = $subcatData->name;
                    $data['main_CategoryID']   = $mainCatData->id;
                    $data['categoryID']        = $catData->id;
                    $data['subcatID']          = $subcatData->id;
                }
            }

            $subcategories = $this->categoryModel->getSubchilds($categoryId);

            if (empty($subcategories)) {
                $data['products']     = $this->productModel->getProductsByCategory($categoryId);
                $data['show_prodcuts'] = 1;
            } else {
                $data['category_as_products'] = $subcategories;
                $data['show_prodcuts']        = 0;
            }
        } else {
            $getMainCategory              = $this->categoryModel->getMainCategories();
            $data['category_as_products'] = $this->categoryModel->getSubchilds($getMainCategory->id);
            $data['main_CategoryName']    = $getMainCategory->name;
            $data['main_CategoryID']      = $getMainCategory->id;
            $data['show_prodcuts']        = 0;
        }

        $data['categories']   = $this->categoryModel->getAllCategory();
        $data['main_content'] = ['products/index'];

        return view('templates/main_template', $data);
    }

    public function update_squence_order()
    {
        $position = $this->request->getPost('position');

        if ($position) {
            $i = 1;

            foreach ($position as $v) {
                if ($this->productModel->updateSequenceOrder($v, $i)) {
                    $res                   = $this->bigCommerce->getAllCustomFileds($v);
                    $productCustomFileds   = json_decode($res, true);

                    if (isset($productCustomFileds['errors'])) {
                        log_message('info', 'Api errors - Get all custom fields : Status -' . $productCustomFileds['status'] . ', Error title -' . $productCustomFileds['title']);
                    } elseif (! empty($productCustomFileds['data'])) {
                        foreach ($productCustomFileds['data'] as $pval) {
                            if ($pval['name'] === 'sequence_order') {
                                $updatedData = [
                                    'name'  => 'sequence_order',
                                    'value' => strval($i),
                                ];

                                $response = $this->bigCommerce->updateCustomFiled($v, $pval['id'], json_encode($updatedData));

                                if (isset($response['errors'])) {
                                    log_message('info', 'Api errors - Update custom field sequence order : Status -' . $response['status'] . ', Error title -' . $response['title']);
                                }
                            }
                        }
                    }
                }
                $i++;
            }
        }
    }

    public function add_products_into_DB()
    {
        $mappingData = $this->bigCommerce->getAllProducts();
        $products    = json_decode($mappingData, true);

        if (! empty($products['data'])) {
            foreach ($products['data'] as $key => $value) {
                $productImg = $this->bigCommerce->getAllProductImages($value['id']);
                $productImg = json_decode($productImg, true);

                $products['data'][$key]['product_images'] = ['url_thumbnail' => ''];

                if (! empty($productImg['data'])) {
                    foreach ($productImg['data'] as $provalue) {
                        if ($provalue['is_thumbnail'] == 1) {
                            $products['data'][$key]['product_images'] = $provalue;
                        }
                    }
                }

                $saveData = [
                    'id'          => $value['id'],
                    'name'        => $value['name'],
                    'sku'         => $value['sku'],
                    'image'       => $products['data'][$key]['product_images']['url_thumbnail'] ?? '',
                    'page_url'    => $value['custom_url']['url'],
                    'category_id' => json_encode(implode(',', $value['categories'])),
                    'is_visible'  => $value['is_visible'],
                    'created_at'  => date('Y-m-d H:i:s'),
                ];

                $this->productModel->addProduct($saveData);
            }
        } elseif (isset($products['errors'])) {
            log_message('info', 'Api errors - Add all products from store into DB : Status -' . $products['status'] . ', Error title -' . $products['title']);
        }

        return redirect()->to(site_url('products/index'));
    }
}
