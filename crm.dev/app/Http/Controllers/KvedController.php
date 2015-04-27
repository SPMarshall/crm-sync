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
        $kveds = Kved::orderBy('kved')->get();
        return view('kveds', [
            'kveds' => $kveds,
        ]);
    }

}
