@extends('layouts.app')

@section('content')

<form action="{{route('newApplicationCreate')}}"  method="post" class="newApplicationForm">
    @csrf

    <label for="newApplicationName">Enter app name (alphabetic and numeric symbols only)</label>
    <input type="text" class="newApplicationForm__name" name='applicationName' id="newApplicationName" value="{{old('applicationName' ?? '')}}">
    @error('applicationName')
        <div class="alert-danger">
            {{ $message }}
        </div>
    @enderror
    <label for="giturl">Enter your project's clone with HTTPS link from github</label>
<input type="text" class="newApplicationForm__giturl" name="giturl" id="giturl" value="{{old('giturl' ?? '')}}">
@error('giturl')
<div class="alert-danger">
    {{$message}}
</div>
@enderror
    <input type="submit" value="submit">
</form>

<a href='/'>Home</a>



@endsection