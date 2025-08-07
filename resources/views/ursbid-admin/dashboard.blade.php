@extends('ursbid-admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Super Admin</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                    <li class="breadcrumb-item active">Super Admin</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="mb-1">Super Admin Dashboard</h2>
                    <p class="mb-0">Hello, URSBID, Welcome to Super Admin Dashboard</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card mb-0 text-center">
                                <div class="card-body">
                                    <h5 class="mb-2">Total Category</h5>
                                    <h2 class="mb-0">{{ $stats['categories'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card mb-0 text-center">
                                <div class="card-body">
                                    <h5 class="mb-2">Total Sub Category</h5>
                                    <h2 class="mb-0">{{ $stats['subCategories'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card mb-0 text-center">
                                <div class="card-body">
                                    <h5 class="mb-2">Total Product</h5>
                                    <h2 class="mb-0">{{ $stats['products'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card mb-0 text-center">
                                <div class="card-body">
                                    <h5 class="mb-2">Total Vendor</h5>
                                    <h2 class="mb-0">{{ $stats['vendors'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card mb-0 text-center">
                                <div class="card-body">
                                    <h5 class="mb-2">Total Buyer</h5>
                                    <h2 class="mb-0">{{ $stats['buyers'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card mb-0 text-center">
                                <div class="card-body">
                                    <h5 class="mb-2">Total Contractor</h5>
                                    <h2 class="mb-0">{{ $stats['contractors'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card mb-0 text-center">
                                <div class="card-body">
                                    <h5 class="mb-2">Total Client</h5>
                                    <h2 class="mb-0">{{ $stats['clients'] }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-end mt-3 mb-0"><small>As of {{ $currentDate }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

