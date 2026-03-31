@extends('layouts.app')

@php
    $lastUpdated = config('privacy.last_updated', '2025-01-01');
    $companyName = config('app.name', 'Notre Entreprise');
    $contactEmail = config('privacy.contact_email', 'privacy@example.com');
    $dpoEmail = config('privacy.dpo_email', null);
    $companyAddress = config('privacy.address', null);
    $cookiesEnabled = config('privacy.cookies_enabled', true);
    $analyticsEnabled = config('privacy.analytics_enabled', true);
    $thirdPartyEnabled = config('privacy.third_party_sharing', false);
    $userIsAuthenticated = auth()->check();
    $userIsMinor = $userIsAuthenticated && auth()->user()->age < 18;
    $gdprRegion = in_array(request()->header('CF-IPCountry'), ['FR','DE','IT','ES','BE','NL','PL','PT','SE','AT','DK','FI','IE','LU','GR','CZ','HU','RO','SK','BG','HR','SI','EE','LV','LT','MT','CY']);
    $ccpaRegion = request()->header('CF-IPCountry') === 'US';
    $locale = app()->getLocale();
    $isProduction = app()->environment('production');
    $retentionPeriod = config('privacy.retention_months', 24);
    $dataProcessingBasis = config('privacy.legal_basis', ['consent', 'contract', 'legitimate_interest']);
    $hasNewsletter = config('privacy.newsletter', true);
    $hasPayment = config('privacy.payment_processing', false);
    $hasProfiling = config('privacy.profiling', false);
    $hasAi = config('privacy.ai_processing', false);
    $privacyPolicyUrl = Route::has('privacy.policy') ? route('privacy.policy') : url('/politique-de-confidentialite');
    $doNotSellUrl = Route::has('privacy.do-not-sell') ? route('privacy.do-not-sell') : route('home.contacts');
    $accountDataUrl = Route::has('user.account.details') ? route('user.account.details') : route('home.contacts');
    $parentalConsentUrl = Route::has('parental.consent') ? route('parental.consent') : route('home.contacts');
    $termsUrl = Route::has('terms') ? route('terms') : route('mentions.legales');
@endphp

@section('title', __('privacy.page_title', ['company' => $companyName]))

@section('meta')
    <meta name="robots" content="noindex, follow">
    <meta name="description" content="{{ __('privacy.meta_description', ['company' => $companyName]) }}">
    <link rel="canonical" href="{{ $privacyPolicyUrl }}">
    @if($gdprRegion)
        <meta name="gdpr-compliant" content="true">
    @endif
@endsection

@section('content')
<main class="pt-90 text-slate-900 dark:text-slate-100">

{{-- ------------------------------------------------------
     HERO / EN-TÊTE
------------------------------------------------------ --}}
<section class="pp-hero container pt-4 pt-xl-5">
    <div class="pp-hero__inner rounded-xl border border-slate-200/70 bg-white/90 p-4 p-xl-5 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/90">
        <div class="pp-hero__badge">
            @if($gdprRegion)
                <span class="pp-badge pp-badge--gdpr">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    RGPD Conforme
                </span>
            @endif
            @if($ccpaRegion)
                <span class="pp-badge pp-badge--ccpa">CCPA Compliant</span>
            @endif
            @if(!$gdprRegion && !$ccpaRegion)
                <span class="pp-badge pp-badge--default">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Données Protégées
                </span>
            @endif
        </div>

        <h1 class="pp-hero__title">Politique de Confidentialité</h1>

        <p class="pp-hero__subtitle">
            Chez <strong>{{ $companyName }}</strong>, la protection de vos données personnelles est une priorité absolue.
            Cette politique explique de manière transparente comment nous collectons, utilisons et protégeons vos informations.
        </p>

        <div class="pp-hero__meta">
            <div class="pp-meta-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Dernière mise à jour : <strong>{{ \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y') }}</strong>
            </div>
            <div class="pp-meta-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Temps de lecture : <strong>~8 minutes</strong>
            </div>
            @if($userIsAuthenticated)
            <div class="pp-meta-item pp-meta-item--user">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Vous êtes connecté(e) - <a href="{{ $accountDataUrl }}">Gérer mes données</a>
            </div>
            @endif
        </div>

        {{-- Alerte mineur --}}
        @if($userIsMinor)
        <div class="pp-alert pp-alert--warning rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <strong>Compte mineur détecté.</strong>
                Si vous avez moins de 16 ans, l'utilisation de nos services nécessite le consentement de votre représentant légal.
                <a href="{{ $parentalConsentUrl }}">En savoir plus</a>
            </div>
        </div>
        @endif

    </div>

    {{-- Table des matières --}}
    <div class="pt-4">
        <nav class="pp-toc rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" aria-label="Table des matières">
            <h2 class="pp-toc__title">Sommaire</h2>
            <ol class="pp-toc__list">
                <li><a href="#responsable">1. Responsable du traitement</a></li>
                <li><a href="#donnees-collectees">2. Données collectées</a></li>
                <li><a href="#finalites">3. Finalités et bases légales</a></li>
                @if($cookiesEnabled)
                <li><a href="#cookies">4. Cookies et traceurs</a></li>
                @endif
                @if($analyticsEnabled)
                <li><a href="#analytics">5. Analytiques et mesure d'audience</a></li>
                @endif
                @if($thirdPartyEnabled)
                <li><a href="#tiers">6. Partage avec des tiers</a></li>
                @endif
                @if($hasPayment)
                <li><a href="#paiement">7. Traitement des paiements</a></li>
                @endif
                @if($hasProfiling)
                <li><a href="#profilage">8. Profilage et décisions automatisées</a></li>
                @endif
                @if($hasAi)
                <li><a href="#ia">9. Intelligence artificielle</a></li>
                @endif
                <li><a href="#conservation">{{ $cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi ? '10' : '6' }}. Durée de conservation</a></li>
                <li><a href="#droits">{{ $cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi ? '11' : '7' }}. Vos droits</a></li>
                <li><a href="#securite">{{ $cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi ? '12' : '8' }}. Sécurité</a></li>
                <li><a href="#contact">{{ $cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi ? '13' : '9' }}. Nous contacter</a></li>
            </ol>
        </nav>
    </div>
