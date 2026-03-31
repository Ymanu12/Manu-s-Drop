@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Catalog</div>
                <h2 class="admin-page-header__title">Create category</h2>
                <p class="admin-page-header__meta">Add a new collection entry with a clear slug and a representative image.</p>
            </div>
            <ul class="admin-breadcrumbs">
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li>Categories</li>
                <li>Create</li>
            </ul>
        </div>

        @if (session('status'))
            <div class="admin-form-alert admin-form-alert--success">{{ session('status') }}</div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="form-new-product form-style-1 admin-form-grid">
            @csrf

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Category information</div>
                        <p class="admin-form-panel__meta">Define the category name and slug used across navigation, filters, and product groupings.</p>
                    </div>
                    <span class="admin-form-badge">Catalog setup</span>
                </div>

                <x-input-field name="name" label="Category name" :required="true" :value="old('name')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="slug" label="Category slug" :required="true" :value="old('slug')" wrapperClass="form-floating theme-form-field" />
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Category media</div>
                        <p class="admin-form-panel__meta">Upload a clean, representative image for listing pages and promotional placements.</p>
                    </div>
                    <span class="admin-form-badge">Visual asset</span>
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
                    <p class="admin-form-help">Recommended: square image with clean padding for better category cards.</p>
                    @error('image')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror
                </fieldset>

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Categories structure the storefront. Keep naming consistent with your product taxonomy.</div>
                    <button class="tf-button" type="submit">Save category</button>
                </div>
            </section>
        </form>
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
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush