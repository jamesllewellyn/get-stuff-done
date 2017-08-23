@extends('layouts.basic')

@section('content')
    <div id="app">
        <div class="columns is-vcentered">
            <div class="column is-4 is-offset-4">
                <h1 class="title">
                    Create Account
                </h1>
                <div class="box">
                    <form role="form" method="POST" action="{{ route('user.invite') }}">
                        {{ csrf_field() }}
                        <div class="">
                            <label for="first_name" class="label">First name</label>
                            <p class="control">
                                <input id="first_name" type="text" class="input" name="first_name" value="{{ old('first_name') }}" required autofocus>
                             </p>
                            @if ($errors->has('first_name'))
                                <p class="help is-danger">{{$errors->first('first_name')}}</p>
                            @endif
                            <label for="last_name" class="label">Last name</label>
                            <p class="control">
                                <input id="last_name" type="text" class="input" name="last_name" value="{{ old('last_name') }}">
                            </p>
                            @if ($errors->has('last_name'))
                                <p class="help is-danger">{{$errors->first('last_name')}}</p>
                            @endif
                            <label for="handle" class="label">Handle</label>
                            <p class="control">
                                <input id="handle" type="text" class="input" name="handle" value="" value="{{ old('handle') }}">
                            </p>
                            @if ($errors->has('handle'))
                                <p class="help is-danger">{{$errors->first('handle')}}</p>
                            @endif
                            <label for="password" class="label">Password</label>
                            <p class="control">
                                <input id="password" type="password" class="input" name="password" value="" v-model="password">
                            </p>
                            @if ($errors->has('password'))
                                <p class="help is-danger">{{$errors->first('password')}}</p>
                            @endif
                            <p class="control">
                                <button class="button is-primary" >Submit</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{--<script src="{{ asset('js/sign-up.js') }}"></script>--}}
@endsection