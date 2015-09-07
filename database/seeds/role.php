<?php

use Illuminate\Database\Seeder;

class role extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Role::create([
            'name' => 'Admin',
        ]);

        \App\Role::create([
            'name' => 'Student',
        ]);

    }
}
