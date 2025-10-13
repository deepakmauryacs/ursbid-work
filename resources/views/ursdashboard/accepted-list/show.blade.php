@extends('seller.layouts.app')
@section('title', 'Accepted List')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex align-items-center justify-content-between">
                    <h4 class="text-capitalize breadcrumb-title">Accepted List</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <div class="action-btn">
                            <a href="{{ route('acc-list') }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $records = $records ?? collect();
            $total = $total ?? $records->count();
        @endphp

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0">Accepted Bids</h5>
                        <span class="badge bg-primary">Total: {{ $total }}</span>
                    </div>
                    <div class="card-body">
                        @include('ursdashboard.accepted-list.partials.table', [
                            'records' => $records,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
