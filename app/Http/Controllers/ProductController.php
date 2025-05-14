<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products =  Product::all();
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name'      => 'required|string|max:255',
            'category'          => 'required|string',
            'price'             => 'required|numeric',
        ]);
        $product = Product::create($request->all());

        return response()->json([
            'message'       => 'Data created',
            'status'        => true,
            'data'          => new ProductResource($product),
        ], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'message'   => 'Detail data',
            'status'    => true,
            'data'      => new ProductResource($product)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang masuk
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Cari produk berdasarkan ID atau tampilkan error 404 jika tidak ditemukan
        $product = Product::findOrFail($id);

        // Update hanya field yang diizinkan
        $product->update($request->only(['product_name', 'category', 'price']));

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Data updated',
            'status' => true,
            'data' => new ProductResource($product->refresh()), // Menggunakan refresh untuk mengambil data terbaru
        ], 200);
    }



    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        // Mengembalikan respons JSON setelah penghapusan
        return response()->json([
            'message' => 'Data deleted',
            'status' => true,
        ], 200);
    }
}
