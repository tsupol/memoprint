<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->delete();
        
		\DB::table('users')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'masterpiece review',
				'email' => 'ton.supol@gmail.com',
				'password' => '$2y$10$Jo5HaXQIGiPAXWVtdDPXteMWpzCfZLRAkYgUoY6fp0zodFm9HA51u',
				'remember_token' => NULL,
				'created_at' => '2015-03-29 17:50:48',
				'updated_at' => '2015-03-29 17:50:48',
			),
		));
	}

}
