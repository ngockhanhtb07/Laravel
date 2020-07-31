@extends('layout')

@section('title')
    Index
@endsection

@section('content')
    <div class="container">
        <h1>Jobeet</h1>
        <div class="group-link">
            <ul>
                <li><a href="{{ route('jobs.create') }}">Post job</a></li>
                <li><a href="{{ route('jobs.index') }}">Find job</a></li>
                <li><a href="{{ route('users.apply') }}">User</a></li>
            </ul>
        </div>

        <h1>New Jobs</h1>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Posted</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>b
            @foreach($jobs as $key => $value)
                <tr>
                    <td scope="row">IT Job</td>
                    <td>{{ $value->created_at }}</td>
                    <td>Apply</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection

