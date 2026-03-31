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
            'title' => "Home | " . config('seo.site_name'),
            'description' => "Manu's Drop online store: new arrivals, sale products, and trend-driven picks for shopping in Togo.",
            'canonical' => route('home.index'),
            'keywords' => 'online store, fashion, accessories, shopping Togo, Manu\'s Drop',
        ];

        return view('index', compact('sliders', 'categories', 'sproducts', 'fproducts', 'seo'));
    }

    public function contacts()
    {
        $seo = [
            'title' => 'Contact | ' . config('seo.site_name'),
            'description' => "Contact Manu's Drop for orders, deliveries, and support requests.",
            'canonical' => route('home.contacts'),
            'keywords' => 'store contact, customer service, Manu\'s Drop',
        ];

        return view('contacts', compact('seo'));
    }

    public function abouts()
    {
        $seo = [
            'title' => 'About | ' . config('seo.site_name'),
            'description' => "D�couvrez l'univers de Manu's Drop, sa s�lection de produits et sa vision du shopping en ligne.",
            'canonical' => route('home.about'),
            'keywords' => 'about, online store, Manu\'s Drop',
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

        $query = trim($validated['query']);

        $results = Product::query()
            ->select('id', 'name', 'slug', 'image', 'regular_price', 'sale_price')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END', [$query . '%'])
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}
