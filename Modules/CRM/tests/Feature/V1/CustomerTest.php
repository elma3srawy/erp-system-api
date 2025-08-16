<?php

namespace Modules\CRM\Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Modules\Core\Models\Admin;
use Modules\CRM\Models\Customer;
class CustomerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create([
            'email_verified_at' => now(),
        ]);

        // Authenticate once before each test
        $this->actingAs($this->admin, 'admin_token');
    }
    public function test_it_can_get_all_customers(): void
    {
        $response = $this->getJson('/api/v1/customers');
        $response->assertStatus(200);
    }
    public function test_it_can_store_customer(): void
    {
        $response = $this->postJson('/api/v1/customers', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'phone' => '01234567890',
            'address' => '123 Main St',
        ]);
        $response->assertStatus(201);
    }
    public function test_it_can_update_customer(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'phone' => '01234567890',
            'address' => '123 Main St',
        ]);
        $response = $this->putJson('/api/v1/customers/'.$customer->id, [
            'name' => 'Test',
            'email' => 'john@example.com',
            'password' => 'password',
            'phone' => '01234567890',
            'address' => '123 Main St',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', [
            'name' => 'Test',
        ]);
    }
    public function test_it_can_delete_customer(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'phone' => '01234567890',
            'address' => '123 Main St',
        ]);
        $response = $this->deleteJson('/api/v1/customers/'.$customer->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('customers', [
            'name' => 'John Doe',
        ]);
    }

    public function test_it_can_get_customer_by_id()
    {
        $customer = Customer::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'phone' => '01234567890',
            'address' => '123 Main St',
        ]);  
        $response = $this->get('/api/v1/customers/'.$customer->id);
        $response->assertStatus(200);
    }

}
