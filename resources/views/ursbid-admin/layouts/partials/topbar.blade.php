<header class="">
    <div class="topbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <div class="d-flex align-items-center gap-2">
                    <!-- Menu Toggle Button -->
                    <div class="topbar-item">
                        <button type="button" class="button-toggle-menu topbar-button">
                            <i class="ri-menu-2-line fs-24"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <!-- Theme Color (Light/Dark) -->
                    <div class="topbar-item">
                        <button type="button" class="topbar-button" id="light-dark-mode">
                            <i class="ri-moon-line fs-24 light-mode"></i>
                            <i class="ri-sun-line fs-24 dark-mode"></i>
                        </button>
                    </div>

                    <!-- Notification -->
                    <div class="dropdown topbar-item">
                         <button type="button" class="topbar-button position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="ri-notification-3-line fs-24"></i>
                              <span class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">3<span class="visually-hidden">unread messages</span></span>
                         </button>
                         <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end" aria-labelledby="page-header-notifications-dropdown">
                              <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                   <div class="row align-items-center">
                                        <div class="col">
                                             <h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
                                        </div>
                                        <div class="col-auto">
                                             <a href="javascript: void(0);" class="text-dark text-decoration-underline">
                                                  <small>Clear All</small>
                                             </a>
                                        </div>
                                   </div>
                              </div>
                              <div data-simplebar style="max-height: 280px;">
                                   <!-- Item -->
                                   <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom text-wrap">
                                        <div class="d-flex">
                                             <div class="flex-shrink-0">
                                                  <img src="{{ asset('public/assets/images/users/avatar-1.jpg')}}" class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-1" />
                                             </div>
                                             <div class="flex-grow-1">
                                                  <p class="mb-0"><span class="fw-medium">Josephine Thompson </span>commented on admin panel <span>" Wow 😍! this admin looks good and awesome design"</span></p>
                                             </div>
                                        </div>
                                   </a>
                                   <!-- Item -->
                                   <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                             <div class="flex-shrink-0">
                                                  <div class="avatar-sm me-2">
                                                       <span class="avatar-title bg-soft-info text-info fs-20 rounded-circle">
                                                            D
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="flex-grow-1">
                                                  <p class="mb-0 fw-semibold">Donoghue Susan</p>
                                                  <p class="mb-0 text-wrap">
                                                       Hi, How are you? What about our next meeting
                                                  </p>
                                             </div>
                                        </div>
                                   </a>
                                   <!-- Item -->
                                   <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                             <div class="flex-shrink-0">
                                                  <img src="{{ asset('public/assets/images/users/avatar-3.jpg')}}" class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-3" />
                                             </div>
                                             <div class="flex-grow-1">
                                                  <p class="mb-0 fw-semibold">Jacob Gines</p>
                                                  <p class="mb-0 text-wrap">
                                                       Answered to your comment on the cash flow forecast's graph 🔔.
                                                  </p>
                                             </div>
                                        </div>
                                   </a>
                                   <!-- Item -->
                                   <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                             <div class="flex-shrink-0">
                                                  <div class="avatar-sm me-2">
                                                       <span class="avatar-title bg-soft-warning text-warning fs-20 rounded-circle">
                                                            <iconify-icon icon="solar:leaf-broken"></iconify-icon>
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="flex-grow-1">
                                                  <p class="mb-0 fw-semibold text-wrap">You have received <b>20</b> new messages in the
                                                       conversation</p>
                                             </div>
                                        </div>
                                   </a>
                                   <!-- Item -->
                                   <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                             <div class="flex-shrink-0">
                                                  <img src="{{ asset('public/assets/images/users/avatar-5.jpg')}}" class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-5" />
                                             </div>
                                             <div class="flex-grow-1">
                                                  <p class="mb-0 fw-semibold">Shawn Bunch</p>
                                                  <p class="mb-0 text-wrap">
                                                       Commented on Admin
                                                  </p>
                                             </div>
                                        </div>
                                   </a>
                              </div>
                              <div class="text-center py-3">
                                   <a href="javascript:void(0);" class="btn btn-primary btn-sm">View All Notification <i class="ri-arrow-right-line ms-1"></i></a>
                              </div>
                         </div>
                    </div>

                    <!-- User -->
                    <div class="dropdown topbar-item">
                     <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="d-flex align-items-center">
                               <img class="rounded-circle" width="32" src="{{ asset('public/assets/images/users/avatar-1.jpg')}}" alt="avatar-3">
                          </span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <h6 class="dropdown-header">Welcome Gaston!</h6>
                  
                          <a class="dropdown-item" href="pages-calendar.html">
                               <iconify-icon icon="solar:calendar-broken" class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Profile </span>
                          </a>

                          <div class="dropdown-divider my-1"></div>

                          <a class="dropdown-item text-danger" href="auth-signin.html">
                               <iconify-icon icon="solar:logout-3-broken" class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Logout</span>
                          </a>
                     </div>
                 </div>
                </div>
            </div>
        </div>
    </div>
</header>