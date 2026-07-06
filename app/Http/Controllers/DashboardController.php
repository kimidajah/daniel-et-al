<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'guru' => view('dashboard.guru'),
            'piket' => view('dashboard.piket'),
            'tu' => view('dashboard.tu'),
            'kepala_sekolah' => view('dashboard.kepala'),
            default => abort(403, 'Peran tidak dikenali.'),
        };
    }
}
