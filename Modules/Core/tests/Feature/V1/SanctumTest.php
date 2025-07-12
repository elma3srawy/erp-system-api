<?php

namespace Modules\Core\Tests\Feature\V1;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Modules\Core\Models\User;
use Modules\Core\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SanctumTest extends TestCase
{
    /**
     * A basic test example.
     */

    use RefreshDatabase;

    public function test_admin_can_login_via_session()
    {
            $admin = Admin::factory()->create();
            $this->actingAs($admin, 'admin');
            $response = $this->getJson('/api/v1/admin/auth-admin');
            $response->assertStatus(200);
    }
    public function test_admin_can_login_via_token()
    {
            Sanctum::actingAs( Admin::factory()->create(), ['*'], 'admin_token');
            $response = $this->getJson('/api/v1/admin/auth-admin');
            $response->assertOk();
    }
       public function test_user_can_login_via_session()
        {
                $user = User::factory()->create();
                $this->actingAs($user, 'user');
                $response = $this->getJson('/api/v1/user/auth-user');
                $response->assertStatus(200);
        }
}
