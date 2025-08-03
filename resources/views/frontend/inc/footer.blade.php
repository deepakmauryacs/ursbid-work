<!-- FOOTER AREA START -->
<footer class="ltn__footer-area  ">
    <div class="footer-top-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-about-widget">
                        <div class="bsdk">
                        <div class="footer-logo">
                            <div class="site-logo">
                                <img src="{{url('assets/front/img/logo.png')}}" alt="Logo">
                            </div>
                        </div>
                       
                        </div>
                        <div class="footer-address">
                            <ul>
                                <li>
                                    
                                    <div class="footer-address-info">
                                    <p>Connecting Dreams To Reality.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="footer-address-icon">
                                        <i class="icon-placeholder"></i>
                                    </div>
                                    <div class="footer-address-info">
                                        <p>Village - Parewpur, Post - Dharshawa, District - Shrawasti, Uttar Pradesh,
                                            271835</p>
                                    </div>
                                </li>
</ul>
<ul class="tmc">
                                <li>
                                    <div class="footer-address-icon">
                                        <i class="icon-call"></i>
                                    </div>
                                    <div class="footer-address-info">
                                        <p><a href="tel:+919984555300">+91 9984555300</a></p>
                                    </div>

                                    <div class="footer-address-icon">
                                        <i class="icon-call"></i>
                                    </div>
                                    <div class="footer-address-info">
                                        <p><a href="tel:+919984555400">+91 9984555400</a></p>
                                    </div>
                                </li>
                               
                                <li>
                                    <div class="footer-address-icon">
                                        <i class="icon-mail"></i>
                                    </div>
                                    <div class="footer-address-info">
                                        <p><a href="mailto:info@ursbid.com">info@ursbid.com</a></p>
                                    </div>
                                    <!-- <div class="footer-address-icon">
                                        <i class="icon-mail"></i>
                                    </div> -->
                                    <!-- <div class="footer-address-info">
                                        <p><a href="mailto:support@ursbid.com">support@ursbid.com</a></p>
                                    </div> -->
                                </li>
                               
                            </ul>
                        </div>
                        <div class="ltn__social-media mt-20">
                            <ul>
                                <li><a target="_blank" href="https://www.facebook.com/profile.php?viewas=100000686899395&id=61566764819246" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a target="_blank" href="https://www.instagram.com/urs_bid1195/" title="instagram"><i class="fab fa-instagram"></i></a></li>
                                <li><a target="_blank" href="https://www.linkedin.com/company/ursbid/about/?viewAsMember=true" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                                <li><a target="_blank" href="https://www.youtube.com/@URSBID" title="Youtube"><i class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @php
 $currentDate = \Carbon\Carbon::now();
@endphp
          @php 
          
           $active = DB::table('qutation_form')->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?", [$currentDate])->count();
           $total = DB::table('qutation_form')->count();
          @endphp

                <div class="col-xl-4 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-menu-widget clearfix pdy">
                        <h4 class="footer-title">More </h4>
                        <div class="footer-menu">
                               <ul>
                                <li> Active Bidding  <b class="bc"> {{ $active }}</b></li>
                                <li> Total  Bidding <b class="bc"> {{ $total }}</b></li>
                                   
                                    </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ltn__copyright-area">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="ltn__copyright-design clearfix">
                        <p>@ Copyright <span class="current-year"></span>. All Rights Reserved By Ursbid  & Designed by <a
                                href="https://cssfounder.com" target="_blank">CssFounder.com</a> </p>
                    </div>
                </div>
                <div class="col-md-6 col-12 align-self-center">
                    <div class="ltn__copyright-menu">
                        <ul>
                        <li><a href="{{ url('terms-conditions')}}">Terms & Conditions</a></li>
                            <li><a href="{{ url('disclaimer')}}">Disclaimer</a></li>
                            <li><a href="{{ url('privacy-policy')}}">Privacy & Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER AREA END -->

