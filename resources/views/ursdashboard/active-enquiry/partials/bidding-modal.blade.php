<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Bidding Price</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form action="{{ url('/openqotationpage') }}" method="POST" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="seller_email" value="{{ $sellerEmail }}">
               <input type="hidden" name="product_id" class="product_id form-control">
               <input type="hidden" name="product_quantity" class="product_quantity form-control">
               <input type="hidden" name="product_name" class="product_name form-control">
               <input type="hidden" name="user_email" class="user_email form-control">
               <input type="hidden" name="data_id" class="data_id form-control">

               <div class="d-flex gap-3">
                  <div class="flex-grow-1">
                     <label class="font-weight-bold">Enter Price</label>
                     <input type="number" name="price" required class="price form-control" placeholder="Enter Price" min="1">
                  </div>
                  <div>
                     <label class="font-weight-bold">Quantity</label>
                     <input readonly class="product_quantity form-control">
                  </div>
               </div>

               <div class="d-flex justify-content-between mt-3 gap-3 flex-wrap">
                  <div class="d-flex align-items-center">
                     <span class="font-weight-bold">Total :- </span>
                     <span class="total ms-2">0.00</span>
                  </div>
                  <div class="flex-grow-1">
                     <label class="font-weight-bold">Quotation file (optional)</label>
                     <input type="file" name="file" class="form-control">
                  </div>
               </div>

               <div class="d-flex mt-3 align-items-center">
                  <input type="checkbox" id="myCheckbox" required>
                  <span class="ms-2">I accept all the</span>&nbsp;
                  <a href="#!" class="exampleModalCentersmall" data-bs-toggle="modal" data-bs-target="#exampleModalCentersmall">agreement terms & condition</a>
               </div>

               <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
         </div>
      </div>
   </div>
</div>
