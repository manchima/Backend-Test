<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoanMaster;
use App\LoanDetail;

class LoanController extends Controller
{
    //
    public function create()
    {
        $years = [];
        for ($year=2017; $year <= 2050; $year++){
            $years[$year] = $year;
        }
        $months = [];
        for ($month=0; $month < 12; $month++){
            $name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            $months[$month] = $name[$month];
        }
        return view('create', ['years'=>$years, 'months' => $months]);
    }

    public function index()
    {
//        $loanMasters = LoanMaster::query()->orderBy('id', 'desc')->paginate(3);
        $loanMasters = LoanMaster::selectRaw("format(id, 0) as id, id as hide_id
                    , concat(format(amount, 2), ' ฿') as amount
                    , concat(format(term, 0), ' Years') as term
                    , concat(format(interest_rate, 2), '%') as interest_rate
                    , created_at
                ")
            ->orderBy('hide_id', 'desc')->paginate(15);
        return view('index', compact('loanMasters'));
    }

    public function view($id){
        $loanMaster = LoanMaster::where('id', $id)
            ->selectRaw("format(id, 0) as id
                , concat(format(amount, 2), ' ฿') as amount
                , concat(format(term, 0), ' Years') as term
                , concat(format(interest_rate, 2), '%') as interest_rate
                , created_at
            ")
            ->get();


        $loanDetails = LoanDetail::where('master_id', $id)
            ->selectRaw("id, master_id, payment_no
                , format(payment_amount, 2) as payment_amount
                , format(principal, 2) as principal
                , format(interest, 2) as interest
                , format(balance, 2) as balance
                , created_at, updated_at
                , concat(DATE_FORMAT(payment_date, '%b'), '-', DATE_FORMAT(payment_date, '%Y')) as payment_date
                ")
            ->get();

        $loanMasters = $loanMaster[0];
        return view('createdetail',compact('loanMasters', 'loanDetails'));
    }

    public function edit($id)
    {
        $years = [];
        for ($year=2017; $year <= 2050; $year++){
            $years[$year] = $year;
        }
        $months = [];
        for ($month=0; $month < 12; $month++){
            $name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            $months[$month] = $name[$month];
        }

//        $loanMaster2 = LoanMaster::find($id);
        $loanMaster2 = LoanMaster::where('id',$id)
            ->selectRaw("id, amount, term, interest_rate, remember_token, created_at, updated_at 
	            , DATE_FORMAT(start_date, '%b') as start_month
	            , DATE_FORMAT(start_date, '%Y') as start_year
            ")
            ->get();
        $loanMaster = $loanMaster2[0];
        return view('edit', ['years'=>$years, 'months' => $months], compact('loanMaster'))
            ->with('success', 'The loan has been updated successfully.');
    }

    public function destroy($id)
    {
        LoanDetail::where('master_id', $id)->delete();
        LoanMaster::find($id)->delete();

        return redirect('/')->with('success', 'Loan ID #'.$id.' has been deleted!!');
    }

    public function store(Request $request)
    {
        $name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $loanMaster = new LoanMaster();
        $this->validate($request, [
            'loan_amount'=>'numeric|min:1000|max:100000000',
            'loan_term'=> 'numeric|min:1|max:50',
            'interest_rate'=> 'numeric|min:1|max:36|regex:/^-?[0-9]{1,2}+(?:\.[0-9]{0,2})?$/'
        ]);


        $balance = $request['loan_amount'];
        $interest_rate = $request['interest_rate'];
        $interest_rate_cal = $interest_rate/100;
        $term = $request['loan_term'];
        $year = $request['year'];
        $month = str_pad(array_search($request['month'], $name)+1,2,"0", STR_PAD_LEFT);
        $fulldate = $year."-".$month."-01";

        $data = [
            'amount'=>$balance,
            'term'=> $term,
            'interest_rate'=> $interest_rate,
            'start_date' => $fulldate
        ];
        $loanMaster->saveMaster($data);
        $id = $loanMaster['id'];

        $payment_amount = ($balance*($interest_rate_cal/12))/(1-pow(1+($interest_rate_cal/12), (-12*$term)));
        for ($i=1; $i<=$term*12; $i++){
            $loanDetail = new LoanDetail();
            $interest = ($balance * $interest_rate_cal)/12;
            $principal = $payment_amount - $interest;
            if ($i == $term*12){
                $principal = $balance;
                $balance = 0;
            }else{
                $balance = $balance - $principal;
            }
            $date = date("Y-m-d", strtotime("+".($i-1)."months", strtotime($fulldate)));
            $detailData = [
                'master_id' => $id,
                'payment_no' => $i,
                'payment_date' => $date,
                'payment_amount' => $payment_amount,
                'principal' => $principal,
                'interest' => $interest,
                'balance' => $balance
            ];
            $loanDetail->saveDetail($detailData);
        }
            $loanMaster = LoanMaster::where('id', $id)
                ->selectRaw("format(id, 0) as id
                    , concat(format(amount, 2), ' ฿') as amount
                    , concat(format(term, 0), ' Years') as term
                    , concat(format(interest_rate, 2), '%') as interest_rate
                    , created_at
                ")
                ->get();
            $loanDetails = LoanDetail::where('master_id', $id)
                ->selectRaw("id, master_id, payment_no
                    , format(payment_amount, 2) as payment_amount
                    , format(principal, 2) as principal
                    , format(interest, 2) as interest
                    , format(balance, 2) as balance
                    , created_at, updated_at
                    , concat(DATE_FORMAT(payment_date, '%b'), '-', DATE_FORMAT(payment_date, '%Y')) as payment_date
                    ")
                ->get();
            $loanMasters = $loanMaster[0];
        return view('createdetail',compact('loanMasters', 'loanDetails'))->with('success', 'The loan has been created successfully.');
    }

    public function update(Request $request, $id)
    {
        $name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $loanMaster = new LoanMaster();
        $this->validate($request, [
            'loan_amount'=>'numeric|min:1000|max:100000000',
            'loan_term'=> 'numeric|min:1|max:50',
            'interest_rate'=> 'numeric|min:1|max:36|regex:/^-?[0-9]{1,2}+(?:\.[0-9]{0,2})?$/'
        ]);


        $balance = $request['loan_amount'];
        $interest_rate = $request['interest_rate'];
        $interest_rate_cal = $interest_rate/100;
        $term = $request['loan_term'];
        $year = $request['year'];
        $month = str_pad(array_search($request['month'], $name)+1,2,"0", STR_PAD_LEFT);
        $fulldate = $year."-".$month."-01";

        $data = [
            'amount'=>$balance,
            'term'=> $term,
            'interest_rate'=> $interest_rate,
            'start_date' => $fulldate
        ];
        $loanMaster->updateMaster($data, $id);

        $loanDetail = LoanDetail::where('master_id', $id);
        $loanDetail->delete();

        $payment_amount = ($balance*($interest_rate_cal/12))/(1-pow(1+($interest_rate_cal/12), (-12*$term)));
        for ($i=1; $i<=$term*12; $i++){
            $loanDetail = new LoanDetail();
            $interest = ($balance * $interest_rate_cal)/12;
            $principal = $payment_amount - $interest;
            if ($i == $term*12){
                $principal = $balance;
                $balance = 0;
            }else{
                $balance = $balance - $principal;
            }

            $date = date("Y-m-d", strtotime("+".($i-1)."months", strtotime($fulldate)));
            $detailData = [
                'master_id' => $id,
                'payment_no' => $i,
                'payment_date' => $date,
                'payment_amount' => $payment_amount,
                'principal' => $principal,
                'interest' => $interest,
                'balance' => $balance
            ];
            $loanDetail->saveDetail($detailData);
        }
        $loanMaster = LoanMaster::where('id', $id)
            ->selectRaw("format(id, 0) as id
                    , concat(format(amount, 2), ' ฿') as amount
                    , concat(format(term, 0), ' Years') as term
                    , concat(format(interest_rate, 2), '%') as interest_rate
                    , created_at
                ")
            ->get();
        $loanDetails = LoanDetail::where('master_id', $id)
            ->selectRaw("id, master_id, payment_no
                    , format(payment_amount, 2) as payment_amount
                    , format(principal, 2) as principal
                    , format(interest, 2) as interest
                    , format(balance, 2) as balance
                    , created_at, updated_at
                    , concat(DATE_FORMAT(payment_date, '%b'), '-', DATE_FORMAT(payment_date, '%Y')) as payment_date
                    ")
            ->get();
        $loanMasters = $loanMaster[0];
        return view('createdetail',compact('loanMasters', 'loanDetails'))->with('success', 'The loan has been updated successfully.');
    }


}
