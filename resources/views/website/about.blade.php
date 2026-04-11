@include('website.header')
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 md:pt-10 text-xs md:text-sm " aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="text-red-600 font-medium" aria-current="page">Tentang Kami</li>
        </ol>
    </nav>
    <section id="tentang" class="py-5 md:pb-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="lg:order-2" data-aos="fade-right" data-aos-duration="1500">
                <img src="{{ "image/aboutme/" . $pref_image->pref_value }}" alt="Petani di kebun" class="rounded-2xl shadow-2xl w-full h-96 object-cover border-4 border-sage/50" loading="lazy"/>
            </div>
            <div class="lg:order-1" data-aos="fade-left" data-aos-duration="1500">
                <h2 class="text-4xl font-serif font-bold mb-6 text-sage">Putra Cabe</h2>
                {!! $aboutme->pref_value !!}
            </div>
        </div>
    </section>
@include('website.footer')