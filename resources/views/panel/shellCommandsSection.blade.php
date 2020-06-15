@extends('layouts.app')

@section('content')

<a href="{{ route('composer_install', ['project' => $project]) }}">{{$project}} composer install </a><br>
<a href="{{ route('git_pull', ['project' => $project]) }}">Pull {{$project}} git </a><br>
<a href="{{ route('db_seed', ['project' => $project]) }}">Seed {{$project}}'s' database </a><br>
<a href="{{ route('get_env_values', ['project' => $project]) }}">get env values</a><br>
<a href="{{ route('copy_env_example', ['project' => $project]) }}">copy env values</a><br>
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
<form action="{{ route('write_to_env_file', ['project' => $project]) }}" method="post">
    @csrf
    <input type="submit" value="save enviroment values to to env">
    <textarea name="envVars" id="envVars" cols="30" rows="10">{{$valuess ?? '' }}</textarea>

</form>
@endsection