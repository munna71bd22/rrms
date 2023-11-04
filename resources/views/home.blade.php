@extends('layouts.app')
@php
    $all = \App\Models\Booking::whereYear('booking_date',now())->count('id');
    $x =  \App\Models\Booking::where('status','pending')->whereMonth('booking_date',now())->count('id');
    $y =  \App\Models\Booking::where('status','approved')->whereMonth('booking_date',now())->count('id');
    $z = \App\Models\Booking::where('status','cancel')->whereMonth('booking_date',now())->count('id');
    $w = \App\Models\Booking::where('status','approved')->whereYear('booking_date',now())->count('id');


    $jan =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-01-01'),date('Y-01-31')])->count('id');
    $feb =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-02-01'),date('Y-02-29')])->count('id');
    $mar =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-03-01'),date('Y-03-31')])->count('id');
    $apr =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-04-01'),date('Y-04-30')])->count('id');
    $may =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-05-01'),date('Y-05-31')])->count('id');
    $jun =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-06-01'),date('Y-06-30')])->count('id');
    $july =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-07-01'),date('Y-07-31')])->count('id');
    $aug =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-08-01'),date('Y-08-31')])->count('id');
    $sep =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-09-01'),date('Y-09-30')])->count('id');
    $oct =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-10-01'),date('Y-10-31')])->count('id');
    $nov =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-11-01'),date('Y-11-30')])->count('id');
    $dec =  \App\Models\Booking::where('status','approved')->whereBetween('booking_date',[date('Y-12-01'),date('Y-12-31')])->count('id');

@endphp
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Congratulations @if(Auth::user())
                                    {{ Auth::user()->name }}
                                @endif!! 🎉</h5>


                            <a href="{{route('bookings.index')}}" class="btn btn-sm btn-outline-primary">View
                                Bookings</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img
                                src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}"
                                height="140"
                                alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row">
        <!-- Order Statistics -->
        <div class="col-md-12 col-lg-4 col-xl-5 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Booking Statistics</h5>
                        <small class="text-muted">
                            {{$all}} Total Bookings
                        </small>
                    </div>

                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <h2 class="mb-2">
                                {{$y}}
                            </h2>
                            <span>Total Approved</span>
                        </div>
                        <div id="orderStatisticsChart"></div>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"
                            ><i class="bx bx-mobile-alt"></i
                                ></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Pending</h6>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">
                                        {{$x}}
                                        <input type="hidden" id="total_pending" value="{{$x}}">
                                    </small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-closet"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Approved</h6>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">
                                        {{$y}}
                                        <input type="hidden" id="total_approved" value="{{$y}}">
                                    </small>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex">
                            <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"
                            ><i class="bx bx-football"></i
                                ></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Cancel</h6>

                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">
                                        {{$z}}
                                        <input type="hidden" id="total_cancel" value="{{$z}}">
                                    </small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

        <!-- Expense Overview -->
        <div class="col-md-6 col-lg-7 order-1 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link active"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-tabs-line-card-income"
                                aria-controls="navs-tabs-line-card-income"
                                aria-selected="true"
                            >
                                Booking Overview Yearly
                            </button>
                        </li>


                    </ul>
                </div>
                <div class="card-body px-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                            <div class="d-flex p-4 pt-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/icons/unicons/wallet.png')}}" alt="User"/>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Approved Bookings in this current
                                        year</small>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">
                                        {{$w}}

                                    </div>
                                </div>
                            </div>
                            <div id="incomeChart"></div>

                            <input type="hidden" id="jan" value="{{$jan}}">
                            <input type="hidden" id="feb" value="{{$feb}}">
                            <input type="hidden" id="mar" value="{{$mar}}">
                            <input type="hidden" id="apr" value="{{$apr}}">
                            <input type="hidden" id="may" value="{{$may}}">
                            <input type="hidden" id="jun" value="{{$jun}}">
                            <input type="hidden" id="july" value="{{$july}}">
                            <input type="hidden" id="aug" value="{{$aug}}">
                            <input type="hidden" id="sep" value="{{$sep}}">
                            <input type="hidden" id="oct" value="{{$oct}}">
                            <input type="hidden" id="nov" value="{{$nov}}">
                            <input type="hidden" id="dec" value="{{$dec}}">


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Expense Overview -->

@endsection
