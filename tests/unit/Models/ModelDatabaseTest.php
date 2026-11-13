<?php

namespace Tests\Unit\Models;

use App\Models\CategoryModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

/**
 * Read-heavy checks against the existing `roman_thomas` database.
 * Write test uses a transaction rollback so no durable rows are left.
 */
final class ModelDatabaseTest extends CIUnitTestCase
{
    private static bool $dbChecked = false;

    private static bool $dbAvailable = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (! self::$dbChecked) {
            self::$dbChecked = true;

            try {
                $db = Database::connect('tests');
                $db->connect();
                $db->query('SELECT 1 FROM users LIMIT 1');
                self::$dbAvailable = true;
            } catch (\Throwable) {
                self::$dbAvailable = false;
            }
        }

        if (! self::$dbAvailable) {
            $this->markTestSkipped('MySQL connection for roman_thomas is not available.');
        }
    }

    public function testUserLoginInvalidCredentialsReturnsFalse(): void
    {
        $model = model(UserModel::class);

        $this->assertFalse($model->login('no-such-user@example.invalid', 'not-a-real-password'));
    }

    public function testProductGetProductsByCategoryReturnsArray(): void
    {
        $model   = model(ProductModel::class);
        $results = $model->getProductsByCategory('1');

        $this->assertIsArray($results);

        if ($results !== []) {
            $this->assertIsObject($results[0]);
            $this->assertObjectHasProperty('id', $results[0]);
        }
    }

    public function testCategoryGetMainCategoriesReturnsObjectOrNull(): void
    {
        $model = model(CategoryModel::class);
        $row   = $model->getMainCategories();

        $this->assertTrue($row === null || is_object($row));
    }

    public function testCategoryGetCategoryDetailsUsesIdFilter(): void
    {
        $model = model(CategoryModel::class);
        $main  = $model->getMainCategories();

        if ($main === null) {
            $this->markTestSkipped('No root category (parent_id = 0) in database.');

            return;
        }

        $details = $model->getCategoryDetails($main->id);

        $this->assertIsArray($details);
        $this->assertNotEmpty($details);
        $this->assertSame((int) $main->id, (int) $details[0]->id);
    }

    public function testCustomerFindMissingReturnsNull(): void
    {
        $model = model(CustomerModel::class);

        $this->assertNull($model->findCustomer(2147483646));
    }

    public function testProductAddAndDeleteInsideTransactionRollback(): void
    {
        $db    = Database::connect();
        $model = model(ProductModel::class);
        $testId = 2147483645;

        $db->transBegin();

        try {
            $payload = [
                'id'             => $testId,
                'name'           => 'PHPUnit transient product',
                'sku'            => 'phpunit-transient',
                'image'          => '',
                'page_url'       => '/phpunit-transient',
                'category_id'    => '[]',
                'sequence_order' => 99999,
                'is_visible'     => '0',
                'created_at'     => date('Y-m-d H:i:s'),
            ];

            $this->assertTrue($model->addProduct($payload));
            $this->assertNotNull($model->findProduct($testId));

            $this->assertTrue($model->updateProduct($testId, ['name' => 'PHPUnit updated']));
            $updated = $model->findProduct($testId);
            $this->assertNotNull($updated);
            $this->assertSame('PHPUnit updated', $updated->name);

            $this->assertTrue($model->deleteProduct($testId));
            $this->assertNull($model->findProduct($testId));
        } finally {
            $db->transRollback();
        }

        $this->assertNull($model->findProduct($testId));
    }
}
