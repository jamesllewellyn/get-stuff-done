@extends('layouts.basic')

@section('content')
    <div id="app">
        <div class="columns is-vcentered">
            <transition name="fade" mode="out-in">
                <router-view ></router-view>
            </transition>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/sign-up.js') }}"></script>
@endsection