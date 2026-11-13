<?php

namespace App\Models;

use CodeIgniter\Model;

/** CI3: Product_model */
class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'id',
        'name',
        'sku',
        'image',
        'category_id',
        'page_url',
        'sequence_order',
        'is_visible',
        'created_at',
        'updated_at',
    ];

    public function getProductsByCategory($categoryId = null): array
    {
        return $this->builder()
            ->where('is_visible', '1')
            ->like('category_id', (string) $categoryId, 'both')
            ->orderBy('sequence_order', 'asc')
            ->get()
            ->getResult();
    }

    public function get_products_by_category($categoryId = null): array
    {
        return $this->getProductsByCategory($categoryId);
    }

    public function findProduct($productId)
    {
        return $this->find($productId);
    }

    public function find_product($productId)
    {
        return $this->findProduct($productId);
    }

    public function updateProduct($productId, ?array $productData = null): bool
    {
        if ($productId === null || $productData === null) {
            return false;
        }

        return $this->update($productId, $productData);
    }

    public function update_product($productId = null, $productData = null): bool
    {
        return $this->updateProduct($productId, is_array($productData) ? $productData : null);
    }

    public function addProduct(?array $productData = null): bool
    {
        if ($productData === null) {
            return false;
        }

        return $this->insert($productData) !== false;
    }

    public function add_product($productData = null): bool
    {
        return $this->addProduct(is_array($productData) ? $productData : null);
    }

    public function deleteProduct($productId = null): bool
    {
        if ($productId === null) {
            return false;
        }

        return $this->delete($productId);
    }

    public function delete_product($productId = null): bool
    {
        return $this->deleteProduct($productId);
    }

    public function updateSequenceOrder(int|string $productId, int $sequenceOrder): bool
    {
        return $this->builder()
            ->where('id', $productId)
            ->update(['sequence_order' => $sequenceOrder]);
    }
}
