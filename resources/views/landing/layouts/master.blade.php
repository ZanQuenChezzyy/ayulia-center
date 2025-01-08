<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-wide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('components') }}/assets/" data-template="front-pages-no-customizer" data-style="light">

@include('landing.layouts.header')

<body>
    <script src="{{ asset('components') }}/assets/vendor/js/dropdown-hover.js"></script>
    <script src="{{ asset('components') }}/assets/vendor/js/mega-dropdown.js"></script>

    <!-- Navbar: Start -->
    @include('landing.layouts.navbar')
    <!-- Navbar: End -->

    <!-- Sections:Start -->
    <div data-bs-spy="scroll" class="scrollspy-example">
        @yield('content')
    </div>

    <!-- / Sections:End -->

    <!-- Footer: Start -->
    @include('landing.layouts.footer')
    <!-- Footer: End -->

    @include('landing.layouts.script')
</body>

</html>
