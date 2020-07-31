@extends('layout')

@section('content')
    <div class="container col-lg-4 col-lg-offset-4">
        <h1>Apply</h1>
        {{ Form::open(['method' => 'post', 'route' => ['users.storeApplication']]) }}
        <p><strong>User Name : </strong>{{ $user->name }}</p>
        <p><strong>Job :</strong> {{ $job->id }}</p>
        {{ Form::hidden('job_id', $job->id) }}
        {{ Form::hidden('user_id', $user->id) }}
        {{ Form::textarea('message', null, ['class' => 'form-control']) }}
        <br>
        {{ Form::submit('Apply', ['class' => 'btn btn-primary']) }}
        {{ Form::close() }}
    </div>
@endsection