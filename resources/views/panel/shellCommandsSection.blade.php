@extends('layouts.app')

@section('content')

<a href="{{ route('composer_install', ['project' => $project]) }}">{{$project}} composer install </a><br>
<a href="{{ route('npm_install', ['project' => $project]) }}">{{$project}} npm install </a><br>
<a href="{{ route('copy_env_example', ['project' => $project]) }}">copy env values</a><br>
<a href="{{ route('create_env_file', ['project' => $project]) }}">create empty .env file</a><br>
<a href="{{ route('app_key_generate', ['project' => $project]) }}">generate app's key</a><br>
<textarea name="envVars" id="envVars" cols="30" rows="2">{{$appkey ?? '' }}</textarea>
<a href="{{ route('app_storage_link', ['project' => $project]) }}">link storage (FILESYSTEM_DRIVER must be set in env if not default)</a><br>
<a href="{{ route('git_pull', ['project' => $project]) }}">Pull {{$project}} git </a><br>
<a href="{{ route('dump_autoload', ['project' => $project]) }}">Run artisan dump autoload </a><br>
<a href="{{ route('db_migrate', ['project' => $project]) }}">migrate {{$project}}'s' database </a><br>
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
<a href="{{ route('get_env_values', ['project' => $project]) }}">get env values</a><br>
<form action="{{ route('write_to_env_file', ['project' => $project]) }}" method="post">
    @csrf
    <input type="submit" value="save enviroment values to to env">
    <textarea name="envVars" id="envVars" cols="30" rows="10">{{$values ?? '' }}</textarea>

</form>
<form action="{{ route('nginx_config', ['project' => $project]) }}" method="post">
    @csrf
    <input type="submit" value="initiate server configuration">
    <input type="text" name='path'>

</form>
<form action="{{ route('db_create', ['project' => $project]) }}" method="post">
    @csrf
    <input type="submit" value="create database and user">
    <input type="password" name='password' placeholder="password">
    @error('password') 
    {{-- vienu metu tures but tik vienas psw jei nera sukurtos db, db_create jei yra db_custom_query --}}
        {{$message}}
    @enderror
</form>
<form action="{{ route('db_custom_query', ['project' => $project]) }}" method="post">
    @csrf
    <input type="submit" value="run mysql query">
    <input type="password" name='password'  placeholder="password">
    <input type="text" name='customquery'  placeholder="custom database query">


</form>
@endsection