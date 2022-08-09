<?php

namespace Database\Seeders;

use App\Models\OnlineClassContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnlineClassContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OnlineClassContent::create([
            'online_class_id' => 1,
            'title' => 'Materi 1',
        ]);
        OnlineClassContent::create([
            'online_class_id' => 1,
            'title' => 'Materi 2',
        ]);
        OnlineClassContent::create([
            'online_class_id' => 1,
            'title' => 'Materi 3',
        ]);
    }
}
