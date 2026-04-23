<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $certifications = [
            [
                'name' => 'Laravel PHP Framework',
                'issuer' => 'Incentive Media',
                'issued_at' => '2022-06-01',
                'icon_path' => 'skills_logo/laravel_logo.png',
                'description' => 'Comprehensive Laravel certification covering Eloquent ORM, MVC architecture, routing, middleware, and best practices for production-grade PHP applications.',
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Machine Learning Using TensorFlow',
                'issuer' => 'San Beda University',
                'issued_at' => '2019-09-01',
                'icon_path' => null,
                'description' => 'Foundations of machine learning with TensorFlow — neural networks, model training, and applied ML for real-world problem solving.',
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Entrepreneurship Master Class & Incubation',
                'issuer' => 'DOST & TIP NITRO Academy of Entrepreneurs',
                'issued_at' => '2021-05-01',
                'icon_path' => null,
                'description' => '15-week intensive program covering business modeling, ideation, market validation, and startup incubation. Bridges technical skills with entrepreneurial thinking.',
                'sort_order' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($certifications as $cert) {
            Certification::firstOrCreate(['name' => $cert['name']], $cert);
        }
    }
}
