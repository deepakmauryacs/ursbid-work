@extends('ursbid-admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
    /* 
     * DEFINING COLORS USING CSS VARIABLES FOR EASY THEME SWITCHING
     * These are the default (light mode) colors.
    */
    :root {
        --metric-card-bg: #ffffff;
        --metric-card-border-color: #e0e0e0;
        --metric-title-color: #555555;
        --metric-value-color: #222222;
        --welcome-header-bg: #f8f9fa;
    }

    /*
     * DARK MODE COLOR OVERRIDES
     * When the body has the 'data-bs-theme="dark"' attribute, these colors will be used instead.
    */
    [data-bs-theme="dark"] {
        --metric-card-bg: #2a2f3b; /* Dark background for cards */
        --metric-card-border-color: #404656;
        --metric-title-color: #c1c8d4;
        --metric-value-color: #ffffff;
        --welcome-header-bg: #2a2f3b;
    }

    .metric-card {
        background-color: var(--metric-card-bg); /* Use variable */
        border: 1px solid var(--metric-card-border-color); /* Use variable */
        border-radius: 8px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        border-left-width: 5px;
    }

    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .metric-card .card-body {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .metric-info {
        display: flex;
        align-items: center;
        gap: 1rem; /* Adds space between icon and title */
    }

    .metric-info .icon {
        font-size: 1.8rem; /* Icon size */
    }
    
    .metric-info h5 {
        margin-bottom: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--metric-title-color); /* Use variable */
    }

    .metric-value h2 {
        margin-bottom: 0;
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--metric-value-color); /* Use variable */
    }
    
    .welcome-card {
        background-color: var(--welcome-header-bg); /* Use variable */
        border: none;
    }

    /* Specific border and icon colors for each card */
    .border-primary-left { border-left-color: #3b82f6; }
    .border-primary-left .icon { color: #3b82f6; }

    .border-success-left { border-left-color: #16a34a; }
    .border-success-left .icon { color: #16a34a; }

    .border-info-left { border-left-color: #0ea5e9; }
    .border-info-left .icon { color: #0ea5e9; }

    .border-warning-left { border-left-color: #f59e0b; }
    .border-warning-left .icon { color: #f59e0b; }

    .border-danger-left { border-left-color: #ef4444; }
    .border-danger-left .icon { color: #ef4444; }

    .border-purple-left { border-left-color: #8b5cf6; }
    .border-purple-left .icon { color: #8b5cf6; }

    .border-dark-left { border-left-color: #374151; }
    .border-dark-left .icon { color: #374151; }
    [data-bs-theme="dark"] .border-dark-left .icon { color: #9ca3af; }


</style>

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
        <div class="col-12">
            <div class="card mb-4 welcome-card">
                <div class="card-body">
                    <h2 class="mb-1 display-6">Super Admin Dashboard</h2>
                    <p class="mb-0 fs-5 text-muted">Hello, User, Welcome back. Here's your data overview.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Category -->
        <div class="col-lg-4 col-md-6">
            <div class="card metric-card border-primary-left">
                <div class="card-body">
                    <div class="metric-info">
                        <i class="bi bi-grid-fill icon"></i>
                        <h5 class="mb-0">Total Category</h5>
                    </div>
                    <div class="metric-value">
                        <h2 class="mb-0">{{ $stats['categories'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sub Category -->
        <div class="col-lg-4 col-md-6">
            <div class="card metric-card border-success-left">
                <div class="card-body">
                    <div class="metric-info">
                        <i class="bi bi-diagram-3-fill icon"></i>
                        <h5 class="mb-0">Total Sub Category</h5>
                    </div>
                    <div class="metric-value">
                        <h2 class="mb-0">{{ $stats['subCategories'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Product -->
        <div class="col-lg-4 col-md-6">
            <div class="card metric-card border-info-left">
                <div class="card-body">
                    <div class="metric-info">
                        <i class="bi bi-box-seam-fill icon"></i>
                        <h5 class="mb-0">Total Product</h5>
                    </div>
                    <div class="metric-value">
                        <h2 class="mb-0">{{ $stats['products'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vendor -->
        <div class="col-lg-4 col-md-6">
            <div class="card metric-card border-warning-left">
                <div class="card-body">
                    <div class="metric-info">
                         <i class="bi bi-shop icon"></i>
                        <h5 class="mb-0">Total Vendor</h5>
                    </div>
                    <div class="metric-value">
                        <h2 class="mb-0">{{ $stats['vendors'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Buyer -->
        <div class="col-lg-4 col-md-6">
            <div class="card metric-card border-danger-left">
                <div class="card-body">
                    <div class="metric-info">
                        <i class="bi bi-bag icon"></i>
                        <h5 class="mb-0">Total Buyer</h5>
                    </div>
                    <div class="metric-value">
                        <h2 class="mb-0">{{ $stats['buyers'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Contractor -->
        <div class="col-lg-4 col-md-6">
            <div class="card metric-card border-purple-left">
                <div class="card-body">
                    <div class="metric-info">
                        <i class="bi bi-tools icon"></i>
                        <h5 class="mb-0">Total Contractor</h5>
                    </div>
                    <div class="metric-value">
                        <h2 class="mb-0">{{ $stats['contractors'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Client -->
        <div class="col-lg-4 col-md-6">
            <div class="card metric-card border-dark-left">
                <div class="card-body">
                    <div class="metric-info">
                        <i class="bi bi-people-fill icon"></i>
                        <h5 class="mb-0">Total Client</h5>
                    </div>
                    <div class="metric-value">
                        <h2 class="mb-0">{{ $stats['clients'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <p class="text-end text-muted mt-3 mb-0"><small>As of {{ $currentDate }}</small></p>
        </div>
    </div>
</div>
@endsection