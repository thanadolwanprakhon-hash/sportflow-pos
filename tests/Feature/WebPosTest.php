<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebPosTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_employee_can_login_and_open_all_main_modules(): void
    {
        $this->seed();

        $this->post('/login', ['email' => 'admin@sportflow.test', 'password' => '123456'])
            ->assertRedirect('/');

        foreach (['/', '/pos', '/products', '/members', '/employees', '/sales', '/reports', '/settings'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_web_checkout_uses_logged_in_employee_and_reduces_stock(): void
    {
        $this->seed();
        $employee = User::where('email', 'admin@sportflow.test')->firstOrFail();
        $product = Product::where('sku', 'FIT-PT-027')->firstOrFail();

        $response = $this->actingAs($employee)->post('/sales', [
            'payment_method' => 'cash',
            'received_amount' => 1000,
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 23]);
        $this->assertDatabaseHas('sales', ['employee_id' => $employee->id, 'total' => 199]);
        $this->assertDatabaseHas('payments', ['method' => 'cash', 'change_amount' => 801]);
    }
}
