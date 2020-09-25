@extends('components.app')
@section('content')
    <script>
        document.getElementById('login').className = 'nav-item active';
    </script>
    <div class="n-designs">
        <div class="container-fluid">
            <div class="form-box ">
                <div class="button-box">
                    <h1>Log In </h1>
                </div>
                <form method="POST" class="input-group" action="{{ route('login') }}">
                    @csrf
                    <input class="input-field" placeholder="PhoneNumber" type="number" name="PhoneNumber" :value="old('PhoneNumber')" required autofocus>
                    <input class="input-field" placeholder="Password" type="password" name="password" required autocomplete="current-password">
                    <div class="fp w-100 mt-3">
                        <a href="{{route('password.request')}}" >Forgot password?</a>
                    </div>
                    <div class="fp w-100 mt-3">
                        <a href="{{route('register')}}" >Register Now</a>
                    </div>
                    <button type="submit" class="submit-btn mt-4">Log In</button>
                </form>
            </div>
        </div>
    </div>
@endsection
