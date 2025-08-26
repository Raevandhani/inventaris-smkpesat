<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            Role::class => [
                ['name' => 'student', 'guard_name' => 'web'],
                ['name' => 'teacher', 'guard_name' => 'web'],
            ],
            Category::class => [
                ['name' => 'Computer'],
                ['name' => 'Audio'],
                ['name' => 'Networking'],
                ['name' => 'Accessories'],
            ],
            Location::class => [
                ['name' => 'Lab 1'],
                ['name' => 'Lab 2'],
                ['name' => 'Lab 3'],
                ['name' => 'Lab 4'],
                ['name' => 'Lab 5'],
                ['name' => 'Lab 6'],
            ],
        ];

        foreach ($data as $model => $records) {
            foreach ($records as $record) {
                $model::firstOrCreate(['name' => $record['name']], $record);
            }
        }
    }
}
