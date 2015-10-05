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
        \App\TypeExam::create(['name' => 'Credit']);
        \App\TypeExam::create(['name' => 'Exams']);
        \App\TypeExam::create(['name' => 'Retake Exam']);
    }
}
