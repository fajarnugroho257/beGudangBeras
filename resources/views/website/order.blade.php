@include('website.header')
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 md:pt-10 text-xs md:text-sm " aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="text-red-600 font-medium" aria-current="page">Cara Pesan</li>
        </ol>
    </nav>
    <section id="cara-order" class="py-5 md:pb-16 bg-white" data-aos="fade-left" data-aos-duration="1500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl md:text-4xl font-serif font-bold mb-4 md:mb-8 text-center">Cara Pemesanan yang Mudah</h2>
            <ol class="grid grid-cols-1 sm:grid-cols-3 gap-1 md:gap-8 text-left">
                @foreach ($rs_order as $order)
                    <li class="relative pb-4 md:pb-8 sm:pb-0">
                        <div class="h-full w-0.5 bg-sage/50 absolute left-4 top-4 sm:hidden"></div>
                        <div class="flex items-start">
                            <div class="shrink-0 w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center font-bold mr-4 z-10">{{ $order->urut }}</div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ $order->title }}</h3>
                                <p class="text-gray-600">{{ $order->desc }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@include('website.footer')