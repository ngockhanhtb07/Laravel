@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>List User</h4>
                <div class="table-responsive">
                    <table id="mytable" class="table table-bordred table-striped">
                        <thead>
                        <th><input type="checkbox" id="checkall"/></th>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Skill</th>
                        <th>Create_at</th>
                        <th>Update_at</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        </thead>
                        <tbody>
                        {{ Form::open(['id' => 'form-all', 'class' => 'form-delete-submit','method' => 'POST']) }}
                        @foreach($users as $key => $user)
                            <tr>
                                <td><input type="checkbox" class="checkthis"/></td>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->skills }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>
                                <td>
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal"
                                                data-target="#edit">
                                            <a href="{{ route('users.edit', ['id' => $user->id]) }}">Edit</a>
                                        </button>
                                    </p>
                                </td>
                                <td>
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-title="DElete" data-toggle="modal"
                                                data-target="#destroy">
                                            <a href="{{ route('users.destroy', ['id' => $user->id]) }}">Delete</a>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ Form::close() }}
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="pull-right">
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection