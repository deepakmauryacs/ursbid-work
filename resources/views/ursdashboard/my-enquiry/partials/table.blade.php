<div class="table-responsive">
   <table class="table align-middle text-nowrap table-hover table-centered mb-0">
      <thead>
         <tr>
            <th>Sr no</th>
            <th>Quotation ID</th>
           <th>Buyer / Client</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Product Name</th>
            <th>Time</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Quotation</th>
            <th>Status</th>
         </tr>
      </thead>
      <tbody>
         @forelse ($blogs as $index => $blog)
            <tr>
               <td>{{ ($blogs->currentPage() - 1) * $blogs->perPage() + $index + 1 }}</td>
               <td>{{ $blog->qutation_id ?? '-' }}</td>
               <td>{{ $blog->name }}</td>
               <td>{{ $blog->category_name }}</td>
               <td>{{ $blog->sub_name }}</td>
               <td>{{ $blog->product_name }}</td>
               <td>{{ $blog->bid_time }} day</td>
               <td>{{ $blog->date_time ? \Carbon\Carbon::parse($blog->date_time)->format('d-m-Y') : '-' }}</td>
               <td>{{ $blog->quantity }}</td>
               <td>{{ $blog->unit }}</td>
               <td>
                  @if(!empty($blog->qutation_form_image))
                     <a href="{{ url('seller/enquiry/file/'.$blog->id) }}" target="_blank">View</a>
                  @else
                     No file found
                  @endif
               </td>
               <td>
                  <span class="badge {{ $blog->status_badge === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} py-1 px-2 fs-13">
                     {{ $blog->status_badge }}
                  </span>
               </td>
            </tr>
         @empty
            <tr>
               <td colspan="12" class="text-center py-4">No enquiries found.</td>
            </tr>
         @endforelse
      </tbody>
   </table>
</div>

@if ($blogs->hasPages())
   <div class="mt-3">
      {{ $blogs->links('pagination::bootstrap-5') }}
   </div>
@endif
