@extends('layout')

@section('content')
    <div class="container">
        <h1 class="mt-3">Find job</h1>
        <div class="row">
            <div class="form-content">
                <form action="" method="" class="col-lg-5">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" placeholder="" aria-describedby="helpId">
                    </div>

                    <div class="form-group">
                        {{ Form::select('category', $categories, 1, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <h1>Results</h1>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Creator</th>
                <th>Create_at</th>
                <th>Category</th>
                <th>Apply</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($jobs->sortByDesc('created_at') as $key => $job)
                <tr>
                    <td scope="row">{{ $job->id }}</td>
                    <td>{{ $job->user->name }}</td>
                    <td>{{ $job->created_at }}</td>
                    <td>{{ $job->category->name }}</td>
                    <td>
                        <p data-placement="top" data-toggle="tooltip" title="Edit">
                            <button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal"
                                    data-target="#edit">
                                <a href="{{ route('users.apply', ['id' => $job->id]) }}">Apply</a>
                            </button>
                        </p>
                    </td>
                    <td>
                        <p data-placement="top" data-toggle="tooltip" title="Edit">
                            <button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal"
                                    data-target="#edit">
                                <a href="{{ route('jobs.edit', ['id' => $job->id]) }}">Edit</a>
                            </button>
                        </p>
                    </td>
                    <td>
                        <p data-placement="top" data-toggle="tooltip" title="Delete">
                            <button class="btn btn-danger btn-xs" data-title="DElete" data-toggle="modal"
                                    data-target="#destroy">
                                <a href="{{ route('jobs.delete', ['id' => $job->id]) }}">Delete</a>
                            </button>
                        </p>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="clearfix"></div>
        <div class="">
            <div class="pull-right">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
@endsection
