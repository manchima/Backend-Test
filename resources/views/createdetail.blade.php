@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @elseif(!empty($success))
            <div class="alert alert-success">
                {{$success}}
            </div>
        @endif

        <div class="container">
            <table class="border-0">
                <thead>
                <h1>Loan Details</h1>
                </thead>
                <tbody>
                <tr>
                    <td width="200">ID:</td>
                    <td>{{number_format($loanMasters->id, 0)}}</td>
                <tr>
                    <td>Loan Amount:</td>
                    <td>{{number_format($loanMasters->amount, 2)}}฿</td>
                </tr>
                <tr>
                    <td>Loan Term:</td>
                    <td>{{number_format($loanMasters->term, 0)}} Years</td>
                </tr>
                <tr>
                    <td>Interest Rate:</td>
                    <td>{{number_format($loanMasters->interest_rate, 2)}}%</td>
                </tr>
                <tr>
                    <td>Created at:</td>
                    <td>{{$loanMasters->created_at}}</td>
                </tr>
                </tbody>
            </table>
        <div>
        <br>
        <button type="button" onclick="window.location='{{url('/')}}'">Back</button>

         <br><br>
        {{--Repayment Schedules--}}
        <h1>Repayment Schedules</h1>
        <div class="container">
            <table class="table table-striped">
                <thead class="font-weight-bold">
                <tr>
                    <td>Payment No</td>
                    <td>Date</td>
                    <td>Payment Amount</td>
                    <td>Principal</td>
                    <td>Interest</td>
                    <td>Balance</td>
                </tr>
                </thead>
                <tbody>

                @foreach($loanDetails as $loanDetail)
                <tr>
                    <td>{{$loanDetail->payment_no}}</td>
                    <td>{{\Carbon\Carbon::parse($loanDetail->payment_date)->format('Y-m')}}</td>
                    <td>{{number_format($loanDetail->payment_amount, 2)}}</td>
                    <td>{{number_format($loanDetail->principal, 2)}}</td>
                    <td>{{number_format($loanDetail->interest, 2)}}</td>
                    <td>{{number_format($loanDetail->balance, 2)}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        <div>
            <button type="button" onclick="window.location='{{url('/')}}'">Back</button>
    </div>
@endsection