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
