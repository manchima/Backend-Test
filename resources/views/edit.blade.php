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
        @elseif(!empty(session('success')))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
        @endif

        <h1>Edit Loan</h1>
        <div class="row">
            <form method="post" action="{{action('LoanController@update', $loanMaster->id)}}" >
                <input type="hidden" value="{{csrf_token()}}" name="_token" />
                <input name="_method" type="hidden" value="PATCH">
                <table class="border-0">
                    <tbody>
                        <tr>
                            <td width="150">Loan Amount:</td>
                            <td>
                                <input type="text" size="39%" name="loan_amount" value="{{$loanMaster->amount}}"/>
                                <input type="text" size="1%" style="text-align: center" readonly="readonly" disabled="disabled" value="฿"/>
                            </td>
                        </tr>
                        <tr>
                            <td >Loan Term:</td>
                            <td>
                                <input type="text" size="37%" name="loan_term" value="{{$loanMaster->term}}"/>
                                <input type="text" size="3%" style="text-align: center" readonly="readonly" disabled="disabled" value="Years"/>
                            </td>
                        </tr>
                        <tr>
                            <td >Interest Rate:</td>
                            <td>
                                <input type="text" size="39%" name="interest_rate" value="{{$loanMaster->interest_rate}}"/>
                                <input type="text" size="1%" style="text-align: center" readonly="readonly" disabled="disabled" value="%"/>
                            </td>
                        </tr>
                        <tr>
                            <td >Start Date:</td>
                            <td><select name="month" style="width: 165px">
                                    @foreach($months as $month)
                                        @if($month == $loanMaster->start_month)
                                            <option value="{{$month}}" selected="selected">{{$month}}</option>
                                        @else
                                            <option value="{{$month}}">{{$month}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <select name="year" style="width: 165px">
                                    @foreach($years as $year)
                                        @if($year == $loanMaster->start_year)
                                            <option value="{{$year}}" selected="selected">{{$year}}</option>
                                        @else
                                            <option value="{{$year}}">{{$year}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                <button type="button" onclick="window.location='{{url('/')}}'">Back</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection