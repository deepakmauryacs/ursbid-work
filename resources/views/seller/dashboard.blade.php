@extends('seller.layouts.app')
@section('title', ' Dashboard')
<!--  -->
@section('content')
@php
$acc_type = session('seller')->acc_type;
$acc_type_array = explode(',', $acc_type);
@endphp
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title"> Dashboard</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <!-- <div class="action-btn">
  
                                        <div class="form-group mb-0">
                                            <div class="input-container icon-left position-relative">
                                                <span class="input-icon icon-left">
                                                    <span data-feather="calendar"></span>
                                                </span>
                                                <input type="text" class="form-control form-control-default date-ranger" name="date-ranger" placeholder="Oct 30, 2019 - Nov 30, 2019">
                                                <span class="input-icon icon-right">
                                                    <span data-feather="chevron-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div> -->
                        <!-- <div class="dropdown action-btn">
                                        <button class="btn btn-sm btn-default btn-white dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-download"></i> Export
                                        </button>
                                       
                                    </div>
                                    <div class="dropdown action-btn">
                                        <button class="btn btn-sm btn-default btn-white dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-share"></i> Share
                                        </button>
                                        
                                    </div>
                                    <div class="action-btn">
                                        <a href="#" class="btn btn-sm btn-primary btn-add">
                                            <i class="la la-plus"></i> Add New</a>
                                    </div> -->
                    </div>
                </div>

            </div>
            @php
            if (in_array(1, $acc_type_array) || in_array(2, $acc_type_array) ) {

            @endphp
            <div class="col-lg-4">
                <div class="card card-default card-md mb-4">
                    <div class="card-header  py-20">
                        <h6>Update Latitude & Longitude</h6>
                    </div>
                    <div class="card-body">
                        <div class="atbd-statistics-wrap d-flex">



                            <div class="statistics-item statistics-default">

                                <span class="statistics-item__title">Your latitude & longitude </span>
                                @if(Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif

                                <p class="statistics-item__number">

                                    @php

                                    $email = session('seller')->email;
                                    $data = DB::table('seller')->where('email', $email)->first();
                                    if($data->latitude && $data->longitude){
                                    @endphp
                                    {{$data->latitude}} & {{$data->longitude}}
                                    @php
                                    }
                                    @endphp
                                </p>
                                <div class="statistics-item__action ">


                                    @if($data->lock_location==1)
                                    <form action="{{ url('seller/update_lat_long')}}" method='POST'>
                                        @csrf
                                        <input type="hidden" value="{{ session('seller')->email }}" name="email"><br>
                                        <input type="hidden" id="latitude" name="latitude" value=""><br>
                                        <input type="hidden" id="longitude" name="longitude" value="">
                                        <button type='submit'
                                            class="btn btn-shadow-primary btn-primary btn-md">Update</button>
                                    </form>

                                    @endif
                                    @if($data->latitude && $data->longitude)
                                    <div class="d-inline-block">
                                        @if($data->lock_location==1)
                                        <a href="{{ url('lock_location/'.$data->id) }}">
                                            <span class="media-badge color-white bg-danger">Lock-Location</span>
                                        </a>
                                        @else
                                        <a href="{{ url('unlock_location/'.$data->id) }}">
                                            <span class="media-badge color-white bg-success">Unlock-Location</span>
                                        </a>
                                        @endif

                                    </div>
                                    @endif


                                </div>

                            </div>


                        </div>
                    </div>
                </div>
                <!-- ends: .card -->
            </div>
            @php

            }
            @endphp
            <div class="col-lg-8">
                <div class="card card-default card-md mb-4">
                    <div class="card-header  py-20">
                        <h6>Profile</h6>
                    </div>
                    <div class="card-body">




                        <table class="table mb-0">
                            <tr>
                                <th>Name</th>
                                <td>{{ session('seller')->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ session('seller')->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ session('seller')->phone }}</td>
                            </tr>
                            <tr>
                                <th>GST</th>
                                <td>{{ session('seller')->gst }} </td>
                            </tr>
                            <tr>
                                <th>Total share</th>

                                @php
                                $totalsare = DB::table('seller')->where('verify', 1)->where('ref_by',
                                session('seller')->ref_code)->count();

                                @endphp
                                <td>{{ $totalsare }} </td>

                            </tr>
                            <tr>
                                <th>Referral Code</th>
                                <td>

                                    {!! QrCode::size(100)->generate(url('/refer-register/' .
                                    session('seller')->ref_code)) !!}
                                    <a href="https://wa.me/?text={{ urlencode(url('/refer-register/' . session('seller')->ref_code)) }}"
                                        target="_blank">
                                        Share on WhatsApp
                                    </a>

                                    <!-- Copy to Clipboard Button -->
                                    <input type="text" class="d-none"
                                        value="{{ url('/refer-register/' . session('seller')->ref_code) }}"
                                        id="referralLink" readonly>
                                    <button onclick="copyToClipboard()">Copy to Clipboard</button>


                                </td>
                            </tr>


                        </table>




                    </div>
                </div>
                <!-- ends: .card -->
            </div>

            <script>
            function copyToClipboard() {
                // Get the text field
                var copyText = document.getElementById("referralLink");

                // Select the text field
                copyText.select();
                copyText.setSelectionRange(0, 99999); // For mobile devices

                // Copy the text
                document.execCommand("copy");

                // Alert the copied text
                alert("Copied the text: " + copyText.value);
            }
            </script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            @php
            if (in_array(1, $acc_type_array) || in_array(2, $acc_type_array) ) {

            @endphp
            <div class="col-lg-12">
                <div class="card card-default card-md mb-4">
                    <div class="card-header  py-20">
                        <h6>Accepted History </h6>
                    </div>
                    <div class="card-body">




                        @php


                        $email = session('seller')->email;

                        $data = DB::table('bidding_price')
                        ->select(DB::raw('SUM(price) as total_price, DATE_FORMAT(created_at, "%Y-%m") as month'))
                        ->where('action', 1)
                        ->where('seller_email', $email)
                        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                        ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), 'asc')
                        ->get();
                        $months = [];
                        $prices = [];

                        foreach ($data as $item) {
                        $months[] = $item->month;
                        $prices[] = $item->total_price;
                        }
                        @endphp

                        <div style="width: 100%; margin: auto;">
                            <canvas id="priceChart"></canvas>
                        </div>

                        <script>
                        const ctx = document.getElementById('priceChart').getContext('2d');
                        const priceChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: @json($months),
                                datasets: [{
                                    label: 'Total Price',
                                    data: @json($prices),
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                        </script>




                    </div>
                </div>
                <!-- ends: .card -->
            </div>
            @php

            }
            @endphp
        </div>
        <div class="form-element">

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const latitudeField = document.getElementById('latitude');
    const longitudeField = document.getElementById('longitude');

    const autoFillLocation = () => {
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    latitudeField.value = position.coords.latitude;
                    longitudeField.value = position.coords.longitude;
                },
                error => {
                    handleGeolocationError(error);
                }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    };

    const handleGeolocationError = error => {
        let errorMessage = '';
        switch (error.code) {
            case error.PERMISSION_DENIED:
                errorMessage = "User denied the request for Geolocation.";
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage = "Location information is unavailable.";
                break;
            case error.TIMEOUT:
                errorMessage = "The request to get user location timed out.";
                break;
            case error.UNKNOWN_ERROR:
            default:
                errorMessage = "An unknown error occurred.";
                break;
        }
        alert(errorMessage);
    };

    autoFillLocation();
});
// 
</script>

<!-- <script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    $("#latitude").val(latitude);
    $("#longitude").val(longitude);
}
getLocation();
</script> -->
@endsection