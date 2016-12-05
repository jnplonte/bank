<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class InfoUser extends Model
{
    protected $table = 'info';

    protected $fillable = ['user_id', 'last_transaction_balance', 'last_transaction_date'];

    public function __construct(){

    }

    public function insertInfo($id = null){
      if(!empty($id)){
        $arr['updated_at'] =  date('Y-m-d G:i:s');
        $arr['created_at'] =  date('Y-m-d G:i:s');
        $arr['user_id'] =  $id;
        $info = DB::table($this->table)
        ->insert($arr);
        if(!empty($info)){
          return array('success' => true, 'id' => $id);
        }else{
          return array('success' => false, 'id' => null);
        }
      }else{
        return array('success' => false, 'id' => null);
      }
    }
}
