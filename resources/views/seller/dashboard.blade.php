@extends('seller.layouts.app')
@section('title', ' Dashboard')
@section('content')
@php
    $sellerSession = session('seller');
    $acc_type = $sellerSession->acc_type ?? '';
    $acc_type_array = array_filter(explode(',', $acc_type));
    $sellerEmail = $sellerSession->email ?? null;
    $sellerDetails = $sellerEmail
        ? DB::table('seller')->where('email', $sellerEmail)->first()
        : null;
    $canManageLocation = in_array(1, $acc_type_array) || in_array(2, $acc_type_array);

    $referralCode = $sellerSession->ref_code ?? null;
    $referralUrl = $referralCode ? url('/refer-register/' . $referralCode) : null;
    $totalShare = $referralCode
        ? DB::table('seller')->where('verify', 1)->where('ref_by', $referralCode)->count()
        : 0;

    $acceptedHistory = collect();
    $chartMonths = [];
    $chartPrices = [];

    if ($canManageLocation && $sellerEmail) {
        $acceptedHistory = DB::table('bidding_price')
            ->select(DB::raw('SUM(price) as total_price, DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->where('action', 1)
            ->where('seller_email', $sellerEmail)
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), 'asc')
            ->get();

        $chartMonths = $acceptedHistory->pluck('month');
        $chartPrices = $acceptedHistory->pluck('total_price');
    }
