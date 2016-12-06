<?php

use Illuminate\Database\Seeder;

class account_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('account')
        ->insert([[
            'id' => '1',
            'user_id' => '1',
            'balance' => '10000',
            'updated_at' =>  date('Y-m-d G:i:s'),
            'created_at' =>  date('Y-m-d G:i:s')
        ],[
            'id' => '2',
            'user_id' => '1',
            'balance' => '20000',
            'updated_at' =>  date('Y-m-d G:i:s'),
            'created_at' =>  date('Y-m-d G:i:s')
        ]]);
    }
}
