@extends('layout')
@section('content')
    <div class="container">
        <h1 class="mt-3">Post new job</h1>
        <div class="form-content">
            {{ Form::open(['route' => 'jobs.store', 'name' => 'form-create', 'class' => 'form-horizontal col-md-5', 'method' => 'POST']) }}

            @include('jobs.partials.job_form')

            <div class="form-group">
                {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection