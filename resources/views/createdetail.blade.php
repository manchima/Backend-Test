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
                    <td>{{$loanMasters->id}}</td>
                <tr>
                    <td>Loan Amount:</td>
                    <td>{{$loanMasters->amount}}</td>
                </tr>
                <tr>
                    <td>Loan Term:</td>
                    <td>{{$loanMasters->term}}</td>
                </tr>
                <tr>
                    <td>Interest Rate:</td>
                    <td>{{$loanMasters->interest_rate}}</td>
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
                    <td>{{$loanDetail->payment_date}}</td>
                    <td>{{$loanDetail->payment_amount}}</td>
                    <td>{{$loanDetail->principal}}</td>
                    <td>{{$loanDetail->interest}}</td>
                    <td>{{$loanDetail->balance}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        <div>
            <button type="button" onclick="window.location='{{url('/')}}'">Back</button>
    </div>
@endsection