<?php

use Illuminate\Database\Seeder;

class departaments extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Departments::create([
            'name' => 'Кафедра 1',
        ]);

        \App\Departments::create([
            'name' => 'Кафедра 2',
        ]);
    }
}
