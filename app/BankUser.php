<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class BankUser extends Model
{
    //datbase table
    protected $table = 'user';

    //fillable information
    protected $fillable = ['first_name', 'last_name', 'email'];

    //items per page
    protected $page = 10;

    //default sorting
    protected $type = 'id';

    //default sorting direction
    protected $sort = 'asc';

    //searh parameters
    protected $search = '';

    public function __construct(){

    }

    public function getUsers($sort = null, $page = null, $search = null){
      $appends = [];
      if(!empty($page)){ $this->$page = $page; }
      if(!empty($sort)){ $this->sort = $sort; $appends['sort'] = $sort; }
      if(!empty($search)){ $this->search = $search;  $appends['q'] = $search; }
      $users = DB::table($this->table)
              ->select('id as user_id', 'first_name', 'last_name', 'email')
              ->where('first_name', 'like', '%'.$search.'%')
              ->orWhere('last_name', 'like', '%'.$search.'%')
              ->orWhere('email', 'like', '%'.$search.'%')
              ->orderBy($this->type, $this->sort)
              ->paginate($this->page)
              ->appends($appends);
      return $users;
    }

    public function getUser($id = null){
      if(!empty($id)){
        $users = DB::table($this->table)
                ->select('id as user_id', 'first_name', 'last_name', 'email')
                ->where('id', $id)
                ->first();
        return array('status' => 'success', 'data' => $users);
      }
      return array('status' => 'failed', 'error' => 'no data found');
    }

    public function updateUser($id = null, $arr = array()){
      if(!empty($id) && !empty($arr)){
        $checkDuplicateEmail = false;
        if(!empty($arr['email'])){
          $checkDuplicateEmail = DB::table($this->table)->where('email', $arr['email'])->where('id', '!=', $id)->get();
        }
        if( empty($checkDuplicateEmail) ){
          $arr = $this->getFillableInfo($arr);
          $arr['updated_at'] =  date('Y-m-d G:i:s');
          $users = DB::table($this->table)
                  ->where('id', $id)
                  ->update($arr);
          if(!empty($users)){
            return array('status' => 'success', 'data' => array('user_id' => $id));
          }
        }else{
          return array('status' => 'failed', 'error' => 'email already exists');
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    public function deleteUser($id = null){
      if(!empty($id)){
        $users = DB::table($this->table)
                ->where('id', $id);
        if($users->delete()){
          return array('status' => 'success', 'data' => array('user_id' => $id));
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    public function insertUser($arr = array()){
      if(!empty($arr['email'])){
        $checkDuplicateEmail = DB::table($this->table)->where('email', $arr['email'])->get();
        if( empty($checkDuplicateEmail) ){
          $arr = $this->getFillableInfo($arr);
          $arr['updated_at'] =  date('Y-m-d G:i:s');
          $arr['created_at'] =  date('Y-m-d G:i:s');
          $id = DB::table($this->table)
                ->insertGetId($arr);
          if(!empty($id)){
            return array('status' => 'success', 'data' => array('user_id' => $id));
          }
        }else{
          return array('status' => 'failed', 'error' => 'email already exists');
        }
      }
      return array('status' => 'failed', 'error' => 'unable to process request');
    }

    private function getFillableInfo($arr = null){
      if(!empty($arr)){
        $finalArr = array();
        foreach ($this->fillable as $value) {
          if( !empty($arr[$value]) ){
            $finalArr[$value] = $arr[$value];
          }
        }
      }
      return $finalArr;
    }
}
