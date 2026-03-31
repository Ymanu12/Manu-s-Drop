@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Homepage</div>
                <h2 class="admin-page-header__title">Create slide</h2>
                <p class="admin-page-header__meta">Build a polished hero slide with a clear message, strong visual, and a clean call to action.</p>
            </div>
            <ul class="admin-breadcrumbs">
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.sliders') }}">Slides</a></li>
                <li>Create</li>
            </ul>
        </div>

        <form action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data" class="form-new-product form-style-1 admin-form-grid">
            @csrf

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Slide content</div>
                        <p class="admin-form-panel__meta">Write the copy displayed in the homepage hero carousel.</p>
                    </div>
                    <span class="admin-form-badge">Hero content</span>
                </div>

                <x-input-field name="tagline" label="Tagline" :required="true" :value="old('tagline')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="title" label="Title" :required="true" :value="old('title')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="subtitle" label="Subtitle" :required="true" :value="old('subtitle')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="link" type="url" label="Link" :required="true" :value="old('link')" wrapperClass="form-floating theme-form-field" />
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <div class="admin-form-panel__title">Visual and status</div>
                        <p class="admin-form-panel__meta">Upload the artwork and decide whether the slide should be published immediately.</p>
                    </div>
                    <span class="admin-form-badge">Publishing</span>
                </div>

                <fieldset class="theme-form-field">
                    <div class="body-title">Upload image <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item admin-inline-hidden" id="imgpreview">
                            <img src="#" class="effect8 admin-preview-image" alt="Preview image">
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your image here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('image')<span class="alert alert-danger admin-form-alert">{{ $message }}</span>@enderror
                </fieldset>

                <fieldset class="theme-form-field category">
                    <div class="body-title">Status</div>
                    <div class="select flex-grow admin-shell-select">
                        <select name="status" required>
                            <option value="">Select</option>
                            <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <p class="admin-form-help">Use inactive status to prepare the slide before publishing it.</p>
                </fieldset>

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Hero slides should combine clear copy with a high-quality visual.</div>
                    <button class="tf-button" type="submit">Save slide</button>
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