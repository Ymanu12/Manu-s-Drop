@extends('layouts.admin')

@section('content')
<div class="main-content-inner text-slate-900 dark:text-slate-100">
    <div class="main-content-wrap text-slate-900 dark:text-slate-100">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-header__eyebrow">Catalog</div>
                <h2 class="admin-page-header__title">Edit product</h2>
                <p class="admin-page-header__meta">Update merchandising content, media, pricing, and inventory from one structured wide workspace.</p>
            </div>
            <ul class="admin-breadcrumbs">
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.products') }}">Products</a></li>
                <li>Edit</li>
            </ul>
        </div>

        <form class="tf-section-2 form-add-product admin-form-grid" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.update', $product->id) }}" novalidate>
            @csrf
            @method('PUT')

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Core information</h4>
                        <div class="admin-form-panel__meta">Refine the product identity, category placement, and storefront descriptions.</div>
                    </div>
                    <div class="admin-form-badge">Catalog update</div>
                </div>

                <x-input-field name="name" label="Product name" :required="true" :value="$product->name" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="slug" label="Slug" :required="true" :value="$product->slug" wrapperClass="form-floating theme-form-field" />

                <fieldset class="theme-form-field category">
                    <div class="body-title">Category <span class="tf-color-1">*</span></div>
                    <div class="select admin-shell-select">
                        <select class="bg-transparent text-slate-900 dark:text-slate-100" name="category_id" required>
                            <option value="">Choose category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>

                <x-input-field name="short_description" as="textarea" label="Short description" :required="true" :value="$product->short_description" rows="7" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="description" as="textarea" label="Full description" :required="true" :value="$product->description" rows="12" wrapperClass="form-floating theme-form-field" />
            </section>

            <section class="admin-form-panel admin-form-stack">
                <div class="admin-form-panel__header">
                    <div>
                        <h4 class="admin-form-panel__title">Media</h4>
                        <div class="admin-form-panel__meta">Review the current visuals, then replace the main image or gallery if needed.</div>
                    </div>
                    <div class="admin-form-badge">Visual assets</div>
                </div>

                <fieldset class="theme-form-field">
                    <div class="body-title">Current image</div>
                    @if($product->image)
                        <div class="item" id="imgpreview">
                            <img src="{{ asset('uploads/products/' . $product->image) }}" class="effect8 admin-preview-image" alt="Current image">
                        </div>
                    @else
                        <div class="item admin-inline-hidden" id="imgpreview">
                            <img src="" class="effect8 admin-preview-image" alt="Preview image">
                        </div>
                    @endif
                </fieldset>

                <fieldset class="theme-form-field">
                    <div class="body-title">Replace primary image</div>
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

                <fieldset class="theme-form-field">
                    <div class="body-title">Gallery images</div>
                    <div class="upload-image mb-16">
                        @if(!empty($product->images_list))
                            @foreach($product->images_list as $img)
                                <div class="item gitems">
                                    <img src="{{ asset('uploads/products/thumbnails/' . trim($img)) }}" alt="Gallery image">
                                </div>
                            @endforeach
                        @endif
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
                        <div class="admin-form-panel__meta">Adjust commercial data and storefront availability.</div>
                    </div>
                    <div class="admin-form-badge">Inventory</div>
                </div>

                <x-input-field name="regular_price" label="Regular price" :required="true" :value="$product->regular_price" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="sale_price" label="Sale price" :value="$product->sale_price" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="SKU" label="SKU" :required="true" :value="$product->SKU" wrapperClass="form-floating theme-form-field" />
                <x-input-field name="quantity" label="Quantity" :required="true" :value="$product->quantity" wrapperClass="form-floating theme-form-field" />

                <fieldset class="theme-form-field name">
                    <div class="body-title">Stock status</div>
                    <div class="select admin-shell-select">
                        <select class="bg-transparent text-slate-900 dark:text-slate-100" name="stock_status">
                            <option value="instock" {{ old('stock_status', $product->stock_status) == 'instock' ? 'selected' : '' }}>In stock</option>
                            <option value="outofstock" {{ old('stock_status', $product->stock_status) == 'outofstock' ? 'selected' : '' }}>Out of stock</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset class="theme-form-field name">
                    <div class="body-title">Featured</div>
                    <div class="select admin-shell-select">
                        <select class="bg-transparent text-slate-900 dark:text-slate-100" name="featured">
                            <option value="0" {{ old('featured', $product->featured) == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('featured', $product->featured) == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </fieldset>

                <div class="admin-form-actions admin-form-actions--split">
                    <a href="{{ route('admin.products') }}" class="tf-button style-3 type-white">Back to products</a>
                    <button class="tf-button" type="submit">Update product</button>
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
            const previewContainer = document.getElementById("imgpreview");
            const preview = previewContainer.querySelector("img");
            preview.src = URL.createObjectURL(file);
            previewContainer.style.display = "block";
        }
    });

    $(function() {
        $("#gFile").on("change", function() {
            const gphotos = this.files;
            $("#galUpload").siblings('.gitems').remove();
            $.each(gphotos, function(_, file) {
                $("#galUpload").before(`<div class="item gitems"><img src="${URL.createObjectURL(file)}" alt="Gallery preview" /></div>`);
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