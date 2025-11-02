@extends('ursbid-admin.layouts.app')
@section('title', 'Quotation File')

@section('content')

<div class="container-fluid">
    <!-- Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Quotation File</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Quotation File</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div id="table-container">
                        <div class="table-responsive">
                            <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                                <thead>
                                    <tr class="userDatatable-header">
                                        <th>
                                            <span class="projectDatatable-title">Sr no</span>
                                        </th>
                                        <th>
                                            <span class="projectDatatable-title">Qutation</span>
                                        </th>
                                        <!-- <th>
                                            <span class="projectDatatable-title">Action</span>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                    @endphp
                                    @foreach($images as $blog)
                                        <tr>
                                            <td>
                                                <div class="userDatatable-content text-center">
                                                    {{ $i++ }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    <a href="{{ url('public/bidfile/'.$blog) }}" target="_blank">View</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
