@extends('layouts.app')

@section('content')
    <div class="container">
        <transactions-component :transactions-prop="{{ $transactions }}"></transactions-component>
    </div>
@endsection
