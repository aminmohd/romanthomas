<?php

namespace App\Models;

use CodeIgniter\Model;

/** CI3: Customer_model */
class CustomerModel extends Model
{
    protected $table         = 'customers';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'sent_mail',
        'customer_group_id',
        'created_at',
        'updated_at',
    ];

    public function addCustomer(?array $customerData = null): bool
    {
        if ($customerData === null) {
            return false;
        }

        return $this->insert($customerData) !== false;
    }

    public function add_customer($customerData = null): bool
    {
        return $this->addCustomer(is_array($customerData) ? $customerData : null);
    }

    public function updateCustomer($customerId = null, ?array $customerData = null): bool
    {
        if ($customerId === null || $customerData === null) {
            return false;
        }

        return $this->update($customerId, $customerData);
    }

    public function update_customer($customerId = null, $customerData = null): bool
    {
        return $this->updateCustomer($customerId, is_array($customerData) ? $customerData : null);
    }

    public function findCustomer($customerId)
    {
        return $this->find($customerId);
    }

    public function find_customer($customerId)
    {
        return $this->findCustomer($customerId);
    }
}
