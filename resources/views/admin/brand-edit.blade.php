@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Catalog</div>
                <h3 class="admin-page-header__title">Edit Brand</h3>
                <div class="admin-page-header__meta">Update brand naming, URL structure, and imagery without changing the underlying catalog relationships.</div>
            </div>
            <ul class="breadcrumbs admin-breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.brands') }}"><div class="text-tiny">Brands</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Edit brand</div></li>
            </ul>
        </div>

        @if(session('success'))
            <div class="alert alert-success admin-form-alert mb-4">{{ session('success') }}</div>
        @endif

        <form class="form-new-product form-style-1 admin-form-grid" action="{{ route('admin.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Brand information</h4>
                        <div class="admin-form-panel__meta">Refine how this brand appears in the catalog and in customer-facing filters.</div>
                    </div>
                    <div class="admin-form-badge">Catalog update</div>
                </div>

                <fieldset class="theme-form-field name">
                    <div class="body-title">Brand name <span class="tf-color-1">*</span></div>
                    <input type="text" name="name" class="admin-shell-input" placeholder="Brand name" value="{{ old('name', $brand->name) }}" required>
                </fieldset>
                @error('name')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror

                <fieldset class="theme-form-field name">
                    <div class="body-title">Brand slug <span class="tf-color-1">*</span></div>
                    <input type="text" name="slug" class="admin-shell-input" placeholder="Brand slug" value="{{ old('slug', $brand->slug) }}" required>
                </fieldset>
                @error('slug')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Brand media</h4>
                        <div class="admin-form-panel__meta">Preview the current asset and replace it if the branding has changed.</div>
                    </div>
                    <div class="admin-form-badge">Visual asset</div>
                </div>

                <fieldset class="theme-form-field">
                    <div class="body-title">Current image</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview">
                            <img src="{{ asset($brand->image) }}" class="effect8 admin-preview-image" alt="{{ $brand->name }}">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop a new image here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Leave the image field untouched to keep the current file.</div>
                    <button class="tf-button" type="submit">Update brand</button>
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