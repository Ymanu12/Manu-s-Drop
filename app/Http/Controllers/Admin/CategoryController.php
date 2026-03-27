<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    use AuditsAdminActions;

    public function categories()
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function add_category()
    {
        $this->authorize('create', Category::class);

        return view('admin.category-add');
    }

    public function store_category(Request $request)
    {
        $this->authorize('create', Category::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/categories'), $imageName);
        $imagePath = 'uploads/categories/' . $imageName;

        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $imagePath,
        ]);

        $this->auditAdminAction('category.created', Category::class, $category->id, ['name' => $category->name, 'slug' => $category->slug]);

        return redirect()->route('admin.categories')->with('status', 'Category created successfully!');
    }

    public function update_category(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);

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

        $this->auditAdminAction('category.updated', Category::class, $category->id, ['name' => $category->name, 'slug' => $category->slug]);

        return redirect()->route('admin.categories')->with('status', 'Category updated successfully!');
    }

    public function generateCategoryThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/category');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $imageObject = ImageManager::make($image->getRealPath());
        $imageObject->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imageObject->save($destinationPath . '/' . $imageName);
    }

    public function category_edit($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('view', $category);
        return view('admin.category-edit', compact('category'));
    }

    public function destroy_category($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);

        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $categoryId = $category->id;
        $category->delete();

        $this->auditAdminAction('category.deleted', Category::class, $categoryId);

        return redirect()->route('admin.categories')->with('status', 'Category deleted successfully!');
    }
}
