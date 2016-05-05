<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Add calls to Seeders here
        $this->call(role::class);
        $this->command->info('The Roles table has been seeded!');

        $this->call(type_exam::class);
        $this->command->info('The TypeExam table has been seeded!');

        $this->call(UserTableSeeder::class);
        $this->command->info('The Users table has been seeded!');

        $this->call(LanguageTableSeeder::class);
        $this->command->info('The Language table has been seeded!');

        $this->call(departaments::class);
        $this->command->info('The Departments table has been seeded!');

        $this->call(UserToDepartment::class);
        $this->command->info('The UserToDepartment table has been seeded!');

        $this->call(settings::class);
        $this->command->info('The Settings table has been seeded!');
        Model::reguard();
    }
}
