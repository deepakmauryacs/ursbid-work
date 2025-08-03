<style>
   .selectchange {
   width: 202px;
   }
</style>
<header class="star_head">
   <div class="container">
      <div class="head">
         <div class="name_logo">
            <div class="logo">
               <a href="{{url('')}}"><img src="{{url('assets/front/img/logo.png')}}"> </a>
            </div>
            <button class="mb-sh">
               <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                     d="M10.5 18C14.6421 18 18 14.6421 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18Z"
                     stroke="#717478" stroke-width="1.4" stroke-linecap="round"
                     stroke-linejoin="round"></path>
                  <path d="M16 16L21 21" stroke="#717478" stroke-width="1.4" stroke-linecap="round"
                     stroke-linejoin="round"></path>
               </svg>
            </button>
            <div class="_3NorZ0 hidden">
               <form class="_2rslOn" action="{{url('search/')}}" method="GET">
                  @csrf
                  <div class="_1sFryS">
                     <div class="_2SmNnR">
                        <input class="Pke_EE" type="text" title="Search for Products, Brands and More"
                           name="search" value="{{ isset($data['keyword']) ? $data['keyword'] : '' }}" autocomplete="off" placeholder="Search for Products">
                     </div>
                     <select name='category' class='selectchange'>
                        <option value=''>Select Category</option>
                        @php
                        $category = DB::select("SELECT * FROM category WHERE status = 1 ");
                        foreach ($category as $cat) {
                        $cat_id = $cat->id;
                        $cat_slug = $cat->slug;
                        @endphp
                        <option value="{{ $cat->id }}" {{ isset($data['category']) && $data['category'] == $cat->id ? 'selected' : '' }}>
                        {{ $cat->title }}
                        </option>
                        @php } @endphp
                     </select>
                     <button class="_2iLD__" aria-label="Search for Products, Brands and More"
                        title="Search for Products, Brands and More">
                        <svg width="24" height="24" class="" viewBox="0 0 24 24" fill="none"
                           xmlns="http://www.w3.org/2000/svg">
                           <title>Search Icon</title>
                           <path
                              d="M10.5 18C14.6421 18 18 14.6421 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18Z"
                              stroke="#717478" stroke-width="1.4" stroke-linecap="round"
                              stroke-linejoin="round"></path>
                           <path d="M16 16L21 21" stroke="#717478" stroke-width="1.4" stroke-linecap="round"
                              stroke-linejoin="round"></path>
                        </svg>
                     </button>
                  </div>
               </form>
            </div>
         </div>
         <div class="_2msBFL">
            <div class="all_drop">
               @php
               $seller = session('seller');
               $email = $seller ? $seller->email : null;
               @endphp
               <div class="btn_icos">
                  <div class="icon">
                     <img src="{{ url('assets/front/img/svg/profile-52e0dc.svg') }}" alt="Profile Icon">
                  </div>
                  @if ($email)
                  <span>Dashboard</span>
                  <a href="{{ url('seller-dashboard') }}" class="all_fit"></a>
                  @else
                  <span>Login</span>
                  <a href="#" class="all_fit"></a>
                  @endif
               </div>
               @unless ($email)
               <div class="login_drops">
                  <ul>
                     <li>
                        <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <img src="{{ url('assets/front/img/svg/profile-52e0dc.svg') }}" alt="Profile Icon">
                        Seller/ Contractor Login
                        </a>
                     </li>
                     <li>
                        <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <img src="{{ url('assets/front/img/svg/profile-52e0dc.svg') }}" alt="Profile Icon">
                        Buyer/ Client Login
                        </a>
                     </li>
                  </ul>
               </div>
               @endunless
            </div>
            <div class="dots_login">
               <div class="icon">
                  <img src="{{url('assets/front/img/dots.png')}}">
               </div>
               <div class="contact_drop">
                  <ul>
                     <li><a href="{{url('customer-support')}}"><img
                        src="{{url('assets/front/img/svg/customer.svg')}}">
                        Customer Care </a>
                     </li>
                     <li><a href="{{url('advertise')}}"><img src="{{url('assets/front/img/svg/advertising.svg')}}"> Advertise </a>
                     </li>
                     <li><a href="{{url('contact-detail')}}"><img
                        src="{{url('assets/front/img/svg/contact.svg')}}">
                        Contact Details </a>
                     </li>
                     <li><a href="{{ url('about') }}"><img
                        src="{{url('assets/front/img/svg/contact.svg')}}">
                        About us </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>
</header>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
   aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Login Here</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            @if (session('alert'))
            <script>
               alert("{{ session('alert') }}");
            </script>
            @endif
            <form action="{{ url('/seller-login') }}" method="POST" class="">
               @csrf
               <div class="block_in">
                  <label class="label">Email</label>
                  <input type="email" name="email" autocomplete="off" placeholder="Email*"
                     value="{{ old('email') }}">
               </div>
               <div class="block_in">
                  <label class="label">Password</label>
                  <input type="password" name="password" autocomplete="off" value="{{ old('password') }}"
                     placeholder="Password*">
               </div>
               @if($errors->any())
               <div class='text-danger'> Please Provide Correct Details.</div>
               @endif
               <div class="go-to-btn mt-20">
                  <a href="{{ url('/forgot-password') }}"><small>Forgot your password?</small></a>
               </div>
               <div class="btn-wrapper">
                  <button class="my_bnty" type="submit">Login</button>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            Don't have an account yet?
            <a href="{{url('seller-register')}}">Sign Up</a>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
   aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Login Here</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form action="{{ url('/buyer-login') }}" method="POST" class="">
               @csrf
               <div class="block_in">
                  <label class="label">Email</label>
                  <input type="email" name="email" placeholder="Email*" value="{{ old('email') }}">
               </div>
               <div class="block_in">
                  <label class="label">Password</label>
                  <input type="password" name="password" value="{{ old('password') }}" placeholder="Password*">
               </div>
               @if($errors->any())
               <div class='text-danger'> Please Provide Correct Details.</div>
               @endif
               <div class="btn-wrapper">
                  <button class="my_bnty" type="submit">Login</button>
               </div>
               <div class="go-to-btn mt-20">
                  <a href="#"><small>Forgot your password?</small></a>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            Don't have an account yet?
            <a href="{{url('buyer-register')}}">Sign Up</a>
         </div>
      </div>
   </div>
</div>
<script>
   let old = document.querySelector("._3NorZ0");
   let mob = document.querySelector(".mb-sh");
   
   mob.addEventListener("click", function() {
       old.classList.toggle("hidden");
   });
</script>