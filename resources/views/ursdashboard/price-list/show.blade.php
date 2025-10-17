@extends('seller.layouts.app')
@section('title', 'Seller List with Price')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h4 class="text-capitalize breadcrumb-title mb-0">Seller List with Price</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <div class="action-btn">
                            <a href="{{ url('buyer/bidding-received/list') }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-arrow-left me-1"></i> Back
                            </a>
                        </div>
                        <div class="action-btn">
                            <a href="{{ route('buyer.cis-details', $enquiryId) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-clipboard-data me-1"></i> CIS View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card shadow-sm">
                    <div class="card-header border-bottom">
                        <h5 class="mb-0">Enquiry #{{ $enquiryId }}</h5>
                    </div>
                    <div class="card-body">
                        @include('ursdashboard.price-list.partials.table', [
                            'records' => $records,
                            'total' => $total,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
