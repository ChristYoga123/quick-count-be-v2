<?php

use App\Http\Controllers\Admin\CalegController;
use App\Http\Controllers\Admin\CapresController;
use App\Http\Controllers\Admin\ConditionalQuestionController;
use App\Http\Controllers\Admin\DapilController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanPillegController;
use App\Http\Controllers\Admin\LaporanPilparController;
use App\Http\Controllers\Admin\LaporanPilpresController;
use App\Http\Controllers\Admin\PartaiController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\RealCountPartaiController;
use App\Http\Controllers\Admin\RealCountPresidenController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SurveyCategoryController;
use App\Http\Controllers\Admin\SurveyQuestionController;
use App\Http\Controllers\Admin\SurveyTitleController;
use App\Http\Controllers\Admin\TPSController;
use App\Models\TPS;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__ . '/auth.php';
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('roles', RoleController::class);
    });
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('tps', TPSController::class);
        Route::get('kabupaten/{provinceName}', [TPSController::class, 'getCities'])->name('getCities');
        Route::get('kecamatan/{cityName}', [TPSController::class, 'getDistricts'])->name('getDistricts');
        Route::get('kelurahan/{districtName}', [TPSController::class, 'getSubDistricts'])->name('getSubDistricts');
        Route::resource('dapil', DapilController::class);
        Route::resource('petugas', PetugasController::class);
        Route::post('petugas/single-store', [PetugasController::class, 'singleStore'])->name('petugas.singleStore');
        Route::resource('partai', PartaiController::class);
        Route::resource('caleg', CalegController::class);
        Route::resource('capres', CapresController::class);
    });

    Route::prefix('survey')->name('survey.')->group(function () {
        Route::resource('judul', SurveyTitleController::class);
        Route::resource('kategori', SurveyCategoryController::class);
        Route::resource('pertanyaan', SurveyQuestionController::class);
        Route::resource('perkondisian', ConditionalQuestionController::class);
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('pilpres', [LaporanPilpresController::class, 'index'])->name('pilpres.index');
        Route::get('pilpres/{dapil}', [LaporanPilpresController::class, 'show'])->name('pilpres.show');
        Route::get('pilpar', [LaporanPilparController::class, 'index'])->name('pilpar');
        Route::get('pilpar/{dapil}', [LaporanPilparController::class, 'show'])->name('pilpar.show');
        Route::get('pileg', [LaporanPillegController::class, 'index'])->name('pilleg');
        Route::get('pileg/{dapil}', [LaporanPillegController::class, 'show'])->name('pilleg.show');
    });

    Route::prefix('real-count')->name('real-count.')->group(function () {
        Route::get('pilpres', [RealCountPresidenController::class, 'index'])->name('pilpres.index');
        Route::get('pilpres/{dapil}', [RealCountPresidenController::class, 'show'])->name('pilpres.show');
        Route::get('pilpar', [RealCountPartaiController::class, 'index'])->name('pilpar.index');
        Route::get('pilpar/{dapil}', [RealCountPartaiController::class, 'show'])->name('pilpar.show');
    });
});
