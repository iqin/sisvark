<?php

namespace App\Controllers;

class ZpdController extends BaseController
{
    public function test($moduleId = 1)
    {
        echo "<h1>Tes ZPD Modul {$moduleId}</h1>";
        echo "<p>Halaman ini sedang dalam pengembangan. (Storyboard Page 6-8)</p>";
        echo "<a href='" . base_url('siswa/modul') . "' class='btn btn-secondary'>Kembali</a>";
    }
}