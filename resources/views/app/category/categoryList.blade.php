<!--
 * Project  : dCard
 * File     : categoryList.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/8/15 - 4:25 PM
-->

@extends("layout.master")

@section("content")
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Store List</h3>
        </div>
        <div class="box-body">
            <table id="data-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Added By</th>
                    <th>Added Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {{-- */ $i = 1; /* --}}
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ @$category->name }}</td>
                        <td>{{ @$category->createdBy->first_name . " " . @$category->createdBy->last_name }}</td>
                        <td>{{ @$category->created_date }}</td>
                        <td><a href="{{ url("store/category/" . $category->id . "/edit") }}"><button class="btn btn-primary">Edit</button></a> </td>
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