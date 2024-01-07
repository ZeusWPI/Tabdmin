<nav-bar brand="{{ config('app.name', 'Laravel') }}" url="{{ url()->current() }}" guest="{{ !Auth::check() }}"
         login="{{ route('login') }}" username="{{ optional(Auth::user())->name ?: null }}"
         logout="{{ route('logout') }}" csrf="{{ @csrf_token() }}" welcome="{{ route('welcome') }}"
         home="{{ route('home') }}"></nav-bar>
