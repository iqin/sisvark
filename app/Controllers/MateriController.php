<?php

namespace App\Controllers;

class MateriController extends BaseController
{
    public function index($moduleId = 1)
    {
        echo "<h1>Materi Adaptif Modul {$moduleId}</h1>";
        echo "<p>Halaman ini sedang dalam pengembangan. (Storyboard Page 9)</p>";
        echo "<a href='" . base_url('siswa/modul') . "' class='btn btn-secondary'>Kembali</a>";
    }
}