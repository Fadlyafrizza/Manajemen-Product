<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(new ProductCollection(Product::get()));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/products'), $filename);
                $data['image'] = 'products/' . $filename;
            }

            $product = Product::create($data);

            DB::commit();
            return new ProductResource($product);

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ];

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path('storage/' . $product->image))) {
                    unlink(public_path('storage/' . $product->image));
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/products'), $filename);
                $data['image'] = 'products/' . $filename;
            }

            $product->update($data);

            DB::commit();
            return new ProductResource($product);

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
public function destroy(Product $product)
{
    try {
        $protectedImages = ['products/obat.jpeg', 'products/obat2.jpeg'];

        if (
            $product->image &&
            file_exists(public_path('storage/' . $product->image)) &&

            // Karena ini test image testnya tidak akan dihapus 
            !in_array($product->image, $protectedImages)
        ) {
            unlink(public_path('storage/' . $product->image));
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to delete product',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
