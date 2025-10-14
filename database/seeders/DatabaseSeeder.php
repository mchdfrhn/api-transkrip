<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            RequestsSeeder::class,
            ResponsesSeeder::class,
            RequestFilesSeeder::class,
            ResponseFilesSeeder::class,
        ]);
    }
}
