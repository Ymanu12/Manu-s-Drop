<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    use AuditsAdminActions;

    public function products()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        $this->authorize('create', Product::class);

        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $this->authorize('create', Product::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'SKU' => 'required|string|max:191',
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'required|boolean',
            'quantity' => 'required|integer|min:0|max:100000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:3048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->slug);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = [];
        $counter = 1;
        $current_timestamp = time();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                if (in_array($extension, ['jpg', 'png', 'jpeg'], true)) {
                    $fileName = $current_timestamp . '-' . $counter . '.' . $extension;
                    $this->GenerateProductThumbnailImage($file, $fileName);
                    $gallery_arr[] = $fileName;
                    $counter++;
                }
            }
        }

        $product->images = implode(',', $gallery_arr);
        $product->save();

        $this->auditAdminAction('product.created', Product::class, $product->id, ['name' => $product->name, 'slug' => $product->slug]);

        return redirect()->route('admin.products')->with('status', 'The product has been added successfully!');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/products');
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        if (!file_exists($destinationPathThumbnail)) {
            mkdir($destinationPathThumbnail, 0755, true);
        }

        $manager = new ImageManager(new Driver());
        $img = $manager->read($image->getRealPath());

        $img->cover(540, 689, 'top');
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail . '/' . $imageName);
    }

    public function product_edit($id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('view', $product);

        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($id)],
            'short_description' => 'required|string',
            'description' => 'required|string',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'SKU' => 'required|string|max:191',
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'required|boolean',
            'quantity' => 'required|integer|min:0|max:100000',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:3048',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|max:3048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product->name = $request->name;
        $product->slug = Str::slug($request->slug);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/products/' . $product->image))) {
                File::delete(public_path('uploads/products/' . $product->image));
            }
            if (File::exists(public_path('uploads/products/thumbnails/' . $product->image))) {
                File::delete(public_path('uploads/products/thumbnails/' . $product->image));
            }

            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        if ($request->hasFile('images')) {
            if ($product->images) {
                foreach (explode(',', $product->images) as $ofile) {
                    if (File::exists(public_path('uploads/products/' . $ofile))) {
                        File::delete(public_path('uploads/products/' . $ofile));
                    }
                    if (File::exists(public_path('uploads/products/thumbnails/' . $ofile))) {
                        File::delete(public_path('uploads/products/thumbnails/' . $ofile));
                    }
                }
            }

            $gallery_arr = [];
            $counter = 1;
            $files = $request->file('images');

            foreach ($files as $file) {
                $gextension = strtolower($file->getClientOriginalExtension());
                if (in_array($gextension, ['jpg', 'png', 'jpeg'], true)) {
                    $gfileName = $current_timestamp . '.' . $counter . '.' . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    $gallery_arr[] = $gfileName;
                    $counter++;
                }
            }

            $product->images = implode(',', $gallery_arr);
        }

        $product->save();

        $this->auditAdminAction('product.updated', Product::class, $product->id, ['name' => $product->name, 'slug' => $product->slug]);

        return redirect()->route('admin.products')->with('status', 'Produit mis a jour avec succes !');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Product not found.');
        }

        $this->authorize('delete', $product);

        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach (array_filter(explode(',', (string) $product->images)) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }

        $productId = $product->id;
        $product->delete();

        $this->auditAdminAction('product.deleted', Product::class, $productId);

        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }
}
