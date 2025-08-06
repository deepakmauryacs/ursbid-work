<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ route('super-admin.dashboard') }}" class="logo-dark">
            <img src="{{ asset('public/assets/images/logo-sm.png?v=1.0.3') }}" class="logo-sm" alt="logo sm">
            <img src="{{ asset('public/assets/images/logo-dark.png?v=1.0.3') }}" class="logo-lg" alt="logo dark">
        </a>
    
        <a href="{{ route('super-admin.dashboard') }}" class="logo-light">
            <img src="{{ asset('public/assets/images/logo-sm.png?v=1.0.3') }}" class="logo-sm" alt="logo sm">
            <img src="{{ asset('public/assets/images/logo-light.png?v=1.0.3') }}" class="logo-lg" alt="logo light">
        </a>
    </div>


    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <i class="ri-menu-2-line fs-24 button-sm-hover-icon"></i>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title">Menu</li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin-dashboard*') ? 'active' : '' }}" href="{{ route('super-admin.dashboard') }}">
                    <span class="nav-icon">
                        <i class="ri-dashboard-2-line"></i>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/categories*') || request()->is('super-admin/sub-categories*') ? 'active' : '' }}" 
                   href="#sidebarCategories" data-bs-toggle="collapse" role="button" 
                   aria-expanded="{{ request()->is('super-admin/categories*') || request()->is('super-admin/sub-categories*') ? 'true' : 'false' }}" 
                   aria-controls="sidebarCategories">
                    <span class="nav-icon">
                        <i class="ri-list-check-2"></i>
                    </span>
                    <span class="nav-text"> Categories </span>
                </a>
                <div class="collapse {{ request()->is('super-admin/categories*') || request()->is('super-admin/sub-categories*') ? 'show' : '' }}" 
                     id="sidebarCategories">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/categories*') ? 'active' : '' }}" 
                               href="{{ route('super-admin.categories.index') }}">Category</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/sub-categories*') ? 'active' : '' }}" 
                               href="{{ route('super-admin.sub-categories.index') }}">Sub Category</a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/products/create') || request()->is('super-admin/products') ? 'active' : '' }}" 
                   href="#sidebarProducts" data-bs-toggle="collapse" role="button" 
                   aria-expanded="{{ request()->is('super-admin/products*') ? 'true' : 'false' }}" 
                   aria-controls="sidebarProducts">
                    <span class="nav-icon">
                        <i class="ri-store-2-line"></i>
                    </span>
                    <span class="nav-text"> Products </span>
                </a>
                <div class="collapse {{ request()->is('super-admin/products*') ? 'show' : '' }}" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/products/create') ? 'active' : '' }}" 
                               href="{{ route('super-admin.products.create') }}">Create Product</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/products') ? 'active' : '' }}" 
                               href="{{ route('super-admin.products.index') }}">Product List</a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/blogs*') ? 'active' : '' }}" href="#sidebarBlog" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('super-admin/blogs*') ? 'true' : 'false' }}" aria-controls="sidebarBlog">
                     <span class="nav-icon">
                          <i class="ri-news-line"></i>
                     </span>
                     <span class="nav-text">Blog ( CMS ) </span>
                </a>
                <div class="collapse {{ request()->is('super-admin/blogs*') ? 'show' : '' }}" id="sidebarBlog">
                     <ul class="nav sub-navbar-nav">
                          <li class="sub-nav-item">
                               <a class="sub-nav-link {{ request()->is('super-admin/blogs/create') ? 'active' : '' }}" href="{{ route('super-admin.blogs.create') }}">Create Blog</a>
                          </li>
                           <li class="sub-nav-item">
                               <a class="sub-nav-link {{ request()->is('super-admin/blogs') ? 'active' : '' }}" href="{{ route('super-admin.blogs.index') }}">Blog List</a>
                          </li>
                     </ul>
                </div>
           </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/on-page-seo*') ? 'active' : '' }}" href="#sidebarSeo" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('super-admin/on-page-seo*') ? 'true' : 'false' }}" aria-controls="sidebarSeo">
                    <span class="nav-icon">
                        <i class="ri-search-eye-line"></i>
                    </span>
                    <span class="nav-text">On Page SEO</span>
                </a>
                <div class="collapse {{ request()->is('super-admin/on-page-seo*') ? 'show' : '' }}" id="sidebarSeo">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/on-page-seo/create') ? 'active' : '' }}" href="{{ route('super-admin.on-page-seo.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/on-page-seo') ? 'active' : '' }}" href="{{ route('super-admin.on-page-seo.index') }}">List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/accounts*') ? 'active' : '' }}" href="#sidebarAccounts" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('super-admin/accounts*') ? 'true' : 'false' }}" aria-controls="sidebarAccounts">
                    <span class="nav-icon">
                        <i class="ri-user-3-line"></i>
                    </span>
                    <span class="nav-text">Account Module</span>
                </a>
                <div class="collapse {{ request()->is('super-admin/accounts*') ? 'show' : '' }}" id="sidebarAccounts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/accounts/vendors*') ? 'active' : '' }}" href="{{ route('super-admin.accounts.index', 'vendors') }}">Vendor List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/accounts/buyers*') ? 'active' : '' }}" href="{{ route('super-admin.accounts.index', 'buyers') }}">Buyer List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/accounts/contractors*') ? 'active' : '' }}" href="{{ route('super-admin.accounts.index', 'contractors') }}">Contractor List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/accounts/clients*') ? 'active' : '' }}" href="{{ route('super-admin.accounts.index', 'clients') }}">Client List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('super-admin/youtube-links*') ? 'active' : '' }}" href="{{ route('super-admin.youtube-links.index') }}">
                    <span class="nav-icon">
                        <i class="ri-youtube-line"></i>
                    </span>
                    <span class="nav-text">Youtube Links</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/units*') ? 'active' : '' }}" href="#sidebarUnits" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('super-admin/units*') ? 'true' : 'false' }}" aria-controls="sidebarUnits">
                    <span class="nav-icon">
                        <i class="ri-ruler-line"></i>
                    </span>
                    <span class="nav-text"> Units </span>
                </a>
                <div class="collapse {{ request()->is('super-admin/units*') ? 'show' : '' }}" id="sidebarUnits">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/units/create') ? 'active' : '' }}" href="{{ route('super-admin.units.create') }}">Add Unit</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/units') ? 'active' : '' }}" href="{{ route('super-admin.units.index') }}">Unit List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/advertisements*') ? 'active' : '' }}" href="#sidebarAdvertisements" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('super-admin/advertisements*') ? 'true' : 'false' }}" aria-controls="sidebarAdvertisements">
                    <span class="nav-icon">
                        <i class="ri-advertisement-line"></i>
                    </span>
                    <span class="nav-text"> Advertisements </span>
                </a>
                <div class="collapse {{ request()->is('super-admin/advertisements*') ? 'show' : '' }}" id="sidebarAdvertisements">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/advertisements/create') ? 'active' : '' }}" href="{{ route('super-admin.advertisements.create') }}">Add Advertisement</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/advertisements') ? 'active' : '' }}" href="{{ route('super-admin.advertisements.index') }}">Advertisement List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('super-admin/product-brands*') ? 'active' : '' }}" href="#sidebarProductBrands" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('super-admin/product-brands*') ? 'true' : 'false' }}" aria-controls="sidebarProductBrands">
                    <span class="nav-icon">
                        <i class="ri-price-tag-3-line"></i>
                    </span>
                    <span class="nav-text"> Product Brands </span>
                </a>
                <div class="collapse {{ request()->is('super-admin/product-brands*') ? 'show' : '' }}" id="sidebarProductBrands">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/product-brands/create') ? 'active' : '' }}" href="{{ route('super-admin.product-brands.create') }}">Add Product Brand</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('super-admin/product-brands') ? 'active' : '' }}" href="{{ route('super-admin.product-brands.index') }}">Product Brand List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('super-admin/testimonials*') ? 'active' : '' }}" href="{{ route('super-admin.testimonials.index') }}">
                    <span class="nav-icon">
                        <i class="ri-chat-1-line"></i>
                    </span>
                    <span class="nav-text">Testimonials</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProperty" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProperty">
                     <span class="nav-icon">
                        <i class='ri-share-line'></i> 
                     </span>
                     <span class="nav-text"> Settings </span>
                </a>
                <div class="collapse" id="sidebarProperty">
                     <ul class="nav sub-navbar-nav">
                          <li class="sub-nav-item">
                               <a class="sub-nav-link" href="{{ route('super-admin.web-settings.edit') }}">General Settings</a>
                          </li>
                     </ul>
                </div>
            </li>

            



            <!-- Add other menu items similarly -->
        </ul>
    </div>
</div>
