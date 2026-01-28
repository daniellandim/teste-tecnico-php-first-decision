<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_should_return_all_products(): void
    {
        $product = Product::factory()->create();

        $this->get(route('products.index'))
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->where('data.0.id', $product->id)
                ->count('data', 1)
                ->etc()
            );
    }

    #[Test]
    public function it_should_be_possible_to_search_a_product()
    {
        $product = Product::factory()->create([
            'name' => 'Notebook Dell'
        ]);

        Product::factory()->create([
            'name' => 'Mouse Logitech'
        ]);

        $this->getJson(route('products.index', ['name' => 'Notebook']))
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->count('data', 1)
                ->where('data.0.name', $product->name)
                ->etc()
            );

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

        $response = $this->postJson(route('products.store'), $payload);

        $response
            ->assertCreated()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', 'Produto criado com sucesso.')
                ->has('data')
                ->where('data.name', $payload['name'])
                ->where('data.amount', $payload['amount'])
                ->where('data.quantity', $payload['quantity'])
                ->etc()
            );

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

        $response = $this->putJson(
            route('products.update', $product),
            $payload
        );

        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', 'Produto atualizado com sucesso.')
                ->has('data')
                ->where('data.id', $product->id)
                ->where('data.name', 'Notebook Atualizado')
                ->where('data.quantity', 20)
                ->etc()
            );

        $this->assertDatabaseHas(Product::class, [
            'id'       => $product->id,
            'name'     => 'Notebook Atualizado',
            'quantity' => 20,
        ]);
    }

    #[Test]
    public function it_should_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(
            route('products.destroy', $product)
        );

        $response->assertOk()
            ->assertJson(['message' => __('messages.product_removed')]);

        $this->assertDatabaseMissing(Product::class, [
            'id' => $product->id,
        ]);
    }

    #[Test]
    public function it_should_return_a_product_by_id()
    {
        $product = Product::factory()->create([
            'name' => 'Notebook Dell',
        ]);

        $response = $this->getJson(
            route('products.show', $product)
        );

        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->where('data.id', $product->id)
                ->where('data.name', 'Notebook Dell')
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('validateFields')]
    public function it_should_validate_fields_when_creating_product($data, $key, $message)
    {
        Product::factory()->create(['name' => 'Notebook Dell']);

        $this->postJson(route('products.store'), $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$key => __($message[0], $message[1])]);
    }

    #[Test]
    #[DataProvider('validateFields')]
    public function it_should_validate_fields_when_updating_product($data, $key, $message)
    {
        Product::factory()->create(['name' => 'Notebook Dell']);
        $product = Product::factory()->create(['name' => 'Tablet']);

        $this->putJson(route('products.update', $product), $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$key => __($message[0], $message[1])]);
    }

    public static function validateFields(): array
    {
        return [
            'name as required'       => [
                'data'    => ['name' => ''],
                'key'     => 'name',
                'message' => ['validation.required', ['attribute' => 'nome']]
            ],
            'name as string'         => [
                'data'    => ['name' => 123],
                'key'     => 'name',
                'message' => ['validation.string', ['attribute' => 'nome']]
            ],
            'name as unique'         => [
                'data'    => ['name' => 'Notebook Dell'],
                'key'     => 'name',
                'message' => ['validation.unique', ['attribute' => 'nome']]
            ],
            'name as max:255'        => [
                'data'    => ['name' => str_repeat('a', 256)],
                'key'     => 'name',
                'message' => ['validation.max.string', ['attribute' => 'nome', 'max' => 255]]
            ],
            'description as string'  => [
                'data'    => ['description' => 123],
                'key'     => 'description',
                'message' => ['validation.string', ['attribute' => 'descrição']],
            ],
            'description as max:255' => [
                'data'    => ['description' => str_repeat('a', 256)],
                'key'     => 'description',
                'message' => ['validation.max.string', ['attribute' => 'descrição', 'max' => 255]]
            ],
            'amount as required'     => [
                'data'    => ['amount' => ''],
                'key'     => 'amount',
                'message' => ['validation.required', ['attribute' => 'preço']]
            ],
            'amount as numeric'      => [
                'data'    => ['amount' => 'joão'],
                'key'     => 'amount',
                'message' => ['validation.numeric', ['attribute' => 'preço']]
            ],
            'amount as min:0.01'     => [
                'data'    => ['amount' => 0.00],
                'key'     => 'amount',
                'message' => ['validation.min.numeric', ['attribute' => 'preço', 'min' => 0.00]]
            ],
            'quantity as required'   => [
                'data'    => ['quantity' => ''],
                'key'     => 'quantity',
                'message' => ['validation.required', ['attribute' => 'quantidade']]
            ],
            'quantity as integer'    => [
                'data'    => ['quantity' => 'jão'],
                'key'     => 'quantity',
                'message' => ['validation.integer', ['attribute' => 'quantidade']]
            ],
            'quantity as min:0'      => [
                'data'    => ['quantity' => -1],
                'key'     => 'quantity',
                'message' => ['validation.min.numeric', ['attribute' => 'quantidade', 'min' => 0]]
            ],
        ];
    }

}
