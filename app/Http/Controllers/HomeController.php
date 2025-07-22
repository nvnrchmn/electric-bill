<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Level;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->id_level === Level::ADMINISTRATOR_ID) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->id_level === Level::PETUGAS_ID) {
            return redirect()->route('petugas.dashboard');
        } elseif ($user->id_level === Level::PELANGGAN_ID) {
            return redirect()->route('pelanggan.dashboard');
        }

        return abort(403, 'Level user tidak diketahui');
    }
}
