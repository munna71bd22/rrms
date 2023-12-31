@extends('layouts.app')

@section('content')

    @include('layouts.back-button')

    <div class="col-md-12">
        <div class="card mb-4">

            <div class="card-body">
                <form method="{{$method}}" action="{{route($route)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                    @foreach($fields as $field)
                        <div class="col-{{$field['width']}}">
                            <label class="form-label">
                                {{$field['label']}}
                            </label>
                            <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text">
                                <i class="{{$field['icon']}}"></i>
                            </span>

                                @if($field['type'] == 'select')
                                    <select  name="{{$field['name']}}"
                                             {{$field['select_type']}}"
                                             class="form-control @error($field['name']) is-invalid @enderror"
                                             @if($field['required'])  required="{{$field['required']}}"@endif >
                                           <option disabled selected value="">Select</option>
                                        @foreach($field['options'] as $option)
                                            <option value="{{$option['value']}}">{{$option['text']}}</option>
                                        @endforeach
                                    </select>
                                @elseif($field['type'] == 'textarea')
                                    <textarea
                                        type="{{$field['type']}}"
                                        name="{{$field['name']}}"
                                        class="form-control @error($field['name']) is-invalid @enderror"
                                        placeholder="{{$field['placeholder']}}"
                                        aria-label="{{$field['label']}}"
                                        @if($field['required'])  required="{{$field['required']}}"@endif
                                    >{{old($field['name'])}}</textarea>

                                @else($field['type'] != 'textarea')
                                    <input
                                        type="{{$field['type']}}"
                                        name="{{$field['name']}}"
                                        class="form-control @error($field['name']) is-invalid @enderror"
                                        placeholder="{{$field['placeholder']}}"
                                        aria-label="{{$field['label']}}"
                                        @if($field['required'])
                                            required="{{$field['required']}}"
                                        @endif
                                        @if( isset($field['pattern']))
                                            required="{{$field['pattern']}}"
                                        @endif
                                        value="{{ old($field['name'])}}"
                                    />
                                @endif

                                @error($field['name'])
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    @endforeach
                    </div>
                    <br>
                    <div class="mb-4 bx-pull-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
