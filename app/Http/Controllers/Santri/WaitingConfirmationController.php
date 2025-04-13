<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitingConfirmationController extends Controller
{
    /**
     * Display the waiting confirmation page.
     */
    public function index()
    {
        $user = Auth::user();
        $santri = $user->santri;

        // If santri is already confirmed, redirect to dashboard
        if ($santri && $santri->is_confirmed) {
            return redirect()->route('santri.dashboard');
        }

        return view('santri.waiting-confirmation', compact('user', 'santri'));
    }
}