</section>

{{-- ------------------------------------------------------
     CONTENU PRINCIPAL
------------------------------------------------------ --}}
<div class="container pp-body pb-5">
    <div class="pp-content">

        {{-- --- 1. RESPONSABLE DU TRAITEMENT --- --}}
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="responsable">
            <div class="pp-section__header">
                <span class="pp-section__number">01</span>
                <h2 class="pp-section__title">Responsable du traitement</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Le responsable du traitement des données à caractère personnel est <strong>{{ $companyName }}</strong>,
                    représenté par son dirigeant légal.
                </p>

                @if($companyAddress)
                <div class="pp-card pp-card--info rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <div class="pp-card__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <strong>Siège social :</strong><br>
                        {!! nl2br(e($companyAddress)) !!}
                    </div>
                </div>
                @endif

                @if($dpoEmail)
                <div class="pp-card pp-card--dpo rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <div class="pp-card__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <strong>Délégué à la Protection des Données (DPO) :</strong><br>
                        Notre DPO est joignable à l'adresse :
                        <a href="mailto:{{ $dpoEmail }}" class="pp-link">{{ $dpoEmail }}</a>
                    </div>
                </div>
                @else
                <div class="pp-card pp-card--info rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <div class="pp-card__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div>
                        <strong>Contact protection des données :</strong>
                        <a href="mailto:{{ $contactEmail }}" class="pp-link">{{ $contactEmail }}</a>
                    </div>
                </div>
                @endif

                @if($gdprRegion)
                <div class="pp-infobox pp-infobox--gdpr rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Conformément au <strong>Règlement Général sur la Protection des Données (RGPD)</strong> - Règlement (UE) 2016/679 -
                    nous nous engageons à traiter vos données en toute transparence.
                </div>
                @elseif($ccpaRegion)
                <div class="pp-infobox pp-infobox--ccpa rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    En tant que résident californien, vous bénéficiez de droits spécifiques au titre du
                    <strong>California Consumer Privacy Act (CCPA)</strong>.
                    <a href="#droits" class="pp-link">Voir vos droits ?</a>
                </div>
                @endif
            </div>
        </section>

        {{-- --- 2. DONNÉES COLLECTÉES --- --}}
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="donnees-collectees">
            <div class="pp-section__header">
                <span class="pp-section__number">02</span>
                <h2 class="pp-section__title">Données que nous collectons</h2>
            </div>
            <div class="pp-section__body">
                <p>Nous collectons uniquement les données strictement nécessaires à la fourniture de nos services.</p>

                <div class="pp-data-grid">
                    {{-- Données d'identification --}}
                    <div class="pp-data-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-data-card__icon pp-data-card__icon--blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <h3>Données d'identification</h3>
                        <ul>
                            <li>Nom et prénom</li>
                            <li>Adresse e-mail</li>
                            <li>Numéro de téléphone <span class="pp-tag pp-tag--optional">Optionnel</span></li>
                            @if($userIsAuthenticated)
                            <li>Identifiant de compte</li>
                            <li>Photo de profil <span class="pp-tag pp-tag--optional">Optionnel</span></li>
                            @endif
                        </ul>
                    </div>

                    {{-- Données de navigation --}}
                    @if($cookiesEnabled || $analyticsEnabled)
                    <div class="pp-data-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-data-card__icon pp-data-card__icon--purple">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        </div>
                        <h3>Données de navigation</h3>
                        <ul>
                            <li>Adresse IP (anonymisée)</li>
                            <li>Type de navigateur et appareil</li>
                            <li>Pages visitées et durée</li>
                            @if($analyticsEnabled)
                            <li>Source de trafic</li>
                            <li>Interactions avec le site</li>
                            @endif
                            @if($cookiesEnabled)
                            <li>Données de cookies <span class="pp-tag pp-tag--consentement">Consentement</span></li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    {{-- Données de paiement --}}
                    @if($hasPayment)
                    <div class="pp-data-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-data-card__icon pp-data-card__icon--green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        <h3>Données de paiement</h3>
                        <ul>
                            <li>Historique des transactions</li>
                            <li>Montants et devises</li>
                            <li>Données bancaires <span class="pp-tag pp-tag--secure">Chiffrées</span></li>
                        </ul>
                        <p class="pp-data-card__note">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Les données bancaires sont traitées exclusivement par nos partenaires certifiés PCI-DSS.
                        </p>
                    </div>
                    @endif

                    {{-- Données de communication --}}
                    @if($hasNewsletter)
                    <div class="pp-data-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-data-card__icon pp-data-card__icon--orange">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <h3>Communications</h3>
                        <ul>
                            <li>Adresse e-mail newsletter <span class="pp-tag pp-tag--consentement">Consentement</span></li>
                            <li>Préférences de communication</li>
                            <li>Historique des échanges</li>
                            <li>Taux d'ouverture (anonymisé)</li>
                        </ul>
                    </div>
                    @endif
                </div>

                {{-- Données sensibles --}}
                <div class="pp-alert pp-alert--info rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <strong>Données sensibles :</strong>
                        Nous ne collectons <strong>aucune donnée sensible</strong> au sens de l'article 9 du RGPD
                        (origine ethnique, opinions politiques, convictions religieuses, données de santé, orientation sexuelle, etc.),
                        sauf consentement explicite et justification légale.
                    </div>
                </div>

                {{-- Collecte indirecte --}}
                @if($thirdPartyEnabled || $analyticsEnabled)
                <h3 class="pp-subsection-title">Données collectées indirectement</h3>
                <p>
                    Certaines données peuvent être collectées indirectement via :
                </p>
                <ul class="pp-list">
                    @if($analyticsEnabled)
                    <li>Outils d'analyse d'audience (Google Analytics, Matomo, etc.)</li>
                    @endif
                    @if($thirdPartyEnabled)
                    <li>Réseaux sociaux (si vous vous connectez via un compte tiers)</li>
                    <li>Partenaires commerciaux (dans le cadre de programmes co-brandés)</li>
                    @endif
                    @if($cookiesEnabled)
                    <li>Pixels de suivi et balises web (soumis à votre consentement)</li>
                    @endif
                </ul>
                @endif
            </div>
        </section>

        {{-- --- 3. FINALITÉS ET BASES LÉGALES --- --}}
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="finalites">
            <div class="pp-section__header">
                <span class="pp-section__number">03</span>
                <h2 class="pp-section__title">Finalités du traitement et bases légales</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Chaque traitement de données repose sur une base légale identifiée conformément à l'article 6 du RGPD.
                </p>

                <div class="pp-table-wrap">
                    <table class="pp-table">
                        <thead>
                            <tr>
                                <th>Finalité</th>
                                <th>Base légale</th>
                                <th>Obligatoire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Création et gestion de votre compte</td>
                                <td><span class="pp-basis pp-basis--contract">Exécution du contrat</span></td>
                                <td><span class="pp-tag pp-tag--required">Oui</span></td>
                            </tr>
                            <tr>
                                <td>Fourniture des services souscrits</td>
                                <td><span class="pp-basis pp-basis--contract">Exécution du contrat</span></td>
                                <td><span class="pp-tag pp-tag--required">Oui</span></td>
                            </tr>
                            @if(in_array('legitimate_interest', $dataProcessingBasis))
                            <tr>
                                <td>Prévention de la fraude et sécurité</td>
                                <td><span class="pp-basis pp-basis--interest">Intérêt légitime</span></td>
                                <td><span class="pp-tag pp-tag--required">Oui</span></td>
                            </tr>
                            <tr>
                                <td>Amélioration de nos services</td>
                                <td><span class="pp-basis pp-basis--interest">Intérêt légitime</span></td>
                                <td><span class="pp-tag pp-tag--optional">Non</span></td>
                            </tr>
                            @endif
                            @if(in_array('consent', $dataProcessingBasis))
                            @if($hasNewsletter)
                            <tr>
                                <td>Envoi de la newsletter et communications marketing</td>
                                <td><span class="pp-basis pp-basis--consent">Consentement</span></td>
                                <td><span class="pp-tag pp-tag--optional">Non</span></td>
                            </tr>
                            @endif
                            @if($cookiesEnabled)
                            <tr>
                                <td>Dépôt de cookies non essentiels</td>
                                <td><span class="pp-basis pp-basis--consent">Consentement</span></td>
                                <td><span class="pp-tag pp-tag--optional">Non</span></td>
                            </tr>
                            @endif
                            @if($hasProfiling)
                            <tr>
                                <td>Personnalisation et profilage</td>
                                <td><span class="pp-basis pp-basis--consent">Consentement</span></td>
                                <td><span class="pp-tag pp-tag--optional">Non</span></td>
                            </tr>
                            @endif
                            @endif
                            <tr>
                                <td>Obligations légales et comptables</td>
                                <td><span class="pp-basis pp-basis--legal">Obligation légale</span></td>
                                <td><span class="pp-tag pp-tag--required">Oui</span></td>
                            </tr>
                            <tr>
                                <td>Gestion des litiges et défense en justice</td>
                                <td><span class="pp-basis pp-basis--legal">Obligation légale</span></td>
                                <td><span class="pp-tag pp-tag--optional">Selon cas</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- --- 4. COOKIES --- --}}
        @if($cookiesEnabled)
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="cookies">
            <div class="pp-section__header">
                <span class="pp-section__number">04</span>
                <h2 class="pp-section__title">Cookies et traceurs</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Notre site utilise des cookies et technologies similaires. Conformément à la réglementation en vigueur,
                    seuls les cookies strictement nécessaires au fonctionnement du site sont déposés sans votre consentement préalable.
                </p>

                <div class="pp-cookie-grid">
                    <div class="pp-cookie-type">
                        <div class="pp-cookie-type__header pp-cookie-type__header--essential">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <strong>Essentiels</strong>
                            <span class="pp-tag pp-tag--always">Toujours actifs</span>
                        </div>
                        <p>Indispensables au fonctionnement du site : session utilisateur, panier, sécurité CSRF.</p>
                        <div class="pp-cookie-list">
                            <div class="pp-cookie-item">
                                <code>XSRF-TOKEN</code>
                                <span>Protection CSRF</span>
                                <span>Session</span>
                            </div>
                            <div class="pp-cookie-item">
                                <code>laravel_session</code>
                                <span>Session utilisateur</span>
                                <span>Session</span>
                            </div>
                            @if($hasNewsletter)
                            <div class="pp-cookie-item">
                                <code>newsletter_dismissed</code>
                                <span>Préférence bannière</span>
                                <span>30 jours</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($analyticsEnabled)
                    <div class="pp-cookie-type">
                        <div class="pp-cookie-type__header pp-cookie-type__header--analytics">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            <strong>Analytiques</strong>
                            <span class="pp-tag pp-tag--consentement">Sur consentement</span>
                        </div>
                        <p>Mesure d'audience anonymisée pour améliorer notre service.</p>
                        <div class="pp-cookie-list">
                            <div class="pp-cookie-item">
                                <code>_ga</code>
                                <span>Google Analytics - identifiant</span>
                                <span>2 ans</span>
                            </div>
                            <div class="pp-cookie-item">
                                <code>_ga_*</code>
                                <span>Google Analytics - session</span>
                                <span>1 an</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($thirdPartyEnabled)
                    <div class="pp-cookie-type">
                        <div class="pp-cookie-type__header pp-cookie-type__header--marketing">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <strong>Marketing & Publicité</strong>
                            <span class="pp-tag pp-tag--consentement">Sur consentement</span>
                        </div>
                        <p>Personnalisation publicitaire et suivi des conversions.</p>
                        <div class="pp-cookie-list">
                            <div class="pp-cookie-item">
                                <code>_fbp</code>
                                <span>Facebook Pixel</span>
                                <span>90 jours</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pp-cta-block">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07M4.93 4.93a10 10 0 0 0 0 14.14M8.46 8.46a5 5 0 0 0 0 7.07"/></svg>
                    <div>
                        Vous pouvez gérer vos préférences de cookies à tout moment.
                        <button class="pp-btn pp-btn--secondary" onclick="openCookieManager()">
                            Gérer mes préférences
                        </button>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- --- 5. ANALYTICS --- --}}
        @if($analyticsEnabled)
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="analytics">
            <div class="pp-section__header">
                <span class="pp-section__number">05</span>
                <h2 class="pp-section__title">Analytiques et mesure d'audience</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Nous utilisons des outils d'analyse pour comprendre comment nos visiteurs interagissent avec notre site
                    et ainsi améliorer l'expérience utilisateur.
                </p>
                <div class="pp-infobox pp-infobox--neutral rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <div>
                        Les données collectées à des fins analytiques sont <strong>anonymisées ou pseudonymisées</strong>.
                        Les adresses IP sont tronquées avant tout enregistrement. Aucun profil individuel n'est constitué
                        à des fins publicitaires sans votre consentement explicite.
                    </div>
                </div>
                @if($gdprRegion)
                <p>
                    Si vous avez refusé les cookies analytiques dans notre bandeau de consentement, aucune donnée de navigation
                    n'est transmise à des services tiers d'analyse.
                </p>
                @endif
            </div>
        </section>
        @endif

        {{-- --- 6. PARTAGE TIERS --- --}}
        @if($thirdPartyEnabled)
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="tiers">
            <div class="pp-section__header">
                <span class="pp-section__number">06</span>
                <h2 class="pp-section__title">Partage avec des tiers</h2>
            </div>
            <div class="pp-section__body">
                <div class="pp-alert pp-alert--warning rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <div>
                        <strong>Nous ne vendons jamais vos données personnelles.</strong>
                        Tout partage est encadré par des contrats de sous-traitance conformes au RGPD.
                    </div>
                </div>

                <p>Vos données peuvent être partagées avec les catégories de destinataires suivantes :</p>
                <ul class="pp-list pp-list--detailed">
                    <li>
                        <strong>Prestataires techniques</strong> (hébergeur, CDN, service e-mail transactionnel)
                        - dans le cadre de l'exécution du contrat.
                    </li>
                    @if($hasPayment)
                    <li>
                        <strong>Partenaires de paiement</strong> (Stripe, PayPal, etc.)
                        - soumis à certification PCI-DSS et leurs propres politiques de confidentialité.
                    </li>
                    @endif
                    @if($hasNewsletter)
                    <li>
                        <strong>Plateforme d'e-mailing</strong> (Mailchimp, Brevo, etc.)
                        - uniquement si vous avez consenti à recevoir nos communications.
                    </li>
                    @endif
                    <li>
                        <strong>Autorités compétentes</strong>
                        - sur réquisition judiciaire ou obligation légale uniquement.
                    </li>
                </ul>

                @if($gdprRegion)
                <h3 class="pp-subsection-title">Transferts hors Union Européenne</h3>
                <p>
                    Certains de nos sous-traitants peuvent être établis hors de l'UE. Dans ce cas, nous nous assurons
                    que des garanties appropriées sont en place (Clauses Contractuelles Types de la Commission européenne,
                    décision d'adéquation, etc.) conformément au chapitre V du RGPD.
                </p>
                @endif
            </div>
        </section>
        @endif

        {{-- --- 7. PAIEMENT --- --}}
        @if($hasPayment)
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="paiement">
            <div class="pp-section__header">
                <span class="pp-section__number">07</span>
                <h2 class="pp-section__title">Traitement des paiements</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    {{ $companyName }} ne stocke <strong>jamais</strong> vos données de carte bancaire sur ses serveurs.
                    Les paiements sont traités directement par nos partenaires certifiés <strong>PCI-DSS niveau 1</strong>.
                </p>
                <div class="pp-card pp-card--info rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <div class="pp-card__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        Nous conservons uniquement un <strong>token de paiement sécurisé</strong> (identifiant anonymisé)
                        qui ne permet pas de reconstituer vos coordonnées bancaires.
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- --- 8. PROFILAGE --- --}}
        @if($hasProfiling)
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="profilage">
            <div class="pp-section__header">
                <span class="pp-section__number">08</span>
                <h2 class="pp-section__title">Profilage et décisions automatisées</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Nous utilisons des techniques de profilage pour personnaliser votre expérience.
                    Conformément à l'article 22 du RGPD, vous avez le droit de ne pas faire l'objet d'une décision fondée
                    exclusivement sur un traitement automatisé produisant des effets juridiques significatifs vous concernant.
                </p>
                <div class="pp-alert pp-alert--info rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        Pour exercer ce droit ou obtenir une intervention humaine dans le processus de décision,
                        contactez-nous à <a href="mailto:{{ $contactEmail }}" class="pp-link">{{ $contactEmail }}</a>.
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- --- 9. IA --- --}}
        @if($hasAi)
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="ia">
            <div class="pp-section__header">
                <span class="pp-section__number">09</span>
                <h2 class="pp-section__title">Intelligence artificielle et traitement automatisé</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Certaines fonctionnalités de notre service intègrent des technologies d'intelligence artificielle.
                    Vos données utilisées à ces fins sont pseudonymisées dans la mesure du possible.
                </p>
                <div class="pp-infobox pp-infobox--neutral rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <div>
                        Nous ne partageons pas vos données personnelles pour entraîner des modèles d'IA tiers
                        sans votre consentement explicite.
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- --- CONSERVATION --- --}}
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="conservation">
            <div class="pp-section__header">
                <span class="pp-section__number">
                    @if($cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi) 10 @else 06 @endif
                </span>
                <h2 class="pp-section__title">Durée de conservation</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Nous conservons vos données uniquement le temps nécessaire aux finalités pour lesquelles elles ont été collectées,
                    conformément aux obligations légales applicables.
                </p>

                <div class="pp-retention-grid">
                    <div class="pp-retention-item">
                        <div class="pp-retention-item__duration">
                            <span class="pp-retention-item__number">{{ $retentionPeriod }}</span>
                            <span class="pp-retention-item__unit">mois</span>
                        </div>
                        <p>Données de compte actif</p>
                    </div>
                    <div class="pp-retention-item">
                        <div class="pp-retention-item__duration">
                            <span class="pp-retention-item__number">3</span>
                            <span class="pp-retention-item__unit">ans</span>
                        </div>
                        <p>Après clôture du compte (données inactives)</p>
                    </div>
                    @if($hasPayment)
                    <div class="pp-retention-item">
                        <div class="pp-retention-item__duration">
                            <span class="pp-retention-item__number">10</span>
                            <span class="pp-retention-item__unit">ans</span>
                        </div>
                        <p>Données comptables et financières (obligation légale)</p>
                    </div>
                    @endif
                    @if($cookiesEnabled)
                    <div class="pp-retention-item">
                        <div class="pp-retention-item__duration">
                            <span class="pp-retention-item__number">13</span>
                            <span class="pp-retention-item__unit">mois max</span>
                        </div>
                        <p>Cookies analytiques (recommandation CNIL)</p>
                    </div>
                    @endif
                    <div class="pp-retention-item">
                        <div class="pp-retention-item__duration">
                            <span class="pp-retention-item__number">5</span>
                            <span class="pp-retention-item__unit">ans</span>
                        </div>
                        <p>Logs de connexion (sécurité)</p>
                    </div>
                </div>

                @if($userIsAuthenticated)
                <div class="pp-cta-block">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <div>
                        En tant qu'utilisateur connecté, vous pouvez télécharger toutes vos données ou demander leur suppression.
                        <a href="{{ $accountDataUrl }}" class="pp-btn pp-btn--secondary">Gérer mes données</a>
                    </div>
                </div>
                @endif
            </div>
        </section>

        {{-- --- VOS DROITS --- --}}
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="droits">
            <div class="pp-section__header">
                <span class="pp-section__number">
                    @if($cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi) 11 @else 07 @endif
                </span>
                <h2 class="pp-section__title">Vos droits</h2>
            </div>
            <div class="pp-section__body">

                @if($gdprRegion)
                <p>Conformément au RGPD, vous disposez des droits suivants sur vos données personnelles :</p>
                @elseif($ccpaRegion)
                <p>En tant que résident californien (CCPA), vous disposez des droits suivants :</p>
                @else
                <p>Vous disposez des droits suivants concernant vos données personnelles :</p>
                @endif

                <div class="pp-rights-grid">
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
                        <h3>Droit d'accès</h3>
                        <p>Obtenir une copie de vos données personnelles que nous détenons.</p>
                        @if($gdprRegion)<small>Art. 15 RGPD</small>@endif
                    </div>
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/></svg></div>
                        <h3>Droit de rectification</h3>
                        <p>Corriger des données inexactes ou incomplêtes vous concernant.</p>
                        @if($gdprRegion)<small>Art. 16 RGPD</small>@endif
                    </div>
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/></svg></div>
                        <h3>Droit à l'effacement</h3>
                        <p>Demander la suppression de vos données ("droit à l'oubli").</p>
                        @if($gdprRegion)<small>Art. 17 RGPD</small>@endif
                    </div>
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></div>
                        <h3>Droit à la limitation</h3>
                        <p>Restreindre le traitement de vos données dans certains cas.</p>
                        @if($gdprRegion)<small>Art. 18 RGPD</small>@endif
                    </div>
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
                        <h3>Droit à la portabilité</h3>
                        <p>Recevoir vos données dans un format structuré et lisible.</p>
                        @if($gdprRegion)<small>Art. 20 RGPD</small>@endif
                    </div>
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M5.6 5.6 18.4 18.4"/></svg></div>
                        <h3>Droit d'opposition</h3>
                        <p>Vous opposer au traitement fondé sur l'intérêt légitime.</p>
                        @if($gdprRegion)<small>Art. 21 RGPD</small>@endif
                    </div>
                    @if($hasProfiling)
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg></div>
                        <h3>Décisions automatisées</h3>
                        <p>Ne pas faire l'objet de décisions basées uniquement sur un traitement automatisé.</p>
                        @if($gdprRegion)<small>Art. 22 RGPD</small>@endif
                    </div>
                    @endif
                    @if(in_array('consent', $dataProcessingBasis))
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 9 6 6"/><path d="m15 9-6 6"/></svg></div>
                        <h3>Retrait du consentement</h3>
                        <p>Retirer votre consentement à tout moment sans justification.</p>
                        @if($gdprRegion)<small>Art. 7 RGPD</small>@endif
                    </div>
                    @endif
                    @if($ccpaRegion)
                    <div class="pp-right-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-right-card__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4Z"/><path d="M9 12l2 2 4-4"/></svg></div>
                        <h3>Non-discrimination</h3>
                        <p>Exercer vos droits CCPA sans faire l'objet d'une discrimination tarifaire.</p>
                        <small>CCPA Section 1798.125</small>
                    </div>
                    @endif
                </div>

                <div class="pp-alert pp-alert--info rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        Pour exercer vos droits, envoyez votre demande à
                        <a href="mailto:{{ $contactEmail }}" class="pp-link">{{ $contactEmail }}</a>.
                        Nous vous répondrons dans un délai maximum de <strong>30 jours</strong>.
                        Une pièce d'identité peut être demandée pour vérification.
                    </div>
                </div>

                @if($gdprRegion)
                <div class="pp-infobox pp-infobox--neutral rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <div>
                        Si vous estimez que le traitement de vos données ne respecte pas la réglementation,
                        vous avez le droit d'introduire une réclamation auprès de l'autorité de contrôle compétente.
                        En France : <strong>CNIL</strong> - <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer" class="pp-link">www.cnil.fr</a>
                    </div>
                </div>
                @elseif($ccpaRegion)
                <div class="pp-infobox pp-infobox--ccpa rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        Vous pouvez exercer vos droits CCPA via notre
                        <a href="{{ $doNotSellUrl }}" class="pp-link">formulaire "Do Not Sell My Personal Information"</a>.
                    </div>
                </div>
                @endif
            </div>
        </section>

        {{-- --- SÉCURITÉ --- --}}
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70" id="securite">
            <div class="pp-section__header">
                <span class="pp-section__number">
                    @if($cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi) 12 @else 08 @endif
                </span>
                <h2 class="pp-section__title">Sécurité des données</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger
                    vos données contre toute perte, accès non autorisé, divulgation ou altération.
                </p>
                <div class="pp-security-grid">
                    <div class="pp-security-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Chiffrement TLS/SSL</span>
                    </div>
                    <div class="pp-security-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Chiffrement au repos (AES-256)</span>
                    </div>
                    <div class="pp-security-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Sauvegardes automatiques</span>
                    </div>
                    <div class="pp-security-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Accès restreint aux données</span>
                    </div>
                    <div class="pp-security-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        <span>Audits de sécurité réguliers</span>
                    </div>
                    <div class="pp-security-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" y1="16" x2="6.01" y2="16"/><line x1="10" y1="16" x2="10.01" y2="16"/></svg>
                        <span>Formation de l'équipe</span>
                    </div>
                </div>

                @if($isProduction)
                <div class="pp-infobox pp-infobox--neutral rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        En cas de violation de données susceptible d'engendrer un risque pour vos droits et libertés,
                        nous nous engageons à vous notifier dans les <strong>72 heures</strong> conformément à l'article 33 du RGPD.
                    </div>
                </div>
                @endif
            </div>
        </section>

        {{-- --- CONTACT --- --}}
        <section class="pp-section rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70 pp-section--last" id="contact">
            <div class="pp-section__header">
                <span class="pp-section__number">
                    @if($cookiesEnabled || $analyticsEnabled || $thirdPartyEnabled || $hasPayment || $hasProfiling || $hasAi) 13 @else 09 @endif
                </span>
                <h2 class="pp-section__title">Nous contacter</h2>
            </div>
            <div class="pp-section__body">
                <p>
                    Pour toute question relative à cette politique ou pour exercer vos droits,
                    plusieurs canaux sont à votre disposition :
                </p>

                <div class="pp-contact-grid">
                    <a href="mailto:{{ $contactEmail }}" class="pp-contact-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-contact-card__icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <strong>E-mail</strong>
                            <span>{{ $contactEmail }}</span>
                        </div>
                    </a>

                    @if($dpoEmail && $dpoEmail !== $contactEmail)
                    <a href="mailto:{{ $dpoEmail }}" class="pp-contact-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                        <div class="pp-contact-card__icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <strong>DPO (Délégué Protection Données)</strong>
                            <span>{{ $dpoEmail }}</span>
                        </div>
                    </a>
                    @endif

                    @if($companyAddress)
                    <div class="pp-contact-card rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950 pp-contact-card--static">
                        <div class="pp-contact-card__icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <strong>Courrier postal</strong>
                            <span>À l'attention du DPO : {!! nl2br(e($companyAddress)) !!}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pp-alert pp-alert--success rounded-xl border border-slate-200/60 bg-white p-3 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <div>
                        Nous nous engageons à répondre à toute demande dans un délai de <strong>30 jours calendaires</strong>.
                        Pour les demandes complexes, ce délai peut être prolongé de deux mois supplémentaires avec notification.
                    </div>
                </div>
            </div>
        </section>

    </div>{{-- /.pp-content --}}
