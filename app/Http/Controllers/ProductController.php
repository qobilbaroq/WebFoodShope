<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['product_name', 'category_id', 'description', 'stock', 'price']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            
            $data['img_url'] = Storage::url($imagePath);
            $data['img_name'] = $imageName;
        }

        $product = Product::create($data);
        return response()->json($product->load('category'), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['product_name', 'category_id', 'description', 'stock', 'price']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->img_name) {
                Storage::disk('public')->delete('products/' . $product->img_name);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            
            $data['img_url'] = Storage::url($imagePath);
            $data['img_name'] = $imageName;
        }

        $product->update($data);
        return response()->json($product->load('category'));
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->img_name) {
            Storage::disk('public')->delete('products/' . $product->img_name);
        }

        $product->delete();
        return response()->json(null, 204);
    }

    public function getByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->with('category')->get();
        return response()->json($products);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = Product::where('product_name', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%")
                            ->with('category')
                            ->get();
        return response()->json($products);
    }
}