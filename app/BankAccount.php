<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class BankAccount extends Model
{
    //datbase table
    protected $table = 'account';

    //fillable information
    protected $fillable = ['user_id', 'balance'];

    public function __construct(){

    }

    public function getAccount($id = null, $by = null){
      if(!empty($id) && !empty($by)){
        $accounts = DB::table($this->table)
                    ->select('id as account_id', 'balance')
                    ->where($by, $id)
                    ->get();
        return array('success' => true, 'data' => $accounts);
      }
      return array('success' => false, 'error' => 'no data found');
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
          return array('success' => true, 'data' => array('user_id' => $id));
        }
      }
      return array('success' => false, 'error' => 'unable to process request');
    }

    public function deleteAccount($id = null){
      if(!empty($id)){
        $accounts = DB::table($this->table)
                    ->where('user_id', $id)
                    ->delete();
        if(!empty($accounts)){
          return array('success' => true, 'data' => array('user_id' => $id));
        }
      }
      return array('success' => false, 'error' => 'unable to process request');
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
            return array('success' => true, 'data' => array('account_id' => $id));
          }
        }
      }
      return array('success' => false, 'error' => 'unable to process request');
    }

    public function depositAccount($id = null, $amount = null){
      if(!empty($id) && !empty($amount)){
        $assumeBalance = DB::table($this->table)->select('balance')->where('id', $id)->first();
        $arr['updated_at'] = date('Y-m-d G:i:s');
        $arr['balance'] = (int)$assumeBalance->balance + (int)$amount;
        $accounts = DB::table($this->table)
                    ->where('id', $id)
                    ->update($arr);
        if(!empty($accounts)){
          return array('success' => true, 'data' => array('account_id' => $id));
        }
      }
      return array('success' => false, 'error' => 'unable to process request');
    }

    private function checkBalance($id = null, $amount = null){
      if(!empty($id) && !empty($amount)){
        $assumeBalance = DB::table($this->table)->select('balance')->where('id', $id)->first();
        if($assumeBalance->balance > $amount){
          return $assumeBalance->balance;
        }
      }
      return false;
    }
}
