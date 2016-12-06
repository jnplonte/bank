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

    public function insertAccount($id = null, $balance = null){
      if(!empty($id)){
        $arr['updated_at'] =  date('Y-m-d G:i:s');
        $arr['created_at'] =  date('Y-m-d G:i:s');
        $arr['user_id'] = $id;
        $arr['balance'] = !empty($balance) ? $balance : 0;
        $account = DB::table($this->table)
                  ->insert($arr);
        if(!empty($account)){
          return array('success' => true, 'data' => array('user_id' => $id));
        }
      }
      return array('success' => false, 'data' => null);
    }

    public function deleteAccount($id = null){
      if(!empty($id)){
        $account = DB::table($this->table)
                ->where('user_id', $id)
                ->delete();
        if(!empty($account)){
          return array('success' => true, 'data' => array('user_id' => $id));
        }
      }
      return array('success' => false, 'data' => null);
    }
}
