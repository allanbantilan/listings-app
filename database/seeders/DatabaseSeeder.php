<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Listing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.                                                              
     */                
    public function run(): void
    {
        User::factory(count: 2)->create();

        Listing::factory(50)->create();
    }
}
