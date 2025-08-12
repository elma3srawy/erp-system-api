<?php

namespace Modules\Core\Tests\Feature\V1;

use Tests\TestCase;
use Modules\Core\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\Department;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_it_can_get_all_departments()
    {
        $admin = Admin::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($admin , 'admin');
        $response = $this->getJson('/api/v1/admin/department/all');
        $response->assertStatus(200);
    }
    public function test_it_can_create_departments()
    {

        $admin = Admin::factory()->create(
            ['email_verified_at' => now()]
        );

        $this->actingAs($admin, 'admin_token');

        $response = $this->postJson('/api/v1/admin/department/store', [
            'department_name' => 'IT Department',
        ]);
    
        $response->assertStatus(201); 

        $this->assertDatabaseHas('departments', [
            'department_name' => 'IT Department',
        ]);
    }
    public function test_it_can_update_departments()
    {

        $admin = Admin::factory()->create(
            ['email_verified_at' => now()]
        );

        $department = Department::factory()->create();

        $this->actingAs($admin, 'admin_token');

        $response = $this->putJson('/api/v1/admin/department/update/'.$department->id, [
            'department_name' => 'IT Department',
        ]);
        
        $response->assertStatus(200); 

        $this->assertDatabaseHas('departments', [
            'department_name' => 'IT Department',
        ]);
    }
    public function test_it_can_delete_departments()
    {

        $admin = Admin::factory()->create(
            ['email_verified_at' => now()]
        );

        $department = Department::factory()->create(['department_name' => 'IT Department']);

        $this->actingAs($admin, 'admin_token');

        $response = $this->deleteJson('/api/v1/admin/department/delete/'.$department->id);
    
        $response->assertStatus(200); 

        $this->assertDatabaseMissing('departments', [
            'department_name' => 'IT Department',
        ]);
    }

}
