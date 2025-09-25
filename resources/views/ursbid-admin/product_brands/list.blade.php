@extends('ursbid-admin.layouts.app')
@section('title', 'Product Brands')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Product Brands</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Product Brands</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Product Brands List</h4>
                    </div>
                    <a href="{{ route('super-admin.product-brands.create') }}" class="btn btn-sm btn-primary">Add Product Brand</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Product</th>
                                <th>Brand</th> {{-- image + name --}}
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            @php
                                $img = $brand->image ?? null;
                                $imgSrc = '';
                                if ($img) {
                                    // If full URL keep as-is, else prefix with /public/
                                    $imgSrc = \Illuminate\Support\Str::startsWith($img, ['http://','https://'])
                                        ? $img
                                        : asset('public/'.$img);
                                }
                            @endphp
                            <tr id="row-{{ $brand->id }}">
                                <td>{{ $brands->firstItem() + $loop->index }}</td>
                                <td>{{ $brand->category_name }}</td>
                                <td>{{ $brand->sub_name }}</td>
                                <td>{{ $brand->product_title }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($imgSrc)
                                            <img src="{{ $imgSrc }}" alt="{{ $brand->brand_name }}"  class="avatar-md rounded border border-light border-3" style="object-fit: cover;">
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">No image</span>
                                        @endif
                                        <span class="fw-semibold">{{ $brand->brand_name }}</span>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($brand->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    @if($brand->status == '1')
                                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('super-admin.product-brands.edit', $brand->id) }}" class="btn btn-soft-primary btn-sm" title="Edit">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <button type="button"
                                                data-id="{{ $brand->id }}"
                                                data-url="{{ route('super-admin.product-brands.destroy', $brand->id) }}"
                                                class="btn btn-soft-danger btn-sm deleteBtn"
                                                title="Delete">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No product brands found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-paginationwithlength :paginator="$brands" />
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('.deleteBtn').on('click', function(){
        if(!confirm('Are you sure want to delete?')) return;
        let id = $(this).data('id');
        let url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res){
                toastr.success(res.message || 'Deleted');
                $('#row-'+id).remove();
            },
            error: function(xhr){
                let msg = 'Unable to delete record';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                toastr.error(msg);
            }
        });
    });
});
</script>
@endpush
