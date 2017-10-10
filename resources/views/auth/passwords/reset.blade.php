@extends('layouts.basic')

@section('content')
    <div class="columns is-centered">
        <div class="column is-4">
            <h1 class="title">
                Reset Password
            </h1>
            <div class="box">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="" role="form" method="POST" action="{{ route('password.request') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
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
                                <p class="help is-danger">{{$errors->first('password')}}</p>
                            @endif
                        </p>
                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} field">
                        <label for="password-confirm" class="label">Confirm Password</label>
                        <p class="control">
                            <input id="password-confirm" type="password" class="input" name="password_confirmation" required>
                        @if ($errors->has('password_confirmation'))
                            <p class="help is-danger">{{$errors->first('password_confirmation')}}</p>
                            @endif
                            </p>
                    </div>
                    <p class="control">
                        <button class="button is-primary">Reset Password</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection
