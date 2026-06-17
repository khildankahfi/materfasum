<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::updateOrCreate(
            ['email' => 'admin@materfasum.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'phone'    => '081234567890',
            ]
        );

        // User demo
        User::updateOrCreate(
            ['email' => 'user@materfasum.id'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('user123'),
                'role'     => 'user',
                'phone'    => '089876543210',
            ]
        );

        // Seeding Categories
        $categories = [
            [
                'slug' => 'jalan',
                'name' => 'Jalan',
                'icon' => 'bi-cone-striped',
                'color' => '#6366f1',
            ],
            [
                'slug' => 'jembatan',
                'name' => 'Jembatan',
                'icon' => 'bi-tools',
                'color' => '#ec4899',
            ],
            [
                'slug' => 'lampu',
                'name' => 'Lampu Jalan',
                'icon' => 'bi-lightbulb-fill',
                'color' => '#f59e0b',
            ],
            [
                'slug' => 'taman',
                'name' => 'Taman',
                'icon' => 'bi-tree-fill',
                'color' => '#10b981',
            ],
            [
                'slug' => 'drainase',
                'name' => 'Drainase',
                'icon' => 'bi-droplet-fill',
                'color' => '#0ea5e9',
            ],
            [
                'slug' => 'fasilitas_umum',
                'name' => 'Fasilitas Umum',
                'icon' => 'bi-building',
                'color' => '#64748b',
            ],
            [
                'slug' => 'lainnya',
                'name' => 'Lainnya',
                'icon' => 'bi-tags-fill',
                'color' => '#64748b',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Seeding Departments
        $departments = [
            [
                'code' => 'DPUTR',
                'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
            ],
            [
                'code' => 'DISHUB',
                'name' => 'Dinas Perhubungan',
            ],
            [
                'code' => 'DLH',
                'name' => 'Dinas Lingkungan Hidup',
            ],
            [
                'code' => 'SATPOL_PP',
                'name' => 'Satuan Polisi Pamong Praja',
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
