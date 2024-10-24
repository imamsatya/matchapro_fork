<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgressProfilingController;
use App\Http\Controllers\DirektoriUsahaController;
use App\Http\Controllers\ProfilingController;
use App\Http\Controllers\FormCreateUsahaController;
use App\Http\Controllers\FormUpdateUsahaController;
use App\Http\Controllers\ProfilingMandiriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterWilayahController;
use App\Http\Controllers\MasterKBLi;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MiscController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//MatchaPro

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('error/not-authorized', [MiscController::class, 'notAuthorizedPage'])->name('not-authorized.index');

Route::middleware(['sso-bps'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    //Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    //Progress Profiling
    //Wilayah
    Route::get('profiling/progress/wilayah', [ProgressProfilingController::class, 'wilayah_index'])->name('progress_wilayah.index');
    Route::get('profiling/progress/status_statistics', [ProgressProfilingController::class, 'getStatusStatistics'])->name('progress_wilayah.status_statistics');
    //Profiler
    Route::get('profiling/progress/profiler', [ProgressProfilingController::class, 'profiler_index'])->name('progress_profiler.index');

    //Direktori Usaha
    Route::get('direktori-usaha', [DirektoriUsahaController::class, 'index'])->name('direktori_usaha.index');
    Route::post('direktori-usaha/data', [DirektoriUsahaController::class, 'getDirektoriUsahaData'])->name('direktori_usaha.data');
    Route::post('direktori-usaha/data-by-id', [DirektoriUsahaController::class, 'getDirektoriUsahaDataById'])->name('direktori_usaha.data_by_id');
    Route::get('direktori-usaha/export', [DirektoriUsahaController::class, 'exportExcelPeriodik'])->name('export.excel');

    //Profiling
    Route::get('profiling', [ProfilingController::class, 'index'])->name('profiling.index');
    Route::get('/profiling/get-data', [ProfilingController::class, 'getData'])->name('profiling.getData');
    Route::get('profiling/update/usaha/{perusahaan_id}/{alokasi_id}/history', [ProfilingController::class, 'getHistoryData'])->name('form_update_usaha.history');
    Route::post('profiling/update/usaha/{perusahaan_id}/{alokasi_id}/cancel', [ProfilingController::class, 'cancelData'])->name('form_update_usaha.cancel');
    Route::get('profiling/export_excel', [ProfilingController::class, 'exportExcel'])->name('export.excel.profiling');
    


    //Create Usaha - Form
    Route::get('profiling/create/usaha', [FormCreateUsahaController::class, 'index'])->name('form_create_usaha.index');
    Route::get('/profiling/get-data-kelurahan_desa', [FormCreateUsahaController::class, 'getDataKelurahanDesa'])->name('getDataKelurahanDesa');
    Route::get('/profiling/get-data-fulltext', [FormCreateUsahaController::class, 'getDataFulltext'])->name('getDataFulltext');
    Route::post('profiling/create/usaha', [FormCreateUsahaController::class, 'store'])->name('form_create_usaha.store');

    //Profiling Mandiri
    Route::get('profiling/mandiri/index', [ProfilingMandiriController::class, 'index'])->name('profilng_mandiri.index');
    Route::get('/profiling/mandiri/get-data-all', [ProfilingMandiriController::class, 'getDataAll'])->name('profiling_mandiri.getDataAll');
    //Update Usaha - Form
    Route::get('profiling/update/usaha/{perusahaan_id}/{alokasi_id}', [FormUpdateUsahaController::class, 'index'])->name('form_update_usaha.index');
    Route::post('profiling/update/usaha/{perusahaan_id}/{alokasi_id}', [FormUpdateUsahaController::class, 'save'])->name('form_update_usaha.save');
    
    //dari direktori usaha
    Route::get('profiling/update/usaha/{perusahaan_id}', [FormUpdateUsahaController::class, 'formUpdateFromDirektoriUsaha'])->name('form_update_usaha2.index');

    // halaman kalo periode profiling mandiri lagi tutup
    Route::get('profiling/periode-mandiri-tutup', [FormUpdateUsahaController::class, 'periodeProfilingMandiriTutup'])->name('periode_profiling_mandiri_tutup.index');
    // halaman kalo user coba akses kerjaan orang lain
    Route::get('profiling/unauthorized', [FormUpdateUsahaController::class, 'lagiDikerjainOrangLain'])->name('lagi_dikerjain_orang_lain.index');    
    // halaman buat nampilin kalo something error happened
    Route::get('profiling/error', [FormUpdateUsahaController::class, 'errorPage'])->name('error_page.index');

    Route::get('master-provinsi', [MasterWilayahController::class, 'getMasterProvinsi'])->name('master-provinsi.data');
    Route::post('wil-desa', [MasterWilayahController::class, 'getDesa'])->name('wil-desa');
    Route::post('wil-kecamatan', [MasterWilayahController::class, 'getKecamatan'])->name('wil-kecamatan');
    Route::post('wil-kabupaten-kota', [MasterWilayahController::class, 'getKabupaten'])->name('wil-kabupaten-kota');
    Route::post('wil-kabupaten-kota-user', [MasterWilayahController::class, 'getMasterKabkotUser'])->name('wil-kabupaten-kota-user');
    Route::post('master-kbli', [MasterKBLI::class, 'getMasterKBLI'])->name('master-kbli');
    Route::post('check-idsbr', [FormUpdateUsahaController::class, 'checkIDSBR'])->name('check-idsbr');
    Route::post('wil-provinsi-user', [MasterWilayahController::class, 'getMasterProvinsiUser'])->name('wil-provinsi-user');

    Route::get('user/list', [UserController::class, 'index'])->name('user.list');
    Route::get('user/data', [UserController::class, 'getData'])->name('user.list.data');
    Route::post('user/detail', [UserController::class, 'getDetailUser'])->name('user.list.detail');
    Route::post('user/update', [UserController::class, 'updateUser'])->name('user.list.update');
    Route::post('user/update-wilayah', [UserController::class, 'updateUserWilayah'])->name('user.list.update.wilayah');
    Route::post('user/wilayah-akses', [UserController::class, 'getWilayahAksesUser'])->name('user.wilayah.akses');
});

//End Matcha Pro
// ==================================================================================================

//Tempplate
// Route::get('/', [StaterkitController::class, 'home'])->name('home');
// Route::get('home', [StaterkitController::class, 'home'])->name('home');
// Route Components
Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
Route::get('layouts/full', [StaterkitController::class, 'layout_full'])->name('layout-full');
Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');


// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
