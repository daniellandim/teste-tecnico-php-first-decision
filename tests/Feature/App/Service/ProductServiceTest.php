<?php

namespace App\Service;

use App\Filters\ProductFilter;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use LazilyRefreshDatabase;

    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductService();
    }

    #[Test]
    public function it_should_remove_a_product()
    {
        $product = Product::factory()->create();

        $this->service->delete($product);

        $this->assertDatabaseMissing(Product::class, ['id' => $product->id]);
    }

    #[Test]
    public function it_should_create_a_product()
    {
        $payload = [
            'name'        => 'Notebook Dell',
            'description' => 'Notebook para desenvolvimento',
            'amount'      => 4500.90,
            'quantity'    => 10,
        ];

        $this->service->store($payload);

        $this->assertDatabaseHas(Product::class, [
            'name'     => 'Notebook Dell',
            'quantity' => 10,
        ]);

    }

    #[Test]
    public function it_should_update_a_product()
    {
        $product = Product::factory()->create([
            'name'     => 'Notebook Antigo',
            'quantity' => 5,
        ]);

        $payload = [
            'name'        => 'Notebook Atualizado',
            'description' => 'Notebook para desenvolvimento',
            'amount'      => 4500.90,
            'quantity'    => 20,
        ];

        $this->service->update($payload, $product);

        $this->assertDatabaseHas(Product::class, [
            'id'       => $product->id,
            'name'     => 'Notebook Atualizado',
            'quantity' => 20,
        ]);
    }

    #[Test]
    public function it_should_return_paginated_products_with_filters()
    {

        Product::factory()->create(['name' => 'Marcelino Hudson', 'amount' => 227.93]);
        Product::factory()->create(['name' => 'Outro Produto', 'amount' => 100.00]);

        // Simula query string (?name=marcelino)
        $request = request()->merge([
            'name' => 'marcelino',
        ]);

        $filters = new ProductFilter($request);

        $result = $this->service->index($filters);

        $this->assertInstanceOf(
            AnonymousResourceCollection::class,
            $result
        );

        $this->assertCount(1, $result->collection);

        $this->assertEquals(
            'Marcelino Hudson',
            $result->collection->first()->name
        );
    }

    #[Test]
    public function it_should_return_all_products_when_no_filter_is_applied()
    {
        Product::factory()->count(3)->create();

        $filters = new ProductFilter(request());

        $result = $this->service->index($filters);

        $this->assertCount(3, $result->collection);
    }
}
