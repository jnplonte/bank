<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\BankUser;
use App\BankAccount;

class UserController extends Controller
{
    private $request;

    public function __construct(Request $request){
      $this->request = $request;
    }

    public function getAll()
    {
      $getData = $this->request->all();

      $sort = !empty($getData['sort']) ? $getData['sort'] : null;
      $page = !empty($getData['page']) ? $getData['page'] : null;
      $search = !empty($getData['q']) ? $getData['q'] : null;

      $bankUser = new BankUser();
      $userData = $bankUser->getUsers($sort, $page, $search)->toArray();
      if($userData['total'] >= 1){
        $bankAccount = new BankAccount();
        foreach ($userData['data'] as $key => $value) {
          $value->accounts = $bankAccount->getAccount($value->user_id, 'user_id')['data'];
        }
      }
      return response()->json($userData);
    }

    public function get($id=null)
    {
      $bankUser = new BankUser();
      $userData = $bankUser->getUser($id);
      if(!empty($userData['data'])){
        $bankAccount = new BankAccount();
          $userData['data']->accounts = $bankAccount->getAccount($userData['data']->user_id, 'user_id')['data'];
      }
      return response()->json($userData);
    }

    public function insert()
    {
        $postData = $this->request->all();
        $bankUser = new BankUser();
        $insertUser = $bankUser->insertUser($postData);
        if($insertUser['success'] == true){
          $bankAccount = new BankAccount();
          $balance = !empty($postData['balance']) ? $postData['balance'] : null;
          return response()->json($bankAccount->insertAccount($insertUser['data']['user_id'], $balance));
        }else{
          return response()->json($insertUser);
        }
    }

    public function update($id=null)
    {
      if ($this->request->isMethod('put')) {
          $putData = $this->request->all();
          $bankUser = new BankUser();
          return response()->json($bankUser->updateUser($id, $putData));
      }

      if ($this->request->isMethod('delete')) {
          $bankUser = new BankUser();
          $deleteUser = $bankUser->deleteUser($id);
          if($deleteUser['success'] == true){
            $bankAccount = new BankAccount();
            return response()->json($bankAccount->deleteAccount($deleteUser['data']['user_id']));
          }else{
            return response()->json($deleteUser);
          }

      }

      return abort(404);
    }
}
