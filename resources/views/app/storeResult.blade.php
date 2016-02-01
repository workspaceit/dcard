@extends("layout.master")

@section("content")
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Store List</h3>
        </div>
        <div class="box-body">
            <table id="data-t-able" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Select</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>State</th>
                </tr>
                </thead>
                <tbody>
                {!! Form::open(['url' => url("app/new-store"), 'method' => 'post', "onSubmit" => "return false;"]) !!}
                    {{-- */ $i=0; /* --}}
                    @foreach($yelpData->businesses as $store)
                        <?php //echo "<pre/>"; print_r($store); die; ?>
                        @if(!in_array(@$store->id, $dCardStore))
                            <tr>
                                <td>
                                    <i class="success fa fa-check fa-lg text-green hidden"></i>
                                    <i class="failed fa fa-close fa-lg text-danger hidden"></i>
                                    {!! Form::checkbox('yelp_id['.$i.']', @$store->id, NULL,  ['class' => 'yelp_id']) !!}
                                </td>
                                <td>
                                    {!! Form::select('category['.$i.']', $category , NULL , ['class' => 'form-control category_id']) !!}
                                    {!! Form::hidden('store_name['.$i.']', @$store->name, ["class"=> "store_name"]) !!}
                                    {!! Form::hidden('store_state['.$i.']', @$store->location->state_code, ["class"=> "store_state"]) !!}
                                    {!! Form::hidden('store_city['.$i.']', @$store->location->city, ["class"=> "store_city"]) !!}
                                    {!! Form::hidden('store_zip['.$i.']', @$store->location->postal_code, ["class"=> "store_zip"]) !!}
                                    {!! Form::hidden('store_country['.$i.']', @$store->location->country_code, ["class"=> "store_country"]) !!}
                                    {!! Form::hidden('phone['.$i.']', @$store->phone, ["class"=> "phone"]) !!}
                                    {!! Form::hidden('lon['.$i.']', @$store->location->coordinate->longitude, ["class"=> "lon"]) !!}
                                    {!! Form::hidden('lat['.$i.']', @$store->location->coordinate->latitude, ["class"=> "lat"]) !!}
                                </td>
                                <td><a href="http://www.yelp.com/biz/{{ @$store->id }}" target="_blank">{{ @$store->name }}</a></td>
                                <td>{{ @$store->location->city }}</td>
                                <td>{{ @$store->location->country_code }}</td>
                                <td>{{ @$store->location->state_code }}</td>
                            </tr>
                            {{-- */ $i++; /* --}}
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="6">{!! Form::submit('Submit', ['class' => 'form-control btn-primary', "onclick" => "insertStore()"]) !!}</td>
                    </tr>
                {!! Form::close() !!}
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
    <script src="{{ url("plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ url("plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
@endsection

@section("raw-script")
    @parent

    <script>
        $(function () {
            $("#data-table").DataTable();
        });

        function insertStore(){
            var elements = $(".yelp_id:checked");
            elements.each(function(){
                var checkList = $(this);
                var parentTr = $(this).parents("tr").first();

                var success = $(parentTr).find(".success");
                var failed = $(parentTr).find(".failed");

                var yelp_id = $(parentTr).find(".yelp_id").val();
                var category_id = $(parentTr).find(".category_id").val();
                var store_name = $(parentTr).find(".store_name").val();
                var store_state = $(parentTr).find(".store_state").val();
                var store_city = $(parentTr).find(".store_city").val();
                var store_zip = $(parentTr).find(".store_zip").val();
                var store_country = $(parentTr).find(".store_country").val();
                var phone = $(parentTr).find(".phone").val();
                var lat = $(parentTr).find(".lat").val();
                var lon = $(parentTr).find(".lon").val();

                $.ajax({
                    url:        "../app/new-store",
                    method:     "POST",
                    dataType:   "JSON",
                    data: {
                        "_token": "",
                        "yelp_id": yelp_id,
                        "category_id": category_id,
                        "store_name": store_name,
                        "store_state": store_state,
                        "store_city": store_city,
                        "store_zip": store_zip,
                        "store_country": store_country,
                        "phone": phone,
                        "lat": lat,
                        "lon": lon
                    },
                    success: function(data){
                        if(data.responseStatus.status){
                            $(checkList).remove();
                            success.removeClass("hidden");
                        } else {
                            failed.removeClass("hidden");

                        }
                    }
                });
            });

            if(elements.length < 0){
                alert("No store selected !")
            }

        }
    </script>
@endsection