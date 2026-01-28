<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    public function index(ProductFilter $filters, Request $request): AnonymousResourceCollection
    {
        return $this->service->index($filters);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'data' => ProductResource::make($product),
        ], Response::HTTP_OK);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        return response()->json([
            'message' => __('messages.product_created'),
            'data'    => $this->service->store($request->validated()),
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        return response()->json([
            'message' => __('messages.product_updated'),
            'data'    => $this->service->update($request->validated(), $product),
        ], Response::HTTP_OK);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->service->delete($product);

        return response()->json([
            'message' => __('messages.product_removed'),
        ], Response::HTTP_OK);
    }

}
