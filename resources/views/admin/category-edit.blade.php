@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Catalog</div>
                <h2 class="admin-page-header__title">Edit category</h2>
                <p class="admin-page-header__meta">Update the category identity and refresh the media when the collection needs a new presentation.</p>
            </div>
            <ul class="admin-breadcrumbs">
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.categories') }}">Categories</a></li>
                <li>Edit</li>
            </ul>
        </div>

        @if (session('success'))
            <div class="admin-form-alert admin-form-alert--success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="form-new-product form-style-1 admin-form-grid">
            @csrf

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Category information</div>
                        <p class="admin-form-panel__meta">Refine the public category label and the slug used in filters and URLs.</p>
                    </div>
                    <span class="admin-form-badge">Catalog update</span>
                </div>

                <x-input-field name="name" label="Category name" :required="true" :value="$category->name" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="slug" label="Category slug" :required="true" :value="$category->slug" wrapperClass="form-floating theme-form-field" />
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Category media</div>
                        <p class="admin-form-panel__meta">Preview the current asset and replace it only when the category visual needs to change.</p>
                    </div>
                    <span class="admin-form-badge">Visual asset</span>
                </div>

                <fieldset class="theme-form-field">
                    <div class="body-title">Current image</div>
                    <div class="upload-image flex-grow">
                        <div class="item {{ $category->image ? '' : 'admin-inline-hidden' }}" id="imgpreview">
                            <img src="{{ asset($category->image) }}" class="effect8 admin-preview-image" alt="Current category image">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="theme-form-field">
                    <div class="body-title">Replace image</div>
                    <div class="upload-image flex-grow">
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your image here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    <p class="admin-form-help">Leave empty to keep the existing image.</p>
                    @error('image')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror
                </fieldset>

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Changing the slug can affect public links if the category is already referenced externally.</div>
                    <button class="tf-button" type="submit">Update category</button>
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
        }
    });
</script>
@endpush