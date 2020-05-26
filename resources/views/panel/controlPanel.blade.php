@extends('layouts.app')

@section('content')

<a href='/'>Home</a><br>
<a href="{{ route('showShell', ['project' => 'serverpi'])}}">shell comands</a>


@endsection