<!-- MODAL AREA START (Quick View Modal) -->
<div class="ltn__modal-area ltn__quick-view-modal-area">
    <div class="modal fade" id="quick_view_modal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <!-- <i class="fas fa-times"></i> -->
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ltn__quick-view-modal-inner">
                        <div class="modal-product-item">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="modal-product-img">
                                        <img src="{{url('assets/front/img/product/4.png')}}" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="modal-product-info">
                                        <div class="product-ratting">
                                            <ul>
                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                <li><a href="#"><i class="far fa-star"></i></a></li>
                                                <li class="review-total"> <a href="#"> ( 95 Reviews )</a></li>
                                            </ul>
                                        </div>
                                        <h3><a href="#">3 Rooms Manhattan</a></h3>
                                        <div class="product-price">
                                            <span>$34,900</span>
                                            <del>$36,500</del>
                                        </div>
                                        <hr>
                                        <div class="modal-product-brief">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos
                                                repellendus repudiandae incidunt quidem pariatur expedita, quo quis modi
                                                tempore non.</p>
                                        </div>
                                        <div class="modal-product-meta ltn__product-details-menu-1 d-none">
                                            <ul>
                                                <li>
                                                    <strong>Categories:</strong>
                                                    <span>
                                                        <a href="#">Parts</a>
                                                        <a href="#">Car</a>
                                                        <a href="#">Seat</a>
                                                        <a href="#">Cover</a>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="ltn__product-details-menu-2 d-none">
                                            <ul>
                                                <li>
                                                    <div class="cart-plus-minus">
                                                        <input type="text" value="02" name="qtybutton"
                                                            class="cart-plus-minus-box">
                                                    </div>
                                                </li>
                                                <li>
                                                    <a href="#" class="theme-btn-1 btn btn-effect-1" title="Add to Cart"
                                                        data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                        <i class="fas fa-shopping-cart"></i>
                                                        <span>ADD TO CART</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- <hr> -->
                                        <div class="ltn__product-details-menu-3">
                                            <ul>
                                                <li>
                                                    <a href="#" class="" title="Wishlist" data-bs-toggle="modal"
                                                        data-bs-target="#liton_wishlist_modal">
                                                        <i class="far fa-heart"></i>
                                                        <span>Add to Wishlist</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="" title="Compare" data-bs-toggle="modal"
                                                        data-bs-target="#quick_view_modal">
                                                        <i class="fas fa-exchange-alt"></i>
                                                        <span>Compare</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <hr>
                                        <div class="ltn__social-media">
                                            <ul>
                                                <li>Share:</li>
                                                <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                                </li>
                                                <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                                <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a>
                                                </li>
                                                <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                                                </li>

                                            </ul>
                                        </div>
                                        <label class="float-right mb-0"><a class="text-decoration" href="#"><small>View
                                                    Details</small></a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL AREA END -->

<!-- MODAL AREA START (Add To Cart Modal) -->
<div class="ltn__modal-area ltn__add-to-cart-modal-area">
    <div class="modal fade" id="add_to_cart_modal" tabindex="-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ltn__quick-view-modal-inner">
                        <div class="modal-product-item">
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-product-img">
                                        <img src="{{url('assets/front/img/product/1.png')}}" alt="#">
                                    </div>
                                    <div class="modal-product-info">
                                        <h5><a href="#">Brake Conversion Kit</a></h5>
                                        <p class="added-cart"><i class="fa fa-check-circle"></i> Successfully added to
                                            your Cart</p>
                                        <div class="btn-wrapper">
                                            <a href="#" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                                            <a href="#" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                                        </div>
                                    </div>
                                    <!-- additional-info -->
                                    <div class="additional-info d-none">
                                        <p>We want to give you <b>10% discount</b> for your first order, <br> Use
                                            discount code at checkout</p>
                                        <div class="payment-method">
                                            <img src="{{url('assets/front/img/icons/payment.png')}}" alt="#">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL AREA END -->

<!-- MODAL AREA START (Wishlist Modal) -->
<div class="ltn__modal-area ltn__add-to-cart-modal-area">
    <div class="modal fade" id="liton_wishlist_modal" tabindex="-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ltn__quick-view-modal-inner">
                        <div class="modal-product-item">
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-product-img">
                                        <img src="{{url('assets/front/img/product/7.png')}}" alt="#">
                                    </div>
                                    <div class="modal-product-info">
                                        <h5><a href="#">Brake Conversion Kit</a></h5>
                                        <p class="added-cart"><i class="fa fa-check-circle"></i> Successfully added to
                                            your Wishlist</p>
                                        <div class="btn-wrapper">
                                            <a href="#" class="theme-btn-1 btn btn-effect-1">View Wishlist</a>
                                        </div>
                                    </div>
                                    <!-- additional-info -->
                                    <div class="additional-info d-none">
                                        <p>We want to give you <b>10% discount</b> for your first order, <br> Use
                                            discount code at checkout</p>
                                        <div class="payment-method">
                                            <img src="{{url('assets/front/img/icons/payment.png')}}" alt="#">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL AREA END -->