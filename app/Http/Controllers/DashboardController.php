<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStruk = Struk::count();
        $totalHarga = Struk::sum('total_harga');
        $latestStruk = Struk::latest()->first();

        return view('dashboard', compact('totalStruk', 'totalHarga', 'latestStruk'));
    }
}
