<?php

namespace Tests\Unit\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class RouteSmokeTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testLoginPageReturnsOk(): void
    {
        $result = $this->get('login');

        $result->assertOK();
        $this->assertStringContainsString('Login', (string) $result->response()->getBody());
    }

    public function testProductsRequiresAuthRedirect(): void
    {
        $result = $this->get('products/index');

        $result->assertRedirect();
        $this->assertStringContainsString('login', strtolower($result->response()->getHeaderLine('Location')));
    }

    public function testApiGetCustomerOptionsReturns204(): void
    {
        $result = $this->call('options', 'api/get_customer');

        $result->assertStatus(204);
    }

    public function testApiGetCustomerJsonShape(): void
    {
        $result = $this->call('get', 'api/get_customer', [
            'email'    => 'test@example.com',
            'fullname' => 'Test User',
        ]);

        $result->assertOK();
        $json = json_decode($result->getJSON(), true);
        $this->assertSame('success', $json['status']);
        $this->assertSame('test@example.com', $json['email']['email']);
    }
}
