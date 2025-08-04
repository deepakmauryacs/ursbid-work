@extends('ursbid-admin.layouts.app')
@section('title','On Page SEO')
@section('content')
<style>
.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}

.page-item {
    margin: 0;
}

.page-link {
    display: inline-block;
    padding: 5px 10px;
    color: #333;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fff;
}

.page-item.active .page-link {
    background-color: #614ce1;
    color: #fff;
    border-color: #614ce1;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #ddd;
}

.page-link:hover {
    background-color: #f8f9fa;
    color: #0056b3;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">On Page SEO List</h4>
                    </div>
                    <a href="{{ route('super-admin.on-page-seo.create') }}" class="btn btn-sm btn-primary">Add On Page SEO</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>Page URL</th>
                                <th>Page Name</th>
                                <th>Meta Title</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seos as $seo)
                            <tr id="row-{{ $seo->id }}">
                                <td>{{ $seo->page_url }}</td>
                                <td>{{ $seo->page_name }}</td>
                                <td>{{ $seo->meta_title }}</td>
                                <td>{{ $seo->created_at ? \Carbon\Carbon::parse($seo->created_at)->format('d-m-Y') : '' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('super-admin.on-page-seo.edit',$seo->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <button type="button" data-id="{{ $seo->id }}" data-url="{{ route('super-admin.on-page-seo.destroy',$seo->id) }}" class="btn btn-soft-danger btn-sm deleteBtn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-paginationwithlength :paginator="$seos" />
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(function(){
    $('.deleteBtn').on('click',function(){
        if(!confirm('Are you sure want to delete?')) return;
        let id = $(this).data('id');
        let url = $(this).data('url');
        $.ajax({
            url:url,
            type:'DELETE',
            data:{ _token:'{{ csrf_token() }}' },
            success:function(res){
                toastr.success(res.message);
                $('#row-'+id).remove();
            },
            error:function(){
                toastr.error('Unable to delete record');
            }
        });
    });
});
</script>
@endpush
