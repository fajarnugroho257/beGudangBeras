<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Artikel;
use App\Models\website\Banner;
use App\Models\website\Faq;
use App\Models\website\Galery;
use App\Models\website\Kategori;
use App\Models\website\Order;
use App\Models\website\Pref;
use App\Models\website\Produk;
use App\Models\website\Produk_image;
use App\Models\website\ProdukDetail;
use App\Models\website\Testimoni;
use App\Models\website\VisiMisi;
use App\Models\website\Why;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'home';
        $data['title_meta'] = 'Putracabe | Supplier Cabai Segar Terpercaya - Kirim Seluruh Indonesia';
        $data['meta_description'] = 'Putracabe adalah pusat jual beli berbagai jenis cabai segar berkualitas tinggi. Kami melayani pengiriman cabai merah, rawit, dan keriting ke seluruh wilayah Indonesia dengan harga kompetitif.';
        // 
        $data['visi'] = Pref::where('pref_name', 'pref_misi')->first();
        $data['rs_misi'] = VisiMisi::get();
        $data['rs_why'] = Why::get();
        $data['rs_test'] = Testimoni::get();
        $data['rs_faq'] = Faq::get();
        $data['cta_title'] = Pref::where('pref_name', 'cta_title')->first();
        $data['cta_desc'] = Pref::where('pref_name', 'cta_desc')->first();
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        $data['rs_galery'] = Galery::get();
        $data['rs_produk'] = Produk::get();
        $data['rs_banner'] = Banner::orderBy('banner_urut')->get();
        // dd($data);
        return view('website.index', $data);
    }

    public function katalog()
    {
        $data['title'] = 'katalog';
        $data['title_meta'] = 'Katalog Produk Cabai Segar: Rawit, Keriting & Cabai Merah | Putracabe';
        $data['meta_description'] = 'Jelajahi koleksi cabai terbaik dari Putracabe. Tersedia cabai rawit merah, cabai keriting, hingga cabai besar kualitas premium. Stok melimpah untuk kebutuhan grosir dan eceran';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        // 
        $data['rs_produk'] = Produk::get();
        return view('website.katalog', $data);
    }

    public function katalog_detail(string $slug)
    {
        $detail = Produk::where('slug', $slug)->first();
        if (empty($detail)) {
            return redirect()->route('home');
        }
        $data['title'] = 'katalog';
        $data['title_meta'] = 'Produk ' . $detail->produk_nama . '| Putracabe';
        $data['meta_description'] = 'Produk ' . $detail->produk_short_desc . '| Putracabe';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        $data['rs_produk'] = Produk::where('id', '<>', $detail->id)->get();
        $data['detail'] = $detail;
        $data['rs_image'] = Produk_image::where('produk_id', $detail->id)->get();
        $data['rs_desc'] = ProdukDetail::where('detail_jenis', 'desc')->where('produk_id', $detail->id)->get();
        $data['rs_detail'] = ProdukDetail::where('detail_jenis', 'detail')->where('produk_id', $detail->id)->get();
        return view('website.katalog_detail', $data);
    }

    public function about()
    {
        $data['title'] = 'about';
        $data['title_meta'] = 'Tentang Putracabe | Partner Bisnis Cabai Berpengalaman di Indonesia';
        $data['meta_description'] = 'Kenali lebih dekat Putracabe, dedikasi kami dalam menyediakan hasil tani cabai terbaik. Kami berkomitmen menghubungkan petani dengan pasar melalui distribusi yang cepat dan aman';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        // 
        $data['aboutme'] = Pref::where('pref_name', 'aboutme')->first();
        $data['pref_image'] = Pref::where('pref_name', 'pref_image')->first();
        return view('website.about', $data);
    }

    
    public function order()
    {
        $data['title'] = 'order';
        $data['title_meta'] = 'Cara Pemesanan Cabai di Putracabe - Mudah, Cepat & Aman';
        $data['meta_description'] = 'Bingung cara memesan cabai di Putracabe? Ikuti langkah-langkah mudah order cabai secara online di sini. Kami menjamin proses transaksi yang transparan dan pengiriman tepat waktu.';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        // 
        $data['rs_order'] = Order::orderBy('urut')->get();
        return view('website.order', $data);
    }

    public function kontak()
    {
        $data['title'] = 'kontak';
        $data['title_meta'] = 'Hubungi Putracabe | Layanan Pelanggan & Informasi Harga Cabai';
        $data['meta_description'] = 'Butuh info harga cabai hari ini atau ingin melakukan pemesanan? Hubungi tim Putracabe sekarang melalui WhatsApp atau telepon. Kami siap melayani kebutuhan pengiriman Anda';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        // 
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        $data['no_wa_zero'] = Pref::where('pref_name', 'no_wa_zero')->first();
        return view('website.kontak', $data);
    }

    public function artikel()
    {
        $data['title'] = 'artikel';
        $data['title_meta'] = 'Artikel & Tips Seputar Cabai | Update Harga & Pertanian - Putracabe';
        $data['meta_description'] = 'Dapatkan informasi terbaru seputar harga cabai, tips menjaga kesegaran cabai, hingga tren pasar pertanian hanya di blog resmi Putracabe. Sumber info cabai terpercaya';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        // 
        $data['rs_artikel'] = Artikel::with('kategori', 'author')->orderBy('artikel_date', 'desc')->paginate(10);
        $data['rs_kategori'] = DB::select("SELECT a.id, a.name, a.color, res.total
                                FROM artikel_kategori a 
                                LEFT JOIN (
                                    SELECT b.kategori_id, COUNT(*) 'total'
                                    FROM artikel b GROUP BY b.kategori_id
                                ) res ON a.id = res.kategori_id
                                ORDER BY res.total DESC
                                ");
        $data['rs_most'] = DB::select("SELECT a.*, DATEDIFF(CURRENT_DATE, artikel_date) AS selisih_hari FROM artikel a 
                                WHERE MONTH(a.artikel_date) = MONTH(CURRENT_DATE) 
                                AND YEAR(a.artikel_date) = YEAR(CURRENT_DATE)
                                ORDER BY a.artikel_views DESC 
                                LIMIT 3 ");
        // dd($data);
        return view('website.artikel', $data);
    }

    public function artikel_detail(string $slug)
    {
        $data['title'] = 'artikel';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        $detail = Artikel::with('kategori', 'author')->where('artikel_slug', $slug)->first();
        if (empty($detail)) {
            return redirect()->route('artikel');
        }
        $data['title_meta'] = 'Artikel | ' . $detail->artikel_title ;
        $data['meta_description'] = $detail->artikel_title;
        // tambah view
        $data['detail'] = $detail;
        $view = $detail->artikel_views + 1;
        $detail->artikel_views = $view;
        $detail->save();
        // 
        $data['rs_kategori'] = DB::select("SELECT a.id, a.name, a.color, res.total
                                FROM artikel_kategori a 
                                LEFT JOIN (
                                    SELECT b.kategori_id, COUNT(*) 'total'
                                    FROM artikel b GROUP BY b.kategori_id
                                ) res ON a.id = res.kategori_id
                                ORDER BY res.total DESC
                                ");
        $data['rs_populer'] = DB::select("SELECT a.*, DATEDIFF(CURRENT_DATE, artikel_date) AS selisih_hari FROM artikel a 
                            WHERE MONTH(a.artikel_date) = MONTH(CURRENT_DATE) 
                            AND YEAR(a.artikel_date) = YEAR(CURRENT_DATE)
                            AND a.id <> ?
                            ORDER BY a.artikel_views DESC 
                            LIMIT 5", [$detail->id]);
        // dd($data);
        return view('website.artikel_detail', $data);
    }

    public function cari_artikel(Request $request)
    { 
        $data['title'] = 'artikel';
        $data['title_meta'] = 'Artikel & Tips Seputar Cabai | Update Harga & Pertanian - Putracabe';
        $data['meta_description'] = 'Dapatkan informasi terbaru seputar harga cabai, tips menjaga kesegaran cabai, hingga tren pasar pertanian hanya di blog resmi Putracabe. Sumber info cabai terpercaya';
        $data['no_wa'] = Pref::where('pref_name', 'no_wa')->first();
        // 
        $keyword = $request->keyword;
        if (empty($keyword)) {
            return redirect()->route('artikel');
        }
        $type = $request->type;
        if ($type == 'reset') {
            return redirect()->route('artikel');
        }
        // 
        $data['rs_artikel'] = Artikel::with('kategori', 'author')
                            ->where(function($query) use ($keyword) {
                                $query->where('artikel_title', 'LIKE', '%' . $keyword . '%')->orWhere('artikel_desc', 'LIKE', '%' . $keyword . '%');
                            })
                            ->orderBy('artikel_date', 'desc')
                            ->paginate(10);
        $data['rs_kategori'] = DB::select("SELECT a.id, a.name, a.color, res.total
                                FROM artikel_kategori a 
                                LEFT JOIN (
                                    SELECT b.kategori_id, COUNT(*) 'total'
                                    FROM artikel b GROUP BY b.kategori_id
                                ) res ON a.id = res.kategori_id
                                ORDER BY res.total DESC
                                ");
        $data['keyword'] = $keyword;
        $data['rs_most'] = DB::select("SELECT a.*, DATEDIFF(CURRENT_DATE, artikel_date) AS selisih_hari FROM artikel a 
                                WHERE MONTH(a.artikel_date) = MONTH(CURRENT_DATE) 
                                AND YEAR(a.artikel_date) = YEAR(CURRENT_DATE)
                                ORDER BY a.artikel_views DESC 
                                LIMIT 3 ");
        // dd($data);
        return view('website.artikel', $data);
    }
    
    public function generate()
    {
        $sitemap = Sitemap::create()
        ->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
        ->add(Url::create('/produk')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
        ->add(Url::create('/tentang-kami')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
        ->add(Url::create('/cara-pesan')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
        ->add(Url::create('/kontak-kami')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
        ->add(Url::create('/artikel')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        
        // Gunakan render untuk mengecek apakah error 'hint path' hilang
        return $sitemap->render('xml'); 
    }

}
