@extends('layouts.app')

@section('content')

<a href="{{ route('composer_install', ['project' => $project]) }}">{{$project}} composer install </a><br>
<a href="{{ route('git_pull', ['project' => $project]) }}">Pull {{$project}} git </a><br>
<a href="{{ route('db_seed', ['project' => $project]) }}">Seed {{$project}}'s' database </a><br>
<form action="{{ route('db_seed', ['project' => $project]) }}" method="post">
    @csrf
    <input type="submit" value="Seed {{$project}}'s database from seeder class:">
    <input type="text" name='seedClass'>
</form>
<form action="{{ route('custom_artisan', ['project' => $project]) }}" method="post">
    @csrf
    <input type="submit" value="run artisan command. php artisan ">
    <input type="text" name='artisanCmd'>
    @error('artisanCmd')
        {{$message}}
    @enderror
    @error('project')
    {{$message}}
@enderror
</form>
@endsection