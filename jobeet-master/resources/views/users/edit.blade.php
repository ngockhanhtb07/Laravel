@extends('layout')

@section('content')

    <div class="col-lg-4 col-lg-offset-4">
        <h1>UPDATE USER</h1>
        {{ Form::model($user, ['route' => ['users.update', $user->id]]) }}
        <!-- name -->
            {{ Form::hidden('id', $user->id) }}

            @include('users.partials.user_form');

            <div class="form-group">
                {{ Form::submit('Update User!', ['class' => 'btn btn-primary']) }}
            </div>
        {{ Form::close() }}
    </div>

@endsection

