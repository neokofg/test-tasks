<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\IndexProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

final class IndexProductController extends Controller
{
    protected function handle(): JsonResponse
    {
        $data = app(IndexProductRequest::class)->validated();

        $products = cache_get(function() use ($data) {
            return Product::withFormattedPrice($data['currency'])
                ->simplePaginate($data['first'], page: $data['page']);
        }, 'products:index:' . $data['first'] . $data ['page'] . $data['currency']->value);

        return response_json(data: ProductResource::collection($products));
    }
}