</div>{{-- /.container --}}

{{-- ------------------------------------------------------
     FOOTER DE LA PAGE
------------------------------------------------------ --}}
<div class="pp-page-footer rounded-xl border border-slate-200/60 bg-white/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900/70">
    <div class="container pp-page-footer__inner">
        <p class="pp-page-footer__text">
            Cette politique a été mise à jour le <strong>{{ \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y') }}</strong>.
            Les modifications substantielles vous seront notifiées par e-mail ou via une bannière sur le site.
        </p>
        <div class="pp-page-footer__actions">
            <button class="pp-btn pp-btn--ghost" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Imprimer
            </button>
            <a href="{{ $termsUrl }}" class="pp-btn pp-btn--ghost">
                Conditions d'utilisation
            </a>
            @if($cookiesEnabled)
            <button class="pp-btn pp-btn--ghost" onclick="openCookieManager()">
                Préférences cookies
            </button>
            @endif
        </div>
    </div>
</div>

</main>
@endsection

@push('styles')
<style>
.pp-hero,
.pp-content,
.pp-page-footer {
    color: inherit;
}

.pp-hero {
    padding: 1.25rem 0 0;
}

.pp-hero__inner,
.pp-content,
.pp-page-footer__inner {
    max-width: 1120px;
}

.pp-hero__inner,
.pp-toc,
.pp-section,
.pp-page-footer {
    border-radius: 1rem;
    border: 1px solid rgba(148, 163, 184, .18);
    background: rgba(255, 255, 255, .9);
    box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
    transition: color .2s ease, background-color .2s ease, border-color .2s ease;
}

.dark .pp-hero__inner,
.dark .pp-toc,
.dark .pp-section,
.dark .pp-page-footer {
    background: rgba(15, 23, 42, .82);
    border-color: rgba(148, 163, 184, .18);
    box-shadow: 0 1px 2px rgba(2, 6, 23, .32);
}

.pp-hero__inner {
    padding: 1.5rem;
}

.pp-hero__badge {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-bottom: 1rem;
}

.pp-badge {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .45rem .8rem;
    border-radius: 9999px;
    border: 1px solid rgba(148, 163, 184, .2);
    background: rgba(248, 250, 252, .92);
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.dark .pp-badge {
    background: rgba(30, 41, 59, .78);
    border-color: rgba(148, 163, 184, .18);
}

.pp-hero__title,
.pp-section__title,
.pp-toc__title {
    font-family: inherit;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.pp-hero__title {
    font-size: clamp(2rem, 3vw, 2.8rem);
    font-weight: 400;
    line-height: 1.05;
    margin: 0 0 1rem;
}

.pp-hero__title strong,
.pp-section__title strong {
    font-weight: 700;
}

.pp-hero__subtitle,
.pp-section__body,
.pp-card,
.pp-infobox,
.pp-alert,
.pp-right-card p,
.pp-contact-card span,
.pp-retention-item p,
.pp-cookie-type p,
.pp-legal-table,
.pp-data-grid,
.pp-rights-grid,
.pp-security-grid {
    font-family: inherit;
}

.pp-hero__subtitle {
    max-width: 72ch;
    font-size: 1rem;
    line-height: 1.75;
    color: rgba(51, 65, 85, .9);
}

.dark .pp-hero__subtitle {
    color: rgba(226, 232, 240, .88);
}

.pp-hero__meta,
.pp-page-footer__inner,
.pp-page-footer__actions,
.pp-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
}

.pp-meta-item {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .8rem 1rem;
    border-radius: .85rem;
    border: 1px solid rgba(148, 163, 184, .18);
    background: rgba(248, 250, 252, .92);
    font-size: .92rem;
}

.dark .pp-meta-item {
    background: rgba(30, 41, 59, .7);
    border-color: rgba(148, 163, 184, .16);
}

.pp-body {
    padding: 1.5rem 0 3rem;
}

.pp-content {
    display: grid;
    gap: 1.25rem;
}

.pp-toc,
.pp-section,
.pp-page-footer {
    padding: 1.5rem;
}

.pp-toc {
    margin-top: 1rem;
}

.pp-toc__title {
    font-size: .82rem;
    font-weight: 700;
    margin: 0 0 1rem;
    color: rgba(71, 85, 105, .9);
}

.dark .pp-toc__title {
    color: rgba(203, 213, 225, .84);
}

.pp-toc__list {
    columns: 2;
    gap: 1.5rem;
    margin: 0;
    padding-left: 1.1rem;
}

.pp-toc__list li {
    margin-bottom: .75rem;
    break-inside: avoid;
}

.pp-toc__list a,
.pp-link,
.pp-meta-item a,
.pp-contact-card {
    color: inherit;
    text-decoration: none;
}

.pp-toc__list a,
.pp-link,
.pp-meta-item a {
    transition: color .2s ease;
}

.pp-toc__list a:hover,
.pp-link:hover,
.pp-meta-item a:hover {
    color: #0f172a;
}

.dark .pp-toc__list a:hover,
.dark .pp-link:hover,
.dark .pp-meta-item a:hover {
    color: #f8fafc;
}

.pp-section {
    margin-bottom: 1.25rem;
}

.pp-section__header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(148, 163, 184, .16);
}

.pp-section__number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 3rem;
    height: 3rem;
    flex: 0 0 3rem;
    border-radius: 9999px;
    border: 1px solid rgba(148, 163, 184, .18);
    background: rgba(248, 250, 252, .95);
    font-size: .95rem;
    font-weight: 700;
    letter-spacing: .08em;
    color: rgba(15, 23, 42, .85);
}

.dark .pp-section__number {
    background: rgba(30, 41, 59, .75);
    color: rgba(248, 250, 252, .92);
    border-color: rgba(148, 163, 184, .16);
}

.pp-section__title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
}

