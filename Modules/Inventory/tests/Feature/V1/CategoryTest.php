<?php

namespace Modules\Inventory\Tests\Feature\V1;

use Tests\TestCase;
use Modules\Core\Models\Admin;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = Admin::factory()->create([
            'email_verified_at' => now()
        ]);
    
        $this->actingAs($admin , 'admin_token');
    }

    public function test_it_can_list_categories()
    {

       Category::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.inventory.categories.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }


    public function test_it_can_store_a_category()
    {

        $categoryData = ['name' => 'Test Category'];
        $response = $this->postJson(route('api.v1.inventory.categories.store'), $categoryData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', $categoryData);
    }


    public function test_it_validates_category_creation()
    {
        $response = $this->postJson(route('api.v1.inventory.categories.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }


    public function test_it_can_show_a_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson(route('api.v1.inventory.categories.show', $category->id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $category->id]);
    }

    public function test_it_returns_404_for_nonexistent_category()
    {
        $response = $this->getJson(route('api.v1.inventory.categories.show', 9999));

        $response->assertStatus(404);
    }

    public function test_it_can_update_a_category()
    {
        $category = Category::factory()->create();
        $updateData = ['name' => 'Updated Category'];

        $response = $this->putJson(route('api.v1.inventory.categories.update', $category->id), $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', array_merge(['id' => $category->id], $updateData));
    }


    public function test_it_validates_category_updates()
    {
        $category = Category::factory()->create();
        $response = $this->putJson(route('api.v1.inventory.categories.update', $category->id), ['name' => '']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }
    public function test_it_can_delete_a_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson(route('api.v1.inventory.categories.destroy', $category->id));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
    public function test_it_can_get_categories_with_sections()
    {
        $category = Category::factory()->create();
        $section = Section::factory()->create(['category_id' => $category->id]);
        $section2 = Section::factory()->create(['category_id' => $category->id]);
        
        $response = $this->getJson(route('api.v1.inventory.categories.sections'));
        $response->assertStatus(200);
        $response->assertJsonPath('data.data.0.id', $category->id);
        $response->assertJsonPath('data.data.0.sections.0.id', $section->id);
        $response->assertJsonPath('data.data.0.sections.1.id', $section2->id);
    }
}
