<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class BankUser extends Model
{
    protected $table = 'user';

    protected $fillable = ['name', 'balance'];

    protected $page = 10;

    protected $type = 'id';

    protected $sort = 'asc';

    protected $search = '';

    public function __construct(){

    }

    public function getUsers($sort = null, $page = null, $search = null){
      $appends = [];
      if(!empty($page)){ $this->$page = $page; }
      if(!empty($sort)){ $this->sort = $sort; $appends['sort'] = $sort; }
      if(!empty($search)){ $this->search = $search;  $appends['q'] = $search; }
      $users = DB::table($this->table)
          ->select('id', 'name', 'balance')
          ->where('name', 'like', '%'.$search.'%')
          ->orderBy($this->type, $this->sort)
          ->paginate($this->page)
          ->appends($appends);
      return $users;
    }

    public function getUser($id = null){
      if(!empty($id)){
        $users = DB::table($this->table)
            ->select('name', 'balance')
            ->where('id', $id)
            ->first();
        return array('success' => true, 'data' => $users);
      }else{
        return array('success' => false, 'data' => null);
      }
    }

    public function updateUser($id = null, $arr = array()){
      if(!empty($id) && !empty($arr)){
        $arr['updated_at'] =  date('Y-m-d G:i:s');
        $users = DB::table($this->table)
            ->where('id', $id)
            ->update($arr);
        if(!empty($users)){
          return array('success' => true, 'id' => $id);
        }else{
          return array('success' => false, 'id' => null);
        }
      }else{
        return array('success' => false, 'id' => null);
      }
    }

    public function deleteUser($id = null){
      if(!empty($id)){
        $users = DB::table($this->table)
        ->where('id', $id)
        ->delete();
        if(!empty($users)){
          return array('success' => true, 'id' => $id);
        }else{
          return array('success' => false, 'id' => null);
        }
      }else{
        return array('success' => false, 'id' => null);
      }
    }

    public function insertUser($arr = array()){
      if(!empty($arr)){
        $arr['updated_at'] =  date('Y-m-d G:i:s');
        $arr['created_at'] =  date('Y-m-d G:i:s');
        $id = DB::table($this->table)
        ->insertGetId($arr);
        if(!empty($id)){
          return array('success' => true, 'id' => $id);
        }else{
          return array('success' => false, 'id' => null);
        }
      }else{
        return array('success' => false, 'id' => null);
      }
    }
}