.pp-section__body {
    line-height: 1.75;
    color: rgba(51, 65, 85, .92);
}

.pp-section__body > * {
    margin-top: 0;
    margin-bottom: 0;
}

.pp-section__body > * + * {
    margin-top: 1.25rem;
}

.pp-section__body p,
.pp-section__body li,
.pp-section__body td,
.pp-section__body th,
.pp-page-footer__text {
    line-height: 1.75;
}

.dark .pp-section__body {
    color: rgba(226, 232, 240, .88);
}

.pp-card,
.pp-alert,
.pp-infobox,
.pp-data-card,
.pp-cookie-type,
.pp-retention-item,
.pp-right-card,
.pp-contact-card,
.pp-cta-block,
.pp-right-item,
.pp-security-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    padding: 1.1rem 1.15rem;
    border-radius: 1rem;
    border: 1px solid rgba(148, 163, 184, .16);
    background: rgba(248, 250, 252, .94);
    box-shadow: 0 1px 2px rgba(15, 23, 42, .03);
}

.dark .pp-card,
.dark .pp-alert,
.dark .pp-infobox,
.dark .pp-data-card,
.dark .pp-cookie-type,
.dark .pp-retention-item,
.dark .pp-right-card,
.dark .pp-contact-card,
.dark .pp-cta-block,
.dark .pp-right-item,
.dark .pp-security-item {
    background: rgba(15, 23, 42, .74);
    border-color: rgba(148, 163, 184, .16);
}

