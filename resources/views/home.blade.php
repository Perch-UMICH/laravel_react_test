@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>You are logged in!</p>
                    <p>google_id: {{ Auth::user()->google_id }}</p>
                    <p>Email: {{ Auth::user()->email }}</p>
                    <a class='btn btn-primary' href={{url('logout')}}>Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
