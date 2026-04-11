<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\menu\headingAppController;
use App\Http\Controllers\menu\menuController;
use App\Http\Controllers\menu\roleMenuController;
use App\Http\Controllers\menu\rolePenggunaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\website\AboutMeController;
use App\Http\Controllers\website\ArtikelController;
use App\Http\Controllers\website\AuthorController;
use App\Http\Controllers\website\BannerController;
use App\Http\Controllers\website\FaqController;
use App\Http\Controllers\website\GaleryController;
use App\Http\Controllers\website\KategoriController;
use App\Http\Controllers\website\OrderController;
use App\Http\Controllers\website\PreferenceController;
use App\Http\Controllers\website\ProdukController;
use App\Http\Controllers\website\TestimoniController;
use App\Http\Controllers\website\VisiMisiController;
use App\Http\Controllers\website\WebsiteController;
use App\Http\Controllers\website\WhyChooseMeController;
use App\Models\website\Artikel;
use App\Models\website\Produk;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// sitemaps
// Route::get('/generate-sitemap', function () {
//     $sitemap = Sitemap::create()
//         ->add(Url::create('/')
//             ->setPriority(1.0)
//             ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
//         ->add(Url::create('/produk')
//             ->setPriority(0.8)
//             ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
//         ->add(Url::create('/tentang-kami')
//             ->setPriority(0.8)
//             ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
//         ->add(Url::create('/cara-pesan')
//             ->setPriority(0.8)
//             ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
//         ->add(Url::create('/kontak-kami')
//             ->setPriority(0.8)
//             ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
//         ->add(Url::create('/artikel')
//             ->setPriority(0.8)
//             ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

//     // Tambahkan halaman dinamis dari database
//     $produks = Produk::all();
//     foreach ($produks as $produk) {
//         $sitemap->add(
//             Url::create("/produk/{$produk->slug}")
//                 ->setLastModificationDate($produk->updated_at)
//                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
//                 ->setPriority(0.7)
//         );
//     }

//     $artikels = Artikel::all();
//     foreach ($artikels as $artikel) {
//         $sitemap->add(
//             Url::create("/artikel/{$artikel->artikel_slug}")
//                 ->setLastModificationDate($artikel->updated_at)
//                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
//                 ->setPriority(0.7)
//         );
//     }

//     // $sitemap->writeToFile(public_path('sitemap.xml'));

//     return 'Sitemap berhasil dibuat!';
// });

// website
Route::get('/', [WebsiteController::class, 'index'])->name('home');
Route::get('/test', [WebsiteController::class, 'generate'])->name('generate');
Route::get('/produk', [WebsiteController::class, 'katalog'])->name('katalog');
Route::get('/produk/{slug}', [WebsiteController::class, 'katalog_detail'])->name('katalogDetail');
Route::get('/tentang-kami', [WebsiteController::class, 'about'])->name('about');
Route::get('/cara-pesan', [WebsiteController::class, 'order'])->name('order');
Route::get('/kontak-kami', [WebsiteController::class, 'kontak'])->name('kontak');
Route::get('/artikel', [WebsiteController::class, 'artikel'])->name('artikel');
Route::get('/artikel-search', [WebsiteController::class, 'cari_artikel'])->name('cariArtikel');
Route::get('/artikel/{slug}', [WebsiteController::class, 'artikel_detail'])->name('artikel_detail');

// login
Route::middleware(['guest'])->group(function () {
    Route::get('/login-web', [LoginController::class, 'index'])->name('login');
    Route::post('/login-process', [LoginController::class, 'loginProcess'])->name('login-process');
});

Route::middleware(['auth'])->group(function () {
    // dahsboard
    Route::middleware(['hasRole.page:dashboard'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
    // logout
    Route::get('/log-out', [LoginController::class, 'logOut'])->name('logOut');
    // heading
    Route::middleware(['hasRole.page:headingApp'])->group(function () {
        Route::get('/heading-aplikasi', [headingAppController::class, 'index'])->name('headingApp');
        Route::get('/add-heading-aplikasi', [headingAppController::class, 'create'])->name('tambahHeadingApp');
        Route::post('/process-add-heading-aplikasi', [headingAppController::class, 'store'])->name('aksiTambahHeadingApp');
        Route::get('/update-heading-aplikasi/{app_heading_id}', [headingAppController::class, 'edit'])->name('updateHeadingApp');
        Route::post('/aksi-update-heading-aplikasi/{app_heading_id}', [headingAppController::class, 'update'])->name('aksiUpdateHeadingApp');
        Route::get('/process-delete-heading-aplikasi/{app_heading_id}', [headingAppController::class, 'destroy'])->name('deleteHeadingApp');
    });
    // menu
    Route::middleware(['hasRole.page:menuApp'])->group(function () {
        Route::get('/menu-aplikasi', [menuController::class, 'index'])->name('menuApp');
        Route::get('/add-menu-aplikasi', [menuController::class, 'create'])->name('tambahMenuApp');
        Route::post('/process-add-menu-aplikasi', [menuController::class, 'store'])->name('aksiTambahMenuApp');
        Route::get('/update-menu-aplikasi/{menu_id}', [menuController::class, 'edit'])->name('updateMenuApp');
        Route::post('/aksi-update-menu-aplikasi/{menu_id}', [menuController::class, 'update'])->name('aksiUpdateMenuApp');
        Route::get('/process-delete-menu-aplikasi/{menu_id}', [menuController::class, 'destroy'])->name('deleteMenuApp');
    });
    // role
    Route::middleware(['hasRole.page:rolePengguna'])->group(function () {
        Route::get('/role-pengguna', [rolePenggunaController::class, 'index'])->name('rolePengguna');
        Route::get('/add-role-pengguna', [rolePenggunaController::class, 'create'])->name('tambahRolePengguna');
        Route::post('/process-add-role-pengguna', [rolePenggunaController::class, 'store'])->name('aksiTambahRolePengguna');
        Route::get('/update-role-pengguna/{role_id}', [rolePenggunaController::class, 'edit'])->name('updateRolePengguna');
        Route::post('/aksi-update-role-pengguna/{role_id}', [rolePenggunaController::class, 'update'])->name('aksiUpdateRolePengguna');
    });
    // menu
    Route::middleware(['hasRole.page:roleMenu'])->group(function () {
        Route::get('/role-menu', [roleMenuController::class, 'index'])->name('roleMenu');
        Route::get('/list-data-role-menu/{role_id}', [roleMenuController::class, 'listDataRoleMenu'])->name('listDataRoleMenu');
        Route::post('/add-role-menu', [roleMenuController::class, 'tambahRoleMenu'])->name('tambahRoleMenu');
    });
    // User
    Route::middleware(['hasRole.page:dataUser'])->group(function () {
        Route::get('/data-user', [UserController::class, 'index'])->name('dataUser');
        Route::get('/add-data-user', [UserController::class, 'create'])->name('tambahUser');
        Route::post('/process-add-data-user', [UserController::class, 'store'])->name('aksiTambahUser');
        Route::get('/update-data-user/{user_id}', [UserController::class, 'edit'])->name('UpdateUser');
        Route::post('/process-update-data-user/{user_id}', [UserController::class, 'update'])->name('aksiUpdateUser');
        Route::get('/process-delete-data-user/{user_id}', [UserController::class, 'destroy'])->name('deleteUser');
    });

    /* YOUR ROUTE APLICATION */

    // BANNER
    Route::middleware(['hasRole.page:banner'])->group(function () {
        Route::get('/banner', [BannerController::class, 'index'])->name('banner');
        Route::get('/add-banner', [BannerController::class, 'create'])->name('addBanner');
        Route::post('/process-add-banner', [BannerController::class, 'store'])->name('processAddBanner');
        Route::get('/update-banner/{id}', [BannerController::class, 'edit'])->name('editBanner');
        Route::post('/process-update-banner/{id}', [BannerController::class, 'update'])->name('processEditBanner');
        Route::get('/process-delete-banner/{id}', [BannerController::class, 'destroy'])->name('processDeleteBanner');
    });

    // why choose me
    Route::middleware(['hasRole.page:whyChooseMe'])->group(function () {
        Route::get('/why-choose-me', [WhyChooseMeController::class, 'index'])->name('whyChooseMe');
        Route::get('/add-why-choose-me', [WhyChooseMeController::class, 'create'])->name('addWhyChooseMe');
        Route::post('/process-add-why-choose-me', [WhyChooseMeController::class, 'store'])->name('processAddWhyChooseMe');
        Route::get('/edit-why-choose-me/{id}', [WhyChooseMeController::class, 'edit'])->name('editWhyChooseMe');
        Route::post('/process-edit-why-choose-me/{id}', [WhyChooseMeController::class, 'update'])->name('processEditWhyChooseMe');
        Route::get('/process-delete-why-choose-me/{id}', [WhyChooseMeController::class, 'destroy'])->name('processDeleteWhyChooseMe');
    });

    // testimoni
    Route::middleware(['hasRole.page:testimoni'])->group(function () {
        Route::get('/testimoni', [TestimoniController::class, 'index'])->name('testimoni');
        Route::get('/add-testimoni', [TestimoniController::class, 'create'])->name('addTestimoni');
        Route::post('/process-add-testimoni', [TestimoniController::class, 'store'])->name('processAddTestimoni');
        Route::get('/edit-testimoni/{id}', [TestimoniController::class, 'edit'])->name('editTestimoni');
        Route::post('/process-edit-testimoni/{id}', [TestimoniController::class, 'update'])->name('processEditTestimoni');
        Route::get('/process-delete-testimoni/{id}', [TestimoniController::class, 'destroy'])->name('processDeleteTestimoni');
    });

    // faq
    Route::middleware(['hasRole.page:faq'])->group(function () {
        Route::get('/faq', [FaqController::class, 'index'])->name('faq');
        Route::get('/add-faq', [FaqController::class, 'create'])->name('addFaq');
        Route::post('/process-add-faq', [FaqController::class, 'store'])->name('processAddFaq');
        Route::get('/edit-faq/{id}', [FaqController::class, 'edit'])->name('editFaq');
        Route::post('/process-edit-faq/{id}', [FaqController::class, 'update'])->name('processEditFaq');
        Route::get('/process-delete-faq/{id}', [FaqController::class, 'destroy'])->name('processDeleteFaq');
    });

    // galery
    Route::middleware(['hasRole.page:galery'])->group(function () {
        Route::get('/data-galery', [GaleryController::class, 'index'])->name('galery');
        Route::post('/add-proses-galery', [GaleryController::class, 'add_proses_galery'])->name('addProsesGalery');
        Route::get('/delete-proses-galery/{id}', [GaleryController::class, 'delete_proses_galery'])->name('deleteGalery');
    });

    // tentang kami
    Route::middleware(['hasRole.page:tentangKami'])->group(function () {
        Route::get('/about-me', [AboutMeController::class, 'index'])->name('tentangKami');
        Route::post('/process-edit-about-me/{id}', [AboutMeController::class, 'update'])->name('processEditTentangKami');
        Route::get('/process-delete-image/{id}', [AboutMeController::class, 'destroy'])->name('hapusImage');
    });

    // Pref
    Route::middleware(['hasRole.page:preference'])->group(function () {
        Route::get('/preference', [PreferenceController::class, 'index'])->name('preference');
        Route::get('/edit-preference/{id}', [PreferenceController::class, 'edit'])->name('editPreference');
        Route::post('/process-edit-preference/{id}', [PreferenceController::class, 'update'])->name('processEditPreference');
    });

    // cara order
    Route::middleware(['hasRole.page:caraOrder'])->group(function () {
        Route::get('/cara-order', [OrderController::class, 'index'])->name('caraOrder');
        Route::get('/add-cara-order', [OrderController::class, 'create'])->name('addOrder');
        Route::post('/process-add-cara-order', [OrderController::class, 'store'])->name('processAddOrder');
        Route::get('/edit-cara-order/{id}', [OrderController::class, 'edit'])->name('editOrder');
        Route::post('/process-edit-cara-order/{id}', [OrderController::class, 'update'])->name('processEditOrder');
        Route::get('/process-delete-cara-order/{id}', [OrderController::class, 'destroy'])->name('processDeleteOrder');
    });

    Route::middleware(['hasRole.page:visiMisi'])->group(function () {
        Route::get('/visi-misi', [VisiMisiController::class, 'index'])->name('visiMisi');
        Route::get('/add-visi-misi', [VisiMisiController::class, 'create'])->name('addvisiMisi');
        Route::post('/process-add-visi-misi', [VisiMisiController::class, 'store'])->name('processAddvisiMisi');
    });

    Route::middleware(['hasRole.page:katalogProduk'])->group(function () {
        Route::get('/katalog-produk', [ProdukController::class, 'index'])->name('katalogProduk');
        Route::get('/add-katalog-produk', [ProdukController::class, 'create'])->name('addkatalogProduk');
        Route::post('/process-add-katalog-produk', [ProdukController::class, 'store'])->name('processAddkatalogProduk');
        Route::get('/edit-katalog-produk/{slug}', [ProdukController::class, 'edit'])->name('editkatalogProduk');
        Route::post('/process-edit-katalog-produk/{slug}', [ProdukController::class, 'update'])->name('processEditkatalogProduk');
        Route::get('/process-delete-produk-image/{slug}/{id}', [ProdukController::class, 'delete_produk_image'])->name('processDeleteProdukImage');
        Route::get('/process-delete-katalog-produk/{slug}', [ProdukController::class, 'destroy'])->name('processDeletekatalogProduk');
    });

    Route::middleware(['hasRole.page:artikelData'])->group(function () {
        Route::get('/artikel-data', [ArtikelController::class, 'index'])->name('artikelData');
        Route::get('/add-artikel-data', [ArtikelController::class, 'create'])->name('addArtikelData');
        Route::post('/process-add-artikel-data', [ArtikelController::class, 'store'])->name('processAddArtikelData');
        Route::get('/edit-artikel-data/{slug}', [ArtikelController::class, 'edit'])->name('editArtikelData');
        Route::post('/process-edit-artikel-data/{slug}', [ArtikelController::class, 'update'])->name('processEditArtikelData');
        Route::get('/process-delete-artikel-data/{slug}', [ArtikelController::class, 'destroy'])->name('processDeleteArtikelData');
    });

    Route::middleware(['hasRole.page:artikelKategori'])->group(function () {
        Route::get('/artikel-kategori', [KategoriController::class, 'index'])->name('artikelKategori');
        Route::post('/process-add-artikel-kategori', [KategoriController::class, 'store'])->name('processAddArtikelKategori');
        Route::post('/process-edit-kategori', [KategoriController::class, 'update'])->name('processEditArtikelKategori');
        Route::get('/process-delete-kategori/{slug}', [KategoriController::class, 'destroy'])->name('processDeleteArtikelKategori');

        Route::get('/get-detail-kategori', [KategoriController::class, 'show'])->name('getDetailKategori');

    });

    Route::middleware(['hasRole.page:artikelAuthor'])->group(function () {
        Route::get('/artikel-author', [AuthorController::class, 'index'])->name('artikelAuthor');
        Route::post('/process-add-artikel-author', [AuthorController::class, 'store'])->name('processAddArtikelAuthor');
        Route::post('/process-edit-author', [AuthorController::class, 'update'])->name('processEditArtikelAuthor');
        Route::get('/process-delete-author/{slug}', [AuthorController::class, 'destroy'])->name('processDeleteArtikelAuthor');

        Route::get('/get-detail-author', [AuthorController::class, 'show'])->name('getDetailAuthor');
    });

    /* END YOUR ROUTE APLICATION */
});
