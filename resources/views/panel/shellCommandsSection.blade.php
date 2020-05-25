@extends('layouts.app')

@section('content')

<a href="{{ route('gitpull'.$project, ['project' => $project]) }}">Pull {{$project}} git </a>

@endsection