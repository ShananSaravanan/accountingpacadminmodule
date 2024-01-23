
@include('Pages.allextensions')

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
    
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      
    </ul>
  </nav>


<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('toindex') }}" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Accounting PAC</span>
    </a>
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div> -->
        <div class="info">
          <a href="" class="d-block">Administrator</a>
        </div>
      </div>
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
            <a href="{{ route('toindex') }}" class="nav-link">
            <i class="nav-icon fa-solid fa-house"></i>
              <p>
                Dashboard
                
              </p>
            </a>
    </li>
    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-users-line"></i>
              <p>
                User
                <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('displayUsers',['searchType' => 'allusers']) }}" class="nav-link">
                <i class="fa-regular fa-address-card nav-icon"></i>
                  <p>Registered Users</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('toShowRoles') }}" class="nav-link">
                <i class="fa-regular fa-circle-user nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('toShowHcodes') }}" class="nav-link">
                <i class="fa-regular fa-id-badge nav-icon"></i>
                  <p>Honorific Codes</p>
                </a>
              </li>
            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class=" nav-icon fa-solid fa-map-location-dot"></i>
              <p>Address
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowAddress')}}" class="nav-link">
                <i class="fa-solid fa-location-arrow nav-icon"></i>
                  <p>Registered Addresses</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('toShowPostCode') }}" class="nav-link">
                <i class="fa-solid fa-location-crosshairs nav-icon"></i>
                  <p>Post Codes</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('toShowPostOffice') }}" class="nav-link">
                <i class="fa-regular fa-compass nav-icon"></i>
                  <p>Post Offices</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('toShowStateCode') }}" class="nav-link">
                <i class="fa-regular fa-flag nav-icon"></i>
                  <p>State Codes</p>
                </a>
              </li>
            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-building-columns"></i>
              <p>Firm
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowFirm')}}" class="nav-link">
                <i class="fa-regular fa-registered nav-icon"></i>
                  <p>Registered Firms</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('toShowFirmUser') }}" class="nav-link">
                <i class="fa-solid fa-user-group nav-icon"></i>
                  <p>Firm Users</p>
                </a>
              </li>

            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-briefcase"></i>
              <p>Business
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowRegBusiness')}}" class="nav-link">
                <i class="fa-regular fa-building nav-icon"></i>
                  <p>Registered Businesses</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('toShowBusinessUser') }}" class="nav-link">
                <i class="fa-solid fa-user-tie nav-icon"></i>
                  <p>Business Users</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('toShowFinancialRecords') }}" class="nav-link">
                <i class="fa-solid fa-coins nav-icon"></i>
                  <p>Financial Records</p>
                </a>
              </li>

            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-cubes"></i>
              <p>Package
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowPackage')}}" class="nav-link">
                <i class="fa-solid fa-circle-info nav-icon"></i>
                  <p>Package Bases</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('toShowPackageBase')}}" class="nav-link">
                <i class="fa-solid fa-cart-shopping nav-icon"></i>
                  <p>Purchasable Packages</p>
                </a>
              </li>
              

            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-file-invoice "></i>
              <p>Blling
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowSubscription')}}" class="nav-link">
                <i class="fa-solid fa-credit-card nav-icon"></i>
                  <p>Subscriptions</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('toShowTransaction')}}" class="nav-link">
                <i class="fa-solid fa-money-bills nav-icon"></i>
                  <p>Transactions</p>
                </a>
              </li>
              

            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-laptop-file"></i>
              <p>Engagement
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowAssignment')}}" class="nav-link">
                <i class="fa-solid fa-file-contract nav-icon"></i>
                  <p>Assignments</p>
                </a>
              </li>
              
            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-layer-group"></i>
              <p>References
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowAddressTypes')}}" class="nav-link">
                <i class="  fa-solid fa-map-location-dot nav-icon"></i>
                  <p>Address Types</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('toDisplayBusinessType')}}" class="nav-link">
                <i class=" fa-solid fa-briefcase nav-icon"></i>
                  <p>Business Types</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('toShowFirmTypes')}}" class="nav-link">
                <i class="fa-solid fa-building-columns nav-icon"></i>
                  <p>Firm Types</p>
                </a>
              </li>
              
            </ul>
    </li>

    <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fa-solid fa-wand-magic-sparkles"></i>
              <p>Miscellanous
              <i class="right fas fa-angle-left"></i>
              </p>
              
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('toShowRecycleBin')}}" class="nav-link">
                <i class="fa-solid fa-trash nav-icon"></i>
                  <p>Recycle Bin</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="fa-solid fa-server nav-icon"></i>
                  <p>Server Log</p>
                </a>
              </li>
              
            </ul>
    </li>

    <li class="nav-item">
            <a href="{{ route('logout.getservice') }}" class="nav-link">
            <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>

              <p>
                Logout
              </p>
            </a>
    </li>
</ul>
</nav>
</div>
</aside>

<!--
    <div class="sidebar-section">
        <a href="{{ route('logout.getservice') }}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">Logout</a>
    </div>
</div>
-->

