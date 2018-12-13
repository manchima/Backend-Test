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

        <h1>All Loans</h1>
        <div class="container">
            <table class="table table-striped">
                <thead class="font-weight-bold">
                <tr>
                    <td>ID</td>
                    <td>Loan Amount</td>
                    <td>Loan Term</td>
                    <td>Interest Rate</td>
                    <td>Created at</td>
                    <td>Edit</td>
                </tr>
                </thead>
                <tbody>
                <a href="{{url('/create/loan')}}" class="btn btn-primary">Add New Loan</a>
                @foreach($loanMasters as $loanMaster)
                    <tr>
                        <td>{{number_format($loanMaster->id, 0)}}</td>
                        <td>{{number_format($loanMaster->amount, 2)}}฿</td>
                        <td>{{number_format($loanMaster->term, 0)}} Years</td>
                        <td>{{number_format($loanMaster->interest_rate, 2)}}%</td>
                        <td>{{$loanMaster->created_at}}</td>
                        <td class="form-inline"><a href="{{action('LoanController@view',$loanMaster->id)}}" class="btn btn-primary">View</a>
                        <a href="{{action('LoanController@edit',$loanMaster->id)}}" class="btn btn-success">Edit</a>
                            <form action="{{action('LoanController@destroy', $loanMaster->id)}}" method="post">
                                {{csrf_field()}}
                                <input name="_method" type="hidden" value="DELETE">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $loanMasters->links() }}
        <div>
@endsection