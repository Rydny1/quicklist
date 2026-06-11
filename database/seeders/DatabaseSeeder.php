<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // create one default admin account so we can log into the admin panel
        // (password gets hashed automatically by the User model's cast)
        User::create([
            'name' => 'Admin',
            'email' => 'admin@quicklist.com',
            'password' => 'admin1234',
            'role' => 'admin',
        ]);

        // seed the fixed list of categories the site uses
        $cats = ['Electronics', 'Vehicles', 'Jobs', 'Real Estate', 'Other'];
        foreach ($cats as $cat) {
            Category::create([
                'name' => $cat,
                // build the slug from the name: "Real Estate" -> "real-estate"
                'slug' => strtolower(str_replace(' ', '-', $cat)),
            ]);
        }
    }
}