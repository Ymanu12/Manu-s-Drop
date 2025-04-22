@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">

            <!-- Header & Breadcrumbs -->
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Brand Information</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.brands') }}">
                            <div class="text-tiny">Brands</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">New Brand</div>
                    </li>
                </ul>
            </div>


            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success text-center my-3">
                    {{ session('success') }}
                </div>
            @endif

            <!-- New Brand Form -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" 
                    action="{{ route('admin.brand.update', $brand->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Brand Name -->
                    <fieldset class="name">
                        <div class="body-title">Brand Name <span class="tf-color-1">*</span></div>
                        <input type="text" name="name" class="flex-grow" placeholder="Brand name"
                            value="{{ old('name', $brand->name) }}">
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <!-- Brand Slug -->
                    <fieldset class="name">
                        <div class="body-title">Brand Slug <span class="tf-color-1">*</span></div>
                        <input type="text" name="slug" class="flex-grow" placeholder="Brand slug"
                            value="{{ old('slug', $brand->slug) }}">
                    </fieldset>
                    @error('slug')
                        <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <!-- Image Upload -->
                    <fieldset>
                        <div class="body-title">Upload Image <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview">
                                <img src="{{ asset($brand->image) }}"  class="effect8" alt="{{ $brand->name }}">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="body-text">
                                        Drop your images here or <span class="tf-color">click to browse</span>
                                    </span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <!-- Submit Button -->
                    <div class="bot mt-4">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById("myFile").addEventListener("change", function(event) {
    const [file] = event.target.files;
    if (file) {
        const preview = document.querySelector("#imgpreview img");
        preview.src = URL.createObjectURL(file);
        document.getElementById("imgpreview").style.display = "block";
    }
});
</script>
@endpush
