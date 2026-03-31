@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Catalog</div>
                <h3 class="admin-page-header__title">Create Brand</h3>
                <div class="admin-page-header__meta">Add a brand with a clean name, a reusable slug, and a strong visual identity for catalog navigation.</div>
            </div>
            <ul class="breadcrumbs admin-breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.brands') }}"><div class="text-tiny">Brands</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">New brand</div></li>
            </ul>
        </div>

        @if(session('success'))
            <div class="alert alert-success admin-form-alert mb-4">{{ session('success') }}</div>
        @endif

        <form class="form-new-product form-style-1 admin-form-grid" action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Brand information</h4>
                        <div class="admin-form-panel__meta">Set the public brand identity that customers will see throughout the storefront.</div>
                    </div>
                    <div class="admin-form-badge">Catalog setup</div>
                </div>

                <fieldset class="theme-form-field name">
                    <div class="body-title">Brand name <span class="tf-color-1">*</span></div>
                    <input type="text" name="name" class="admin-shell-input" placeholder="Brand name" value="{{ old('name') }}" required>
                    <div class="admin-form-help">Use a short, recognizable brand name.</div>
                </fieldset>
                @error('name')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror

                <fieldset class="theme-form-field name">
                    <div class="body-title">Brand slug <span class="tf-color-1">*</span></div>
                    <input type="text" name="slug" class="admin-shell-input" placeholder="Brand slug" value="{{ old('slug') }}" required>
                    <div class="admin-form-help">Keep the slug URL-friendly and stable over time.</div>
                </fieldset>
                @error('slug')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Brand media</h4>
                        <div class="admin-form-panel__meta">Upload a clean image or logo that works well on both light and dark surfaces.</div>
                    </div>
                    <div class="admin-form-badge">Visual asset</div>
                </div>

                <fieldset class="theme-form-field">
                    <div class="body-title">Upload image <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item admin-inline-hidden" id="imgpreview">
                            <img src="#" class="effect8 admin-preview-image" alt="Preview image">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your image here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">The image is used across brand listings and filters.</div>
                    <button class="tf-button" type="submit">Save brand</button>
                </div>
            </section>
        </form>
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