.pp-card__icon,
.pp-contact-card__icon,
.pp-right-item__icon,
.pp-security-item__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.75rem;
    height: 2.75rem;
    flex: 0 0 2.75rem;
    border-radius: .85rem;
    background: rgba(226, 232, 240, .85);
    color: #0f172a;
}

.dark .pp-card__icon,
.dark .pp-contact-card__icon,
.dark .pp-right-item__icon,
.dark .pp-security-item__icon {
    background: rgba(51, 65, 85, .92);
    color: #f8fafc;
}

.pp-card + .pp-card,
.pp-alert + .pp-card,
.pp-card + .pp-alert,
.pp-contact-card + .pp-contact-card {
    margin-top: 1rem;
}

.pp-grid,
.pp-data-grid,
.pp-cookie-grid,
.pp-retention-grid,
.pp-rights-grid,
.pp-security-grid,
.pp-contact-grid,
.pp-legal-grid {
    display: grid;
    gap: 1rem;
    align-items: stretch;
}

.pp-data-grid,
.pp-cookie-grid,
.pp-rights-grid,
.pp-security-grid,
.pp-contact-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.pp-legal-grid,
.pp-retention-grid {
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}

.pp-legal-table,
.pp-legal-table th,
.pp-legal-table td {
    border-color: rgba(148, 163, 184, .18);
}

