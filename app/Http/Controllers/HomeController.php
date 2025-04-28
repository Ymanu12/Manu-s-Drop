<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Contact;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sliders = Slider::where('status', 1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price','<>','')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured', 1)->get()->take(8);
        return view('index', compact('sliders','categories','sproducts','fproducts'));
    }

    public function contacts()
    {
        return view('contacts');
    }

    public function abouts()
    {
        return view('abouts');
    }

    public function contact_store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'comment' => 'required',
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;

        $contact->save();
        
        return redirect()->back()->with('status', 'Your message has been sent successfully!');
    }

    public function searchs(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name', 'LIKE', "%{$query}%")->take(8)->get();

        return response()->json($results);
    }
}
