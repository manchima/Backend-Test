<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanMaster extends Model
{
    protected $fillable = ['amount', 'term', 'interest_rate', 'start_date'];

    public function saveMaster($data)
    {
        $this->amount = $data['amount'];
        $this->term = $data['term'];
        $this->interest_rate = $data['interest_rate'];
        $this->start_date = $data['start_date'];
        $this->save();
        return $this;
    }

    public function updateMaster($data, $id)
    {
        $loanMaster = $this->find($id);
        $loanMaster->amount = $data['amount'];
        $loanMaster->term = $data['term'];
        $loanMaster->interest_rate = $data['interest_rate'];
        $loanMaster->start_date = $data['start_date'];
        $loanMaster->save();
        return $loanMaster;
    }
}
