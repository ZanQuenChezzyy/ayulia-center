<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('components') }}/assets/vendor/libs/popper/popper.js"></script>
<script src="{{ asset('components') }}/assets/vendor/js/bootstrap.js"></script>
<script src="{{ asset('components') }}/assets/vendor/libs/jquery/jquery.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('components') }}/assets/vendor/libs/nouislider/nouislider.js"></script>
<script src="{{ asset('components') }}/assets/vendor/libs/swiper/swiper.js"></script>

<!-- Main JS -->
<script src="{{ asset('components') }}/assets/js/front-main.js"></script>
<script src="{{ asset('components') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
<!-- Page JS -->
<script src="{{ asset('components') }}/assets/js/front-page-landing.js"></script>

@stack('scripts')
