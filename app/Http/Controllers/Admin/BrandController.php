<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use AuditsAdminActions;

    public function brands()
    {
        $this->authorize('viewAny', Brand::class);

        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        $this->authorize('create', Brand::class);

        return view('admin.brand-add');
    }

    public function store_brand(Request $request)
    {
        $this->authorize('create', Brand::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/brands'), $imageName);
        $chemin = 'uploads/brands/' . $imageName;

        $brand = Brand::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'image' => $chemin,
        ]);

        $this->auditAdminAction('brand.created', Brand::class, $brand->id, ['name' => $brand->name, 'slug' => $brand->slug]);

        return redirect()->route('admin.brands')->with('status', 'Brand created successfully.');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->authorize('view', $brand);
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
        $this->authorize('update', $brand);
        $path = $brand->image;

        if ($request->hasFile('image')) {
            if ($brand->image && file_exists(public_path($brand->image))) {
                unlink(public_path($brand->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/brands'), $imageName);
            $path = 'uploads/brands/' . $imageName;
        }

        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->image = $path;
        $brand->save();

        $this->auditAdminAction('brand.updated', Brand::class, $brand->id, ['name' => $brand->name, 'slug' => $brand->slug]);

        return redirect()->route('admin.brands')->with('status', 'Brand updated successfully!');
    }

    public function destroy_brand($id)
    {
        $brand = Brand::findOrFail($id);
        $this->authorize('delete', $brand);

        if ($brand->image && file_exists(public_path($brand->image))) {
            unlink(public_path($brand->image));
        }

        $brandId = $brand->id;
        $brand->delete();

        $this->auditAdminAction('brand.deleted', Brand::class, $brandId);

        return redirect()->route('admin.brands')->with('status', 'Brand deleted successfully!');
    }
}
