<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            
            [
                'acronym' => 'WMS',
                'title' => 'Warehouse Management System',
                'description' => 'A Warehouse Management System (WMS) is software that keeps a warehouse organized by tracking inventory in real time, from arrival to shipping. It helps manage stock levels, locations, and movements, ensuring efficient operations and accurate order fulfillment.',
                'link' => null,
                'image' => '/project_logo/warehouse_logo.png',
                'project_status' => 'completed',
                'status' => 'active',
                'made_of' => json_encode(['PHP','MySQL','HTML','CSS','Bootstrap','JavaScript','jQuery']),
            ],
            [
                'acronym' => 'CP',
                'title' => 'Customer Portal System',
                'description' => 'This system serves similarly to an e-commerce website, offering clients a business-to-business (B2B) storage solution where they can make inbound and outgoing transactions and utilize this portal to monitor their products on our warehouse management system.',
                'link' => null,
                'image' => null,
                'project_status' => 'completed',
                'status' => 'active',
                'made_of' => json_encode(['PHP','MySQL','HTML','CSS','Bootstrap','JavaScript','jQuery']),
            ]
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
