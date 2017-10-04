@extends('layouts.basic')

@section('content')
    <div class="columns is-centered">
    <div class="column is-5">
        <div class="content">
            <h2 >
                Create New User
            </h2>
            <p class="control">Before we can create you a team we need to create you a user account.</p>
        </div>
        <div class="box">
            <form role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                    <label for="name" class="label">First Name</label>
                    <p class="control">
                        <input id="name" type="text" class="input" name="first_name" value="{{ old('first_name') }}" required autofocus>
                        @if ($errors->has('first_name'))
                         <p class="help is-danger">{{ $errors->first('first_name') }}</p>
                        @endif
                    </p>
                </div>
                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <label for="name" class="label">Last Name</label>
                    <p class="control">
                        <input id="name" type="text" class="input" name="last_name" value="{{ old('last_name') }}" required>
                        @if ($errors->has('last_name'))
                            <p class="help is-danger">{{ $errors->first('last_name') }}</p>
                        @endif
                        </p>
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} field">
                    <label for="email" class="label">E-Mail Address</label>
                    <p class="control">
                        <input id="email" type="email" class="input" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <p class="help is-danger">{{ $errors->first('email') }}</p>
                        @endif
                    </p>
                </div>
                <div class="form-group{{ $errors->has('handle') ? ' has-error' : '' }} field">
                    <label for="handle" class="label">Handle</label>
                    <p class="control">
                        <input id="handle" type="text" class="input" name="handle" value="{{ old('handle') }}" required>
                        @if ($errors->has('handle'))
                            <span class="help is-danger">
                                <strong>{{ $errors->first('handle') }}</strong>
                            </span>
                        @endif
                    </p>
                    <p><small>Your hand will be displayed along with your messages in Get Stuff Done.</small></p>
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
                    @if ($errors->has('password'))
                        <p class="help is-danger">{{ $errors->first('password') }}</p>
                        @endif
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
