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

    private function insertLog($id = null, $amount = null, $type = null){
      $bankTransaction = new BankTransaction();
      $bankTransaction->insertTransaction($id, $amount, $type);
    }
}
