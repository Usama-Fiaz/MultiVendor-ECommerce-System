<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminRoleTable::class,
            AdminTable::class,
            SellerTableSeeder::class,
            BusinessSettingSeeder::class,
        ]);
    }
}
