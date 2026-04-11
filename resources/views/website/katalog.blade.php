@include('website.header')
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 md:pt-10 text-xs md:text-sm " aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="text-red-600 font-medium" aria-current="page">Katalog Produk</li>
        </ol>
    </nav>
    <section id="katalog" class="py-5 md:pb-16 bg-white" data-aos="fade-right" data-aos-duration="1500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl md:text-4xl font-serif font-bold mb-4 md:mb-8 text-center">Produk Kami</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5 lg:gap-8">
                 @foreach ($rs_produk as $produk)
                    <div class="bg-white rounded-md md:rounded-xl shadow-lg overflow-hidden border border-gray-100 transition duration-300 hover:shadow-2xl">
                        <img class="w-full h-48 object-cover" src="{{ asset($produk->produk_path ."/". $produk->produk_image) }}" alt="{{ $produk->produk_nama }}" loading="lazy"/>
                        <div class="p-5">
                            {{-- <span class="inline-block bg-black/10 text-sage text-xs px-3 py-1 rounded-full font-medium mb-2">Sayuran Daun</span> --}}
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
    </section>
@include('website.footer')