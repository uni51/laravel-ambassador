<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        return response($product, Response::HTTP_CREATED); // 201
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only('title', 'description', 'image', 'price'));

        return response($product, Response::HTTP_ACCEPTED); // 202
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT); // 204
    }

    public function frontend()
    {
        if ($products = \Cache::get('products_frontend')) {
            return $products;
        }

        sleep(2);
        $products = Product::all();

        \Cache::set('products_frontend', $products, 30*60); // 30 min

        return $products;
    }

    public function backend()
    {
        return \Cache::remember('products_backend', 30 * 60, function() {
            return Product::paginate();
        });
    }
}
