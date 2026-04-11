<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Heading;

class HeadingMenuTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Heading::create([
            'app_heading_id' => 'H0000',
            'app_heading_name' => 'Dashboard',
            'app_heading_icon' => 'fas fa-tachometer-alt',
        ]);
        Heading::create([
            'app_heading_id' => 'H0001',
            'app_heading_name' => 'Master Data Menu',
            'app_heading_icon' => 'fas fa-database',
        ]);
        Heading::create([
            'app_heading_id' => 'H0002',
            'app_heading_name' => 'Master Data User',
            'app_heading_icon' => 'fas fa-users',
        ]);
        Heading::create([
            'app_heading_id' => 'H0003',
            'app_heading_name' => 'Master Data',
            'app_heading_icon' => '',
        ]);
        Heading::create([
            'app_heading_id' => 'H0004',
            'app_heading_name' => 'Data Penduduk',
            'app_heading_icon' => '',
        ]);
    }
}
