<?php

namespace App\Controllers;

class LandingController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Selamat Datang - Sistem Pembelajaran Adaptif'
        ];
        return view('landing/index', $data);
    }
}