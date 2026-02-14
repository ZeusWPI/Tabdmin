@extends('layouts.app')

@section('content')
    <div class="container">
        <iban-usernames-component :iban-usernames-prop="{{ $ibanUsernames }}"></iban-usernames-component>
    </div>
@endsection
