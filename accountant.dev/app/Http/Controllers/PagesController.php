<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Kved as Kved;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

class PagesController extends Controller {

    private $_auth;
    private $_user;

    public function __construct(Guard $auth) {
        $this->middleware('auth');
        $this->_auth = $auth;
        $this->_user = $this->_auth->user();
    }

    /**
     * Displays User Kved List page
     * 
     * @return View for pages.user_kveds
     */
    public function getUserKveds() {
        $user = $this->_user;
        $selected_page = 'user_kveds';
        $ordered_kveds = $user->kveds()->orderBy('kved', 'ASC')->get();
        return view('pages.user_kveds', compact('user', 'selected_page', 'ordered_kveds'));
    }

    /**
     * Displays Kved List page
     * 
     * @return View for pages.kveds
     */
    public function getKvedList() {
        return view('pages.kveds',[
            'kveds'=> Kved::orderBy('kved')->get(),
            'selected_page'=>'kveds',
        ]);
    }

}
