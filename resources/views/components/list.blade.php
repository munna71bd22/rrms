@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}"/>

    <div class="row">
        <div class="col-{{12-count($tools)}}"></div>
        <div style="margin-bottom: 5px;" class=" col-{{count($tools)}} btn-group" role="group"
             aria-label="First group">
            @foreach($tools as $tool)

                <a href="{{$tool['url']}}" title="{{$tool['name']}}"
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
            <table class="table table-borderless table-strip" id="dataTable-{{$pageID}}">
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
    <form id="deleteForm" action="" method="POST">
        @csrf
        {!! method_field('delete') !!}
    </form>
    <form id="updateStatusForm" action="" method="GET">
        <input type="hidden" id="status" name="status" value="pending">
    </form>
    @push('scripts')
        <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
        <script>
            let dataTable;

            $(document).ready(function () {

                dataTable = $('#dataTable-{{$pageID}}').DataTable({
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
                        @foreach($columns_for_datatable as $col)
                        {data: "{{$col['data']}}", name: "{{$col['name']}}", orderable: {{$col['orderable']}}, sortable: {{$col['sortable']}} },
                        @endforeach
                    ]
                });

            });

            function confirmDelete(url)
            {
                if(confirm("Are you sure want to delete this?")) {
                    $('#deleteForm').attr('action',url);
                    $('#deleteForm').submit();
                }
            }
            function confirmDelete(url)
            {
                if(confirm("Are you sure want to delete this?")) {
                    $('#deleteForm').attr('action',url);
                    $('#deleteForm').submit();
                }
            }
            function updateStatus(url,status)
            {
                if(confirm(`Are you sure want to update status as ${status}?`)) {
                    $('#updateStatusForm').attr('action',url);
                    $('#status').val(status);
                    $('#updateStatusForm').submit();
                }
            }

        </script>
    @endpush

@endsection
