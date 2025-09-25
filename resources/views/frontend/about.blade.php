@extends('frontend.layouts.app')
@section('title', 'URSBID: A platform for Construction material seller and buyer')
@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>
    <!-- Breadcrumb -->
    <div class="ltn__breadcrumb-area text-left" style="padding: 50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title fw-bold">About Us</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li>
                                    <a href="{{ url('/') }}">
                                        <span class="ltn__secondary-color">
                                            <i class="fas fa-home"></i>
                                        </span> Home
                                    </a>
                                </li>
                                <li>About Us</li>
                            </ul>
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div>
    <!-- About Content -->
    <section class="privacy">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">

                    <div class="about-section rounded shadow-sm" style="background: #fff;padding-bottom: 70px;">
                        
                        <h2 class="fw-bold mb-3" style="color:#0c1117;">What is URSBID</h2>
                        <p class="mb-4">Welcome to <strong>URSBID</strong>, it is a platform to connect you with top-tier products and services in the <strong>construction industry</strong>. Whether you're looking to hire professionals for services like excavation work, concreting, or bricklaying, or you're searching for suppliers offering materials such as cement, bricks, concrete, and paints, <strong>URSBID</strong> is the one-stop destination for all your construction needs.</p>
                        
                        <h2 class="fw-bold mb-3" style="color:#0c1117;">Platform &amp; Services of URSBID</h2>
                        <p><strong>URSBID</strong> offers a dynamic marketplace where service providers and product suppliers can register and showcase their offerings. From skilled tradespeople providing essential services like excavation, concreting, and brick work, to suppliers dealing in high-quality building materials like cement, bricks, and paints — our platform brings together all the resources required for successful construction projects.</p>
                        <p class="mb-4">Users can easily search for, compare, and connect with trusted service providers and suppliers to ensure their projects are completed efficiently and to the highest standards. Service providers and product vendors can <em>register their businesses</em>, making them visible to a wide audience actively seeking their expertise and products.</p>

                        <h2 class="fw-bold mb-3" style="color:#0c1117;">Aim of URSBID</h2>
                        <p class="mb-4">At <strong>URSBID</strong>, we strive to streamline the construction process by making it easy for individuals and companies to find reliable professionals and products. Our goal is to create a transparent and efficient marketplace where clients can quickly locate the right services and materials for their projects, while providers and suppliers can expand their reach and grow their businesses.</p>

                        <h2 class="fw-bold mb-3" style="color:#0c1117;">Vision of URSBID</h2>
                        <p><strong>URSBID</strong> aims to become the <em>leading platform in the construction industry</em>, known for connecting people with the best service providers and suppliers. Our vision is to foster a community where quality, innovation, and efficiency are prioritized, ultimately raising the standards of the construction industry. We believe in empowering clients and professionals alike, helping them achieve their construction goals with ease and confidence.</p>

                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

@endsection
