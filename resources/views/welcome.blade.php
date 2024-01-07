@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="height: 80vh;">
        <div class="text-center">
            <h1><span>Tab</span><span class="brand-text-right">dmin</span></h1>
            <br>
            <p>Yes we have to drink. Yes we have to pay. But we also have to put money on our <a
                    href="https://tab.zeus.gent">Tab</a>.</p>
            <p>This makes everyone's life, especially that of the treasurer, easier by automatically handling
                transactions to Zeus.</p>
            <a type="button" class="btn btn-zeus btn-lg mt-4" data-mdb-ripple-init href="{{ route('login') }}">Login with Zeus WPI</a>
        </div>
    </div>
@endsection
