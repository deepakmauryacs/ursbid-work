@extends ('admin.layout')
@section('title', 'Dashboard')
<!--  -->
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title"> Dashboard</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <!-- <div class="action-btn">

                                        <div class="form-group mb-0">
                                            <div class="input-container icon-left position-relative">
                                                <span class="input-icon icon-left">
                                                    <span data-feather="calendar"></span>
                                                </span>
                                                <input type="text" class="form-control form-control-default date-ranger" name="date-ranger" placeholder="Oct 30, 2019 - Nov 30, 2019">
                                                <span class="input-icon icon-right">
                                                    <span data-feather="chevron-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div> -->
                        <!-- <div class="dropdown action-btn">
                                        <button class="btn btn-sm btn-default btn-white dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-download"></i> Export
                                        </button>
                                       
                                    </div>
                                    <div class="dropdown action-btn">
                                        <button class="btn btn-sm btn-default btn-white dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-share"></i> Share
                                        </button>
                                        
                                    </div>
                                    <div class="action-btn">
                                        <a href="#" class="btn btn-sm btn-primary btn-add">
                                            <i class="la la-plus"></i> Add New</a>
                                    </div> -->
                    </div>
                </div>

            </div>

        </div>

        <div class="form-element d-flex">
            <div class="col-md-3 mb-25">
                <div class="ratio-box card">
                    <div class="card-body">
                        <h6 class="ratio-title">Total Contractor</h6>
                        <div class="ratio-info d-flex justify-content-between align-items-center">
                            @php
                            $query1 = DB::table('seller')
                            ->where('verify', 1)
                            ->whereRaw('FIND_IN_SET(?, acc_type)', [2])->count();
                            @endphp
                            <h1 class="ratio-point color-success">{{ $query1 }}</h1>
                            <!-- <span class="ratio-percentage color-success">180%</span> -->
                        </div>
                        <!-- <div class="progress-wrap mb-0">

                            <span class="progress-text">
                                <span class="color-dark dark">1 or higher</span>
                                <span class="progress-target">quick ratio target</span>
                            </span>
                        </div> -->
                    </div>
                </div>

            </div>
            <div class="col-md-3 mb-25">
                <div class="ratio-box card">
                    <div class="card-body">
                        <h6 class="ratio-title">Total Seller</h6>
                        <div class="ratio-info d-flex justify-content-between align-items-center">
                            @php
                            $query1 = DB::table('seller')
                            ->where('verify', 1)
                            ->whereRaw('FIND_IN_SET(?, acc_type)', [1])->count();
                            @endphp
                            <h1 class="ratio-point color-success">{{ $query1 }}</h1>
                            <!-- <span class="ratio-percentage color-success">180%</span> -->
                        </div>
                        <!-- <div class="progress-wrap mb-0">

                            <span class="progress-text">
                                <span class="color-dark dark">1 or higher</span>
                                <span class="progress-target">quick ratio target</span>
                            </span>
                        </div> -->
                    </div>
                </div>

            </div>
            <div class="col-md-3 mb-25">
                <div class="ratio-box card">
                    <div class="card-body">
                        <h6 class="ratio-title">Total Client</h6>
                        <div class="ratio-info d-flex justify-content-between align-items-center">
                            @php
                            $query1 = DB::table('seller')
                            ->where('verify', 1)
                            ->whereRaw('FIND_IN_SET(?, acc_type)', [3])->count();
                            @endphp
                            <h1 class="ratio-point color-success">{{ $query1 }}</h1>
                            <!-- <span class="ratio-percentage color-success">180%</span> -->
                        </div>
                        <!-- <div class="progress-wrap mb-0">

                            <span class="progress-text">
                                <span class="color-dark dark">1 or higher</span>
                                <span class="progress-target">quick ratio target</span>
                            </span>
                        </div> -->
                    </div>
                </div>

            </div>
            <div class="col-md-3 mb-25">
                <div class="ratio-box card">
                    <div class="card-body">
                        <h6 class="ratio-title">Total Buyer</h6>
                        <div class="ratio-info d-flex justify-content-between align-items-center">
                            @php
                            $query1 = DB::table('seller')
                            ->where('verify', 1)
                            ->whereRaw('FIND_IN_SET(?, acc_type)', [4])->count();
                            @endphp
                            <h1 class="ratio-point color-success">{{ $query1 }}</h1>
                            <!-- <span class="ratio-percentage color-success">180%</span> -->
                        </div>
                        <!-- <div class="progress-wrap mb-0">
                                <span class="progress-text">
                                <span class="color-dark dark">1 or higher</span>
                                <span class="progress-target">quick ratio target</span>
                                </span>
                            </div> -->
                    </div>
                </div>

            </div>


        </div>
        <div class="row">
            <div class="col-md-12 mb-25">
                <h3>Users Added Each Month </h3>
                <?php
      

        $monthlyUsers = DB::table('seller')
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as user_count'))
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year', 'asc')
            ->where('verify', 1)
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $data = [];
        foreach ($monthlyUsers as $record) {
            $labels[] = $record->year . '-' . str_pad($record->month, 2, '0', STR_PAD_LEFT);
            $data[] = $record->user_count;
        }
    ?>

                <div style="width: 100%; margin: 0 auto;">
                    <canvas id="monthlyUsersChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                var ctx = document.getElementById('monthlyUsersChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar', // Changed to 'bar' for vertical bar chart
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: 'Users Added Each Month',
                            data: <?php echo json_encode($data); ?>,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'category',
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                </script>
            </div>

        </div>



        <div class="row">
            <div class="col-md-12 mb-25">

            <h3>
            Total bidding raised
            </h3>
            <div style="width: 100%; margin: 50px auto;">
        <canvas id="monthlyPricesChart"></canvas>
    </div>

    <?php
  

    $monthlyPrices = DB::table('bidding_price')
        ->select(DB::raw('SUM(price) as total_price'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
        ->where('action', 1)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    $labels = [];
    $data = [];

    foreach ($monthlyPrices as $monthlyPrice) {
        $labels[] = $monthlyPrice->month;
        $data[] = $monthlyPrice->total_price;
    }
    ?>

    <script>
        var ctx = document.getElementById('monthlyPricesChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Price',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
            </div>
        </div>








    </div>
</div>
@endsection