.pp-legal-table th {
    background: rgba(241, 245, 249, .92);
    text-transform: uppercase;
    letter-spacing: .05em;
    font-size: .8rem;
}

.pp-data-card,
.pp-right-card {
    flex-direction: column;
    gap: .85rem;
    height: 100%;
}

.pp-data-card h3,
.pp-right-card h3,
.pp-cookie-type strong,
.pp-contact-card strong,
.pp-card strong {
    margin: 0;
}

.pp-data-card ul,
.pp-list,
.pp-cookie-list,
.pp-page-footer__actions {
    margin: 0;
}

.pp-data-card ul,
.pp-list {
    padding-left: 1.1rem;
    display: grid;
    gap: .5rem;
}

.pp-cookie-list,
.pp-legal-grid {
    display: grid;
    gap: .75rem;
}

.pp-cookie-item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: .75rem .85rem;
    border-radius: .85rem;
    background: rgba(255, 255, 255, .72);
    border: 1px solid rgba(148, 163, 184, .14);
}

.dark .pp-cookie-item {
    background: rgba(30, 41, 59, .62);
    border-color: rgba(148, 163, 184, .14);
}

.pp-data-card__note,
.pp-alert > div,
.pp-infobox > div,
.pp-card > div:last-child,
.pp-contact-card > div:last-child {
    flex: 1 1 auto;
}

