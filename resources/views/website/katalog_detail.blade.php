@include('website.header')
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 md:pt-10 text-xs md:text-sm " aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="flex items-center">
                <a href="{{ route('katalog') }}" class="text-gray-500 hover:text-red-600">Katalog Produk</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="text-red-600 font-medium" aria-current="page">{{ $detail->produk_nama }}</li>
        </ol>
    </nav>
    <section id="tentang" class="py-5 md:pb-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <section class="bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                    
                    <div class="flex flex-col gap-4">
                        <div class="rounded-lg overflow-hidden border border-gray-200">
                            <img src="{{ asset($detail->produk_path . '/' . $detail->produk_image) }}" alt="Cabai Merah Besar" class="w-full h-auto object-cover max-h-[450px]">
                        </div>
                        <div class="flex gap-2 overflow-x-scroll w-full">
                            @foreach ($rs_image as $image)
                                <a href="{{ asset($image->path . '/' . $image->image) }}" data-fancybox="{{ $detail->produk_nama }}" data-caption="{{ $image->image }}">
                                    <div class="border p-1 rounded-md cursor-pointer hover:border-red-600 w-40 h-28">
                                        <img src="{{ asset($image->path . '/' . $image->image) }}" alt="Thumbnail 1" class="w-full h-full rounded-sm">
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">{{ $detail->produk_nama }}</h1>
                        <div class="flex items-center mb-4 text-sm text-yellow-500">
                            <span class="flex">
                                @for ($i = 0; $i < $detail->produk_rating; $i++)
                                    <i class="fa fa-star"></i>
                                @endfor
                            </span>
                            <span class="ml-2 text-gray-500">({{ $detail->produk_rating }})</span>
                        </div>
                        {{-- <p class="text-4xl font-bold text-red-600 mb-6">Rp {{ number_format($detail->produk_harga, 0, ', ', '.') }}<span class="text-xl text-gray-500 font-normal"> / Kg</span></p> --}}

                        <p class="text-gray-700 mb-6 leading-relaxed">
                            {{ $detail->produk_short_desc }}
                        </p>
                        <hr class="my-6">
                        {{-- <div class="flex items-center space-x-4 mb-6">
                            <label for="quantity" class="text-lg font-semibold text-gray-900">Kuantitas:</label>
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <button class="px-3 py-2 text-gray-600 hover:bg-gray-100">-</button>
                                <input type="number" id="quantity" value="1" min="1" class="w-16 text-center border-x border-gray-300 focus:outline-none" aria-label="Kuantitas">
                                <button class="px-3 py-2 text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                            <span class="text-sm text-gray-500">(Stok Tersedia: 500 Kg)</span>
                        </div> --}}
                        {{-- <div class="flex flex-col sm:flex-row gap-4 mb-8">
                            <button class="flex-1 bg-red-600 text-white font-bold py-3 px-6 rounded-full hover:bg-red-700 transition duration-300 shadow-md">
                                <i class="f fa-cart"></i>
                                Masukkan ke Keranjang
                            </button>
                            <button class="sm:w-1/3 bg-green-500 text-white font-bold py-3 px-6 rounded-full hover:bg-green-600 transition duration-300 flex items-center justify-center shadow-md">
                                <i class="f fa-cart"></i>
                                Pesan via WhatsApp
                            </button>
                        </div> --}}
                        <div class="space-y-3 p-4 bg-gray-100 rounded-lg">
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-700">**Jaminan Kualitas:** Segar dan Bebas Busuk.</p>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2h-1v2H9V8h3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-gray-700">**Grosir:** Harga spesial.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
            <section class="mt-12">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button id="tab-deskripsi" class="tab-button border-b-2 border-red-600 text-red-600 font-semibold py-3 px-1 inline-flex items-center text-lg focus:outline-none" onclick="openTab('deskripsi')">
                            Deskripsi
                        </button>
                        <button id="tab-info" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium py-3 px-1 inline-flex items-center text-lg focus:outline-none" onclick="openTab('info')">
                            Informasi Tambahan
                        </button>
                    </nav>
                </div>

                <div id="content-deskripsi" class="tab-content pt-6">
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Deskripsi Produk</h3>
                    <p class="text-gray-700 mb-4">
                        {{ $detail->produk_desc }}
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-700">
                        @foreach ($rs_desc as $desc)
                            <li>{{ $desc->detail_desc }}</li>
                        @endforeach
                    </ul>
                </div>

                <div id="content-info" class="tab-content pt-6 hidden">
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Spesifikasi Detail</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($rs_detail as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium bg-gray-50 w-1/3">{{ $detail->detail_title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $detail->detail_desc }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section class="mt-12">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">🔥 Produk Terkait</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($rs_produk as $produk)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                            <a href="{{ route('katalogDetail', $produk->slug) }}" class="block">
                                <img src="{{ asset($produk->produk_path ."/". $produk->produk_image) }}" alt="Cabai Rawit Merah" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 truncate">{{ $produk->produk_nama }}</h4>
                                    {{-- <p class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($produk->produk_harga, 0, ', ', '.') }} / Kg</p> --}}
                                    <span class="flex">
                                        @for ($i = 0; $i < $produk->produk_rating; $i++)
                                            <i class="fa fa-star text-yellow-500 mr-1"></i> 
                                        @endfor
                                    </span>
                                    <span class="text-sm text-gray-500">Stok: Tersedia</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
    @section('javascriptWebsite')
    <script>
        function openTab(tabName) {
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.add('hidden');
            });
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(button => {
                button.classList.remove('border-red-600', 'text-red-600', 'font-semibold');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'font-medium');
            });
            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.add('border-red-600', 'text-red-600', 'font-semibold');
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'font-medium');
        }
        document.addEventListener('DOMContentLoaded', () => {
            openTab('deskripsi');
        });
    </script>
    @endsection
@include('website.footer')