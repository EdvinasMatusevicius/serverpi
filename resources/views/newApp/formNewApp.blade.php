@extends('layouts.app')

@section('content')

<form action="{{route('newAppCreate')}}"  method="post" class="newAppForm">
    @csrf

    <label for="newAppName">Enter app name (alphabetic and numeric symbols only)</label>
    <input type="text" class="newAppForm__name" name='name' id="newAppName">
    <label for="newAppType">Select type of app</label>
    <select name="type" id="newAppType">
        <option value="1">Static files</option>
        <option value="2">NodeJS</option>
        <option value="3">PHP(7.3)/Laravel</option>
    </select>
    <input type="submit" value="submit">
</form>

<a href='/'>Home</a>



@endsection