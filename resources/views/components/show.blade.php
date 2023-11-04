@extends('layouts.app')

@section('content')
    @include('layouts.back-button')

    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    @foreach($obj->getAttributes() as $key => $value)
                        <tr>
                            <th> {{$key}}:</th>
                            @if(in_array($key,['avatar','photo','picture']))
                                <td><img height="100px;" src="/storage/{{ $value}}"></td>
                            @elseif(in_array($key,['created_at','date','updated_at','booking_date','confirmed_date']))
                                <td> {{ $value ? date('d M, Y h:i A',strtotime($value)) : 'N/A'}} </td>
                            @elseif(in_array($key,['tbl_id','menus']))
                                <td>
                                    @foreach($value ?? [] as $v)
                                        <span class="badge bg-label-primary me-1"> {{$v->title}}</span>
                                    @endforeach
                                </td>
                            @else
                                <td>{{$value ?? 'N/A'}}</td>
                            @endif
                        </tr>

                    @endforeach
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
