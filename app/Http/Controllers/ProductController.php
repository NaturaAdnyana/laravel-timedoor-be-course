<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json(['products' => $products]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter',
            'price.required' => 'Harga produk wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'stock.required' => 'Stok produk wajib diisi',
            'stock.integer' => 'Stok harus berupa bilangan bulat'
        ]);
        // Product::create($request->all());
        $product = Product::create($validatedData);
        return response()->json(['product' => $product], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $product = Product::findOrFail($id);
        $product = Product::find($id);
        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
    }

    public function showByPage()
    {
        $products = Product::paginate(10);
        return response()->json(['products' => $products]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
        ]);
        $product = Product::find($id);
        if ($product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->update($validatedData);
        return response()->json(['message' => 'Produk berhasil diupdate', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
