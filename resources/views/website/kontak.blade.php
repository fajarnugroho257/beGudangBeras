@include('website.header')
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 md:pt-10 text-xs md:text-sm " aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="text-red-600 font-medium" aria-current="page">Kontak Kami</li>
        </ol>
    </nav>
    <section id="kontak-kami" class="py-5 md:pb-16 bg-white" data-aos="fade-down" data-aos-duration="1500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-4xl font-serif font-bold mb-4 md:mb-8 text-sage">Hubungi Kami</h2>
            <p class="">Kami siap melayani Anda. Jangan ragu untuk menghubungi kami melalui telepon, WhatsApp, atau media sosial.</p>
            <div class="flex justify-center mt-5 md:mt-10 text-white gap-2 text-sm">
                <div class="flex gap-2 items-center bg-[#24cd3d] px-2 py-1 rounded w-fit ">
                    <img class="w-5 h-5" src="{{ asset('images/wa.png') }}" alt="whatsapp putra cabe" loading="lazy">
                    <a href="https://api.whatsapp.com/send?phone=+{{ $no_wa->pref_value }}&text=Halo Putra Cabe, Bisa meminta informasi lebih lanjut?" target="blank" class="hover:text-colorWood transition delay-100 duration-300 ease-in-out">{{ $no_wa_zero->pref_value }}</a>
                </div>
                <div class="flex gap-2 items-center bg-red-600 px-2 py-1 rounded w-fit ">
                    <img class="w-5 h-5" src="{{ asset('images/instagram.png') }}" alt="putracabe77" loading="lazy">
                    <a href="https://www.instagram.com/putracabe77" target="blank" class="hover:text-colorWood transition delay-100 duration-300 ease-in-out">@putracabe77</a>
                </div>
                <div class="flex gap-2 items-center bg-gray-600 px-2 py-1 rounded w-fit ">
                    <img class="w-5 h-5" src="{{ asset('images/tiktok-icon.png') }}" alt="putracabe77" loading="lazy">
                    <a href="https://www.tiktok.com/@putracabe77?_r=1&_t=ZS-92qMI5FPNB6" target="blank" class="hover:text-colorWood transition delay-100 duration-300 ease-in-out">@putracabe77</a>
                </div>
            </div>
        </div>
    </section>
@include('website.footer')