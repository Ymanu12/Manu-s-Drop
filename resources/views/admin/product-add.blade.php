@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Catalog</div>
                <h3 class="admin-page-header__title">Add product</h3>
                <div class="admin-page-header__meta">Create a product with clear merchandising details, large editing surfaces, and streamlined media and stock controls.</div>
            </div>
            <ul class="breadcrumbs admin-breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.products') }}"><div class="text-tiny">Products</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Add product</div></li>
            </ul>
        </div>

        <form class="tf-section-2 form-add-product admin-form-grid" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.store') }}" novalidate>
            @csrf

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Core information</h4>
                        <div class="admin-form-panel__meta">Define the product identity, taxonomy, and storefront descriptions.</div>
                    </div>
                    <div class="admin-form-badge">Catalog setup</div>
                </div>

                <x-input-field name="name" label="Product name" :required="true" :value="old('name')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="slug" label="Slug" :required="true" :value="old('slug')" wrapperClass="form-floating theme-form-field" />

                <fieldset class="theme-form-field category">
                    <div class="body-title">Category <span class="tf-color-1">*</span></div>
                    <div class="select admin-shell-select">
                        <select class="bg-transparent text-slate-900 dark:text-slate-100" name="category_id" required>
                            <option value="">Choose category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>

                <fieldset class="theme-form-field brand">
                    <div class="body-title">Brand <span class="tf-color-1">*</span></div>
                    <div class="select admin-shell-select">
                        <select class="bg-transparent text-slate-900 dark:text-slate-100" name="brand_id" required>
                            <option value="">Choose brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>

                <x-input-field name="short_description" as="textarea" label="Short description" :required="true" :value="old('short_description')" rows="7" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="description" as="textarea" label="Full description" :required="true" :value="old('description')" rows="12" wrapperClass="form-floating theme-form-field" />
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Media</h4>
                        <div class="admin-form-panel__meta">Upload the main visual first, then add optional gallery images.</div>
                    </div>
                    <div class="admin-form-badge">Visual assets</div>
                </div>

                <fieldset class="theme-form-field">
                    <div class="body-title">Primary image <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item admin-inline-hidden" id="imgpreview">
                            <img src="" class="effect8 admin-preview-image" alt="Preview image">
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

                <fieldset class="theme-form-field">
                    <div class="body-title">Gallery images</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="text-tiny">Drop multiple images here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                            </label>
                        </div>
                    </div>
                </fieldset>
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Pricing and stock</h4>
                        <div class="admin-form-panel__meta">Control pricing, inventory, and storefront visibility from one section.</div>
                    </div>
                    <div class="admin-form-badge">Commercial settings</div>
                </div>

                <x-input-field name="regular_price" label="Regular price" :required="true" :value="old('regular_price')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="sale_price" label="Sale price" :value="old('sale_price')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="SKU" label="SKU" :required="true" :value="old('SKU')" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="quantity" label="Quantity" :required="true" :value="old('quantity')" wrapperClass="form-floating theme-form-field" />

                <fieldset class="theme-form-field name">
                    <div class="body-title">Stock status</div>
                    <div class="select admin-shell-select">
                        <select class="bg-transparent text-slate-900 dark:text-slate-100" name="stock_status">
                            <option value="instock" {{ old('stock_status') == 'instock' ? 'selected' : '' }}>In stock</option>
                            <option value="outofstock" {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of stock</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset class="theme-form-field name">
                    <div class="body-title">Featured</div>
                    <div class="select admin-shell-select">
                        <select class="bg-transparent text-slate-900 dark:text-slate-100" name="featured">
                            <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </fieldset>

                <div class="admin-form-actions admin-form-actions--split">
                    <div class="admin-form-note">Save once the title, media, and pricing are all in place.</div>
                    <button class="tf-button" type="submit">Add product</button>
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

    $(function() {
        $("#gFile").on("change", function() {
            const gphotos = this.files;
            $("#galUpload .gitems").remove();
            $.each(gphotos, function(_, file) {
                $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(file)}" alt="Gallery preview" /></div>`);
            });
        });

        $("input[name='name']").on("change", function() {
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function StringToSlug(Text) {
        return Text.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
    }
</script>
@endpush