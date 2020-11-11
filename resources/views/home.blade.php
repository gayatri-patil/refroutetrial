@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" height="80" >
            <hr>
        <h2 class="text-primary">{{ Auth::user()->name }}</h2>
            <div class="col-md-2">
                <h3>Following</h3>

                @foreach($following as $user)
                    <p><a href="{{ route('users', $user) }}" class="btn btn-primary">{{ $user->name }}</a></p>
                @endforeach

                <hr>
                <h3>Followers</h3>

                @foreach($followers as $user)
                    <p><a href="{{ route('users', $user) }}" class="btn btn-success">{{ $user->name }}</a></p>
                @endforeach
            </div>
        </div>
        <div class="col-md-10">
            <div id="root"></div>
        </div>
    </div>
</div>
@endsection
