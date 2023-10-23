@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}"/>

    <div class="row">
        <div class="col-{{12-count($tools)}}"></div>
        <div style="margin-bottom: 5px;" class=" col-{{count($tools)}} btn-group" role="group"
             aria-label="First group">
            @foreach($tools as $tool)

                <a href="{{$tool['url']}}" text="{{$tool['name']}}"
                   type="button"
                   class="{{$tool['class']}}">
                    <i class="{{$tool['icon']}}"></i>
                </a>

            @endforeach
        </div>
    </div>

    <!-- Bootstrap Table with Header - Light -->
    <div class="card">

        <div class="row">
            <div class="col">
                <p></p>
            </div>
        </div>

        <div class="container table-responsive text-nowrap">
            <table class="table table-borderless table-strip" id="dataTable">
                <thead>
                <tr>
                    @foreach($columns as $col)
                        <th>{{$col}}</th>
                    @endforeach
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Bootstrap Table with Header - Light -->
    @push('scripts')
        <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
        <script>
            let dataTable;

            $(document).ready(function () {

                dataTable = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    retrieve: true,
                    bLengthChange: true,
                    responsive: true,
                    ajax: {
                        url: '{{route($data_route)}}',
                        method: 'GET',
                        data: function (d) {
                            //d.division_id = $('#office_division_id').val()
                        }
                    },
                    autoWidth: false,
                    language: {
                        paginate: {
                            next: '&#8250;',
                            previous: '&#8249;'
                        },
                        processing: `<img height="50px;" src="{{asset('assets/img/loader3.gif')}}"> Loading...`
                    },
                    stateSave: true,
                    stateDuration: 7200,
                    stateSaveParams: function (settings, data) {
                        data.auth_user_id = '{{auth()->user()->id }}'
                    },
                    stateLoadParams: function (settings, data) {

                        if (data.auth_user_id == '{{auth()->user()->id }}') {
                            // need to set some variable
                        }
                    },
                    "columns": [
                        {data: "name", name: "name", orderable: true, sortable: true},
                        {data: "email", name: "email", orderable: false, sortable: false},
                        {data: "avatar", name: "avatar", orderable: false, sortable: false},
                        {data: "actions", name: "actions", orderable: false, sortable: false},
                        /*
                        {
                            "data": function (row, type, set) {
                                if (type === 'display' && row.current_promotion && row.current_promotion.department) {
                                    return row.current_promotion.department.name;
                                }
                                return '';
                            },
                            "name": "current_promotion.department.name",
                            orderable: false,
                            sortable: false,
                            searchable: false
                         */

                    ]
                });

            });

        </script>
    @endpush

@endsection
