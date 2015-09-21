<?php

use Illuminate\Database\Seeder;

class UserToDepartment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\UserToDepartments::create([
            'departments_id' => '1',
            'user_id' => '1',
        ]);

        \App\UserToDepartments::create([
            'departments_id' => '2',
            'user_id' => '2',
        ]);
    }
}
