@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Homepage</div>
                <h2 class="admin-page-header__title">Edit slide</h2>
                <p class="admin-page-header__meta">Refresh the homepage hero copy, replace the artwork, or change publication status.</p>
            </div>
            <ul class="admin-breadcrumbs">
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.sliders') }}">Slides</a></li>
                <li>Edit</li>
            </ul>
        </div>

        <form action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data" class="form-new-product form-style-1 admin-form-grid">
            @csrf
            @method('PUT')

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Slide content</div>
                        <p class="admin-form-panel__meta">Update the campaign message displayed in the homepage hero area.</p>
                    </div>
                    <span class="admin-form-badge">Hero content</span>
                </div>

                <x-input-field name="tagline" label="Tagline" :required="true" :value="$slider->tagline" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="title" label="Title" :required="true" :value="$slider->title" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="subtitle" label="Subtitle" :required="true" :value="$slider->subtitle" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="link" type="url" label="Link" :required="true" :value="$slider->link" wrapperClass="form-floating theme-form-field" />
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Visual and status</div>
                        <p class="admin-form-panel__meta">Preview the current image, replace it if needed, and adjust the publication status.</p>
                    </div>
                    <span class="admin-form-badge">Publishing</span>
                </div>

                <fieldset class="theme-form-field">
                    <div class="body-title">Current image</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview">
                            <img src="{{ asset($slider->image) }}" class="effect8 admin-preview-image admin-preview-image--wide" alt="Current slide image">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="theme-form-field">
                    <div class="body-title">Replace image</div>
                    <div class="upload-image flex-grow">
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your image here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="theme-form-field category">
                    <div class="body-title">Status</div>
                    <div class="select flex-grow admin-shell-select">
                        <select name="status" required>
                            <option value="">Select</option>
                            <option value="1" {{ old('status', $slider->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $slider->status) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </fieldset>

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Publishing changes are visible on the homepage as soon as the slide stays active.</div>
                    <button class="tf-button" type="submit">Update slide</button>
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