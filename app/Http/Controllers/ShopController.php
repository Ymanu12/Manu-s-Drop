<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Nombre de produits par page (par défaut : 12)
        $size = $request->query('size', 12);

        // Valeur de tri reçue depuis l'URL
        $order = $request->query('order', -1);

        $f_brands = $request->query('brands') ?? '';

        $f_categories = $request->query('categories', '');

        // Définir les colonnes et l'ordre de tri
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
        $brands = Brand::with('products')->orderBy('name')->get();

        $categories = Category::with('products')->orderBy('name')->get();

        $products = Product::where(function($query) use ($f_brands, $f_categories) {
            if (!empty($f_brands)) {
                $query->whereIn('brand_id', explode(',', $f_brands));
            }
            if (!empty($f_categories)) {
                $query->whereIn('category_id', explode(',', $f_categories));
            }
        })->orderBy($o_column, $o_order)->paginate($size);
            
        // Envoyer les données à la vue shop.blade.php
        return view('shop', compact('products', 'size', 'order','brands','f_brands','categories'));
    }

    public function product_details($product_slug)
    {
        // Trouver le produit avec le slug
        $product = Product::where('slug', $product_slug)->first();

        // Si le produit n'existe pas, erreur 404
        if (!$product) {
            abort(404);
        }

        // Obtenir d'autres produits pour suggestions (max 8)
        $products = Product::where('slug', '<>', $product_slug)->take(8)->get();

        // Afficher la vue détails
        return view('details', compact('product', 'products'));
    }
}
