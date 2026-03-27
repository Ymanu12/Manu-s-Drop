<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'size' => 'nullable|integer|min:1|max:48',
            'order' => 'nullable|integer|in:-1,1,2,3,4',
            'brands' => 'nullable|string|max:255',
            'categories' => 'nullable|string|max:255',
        ]);

        $size = $validated['size'] ?? 12;
        $order = $validated['order'] ?? -1;
        $f_brands = $validated['brands'] ?? '';
        $f_categories = $validated['categories'] ?? '';

        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'regular_price';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'regular_price';
                $o_order = 'DESC';
                break;
            default:
                $o_column = 'id';
                $o_order = 'DESC';
                break;
        }

        $brandIds = collect(explode(',', $f_brands))->filter(fn ($value) => ctype_digit($value))->map(fn ($value) => (int) $value)->values();
        $categoryIds = collect(explode(',', $f_categories))->filter(fn ($value) => ctype_digit($value))->map(fn ($value) => (int) $value)->values();

        $brands = Brand::with('products')->orderBy('name')->get();
        $categories = Category::with('products')->orderBy('name')->get();

        $products = Product::query()
            ->when($brandIds->isNotEmpty(), function ($query) use ($brandIds) {
                $query->whereIn('brand_id', $brandIds->all());
            })
            ->when($categoryIds->isNotEmpty(), function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds->all());
            })
            ->orderBy($o_column, $o_order)
            ->paginate($size)
            ->withQueryString();

        $seo = [
            'title' => 'Boutique | ' . config('seo.site_name'),
            'description' => "Parcourez le catalogue Manu's Drop et découvrez nos vêtements, accessoires et produits disponibles en ligne.",
            'canonical' => route('shop.index'),
            'keywords' => 'boutique, produits, shopping, mode, accessoires, Manu\'s Drop',
        ];

        return view('shop', compact('products', 'size', 'order', 'brands', 'f_brands', 'categories', 'f_categories', 'seo'));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();

        if (!$product) {
            abort(404);
        }

        $products = Product::where('slug', '<>', $product_slug)->take(8)->get();
        $price = $product->sale_price ?: $product->regular_price;
        $seo = [
            'title' => $product->name . ' | ' . config('seo.site_name'),
            'description' => str($product->short_description ?: $product->description ?: $product->name)->stripTags()->limit(160)->toString(),
            'canonical' => route('shop.product.detail', ['product_slug' => $product->slug]),
            'image' => asset('uploads/products/thumbnails/' . $product->image),
            'keywords' => implode(', ', array_filter([$product->name, optional($product->category)->name, optional($product->brand)->name, 'achat en ligne'])),
            'schemas' => [[
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->name,
                'description' => str($product->short_description ?: $product->description ?: $product->name)->stripTags()->limit(300)->toString(),
                'image' => [asset('uploads/products/thumbnails/' . $product->image)],
                'sku' => $product->SKU,
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'XOF',
                    'price' => (string) $price,
                    'availability' => (int) $product->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'url' => route('shop.product.detail', ['product_slug' => $product->slug]),
                ],
            ]],
        ];

        return view('details', compact('product', 'products', 'seo'));
    }
}
