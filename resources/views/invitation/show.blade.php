@extends('layouts.basic')

@section('content')
    <div id="app">
        <div class="columns is-centered">
            <div class="column is-5">
                @isset($inviteError)
                    @include("invitation._error")
                @endisset
                @empty($inviteError)
                    @include("invitation._form")
                @endempty
            </div>
        </div>
    </div>
@endsection