<?php
namespace Modules\Inventory\Tests\Feature\V1;

use Tests\TestCase;
use Modules\Core\Models\Admin;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Category;

class ProductTest extends TestCase
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

    public function test_it_can_list_products()
    {

        Product::factory()
        ->for(Section::factory()->for(Category::factory()))
        ->has(Supplier::factory()->count(2))
        ->count(6)
        ->create();

        $response = $this->getJson(route('api.v1.inventory.products.index'));


        $response->assertOk();


        $response->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [ 
                        'id',
                        'name',
                        'sku',
                        'quantity',
                        'price',
                        'section',
                        'suppliers',
                    ],
                ],
            ]
        ]);

        $response->assertJsonCount(6, 'data.data');

    }

    public function test_it_validates_required_fields()
    {
        $response = $this->postJson(route('api.v1.inventory.products.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name' , 'sku' , 'quantity' , 'price' , 'section_id' , 'supplier_id']);
    }

    public function test_it_can_store_a_product()
    {
        $supplierId = Supplier::factory()->create()->id;
        $sectionId = Section::factory()->for(Category::factory())->create()->id;
        
        $productData = [
            'name' => 'Test Product',
            'sku' => 'SKU123',
            'quantity' => 10,
            'price' => 100,
            'section_id' => $sectionId,
            'supplier_id' => $supplierId,
        ];
        $response = $this->postJson(route('api.v1.inventory.products.store'), $productData);


        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'sku',
                    'quantity',
                    'price',
                    'section',
                    'suppliers',
                ],
            ]);
            // dd($response->json());
        unset($productData['supplier_id']);
        $this->assertDatabaseHas('products', $productData);

        $this->assertDatabaseHas('products_suppliers', [
            'product_id' => $response->json('data.id'),
            'supplier_id' => $supplierId,
            'price' => $productData['price'],
        ]);
    }



    public function test_it_can_show_a_product()
    {
        $product = Product::factory()
            ->for(Section::factory()->for(Category::factory()))
            ->has(Supplier::factory()->count(2))
            ->create();

        $response = $this->getJson(route('api.v1.inventory.products.show', $product->id));

        $response->assertOk();
        // dd($response->json());
            $response->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                ]
            ]);
    }

  

  

    public function test_it_can_update_a_product()
    {
        $product = Product::factory()
            ->for(Section::factory()->for(Category::factory()))
            ->has(Supplier::factory()->count(2))
            ->create();

        $updateData = [
            'name' => 'Updated Product',
            'sku' => 'SKU123',
            'quantity' => 10,
            'price' => 100,
        ];

        $response = $this->putJson(route('api.v1.inventory.products.update', $product->id), $updateData);
        $response->assertOk();
        // dd($response->json());
        $response->assertOk()
            ->assertJson([
                'data' => [
                    'name' => 'Updated Product',
                    'sku' => 'SKU123',
                    'quantity' => 10,
                    'price' => 100,
                ]
            ]);

        $this->assertDatabaseHas('products', $updateData);
    }

    
    public function test_it_can_update_product_suppliers()
    {
        $product = Product::factory()
            ->for(Section::factory()->for(Category::factory()))
            ->has(Supplier::factory()->count(2))
            ->create();            
        $updateData = [
            'suppliers' => [
                [
                    'supplier_id' => Supplier::factory()->create()->id,
                    'price' => 100,
                ],
            ],
        ];
        $response = $this->putJson(route('api.v1.inventory.products.suppliers.update', $product->id), $updateData);
       

        $response->assertOk()
            ->assertJson([
                'message' => 'Supplier updated successfully',
            ]);

        $this->assertDatabaseHas('products_suppliers', ['product_id' => $product->id, 'supplier_id' => $updateData['suppliers'][0]['supplier_id'] , 'price' => $updateData['suppliers'][0]['price']]);
    }


    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()
            ->for(Section::factory()->for(Category::factory()))
            ->has(Supplier::factory()->count(2))
            ->create();
        $response = $this->deleteJson(route('api.v1.inventory.products.destroy', $product->id));

        $response->assertOk()
            ->assertJson(['message' => 'Product deleted successfully']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]); 
        $this->assertDatabaseMissing('products_suppliers', ['product_id' => $product->id]);
    }
}
