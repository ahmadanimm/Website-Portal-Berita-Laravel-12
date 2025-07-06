<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class AuthorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('authors')->insert([
            [
                'name' => 'Ahmad',
                'avatar' => 'avatars/bJnAH9j4nq8jZpL4Ctm2ONMQtlmbE2sUaigAOSoR.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'average_rating' => 0.0,
                'ratings_count' => 0,
            ],
            [
                'name' => 'Lukman',
                'avatar' => 'avatars/pkZJwSPiq92FL3LhEshklyE2lHOxtZmISE6EyXaf.png', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'average_rating' => 0.0,
                'ratings_count' => 0,
            ],
            [
                'name' => 'Satria',
                'avatar' => 'avatars/yZ9wBnNewhJuqZMISdn1ag6z7yiszNAUhPXMveeA.png', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'average_rating' => 0.0,
                'ratings_count' => 0,
            ],
            [
                'name' => 'Nikki',
                'avatar' => 'avatars/rtxvnoJYmXOHPI7rNp7rqEF2JSOlzJrGjdxt3oZt.png', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'average_rating' => 0.0,
                'ratings_count' => 0,
            ],
            [
                'name' => 'Risam',
                'avatar' => 'avatars/6Gez08Xy09rudxwPBs6Dzo0ZeV0SGiQEBQESBCrC.png', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'average_rating' => 0.0,
                'ratings_count' => 0,
            ],
            [
                'name' => 'Ryan',
                'avatar' => 'avatars/DQA65vHoOagvuKFYYkQuAHj53ASCt2IelFgfOrsu.png', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'average_rating' => 0.0,
                'ratings_count' => 0,
            ],
        ]);
    }
}