<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class MasterWilayahController extends Controller
{    
    public function getLatestSnapshot() {
        $latestSnapshot = DB::table('area_provinsi')->select('snapshot_id')->distinct()->orderBy('snapshot_id', 'desc')->first();
        return $latestSnapshot->snapshot_id;
    }

    public function getMasterProvinsi() {        
        $snapshot_id = $this->getLatestSnapshot();        
        $masterProvinsi = DB::table('area_provinsi')->where('snapshot_id', $snapshot_id)                
                ->get();
        return $masterProvinsi;
    }

    public function getMasterKabkot($provinsi_data) {        
        // $level_role_user = explode('-', auth()->user()->getRoleNames()[0])[0]; // PUSAT, PROVINSI, KABKOT         
        // $masterKabKot = DB::table('area_kabupaten_kota')
        //     ->where('provinsi_id', $provinsi_data)                        
        //     ->when($level_role_user == 'KABKOT', function($query) {
        //         return $query->where('id', auth()->user()->kabupaten_kota_id);
        //     })
        //     ->get();

        $masterKabKot = DB::table('area_kabupaten_kota')
        ->where('provinsi_id', $provinsi_data)  
        ->get();
            
        return $masterKabKot;
    }

    public function getMasterKecamatan($kabupaten_kota_data) {
                
        $masterKecamatan = DB::table('area_kecamatan')
            ->where('kabupaten_kota_id', $kabupaten_kota_data)
            ->get();
        return $masterKecamatan;
    }

    public function getMasterDesa($kecamatan_id) {
        $masterDesa = DB::table('area_kelurahan_desa')->where('kecamatan_id', $kecamatan_id)->get();
        return $masterDesa;
    }

    public function getDesa(Request $request) {       
        $kecamatan_id = $request->input('kecamatan');
        $masterDesa = DB::table('area_kelurahan_desa')->where('kecamatan_id', $kecamatan_id)->get();
        return $masterDesa;
    }

    public function getKecamatan(Request $request) {
        $kabupaten_kota_id= $request->input('kabupaten_kota');
        $masterKecamatan = DB::table('area_kecamatan')->where('kabupaten_kota_id', $kabupaten_kota_id)->get();
        return $masterKecamatan;
    }

    public function getKabupaten(Request $request) {        

        $provinsi_id = $request->input('provinsi');
        $level_request = $request->input('level');        
        $masterKabKot = DB::table('area_kabupaten_kota')->where('provinsi_id', $provinsi_id)->get();

        if($masterKabKot->count() && $level_request != 'all') {            
            // $level_role_user = explode('-', auth()->user()->getRoleNames()[0])[0]; // PUSAT, PROVINSI, KABKOT 
            // $masterKabKot = $masterKabKot->filter(function($value) use ($level_role_user) {
            //     if($level_role_user == 'PROVINSI') return $value->provinsi_id == auth()->user()->provinsi_id;
            //     if($level_role_user == 'KABKOT') return $value->provinsi_id == auth()->user()->provinsi_id && $value->id == auth()->user()->kabupaten_kota_id;
            //     return true;
            // })->values()->all();
        }             

        return $masterKabKot;
    } 
    
    public function getKabupatenByProvinsi($provinsi_id) {
        $masterKabKot = DB::table('area_kabupaten_kota')->where('provinsi_id', $provinsi_id)->get();
        return $masterKabKot;
    }


}
