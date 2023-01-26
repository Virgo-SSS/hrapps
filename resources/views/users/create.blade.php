@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li><span>Create</span></li>
@endsection

@section('content')
<h1>iini halamana create user</h1>
@endsection
