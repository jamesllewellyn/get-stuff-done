@extends('layouts.basic')

@section('content')
    <div class="columns is-vcentered">
        <div class="column is-4 is-offset-4">
            <h1 class="title">
                Register
            </h1>
            <div class="box">
                <form role="form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="label">Name</label>
                        <p class="control">
                            <input id="name" type="text" class="input" name="name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <p class="help is-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </p>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} field">
                        <label for="email" class="label">E-Mail Address</label>
                        <p class="control">
                            <input id="email" type="email" class="input" name="email" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help is-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </p>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} field">
                        <label for="password" class="label">Password</label>
                        <p class="control">
                            <input id="password" type="password" class="input" name="password" required>
                            @if ($errors->has('password'))
                                <p class="help is-danger">{{ $errors->first('password') }}</p>
                            @endif
                        </p>
                    </div>
                    <div class="field">
                        <label for="password-confirm" class="label">Confirm Password</label>

                        <p class="control">
                            <input id="password-confirm" type="password" class="input" name="password_confirmation" required>
                        </p>
                    </div>
                    <p class="control">
                        <button class="button is-primary">Submit</button>
                    </p>
                </form>
            </div>
        </div>
    </div>

@endsection