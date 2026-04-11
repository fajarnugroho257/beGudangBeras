    {{-- <div class="px-5 p-5 md:pb-5 md:pt-14 bg-gray-800 text-white md:flex md:justify-between md:px-[175px] md:gap-7" style="background: #FFF,url(images/bg-footer.png); background-size: cover; background-attachment: fixed;background-position: center;background-repeat: no-repeat;" > --}}
        <div class="px-5 p-5 md:pb-5 md:pt-14 bg-gray-800 text-white md:px-[175px] md:gap-7" >
            <div class="grid grid-cols-1 md:grid-cols-3 mb-10 w-full">
                <div class="">
                    <div class="mb-3 md:justify-start">
                        <h4 class="text-3xl font-semibold font-serif">PutraCabe</h4>
                    </div>
                    @php
                        $no_wa = App\Models\website\Pref::where('pref_name', 'no_wa')->first();
                        $no_wa_zero = App\Models\website\Pref::where('pref_name', 'no_wa_zero')->first();
                        $alamat = App\Models\website\Pref::where('pref_name', 'alamat')->first();
                    @endphp
                    <div class="grid text-white gap-2 text-sm">
                        <div class="flex gap-2 items-center">
                            <img class="w-6 h-6" src="{{ asset('images/alamat-icon.png') }}" alt="alamat suplier putra cabe" loading="lazy">
                            <a href="javascript:;" class="hover:text-colorWood transition delay-100 duration-300 ease-in-out">{{ $alamat->pref_value }}</a>
                        </div>
                        <div class="flex gap-2 items-center">
                            <img class="w-5 h-5" src="{{ asset('images/wa.png') }}" alt="whatsapp suplier putra cabe" loading="lazy">
                            <a href="https://api.whatsapp.com/send?phone=+{{ $no_wa->pref_value }}&text=Halo Putra Cabe, Bisa meminta informasi lebih lanjut?" target="blank" class="hover:text-colorWood transition delay-100 duration-300 ease-in-out">{{ $no_wa_zero->pref_value }}</a>
                        </div>
                        <div class="flex gap-2 items-center">
                            <img class="w-5 h-5" src="{{ asset('images/instagram.png') }}" alt="instagram suplier putra cabe" loading="lazy">
                            <a href="https://www.instagram.com/putracabe77" target="blank" class="hover:text-colorWood transition delay-100 duration-300 ease-in-out">@putracabe77</a>
                        </div>
                        <div class="flex gap-2 items-center">
                            <img class="w-5 h-5" src="{{ asset('images/tiktok-icon.png') }}" alt="tiktok suplier putra cabe" loading="lazy">
                            <a href="https://www.tiktok.com/@putracabe77?_r=1&_t=ZS-92qMI5FPNB6" target="blank" class="hover:text-colorWood transition delay-100 duration-300 ease-in-out">@putracabe77</a>
                        </div>
                    </div>
                </div>
                <div class="mt-8 md:mt-0">
                    <h3 class="font-semibold text-base mb-3"><span class="border-b-2 border-white">Maps</span></h3>
                    <iframe class="w-full md:h-60 y-2"
                        src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3955.9774846538553!2d110.19239427500183!3d-7.467737692543872!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwMjgnMDMuOSJTIDExMMKwMTEnNDEuOSJF!5e0!3m2!1sid!2sid!4v1766397904125!5m2!1sid!2sid"
                        loading="lazy"
                        width="700" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                {{-- <div class="mt-8 md:mt-0  text-[16px] md:text-start md:flex md:justify-center">
                    <div class="footer">
                        <h3 class="font-semibold text-base mb-3"><span class="border-b-2 border-white">Katalog Kami</span></h3>
                        <div class="grid gap-y-2 font-normal">
                            <a href="{{ route('katalog') }}" class="text-sm hover:text-colorWood transition delay-100 duration-300 ease-in-out"><i class="fa fa-chevron-right text-xs"></i> Semua Produk</a>
                        </div>
                    </div>
                </div> --}}
                <div class="mt-8 md:mt-0 text-[16px] md:text-start md:flex md:justify-center">
                    <div class="footer">
                        <h3 class="font-semibold text-base mb-3"><span class="border-b-2 border-white">Quick Link</span></h3>
                        <div class="grid gap-y-2 font-normal">
                            <a href="{{ route('home') }}" class="text-sm hover:text-colorWood transition delay-100 duration-300 ease-in-out"><i class="fa fa-chevron-right text-xs"></i> Home</a>
                            <a href="{{ route('katalog') }}" class="text-sm hover:text-colorWood transition delay-100 duration-300 ease-in-out"><i class="fa fa-chevron-right text-xs"></i> Katalog Produk</a>
                            <a href="{{ route('about') }}" class="text-sm hover:text-colorWood transition delay-100 duration-300 ease-in-out"><i class="fa fa-chevron-right text-xs"></i> Tentang Kami</a>
                            <a href="{{ route('order') }}" class="text-sm hover:text-colorWood transition delay-100 duration-300 ease-in-out"><i class="fa fa-chevron-right text-xs"></i> Cara Order</a>
                            <a href="{{ route('kontak') }}" class="text-sm hover:text-colorWood transition delay-100 duration-300 ease-in-out"><i class="fa fa-chevron-right text-xs"></i> Kontak</a>
                            <a href="{{ route('artikel') }}" class="text-sm hover:text-colorWood transition delay-100 duration-300 ease-in-out"><i class="fa fa-chevron-right text-xs"></i> Artikel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="phone">
            <a href="https://api.whatsapp.com/send?phone=+{{ $no_wa->pref_value }}&text=Halo Putra Cabe, Bisa meminta informasi lebih lanjut?"
                target="blank"
                class="hover:-translate-y-3 right-0 duration-300 transition ease-in-out delay-150 flex items-center gap-2 fixed cursor-pointer shadow-md z-100 bottom-5 md:bottom-8 p-3 bg-[#24cd3d] hover:bg[#6BCC7AFF] font-poppins rounded-bl-2xl rounded-tl-2xl text-white text-sm">
                <img class="w-5 md:w-7" src="{{ asset('images/wa.png') }}" alt="">
                <p>Hubungi Kami</p>
            </a>
        </div>
        <div class="phone hidden" id="backToTopBtn">
            <div
                class="h-7 w-7 md:h-14 md:w-14 hover:-translate-y-3 duration-300 transition ease-in-out delay-150 flex items-center fixed cursor-pointer shadow-md z-100 bottom-20 md:bottom-24 right-2 md:right-10 bg-red-600 rounded-full text-white">
                <i class="mx-auto text-sm md:text-lg fa fa-arrow-up"></i>
            </div>
        </div>
        {{-- aos --}}
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init();
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    </body>
</html>
@yield('javascriptWebsite')
<script>
$(document).ready(function() {
    const $menuButton = $('#mobile-menu-button');
    const $mobileMenu = $('#mobile-menu-dropdown');
    const $iconOpen = $('#icon-open');
    const $iconClose = $('#icon-close');
    const $navItems = $('.mobile-nav-item');
    
    // Harus sesuai dengan duration-500 di class CSS
    const DURATION = 500; 

    function toggleIcons(isOpen) {
        if (isOpen) {
            $iconOpen.addClass('hidden').removeClass('block');
            $iconClose.removeClass('hidden').addClass('block');
            $menuButton.attr('aria-expanded', 'true');
        } else {
            $iconOpen.removeClass('hidden').addClass('block');
            $iconClose.addClass('hidden').removeClass('block');
            $menuButton.attr('aria-expanded', 'false');
        }
    }

    // Event listener untuk tombol menu mobile
    $menuButton.on('click', function() {
        if ($mobileMenu.hasClass('hidden')) {
            // --- BUKA MENU ---
            $mobileMenu.removeClass('hidden'); 
            
            // Memberi waktu browser untuk merender sebelum memulai transisi
            setTimeout(() => {
                $mobileMenu.removeClass('opacity-0 clip-path-initial')
                           .addClass('opacity-100 clip-path-final');
                toggleIcons(true);
            }, 10); 
            
        } else {
            // --- TUTUP MENU ---
            $mobileMenu.removeClass('opacity-100 clip-path-final')
                       .addClass('opacity-0 clip-path-initial');
            
            // Setelah transisi selesai (DURATION ms), sembunyikan div sepenuhnya
            setTimeout(() => {
                $mobileMenu.addClass('hidden');
            }, DURATION);
            
            toggleIcons(false);
        }
    });

    // Tutup menu saat link di menu mobile diklik
    $navItems.on('click', function() {
        // Pemicu transisi keluar
        $mobileMenu.removeClass('opacity-100 clip-path-final')
                   .addClass('opacity-0 clip-path-initial');
            
        // Setelah transisi selesai, sembunyikan div
        setTimeout(() => {
            $mobileMenu.addClass('hidden');
        }, DURATION);
        
        // Pastikan ikon kembali ke hamburger
        toggleIcons(false);
    });

    // Fungsi untuk menampilkan atau menyembunyikan tombol saat scroll
    $(window).scroll(function() {
        if ($(this).scrollTop() > 40) {
            $('#backToTopBtn').fadeIn(); // Tampilkan tombol
        } else {
            $('#backToTopBtn').fadeOut(); // Sembunyikan tombol
        }
    });

    // Fungsi untuk menggulir kembali ke atas ketika tombol diklik
    $('#backToTopBtn').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 'slow'); // Smooth scroll ke atas
        return false;
    });
    
});
</script>