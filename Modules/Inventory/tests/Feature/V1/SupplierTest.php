<?php
namespace Modules\Inventory\Tests\Feature\V1;

use Tests\TestCase;
use Modules\Core\Models\Admin;
use Modules\Inventory\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierTest extends TestCase
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

    public function test_it_can_list_suppliers()
    {
        Supplier::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.inventory.suppliers.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'supplier_name', 'contact_name', 'contact_email', 'phone']
                ]
            ]);
    }


    public function test_it_can_store_a_supplier()
    {
        $supplierData = [
            'supplier_name' => 'Test Supplier',
            'contact_name' => 'John Doe',
            'contact_email' => 'john@example.com',
            'phone' => '01234567890',
        ];

        $response = $this->postJson(route('api.v1.inventory.suppliers.store'), $supplierData);

        $response->assertCreated()
            ->assertJsonFragment($supplierData);

        $this->assertDatabaseHas('suppliers', $supplierData);
    }


    public function test_it_validates_required_fields()
    {
        $response = $this->postJson(route('api.v1.inventory.suppliers.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['supplier_name']);
    }

    public function test_it_can_show_a_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->getJson(route('api.v1.inventory.suppliers.show', $supplier->id));

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $supplier->id,
                    'supplier_name' => $supplier->supplier_name,
                ]
            ]);
    }

    public function test_it_can_update_a_supplier()
    {
        $supplier = Supplier::factory()->create();

        $updateData = [
            'supplier_name' => 'Updated Supplier',
            'contact_name' => 'Jane Doe',
            'contact_email' => 'jane@example.com',
            'phone' => '01234567890',
        ];

        $response = $this->putJson(route('api.v1.inventory.suppliers.update', $supplier->id), $updateData);

        $response->assertOk()
            ->assertJsonFragment([
                'supplier_name' => 'Updated Supplier',
                'contact_name' => 'Jane Doe',
            ]);

        $this->assertDatabaseHas('suppliers', $updateData);
    }

    public function test_it_can_delete_a_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->deleteJson(route('api.v1.inventory.suppliers.destroy', $supplier->id));

        $response->assertOk()
            ->assertJson(['message' => 'Supplier deleted successfully']);

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
