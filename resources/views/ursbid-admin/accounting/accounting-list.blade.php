@extends('ursbid-admin.layouts.app')
@section('title', 'Accounting List')
@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Accounting List</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3 mb-4" method="get" action="">
                            <div class="col-md-2">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categoryData as $cat)
                                        <option value="{{ $cat->id }}" {{ ($datas['category'] ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date</label>
                                <input type="text" name="date" class="form-control" placeholder="Date" value="{{ $datas['date'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" placeholder="City" value="{{ $datas['city'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantity</label>
                                <input type="text" name="quantity" class="form-control" placeholder="Quantity" value="{{ $datas['quantity'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" class="form-control" placeholder="Product Name" value="{{ $datas['product_name'] ?? '' }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ request()->url() }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </form>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Quotation File</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Total Price</th>
                                    <th>Platform Fee</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($blogs as $index => $blog)
                                    <tr>
                                        <td>{{ $blogs->firstItem() + $index }}</td>
                                        <td>{{ $blog->name }}</td>
                                        <td>{{ $blog->email }}</td>
                                        <td>{{ $blog->bid_time }} day</td>
                                        <td>{{ $blog->date_time ? \Carbon\Carbon::parse($blog->date_time)->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            @if(!empty($blog->bidding_price_image))
                                                <a href="{{ url('public/uploads/'.$blog->bidding_price_image) }}" target="_blank">View</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $blog->unit }}</td>
                                        <td>{{ $blog->quantity }}</td>
                                        <td>{{ $blog->rate }}</td>
                                        <td>
                                            @php
                                                $matches = [];
                                                preg_match('/\\d+(?:\\.\\d+)?/', (string) $blog->quantity, $matches);
                                                $qty = $matches[0] ?? 0;
                                            @endphp
                                            {{ number_format($qty * (float) $blog->rate, 2) }}
                                        </td>
                                        <td>{{ $blog->price }}</td>
                                        <td>
                                            <a href="{{ route('super-admin.accounting.enquiry-view', $blog->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">Sorry, no data found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($blogs->hasPages())
                            <div class="d-flex justify-content-end mt-3">
                                {{ $blogs->appends($datas)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
