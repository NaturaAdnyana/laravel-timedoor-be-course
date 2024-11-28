<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $categoryName = $request->input('category');
        $brandName = $request->input('brand');
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');

        $query = Product::query();

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($categoryName) {
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', 'like', "%{$categoryName}%");
            });
        }

        if ($brandName) {
            $query->whereHas('brand', function ($q) use ($brandName) {
                $q->where('name', 'like', "%{$brandName}%");
            });
        }

        $products = $query->with('category', 'brand')
            ->orderBy('name')
            ->paginate(10);

        if (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

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
            'image_url' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter',
            'price.required' => 'Harga produk wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'stock.required' => 'Stok produk wajib diisi',
            'stock.integer' => 'Stok harus berupa bilangan bulat',
            'image_url.required' => 'Gambar product wajib diisi',
            'image_url.max' => 'URL gambar produk tidak boleh lebih dari 255 karakter',
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
        $product = Product::with('category')->with('brand')->find($id);
        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
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
            'image_url' => 'string|max:255',
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

    public function uploadFile(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|string'
            ]);

            $base64Image = $request->input('file');

            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $extension = strtolower($type[1]);

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($extension, $allowedExtensions)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid image extension. Allowed extensions: jpg, jpeg, png, webp',
                        'data' => null,
                    ], 422);
                }

                $resultCheck = substr($base64Image, strpos($base64Image, ',') + 1);
                $photos = base64_decode($resultCheck);

                if ($photos === false) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid base64 image data',
                        'data' => null,
                    ], 422);
                }

                $path = 'photos/products/' . time() . '.' . $extension;
                Storage::disk('public')->put($path, $photos);

                $url = Storage::url($path);

                return response()->json([
                    'status' => true,
                    'message' => 'Image uploaded successfully',
                    'data' => [
                        'url' => $url,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid base64 image dataaa',
                    'data' => null,
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
