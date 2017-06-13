@extends('layouts.basic')

@section('content')

    <div class="columns is-vcentered">
        <div class="column is-4 is-offset-4">
            <h1 class="title">
                Login
            </h1>
            <div class="box">
                <form class="" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} field">
                        <label for="email" class="label">E-Mail Address</label>
                        <p class="control">
                            <input id="email" type="email" class="input" name="email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <p class="help is-danger">{{$errors->first('email')}}</p>
                            @endif
                        </p>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} field">
                        <label for="password" class="label">Password</label>

                        <p class="control">
                            <input id="password" type="password" class="input" name="password" required>

                            @if ($errors->has('password'))
                                <p class="help is-danger">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </p>
                    </div>
                    <div class="field">
                        <p class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </p>
                    </div>
                    <p class="control">
                        <button class="button is-primary">Submit</button>
                    </p>
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
