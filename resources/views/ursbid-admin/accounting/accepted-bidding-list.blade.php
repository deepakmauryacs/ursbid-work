@extends('ursbid-admin.layouts.app')
@section('title', 'Accepted Bidding List')
@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Accepted Bidding List</h4>
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
                                            {{ $cat->name }}
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

                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Product Name</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $index => $record)
                                    <tr>
                                        <td>{{ $data->firstItem() + $index }}</td>
                                        <td>{{ $record->seller_name }}</td>
                                        <td>{{ $record->category_name }}</td>
                                        <td>{{ $record->sub_name }}</td>
                                        <td>{{ $record->product_name }}</td>
                                        <td>{{ $record->date_time ? \Carbon\Carbon::parse($record->date_time)->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $record->quantity }}</td>
                                        <td>{{ $record->unit }}</td>
                                        <td class="d-flex gap-2">
                                            <a href="{{ route('super-admin.accounting.price-list', $record->id) }}" class="btn btn-sm btn-outline-primary">View List</a>
                                            <a href="{{ route('super-admin.accounting.accepted-list', $record->id) }}" class="btn btn-sm btn-success">Accepted List</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-danger">Sorry, no data found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($data->hasPages())
                            <div class="d-flex justify-content-end mt-3">
                                {{ $data->appends($datas)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
