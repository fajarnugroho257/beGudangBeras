<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Suplier;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\PembelianData;
use App\Models\StokBarang;
use App\Models\Pengiriman;
use App\Models\PengirimanData;
use App\Models\Karyawan;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Suppliers
        $suplier1 = Suplier::create([
            'suplier_nama' => 'CV Beras Jaya',
            'alamat' => 'Jl. Ahmad Yani No. 45, Surabaya',
            'no_hp' => '081234567890',
        ]);

        $suplier2 = Suplier::create([
            'suplier_nama' => 'UD Gabah Murni',
            'alamat' => 'Jl. Raya Sidoarjo Km 5, Sidoarjo',
            'no_hp' => '081234567891',
        ]);

        // 2. Create Products (Barang)
        $beras_premium = Barang::create([
            'nama' => 'Beras Premium Putih',
            'tipe' => 'beras',
        ]);

        $beras_medium = Barang::create([
            'nama' => 'Beras Medium Putih',
            'tipe' => 'beras',
        ]);

        $gabah = Barang::create([
            'nama' => 'Gabah Kering',
            'tipe' => 'gabah',
        ]);

        $katul = Barang::create([
            'nama' => 'Katul Halus',
            'tipe' => 'katul',
        ]);

        // 3. Purchase from Suppliers (Pembelian)
        // Purchase 1: Buy Premium Rice from Suplier 1
        $pembelian1 = Pembelian::create([
            'suplier_id' => $suplier1->id,
            'pembelian_tgl' => now()->subDays(10),
        ]);

        PembelianData::create([
            'pembelian_id' => $pembelian1->id,
            'pembayaran' => 'hutang',
            'barang_id' => $beras_premium->id,
            'pembelian_kotor' => '1000',
            'pembelian_potongan' => '10',
            'pembelian_bersih' => '990',
            'pembelian_harga' => '12000',
            'pembelian_total' => '11880000',
            'pembelian_nota_st' => 'yes',
        ]);

        // Purchase 2: Buy Medium Rice from Suplier 1
        $pembelian2 = Pembelian::create([
            'suplier_id' => $suplier1->id,
            'pembelian_tgl' => now()->subDays(8),
        ]);

        PembelianData::create([
            'pembelian_id' => $pembelian2->id,
            'pembayaran' => 'cash',
            'barang_id' => $beras_medium->id,
            'pembelian_kotor' => '1500',
            'pembelian_potongan' => '15',
            'pembelian_bersih' => '1485',
            'pembelian_harga' => '9000',
            'pembelian_total' => '13365000',
            'pembelian_nota_st' => 'yes',
        ]);

        // Purchase 3: Buy Gabah from Suplier 2
        $pembelian3 = Pembelian::create([
            'suplier_id' => $suplier2->id,
            'pembelian_tgl' => now()->subDays(5),
        ]);

        PembelianData::create([
            'pembelian_id' => $pembelian3->id,
            'pembayaran' => 'hutang',
            'barang_id' => $gabah->id,
            'pembelian_kotor' => '2000',
            'pembelian_potongan' => '40',
            'pembelian_bersih' => '1960',
            'pembelian_harga' => '8000',
            'pembelian_total' => '15680000',
            'pembelian_nota_st' => 'no',
        ]);

        // 4. Stock Management (Stok Barang)
        StokBarang::create([
            'barang_id' => $beras_premium->id,
            'suplier_id' => $suplier1->id,
            'stok' => 990,
        ]);

        StokBarang::create([
            'barang_id' => $beras_medium->id,
            'suplier_id' => $suplier1->id,
            'stok' => 1485,
        ]);

        StokBarang::create([
            'barang_id' => $gabah->id,
            'suplier_id' => $suplier2->id,
            'stok' => 1960,
        ]);

        StokBarang::create([
            'barang_id' => $katul->id,
            'suplier_id' => $suplier2->id,
            'stok' => 500,
        ]);

        // 5. Create Karyawan (Employees) for delivery
        $karyawan1 = Karyawan::create([
            'karyawan_nama' => 'Budi Santoso',
        ]);

        $karyawan2 = Karyawan::create([
            'karyawan_nama' => 'Ahmad Wijaya',
        ]);

        // 6. Create Shipments (Pengiriman) - Selling Rice to Customers
        // Shipment 1: Sell Premium Rice
        $pengiriman1 = Pengiriman::create([
            'nama_pembeli' => 'PT ABC',
            'pengiriman_tgl' => now()->subDays(5),
            'uang_muka' => 5000000,
            'status' => 'yes',
        ]);

        // Shipment Details 1
        $pengiriman_data1 = PengirimanData::create([
            'pengiriman_id' => $pengiriman1->id,
            'barang_id' => $beras_premium->id,
            'data_tonase' => '100',
            'data_harga' => '12500',
            'data_total' => '1250000',
            'pembayaran_st' => 'yes',
        ]);

        // Shipment 2: Sell Medium Rice
        $pengiriman2 = Pengiriman::create([
            'nama_pembeli' => 'CV XYZ',
            'pengiriman_tgl' => now()->subDays(2),
            'uang_muka' => 3000000,
            'status' => 'yes',
        ]);

        // Shipment Details 2
        $pengiriman_data2 = PengirimanData::create([
            'pengiriman_id' => $pengiriman2->id,
            'barang_id' => $beras_medium->id,
            'data_tonase' => '150',
            'data_harga' => '9500',
            'data_total' => '1425000',
            'pembayaran_st' => 'no',
        ]);

        // Shipment Details 2b
        PengirimanData::create([
            'pengiriman_id' => $pengiriman2->id,
            'barang_id' => $katul->id,
            'data_tonase' => '30',
            'data_harga' => '2500',
            'data_total' => '75000',
            'pembayaran_st' => 'no',
        ]);

        // Shipment 3: Draft Shipment (not yet completed)
        $pengiriman3 = Pengiriman::create([
            'nama_pembeli' => 'Toko Makmur',
            'pengiriman_tgl' => now(),
            'uang_muka' => null,
            'status' => 'draft',
        ]);

        // Shipment Details 3
        PengirimanData::create([
            'pengiriman_id' => $pengiriman3->id,
            'barang_id' => $gabah->id,
            'data_tonase' => '200',
            'data_harga' => '8500',
            'data_total' => '1700000',
            'pembayaran_st' => 'no',
        ]);
    }
}
