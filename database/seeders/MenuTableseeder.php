<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'menu_id' => 'M0000',
            'app_heading_id' => 'H0000',
            'menu_name' => 'Dashboard',
            'menu_url' => 'dashboard',
            'menu_parent' => '0',
        ]);
        Menu::create([
            'menu_id' => 'M0001',
            'app_heading_id' => 'H0001',
            'menu_name' => 'Heading Aplikasi',
            'menu_url' => 'headingApp',
            'menu_parent' => '0',
        ]);Menu::create([
            'menu_id' => 'M0002',
            'app_heading_id' => 'H0001',
            'menu_name' => 'Menu Aplikasi',
            'menu_url' => 'menuApp',
            'menu_parent' => '0',
        ]);Menu::create([
            'menu_id' => 'M0003',
            'app_heading_id' => 'H0002',
            'menu_name' => 'Role Pengguna',
            'menu_url' => 'rolePengguna',
            'menu_parent' => '0',
        ]);Menu::create([
            'menu_id' => 'M0004',
            'app_heading_id' => 'H0002',
            'menu_name' => 'Role Menu',
            'menu_url' => 'roleMenu',
            'menu_parent' => '0',
        ]);Menu::create([
            'menu_id' => 'M0005',
            'app_heading_id' => 'H0002',
            'menu_name' => 'Data User',
            'menu_url' => 'dataUser',
            'menu_parent' => '0',
        ]);Menu::create([
            'menu_id' => 'M0006',
            'app_heading_id' => 'H0003',
            'menu_name' => 'Jabatan',
            'menu_url' => 'jabatan',
            'menu_parent' => '0',
        ]);Menu::create([
            'menu_id' => 'M0007',
            'app_heading_id' => 'H0004',
            'menu_name' => 'Data Penduduk',
            'menu_url' => 'dataPenduduk',
            'menu_parent' => '0',
        ]);
    }
}
