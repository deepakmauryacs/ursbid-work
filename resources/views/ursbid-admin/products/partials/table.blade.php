<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead class="bg-light-subtle">
            <tr>
                <th>S.No</th>
                <th>Product Title</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Status</th>
                <th style="width: 130px;">Action</th>
            </tr>
        </thead>
        <tbody>
        @php
            $start = ($products->currentPage() - 1) * $products->perPage();
        @endphp

        @forelse($products as $index => $product)
            <tr id="row-{{ $product->id }}">
                <td>{{ $start + $index + 1 }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <img
                                src="{{ $product->image ? asset('public/'.$product->image) : asset('public/uploads/no-image.jpg') }}"
                                alt="{{ $product->title }}"
                                class="avatar-md rounded border border-light border-3"
                                style="object-fit: cover; width:48px; height:48px;">
                        </div>
                        <div>
                            <span class="text-dark fw-medium fs-15">{{ $product->title }}</span>
                        </div>
                    </div>
                </td>
                <td>{{ $product->category_name }}</td>
                <td>{{ $product->sub_name }}</td>
                <td>
                    @if($product->status == 1)
                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.products.edit', $product->id) }}" class="btn btn-soft-primary btn-sm">
                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                        </a>
                        <button type="button"
                                data-id="{{ $product->id }}"
                                data-url="{{ route('super-admin.products.destroy', $product->id) }}"
                                class="btn btn-soft-danger btn-sm deleteBtn">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No products found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<x-paginationwithlength :paginator="$products" />

