<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BankUserTest extends TestCase
{
    //testing users
    public function testGetUsers()
    {
      $this->json('GET', '/users')
            ->seeJsonStructure([
                 'total',
                 'per_page',
                 'current_page',
                 'last_page',
                 'next_page_url',
                 'prev_page_url',
                 'from',
                 'to',
                 'data' => [[
                   'user_id',
                   'first_name',
                   'last_name',
                   'email',
                   'accounts'
                 ]]
             ]);
    }

    //testing one user
    public function testGetUser()
    {
      $this->json('GET', '/user/1')
            ->seeJsonStructure([
                 'status',
                 'data' =>[
                   'user_id',
                   'first_name',
                   'last_name',
                   'email',
                   'accounts'
                 ]
             ]);
    }

    //testing add user
    public function testAddUser()
    {
      $this->json('POST', '/user', ['email' => 'test@test.com',
                                    'first_name' => 'f_test',
                                    'last_name' => 'l_test',
                                    'balance' => '12345'])
            ->seeJsonStructure([
                'status',
                'data'
             ]);
      $this->seeInDatabase('user', ['email' => 'test@test.com']);
    }

    //testing delete user
    public function testDeleteUser()
    {
      $userData = DB::table('user')->orderBy('created_at', 'desc')->first();
      $this->json('DELETE', '/user/'.$userData->id)
            ->seeJsonStructure([
                'status',
                'data'
             ]);
      $this->notSeeInDatabase('user', ['id' => $userData->id]);
    }

    //testing update user
    public function testUpdateUser()
    {
      $this->json('PUT', '/user/1', ['first_name' => 'john',
                                     'last_name' => 'paul'])
            ->seeJsonStructure([
                'status',
                'data'
             ]);
      $this->seeInDatabase('user', ['first_name' => 'john', 'last_name' => 'paul']);
    }
}
