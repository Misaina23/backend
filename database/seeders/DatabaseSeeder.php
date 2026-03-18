<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user (primary)
        User::firstOrCreate(
            ['email' => 'admin@videeko.com'],
            [
                'name' => 'Administrateur VIDEEKO',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Admin user (agrigest)
        User::firstOrCreate(
            ['email' => 'admin@agrigest.com'],
            [
                'name' => 'Admin AgriGest',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Agent user
        User::firstOrCreate(
            ['email' => 'agent@videeko.com'],
            [
                'name' => 'Agent VIDEEKO',
                'password' => Hash::make('agent123'),
                'role' => 'agent',
            ]
        );

        // Create Inspector user
        User::firstOrCreate(
            ['email' => 'inspecteur@videeko.com'],
            [
                'name' => 'Inspecteur VIDEEKO',
                'password' => Hash::make('inspecteur123'),
                'role' => 'inspector',
            ]
        );

        // Seed producers and inspections
        $this->call([
            ProducerSeeder::class,
            InspectionSeeder::class,
        ]);
    }
}
