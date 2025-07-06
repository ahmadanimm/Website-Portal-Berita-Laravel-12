<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Hiburan',
                'slug' => 'hiburan',
                'icon' => 'icons/j7Be7ro4KxmM15gmN8SkFQiFp4CqAbuZFJnbQENh.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Otomotif',
                'slug' => 'otomotif',
                'icon' => 'icons/jEzchdiPTVYFhJaZFJxBMGTLlL7C1IavRUiuj1lN.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'icon' => 'icons/BqNwzEVbyFdiN7h57E1lIpGuOf22z55MasdqzIuf.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Politik',
                'slug' => 'politik',
                'icon' => 'icons/WM6laNXs1kiWrk1FzlGPzRDjwQqU2q7hHZ54tiE2.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bisnis',
                'slug' => 'bisnis',
                'icon' => 'icons/oXOkddDMywgUSREWiUnbIqqg3puKc4aiNI6VHruN.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'icon' => 'icons/V9ERXRQvQdcMYs0D74BvPITiB83rSZ020KrfmOQk.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Makanan',
                'slug' => 'makanan',
                'icon' => 'icons/7s2USAjBs5wndlJ78pzsb2JtWuAnZ8sdPqZZ125o.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}