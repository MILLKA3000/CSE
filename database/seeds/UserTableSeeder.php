<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

	public function run()
	{

		\App\User::create([
			'name' => 'Admin User',
			'username' => 'admin_user',
			'email' => 'admin@admin.com',
			'password' => bcrypt('admin'),
			'confirmed' => 1,
            'role_id' => 1,
			'confirmation_code' => md5(microtime() . env('APP_KEY')),
		]);

		\App\User::create([
			'name' => 'Test User',
			'username' => 'test_user',
			'email' => 'user@user.com',
			'password' => bcrypt('user'),
			'confirmed' => 1,
            'role_id' => 2,
			'confirmation_code' => md5(microtime() . env('APP_KEY')),
		]);


         \App\User::create([
             'name' => 'Ruslan',
             'username' => 'music',
             'email' => 'ruslan.m@tdmu.edu.ua',
             'password' => bcrypt('ruslan'),
             'confirmed' => 1,
             'role_id' => 1,
             'confirmation_code' => md5(microtime() . env('APP_KEY')),
         ]);

         \App\User::create([
             'name' => 'Igor',
             'username' => 'gor',
             'email' => 'gor@tdmu.edu.ua',
             'password' => bcrypt('gorgor'),
             'confirmed' => 1,
             'role_id' => 1,
             'confirmation_code' => md5(microtime() . env('APP_KEY')),
         ]);

         \App\User::create([
             'name' => 'Igor Kovbasuk',
             'username' => 'igor',
             'email' => 'kovbasyk_i@tdmu.edu.ua',
             'password' => bcrypt('12345!'),
             'confirmed' => 1,
             'role_id' => 1,
             'confirmation_code' => md5(microtime() . env('APP_KEY')),
         ]);
	}

}
