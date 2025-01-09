<section id="faq" class="section-py bg-body landing-faq">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-label-primary">FAQ</span>
        </div>
        <h4 class="text-center mb-1">
            Pertanyaan yang sering
            <span class="position-relative fw-extrabold z-1">Muncul
                <img src="{{ asset('components') }}/assets/img/front-pages/icons/section-title-icon.png" alt="icon"
                    class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
        </h4>
        <p class="text-center mb-12 pb-md-4">
            Temukan jawaban untuk pertanyaan umum tentang program dan layanan di Ayulia Training Center.
        </p>
        <div class="row gy-12 align-items-center">
            <div class="col-lg-5">
                <div class="text-center">
                    <img src="{{ asset('components') }}/assets/img/front-pages/landing-page/faq.png"
                        alt="FAQ Illustration" class="faq-image" />
                </div>
            </div>
            <div class="col-lg-7">
                <div class="accordion" id="accordionExample">
                    @foreach ($faqs as $index => $faq)
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button type="button" class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                    data-bs-toggle="collapse" data-bs-target="#accordion{{ $index }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-controls="accordion{{ $index }}">
                                    {{ $faq->pertanyaan }}
                                </button>
                            </h2>
                            <div id="accordion{{ $index }}"
                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    {{ $faq->jawaban }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