@endphp
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row g-3 g-xl-4">
            <div class="col-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Dashboard</h4>
                </div>
            </div>

            @if ($canManageLocation)
                <div class="col-lg-4">
                    <div class="card card-default card-md mb-4 h-100">
                        <div class="card-header py-20 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Update Latitude &amp; Longitude</h6>
                            @if ($sellerDetails && $sellerDetails->latitude && $sellerDetails->longitude)
                                <span class="badge bg-light text-body-secondary">Live</span>
                            @endif
                        </div>
                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <p class="text-muted mb-3">
                                Keep your store location up to date to receive accurate enquiries and bidding requests near you.
                            </p>
                            <div class="d-flex flex-column gap-2 mb-4">
                                <span class="fw-medium text-dark">Current Coordinates</span>
                                @if ($sellerDetails && $sellerDetails->latitude && $sellerDetails->longitude)
                                    <div class="p-3 bg-light rounded border">
                                        <div class="small text-uppercase text-muted">Latitude</div>
                                        <div class="fs-5 fw-semibold">{{ number_format($sellerDetails->latitude, 6) }}</div>
                                        <div class="small text-uppercase text-muted mt-3">Longitude</div>
                                        <div class="fs-5 fw-semibold">{{ number_format($sellerDetails->longitude, 6) }}</div>
                                    </div>
                                @else
                                    <div class="p-3 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                        <span>No location saved yet.</span>
                                    </div>
                                @endif
                            </div>
                            @if ($sellerDetails && $sellerDetails->lock_location == 1)
                                <form action="{{ url('seller/update_lat_long') }}" method="POST" class="d-flex flex-column gap-3">
                                    @csrf
                                    <input type="hidden" value="{{ $sellerEmail }}" name="email">
                                    <input type="hidden" id="latitude" name="latitude" value="">
                                    <input type="hidden" id="longitude" name="longitude" value="">
                                    <button type="submit" class="btn btn-primary w-100">Update Location</button>
                                </form>
                            @endif
                            @if ($sellerDetails && $sellerDetails->latitude && $sellerDetails->longitude)
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @if ($sellerDetails->lock_location == 1)
                                        <a href="{{ url('lock_location/' . $sellerDetails->id) }}" class="btn btn-outline-danger btn-sm">
                                            Lock Location
                                        </a>
                                    @else
                                        <a href="{{ url('unlock_location/' . $sellerDetails->id) }}" class="btn btn-outline-success btn-sm">
                                            Unlock Location
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-8">
                <div class="card card-default card-md mb-4">
                    <div class="card-header py-20">
                        <h6>Profile</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="fw-semibold text-muted">Name</th>
                                        <td>{{ $sellerSession->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold text-muted">Email</th>
                                        <td>{{ $sellerSession->email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold text-muted">Phone</th>
                                        <td>{{ $sellerSession->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold text-muted">GST</th>
                                        <td>{{ $sellerSession->gst ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold text-muted">Total Share</th>
                                        <td>{{ $totalShare }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold text-muted">Referral Code</th>
                                        <td>
                                            @if ($referralUrl)
                                                <div class="d-flex flex-wrap align-items-center gap-3">
                                                    <div>
                                                        {!! QrCode::size(110)->generate($referralUrl) !!}
                                                    </div>
                                                    <div class="d-flex flex-column gap-2">
                                                        <span class="fw-medium">{{ $referralCode }}</span>
                                                        <div class="btn-group" role="group">
                                                            <a class="btn btn-outline-success btn-sm" href="https://wa.me/?text={{ urlencode($referralUrl) }}" target="_blank" rel="noopener">
                                                                Share on WhatsApp
                                                            </a>
                                                            <button class="btn btn-outline-secondary btn-sm" type="button" data-copy-button data-copy-target="referralLink">
                                                                Copy Link
                                                            </button>
                                                        </div>
                                                        <input type="text" class="visually-hidden" value="{{ $referralUrl }}" id="referralLink" readonly>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Referral details will appear once your account has an active referral code.</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if ($canManageLocation)
                <div class="col-12">
                    <div class="card card-default card-md mb-4">
                        <div class="card-header py-20">
                            <h6>Accepted History</h6>
                        </div>
                        <div class="card-body">
                            @if ($acceptedHistory->isEmpty())
                                <div class="py-5 text-center text-muted">
                                    <i class="bi bi-bar-chart fs-2 d-block mb-2"></i>
                                    <span>No accepted history available yet. Complete your first bid to see insights here.</span>
                                </div>
                            @else
                                <div class="position-relative" style="width: 100%; margin: auto; min-height: 320px;">
                                    <canvas id="priceChart" data-chart-months='@json($chartMonths)' data-chart-prices='@json($chartPrices)'></canvas>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const latitudeField = document.getElementById('latitude');
            const longitudeField = document.getElementById('longitude');

            const autoFillLocation = () => {
                if (!latitudeField || !longitudeField) {
                    return;
                }

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
                    alert('Geolocation is not supported by your browser.');
                }
            };

            const handleGeolocationError = error => {
                let errorMessage = '';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'User denied the request for Geolocation.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'The request to get user location timed out.';
                        break;
                    case error.UNKNOWN_ERROR:
                    default:
                        errorMessage = 'An unknown error occurred.';
                        break;
                }
                alert(errorMessage);
            };

            autoFillLocation();

            const copyButton = document.querySelector('[data-copy-button]');
            if (copyButton) {
                const targetId = copyButton.getAttribute('data-copy-target');
                const referralInput = document.getElementById(targetId);

                copyButton.addEventListener('click', async () => {
                    if (!referralInput) {
                        return;
                    }

                    referralInput.select();
                    referralInput.setSelectionRange(0, 99999);

                    try {
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            await navigator.clipboard.writeText(referralInput.value);
                        } else {
                            document.execCommand('copy');
                        }
                        toastr.success('Referral link copied to clipboard.');
                    } catch (error) {
                        console.error('Copy failed', error);
                        alert('Unable to copy the referral link. Please try manually.');
                    }
                });
            }

            const priceChartElement = document.getElementById('priceChart');
            if (priceChartElement) {
                const months = JSON.parse(priceChartElement.getAttribute('data-chart-months') || '[]');
                const prices = JSON.parse(priceChartElement.getAttribute('data-chart-prices') || '[]');

                if (months.length && prices.length) {
                    const chartConfig = {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Total Price',
                                data: prices,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                borderRadius: 6,
                                maxBarThickness: 48
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: value => `₹${value}`
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: context => `₹${context.parsed.y}`
                                    }
                                }
                            }
                        }
                    };

                    new Chart(priceChartElement.getContext('2d'), chartConfig);
                }
            }
        });
    </script>
@endpush
@endsection
