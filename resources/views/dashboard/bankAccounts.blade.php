@extends('layouts.app')

@section('content')
    <div class="container">
        <connect-bank-component :banks="{{ json_encode($banks) }}"
                                :accounts-prop="{{ json_encode($accounts) }}"></connect-bank-component>
    </div>
@endsection
