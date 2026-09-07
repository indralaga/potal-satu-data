<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OPD;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat OPD
        $opds = [
            ['name' => 'Dinas Kesehatan', 'acronym' => 'DINKES'],
            ['name' => 'Dinas Pendidikan', 'acronym' => 'DISDIK'],
            ['name' => 'Dinas Pertanian', 'acronym' => 'DISTANI'],
            ['name' => 'Dinas Perekonomian', 'acronym' => 'DISPEKON'],
            ['name' => 'Badan Pusat Statistik', 'acronym' => 'BPS'],
        ];

        foreach ($opds as $opd) {
            OPD::create($opd);
        }

        // Buat Admin Portal
        User::create([
            'name' => 'Admin Portal',
            'email' => 'admin@taput.gov.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_portal',
            'is_active' => true,
        ]);

        // Buat Admin OPD
        foreach (OPD::all() as $opd) {
            User::create([
                'name' => 'Admin ' . $opd->name,
                'email' => 'admin.' . strtolower(str_replace(' ', '.', $opd->name)) . '@taput.gov.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_opd',
                'opd_id' => $opd->id,
                'is_active' => true,
            ]);
        }

        // Buat Viewer
        User::create([
            'name' => 'User Viewer',
            'email' => 'viewer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'viewer',
            'is_active' => true,
        ]);
    }
}
