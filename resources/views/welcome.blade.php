@extends('layouts.app')

@section('content')
<main class="pt-90 text-slate-900 dark:text-slate-100">
    <section class="container pt-4 pt-xl-5">
        <div class="theme-hero-surface rounded-3xl border border-slate-200/70 px-4 py-5 shadow-sm transition-colors dark:border-slate-800 md:px-5 md:py-6">
            <div class="mx-auto max-w-3xl text-center">
                <span class="d-inline-flex rounded-pill border border-amber-200/70 bg-amber-50 px-3 py-2 text-uppercase tracking-[0.2em] text-[11px] font-semibold text-amber-700 dark:border-amber-400/20 dark:bg-amber-400/10 dark:text-amber-200">Collection mise en avant</span>
                <h1 class="mt-4 display-4 fw-semibold">Bienvenue sur Manu's Drop</h1>
                <p class="mx-auto mt-3 max-w-2xl text-lg text-slate-600 dark:text-slate-300">Explore les nouveautes, les selections du moment et les offres exclusives avec la meme identite visuelle en mode clair comme en mode sombre.</p>
                <div class="mt-4 d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4">Parcourir la boutique</a>
                    <a href="{{ route('home.index') }}#featured-products" class="btn btn-outline-dark rounded-pill px-4 dark:border-slate-600 dark:text-slate-100">Voir les nouveautes</a>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div id="carouselExampleCaptions" class="carousel slide overflow-hidden rounded-3xl border border-slate-200/70 shadow-sm transition-colors dark:border-slate-800" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="theme-carousel-image" alt="Collection phare">
                    <div class="carousel-caption d-none d-md-block rounded-4 bg-slate-950/55 p-4 backdrop-blur-sm">
                        <h5 class="carousel-caption-title text-white">Collections exclusives</h5>
                        <p class="mb-0 text-slate-100">Des pieces selectionnees pour toutes les occasions, sans rupture visuelle entre clair et sombre.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="theme-carousel-image" alt="Nouveautes">
                    <div class="carousel-caption d-none d-md-block rounded-4 bg-slate-950/55 p-4 backdrop-blur-sm">
                        <h5 class="carousel-caption-title text-white">Designs recents</h5>
                        <p class="mb-0 text-slate-100">Decouvre les dernieres sorties avec un rendu lisible sur toutes les surfaces du site.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="theme-carousel-image" alt="Offres et actualites">
                    <div class="carousel-caption d-none d-md-block rounded-4 bg-slate-950/55 p-4 backdrop-blur-sm">
                        <h5 class="carousel-caption-title text-white">Offres en cours</h5>
                        <p class="mb-0 text-slate-100">Les promotions et mises a jour restent nettes, contrastees et coherentes avec le theme actif.</p>
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
    </section>

    <section class="container pb-5">
        <div class="theme-panel-muted rounded-3xl px-4 py-5 transition-colors md:px-5">
            <div class="text-center">
                <span class="text-uppercase tracking-[0.2em] text-[11px] fw-semibold theme-accent-text">Offres speciales</span>
                <h2 class="display-5 mt-3 mb-2">Nos selections du moment</h2>
                <p class="mx-auto mb-4 max-w-2xl text-slate-600 dark:text-slate-300">Chaque bloc reprend les memes couleurs, polices et contrastes que le reste du site pour eviter les sections qui paraissent hors theme.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <article class="theme-panel h-100 overflow-hidden rounded-4">
                        <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="theme-card-image" alt="Remise exclusive">
                        <div class="p-4">
                            <h5 class="mb-2">Remise exclusive</h5>
                            <p class="mb-3 text-slate-600 dark:text-slate-300">Une selection limitee avec une lecture claire et des cartes qui suivent enfin le theme sombre.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4">Decouvrir</a>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="theme-panel h-100 overflow-hidden rounded-4">
                        <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="theme-card-image" alt="Offres limitees">
                        <div class="p-4">
                            <h5 class="mb-2">Offres limitees</h5>
                            <p class="mb-3 text-slate-600 dark:text-slate-300">Des visuels, boutons et surfaces homogenes sur toute la page, sans fond clair force.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4">Voir les offres</a>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="theme-panel h-100 overflow-hidden rounded-4">
                        <img src="{{ asset('images/clark-street-mercantile-P3pI6xzovu0-unsplash.jpg') }}" class="theme-card-image" alt="Selection saisonniere">
                        <div class="p-4">
                            <h5 class="mb-2">Selection saisonniere</h5>
                            <p class="mb-3 text-slate-600 dark:text-slate-300">Le rythme visuel reste coherent avec les autres routes publiques et le changement clair/sombre est fluide.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4">Explorer</a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
