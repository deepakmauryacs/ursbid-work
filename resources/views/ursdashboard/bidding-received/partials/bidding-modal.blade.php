<div class="modal fade" id="buyerBidModal" tabindex="-1" role="dialog" aria-labelledby="buyerBidModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyerBidModalTitle">Submit Bidding Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/bidding_price') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $sellerEmail }}" name="seller_email">
                    <input type="hidden" name="product_id" class="product_id form-control">
                    <input type="hidden" name="product_name" class="product_name form-control">
                    <input type="hidden" name="user_email" class="user_email form-control">
                    <input type="hidden" name="data_id" class="data_id form-control">
                    <label class="form-label">Enter Price</label>
                    <input type="text" name="price" required class="price form-control" placeholder="Enter Price">
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
