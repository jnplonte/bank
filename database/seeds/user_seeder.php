<?php

use Illuminate\Database\Seeder;

class user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('user')->insert([[
          'id' => '1',
          'first_name' => 'john paul',
          'last_name' => 'onte',
          'email' => 'jnpl.onte@gmail.com',
          'updated_at' =>  date('Y-m-d G:i:s'),
          'created_at' =>  date('Y-m-d G:i:s')
      ],
      [
          'id' => '2',
          'first_name' => 'jnpl',
          'last_name' => 'onte',
          'email' => 'jnpl@gmail.com',
          'updated_at' =>  date('Y-m-d G:i:s'),
          'created_at' =>  date('Y-m-d G:i:s')
      ]]);
    }
}
