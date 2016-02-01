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
                    <th>Name</th>
                    <th>Member Code</th>
                    <th>Email</th>
                    <th>User Type</th>
                    {{--<th>Store</th>--}}
                </tr>
                </thead>
                <tbody>
                {{-- */ $i = 1; /* --}}
                @foreach($users as $user)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->member_code }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->user_type == 3)
                                Customer
                            @elseif($user->user_type == 2)
                                Employee
                            @elseif($user->user_type == 1)
                                Merchant
                            @endif
                        </td>
                    </tr>
                    {{-- */ $i++; /* --}}
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