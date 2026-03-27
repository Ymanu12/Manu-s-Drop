{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($pages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach
@foreach($products as $product)
    <url>
        <loc>{{ route('shop.product.detail', ['product_slug' => $product->slug]) }}</loc>
        <lastmod>{{ optional($product->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <priority>0.8</priority>
    </url>
@endforeach
</urlset>
