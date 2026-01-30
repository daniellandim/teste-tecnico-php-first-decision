<?php

namespace App\Services;

use App\Filters\ProductFilter;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductService
{
    public function store(array $payload): JsonResource
    {
        $product = Product::create($payload);

        return ProductResource::make($product);
    }

    public function update(array $payload, Product $product)
    {
        $product->update($payload);
        return ProductResource::make($product->fresh());
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function index(ProductFilter $filters): AnonymousResourceCollection
    {
        return ProductResource::collection(
            Product::query()
                ->filters($filters)
                ->orderBy('id')
                ->get()
        );
    }
}
