<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\BankAccount;
use App\BankTransaction;

class AccountController extends Controller
{
    private $request;

    public function __construct(Request $request){
      $this->request = $request;
    }

    public function withdraw($id=null)
    {
      $putData = $this->request->all();
      if(!empty($putData['amount'])){
        $bankAccount = new BankAccount();
        $withdrawAccount = $bankAccount->withdrawAccount($id, $putData['amount']);
        if($withdrawAccount['status'] == 'success'){
          $this->insertLog($id, $putData['amount'], 1);
        }
        return response()->json($withdrawAccount);
      }
      return abort(404);
    }

    public function deposit($id=null)
    {
      $putData = $this->request->all();
      if(!empty($putData['amount'])){
        $bankAccount = new BankAccount();
        $depositAccount = $bankAccount->depositAccount($id, $putData['amount']);
        if($depositAccount['status'] == 'success'){
          $this->insertLog($id, $putData['amount'], 2);
        }
        return response()->json($depositAccount);
      }
      return abort(404);
    }

    public function transfer($id=null)
    {
      $putData = $this->request->all();
      if(!empty($putData['amount']) && !empty($putData['transfer_id']) && $id != $putData['transfer_id']){
        $bankAccount = new BankAccount();
        $transferAccount = $bankAccount->transferAccount($id, $putData['amount'], $putData['transfer_id']);
        if($transferAccount['status'] == 'success'){
          $this->insertLog($id, $putData['amount'], 3, $putData['transfer_id']);
        }
        return response()->json($transferAccount);
      }
      return abort(404);
    }

    public function manage($id=null)
    {
      if ($this->request->isMethod('post')) {
        $postData = $this->request->all();
        if(!empty($postData['balance'])){
          $bankAccount = new BankAccount();
          return response()->json($bankAccount->insertAccount($id, $postData['balance']));
        }
      }

      if ($this->request->isMethod('delete')) {
        $deleteData = $this->request->all();
        if(!empty($deleteData['account_id'])){
          $bankAccount = new BankAccount();
          return response()->json($bankAccount->deleteAccount($id, $deleteData['account_id']));
        }
      }
      return abort(404);
    }

    //function on insert log to identify the limit trasfer
    private function insertLog($id = null, $amount = null, $type = null, $transfer_id = null){
      $bankTransaction = new BankTransaction();
      $bankTransaction->insertTransaction($id, $amount, $type, $transfer_id);
    }
}
