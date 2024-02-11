<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VersionCheckerController extends Controller
{
    public $url = "https://entryqc.id/";

    public function check(Request $request)
    {
        $appVersion = $request->app_version;
        $latestVersion = \App\Models\AppVersion::latest()->first();
        if ($appVersion === $latestVersion->app_version) {
            return ResponseFormatter::success([
                'latest' => true,
            ], 'Aplikasi sudah dalam versi terbaru');
        } else {
            return ResponseFormatter::success([
                'latest' => false,
                'url' => $this->url,
            ], 'Aplikasi sudah ada versi terbaru, silahkan update aplikasi anda');
        }
    }
}
