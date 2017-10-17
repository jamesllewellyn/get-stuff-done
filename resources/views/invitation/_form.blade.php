<h1 class="title has-text-centered">
    You've been invited to team <br>
    {{$invitation->team->name}}
</h1>
<div class="box">
    <div class="content">
        <p>First we just need to create you an account</p>
    </div>

    <form role="form" method="POST" action="{{ route('team.invitation.createUser') }}">
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
                <input id="handle" type="text" class="input" name="handle" value="{{ old('handle') }}">
            </p>
            @if ($errors->has('handle'))
                <p class="help is-danger">{{$errors->first('handle')}}</p>
            @endif
            <p><small>Your handle will be displayed along with your messages in Get Stuff Done.</small></p>
            <label for="password" class="label">Password</label>
            <p class="control">
                <input id="password" type="password" class="input" name="password" value="">
            </p>
            @if ($errors->has('password'))
                <p class="help is-danger">{{$errors->first('password')}}</p>
            @endif
            <input id="invitation_id" type="hidden" class="input hidden" name="invitation_id" value="{{$invitation['id']}}">
            <input id="email" type="hidden" class="input hidden" name="email" value="{{$invitation['email']}}">
            <p class="control">
                <button class="button is-primary" >Submit</button>
            </p>
        </div>
    </form>
</div>