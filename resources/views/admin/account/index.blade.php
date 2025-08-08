@extends('admin.layout')
@section('title','Account Module')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6>Account Module</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <button class="btn btn-primary show-list" data-type="vendor">Vendor List</button>
                    <button class="btn btn-primary show-list" data-type="buyer">Buyer List</button>
                    <button class="btn btn-primary show-list" data-type="contractor">Contractor List</button>
                    <button class="btn btn-primary show-list" data-type="client">Client List</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="accounts-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div id="edit-errors" class="text-danger mb-2"></div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Account Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="view-name"></span></p>
                <p><strong>Email:</strong> <span id="view-email"></span></p>
                <p><strong>Phone:</strong> <span id="view-phone"></span></p>
                <p><strong>User Type:</strong> <span id="view-user-type"></span></p>
                <p><strong>Date:</strong> <span id="view-date"></span></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(function(){
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $('.show-list').on('click', function(){
        var type = $(this).data('type');
        $.get('/admin/accounts/list/' + type, function(data){
            var tbody = $('#accounts-table tbody');
            tbody.empty();
            data.forEach(function(item){
                tbody.append('<tr>'+
                    '<td>'+item.id+'</td>'+
                    '<td>'+item.name+'</td>'+
                    '<td>'+item.email+'</td>'+
                    '<td>'+item.phone+'</td>'+
                    '<td>'+(item.created_at_formatted ? item.created_at_formatted : '')+'</td>'+
                    '<td><button class="btn btn-sm btn-secondary edit-btn" data-id="'+item.id+'">Edit</button> '+
                    '<button class="btn btn-sm btn-info view-btn" data-id="'+item.id+'">View</button></td>'+
                    '</tr>');
            });
        });
    });

    $(document).on('click','.edit-btn',function(){
        var id = $(this).data('id');
        $.get('/admin/accounts/' + id, function(res){
            $('#edit-form').data('id', id);
            $('#edit-form [name=name]').val(res.name);
            $('#edit-form [name=email]').val(res.email);
            $('#edit-form [name=phone]').val(res.phone);
            $('#edit-errors').html('');
            $('#editModal').modal('show');
        });
    });

    $('#edit-form').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        if(!form[0].checkValidity()){
            form[0].reportValidity();
            return;
        }
        var id = form.data('id');
        $.ajax({
            url: '/admin/accounts/' + id,
            type: 'POST',
            data: form.serialize(),
            success: function(res){
                $('#editModal').modal('hide');
                $('.show-list[data-type="'+res.user_type+'"]').click();
            },
            error: function(xhr){
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    var html='';
                    $.each(xhr.responseJSON.errors, function(k,v){
                        html += v[0]+'<br>';
                    });
                    $('#edit-errors').html(html);
                }
            }
        });
    });

    $(document).on('click','.view-btn',function(){
        var id = $(this).data('id');
        $.get('/admin/accounts/' + id, function(res){
            $('#view-name').text(res.name);
            $('#view-email').text(res.email);
            $('#view-phone').text(res.phone);
            $('#view-user-type').text(res.user_type);
            $('#view-date').text(res.created_at_formatted ? res.created_at_formatted : '');
            $('#viewModal').modal('show');
        });
    });
});
</script>
@endsection

