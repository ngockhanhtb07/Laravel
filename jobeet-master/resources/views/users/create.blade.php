@extends('layout')

@section('content')
    <div class="container">
        <h1 class="mt-3">Create User</h1>
        <div class="form-content">
            {{ Form::open(['route' => 'users.store', 'name' => 'form-create', 'class' => 'form-horizontal col-md-5', 'novalidate' => '', 'style' => 'display:inline', 'method' => 'POST']) }}

            @include('users.partials.user_form')

            <div class="form-group">
                {{  Form::submit('Create', ['class' => 'btn btn-primary'])  }}
            </div>
            {{ Form::close() }}

        </div>

    </div>
@endsection