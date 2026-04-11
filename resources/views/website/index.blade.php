@include('website.header')
{{-- <header class="">
    <div style="background-image: url('/images/hero-6.png')" class="bg-cover bg-center h-[250px] lg:h-[630px] pt-24 lg:pt-[350px]">
        <div class="bg-green-800/80 lg:bg-green-800/90 w-fit py-2 px-3 rounded-sm md:rounded-md lg:rounded-lg transparan text-white ml-2 md:ml-16 lg:ml-40">
            <h1 class="text-lg max-w-72 md:max-w-80 lg:max-w-[550px] md:text-xl lg:text-4xl font-serif leading-5 md:leading-6 lg:leading-tight">Cabai Segar Pilihan Petani Lokal. Kualitas Pedas Terbaik Siap Kirim ke Seluruh Jawa & Bali</h1>
            <p class="text-[9px] md:text-xs lg:text-sm mt-1">Jaminan Panen Hari Ini, Dikirim Hari Ini. Segar Sampai di Dapur Anda.</p>
        </div>
        <div class="my-1 bg-red-800/80 w-fit py-1 px-3 rounded-sm  transparan text-white ml-2 md:ml-16 lg:ml-40">
            <h1 class="text-[9px] md:text-xs lg:text-sm max-w-60 md:max-w-60 lg:max-w-[460px] leading-5 md:leading-6 lg:leading-tight">Cek Harga Hari Ini</h1>
        </div>
        <div class="ml-2 md:ml-16 lg:ml-40 text-white flex gap-1 text-[9px] md:text-xs lg:text-sm">
            <div>- Jaminan Kualitas</div>
            <div>- Pengiriman Cepat</div>
            <div>- Harga Terbaik</div>
        </div>
    </div>
</header> --}}
<header class="relative bg-white overflow-hidden">
    <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 blur-3xl opacity-20 pointer-events-none">
    <div class="aspect-square w-[500px] bg-red-600 rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 lg:py-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
        
            <div class="lg:w-1/2 text-center lg:text-left">
                <span class="inline-block px-4 py-1.5 mb-6 text-sm font-semibold tracking-wide text-red-600 uppercase bg-red-50 rounded-full" data-aos="fade-left" data-aos-duration="1500">
                    Partner B2B Terpercaya
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6" data-aos="fade-left" data-aos-duration="1500">
                Suplai Cabai Segar Langsung dari <span class="text-red-600">Sumbernya.</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed mb-8" data-aos="fade-right" data-aos-duration="1500">
                    <strong class="text-gray-900">Putra Cabe</strong> adalah perusahaan yang bergerak di bidang perdagangan dan distribusi cabai segar untuk kebutuhan pasar B2B seperti pedagang besar, restoran, UMKM kuliner, dan industri makanan.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#cta" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition duration-300 shadow-lg shadow-red-200 text-center">
                        Pesan Sekarang
                    </a>
                    <a href="{{ route('katalog') }}" class="px-8 py-4 bg-white border-2 border-gray-200 hover:border-red-600 text-gray-700 hover:text-red-600 font-bold rounded-xl transition duration-300 text-center">
                        Katalog Produk
                    </a>
                </div>

                <div class="mt-10 pt-10 border-t border-gray-200 grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">100%</p>
                        <p class="text-sm text-gray-500">Cabai Segar</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">Kontinyu</p>
                        <p class="text-sm text-gray-500">Stok Aman</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">B2B</p>
                        <p class="text-sm text-gray-500">Harga Kompetitif</p>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl" data-aos="fade-left" data-aos-duration="1500">
                    <div class="swiper mySwiperbanner">
                        <div class="swiper-wrapper">
                            @foreach ($rs_banner as $banner)
                                <div class="swiper-slide ">
                                    <img src="{{ asset($banner->banner_path) }}" alt="{{ $banner->banner_title }}"  class="w-full h-[400px] lg:h-[550px] object-cover" loading="lazy"/>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-linear-to-t from-black/40 to-transparent"></div>
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl hidden md:block max-w-[240px]">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        </div>
                        <div>
                        <p class="text-sm font-bold text-gray-900">Kualitas Terjamin</p>
                        <p class="text-xs text-gray-500">Lolos kontrol kualitas ketat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<section class="bg-gray-100/60 py-10 md:py-20" data-aos="fade-up" data-aos-duration="1500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="text-center mb-7 md:mb-10 lg:mb-12">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-red-600 mb-2">KOMITMEN KAMI</h2>
            <p class="text-2xl md:text-4xl mb-2 md:mb-4 font-extrabold text-gray-900 leading-tight">Visi & Misi Putra Cabe</p>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 lg:gap-16">
            <div class="p-6 md:p-7 lg:p-8 bg-gray-900 text-white rounded-xl shadow-2xl border-t-8 border-red-600">
                <div class="flex items-center mb-3 md:mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 md:h-12 md:w-12 text-red-600 mr-2 md:mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V17.5a2.5 2.5 0 00-2.5-2.5h-1a2.5 2.5 0 01-2.5-2.5v-.5M19 4h2v2" />
                    </svg>
                    <h3 class="text-xl md:text-xl lg:text-3xl font-bold">Visi Kami</h3>
                </div>
                <blockquote class="text-base md:text-lg lg:text-xl italic border-l-4 border-red-600 pl-4 text-gray-200">
                    "{{ $visi->pref_value }}"
                </blockquote>
            </div>
            <div class="p-8 bg-white text-gray-800 rounded-xl shadow-2xl border-t-8 border-red-600">
                <div class="flex items-center mb-3 md:mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 md:h-12 md:w-12 text-red-600 mr-2 md:mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.192-2.058-.512-3.004z" />
                    </svg>
                    <h3 class="text-xl md:text-xl lg:text-3xl font-bold">Misi Kami</h3>
                </div>
                <ul class="space-y-0 md:space-y-2 lg:space-y-3 text-lg text-gray-700">
                    @foreach ($rs_misi as $misi)
                        <li class="flex items-start">
                            <span class="text-red-600 font-bold mr-3 text-2xl">•</span>
                            <p class="text-base md:text-lg lg:text-xl">{{ $misi->misi_value }}</p>
                        </li>
                    @endforeach
                    
                </ul>
            </div>
        </div>
    </div>
</section>
<section id="keunggulan" class="py-10 md:py-20" data-aos="fade-down" data-aos-duration="1500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-4xl font-serif font-bold mb-2 md:mb-4 text-sage">Mengapa Memilih Kami ?</h2>
        <p class="text-sm md:text-lg text-gray-600 max-w-3xl mx-auto mb-5 md:mb-14">Komitmen kami pada kualitas, transparansi, dan dukungan terhadap petani lokal.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-10">
            @foreach ($rs_why as $why)
                <div class="p-8 bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-sage">
                    <div class="text-3xl md:text-5xl mb-2 md:mb-4">
                        <img width="80px" height="120px" src="{{ asset($why->why_path ."/". $why->why_image) }}" alt="{{ $why->why_image }}" class="mx-auto" loading="lazy">
                    </div>
                    <h3 class="text-base md:text-xl font-semibold mb-1 md:mb-3 text-gray-800">{{ $why->why_title }}</h3>
                    <p class="text-sm md:text-base text-gray-600">{{ $why->why_desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section id="katalog" class="py-10 md:py-20 bg-gray-100/60" data-aos="fade-up" data-aos-duration="1500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-center w-full text-2xl md:text-4xl font-serif font-bold mb-2 md:mb-4 text-sage">Produk Cabai Segar</h2>
        <p class="text-center text-sm md:text-lg text-gray-600 max-w-3xl mx-auto mb-5 md:mb-10">Barbagai Produk Pilihan dengan Kualitas Maksimal.</p>
        
        {{-- <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5 lg:gap-8"> --}}
        <div class="swiper mySwiperProduk">
            <div class="swiper-wrapper">
                @foreach ($rs_produk as $produk)
                        <div class="swiper-slide bg-white rounded-md md:rounded-xl shadow-lg overflow-hidden border border-gray-100 transition duration-300 hover:shadow-2xl">
                            <img class="w-full h-48 object-cover" src="{{ $produk->produk_path . "/" . $produk->produk_image }}" alt="{{ $produk->produk_nama }}" loading="lazy"/>
                            <div class="p-5">
                                {{-- <span class="inline-block bg-black/10 text-sage text-xs px-3 py-1 rounded-full font-medium mb-2"></span> --}}
                                <h3 class="text-sm md:text-base lg:text-lg font-semibold text-gray-800 mb-1">{{ $produk->produk_nama }}</h3>
                                {{-- <p class="text-base md:text-lg lg:text-xl font-bold mb-2 md:mb-4">Rp {{ number_format($produk->produk_harga, 0, ', ', '.') }} / Kg</p> --}}
                                <span class="flex">
                                    @for ($i = 0; $i < $produk->produk_rating; $i++)
                                        <i class="fa fa-star text-yellow-500 mr-1 mb-3"></i> 
                                    @endfor
                                </span>
                                <a href="{{ route('katalogDetail', $produk->slug) }}" class="w-full block text-sm md:text-base text-center bg-red-600 text-white py-1 md:py-2 rounded-lg hover:bg-red-500 transition">
                                    Lihat Selengkapnya
                                </a>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('katalog') }}" class="text-base lg:text-lg font-semibold text-sage hover:text-terracotta transition border-b border-sage hover:border-terracotta">
                Lihat Lebih Banyak Produk &rarr;
            </a>
        </div>
    </div>
</section>
<section id="testimoni" class="py-10 md:py-20" data-aos="fade-up" data-aos-duration="1500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-center w-full text-2xl md:text-4xl font-serif font-bold mb-4 md:mb-10 text-sage">Kata Mereka Tentang Kami</h2>
        {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-8"> --}}
        <div class="swiper mySwiperTesti">
            <div class="swiper-wrapper">
                @foreach ($rs_test as $test)
                    <div class="swiper-slide">
                        <blockquote class="bg-white border border-t-gray-100 border-r-gray-100 border-b-gray-100 p-6 rounded-xl shadow-lg border-l-4 border-terracotta text-left">
                            <p class="text-sm md:text-base italic text-gray-700 mb-4">"{{ $test->desc }}"</p>
                            <footer class="text-xs md:text-sm lg:text-base font-semibold text-gray-800">- {{ $test->person }}</footer>
                            <p class="text-xs md:text-sm lg:text-base text-sage">{{ $test->person_desc }}</p>
                        </blockquote>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<section id="galeri" class="py-10 md:py-20 bg-gray-100/60" data-aos="fade-up" data-aos-duration="1500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-center w-full text-2xl md:text-4xl font-serif font-bold mb-6 md:mb-14 text-sage">Galeri Foto Kami</h2>
        {{-- <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5 lg:gap-8"> --}}
        <div class="swiper mySwiperGalery">
            <div class="swiper-wrapper">
                @foreach ($rs_galery as $galery)
                    <div class="swiper-slide">
                        <a href="{{ asset($galery->image_path . "/" . $galery->image_name) }}" data-fancybox="gallery" data-caption="{{ $galery->deskripsi }}">
                            <img class="w-full h-48 object-cover rounded-lg shadow-md hover:scale-[1.02] transition duration-300" src="{{ asset($galery->image_path . "/" . $galery->image_name) }}" alt="{{ $galery->deskripsi }}" loading="lazy">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<section id="faq" class="py-10 md:py-20 bg-white" data-aos="fade-up" data-aos-duration="1500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-4xl font-serif font-bold mb-3 md:mb-8 text-sage">FAQ</h2>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-5">
            @foreach ($rs_faq as $faq)
                <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-white shadow-sm">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">{{ $faq->title }}</h3>
                    <p class="text-sm md:text-base text-gray-600 mt-1">{{ $faq->desc }}</p>
                </div>
            @endforeach
        </div>
        {{-- <div>
            <h2 class="text-2xl md:text-4xl font-serif font-bold mb-3 md:mb-8 text-sage">Informasi Pengiriman</h2>
            <div class="p-6 rounded-lg border border-gray-200 shadow-xl">
                <h3 class="text-base md:text-xl font-semibold mb-2 md:mb-4 text-terracotta">Area Layanan Utama:</h3>
                <ul class="text-sm md:text-base list-disc list-inside space-x-1 md:space-y-2 text-gray-700 ml-4">
                    <li>Jabodetabek (Setiap Hari)</li>
                    <li>Bandung (Senin & Kamis)</li>
                    <li>Yogyakarta & Semarang (Selasa & Jumat)</li>
                </ul>
                <p class="text-xs md:text-sm text-gray-500 mt-4">Pemesanan sebelum jam 15.00 akan dikirimkan H+1 atau H+2 tergantung area.</p>
            </div>
        </div> --}}
    </div>
</section>
<section id="cta" class="py-20 bg-gray-100/60" data-aos="fade-up" data-aos-duration="1500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-4xl font-serif font-bold mb-4">{{ $cta_title->pref_value }}</h2>
        <p class="text-sm md:text-base lg:text-lg mb-10 opacity-90">{{ $cta_desc->pref_value }}</p>
        <a href="https://api.whatsapp.com/send?phone=+{{ $no_wa->pref_value }}&text=Halo Putra Cabe, Bisa meminta informasi lebih lanjut?" target="blank" class="bg-[#24cd3d] text-white px-6 py-4 rounded-full text-base md:text-lg font-bold shadow-2xl hover:bg-opacity-90 transition duration-300 transform hover:scale-105">
            Hubungi Kami (Pesan via WhatsApp)
        </a>
    </div>
</section>
@section('javascriptWebsite')
<script>

    var swiper = new Swiper(".mySwiperbanner", {
        navigation: {
            nextEl: ".prod-right",
            prevEl: ".prod-left",
        },
        autoplay: {
            delay: 3500,
        },
        slidesPerView: 1,
    });

    var swiper = new Swiper(".mySwiperProduk", {
        navigation: {
            nextEl: ".prod-right",
            prevEl: ".prod-left",
        },
        autoplay: {
            delay: 3500,
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 20
            },
            769: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            415: {
                slidesPerView: 2,
                spaceBetween: 20
            },
        }
    });

    var swiper = new Swiper(".mySwiperTesti", {
        navigation: {
            nextEl: ".prod-right",
            prevEl: ".prod-left",
        },
        autoplay: {
            delay: 3000,
        },
        breakpoints: {
            1024: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            769: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            415: {
                slidesPerView: 1,
                spaceBetween: 20
            },
        }
    });

    const swiperGalery = new Swiper('.mySwiperGalery', {
        // slidesPerView: 4,
        spaceBetween: 20,
        loop: true,
        speed: 6000,

        autoplay: {
            delay: 0,
            disableOnInteraction: false,
        },

        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 20
            },
            769: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            415: {
                slidesPerView: 2,
                spaceBetween: 20
            },
        }
    });
</script>
@endsection
@include('website.footer')
