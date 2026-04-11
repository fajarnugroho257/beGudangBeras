@include('website.header')
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 md:pt-10 text-xs md:text-sm " aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="#" class="text-gray-500 hover:text-red-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="flex items-center">
                <a href="{{ route('artikel') }}" class="text-gray-500 hover:text-red-600">Artikel</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="text-red-600 font-medium" aria-current="page">{{ $detail->artikel_title }}</li>
        </ol>
    </nav>
    <section id="katalog" class="py-5 md:pb-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <div class="lg:col-span-2">
                    <article class="bg-white p-6 md:p-10 rounded-2xl shadow-sm border border-gray-100">
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full mb-4 uppercase">{{ $detail->kategori->name }}</span>
                        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                            {{ $detail->artikel_title }}
                        </h1>

                        <div class="flex items-center space-x-4 mb-8 pb-8 border-b border-gray-100">
                            <img src="{{ asset($detail->author->author_path . "/" . $detail->author->author_image) }}" alt="Author" class="w-12 h-12 rounded-full border-2 border-red-500" loading="lazy">
                            <div>
                                <p class="text-gray-900 font-bold">{{ $detail->author->author_name }}</p>
                                <p class="text-sm text-gray-500">Diterbitkan pada {{ \Carbon\Carbon::parse($detail->artikel_date)->locale('id')->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }} • {{ $detail->artikel_views }} Membaca</p>
                            </div>
                        </div>

                        <figure class="mb-10">
                            <img src="{{ asset($detail->artikel_path . "/" . $detail->artikel_name) }}" alt="{{ $detail->artikel_title }}" class="w-full rounded-2xl shadow-lg" loading="lazy">
                            {{-- <figcaption class="text-center text-sm text-gray-500 mt-4 italic">Tanaman cabai merah besar di lahan mitra PutraCabe.</figcaption> --}}
                        </figure>

                        <div class="article-content text-lg text-gray-700 leading-relaxed space-y-6">
                            {!! $detail->artikel_desc !!}
                            {{-- <p>
                                Menanam cabai merah besar bukan sekadar menabur benih dan menyiramnya. Dibutuhkan teknik khusus, mulai dari pemilihan bibit unggul hingga pemeliharaan tanah agar hasil panen melimpah. Cabai yang berkualitas tinggi biasanya memiliki ciri warna merah merata dan tekstur yang padat.
                            </p> --}}
                            
                            {{-- <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Pemilihan Benih Berkualitas</h2> --}}
                            {{-- <p>
                                Langkah pertama yang paling krusial adalah memastikan benih yang Anda gunakan memiliki daya tumbuh di atas 80%. Di <strong>PutraCabe</strong>, kami menyarankan petani untuk melakukan perendaman benih terlebih dahulu untuk melihat mana yang layak tanam.
                            </p> --}}

                            {{-- <blockquote class="bg-red-50 border-l-4 border-red-600 p-6 italic text-gray-800 rounded-r-lg my-8">
                                "Kunci sukses panen cabai terletak pada 40% pemilihan benih dan 60% perawatan rutin pada masa vegetatif."
                            </blockquote> --}}

                            {{-- <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Pengolahan Lahan dan Pupuk Organik</h2>
                            <p>
                                Cabai menyukai tanah yang gembur dan kaya akan unsur hara. Gunakan pupuk kandang yang sudah matang sempurna untuk menghindari jamur akar. Jangan lupa untuk memasang mulsa plastik untuk menjaga kelembapan tanah.
                            </p>
                            
                            <ul class="list-disc pl-6 space-y-3">
                                <li>Pastikan pH tanah berada di angka 5.5 - 6.5.</li>
                                <li>Gunakan jarak tanam 50cm x 60cm agar sirkulasi udara lancar.</li>
                                <li>Lakukan penyiraman rutin pada pagi atau sore hari saja.</li>
                            </ul> --}}
                        </div>

                        <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
                            <div class="flex flex-wrap gap-2">
                                {{-- <a href="#" class="bg-gray-100 px-3 py-1 text-sm rounded hover:bg-gray-200">#Pertanian</a>
                                <a href="#" class="bg-gray-100 px-3 py-1 text-sm rounded hover:bg-gray-200">#CabaiMerah</a>
                                <a href="#" class="bg-gray-100 px-3 py-1 text-sm rounded hover:bg-gray-200">#TipsPanen</a> --}}
                            </div>
                            <div class="flex items-center space-x-4">
                                {{-- <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Share:</span>
                                <a href="#" class="text-blue-600 hover:scale-110 transition">FB</a>
                                <a href="#" class="text-green-500 hover:scale-110 transition">WA</a>
                                <a href="#" class="text-blue-400 hover:scale-110 transition">TW</a> --}}
                            </div>
                        </div>
                    </article>
                    {{-- <div class="grid grid-cols-2 gap-4 mt-8">
                        <a href="#" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-red-600 transition group">
                            <p class="text-xs text-gray-500 uppercase">Artikel Sebelumnya</p>
                            <p class="font-bold text-gray-900 group-hover:text-red-600 line-clamp-1">Manfaat Capsaicin Bagi Tubuh</p>
                        </a>
                        <a href="#" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-red-600 transition group text-right">
                            <p class="text-xs text-gray-500 uppercase">Artikel Selanjutnya</p>
                            <p class="font-bold text-gray-900 group-hover:text-red-600 line-clamp-1">Prediksi Harga Cabai Akhir Tahun</p>
                        </a>
                    </div> --}}
                </div>

                <aside class="space-y-10">
                    {{-- <div class="bg-red-600 p-8 rounded-2xl text-white shadow-xl relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold mb-2">Update Mingguan</h3>
                            <p class="text-red-100 text-sm mb-4">Dapatkan tips pertanian dan info harga cabai langsung di email Anda.</p>
                            <input type="email" placeholder="Email Anda" class="w-full px-4 py-2 rounded-lg text-gray-900 mb-3 focus:outline-none">
                            <button class="w-full bg-white text-red-600 font-bold py-2 rounded-lg hover:bg-red-50 transition">Berlangganan</button>
                        </div>
                        <div class="absolute -right-10 -bottom-10 opacity-20">
                            <svg width="200" height="200" fill="white" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14h2v2h-2v-2zm0-10h2v8h-2V6z"/></svg>
                        </div>
                    </div> --}}

                    <div>
                        <h3 class="text-xl font-extrabold mb-6 border-b-2 border-red-600 pb-2 inline-block">Populer</h3>
                        <div class="space-y-6">
                            @foreach ($rs_populer as $populer)
                                <a href="{{ route('artikel_detail', $populer->artikel_slug) }}">
                                    <div class="flex space-x-4 group cursor-pointer">
                                        <img src="{{ asset($populer->artikel_path . "/" . $populer->artikel_name )}}" class="w-20 h-20 rounded-lg object-cover" loading="lazy">
                                        <div>
                                            <h4 class="font-bold text-sm group-hover:text-red-600 transition">{{ $populer->artikel_title }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">12 Jan 2025</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            
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
                </aside>

            </div>
        </div>
    </section>
@include('website.footer')