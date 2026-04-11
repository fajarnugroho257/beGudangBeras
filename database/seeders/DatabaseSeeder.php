<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Menu;
use App\Models\Role_menu;
use App\Models\Heading;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleTableseeder::class,
            UserTableseeder::class,
            HeadingMenuTableseeder::class,
            MenuTableseeder::class,
            RoleMenuTableseeder::class,
        ]);

    }
}
