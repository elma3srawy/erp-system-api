<?php

namespace Modules\Finance\Tests\Feature\V1;

use Tests\TestCase;
use Modules\Core\Models\Admin;
use Modules\Sales\Models\Order;
use Modules\Finance\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin_token');
    }

    public function test_can_list_invoices(): void
    {
        Invoice::factory()->count(5)->create();
        $response = $this->getJson('/api/v1/finance/invoices');
        $response->assertSuccessful();
        $response->assertJsonCount(5, 'data.data');
    }

    public function test_can_create_invoice(): void
    {
        $order = Order::factory()->create();
        $data = Invoice::factory()->make(['order_id' => $order->id])->toArray();
        $response = $this->postJson('/api/v1/finance/invoices', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('invoices', ['order_id' => $data['order_id']]);
    }

    public function test_can_show_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $response = $this->getJson('/api/v1/finance/invoices/' . $invoice->id);
        $response->assertSuccessful();
        $response->assertJson(['data' => ['id' => $invoice->id]]);
    }

    public function test_can_update_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $order = Order::factory()->create();
        $data = Invoice::factory()->make(['order_id' => $order->id])->toArray();
        $response = $this->putJson('/api/v1/finance/invoices/' . $invoice->id, $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id, 'order_id' => $data['order_id']]);
    }

    public function test_can_delete_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $response = $this->deleteJson('/api/v1/finance/invoices/' . $invoice->id);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('invoices', ['id'=> $invoice->id]);
    }
}
