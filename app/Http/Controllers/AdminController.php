<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // Ou Imagick\Driver
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    // ============================
    // ======= BRANDS ============
    // ============================

    public function brands()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function store_brand(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Nom de l'image unique
        $imageName = time() . '.' . $request->image->extension();

        // Déplacement dans public/uploads/brands
        $request->image->move(public_path('uploads/brands'), $imageName);
        $chemin = 'uploads/brands/' . $imageName;

        // dd($imageName,$chemin);

        // Enregistrement dans la base
        Brand::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'image' => $chemin // chemin à afficher
        ]);

        return redirect()->route('admin.brands')->with('status', 'Brand created successfully.');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function update_brand(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $brand = Brand::findOrFail($id);

        $path = $brand->image;

        if ($request->hasFile('image')) {
            // ✅ Supprimer l’ancienne image si elle existe
            if ($brand->image && file_exists(public_path($brand->image))) {
                unlink(public_path($brand->image));
            }            

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/brands'), $imageName);
            $path = 'uploads/brands/' . $imageName;
        }

        // dd($path);

        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->image = $path;

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand updated successfully!');
    }

    public function destroy_brand($id)
    {
        $brand = Brand::findOrFail($id);

        // Supprimer l’image si elle existe
        if ($brand->image && file_exists(public_path($brand->image))) {
            unlink(public_path($brand->image));
        }

        $brand->delete();

        return redirect()->route('admin.brands')->with('status', 'Brand deleted successfully!');
    }

    // ============================
    // ======= CATEGORIES =========
    // ============================

    public function categories()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function add_category()
    {
        return view('admin.category-add');
    }

    public function store_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/categories'), $imageName);
        $imagePath = 'uploads/categories/' . $imageName;

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.categories')->with('status', 'Category created successfully!');
    }

    public function update_category(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {

            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $category->image = 'uploads/categories/' . $imageName;            
        }

        $category->name = $request->name;
        $category->slug = $request->slug;

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category updated successfully!');
    }

    public function generateCategoryThumbnailImage($image, $imageName)
    {
        // Define the destination path
        $destinationPath = public_path('uploads/category'); 

        // Create the directory if it does not exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Read the image from the uploaded file using ImageManager
        $imageObject = ImageManager::make($image->getRealPath());

        // Resize the image while maintaining its aspect ratio
        $imageObject->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Save the resized image to the destination path with the provided image name
        $imageObject->save($destinationPath . '/' . $imageName);
    }


    public function category_edit($id)
    {
        $category = Category::findOrFail($id); // Get the category or fail
        return view('admin.category-edit', compact('category'));
    }

    public function destroy_category($id)
    {
        $category = Category::findOrFail($id);

        // Supprimer l'image liée si elle existe
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Category deleted successfully!');
    }
    // ============================
    // ======= PRODUCTS ==========
    // ============================

    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories','brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|string|max:255|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required|boolean',
            'quantity' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id'
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

        if ($request->hasfile('image')){
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }
        $gallery_arr = [];
        $gallery_image = '';
        $counter = 1;
        $current_timestamp = time();
        
        if ($request->hasFile('images')) {
            $allowedExtensions = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
        
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
        
                if (in_array($extension, $allowedExtensions)) {
                    $fileName = $current_timestamp . '-' . $counter . '.' . $extension;
        
                    // Génération de la miniature ou sauvegarde simple
                    $this->GenerateProductThumbnailImage($file, $fileName);
        
                    $gallery_arr[] = $fileName; // Ajoute au tableau
        
                    $counter++;
                }
            }
        
            // Transforme en chaîne pour stockage
            $gallery_image = implode(',', $gallery_arr);
        }
        
        // Stocke dans la base
        $product->images = $gallery_image;
        $product->save();
        
        return redirect()->route('admin.products')->with('status', 'The product has been added successfully!');
    }


    public function GenerateProductThumbnailImage($image, $imageName)
    {
        // Déterminer le dossier de destination en fonction du type d’image
        $destinationPath = public_path('uploads/products');
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');

        // Créer le dossier s’il n'existe pas
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Initialiser le gestionnaire d'image Intervention (v3 avec Driver Gd)
        $manager = new ImageManager(new Driver());

        // Lire et redimensionner l'image
        $img = $manager->read($image->getRealPath());

        $img -> cover(540,689,"top");
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);
    }

    public function product_edit($id)
    {
        $product = Product::findOrFail($id); 
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        
        return view('admin.product-edit', compact('product', 'categories', 'brands')); 
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => [
                'required',
                Rule::unique('products', 'slug')->ignore($request->id)
            ],
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:3048',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|max:3048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = Product::find($request->id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produit non trouvé.');
        }

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
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
            // Suppression des anciennes images
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
            $allowedfileextension = ['jpg', 'png', 'jpeg'];

            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                if (in_array($gextension, $allowedfileextension)) {
                    $gfileName = $current_timestamp . "." . $counter . "." . $gextension;
                    $file->move(public_path('uploads/products'), $gfileName);
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter++;
                }
            }

            $product->images = implode(",", $gallery_arr);
        }

        $product->save();

        return redirect()->route('admin.products')->with('status', 'Produit mis à jour avec succès !');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }

    // ============================
    // ======= BRANDS ============
    // ============================

    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons', compact('coupons'));
    }

    public function coupon_add()
    {
        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|max:191',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        Coupon::create($request->all());

        return redirect()->route('admin.coupons')->with('success', 'Coupon created successfully!');
    }

    public function coupon_edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function coupon_update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:191|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update($request->all());

        return redirect()->route('admin.coupons')->with('success', 'Coupon updated successfully!');
    }

    public function coupon_destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons')->with('success', 'Coupon deleted successfully!');
    }
}