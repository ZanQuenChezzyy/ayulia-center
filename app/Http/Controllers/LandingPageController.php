<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $instrukturs = Instruktur::where('di_tampilkan', true)->get();
        $faqs = Pertanyaan::where('di_tampilkan', true)->get();

        return view('landing.page.index', compact('instrukturs', 'faqs'));
    }
}
