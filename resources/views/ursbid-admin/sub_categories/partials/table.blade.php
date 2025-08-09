<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead class="bg-light-subtle">
            <tr>
                <th>S.No</th>
                <th>Sub Category Name</th>
                <th>Category</th>
                <th>Created</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subs as $sub)
            <tr id="row-{{ $sub->id }}">
                <td>{{ $subs->firstItem() + $loop->index }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <img src="{{ $sub->image ? asset('public/'.$sub->image) : asset('public/uploads/no-image.jpg') }}" alt="{{ $sub->name }}" class="avatar-md rounded border border-light border-3" style="object-fit: cover;">
                        </div>
                        <div>
                            <a href="#!" class="text-dark fw-medium fs-15">{{ $sub->name }}</a>
                        </div>
                    </div>
                </td>
                <td>{{ $sub->category_name }}</td>
                <td>{{ $sub->created_at ? \Carbon\Carbon::parse($sub->created_at)->format('d-m-Y') : '' }}</td>
                <td>
                    @if($sub->status == '1')
                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">
                        @if(auth()->user()->hasModulePermission('sub-categories','can_edit'))
                        <a href="{{ route('super-admin.sub-categories.edit', $sub->id) }}" class="btn btn-soft-primary btn-sm">
                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                        </a>
                        @endif
                        @if(auth()->user()->hasModulePermission('sub-categories','can_delete'))
                        <button type="button" data-id="{{ $sub->id }}" data-url="{{ route('super-admin.sub-categories.destroy', $sub->id) }}" class="btn btn-soft-danger btn-sm deleteBtn">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No sub categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<x-paginationwithlength :paginator="$subs" />
