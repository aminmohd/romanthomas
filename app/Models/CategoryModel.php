<?php

namespace App\Models;

use CodeIgniter\Model;

/** CI3: Category_model */
class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'id',
        'parent_id',
        'category_id',
        'name',
        'image_url',
        'is_parent',
        'is_visible',
        'created_at',
        'modified_at',
    ];

    public function getCategoryDetails($categoryId = null): array
    {
        return $this->builder()
            ->select('id,name,parent_id,image_url')
            ->where('id', $categoryId)
            ->get()
            ->getResult();
    }

    public function get_category_details($categoryId = null): array
    {
        return $this->getCategoryDetails($categoryId);
    }

    public function findCatData($id)
    {
        return $this->find($id);
    }

    public function find_catData($id)
    {
        return $this->findCatData($id);
    }

    public function getSubchilds($categoryId = null): array
    {
        return $this->builder()
            ->select('id,name,parent_id,image_url')
            ->where('parent_id', $categoryId)
            ->where('is_visible', '1')
            ->get()
            ->getResult();
    }

    public function get_subchilds($categoryId = null): array
    {
        return $this->getSubchilds($categoryId);
    }

    public function getMainCategories()
    {
        return $this->where('parent_id', '0')->first();
    }

    public function get_main_categories()
    {
        return $this->getMainCategories();
    }

    public function getParentCategories(): array
    {
        return $this->where('parent_id', '0')->findAll();
    }

    public function get_parent_categories(): array
    {
        return $this->getParentCategories();
    }

    public function getAllCategory(): array
    {
        $categories = $this->getParentCategories();
        $childs     = [];

        foreach ($categories as $ck => $cat) {
            $subCategoriesLevel1 = $this->getSubchilds($cat->id);

            $childs[$ck]['id']        = $cat->id;
            $childs[$ck]['name']      = $cat->name;
            $childs[$ck]['is_parent'] = $cat->parent_id;

            if (! empty($subCategoriesLevel1)) {
                foreach ($subCategoriesLevel1 as $sk => $scat) {
                    $subCategoriesLevel2 = $this->getSubchilds($scat->id);

                    $childs[$ck]['childs'][$sk]['id']        = $scat->id;
                    $childs[$ck]['childs'][$sk]['name']      = $scat->name;
                    $childs[$ck]['childs'][$sk]['parent_id'] = $scat->parent_id;

                    if (! empty($subCategoriesLevel2)) {
                        foreach ($subCategoriesLevel2 as $skey => $schild) {
                            $childs[$ck]['childs'][$sk]['subchilds'][$skey]['id']        = $schild->id;
                            $childs[$ck]['childs'][$sk]['subchilds'][$skey]['name']      = $schild->name;
                            $childs[$ck]['childs'][$sk]['subchilds'][$skey]['parent_id'] = $schild->parent_id;
                        }
                    }
                }
            } else {
                $childs[$ck]['childs'] = [];
            }
        }

        return $childs;
    }

    public function get_all_category(): array
    {
        return $this->getAllCategory();
    }

    public function insertCategoryRow(array $saveData): bool
    {
        return $this->insert($saveData) !== false;
    }
}
