<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\BankAccount;

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
        return response()->json($bankAccount->withdrawAccount($id, $putData['amount']));
      }
      return abort(404);
    }

    public function deposit($id=null)
    {
      $putData = $this->request->all();
      if(!empty($putData['amount'])){
        $bankAccount = new BankAccount();
        return response()->json($bankAccount->depositAccount($id, $putData['amount']));
      }
      return abort(404);
    }
}
