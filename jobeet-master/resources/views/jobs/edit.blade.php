@extends('layout')

@section('content')
    <div class="col-md-5 col-md-offset-3">
        <h1>Update Post</h1>
        {{ Form::model($job, ['method' => 'post', 'route' => ['jobs.update', $job->id]]) }}
            <!-- category -->

            @include('jobs.partials.job_form')

            <br>
            {{ Form::submit('Update job!', ['class' => 'btn btn-primary']) }}
        {{ Form::close() }}
    </div>

@endsection

