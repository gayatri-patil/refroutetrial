@extends('layouts.app')

@section('content')
<div class="container">
    {{ $user->name }}
    <hr>
    @if(Auth::user()->isNotTheUser($user))
        @if(Auth::user()->isFollowing($user))
            <a href="{{ route('users.unfollow', $user) }} " class="btn btn-primary">Unfollow</a>
        @else
        <a href="{{ route('users.follow', $user) }}" class="btn btn-success">Follow</a>
        @endif
    @endif

</div>
@endsection
