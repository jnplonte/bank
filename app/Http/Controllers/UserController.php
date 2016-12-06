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
      $userData = new BankUser();
      $getData = $this->request->all();

      $sort = !empty($getData['sort']) ? $getData['sort'] : null;
      $page = !empty($getData['page']) ? $getData['page'] : null;
      $search = !empty($getData['q']) ? $getData['q'] : null;

      return response()->json($userData->getUsers($sort, $page, $search));
    }

    public function get($id=null)
    {
      $userData = new BankUser();
      return response()->json($userData->getUser($id));
    }

    public function insert()
    {
        $postData = $this->request->all();
        $userData = new BankUser();
        $insertUser = $userData->insertUser($postData);
        if($insertUser['success'] == true){
          $accountData = new BankAccount();
          return response()->json($accountData->insertAccount($insertUser['data']['user_id'], $postData['balance']));
        }else{
          return response()->json($insertUser);
        }
    }

    public function update($id=null)
    {
      if ($this->request->isMethod('put')) {
          $putData = $this->request->all();
          $userData = new BankUser();
          return response()->json($userData->updateUser($id, $putData));
      }

      if ($this->request->isMethod('delete')) {
          $userData = new BankUser();
          $deleteUser = $userData->deleteUser($id);
          if($deleteUser['success'] == true){
            $accountData = new BankAccount();
            return response()->json($accountData->deleteAccount($deleteUser['data']['user_id']));
          }else{
            return response()->json($deleteUser);
          }

      }

      return abort(404);
    }
}
