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
                    @if (Route::has('panel'))
                    {{-- per loopa isprintint esamus projektus su project var --}}
                    <div class="links">
                        <a href="{{route('panel',['project'=>'serverpi'])}}">Project Control panel</a>
                    </div><br>
                    <div class="links">
                        <a href="{{route('newApp')}}">Add new project</a>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection
