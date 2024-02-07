<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dapil;
use Illuminate\Http\Request;

class DapilController extends Controller
{
    public function index()
    {
        $dapil = Dapil::all();
        return ResponseFormatter::success($dapil, 'Data Dapil berhasil diambil');
    }
}
