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
            'name' => 'Self-Admin',
        ]);

        \App\Role::create([
            'name' => 'Inspektor',
        ]);

        \App\Role::create([
            'name' => 'Dekanat',
        ]);

        \App\Role::create([
            'name' => 'Teacher',
        ]);

        \App\Role::create([
            'name' => 'Student',
        ]);

        \App\Role::create([
            'name' => 'Guest',
        ]);

    }
}
