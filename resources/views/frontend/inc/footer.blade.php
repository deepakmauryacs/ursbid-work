<style>
/* Custom button class matching Bootstrap's btn-primary */
.urs-btn-primary {
  display: inline-block;
  font-weight: 400;
  line-height: 1.5;
  color: #fff;
  text-align: center;
  text-decoration: none;
  vertical-align: middle;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
  background-color: #0d6efd;
  border: 1px solid #0d6efd;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, 
              border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.urs-btn-primary:hover {
  color: #fff;
  background-color: #0b5ed7;
  border-color: #0a58ca;
}

.urs-btn-primary:focus {
  color: #fff;
  background-color: #0b5ed7;
  border-color: #0a58ca;
  box-shadow: 0 0 0 0.25rem rgba(49, 132, 253, 0.5);
}

.urs-btn-primary:active {
  color: #fff;
  background-color: #0a58ca;
  border-color: #0a53be;
}

.urs-btn-primary:disabled {
  color: #fff;
  background-color: #0d6efd;
  border-color: #0d6efd;
  opacity: 0.65;
}
</style>
<!-- FOOTER AREA START -->
<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4">
                <!-- Left Side -->
                <div class="col-xl-4 col-lg-5">
                    <div class="footer-brand">
                        <img src="{{ url('assets/front/img/logo.png') }}" alt="URSBID Logo">
                        <div class="brand-name">URSBID</div>
                    </div>

                    <p class="tagline">Connecting Dreams To Reality.</p>

                    <ul class="contact-list">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Village - Parewpur, Post - Dharshawa, District - Shrawasti, Uttar Pradesh, 271835</span>
                        </li>
                        <li class="contact-inline">
                            <span><i class="bi bi-telephone-fill"></i> <a href="tel:+919984555300">+91 9984555300</a></span>
                            <span><i class="bi bi-telephone-fill"></i> <a href="tel:+919984555400">+91 9984555400</a></span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <a href="mailto:info@ursbid.com">info@ursbid.com</a>
                        </li>
                    </ul>

                    <div class="social">
                        <ul>
                            <li><a target="_blank" href="https://www.facebook.com/profile.php?viewas=100000686899395&id=61566764819246" aria-label="Facebook"><i class="bi bi-facebook"></i></a></li>
                            <li><a target="_blank" href="https://www.instagram.com/urs_bid1195/" aria-label="Instagram"><i class="bi bi-instagram"></i></a></li>
                            <li><a target="_blank" href="https://www.linkedin.com/company/ursbid/about/?viewAsMember=true" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a></li>
                            <li><a target="_blank" href="https://www.youtube.com/@URSBID" aria-label="YouTube"><i class="bi bi-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Right Side -->
                @php
                    $currentDate = \Carbon\Carbon::now();
                    $active = DB::table('qutation_form')
                        ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?", [$currentDate])
                        ->count();
                    $total = DB::table('qutation_form')->count();
                @endphp

                <div class="col-xl-8 col-lg-7">
                    <div class="right-section">
                        <!-- More -->
                        <div class="more-card">

                            <!-- Simple blue Bootstrap buttons (added) -->
                            <div class="mb-3 d-flex flex-wrap gap-2">
                                <a href="{{ url('seller-register') }}" class="urs-btn-primary">
                                     SELLER / CONTRACTOR REGISTER
                                </a>
                                <a href="{{ url('buyer-register') }}" class="urs-btn-primary">
                                     BUYER / CLIENT REGISTER
                                </a>
                            </div>

                            <h5 class="widget-title">More</h5>
                            <div class="stat-row">
                                <div class="stat-label">Active Bidding</div>
                                <div class="stat-box">{{ $active }}</div>
                            </div>
                            <div class="stat-row">
                                <div class="stat-label">Total Bidding</div>
                                <div class="stat-box">{{ $total }}</div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="link-card">
                            <h5 class="widget-title">Quick Links</h5>
                            <ul class="link-list">
                                <li>
                                    <a href="{{ url('customer-support')}}"  rel="noopener">
                                        <i class="bi bi-headset"></i> <span>Customer Care</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('advertise')}}"  rel="noopener">
                                        <i class="bi bi-megaphone"></i> <span>Advertise</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('contact-detail')}}"  rel="noopener">
                                        <i class="bi bi-telephone"></i> <span>Contact Details</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('about')}}" rel="noopener">
                                        <i class="bi bi-info-circle"></i> <span>About Us</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('blog') }}">
                                        <i class="bi bi-journal-text"></i> <span>Blog</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="copybar">
        <div class="container">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="small">
                        © <span class="current-year"></span> URSBID. All Rights Reserved.
                    </div>
                </div>
                <div class="col-md-6 linkbar">
                    <ul class="small m-0">
                        <li><a href="{{ url('terms-conditions') }}">Terms & Conditions</a></li>
                        <li><a href="{{ url('disclaimer') }}">Disclaimer</a></li>
                        <li><a href="{{ url('privacy-policy') }}">Privacy & Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set current year automatically
        (function(){ var el=document.querySelector('.current-year'); if(el) el.textContent=new Date().getFullYear(); })();
    </script>
</footer>
<!-- FOOTER AREA END -->
