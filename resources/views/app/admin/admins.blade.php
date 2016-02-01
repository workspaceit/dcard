@extends("layout.master")

@section("content")
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ 'App Users List' }}</h3>
        </div>
        <div class="box-body">
            <table id="data-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {{-- */ $i = 1; /* --}}
                @foreach($users as $user)
                    @if($user->email != @$admin['email'])
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="btn-group" style="min-width: 90px;">
                                    <button type="button" class="btn btn-success btn-flat">Action</button>
                                    <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ url("app/edit-admin/" . @$user->id) }}" >Edit</a></li>
                                        <li><a href="{{ url("app/delete-admin/" . @$user->id) }}" >Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        {{-- */ $i++; /* --}}
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section("css")
    @parent
    <link rel="stylesheet" href="{{ url("plugins/datatables/dataTables.bootstrap.css") }}">
    @endsection

    @section("script-bottom")
    @parent
            <!-- DataTables -->
    <script src="{{ url("plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ url("plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
@endsection

@section("raw-script")
    @parent

    <script>
        $(function () {
            $("#data-table").DataTable();
        });
    </script>
@endsection