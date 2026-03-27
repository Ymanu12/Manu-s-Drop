<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status', 1)->latest()->take(3)->get();
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->take(8)->get();
        $fproducts = Product::where('featured', 1)->latest()->take(8)->get();
        $seo = [
            'title' => "Accueil | " . config('seo.site_name'),
            'description' => "Boutique en ligne Manu's Drop: nouveautés, produits en promotion et sélections tendance pour vos achats au Togo.",
            'canonical' => route('home.index'),
            'keywords' => 'boutique en ligne, mode, accessoires, shopping Togo, Manu\'s Drop',
        ];

        return view('index', compact('sliders', 'categories', 'sproducts', 'fproducts', 'seo'));
    }

    public function contacts()
    {
        $seo = [
            'title' => 'Contact | ' . config('seo.site_name'),
            'description' => "Contactez Manu's Drop pour vos commandes, livraisons et demandes d'information.",
            'canonical' => route('home.contacts'),
            'keywords' => 'contact boutique, service client, Manu\'s Drop',
        ];

        return view('contacts', compact('seo'));
    }

    public function abouts()
    {
        $seo = [
            'title' => 'A propos | ' . config('seo.site_name'),
            'description' => "Découvrez l'univers de Manu's Drop, sa sélection de produits et sa vision du shopping en ligne.",
            'canonical' => route('home.about'),
            'keywords' => 'a propos, boutique en ligne, Manu\'s Drop',
        ];

        return view('abouts', compact('seo'));
    }

    public function contact_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|string|max:20',
            'comment' => 'required|string|max:2000',
        ]);

        $contact = new Contact();
        $contact->name = $validated['name'];
        $contact->email = $validated['email'];
        $contact->phone = $validated['phone'];
        $contact->comment = $validated['comment'];
        $contact->save();

        return back()->with('status', 'Your message has been sent successfully!');
    }

    public function searchs(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:100',
        ]);

        $results = Product::query()
            ->select('id', 'name', 'slug', 'image', 'regular_price', 'sale_price')
            ->where('name', 'LIKE', '%' . $validated['query'] . '%')
            ->limit(8)
            ->get();

        return response()->json($results);
    }
}
