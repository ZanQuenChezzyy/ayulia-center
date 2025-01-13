<nav class="layout-navbar shadow-none py-0">
    <div class="container">
        <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
            <!-- Menu logo wrapper: Start -->
            <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8">
                <!-- Mobile menu toggle: Start-->
                <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="tf-icons bx bx-menu bx-lg align-middle text-heading fw-medium"></i>
                </button>
                <!-- Mobile menu toggle: End-->
                <a href="{{ url('/') }}" class="app-brand-link">
                    <img src="{{ asset('img/ayulia-logo.png') }}" alt="Logo" width="80">
                </a>
            </div>
            <!-- Menu logo wrapper: End -->
            <!-- Menu wrapper: Start -->
            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl p-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="tf-icons bx bx-x bx-lg"></i>
                </button>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" aria-current="page" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#unggulan">Unggulan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#inspectur">Inspectur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#hubungiKami">Hubungi Kami</a>
                    </li>
                </ul>
            </div>
            <div class="landing-menu-overlay d-lg-none"></div>
            <!-- Menu wrapper: End -->
            <!-- Toolbar: Start -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- navbar button: Start -->
                @if (auth()->check())
                    <li>
                        <a href="{{ url('/administrator') }}" class="btn btn-primary">
                            <span class="tf-icons bx bx-user scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Dashboard</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ url('/administrator') }}" class="btn btn-primary">
                            <span class="tf-icons bx bx-log-in scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Masuk</span>
                        </a>
                    </li>
                @endif
                <!-- navbar button: End -->
            </ul>
            <!-- Toolbar: End -->
        </div>
    </div>
</nav>
