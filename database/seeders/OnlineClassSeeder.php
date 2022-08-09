<?php

namespace Database\Seeders;

use App\Models\OnlineClass;
use App\Models\RombelClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnlineClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new = OnlineClass::create([
            'teacher_id' => 1,
            'name' => 'Komputer dan Jaringan Dasar',
            'desc' => 'KJD',
            'rombel_class_id' => 1,
        ]);

        $rombel = RombelClass::find(1);

        // enroll student from rombel_class
        if (!is_null($rombel->students)) {
            $oc = OnlineClass::find($new->id);
            $oc->students()->sync($rombel->students->pluck('id'));
        }
    }
}
