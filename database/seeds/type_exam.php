<?php

use Illuminate\Database\Seeder;

class type_exam extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\TypeExam::create(['name' => 'мк']);
        \App\TypeExam::create(['name' => 'іс']);
        \App\TypeExam::create(['name' => 'дз']);
    }
}
