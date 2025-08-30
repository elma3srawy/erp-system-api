<?php

namespace Modules\Sales\Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CRM\Models\Customer;
use Modules\Inventory\Models\Product;
use Modules\Sales\Models\Order;
use Modules\Sales\Models\OrderDetails;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase, Authentication;

    public function test_guest_cannot_access_orders_endpoints()
    {
        $response = $this->getJson(route('api.v1.sales.orders.index'));
        $response->assertUnauthorized();

        $response = $this->getJson(route('api.v1.sales.orders.show', ['order' => 1]));
        $response->assertUnauthorized();

        $response = $this->postJson(route('api.v1.sales.orders.store'));
        $response->assertUnauthorized();

        $response = $this->putJson(route('api.v1.sales.orders.update', ['order' => 1]));
        $response->assertUnauthorized();

        $response = $this->deleteJson(route('api.v1.sales.orders.destroy', ['order' => 1]));
        $response->assertUnauthorized();
    }

    public function test_admin_can_get_all_orders()
    {
        $this->loginAsAdmin();
        Order::factory()->count(3)->for(Customer::factory())->has(OrderDetails::factory()->count(2), 'details')->create();

        $response = $this->getJson(route('api.v1.sales.orders.index'));
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'customer_id',
                        'order_date',
                        'total_amount',
                        'status',
                    ]
                ]
            ]
        ]);
        $this->assertEquals(3, count($response->json('data.data')));
    }

    public function test_admin_can_get_a_single_order()
    {
        $this->loginAsAdmin();
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->getJson(route('api.v1.sales.orders.show', $order));

        $response->assertOk();
        $response->assertJson(['data' => ['id' => $order->id]]);
    }

    public function test_customer_can_create_an_order()
    {
        $customer = $this->loginAsCustomer();
        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 200]);

        $orderData = [
            'products' => [
                ['product_id' => $product1->id, 'quantity' => 2],
                ['product_id' => $product2->id, 'quantity' => 1],
            ],
        ];

        $response = $this->postJson(route('api.v1.sales.orders.store'), $orderData);

        $response->assertCreated();
        $this->assertDatabaseHas('orders', ['customer_id' => $customer->id, 'total_amount' => 400]);
        $this->assertDatabaseHas('order_details', ['product_id' => $product1->id, 'quantity' => 2]);
        $this->assertDatabaseHas('order_details', ['product_id' => $product2->id, 'quantity' => 1]);
    }

    public function test_customer_can_update_their_own_order()
    {
        $customer = $this->loginAsCustomer();
        $order = Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);
        $product = Product::factory()->create();

        $updateData = [
            'products' => [
                ['product_id' => $product->id, 'quantity' => 5]
            ]
        ];

        $response = $this->putJson(route('api.v1.sales.orders.update', $order), $updateData);

        $response->assertOk();
        $this->assertDatabaseHas('order_details', ['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 5]);
    }

    public function test_customer_cannot_update_another_customers_order()
    {
        $this->loginAsCustomer();

        $otherCustomer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $otherCustomer->id]);

        $response = $this->putJson(route('api.v1.sales.orders.update', $order), []);

        $response->assertForbidden();
    }

    public function test_admin_can_delete_an_order()
    {
        $this->loginAsAdmin();
        $order = Order::factory()->create();

        $response = $this->deleteJson(route('api.v1.sales.orders.destroy', $order));

        $response->assertOk();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_customer_can_delete_their_own_order()
    {
        $customer = $this->loginAsCustomer();
        $order = Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);

        $response = $this->deleteJson(route('api.v1.sales.orders.destroy', $order));

        $response->assertOk();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
