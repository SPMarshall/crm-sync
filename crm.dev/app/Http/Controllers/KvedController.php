<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use App\Kved;

class KvedController extends Controller {

    /**
     * Show list of kveds page
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        return view('kveds', [
            'kveds' => Kved::orderBy('kved')->get(),
        ]);
    }

}
