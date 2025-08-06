@extends('ursbid-admin.layouts.app')
@section('title', 'Edit ' . $userType)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit {{ $userType }}</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.accounts.index', $type) }}">{{ $userType }} List</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Edit {{ $userType }}</h4>
                </div>
                <form id="userForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            <div class="invalid-feedback" data-field="name"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            <div class="invalid-feedback" data-field="email"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
                            <div class="invalid-feedback" data-field="phone"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $user->status == '2' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="invalid-feedback" data-field="status"></div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#userForm').on('submit', function(e){
        e.preventDefault();
        $('.invalid-feedback').text('');
        $.ajax({
            url: '{{ route('super-admin.accounts.update', [$type, $user->id]) }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        $('[data-field="'+field+'"]').text(errors[field][0]);
                    }
                }else{
                    toastr.error('Update failed');
                }
            }
        });
    });
});
</script>
@endpush
