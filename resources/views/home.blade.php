@extends('layouts.app')

@section('content')
@auth('web')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    {{-- per loopa isprintint esamus projektus su project var --}}
                    {{-- @isset($list) --}}
                        <div class="links">
                            @foreach ($list as $application)
                            
                            
                                <a href="{{route('panel',['project'=>$application['slug']])}}">{{$application['applicationName']}} Control panel</a><br>

                            @endforeach
                        </div><br>
                    {{-- @endisset --}}
                    @if (Route::has('newApplication'))
                    <div class="links">
                        <a href="{{route('newApplication')}}">Add new project</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection
