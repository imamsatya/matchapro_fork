<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\User;


class ProfilingMandiriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pageConfigs = ['blankPage' => true];
        return view('/matchapro/misc/not-found', ['pageConfigs' => $pageConfigs]);
        
        // $roleUser = auth()->user()->getRoleNames();
        // // belum memiliki roles
        // if(!$roleUser->count()) {
        //     $pageConfigs = ['blankPage' => true];
        //     return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        // }

        // $pageConfigs = ['sidebarCollapsed' => false];
        // $id_profiling_mandiri = env('PERIODE_PROFILING_MANDIRI');
        
        
        
       
        // return view('/matchapro/page/profiling_mandiri', [
        //     // 'breadcrumbs' => $breadcrumbs, 
        //     'pageConfigs' => $pageConfigs,
        //     'id_profiling_mandiri' => $id_profiling_mandiri]);


    }

    public function getDataAll(Request $request)
    {

        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }

        $status_form = $request->input('status_form');
        
        $periode_id = $request->input('periode_id');
        
        $user = auth()->user();
        $provinsi_id = $user->provinsi_id;
        $kabupaten_kota_id = $user->kabupaten_kota_id;
        $snapshot_id = DB::table('area_provinsi')->max('snapshot_id');
        
        
        //cek wilayah
        //User Prov
        if ($provinsi_id && $kabupaten_kota_id == null) {
            $query = DB::table('matchapro_alokasi_profiling as map')
                ->join('matchapro_temporary_update_profiling as mtup', function ($join) {
                    $join->on('map.id', '=', 'mtup.alokasi_profiling_id')
                        ->whereRaw('mtup.updated_at = (SELECT MAX(mtup_inner.updated_at) FROM matchapro_temporary_update_profiling as mtup_inner WHERE mtup_inner.alokasi_profiling_id = map.id)');
                })
                ->join('area_provinsi as ap', function ($join) use ($snapshot_id) {
                    $join->on('ap.id', '=', 'mtup.provinsi_id')
                        ->where('ap.snapshot_id', '=', $snapshot_id);
                })
                ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'mtup.kabupaten_kota_id')
                ->leftJoin('area_kecamatan as ak', 'ak.id', '=', 'mtup.kecamatan_id')
                ->leftJoin('area_kelurahan_desa as akd', 'akd.id', '=', 'mtup.kelurahan_desa_id')
                ->join('matchapro_users as mu', 'mu.id', '=', 'map.user_id')
                ->leftJoin('matchapro_users as mu_updated', 'mu_updated.id', '=', 'mtup.updated_by') // New join
                ->where('map.periode_id', $periode_id)
                ->where('ap.id', $provinsi_id)            
                ->select([
                    'map.id', 
                    'map.perusahaan_id as perusahaan_id',
                    'map.idsbr as kode', 
                    'mtup.nama_usaha as nama', 
                    'mtup.alamat', 
                    'mtup.provinsi_id',
                    'ap.kode as provinsi_kode',
                    'ap.nama as provinsi_nama',
                    'mtup.kabupaten_kota_id', 
                    'akk.kode as kabupaten_kota_kode',
                    'akk.nama as kabupaten_kota_nama',
                    'mtup.kecamatan_id', 
                    'ak.kode as kecamatan_kode',
                    'ak.nama as kecamatan_nama',
                    'mtup.kelurahan_desa_id',
                    'akd.kode as kelurahan_desa_kode',
                    'akd.nama as kelurahan_desa_nama',
                    'map.action_type',
                    'map.status_form',
                    'map.updated_at',
                    'mu_updated.nama as updated_by_nama', // Select the updated user's name or any other details you need
                    'mu_updated.id as updated_by_id'
                ]);

        }

        //User Kab
        if ($provinsi_id && $kabupaten_kota_id) {
            $query = DB::table('matchapro_alokasi_profiling as map')
                ->join('matchapro_temporary_update_profiling as mtup', function ($join) {
                    $join->on('map.id', '=', 'mtup.alokasi_profiling_id')
                        ->whereRaw('mtup.updated_at = (SELECT MAX(mtup_inner.updated_at) FROM matchapro_temporary_update_profiling as mtup_inner WHERE mtup_inner.alokasi_profiling_id = map.id)');
                })
                ->join('area_provinsi as ap', function ($join) use ($snapshot_id) {
                    $join->on('ap.id', '=', 'mtup.provinsi_id')
                        ->where('ap.snapshot_id', '=', $snapshot_id);
                })
                ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'mtup.kabupaten_kota_id')
                ->leftJoin('area_kecamatan as ak', 'ak.id', '=', 'mtup.kecamatan_id')
                ->leftJoin('area_kelurahan_desa as akd', 'akd.id', '=', 'mtup.kelurahan_desa_id')
                ->join('matchapro_users as mu', 'mu.id', '=', 'map.user_id')
                ->leftJoin('matchapro_users as mu_updated', 'mu_updated.id', '=', 'mtup.updated_by') // New join
                ->where('map.periode_id', $periode_id)
                ->where('ap.id', $provinsi_id)
                ->where('akk.id', $kabupaten_kota_id)            
                ->select([
                    'map.id', 
                    'map.perusahaan_id as perusahaan_id',
                    'map.idsbr as kode', 
                    'mtup.nama_usaha as nama', 
                    'mtup.alamat', 
                    'mtup.provinsi_id',
                    'ap.kode as provinsi_kode',
                    'ap.nama as provinsi_nama',
                    'mtup.kabupaten_kota_id', 
                    'akk.kode as kabupaten_kota_kode',
                    'akk.nama as kabupaten_kota_nama',
                    'mtup.kecamatan_id', 
                    'ak.kode as kecamatan_kode',
                    'ak.nama as kecamatan_nama',
                    'mtup.kelurahan_desa_id',
                    'akd.kode as kelurahan_desa_kode',
                    'akd.nama as kelurahan_desa_nama',
                    'map.action_type',
                    'map.status_form',
                    'map.updated_at',
                    'mu_updated.nama as updated_by_nama', // Select the updated user's name or any other details you need
                    'mu_updated.id as updated_by_id'
                ]);

        }

        //User Pusat
        if($provinsi_id == null && $kabupaten_kota_id == null){
            $query = DB::table('matchapro_alokasi_profiling as map')
            ->join('matchapro_temporary_update_profiling as mtup', function ($join) {
                $join->on('map.id', '=', 'mtup.alokasi_profiling_id')
                    ->whereRaw('mtup.updated_at = (SELECT MAX(mtup_inner.updated_at) FROM matchapro_temporary_update_profiling as mtup_inner WHERE mtup_inner.alokasi_profiling_id = map.id)');
            })
            ->join('area_provinsi as ap', function ($join) use ($snapshot_id) {
                $join->on('ap.id', '=', 'mtup.provinsi_id')
                    ->where('ap.snapshot_id', '=', $snapshot_id);
            })
            ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'mtup.kabupaten_kota_id')
            ->leftJoin('area_kecamatan as ak', 'ak.id', '=', 'mtup.kecamatan_id')
            ->leftJoin('area_kelurahan_desa as akd', 'akd.id', '=', 'mtup.kelurahan_desa_id')
            ->join('matchapro_users as mu', 'mu.id', '=', 'map.user_id')
            ->leftJoin('matchapro_users as mu_updated', 'mu_updated.id', '=', 'mtup.updated_by') // New join
            ->where('map.periode_id', $periode_id)
            ->select([
                'map.id', 
                'map.perusahaan_id as perusahaan_id',
                'map.idsbr as kode', 
                'mtup.nama_usaha as nama', 
                'mtup.alamat', 
                'mtup.provinsi_id',
                'ap.kode as provinsi_kode',
                'ap.nama as provinsi_nama',
                'mtup.kabupaten_kota_id', 
                'akk.kode as kabupaten_kota_kode',
                'akk.nama as kabupaten_kota_nama',
                'mtup.kecamatan_id', 
                'ak.kode as kecamatan_kode',
                'ak.nama as kecamatan_nama',
                'mtup.kelurahan_desa_id',
                'akd.kode as kelurahan_desa_kode',
                'akd.nama as kelurahan_desa_nama',
                'map.action_type',
                'map.status_form',
                'map.updated_at',
                'mu_updated.nama as updated_by_nama', // Select the updated user's name or any other details you need
                'mu_updated.id as updated_by_id'
            ]);

        }
        

                        

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('mtup.nama_usaha', 'like', "%$search%")
                    ->orWhere('map.idsbr', 'like', "%$search%")
                    ->orWhere('mtup.alamat', 'like', "%$search%")
                    ->orWhere('mtup.provinsi_id', 'like', "%$search%")
                    ->orWhere('akk.kode', 'like', "%$search%")
                    ->orWhere('akk.nama', 'like', "%$search%")
                    ->orWhere('ak.kode', 'like', "%$search%")
                    ->orWhere('ak.nama', 'like', "%$search%")
                    ->orWhere('akd.kode', 'like', "%$search%")
                    ->orWhere('akd.nama', 'like', "%$search%")
                    ->orWhere('map.status_form', 'like', "%$search%")
                    ->orWhere('map.updated_at', 'like', "%$search%")
                    ->orWhere('mu_updated.nama', 'like', "%$search%");
                    
                });
            }

            $countGroup = (clone $query)
            ->select('map.status_form', DB::raw('count(*) as total'))
            ->groupBy('map.status_form')
            ->get();

            
        
        if ($status_form) {
            $query->where('map.status_form', $status_form);
        }

            // Apply pagination and ordering
            $length = $request->input('length');
            $start = $request->input('start');
            $orderColumn = $request->input('order')[0]['column'];
            $orderDirection = $request->input('order')[0]['dir'];
            $columns = ['id', 'perusahaan_id','kode', 'nama', 'alamat', 
                'provinsi_id',
                'provinsi_kode',
                'provinsi_nama',
                'kabupaten_kota_id',
                'kabupaten_kota_kode',
                'kabupaten_kota_nama',
                'kecamatan_id',
                'kecamatan_kode',
                'kecamatan_nama',
                'kelurahan_desa_id',
                'kelurahan_desa_kode',
                'kelurahan_desa_nama',
                'status_form',
                'updated_at'];

            $query->orderBy($columns[$orderColumn], $orderDirection);
            $total = $query->count();

            $data = $query->offset($start)->limit($length)->get();
            
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
                'countGroup' => $countGroup
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
