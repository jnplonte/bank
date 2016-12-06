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
      return array('success' => false, 'data' => null);
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
      return array('success' => false, 'data' => null);
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
      return array('success' => false, 'data' => null);
    }
}
