<?php

namespace Modules\Inventory\Tests\Feature\V1;

use Tests\TestCase;
use Modules\Core\Models\Admin;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Inventory\Models\PurchaseOrderDetail;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;
    protected $admin;
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->admin, 'admin_token'); 

    }
    /**
     * A basic test example.
     */
    public function test_purchase_order_index(): void
    {
        $response = $this->getJson(route('api.v1.inventory.purchase-orders.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [
                        "id",
                        "supplier" => ['*'],
                        "order_date",
                        "status",
                        "total",
                        "details" => ['*'],
                        "created_at",
                        "updated_at",
                    ],
                ],
            ],
        ]);
    }

    public function test_purchase_order_detail() 
    {
        $purchaseOrder = PurchaseOrder::factory()
        ->for(Supplier::factory())
        ->has(PurchaseOrderDetail::factory()->count(2)
            ->has(Product::factory()->count(3)),
            'details'
        )->create();

        $response = $this->getJson(route('api.v1.inventory.purchase-orders.show', $purchaseOrder->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'supplier',
                'order_date',
                'status',
                'total',
                'details',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_it_can_store_a_purchase_order(): void
    {
        $supplierId = Supplier::factory()->create()->id;
        $productId = Product::factory()->create()->id;
        $productId2 = Product::factory()->create()->id;
        $purchaseOrderData = [
            'supplier_id' => $supplierId,
            'order_date' => now(),
            'status' => 'pending',
            'items' => [
                [
                    'product_id' => $productId,
                    'quantity' => 10,
                    'unit_price' => 100,
                ],
                [
                    'product_id' => $productId2,
                    'quantity' => 3,
                    'unit_price' => 50,
                ],
            ],
            
        ];
        $response = $this->postJson(route('api.v1.inventory.purchase-orders.store'), $purchaseOrderData);
        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'supplier',
                'order_date',
                'status',
                'total',
                'details',
                'created_at',
                'updated_at',
            ],
        ]);
        $this->assertDatabaseHas('purchase_orders', [
            'supplier_id' => $supplierId,
            'status' => 'pending',
        ]);
        
        $this->assertDatabaseHas('purchase_order_details', [
            'product_id' => $productId,
            'quantity' => 10,
            'unit_price' => 100,
        ]);
        
        $this->assertDatabaseHas('purchase_order_details', [
            'product_id' => $productId2,
            'quantity' => 3,
            'unit_price' => 50,
        ]);
        
    }

    public function test_it_can_update_purchase_order()
    {
        $supplierId = Supplier::factory()->create()->id;
        $productId = Product::factory()->create()->id;
        $productId2 = Product::factory()->create()->id;
        $purchaseOrderId = PurchaseOrder::factory()->create()->id;

        $purchaseOrderData = [
            'supplier_id' => $supplierId,
            'order_date' => now(),
            'status' => 'received',
            'items' => [
                [
                    'product_id' => $productId,
                    'quantity' => 10,
                    'unit_price' => 100,
                ],
                [
                    'product_id' => $productId2,
                    'quantity' => 3,
                    'unit_price' => 50,
                ],
            ],
            
        ];
        $response = $this->putJson(route('api.v1.inventory.purchase-orders.update', $purchaseOrderId), $purchaseOrderData);
        $response->assertOk();
        $this->assertDatabaseHas('purchase_orders', [
            'id' => $purchaseOrderId,
            'status' => $purchaseOrderData['status'],
        ]);
        $this->assertDatabaseHas('purchase_order_details', [
            'product_id' => $productId,
            'quantity' => $purchaseOrderData['items'][0]['quantity'],
            'unit_price' => $purchaseOrderData['items'][0]['unit_price'],
        ]);
        $this->assertDatabaseHas('purchase_order_details', [
            'product_id' => $productId2,
            'quantity' => $purchaseOrderData['items'][1]['quantity'],
            'unit_price' => $purchaseOrderData['items'][1]['unit_price'],
        ]);
    }
    public function test_it_can_delete_purchase_order()
    {
        $purchaseOrderId = PurchaseOrder::factory()->create()->id;
        $response = $this->deleteJson(route('api.v1.inventory.purchase-orders.destroy', $purchaseOrderId));
        $response->assertOk();
        $this->assertDatabaseMissing('purchase_orders', ['id' => $purchaseOrderId]);
        $this->assertDatabaseMissing('purchase_order_details', ['purchase_order_id' => $purchaseOrderId]);
    }
}
