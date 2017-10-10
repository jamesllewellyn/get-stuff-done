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
                <form class="" role="form" method="POST" action="{{ route('password.email') }}">
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
                    <p class="control">
                        <button class="button is-primary">Submit</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection
