<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminViewController extends Controller
{
    public function dashboard() { return view('index'); }
    public function buttons() { return view('buttons'); }
    public function cards() { return view('cards'); }
    public function charts() { return view('charts'); }
    public function forms() { return view('forms'); }
    public function modals() { return view('modals'); }
    public function tables() { return view('tables'); }

    public function error404() { return view('404'); }
    public function blank() { return view('blank'); }
    public function createAccount() { return view('create-account'); }
    public function forgotPassword() { return view('forgot-password'); }
    public function login() { return view('login'); }
}