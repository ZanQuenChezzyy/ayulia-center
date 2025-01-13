@extends('landing.layouts.master')

@section('title', 'Ayulia Training Center')

@section('content')
    <section class="section-py bg-body first-section-pt">
        <div class="container">
            <div class="card px-3">
                <div class="row">
                    <div class="col-lg-7 card-body border-end p-md-8">
                        <h4 class="mb-2">Formulir Pendaftaran</h4>
                        <p class="mb-0">
                            Form Registrasi Member
                        </p>
                        <form id="form-pendaftaran" method="POST" action="{{ route('pendaftaran.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-5 py-8">
                                <div class="col-md-12">
                                    <label class="form-label" for="kelas_id">Kelas</label>
                                    <select id="kelas_id" class="form-select" name="kelas_id" required>
                                        <option disabled selected>Pilih Kelas</option>
                                        @foreach ($kelas as $data)
                                            <option value="{{ $data->id }}" data-harga="{{ $data->harga }}"
                                                data-deskripsi="{{ $data->deskripsi }}"
                                                data-tingkatan="{{ $data->tingkatan }}"
                                                data-jam-mulai="{{ $data->jam_mulai }}"
                                                data-jam-selesai="{{ $data->jam_selesai }}"
                                                data-pertemuan="{{ $data->jumlah_pertemuan }}"
                                                data-instruktur="{{ $data->instruktur->nama }}">
                                                {{ $data->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label class="form-label" for="nama">Nama Lengkap</label>
                                    <input type="text" id="nama" class="form-control"
                                        placeholder="Masukkan Nama Lengkap" minlength="3" maxlength="45" name="nama"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" class="form-control" minlength="3" maxlength="45"
                                        placeholder="Masukkan Email" name="email" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="no_telepon">No. Telepon</label>
                                    <input type="text" id="no_telepon" class="form-control"
                                        placeholder="Masukkan No. Telepon" name="no_telepon" min="1" minlength="10"
                                        maxlength="15" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" id="tempat_lahir" class="form-control"
                                        placeholder="Masukkan Tempat Lahir" name="tempat_lahir" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" class="form-control" name="tanggal_lahir"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="alamat">Alamat</label>
                                    <input type="text" id="alamat" class="form-control" placeholder="Masukkan Alamat"
                                        name="alamat" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                    <select type="text" id="pendidikan_terakhir" class="form-select"
                                        placeholder="Pilih Pendidikan Terakhir" name="pendidikan_terakhir" required>
                                        <option disabled selected>Pilih Pendidikan Terakhir</option>
                                        <option value="0">SD - Sekolah Dasar</option>
                                        <option value="1">SMP - Sekolah Menengah Pertama</option>
                                        <option value="2">SMA - Sekolah Menengah Atas</option>
                                        <option value="3">D3 - Diploma 3</option>
                                        <option value="4">S1 - Sarjana</option>
                                        <option value="5">S2 - Magister</option>
                                        <option value="6">S3 - Doktor</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ktp_url">Upload KTP</label>
                                    <input type="file" id="ktp_url" class="form-control" name="ktp_url" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="avatar_url">Upload Foto Latar Merah</label>
                                    <input type="file" id="avatar_url" class="form-control" name="avatar_url"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="bukti_pembayaran">Upload Bukti Pembayaran</label>
                                    <input type="file" id="bukti_pembayaran" class="form-control"
                                        name="bukti_pembayaran" required />
                                </div>
                            </div>
                            <div class="d-grid mt-5">
                                <button type="submit" id="submit-button" class="btn btn-primary">
                                    <span class="me-2">Daftar Sekarang</span>
                                    <i class="bx bx-check-double scaleX-n1-rtl"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div id="order-summary" class="col-lg-5 card-body p-md-8">
                        <h4 class="mb-8" id="kelas-nama">Ringkasan Pendaftaran</h4>
                        <div class="bg-lighter p-6 rounded row">
                            <h4>Detail Kelas</h4>
                            <div class="col-md-6">
                                <span>Tingkatan
                                    <p id="kelas-tingkatan" class="fw-bold">-</p>
                                </span>
                                <span>Jadwal
                                    <p id="kelas-jadwal" class="fw-bold">-</p>
                                </span>
                                <span>Pertemuan
                                    <p id="kelas-pertemuan" class="fw-bold">-</p>
                                </span>
                            </div>
                            <div class="col-md-6">
                                <span>Instruktur Kelas
                                    <p id="kelas-instruktur" class="fw-bold">-</p>
                                </span>
                                <span>Harga
                                    <p id="kelas-harga" class="fw-bold">-</p>
                                </span>
                            </div>
                        </div>
                        <p id="kelas-deskripsi" class="mt-5">
                            Pilih kelas untuk melihat detail harga dan deskripsi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @include('landing.page.scripts.kelas-data')
    @include('landing.page.scripts.store-pendaftaran')
@endpush