.pp-data-card__note,
.pp-right-card p,
.pp-contact-card span,
.pp-retention-item p,
.pp-cookie-type p {
    margin: 0;
}

.pp-right-card small,
.pp-contact-card small {
    display: block;
    margin-top: .35rem;
}

.pp-retention-item {
    flex-direction: column;
    gap: .85rem;
    justify-content: space-between;
    min-height: 100%;
}

.pp-retention-item__duration {
    display: flex;
    align-items: baseline;
    gap: .35rem;
}

.pp-contact-card {
    height: 100%;
}

.pp-contact-card > div:last-child {
    display: grid;
    gap: .3rem;
}

.pp-table,
.pp-legal-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    border-radius: 1rem;
}

.pp-table th,
.pp-table td,
.pp-legal-table th,
.pp-legal-table td {
    padding: .9rem 1rem;
}

.dark .pp-legal-table th {
    background: rgba(30, 41, 59, .78);
}

.pp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .55rem;
    min-height: 42px;
    padding: .7rem 1rem;
    border-radius: .85rem;
    border: 1px solid rgba(148, 163, 184, .18);
    background: rgba(255, 255, 255, .96);
    color: inherit;
    font-size: .84rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    text-decoration: none;
    transition: all .2s ease;
}

.pp-btn:hover {
    border-color: rgba(15, 23, 42, .22);
    color: #0f172a;
}

