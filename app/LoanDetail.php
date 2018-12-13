<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    protected $fillable = ['master_id', 'payment_no', 'payment_date', 'payment_amount', 'principal', 'interest', 'balance'];
    public function saveDetail($data)
    {
        $this->master_id = $data['master_id'];
        $this->payment_no = $data['payment_no'];
        $this->payment_date = $data['payment_date'];
        $this->payment_amount = $data['payment_amount'];
        $this->principal = $data['principal'];
        $this->interest = $data['interest'];
        $this->balance = $data['balance'];
        $this->save();
        return $this->id;
    }

    public static function getLoanDetailDataById($id){
        $loanDetails = LoanDetail::where('master_id', $id)->get();
        return $loanDetails;
    }
}
