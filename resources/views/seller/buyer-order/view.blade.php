@extends('seller.layouts.app')
@section('title', 'Detail')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
  <div class="social-dash-wrap">
    <div class="row">
      <div class="col-lg-12">
        <div class="breadcrumb-main d-flex align-items-center justify-content-between">
          <h4 class="text-capitalize breadcrumb-title mb-0">Seller List with Price</h4>
          <div class="breadcrumb-action justify-content-center flex-wrap">
            <div class="action-btn">
              <a href="{{ url('buyer/bidding-received/list') }}" class="btn btn-sm btn-primary btn-add">
                <i class="la la-plus"></i> Back
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 mb-30">
        <div class="card">
          <div class="col-md-12 mt-2"></div>

          <div class="card-body">
            <div class="table-responsive">
              @if ($total < 1)
                <div class="text-danger">Sorry, No data Found!</div>
              @else
                <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                  <thead>
                    <tr>
                      <th>Sr.No</th>
                      <th>Action</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Sub Category</th>
                      <th>Product Name</th>
                      <th>Date</th>
                      <th>File</th>
                      <th>Unit</th>
                      <th>Quantity</th>
                      <th>Rate</th>
                      <th>Total Price</th>
                      <th>Platform Fee</th>
                     
                    </tr>
                  </thead>

                  <tbody>
                    @foreach ($data as $index => $all)
                      <tr>
                        <td>{{ $index + 1 }}</td>

                        <td class="d-flex gap-2">
                          @if ($all->action == '0')
                            <a
                              class="btn btn-sm btn-success"
                              href="{{ url('accepet/' . $all->bidding_price_id . '/' . $all->data_id) }}"
                              onclick="return confirm('Are you sure?')"
                            >
                              Accept
                            </a>
                          @endif

                          <a href="{{ url('seller-profile/' . $all->seller_id) }}" class="btn btn-sm btn-primary">
                            View Profile
                          </a>
                        </td>

                        <td>{{ $all->seller_name }}</td>

                        <td>{{ $all->category_name }}</td>

                        <td>{{ $all->sub_name }}</td>

                        <td>{{ $all->product_name }}</td>

                        <td>{{ \Carbon\Carbon::parse($all->date_time)->format('Y-m-d') }}</td>

                        <td>
                          @if (!empty($all->bidding_price_filename))
                            <a href="{{ url('public/uploads/' . $all->bidding_price_filename) }}" target="_blank">View</a>
                          @else
                            <span class="text-muted">No file found</span>
                          @endif
                        </td>

                        <td>{{ $all->unit }}</td>

                        <td>{{ $all->quantity }}</td>

                        <td>{{ $all->rate }}</td>

                        <td>
                          @php
                            // Extract numeric quantity from possible "12 KG" etc.
                            $raw = (string) $all->quantity;
                            preg_match('/\d+(\.\d+)?/', $raw, $matches);
                            $qty = isset($matches[0]) ? (float) $matches[0] : 0;
                            $totalPrice = $qty * (float) $all->rate;
                          @endphp
                          {{ number_format($totalPrice, 2) }}
                        </td>

                        <td>{{ $all->price }}</td>

                        
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
            </div>
          </div>
          <!-- End: .card-body -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
