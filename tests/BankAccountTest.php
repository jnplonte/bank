<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BankAccountTest extends TestCase
{
    //testing add account
    public function testAddAccount()
    {
     $this->json('POST', '/account/1', ['balance' => '7777'])
           ->seeJsonStructure([
               'status',
               'data'
            ]);
     $this->seeInDatabase('account', ['user_id' => '1', 'balance' => '7777']);
    }

    //testing delete account
    public function testDeleteAccount()
    {
      $accountData = DB::table('account')->orderBy('created_at', 'desc')->first();
      $this->json('DELETE', '/account/1', ['account_id' => $accountData->id])
           ->seeJsonStructure([
               'status',
               'data'
            ]);
      $this->notSeeInDatabase('account', ['id' => $accountData->id]);
    }

    //testing withdraw money
    public function testWithdrawMoney()
    {
      $accountData = DB::table('account')->where('id', '1')->first();
      $this->json('PUT', '/withdraw/1', ['amount' => '1000'])
            ->seeJsonStructure([
                'status',
                'data'
             ]);
      $this->seeInDatabase('account', ['id' => '1', 'balance' => ((int)$accountData->balance - 1000)]);
    }

    //testing withdraw money
    public function testDepositMoney()
    {
      $accountData = DB::table('account')->where('id', '1')->first();
      $this->json('PUT', '/deposit/1', ['amount' => '1000'])
            ->seeJsonStructure([
                'status',
                'data'
             ]);
      $this->seeInDatabase('account', ['id' => '1', 'balance' => ((int)$accountData->balance + 1000)]);
    }

    //testing transfer money from same account
    public function testTransferMoneySameAccount()
    {
      $accountData1 = DB::table('account')->where('id', '1')->first();
      $accountData2 = DB::table('account')->where('id', '2')->first();
      $this->json('PUT', '/transfer/1', ['amount' => '1', 'transfer_id' => '2'])
            ->seeJsonStructure([
                'status',
                'data'
             ]);
      $this->seeInDatabase('account', ['id' => '1', 'balance' => ((int)$accountData1->balance - 1)]);
      $this->seeInDatabase('account', ['id' => '2', 'balance' => ((int)$accountData2->balance + 1)]);
    }

    //testing transfer money from diff account
    public function testTransferMoneyDiffAccount()
    {
      $accountData1 = DB::table('account')->where('id', '1')->first();
      $accountData2 = DB::table('account')->where('id', '3')->first();
      $this->json('PUT', '/transfer/1', ['amount' => '1', 'transfer_id' => '3'])
            ->seeJsonStructure([
                'status',
                'data'
             ]);
      $this->seeInDatabase('account', ['id' => '1', 'balance' => ((int)$accountData1->balance - (1 + (int)env('TRANSFER_CHARGE', '100')))]);
      $this->seeInDatabase('account', ['id' => '3', 'balance' => ((int)$accountData2->balance + 1)]);
    }
}