.dark .pp-btn {
    background: rgba(15, 23, 42, .92);
    border-color: rgba(148, 163, 184, .18);
}

.dark .pp-btn:hover {
    color: #f8fafc;
}

.pp-btn--secondary {
    background: #0f172a;
    border-color: #0f172a;
    color: #fff;
}

.pp-btn--secondary:hover {
    background: #1e293b;
    border-color: #1e293b;
    color: #fff;
}

.dark .pp-btn--secondary {
    background: #f8fafc;
    border-color: #f8fafc;
    color: #0f172a;
}

.dark .pp-btn--secondary:hover {
    background: #e2e8f0;
    border-color: #e2e8f0;
    color: #0f172a;
}

.pp-btn--ghost {
    background: transparent;
}

.pp-page-footer {
    margin-top: 1.5rem;
}

.pp-page-footer__text {
    margin: 0;
    flex: 1 1 320px;
    line-height: 1.7;
}

@media (max-width: 991.98px) {
    .pp-toc__list,
    .pp-data-grid,
    .pp-cookie-grid,
    .pp-rights-grid,
    .pp-security-grid,
    .pp-contact-grid {
        columns: 1;
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767.98px) {
    .pp-hero__inner,
    .pp-toc,
    .pp-section,
    .pp-page-footer {
        padding: 1rem;
        border-radius: .9rem;
    }

    .pp-section__header {
        align-items: flex-start;
    }

    .pp-hero__title {
        font-size: 1.75rem;
    }

    .pp-page-footer__actions,
    .pp-actions {
        width: 100%;
    }

    .pp-btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
function openCookieManager() {
    // Adapter selon votre CMP (Axeptio, Tarteaucitron, CookieBot, etc.)
    if (typeof window.axeptioSDK !== 'undefined') {
        window.axeptioSDK.openCookies();
    } else if (typeof tarteaucitron !== 'undefined') {
        tarteaucitron.userInterface.openPanel();
    } else {
        console.warn('[Privacy] Cookie manager not found. Integrate your CMP here.');
    }
}

// Scroll smooth pour les ancres
document.querySelectorAll('.pp-toc__list a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.pushState(null, null, this.getAttribute('href'));
        }
    });
});

// Highlight section active dans TOC au scroll
const sections = document.querySelectorAll('.pp-section[id]');
const navLinks = document.querySelectorAll('.pp-toc__list a');
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            navLinks.forEach(link => link.style.color = '');
            const active = document.querySelector(`.pp-toc__list a[href="#${entry.target.id}"]`);
            if (active) active.style.color = document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a';
        }
    });
}, { rootMargin: '-20% 0px -70% 0px' });
sections.forEach(s => observer.observe(s));
</script>
@endpush


