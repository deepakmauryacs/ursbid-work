@extends('ursbid-admin.layouts.app')
@section('title', 'Quotation Files')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="mb-0 fw-semibold">Quotation Files</h4>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('super-admin.accounting.accounting-list') }}">Accounting List</a></li>
                        <li class="breadcrumb-item active">Quotation Files</li>
                    </ol>
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($files)
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 80px;">#</th>
                                        <th scope="col">File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($files as $index => $file)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="{{ url('public/bidfile/'.$file) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">No quotation files available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
