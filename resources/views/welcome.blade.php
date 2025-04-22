<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="header-section text-center py-5" style="background-color: #003366; color: #ffffff; margin-bottom: 50px;">
        <h1 class="display-3 font-weight-bold">Welcome to Our Website!</h1>
        <p class="lead">Explore our latest news, collections, and exclusive offers</p>
        <a href="#carouselExampleCaptions" class="btn btn-lg btn-light rounded-pill">Browse Our Products</a>
    </section>

    <!-- Carousel -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="First Image">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="carousel-caption-title">Exclusive Collections</h5>
                    <p>Discover our curated collections, perfect for every occasion.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Second Image">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="carousel-caption-title">Innovative Designs</h5>
                    <p>Explore the latest in design and technology with our new arrivals.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Third Image">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="carousel-caption-title">Stay Updated</h5>
                    <p>Get the latest updates and exclusive offers directly from us.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Featured Section -->
    <section class="featured-section py-5 text-center" style="background-color: #f4f6f9;">
        <h2 class="display-4 mb-4 font-weight-bold" style="color: #003366;">Our Special Offers</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Offer 1">
                    <div class="card-body">
                        <h5 class="card-title">Exclusive Discount</h5>
                        <p class="card-text">Take advantage of our limited-time offer on selected items.</p>
                        <a href="#" class="btn btn-primary rounded-pill">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Offer 2">
                    <div class="card-body">
                        <h5 class="card-title">Limited-Time Deals</h5>
                        <p class="card-text">Hurry up! Get amazing deals before they're gone.</p>
                        <a href="#" class="btn btn-primary rounded-pill">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Offer 3">
                    <div class="card-body">
                        <h5 class="card-title">Seasonal Specials</h5>
                        <p class="card-text">Enjoy amazing offers on seasonal products.</p>
                        <a href="#" class="btn btn-primary rounded-pill">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
