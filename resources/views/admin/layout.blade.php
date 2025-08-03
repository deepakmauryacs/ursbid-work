<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- inject:css-->

    <link rel="stylesheet" href="{{url('assets/css/plugin.min.css')}}">

    <link rel="stylesheet" href="{{url('assets/style.css')}}">

    <!-- endinject -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{url('assets/front/img/logo.png')}}">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</head>

<body class="layout-light side-menu overlayScroll">
    <div class="mobile-search">
        <form class="search-form">
            <span data-feather="search"></span>
            <input class="form-control mr-sm-2 box-shadow-none" type="text" placeholder="Search...">
        </form>
    </div>

    <div class="mobile-author-actions"></div>
    <header class="header-top">
        <nav class="navbar navbar-light">
            <div class="navbar-left">
                <a href="#" class="sidebar-toggle">
                    <img class="svg" src="{{url('assets/img/svg/bars.svg')}}" alt="img"></a>
                <a class="navbar-brand" href="{{ url('/') }}"><img class="dark" src="{{url('assets/front/img/logo.png')}}" style='width:100px; height:70px; object-fit:contain;'
                        alt="svg"><img class="light" src="{{url('assets/front/img/logo.png')}}" style='width:100px; height:70px; object-fit:contain;' alt="img"></a>

                <div class="top-menu">



                </div>
            </div>
            <!-- ends: navbar-left -->

            <div class="navbar-right">
                <ul class="navbar-right__menu">




                    <li class="nav-support">
                        <div class="dropdown-custom">
                            <a href="{{ url('admin/logout') }}" class="nav-item-toggle btn-sm btn btn-primary text-white">
                                Logout</a>

                        </div>
                    </li>
                    <!-- ends: .nav-support -->
                    <li class="nav-flag-select">
                        <div class="dropdown-custom">
                            <a href="javascript:;" class="nav-item-toggle"><img src="img/flag.png" alt=""
                                    class="rounded-circle"></a>
                            <div class="dropdown-wrapper dropdown-wrapper--small">
                                <a href="#"><img src="img/eng.png" alt=""> English</a>
                                <a href="#"><img src="img/ger.png" alt=""> German</a>
                                <a href="#"><img src="img/spa.png" alt=""> Spanish</a>
                                <a href="#"><img src="img/tur.png" alt=""> Turkish</a>
                            </div>
                        </div>
                    </li>
                    <!-- ends: .nav-flag-select -->
                    <li class="nav-author">
                        <div class="dropdown-custom">
                            <a href="javascript:;" class="nav-item-toggle"><img src="img/author-nav.jpg" alt=""
                                    class="rounded-circle"></a>
                            <div class="dropdown-wrapper">
                                <div class="nav-author__info">
                                    <div class="author-img">
                                        <img src="img/author-nav.jpg" alt="" class="rounded-circle">
                                    </div>
                                    <div>
                                        <h6>Admin</h6>
                                        <!-- <span>UI Designer</span> -->
                                    </div>
                                </div>
                                <div class="nav-author__options">
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <span data-feather="user"></span> Profile</a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span data-feather="settings"></span> Settings</a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span data-feather="key"></span> Billing</a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span data-feather="users"></span> Activity</a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span data-feather="bell"></span> Help</a>
                                        </li>
                                    </ul>
                                    <a href="#" class="nav-author__signout">
                                        <span data-feather="log-out"></span> Sign Out</a>
                                </div>
                            </div>
                            <!-- ends: .dropdown-wrapper -->
                        </div>
                    </li>
                    <!-- ends: .nav-author -->
                </ul>
                <!-- ends: .navbar-right__menu -->
                <div class="navbar-right__mobileAction d-md-none">
                    <a href="#" class="btn-search">
                        <span data-feather="search"></span>
                        <span data-feather="x"></span></a>
                    <a href="#" class="btn-author-action">
                        <span data-feather="more-vertical"></span></a>
                </div>
            </div>
            <!-- ends: .navbar-right -->
        </nav>
    </header>
    <main class="main-content">

        <aside class="sidebar-wrapper">
            <div class="sidebar sidebar-collapse" id="sidebar">
                <div class="sidebar__menu-group">
                    <ul class="sidebar_nav">
                        <li class="menu-title">
                            <span>Main menu</span>
                        </li>
                        <li class="">
                            <a href="{{ url('dashboard') }}" class="active">
                                <span data-feather="home" class="nav-icon"></span>
                                <span class="menu-text">Dashboard</span>
                                <span class="toggle-icon"></span>
                            </a>

                        </li>
                        <!-- <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Blogs</span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/blog/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Blogs List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li> -->
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Category</span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/category/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Category List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Sub category </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/sub/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Sub Category List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                       
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Product </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/product/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Product List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>

                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Brand category </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/super/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Brand Category List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Unit </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/unit/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Unit List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Buyer / Seller </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/register/userlist') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Client List</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('admin/register/sellerlist') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Seller List</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('admin/register/buyerlist') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Buyer List</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('admin/register/contractorlist') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Contractor List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Testimonial </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/testimonial/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Testimonial List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>


                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Quotation </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/enquiry/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Active Quotation List</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('admin/enquiry/deactivelist') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Closed Quotation List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Accounting  </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/enquiry/biddlist') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Accounting List</span>
                                    </a>
                                </li>
                             
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Bidding  </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('accounting-ad/buyer-order') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Accepted Bidding List</span>
                                    </a>
                                    <!-- <a href="{{ url('admin/enquiry/acceptedbidding') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Accepted Bidding List</span>
                                    </a> -->
                                </li>
                             
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Advertisement </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/advertisement/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Advertisement List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                        <li class="has-child">
                            <a href="#" class="">
                                <span data-feather="layout" class="nav-icon"></span>
                                <span class="menu-text">Youtube </span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ url('admin/yt/list') }}" class="">
                                        <span data-feather="message-square" class="nav-icon"></span>
                                        <span class="menu-text">Youtube List</span>
                                    </a>
                                </li>
                               

                            </ul>
                        </li>
                        


                        
                        
                    </ul>
                </div>
            </div>
        </aside>

        <div class="contents">

            @yield('content')

        </div>
        <footer class="footer-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="footer-copyright">
                            <p>2025 @<a href="#">Admin</a></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- <div class="footer-menu text-right">
                            <ul>
                                <li>
                                    <a href="#">About</a>
                                </li>
                                <li>
                                    <a href="#">Team</a>
                                </li>
                                <li>
                                    <a href="#">Contact</a>
                                </li>
                            </ul>
                        </div> -->
                        <!-- ends: .Footer Menu -->
                    </div>
                </div>
            </div>
        </footer>
    </main>
    <div id="overlayer">
        <span class="loader-overlay">
            <div class="atbd-spin-dots spin-lg">
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
            </div>
        </span>
    </div>
    <div class="overlay-dark-sidebar"></div>
    <div class="customizer-overlay"></div>

    <div class="customizer-wrapper">
        <div class="customizer">
            <div class="customizer__head">
                <h4 class="customizer__title">Customizer</h4>
                <span class="customizer__sub-title">Customize your overview page layout</span>
                <a href="#" class="customizer-close">
                    <span data-feather="x"></span></a>
            </div>
            <div class="customizer__body">
                <div class="customizer__single">
                    <h4>Layout Type</h4>
                    <ul class="customizer-list d-flex layout">
                        <li class="customizer-list__item">
                            <a href="https://demo.jsnorm.com/html/strikingdash/strikingdash/ltr" class="active">
                                <img src="img/ltr.png" alt="">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="customizer-list__item">
                            <a href="https://demo.jsnorm.com/html/strikingdash/strikingdash/rtl">
                                <img src="img/rtl.png" alt="">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- ends: .customizer__single -->

                <div class="customizer__single">
                    <h4>Sidebar Type</h4>
                    <ul class="customizer-list d-flex l_sidebar">
                        <li class="customizer-list__item">
                            <a href="#" data-layout="light" class="active">
                                <img src="img/light.png" alt="">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="customizer-list__item">
                            <a href="#" data-layout="dark">
                                <img src="img/dark.png" alt="">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- ends: .customizer__single -->

                <div class="customizer__single">
                    <h4>Navbar Type</h4>
                    <ul class="customizer-list d-flex l_navbar">
                        <li class="customizer-list__item">
                            <a href="#" data-layout="side" class="active">
                                <img src="img/side.png" alt="">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="customizer-list__item top">
                            <a href="#" data-layout="top">
                                <img src="img/top.png" alt="">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- ends: .customizer__single -->
            </div>
        </div>
    </div>


    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDduF2tLXicDEPDMAtC6-NLOekX0A5vlnY"></script>
    <!-- inject:js-->
    <script src="{{url('assets/js/plugins.min.js')}}"></script>
    <script src="{{url('assets/js/script.min.js')}}"></script>
    <!-- endinject-->
    @yield('script')
</body>

</html>