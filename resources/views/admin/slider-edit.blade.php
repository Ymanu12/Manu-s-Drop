@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slide</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.sliders') }}">
                            <div class="text-tiny">Slider</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Edit Slide</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <fieldset class="name">
                        <div class="body-title">Tagline <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Tagline" name="tagline"
                            tabindex="0" value="{{ old('tagline', $slider->tagline) }}" aria-required="true" required>
                    </fieldset>
                    @error('tagline')
                        <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title">Title <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Title" name="title"
                            tabindex="0" value="{{ old('title', $slider->title) }}" aria-required="true" required>
                    </fieldset>
                    @error('title')
                        <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title">Subtitle <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Subtitle" name="subtitle"
                            tabindex="0" value="{{ old('subtitle', $slider->subtitle) }}" aria-required="true" required>
                    </fieldset>
                    @error('subtitle')
                        <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title">Link <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="url" placeholder="Link" name="link"
                            tabindex="0" value="{{ old('link', $slider->link) }}" aria-required="true" required>
                    </fieldset>
                    @error('link')
                        <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <fieldset>
                        <div class="body-title">Current Image</div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview">
                                <img src="{{ asset($slider->image) }}" class="effect8" alt="Current Image" style="max-width: 300px;">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="body-title">Change Image</div>
                        <div class="upload-image flex-grow">
                            <div class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="category">
                        <div class="body-title">Status</div>
                        <div class="select flex-grow">
                            <select name="status" required>
                                <option value="">Select</option>
                                <option value="1" {{ old('status', $slider->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $slider->status) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('myFile').addEventListener('change', function(event) {
        const preview = document.getElementById('imgpreview');
        const img = preview.querySelector('img');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
