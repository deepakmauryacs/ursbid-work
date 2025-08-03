<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Dashboard</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('frontend.inc.header-links')

</head>

<body>


    <!-- Body main wrapper start -->
    <div class="body-wrapper">
        @include('frontend.inc.header')

        <div class="ltn__utilize-overlay"></div>


        <section class="twopart">
            <div class="container">
                <div class="row">
                    <div class="heading">
                        <h3>Total Enquiry </h3>
                    </div>

                    <div class="col-sm-12">
                        <div class="padd">
                            @php
                            if($total < 1){ echo "<div class='text-danger'>Sorry, No data Found!</div>"; }else{ @endphp 
                                <div class="pro">
                                <table class="table table-border ">

                                    <head>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Product/Service</th>
                                            <th>Time</th>
                                            <th>With/Without Material</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </head>
                                    <tbody>
                                        @php
                                        $i= 1;
                                        foreach ($data as $all) {
                                        $all_id = $all->id;

                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $all->product_name }}</td>
                                            <td>{{ $all->bid_time }} day</td>
                                            <td>{{ $all->material }}</td>
                                            <td>{{ $all->quantity }}</td>
                                            <td><a href="{{ url('/price-list/'.$all_id) }}"
                                                    class=" btn-sm btn-primary">View List</a></td>
                                        </tr>
                                        @php } @endphp
                                    </tbody>
                                </table>

                        </div>
                        @php
                        }
                        @endphp
                    </div>
                </div>






            </div>
    </div>
    </section>

























    @include('frontend.inc.footer')

    </div>
    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')


</body>

</html>