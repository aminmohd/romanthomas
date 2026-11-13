<?php

namespace App\Controllers;

use App\Libraries\BigCommerceClient;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Categories extends BaseController
{
    protected CategoryModel $categoryModel;

    protected BigCommerceClient $bigCommerce;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->categoryModel = model(CategoryModel::class);
        $this->bigCommerce   = service('bigcommerce');
    }

    /** CI3 had no index(); URI `categories` would error. */
    public function index()
    {
        throw PageNotFoundException::forPageNotFound();
    }

    public function add_category_into_DB()
    {
        $mappingData = $this->bigCommerce->getAllCategories();
        $categories  = json_decode($mappingData, true);

        if (! empty($categories['data'])) {
            foreach ($categories['data'] as $value) {
                $saveData = [
                    'id'          => $value['id'],
                    'parent_id'   => $value['parent_id'],
                    'name'        => $value['name'],
                    'is_parent'   => ($value['parent_id'] == 0) ? '1' : '0',
                    'image_url'   => $value['image_url'],
                    'is_visible'  => $value['is_visible'],
                    'created_at'  => date('Y-m-d H:i:s'),
                ];

                $this->categoryModel->insertCategoryRow($saveData);
            }
        } elseif (isset($categories['errors'])) {
            log_message('info', 'Api errors - Add all categories from store into DB : Status -' . $categories['status'] . ', Error title -' . $categories['title']);
        }

        return redirect()->to(site_url('products/index'));
    }
}
