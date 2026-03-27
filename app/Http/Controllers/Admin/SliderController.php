<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class SliderController extends Controller
{
    use AuditsAdminActions;

    public function sliders()
    {
        $this->authorize('viewAny', Slider::class);

        $sliders = Slider::orderBy('id', 'DESC')->paginate(10);
        return view('admin.sliders', compact('sliders'));
    }

    public function add_slider()
    {
        $this->authorize('create', Slider::class);

        return view('admin.slider-add');
    }

    public function store_slider(Request $request)
    {
        $this->authorize('create', Slider::class);

        $request->validate([
            'tagline' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:3048',
            'status' => 'required|boolean',
        ]);

        $imageName = Str::random(10) . '_' . Carbon::now()->timestamp . '.' . $request->image->extension();
        $path = public_path('uploads/sliders/' . $imageName);

        if (!file_exists(public_path('uploads/sliders'))) {
            mkdir(public_path('uploads/sliders'), 0755, true);
        }

        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $manager->read($request->file('image'))
            ->resize(400, 690, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->cover(400, 690, 'top')
            ->save($path, quality: 100);

        $imagePath = 'uploads/sliders/' . $imageName;

        $slider = Slider::create([
            'tagline' => $request->tagline,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link' => $request->link,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        $this->auditAdminAction('slider.created', Slider::class, $slider->id, ['title' => $slider->title]);

        return redirect()->route('admin.sliders')->with('status', 'Slider ajoute avec succes.');
    }

    public function edit_slider($id)
    {
        $slider = Slider::findOrFail($id);
        $this->authorize('view', $slider);

        return view('admin.slider-edit', compact('slider'));
    }

    public function update_slider(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $this->authorize('update', $slider);

        $request->validate([
            'tagline' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3048',
            'status' => 'required|boolean',
        ]);

        $slider->tagline = $request->tagline;
        $slider->title = $request->title;
        $slider->subtitle = $request->subtitle;
        $slider->link = $request->link;
        $slider->status = $request->status;

        if ($request->hasFile('image')) {
            if (file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }

            $imageName = Str::random(10) . '_' . Carbon::now()->timestamp . '.' . $request->image->extension();
            $path = public_path('uploads/sliders/' . $imageName);

            $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $manager->read($request->file('image'))
                ->resize(400, 690, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->cover(400, 690)
                ->save($path, quality: 100);

            $slider->image = 'uploads/sliders/' . $imageName;
        }

        $slider->save();
        $this->auditAdminAction('slider.updated', Slider::class, $slider->id, ['title' => $slider->title]);

        return redirect()->route('admin.sliders')->with('status', 'Slider mis a jour avec succes.');
    }

    public function destroy_slider($id)
    {
        $slider = Slider::findOrFail($id);
        $this->authorize('delete', $slider);

        if (file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        $sliderId = $slider->id;
        $slider->delete();
        $this->auditAdminAction('slider.deleted', Slider::class, $sliderId);

        return redirect()->route('admin.sliders')->with('status', 'Slider supprime avec succes.');
    }
}
