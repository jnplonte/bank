<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use App\BankAccount;

class BankTransaction extends Model
{
    //datbase table
    protected $table = 'transaction';

    //fillable information
    protected $fillable = ['user_id', 'account_id', 'transfer_id', 'amount', 'type', 'log'];

    //fillable information
    protected $logInfo = ['1' => 'deposit %s from user id %d with account no. %d',
                          '2' => 'withdraw %s from user id %d with account no. %d',
                          '3' => 'transfer %s from user id %d with account no. %d to account no. %d'
                        ];

    public function __construct(){

    }

    public function insertTransaction($id = null, $amount = null, $type = null, $transfer_id = null){
      if(!empty($id) && !empty($amount) && !empty($type)){
        $bankAccount = new BankAccount();
        $accountInfo = $bankAccount->getAccountInfo($id);
        if(!empty($accountInfo)){
          $arr['user_id'] = $accountInfo->user_id;
          $arr['account_id'] = $id;
          $arr['amount'] = $amount;
          $arr['type'] = $type;
          $arr['created_at'] =  date('Y-m-d G:i:s');

          if($type == 3 && !empty($transfer_id)){
            $accountTransferInfo = $bankAccount->getAccountInfo($transfer_id);
            $arr['transfer_user_id'] = $accountInfo->user_id;
            $arr['log'] = sprintf($this->logInfo[$type], $amount, $accountInfo->user_id, $id, $transfer_id);
          }else{
            $arr['log'] = sprintf($this->logInfo[$type], $amount, $accountInfo->user_id, $id);
          }

          DB::table($this->table)->insert($arr);
        }
      }
    }

    public function getTransactionAmount($id = null){
      return DB::table($this->table)
        ->select(DB::raw('SUM(amount) as total_amount'))
        ->where('account_id', $id)
        ->where('created_at', 'like', date('Y-m-d').'%')
        ->first();
    }
}
