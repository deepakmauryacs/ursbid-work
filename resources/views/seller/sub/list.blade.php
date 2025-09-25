@extends('seller.layouts.app')
@section('title', 'Sub Categories')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Sub Categories</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('seller-dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Sub Categories</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card filter-card border-0 shadow-sm">
                <div class="card-body py-3">
                    <form class="filter-form" method="get" action="">
                        <ul class="filter-ul">
                            <li>
                                <label class="filter-label">Search</label>
                                <div class="input-group pill-wrap">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text"
                                           name="keyword"
                                           value="{{ request('keyword', $data['keyword'] ?? '') }}"
                                           class="form-control"
                                           placeholder="Search by sub category">
                                </div>
                            </li>
                            <li>
                                <label class="filter-label">Records Per Page</label>
                                <div class="input-group pill-wrap">
                                    <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                    <select class="form-select" name="r_page">
                                        @php
                                            $records = [25, 50, 100];
                                            $selectedPerPage = request('r_page', $data['r_page'] ?? 25);
                                        @endphp
                                        @foreach($records as $record)
                                            <option value="{{ $record }}" {{ (int)$selectedPerPage === $record ? 'selected' : '' }}>
                                                {{ $record }} Records Per Page
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                            <li class="actions-li">
                                <button type="submit" class="btn btn-primary btn-icon">
                                    <i class="bi bi-funnel"></i>Apply
                                </button>
                                <a href="{{ url('admin/sub/list') }}" class="btn btn-outline-secondary btn-icon">
                                    <i class="bi bi-arrow-counterclockwise"></i>Reset
                                </a>
                                <a href="{{ url('admin/sub/add') }}" class="btn btn-success btn-icon">
                                    <i class="bi bi-plus-circle"></i>Add Sub Category
                                </a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <div class="row">
        <div class="col-12">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = ($blogs->currentPage() - 1) * $blogs->perPage() + 1; @endphp
                                @forelse ($blogs as $blog)
                                    <tr id="row-{{ $blog->sub_id }}">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $blog->category_title }}</td>
                                        <td>{{ $blog->sub_tilte }}</td>
                                        <td>
                                            @if($blog->img)
                                                <img src="{{ url('public/uploads/'.$blog->img) }}" alt="{{ $blog->sub_tilte }}" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($blog->sub_status == 1)
                                                <a href="{{ url('admin/sub/active/'.$blog->sub_id) }}" class="badge bg-success-subtle text-success px-3 py-2">Active</a>
                                            @else
                                                <a href="{{ url('admin/sub/deactive/'.$blog->sub_id) }}" class="badge bg-danger-subtle text-danger px-3 py-2">De-active</a>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('admin/sub/edit/'.$blog->sub_id) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="{{ url('admin/sub/delete/'.$blog->sub_id) }}" onclick="return confirm('Are you sure you want to delete this?')">
                                                            <i class="bi bi-trash me-2"></i>Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No sub categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($blogs->count() > 0)
                        <div class="mt-3">
                            @if(isset($data))
                                {{ $blogs->appends($data)->links('pagination::bootstrap-4') }}
                            @else
                                {{ $blogs->links('pagination::bootstrap-4') }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
