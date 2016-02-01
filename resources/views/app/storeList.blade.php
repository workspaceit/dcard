@extends("layout.master")

@section("content")
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Store List</h3>
        </div>
        <div class="box-body">
            <div class="text-right">
                <a href="{{ url("csv/export/store") }}" >Export Store</a>
            </div>
            <table id="data-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Store ID</th>
                    <th>Name</th>
                    <th>Invite Code</th>
                    <th>Category</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Zip</th>
                    <th>Country</th>
                    {{--<th>Percent Off</th>
                    <th>Amount Off</th>
                    <th>On Spent</th>--}}
                    <th>Vote</th>
                    {{--<th>Status</th>
                    <th>Send Code</th>--}}
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {{-- */ $i = 1; /* --}}
                @foreach($stores as $store)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ @$store->yelp_id }}</td>
                        <td>
                            @if($store->yelp_id == NULL)
                                {{ @$store->store_name }}
                            @else
                                <a href="http://www.yelp.com/biz/{{ @$store->yelp_id }}" target="_blank">{{ @$store->store_name }}</a>
                            @endif
                        </td>
                        <td>{{ @$store->invite_code }}</td>
                        <td>{{ @$store->category->name }}</td>
                        <td>{{ @$store->store_state }}</td>
                        <td>{{ @$store->store_city }}</td>
                        <td>{{ @$store->store_zip }}</td>
                        <td>{{ @$store->store_country }}</td>
                        {{--<td>{{ @$store->percent_off }}</td>
                        <td>{{ @$store->amount_off }}</td>
                        <td>{{ @$store->on_spent }}</td>--}}
                        <td>{{ count(@$store->vote) }}</td>
                        <!--<td>
                            @if($store->participator == 1)
                                <button onclick="status(0, {{ $store->store_id }}, this)" class="de-active btn btn-success">Participator</button>
                            @else
                                <button onclick="status(1, {{ $store->store_id }}, this)" class="active btn btn-danger">Out of dCard</button>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url("store/code/".$store->invite_code) }}" ><button class="active btn btn-primary">Send</button></a>
                        </td>-->
                        <td>
                            <div class="btn-group" style="min-width: 90px;">
                                <button type="button" class="btn btn-success btn-flat">Action</button>
                                <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        @if($store->participator == 1)
                                            <a href="javascript:void(0)" onclick="status(0, {{ $store->store_id }}, this)" class="de-active">Dispute</a>
                                        @else
                                            <a href="javascript:void(0)" onclick="status(1, {{ $store->store_id }}, this)" class="active" >Enrolled</a>
                                        @endif
                                    </li>
                                    <li><a href="{{ url("store/code/".$store->invite_code) }}" >Send Code</a></li>
                                    <li><a href="{{ url("app/store/".$store->store_id) }}" >View</a></li>
                                    <li><a href="{{ url("app/edit-store/".$store->store_id) }}" >Edit</a></li>
                                    <li><a href="{{ url("app/delete-store/".$store->store_id) }}" >Delete</a></li>
                                </ul>
                            </div>
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

        function status(status, id, btn){
            var button = $(btn);
            $.ajax({
                url: "../store/change/status",
                method: "POST",
                data:{
                    status: status,
                    store_id: id
                },
                success: function(data){
                    if(data.responseStatus.status){
                        console.log(status);
                        if(status == 1){
                            //button.removeClass("btn-danger");
                            //button.addClass("btn-success");
                            button.html("Dispute");
                            button.attr("onclick", "status(0," + id + ", this)");
                        } else {
                            //button.removeClass("btn-success");
                            //button.addClass("btn-danger");
                            button.html("Enrolled");
                            button.attr("onclick", "status(1," + id + ", this)");
                        }
                    }
                }
            });
        }
    </script>
@endsection