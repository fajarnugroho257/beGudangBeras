@include('website.header')
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 md:pt-10 text-xs md:text-sm " aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="#" class="text-gray-500 hover:text-red-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="text-red-600 font-medium" aria-current="page">Artikel</li>
        </ol>
    </nav>
    <section id="katalog" class="py-5 md:pb-16 bg-white" data-aos="fade-left" data-aos-duration="1500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-4xl font-serif font-bold mb-4 md:mb-8 text-center">🌶️ Semua Artikel Terbaru</h1>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-8">
                    @foreach ($rs_artikel as $artikel)
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden transition duration-300 card-shadow">
                            <a href="{{ route('artikel_detail', $artikel->artikel_slug) }}" class="block md:flex">
                                <div class="md:w-1/3">
                                    <img src="{{ asset($artikel->artikel_path . "/" . $artikel->artikel_name) }}" alt="{{ $artikel->artikel_title }}" class="w-full h-48 md:h-full object-cover" loading="lazy">
                                </div>
                                <div class="p-6 md:w-2/3">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-{{ $artikel->kategori->color }}-600">{{ $artikel->kategori->name }}</span>
                                    <h2 class="text-base md:text-lh lg:text-xl font-bold text-gray-900 mt-1 mb-2 group-hover:text-red-700">{{ $artikel->artikel_title }}</h2>
                                    <p class="text-sm md:text-base text-gray-600 mb-4">
                                        {!! \Illuminate\Support\Str::words(strip_tags($artikel->artikel_desc), 25, '...') !!}
                                    </p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="text-xs md:text-sm mr-3">Oleh: {{ $artikel->author->author_name }}</span>
                                        <span class="text-xs md:text-sm"><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($artikel->artikel_date)->locale('id')->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span><i class="fa fa-eye"></i> {{ $artikel->artikel_views }} Membaca</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                    <div class="mt-4 font-poppins">
                        {{ $rs_artikel->links('partials.my-pagination') }}
                    </div>
                </div>
                
                <div class="space-y-8">
                    
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg border-t-4 border-red-600">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Cari Artikel</h3>
                        <div class="relative">
                            <form action="{{ route('cariArtikel') }}" method="get">
                                @method('get')
                                <input type="search" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Cari tips atau resep..." class="text-sm md:text-base w-full py-2 pl-4 pr-10 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-gray-700">
                                <button type="submit" name="type" value="sumbit" class="absolute right-0 top-0 mt-2 mr-3 text-gray-500 hover:text-red-600">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg> --}}
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="submit" name="type" value="reset" class="absolute right-7 top-0 mt-2 mr-3 text-gray-500 hover:text-red-600">
                                    <i class="fa fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-red-600">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Kategori</h3>
                        <ul class="space-y-1 md:space-y-2">
                            @foreach ($rs_kategori as $kategori)
                                <li>
                                    <a href="#" class="flex justify-between items-center text-gray-700 hover:text-red-600 font-medium">
                                        <span class="text-sm md:text-base">{{ $kategori->name }}</span>
                                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">{{ $kategori->total ?? 0 }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-red-600">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Populer Bulan Ini</h3>
                        <ul class="space-y-4">
                            @foreach ($rs_most as $key => $most)
                                <li class="flex items-start group">
                                    <span class="text-xl md:text-2xl font-bold text-red-600 mr-3">{{ $key + 1 }}</span>
                                    <a href="{{ route('artikel_detail', $most->artikel_slug) }}" class="block">
                                        <p class="text-sm md:text-base font-semibold text-gray-900 group-hover:text-red-700 transition duration-150">{{ $most->artikel_title }}</p>
                                        <span class="text-xs text-gray-500">{{ $most->selisih_hari }} Hari lalu - {{ $most->artikel_views }}x dilihat</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section>
@include('website.footer')