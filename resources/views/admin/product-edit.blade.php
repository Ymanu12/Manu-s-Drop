@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.products') }}"><div class="text-tiny">Products</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Edit product</div></li>
            </ul>
        </div>

        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.update', $product->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" name="name" value="{{ old('name', $product->name) }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" name="slug" value="{{ old('slug', $product->slug) }}" required>
                </fieldset>

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="category_id" required>
                                <option value="">Choose category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="brand_id" required>
                                <option value="">Choose Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" required>{{ old('short_description', $product->short_description) }}</textarea>
                </fieldset>

                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" required>{{ old('description', $product->description) }}</textarea>
                </fieldset>
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Current Image</div>
                    @if($product->image)
                        <div class="item" id="imgpreview">
                            <img src="{{ asset('uploads/products/' . $product->image) }}" class="effect8" style="max-width: 150px;" alt="Current Image">
                        </div>
                    @endif

                    <div class="body-title mt-4">Change Image</div>
                    <div class="upload-image flex-grow">
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="images[]" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                        <div class="upload-image mb-16">
                            @if(!empty($product->images_list))
                                @foreach($product->images_list as $img)
                                    <div class="item">
                                        <img src="{{ asset('uploads/products/thumbnails/' . trim($img)) }}" alt="">
                                    </div>
                                @endforeach
                            @endif
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="text-tiny">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                                </label>
                            </div>
                    </div>
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input type="text" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" required>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input type="text" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" required>
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                        <input type="text" name="SKU" value="{{ old('SKU', $product->SKU) }}" required>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input type="text" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select name="stock_status">
                                <option value="instock" {{ $product->stock_status == 'instock' ? 'selected' : '' }}>In Stock</option>
                                <option value="outofstock" {{ $product->stock_status == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select name="featured">
                                <option value="0" {{ $product->featured == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $product->featured == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Update Product</button>
                </div>
            </div>
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
            $.each(gphotos, function(_, file) {
                $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(file)}" /></div>`);
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
