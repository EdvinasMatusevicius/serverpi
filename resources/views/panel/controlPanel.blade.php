@extends('layouts.app')

@section('content')

<a href='/'>Home</a>
<a href="{{ route('showShell', ['project' => 'serverpi'])}}">shell comands</a>
This is controll pannel with info on sites and other server info

@endsection