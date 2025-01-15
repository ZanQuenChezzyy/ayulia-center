<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\Pertanyaan;
use App\Models\Pesan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    public function index()
    {
        $instrukturs = Instruktur::where('di_tampilkan', true)->get();
        $faqs = Pertanyaan::where('di_tampilkan', true)->get();

        return view('landing.page.index', compact('instrukturs', 'faqs'));
    }

    public function storePesan(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|min:3|max:45',
            'email' => 'required|email|min:3|max:45',
            'pesan' => 'required|string|min:10',
        ]);

        $pesan = Pesan::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'pesan' => $validated['pesan'],
        ]);

        return response()->json([
            'message' => 'Pendaftaran berhasil disimpan.',
            'pendaftaran' => $pesan
        ], 201);
    }

    public function pendaftaran()
    {
        $kelas = Kelas::all();

        return view('landing.page.pendaftaran', compact('kelas'));
    }

    public function storePendaftaran(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama' => 'required|string|min:3|max:45',
            'email' => 'required|email|max:45',
            'no_telepon' => 'required|string|min:10|max:15',
            'tempat_lahir' => 'required|string|max:45',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'pendidikan_terakhir' => 'required',
            'ktp_url' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'avatar_url' => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        // Simpan file ke dalam folder
        $ktpFileName = Str::random(10) . '_' . $request->file('ktp_url')->getClientOriginalName();
        $avatarFileName = Str::random(10) . '_' . $request->file('avatar_url')->getClientOriginalName();
        $buktiPembayaranFileName = Str::random(10) . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();

        // Store files in 'storage/public/pendaftaran'
        $ktpPath = $request->file('ktp_url')->storeAs('pendaftaran', $ktpFileName, 'public');
        $avatarPath = $request->file('avatar_url')->storeAs('pendaftaran', $avatarFileName, 'public');
        $buktiPembayaranPath = $request->file('bukti_pembayaran')->storeAs('pendaftaran', $buktiPembayaranFileName, 'public');

        // Simpan data ke dalam database
        $pendaftaran = Pendaftaran::create([
            'kelas_id' => $validated['kelas_id'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'alamat' => $validated['alamat'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            'ktp_url' => $ktpPath, // Path file tanpa 'public/'
            'avatar_url' => $avatarPath,
            'bukti_pembayaran' => $buktiPembayaranPath,
            'status_pembayaran' => '0', // Default status pembayaran
            'status_pendaftaran' => '0', // Default status pendaftaran
        ]);

        // Kembalikan respon sukses
        return response()->json([
            'message' => 'Pendaftaran berhasil disimpan.',
            'pendaftaran' => $pendaftaran
        ], 201);
    }
}
