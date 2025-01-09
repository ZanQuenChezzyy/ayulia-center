<section id="inspectur" class="section-py landing-team">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-label-primary">Instruktur</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">Didukung oleh
                <img src="{{ asset('components') }}/assets/img/front-pages/icons/section-title-icon.png" alt="team icon"
                    class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
            </span>
            Instruktur dan Staf Profesional
        </h4>
        <p class="text-center mb-md-11 pb-0 pb-xl-12">Kenali tim yang akan membantu Anda menjadi ahli menjahit.</p>
        <div class="row gy-12 mt-2">
            @foreach ($instrukturs as $instruktur)
                <div class="col-lg-3 col-sm-6">
                    <div class="card mt-3 mt-lg-0 shadow-none">
                        <div
                            class="bg-label-primary border border-bottom-0 border-label-primary position-relative team-image-box">
                            <img src="{{ asset('storage/' . $instruktur->foto) }}"
                                class="position-absolute card-img-position bottom-0 start-50 scaleX-n1-rtl rounded"
                                alt="{{ $instruktur->nama }}" />
                        </div>
                        <div class="card-body border border-top-0 border-label-primary text-center py-5">
                            <h5 class="card-title mb-0">{{ $instruktur->nama }}</h5>
                            <p class="text-muted mb-0">{{ $instruktur->pengalaman }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
