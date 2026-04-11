<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title_meta }}</title>
        <!-- Google & Browser -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Browser modern -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <!-- Apple -->
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <!-- Android / PWA -->
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">
        <!-- Google tag (gtag.js) -->
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PJRZRWTS');</script>
        <!-- End Google Tag Manager -->
        {{-- verification --}}
        
        <meta name="author" content="https://www.instagram.com/putracabe77" />
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title_meta }}">
        <meta name="twitter:description" content="{{ $meta_description }}">
        <meta name="twitter:image" content="https://putracabe.com/images/putraCabe.png">
        <meta property="og:see_also" content="https://www.instagram.com/putracabe77" />
        <link rel="canonical" href="{{ url()->current() }}">
        <meta property="og:type" content="place" />
        <meta property="og:title" content="{{ $title_meta }}" />
        <meta property="og:description" content="{{ $meta_description }}" />
        <meta property="og:image" content="https://putracabe.com/images/putraCabe.png" />
        <meta property="og:url" content="https://putracabe.com">
        <meta property="place:location:latitude" content="-7.467738" />
        <meta property="place:location:longitude" content="110.194969" />
        <meta property="place:location:street-address" content="Jalan raya bandongan, magelang, desa trasan, dusun paingan, kecamatan bandongan" />
        <meta property="place:location:locality" content="Kabupaten Magelang" />
        <meta property="place:location:region" content="Jawa Tengah" />
        <meta property="place:location:postal-code" content="56151" />
        <meta property="place:location:country" content="indonesia" />
        <meta property="og:locale" content="id_ID" />
        <meta property="og:site_name" content="Puta Cabe Perusahaan Perdagangan Cabai" />
        <script type="application/ld+json">
            {
            "@context": "https://schema.org",
            "@type": "Place",
            "name": "Puta Cabe Perusahaan Perdagangan Cabai",
            "description": "{{ $meta_description }}",
            "image": "https://putracabe.com/images/putraCabe.png",
            "url": "https://putracabe.com",
            "telephone": "+{{ $no_wa }}",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Jalan raya bandongan, magelang, desa trasan, dusun paingan, kecamatan bandongan",
                "addressLocality": "Kabupaten Magelang",
                "addressRegion": "Jawa Tengah",
                "postalCode": "56151",
                "addressCountry": "indonesia"
            },
            "geo": {
                "@type": "GeoCoordinates",
                "latitude": -7.467738,
                "longitude": 110.194969
            },
            "hasMap": "https://www.google.com/maps?q=-7.467738,110.194969"
            }
        </script>
        <meta name="keywords" content="putra cabe, putracabe, supplier cabai segar, distributor cabai magelang, jual cabai merah keriting, grosir cabai indonesia, suplier cabe restoran, agen cabai murah, cabai segar tangan pertama, pasokan cabai B2B, distributor sayuran magelang">
        <meta name="description" content="{{ $meta_description }}">
        <meta property="og:description" content="{{ $meta_description }}">
        <meta name="news_keywords" content="putra cabe, putracabe, supplier cabai segar, distributor cabai magelang, jual cabai merah keriting, grosir cabai indonesia, suplier cabe restoran, agen cabai murah, cabai segar tangan pertama, pasokan cabai B2B, distributor sayuran magelang">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        {{-- @vite('resources/css/app.css') --}}
        <link rel="stylesheet" href="{{ asset('dist/css/build.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        {{-- fancy --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
        {{-- aous --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </head>
    <body class="font-poppins">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PJRZRWTS"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <nav id="main-navbar" class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center">
                        <img src="{{ asset('/images/putraCabe.png') }}" alt="PutraCabe" class="w-20 md:w-28">
                    </div>
                    
                    <div class="hidden md:flex space-x-6 text-lg font-medium items-center">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-sage transition @if ($title == 'home') font-bold @endif">Home</a>
                        <a href="{{ route('katalog') }}" class="text-gray-600 hover:text-sage transition @if ($title == 'katalog') font-bold @endif">Katalog Produk</a>
                        <a href="{{ route('about') }}" class="text-gray-600 hover:text-sage transition @if ($title == 'about') font-bold @endif">Tentang Kami</a>
                        <a href="{{ route('order') }}" class="text-gray-600 hover:text-sage transition @if ($title == 'order') font-bold @endif">Cara Order</a>
                        <a href="{{ route('kontak') }}" class="text-gray-600 hover:text-sage transition @if ($title == 'kontak') font-bold @endif">Kontak</a>
                        <a href="{{ route('artikel') }}" class="text-gray-600 hover:text-sage transition @if ($title == 'artikel') font-bold @endif">Artikel</a>
                        {{-- <a href="#cta" class="flex gap-1 bg-red-600 text-white px-5 py-2 ml-4 rounded-lg font-semibold text-sm hover:bg-opacity-90 transition">Pesan Sekarang <img class="w-5 h-5" src="{{ asset('images/wa.png') }}" alt=""></a> --}}
                    </div>
                    <div class="flex md:hidden items-center">
                        <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-sage" aria-controls="mobile-menu-dropdown" aria-expanded="false">
                            <span class="sr-only">Buka menu utama</span>
                            <svg id="icon-open" class="h-6 w-6 block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="icon-close" class="h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div id="mobile-menu-dropdown" class="md:hidden hidden absolute top-20 left-0 w-full bg-white z-40 origin-top transform opacity-0 clip-path-initial transition-all duration-500 ease-in-out border-b-gray-300">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-100">
                    <a href="{{ route('home') }}" class="mobile-nav-item block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sage">Home</a>
                    <a href="{{ route('katalog') }}" class="mobile-nav-item block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sage">Katalog Produk</a>
                    <a href="{{ route('about') }}" class="mobile-nav-item block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sage">Tentang Kami</a>
                    <a href="{{ route('order') }}" class="mobile-nav-item block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sage">Cara Order</a>
                    <a href="{{ route('kontak') }}" class="mobile-nav-item block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sage">Kontak</a>
                    <a href="{{ route('artikel') }}" class="mobile-nav-item block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sage">Artikel</a>
                    <a href="#cta" class="mobile-nav-item block w-full text-center mt-3 bg-red-600 text-white px-3 py-2 rounded-lg font-semibold text-base hover:bg-opacity-90 transition">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </nav>