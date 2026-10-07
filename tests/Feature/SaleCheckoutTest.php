<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_sale_payment_and_updates_stock_and_points(): void
    {
        $this->seed();
        $product = Product::where('sku', 'SHO-NK-001')->firstOrFail();
        $member = Member::where('member_code', 'MBR-0001')->firstOrFail();
        $employee = User::where('email', 'admin@sportflow.test')->firstOrFail();

        $response = $this->actingAs($employee)->post('/sales', [
            'member_id' => $member->id,
            'payment_method' => 'cash',
            'received_amount' => 5000,
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('sales', ['employee_id' => $employee->id, 'subtotal' => 4160, 'discount' => 208, 'total' => 3952]);
        $this->assertDatabaseHas('payments', ['method' => 'cash', 'amount' => 3952, 'change_amount' => 1048]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 11]);
        $this->assertDatabaseHas('members', ['id' => $member->id, 'points' => 1279]);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id, 'type' => 'sale', 'quantity' => -1, 'balance_after' => 11]);
    }

    public function test_checkout_rejects_quantity_greater_than_available_stock(): void
    {
        $this->seed();
        $product = Product::where('sku', 'BAS-MO-020')->firstOrFail();
        $employee = User::where('email', 'admin@sportflow.test')->firstOrFail();
        $saleCount = Sale::count();

        $this->actingAs($employee)->post('/sales', [
            'payment_method' => 'cash',
            'received_amount' => 100000,
            'items' => [['product_id' => $product->id, 'quantity' => 4]],
        ])->assertSessionHasErrors('items');

        $this->assertDatabaseCount('sales', $saleCount);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 3]);
    }
}
