<?php

namespace Modules\Inventory\Tests\Feature\V1;

use Tests\TestCase;
use Modules\Core\Models\Admin;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    protected $category;
    protected function setUp(): void
    {
        parent::setUp();

        $admin = Admin::factory()->create([
            'email_verified_at' => now()
        ]);
    
        $this->actingAs($admin , 'admin_token');
        $this->category = Category::factory()->create();
    }

    public function test_it_can_list_sections()
    {
       
       Section::factory()->count(5)->create(
        ['category_id' => $this->category->id]
       );

        $response = $this->getJson(route('api.v1.inventory.sections.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data.data');
    }


    public function test_it_can_store_a_section()
    {

        $sectionData = ['name' => 'Test Section' , 'category_id' => $this->category->id];
        $response = $this->postJson(route('api.v1.inventory.sections.store'), $sectionData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('sections', $sectionData);
    }


    public function test_it_validates_section_creation()
    {
        $response = $this->postJson(route('api.v1.inventory.sections.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name' , 'category_id']);
    }


    public function test_it_can_show_a_section()
    {
        $section = Section::factory()->create(
            ['category_id' => $this->category->id]
        );
        $response = $this->getJson(route('api.v1.inventory.sections.show', $section->id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $section->id , 'category_id' => $section->category_id]);
    }

    public function test_it_returns_404_for_nonexistent_section()
    {
        $response = $this->getJson(route('api.v1.inventory.sections.show', 9999));

        $response->assertStatus(404);
    }

    public function test_it_can_update_a_section()
    {
        $section = Section::factory()->create(
            ['category_id'=> $this->category->id]
        );
        $newCat = Category::factory()->create();
        $updateData = ['name' => 'Updated Section' ,'category_id' => $newCat->id];

        $response = $this->putJson(route('api.v1.inventory.sections.update', $section->id), $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sections', array_merge(['id' => $section->id], $updateData));
    }


    public function test_it_validates_section_updates()
    {
        $section = Section::factory()->create(['category_id'=> $this->category->id]);
        $response = $this->putJson(route('api.v1.inventory.sections.update', $section->id), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name' ,'category_id']);
    }
    public function test_it_can_delete_a_section()
    {
        $section = Section::factory()->create(['category_id'=> $this->category->id]);
        $response = $this->deleteJson(route('api.v1.inventory.sections.destroy', $section->id));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('sections', ['id' => $section->id]);
    }
}
