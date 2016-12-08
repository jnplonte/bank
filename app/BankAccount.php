<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use DB;

use App\BankTransaction;

class BankAccount extends Model
{
    //datbase table
    protected $table = 'account';

    //fillable information
    protected $fillable = ['user_id', 'balance'];

    //transfer limit
    protected $transfer_limit;

    //transfer charge
    protected $transfer_charge;

    //transfer api
    protected $transfer_api;

    public function __construct(){
      //updating trasnfer limit, charge and api can be configure on .env file
      $this->transfer_limit = env('TRANSFER_LIMIT', '1000');
      $this->transfer_charge = env('TRANSFER_CHARGE', '100');
      $this->transfer_api = env('TRANSFER_API', null);
    }

    public function getAccount($id = null, $by = null){
      if(!empty($id) && !empty($by)){
        $accounts = DB::table($this->table)
                    ->select('id as account_id', 'balance')
                    ->where($by, $id)
                    ->get();
        return array('status' => 'success', 'data' => $accounts);
      }
      return array('status' => 'failed', 'error' => 'no data found');
    }

    public function insertAccount($id = null, $balance = null){
      if(!empty($id)){
        $arr['updated_at'] =  date('Y-m-d G:i:s');
        $arr['created_at'] =  date('Y-m-d G:i:s');
        $arr['user_id'] = $id;
        $arr['balance'] = !empty($balance) ? $balance : 0;
        $accounts = DB::table($this->table)
                    ->insert($arr);
        if(!empty($accounts)){
          return array('status' => 'success', 'data' => array('user_id' => $id));
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    public function deleteAccount($id = null, $account_id = null){
      if(!empty($id)){
        $accounts = DB::table($this->table)
                    ->where('user_id', $id);
        if(!empty($account_id)){
          $accounts->where('id', $account_id);
        }

        if($accounts->delete()){
          return array('status' => 'success', 'data' => array('user_id' => $id));
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    public function withdrawAccount($id = null, $amount = null){
      if(!empty($id) && !empty($amount)){
        $assumeBalance = $this->checkBalance($id, $amount);
        if($assumeBalance){
          $arr['updated_at'] = date('Y-m-d G:i:s');
          $arr['balance'] = (int)$assumeBalance - (int)$amount;
          $accounts = DB::table($this->table)
                      ->where('id', $id)
                      ->update($arr);
          if(!empty($accounts)){
            return array('status' => 'success', 'data' => array('account_id' => $id));
          }
        }else{
          return array('status' => 'failed', 'error' => 'insufficient funds');
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    public function depositAccount($id = null, $amount = null){
      if(!empty($id) && !empty($amount)){
        $assumeBalance = DB::table($this->table)->select('balance')->where('id', $id)->first();
          if(!empty($assumeBalance)){
          $arr['updated_at'] = date('Y-m-d G:i:s');
          $arr['balance'] = (int)$assumeBalance->balance + (int)$amount;
          $accounts = DB::table($this->table)
                      ->where('id', $id)
                      ->update($arr);
          if(!empty($accounts)){
            return array('status' => 'success', 'data' => array('account_id' => $id));
          }
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    public function transferAccount($id = null, $amount = null, $transfer_id = null){
      if(!empty($id) && !empty($amount) && !empty($transfer_id)){
        $canTransfer = true;
        $userId = DB::table($this->table)->select('user_id')->where('id', $id)->first();
        $transferUserId = DB::table($this->table)->select('user_id')->where('id', $transfer_id)->first();
        if(!empty($userId) && !empty($transferUserId)){
          if(!$this->checkLimit($id, $amount)){
            return array('status' => 'failed', 'error' => 'transfer limit reach');
          }
          $amountWithdraw = (int)$amount; $amountDeposit  = (int)$amount;

          //checking for same acount transfer
          if($userId != $transferUserId){
            $canTransfer = $this->getTransferApi();
            $amountWithdraw = (int)$amount + (int)$this->transfer_charge;
          }

          if($canTransfer){
            $widrawAccount = $this->withdrawAccount($id, $amountWithdraw);
            if($widrawAccount['status'] == 'success'){
              $depositAccount = $this->depositAccount($transfer_id, $amountDeposit);
              if($depositAccount['status'] == 'success'){
                return array('status' => 'success', 'data' => array('account_id' => $id));
              }
            }
          }
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    //getting of account information via account_id
    public function getAccountInfo($id = null)
    {
        return DB::table($this->table)->where('id', $id)->first();
    }

    //getting of account information via account_id
    private function checkBalance($id = null, $amount = null){
      if(!empty($id) && !empty($amount)){
        $assumeBalance = DB::table($this->table)->select('balance')->where('id', $id)->first();
        if(!empty($assumeBalance)){
          if($assumeBalance->balance >= $amount){
            return $assumeBalance->balance;
          }
        }
      }
      return false;
    }

    //checking of remaining + the deposit or transfer amount to be able to perform deposit or transfer
    private function checkLimit($id = null, $amount = null){
      if(!empty($id) && !empty($amount)){
        $bankTransaction = new BankTransaction();
        $transactionAmount = $bankTransaction->getTransactionAmount($id);
        if(!empty($transactionAmount)){
          if($this->transfer_limit >= ((int)$transactionAmount->total_amount + (int)$amount)){
            return true;
          }
        }
      }
      return false;
    }

    //call handy api to verify transfer
    private function getTransferApi()
    {
        if(!empty($this->transfer_api)){
          $client = new Client();
          $res = $client->request('GET', $this->transfer_api);
          if($res->getStatusCode() == 200){
            $resBody = json_decode($res->getBody());
            return ($resBody->status == 'success');
          }
        }
        return false;
    }
}
