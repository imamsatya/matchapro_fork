<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MasterWilayahController;
use DB;
use Auth;

class ProgressProfilingController extends Controller
{

    protected $masterWilayah;    

    public function __construct(MasterWilayahController $masterWilayah) {
        $this->masterWilayah = $masterWilayah;        
    }

    public function mandiri_index() {
        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }

        $isAbleToviewPage = auth()->user()->getPermissionsViaRoles()->contains('name', 'view-progress-profiling-nasional') || auth()->user()->getPermissionsViaRoles()->contains('name', 'view-progress-profiling-provinsi') || auth()->user()->getPermissionsViaRoles()->contains('name', 'view-progress-profiling-kabkot');
        if(!$isAbleToviewPage) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }


            
        $pageConfigs = ['sidebarCollapsed' => false];
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['name' => "Progress Profiling - Mandiri"]
        ];

        // master provinsi user
        $mp = $this->masterWilayah->getMasterProvinsiUser();                
        
        $tahun = DB::table('matchapro_periode_profiling')
            ->pluck('start_date')
            ->map(function($date) {
                return \Carbon\Carbon::parse($date)->year;
            })
            ->unique()
            ->values(); 
            
        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst

        $listProfiling = DB::table('matchapro_periode_profiling')
            ->where('id', '=', env('PERIODE_PROFILING_MANDIRI'))
            ->pluck('id')->unique()->toArray();  

        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();        
        $provinsiAkses = $wilayahAkses->pluck('provinsi_id')->unique()->toArray();
        $kabupatenAkses= $wilayahAkses->pluck('kabupaten_kota_id')->unique()->toArray();

        $data = DB::table('matchapro_alokasi_profiling as pp')  
            ->join('area_provinsi as ap', 'ap.id', '=', 'pp.init_provinsi_id')          
            ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'pp.init_kabupaten_kota_id')
            ->join('matchapro_users as mm', 'mm.id', '=', 'pp.user_id')
            ->select('pp.id', 'pp.user_id', 'pp.status_form', 'pp.idsbr', 
                'pp.perusahaan_id', 'pp.periode_id', 'pp.action_type', DB::raw('concat(ap.kode, \' - \', ap.nama) as nmprov'),
                DB::raw('concat(akk.kode, \' - \', akk.nama) as nmkab'), 'ap.kode as kdprov', 'akk.kode as kdkab',
                'mm.username'
                )
            ->whereIn('periode_id', $listProfiling)                                    
            ->when($level_role_user != 'PUSAT', function($query) use ($provinsiAkses) {            
                $query->whereIn('init_provinsi_id', $provinsiAkses);
                
            })
            ->when($level_role_user != 'PUSAT', function($query) use ($kabupatenAkses) {
                $query->whereIn('init_kabupaten_kota_id', $kabupatenAkses);                
            })            
            ->get(); 

        // progres by status
        $statusCounts = $data->pluck('status_form')->map(function ($status) {
            if ($status === 'REJECTED') {
                return 'DRAFT';
            }
            return $status;
        })->countBy();
        $statusCounts = collect([
            'OPEN' => $statusCounts->get('OPEN', 0),
            'DRAFT' => $statusCounts->get('DRAFT', 0) + $statusCounts->get('REJECTED', 0),
            'SUBMITTED' => $statusCounts->get('SUBMITTED', 0),
            'APPROVED' => $statusCounts->get('APPROVED', 0),
        ]);

        // progres by kabupaten/kota        
        $kabupatenKotaDetails = $data->pluck('nmkab')->unique()->toArray();
        $kabupaten_axis = [];
        $listStatus =  ['OPEN', 'DRAFT', 'SUBMITTED', 'APPROVED'];
        $resultStatus = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempKabupaten = [];
            foreach ($kabupatenKotaDetails as $nmkab) {
                $count = $data->where('status_form', $status)->where('nmkab', $nmkab)->count();
                $tempCount[] = $count;
                $tempKabupaten[] = $nmkab;
            }        
            $resultStatus[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $kabupaten_axis = $tempKabupaten;
        }    

        // progres by user
        $userDetails = $data->pluck('username')->unique()->toArray();
        $user_axis = [];
        $resultUsers = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempUser = [];
            foreach ($userDetails as $username) {
                $count = $data->where('status_form', $status)->where('username', $username)->count();
                $tempCount[] = $count;
                $tempUser[] = $username;
            }        
            $resultUsers[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $user_axis = $tempUser;
        }

        return view('/matchapro/page/progress_profiling_mandiri', [
            'breadcrumbs' => $breadcrumbs,
            'pageConfigs' => $pageConfigs,
            'masterProvinsi' => $mp,
            'tahun' => $tahun,
            'status_usaha'=> $statusCounts,
            'total_target' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED';
            })->count(),
            'total_approved' => $data->filter(function ($item) {
                return $item->status_form == 'APPROVED';
            })->count(),
            'total_inprogress' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED' || $item->status_form == 'APPROVED';
            })->count(),
            'kabkot_axis' => $kabupaten_axis,
            'kabkot_data' => $resultStatus,
            'user_axis' => $user_axis,
            'user_data' => $resultUsers,
            'wilayahAkses' => $wilayahAkses->count(),
            'levelRole' => $level_role_user
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wilayah_index()
    {
        // $pageConfigs = ['blankPage' => true];
        // return view('/matchapro/misc/under-maintenance', ['pageConfigs' => $pageConfigs]);        

        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }

        $isAbleToviewPage = auth()->user()->getPermissionsViaRoles()->contains('name', 'view-progress-profiling-nasional') || auth()->user()->getPermissionsViaRoles()->contains('name', 'view-progress-profiling-provinsi') || auth()->user()->getPermissionsViaRoles()->contains('name', 'view-progress-profiling-kabkot');
        if(!$isAbleToviewPage) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }


            
        $pageConfigs = ['sidebarCollapsed' => false];
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['name' => "Progress Profiling - Periodik"]
        ];

        // master provinsi user
        $mp = $this->masterWilayah->getMasterProvinsiUser();                
        
        $tahun = DB::table('matchapro_periode_profiling')
            ->pluck('start_date')
            ->map(function($date) {
                return \Carbon\Carbon::parse($date)->year;
            })
            ->unique()
            ->values(); 
            
        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst

        $listProfiling = DB::table('matchapro_periode_profiling')->whereYear('start_date', date('Y'))
            ->where('id', '!=', env('PERIODE_PROFILING_MANDIRI'))
            ->pluck('id')->unique()->toArray();  

        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();        
        $provinsiAkses = $wilayahAkses->pluck('provinsi_id')->unique()->toArray();
        $kabupatenAkses= $wilayahAkses->pluck('kabupaten_kota_id')->unique()->toArray();

        $data = DB::table('matchapro_alokasi_profiling as pp')  
            ->join('area_provinsi as ap', 'ap.id', '=', 'pp.init_provinsi_id')          
            ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'pp.init_kabupaten_kota_id')
            ->join('matchapro_users as mm', 'mm.id', '=', 'pp.user_id')
            ->select('pp.id', 'pp.user_id', 'pp.status_form', 'pp.idsbr', 
                'pp.perusahaan_id', 'pp.periode_id', 'pp.action_type', DB::raw('concat(ap.kode, \' - \', ap.nama) as nmprov'),
                DB::raw('concat(akk.kode, \' - \', akk.nama) as nmkab'), 'ap.kode as kdprov', 'akk.kode as kdkab',
                'mm.username'
                )
            ->whereIn('periode_id', $listProfiling)                                    
            ->when($level_role_user != 'PUSAT', function($query) use ($provinsiAkses) {            
                $query->whereIn('init_provinsi_id', $provinsiAkses);
                
            })
            ->when($level_role_user != 'PUSAT', function($query) use ($kabupatenAkses) {
                $query->whereIn('init_kabupaten_kota_id', $kabupatenAkses);
                
            })            
            ->get(); 

        // progres by status
        $statusCounts = $data->pluck('status_form')->map(function ($status) {
            if ($status === 'REJECTED') {
                return 'DRAFT';
            }
            return $status;
        })->countBy();
        $statusCounts = collect([
            'OPEN' => $statusCounts->get('OPEN', 0),
            'DRAFT' => $statusCounts->get('DRAFT', 0) + $statusCounts->get('REJECTED', 0),
            'SUBMITTED' => $statusCounts->get('SUBMITTED', 0),
            'APPROVED' => $statusCounts->get('APPROVED', 0),
        ]);

        // progres by kabupaten/kota        
        $kabupatenKotaDetails = $data->pluck('nmkab')->unique()->toArray();
        $kabupaten_axis = [];
        $listStatus =  ['OPEN', 'DRAFT', 'SUBMITTED', 'APPROVED'];
        $resultStatus = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempKabupaten = [];
            foreach ($kabupatenKotaDetails as $nmkab) {
                $count = $data->where('status_form', $status)->where('nmkab', $nmkab)->count();
                $tempCount[] = $count;
                $tempKabupaten[] = $nmkab;
            }        
            $resultStatus[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $kabupaten_axis = $tempKabupaten;
        }    

        // progres by user
        $userDetails = $data->pluck('username')->unique()->toArray();
        $user_axis = [];
        $resultUsers = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempUser = [];
            foreach ($userDetails as $username) {
                $count = $data->where('status_form', $status)->where('username', $username)->count();
                $tempCount[] = $count;
                $tempUser[] = $username;
            }        
            $resultUsers[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $user_axis = $tempUser;
        }

        return view('/matchapro/page/progress_profiling_wilayah', [
            'breadcrumbs' => $breadcrumbs,
            'pageConfigs' => $pageConfigs,
            'masterProvinsi' => $mp,
            'tahun' => $tahun,
            'status_usaha'=> $statusCounts,
            'total_target' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED';
            })->count(),
            'total_approved' => $data->filter(function ($item) {
                return $item->status_form == 'APPROVED';
            })->count(),
            'total_inprogress' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED' || $item->status_form == 'APPROVED';
            })->count(),
            'kabkot_axis' => $kabupaten_axis,
            'kabkot_data' => $resultStatus,
            'user_axis' => $user_axis,
            'user_data' => $resultUsers,
            'wilayahAkses' => $wilayahAkses->count(),
            'levelRole' => $level_role_user
        ]);

    }

    public function profiler_index()
    {
        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }

        return redirect()->route('profiling.index');
        // $pageConfigs = ['sidebarCollapsed' => false];
        // $breadcrumbs = [
        //     ['link' => "home", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Layouts"], ['name' => "Collapsed menu"]
        // ];
        // return view('/matchapro/page/progress_profiling_profiler', ['breadcrumbs' => $breadcrumbs, 'pageConfigs' => $pageConfigs]);

    }

    public function getStatusStatistics(){

        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }
        
        // Get the authenticated user
        $user = Auth::user();

        // Get all role names for the user
        $roles = $user->getRoleNames(); // Returns a collection of role names

        // Check if the user has a role containing 'PUSAT'
        if ($roles->contains(fn($role) => str_contains($role, 'PUSAT'))) {
            // Perform action if role contains 'PUSAT'
            $result = $this->getStatusStatisticsPusat();
        }

        // Check if the user has a role containing 'PROVINSI'
        if ($roles->contains(fn($role) => str_contains($role, 'PROVINSI'))) {
            // Perform action if role contains 'PROVINSI'
            $result = $this->getStatusStatisticsProvinsi();
        }

        // Check if the user has a role containing 'KABKOT'
        if ($roles->contains(fn($role) => str_contains($role, 'KABKOT'))) {
            // Perform action if role contains 'KABKOT'
            $result = $this->getStatusStatisticsKabupatenKota();
        }
        
        return $result;
           
    }

    public function getStatusStatisticsKabupatenKota(){
        //Kabupaten Kota
        $user = Auth::user();
        $user_work = DB::table('matchapro_users_wilayah_akses')->where('user_id', $user->id)->get();
        
        $kabupaten_kota_work = $user_work->pluck('kabupaten_kota_id');

        $countGroupKabupatenKota = DB::table('matchapro_alokasi_profiling as map2')
        ->select('map2.status_form', DB::raw('COUNT(*) as total'))
        ->joinSub(
            DB::table('matchapro_users_wilayah_akses')
                ->distinct()
                ->select('user_id')
                ->whereIn('kabupaten_kota_id', $kabupaten_kota_work),
            'unique_muwa',
            'unique_muwa.user_id',
            '=',
            'map2.user_id'
        )
        ->join('matchapro_model_has_roles as mmhr', 'mmhr.model_id', '=', 'unique_muwa.user_id')
        ->whereIn('mmhr.role_id', [11, 12, 13]) //role : KABKOT-VIEWER, KABKOT-PROFILER, KABKOT-PROFILER-VIEWER
        ->groupBy('map2.status_form')
        ->get();

        $progressUserKabupatenKota = DB::table('matchapro_alokasi_profiling as map2')
        ->joinSub(
            DB::table('matchapro_users_wilayah_akses')
                ->select('user_id')
                ->whereIn('kabupaten_kota_id', $kabupaten_kota_work)
                ->distinct(),
            'unique_muwa',
            'unique_muwa.user_id',
            '=',
            'map2.user_id'
        )
        ->join('matchapro_model_has_roles as mmhr', 'mmhr.model_id', '=', 'unique_muwa.user_id')
        ->join('matchapro_users as mu', 'mu.id', '=', 'unique_muwa.user_id')
        ->select(
            'mu.nama',
            DB::raw("COUNT(CASE WHEN map2.status_form = 'OPEN' THEN 1 END) AS open_count"),
            DB::raw("COUNT(CASE WHEN map2.status_form = 'DRAFT' THEN 1 END) AS draft_count"),
            DB::raw("COUNT(CASE WHEN map2.status_form = 'SUBMITTED' THEN 1 END) AS submitted_count")
        )
        ->whereIn('mmhr.role_id', [11, 12, 13]) //role : KABKOT-VIEWER, KABKOT-PROFILER, KABKOT-PROFILER-VIEWER
        ->groupBy('mu.nama')
        ->orderBy('mu.nama')
        ->get();
        

        $label = $progressUserKabupatenKota->pluck('nama');
        
        $dataUserKabupatenKotaOpen = array_map('intval', $progressUserKabupatenKota->pluck('open_count')->toArray());
        $dataUserKabupatenKotaDraft = array_map('intval', $progressUserKabupatenKota->pluck('draft_count')->toArray());
        $dataUserKabupatenKotaSubmitted = array_map('intval', $progressUserKabupatenKota->pluck('submitted_count')->toArray());
        
        $series = [
            [
                'name' => 'OPEN',
                'data' => $dataUserKabupatenKotaOpen
            ],
            [
                'name' => 'DRAFT',
                'data' => $dataUserKabupatenKotaDraft
            ],
            [
                'name' => 'SUBMITTED',
                'data' => $dataUserKabupatenKotaSubmitted
            ],
        ];
        
        return response()->json([
            'countGroup' => $countGroupKabupatenKota,            
            'label' => $label ?? [],
            'series' => $series ?? []
        ]);
        
    }

    public function getStatusStatisticsPusat(){
        $snapshot_id = DB::table('area_provinsi')->max('snapshot_id');
        
        $query = DB::table('matchapro_alokasi_profiling as map');
        $countGroup = (clone $query)
        ->select('map.status_form', DB::raw('count(*) as total'))
        ->groupBy('map.status_form')
        ->get();

        //=========================
        //Progress Profiling User

        //User Pusat
        // Query result
        $label = ['00 PUSAT'];
        $progressProfilingDataPusat = DB::table('matchapro_alokasi_profiling as map')
        ->join('matchapro_users as mu', 'map.user_id', '=', 'mu.id')
        ->where('mu.provinsi_id', null)
        ->get();

        //PUSAT OPEN
        $countPusatOpen = count($progressProfilingDataPusat->where('status_form', 'OPEN'));
        $countPusatDraft = count($progressProfilingDataPusat->where('status_form', 'DRAFT'));
        $countPusatSubmitted = count($progressProfilingDataPusat->where('status_form', 'SUBMITTED'));
        
        $dataOpen=[$countPusatOpen];
        $dataDraft=[$countPusatDraft];
        $dataSubmitted=[$countPusatSubmitted];
        
        //Provinsi
        $allProvinsi = DB::table('area_provinsi as ap')
            ->select('ap.id as provinsi_id', 'ap.kode as provinsi_kode', 'ap.nama as provinsi_nama')
            ->where('ap.snapshot_id', '=', $snapshot_id)
            ->orderBy('provinsi_kode')
            ->get();

        $label = ['00 PUSAT'];
        $provinsiConcatenatedArray = $allProvinsi->pluck('provinsi_kode', 'provinsi_nama')
            ->map(function ($kode, $nama) {
                return $kode . ' ' . $nama;
            })
            ->values()
            ->toArray();
            
        $label=array_merge($label, $provinsiConcatenatedArray);;

        //Nanti ganti matchapro_users ke matchapro_users_wilayah_akses
        // V1
        // $progressProfilingDataProvinsi = DB::table('area_provinsi as ap')
        //         ->leftJoin('matchapro_users as mu', function($join) {
        //             $join->on('ap.id', '=', 'mu.provinsi_id');
        //         })
        //         ->leftJoin('matchapro_alokasi_profiling as map', function($join) {
        //             $join->on('map.user_id', '=', 'mu.id');
        //         })
        //         ->select(
        //             'ap.id as provinsi_id', 
        //             'ap.kode as provinsi_kode', 
        //             'ap.nama as provinsi_nama',
        //             DB::raw("SUM(CASE WHEN map.status_form = 'OPEN' THEN 1 ELSE 0 END) as open_count"),
        //             DB::raw("SUM(CASE WHEN map.status_form = 'DRAFT' THEN 1 ELSE 0 END) as draft_count"),
        //             DB::raw("SUM(CASE WHEN map.status_form = 'SUBMITTED' THEN 1 ELSE 0 END) as submitted_count")
        //         )
        //         ->where('ap.snapshot_id', '=', $snapshot_id)
        //         ->groupBy('ap.id', 'ap.kode', 'ap.nama')
        //         ->orderBy('ap.kode')
        //         ->get();

        //V2
        $progressProfilingDataProvinsi = DB::table('area_provinsi as provinces')
        ->select(
            'provinces.kode',
            'provinces.nama',
            DB::raw("COALESCE(SUM(CASE WHEN UniqueMap.status_form = 'OPEN' THEN 1 ELSE 0 END), 0) AS open_count"),
            DB::raw("COALESCE(SUM(CASE WHEN UniqueMap.status_form = 'DRAFT' THEN 1 ELSE 0 END), 0) AS draft_count"),
            DB::raw("COALESCE(SUM(CASE WHEN UniqueMap.status_form = 'SUBMITTED' THEN 1 ELSE 0 END), 0) AS submitted_count")
        )
        ->leftJoinSub(
            DB::table('matchapro_alokasi_profiling as map2')
                ->select(
                    'map2.id',
                    'ap.kode',
                    'ap.nama',
                    'map2.status_form',
                    DB::raw('ROW_NUMBER() OVER (PARTITION BY map2.id ORDER BY map2.id) AS row_num')
                )
                ->join('matchapro_users_wilayah_akses as muwa', 'muwa.user_id', '=', 'map2.user_id')
                ->leftJoin('area_provinsi as ap', 'ap.id', '=', 'muwa.provinsi_id')
                ->where('ap.snapshot_id', 4),
            'UniqueMap',
            function ($join) {
                $join->on('provinces.kode', '=', 'UniqueMap.kode')
                     ->where('UniqueMap.row_num', 1);
            }
        )
        ->where('provinces.snapshot_id', $snapshot_id)
        ->groupBy('provinces.kode', 'provinces.nama')
        ->orderBy('provinces.kode')
        ->get();
    
            
            $dataProvinsiOpen = array_map('intval', $progressProfilingDataProvinsi->pluck('open_count')->toArray());
            $dataProvinsiDraft = array_map('intval', $progressProfilingDataProvinsi->pluck('draft_count')->toArray());
            $dataProvinsiSubmitted = array_map('intval', $progressProfilingDataProvinsi->pluck('submitted_count')->toArray());
            
            $finalDataOpen = array_merge($dataOpen, $dataProvinsiOpen);
            $finalDataDraft = array_merge($dataDraft, $dataProvinsiDraft);
            $finalDataSubmitted = array_merge($dataSubmitted, $dataProvinsiSubmitted);
    
            $series = [
                [
                    'name' => 'OPEN',
                    'data' => $finalDataOpen
                ],
                [
                    'name' => 'DRAFT',
                    'data' => $finalDataDraft
                ],
                [
                    'name' => 'SUBMITTED',
                    'data' => $finalDataSubmitted
                ],
            ];

        return response()->json([
            'countGroup' => $countGroup,            
            'label' => $label,
            'series' => $series
        ]);
    }

    public function getStatusStatisticsProvinsi(){
        //Provinsi
        $user = Auth::user();
        $user_work = DB::table('matchapro_users_wilayah_akses')->where('user_id', $user->id)->get();
        
        $kabupaten_kota_work = $user_work->pluck('kabupaten_kota_id');
        $countGroupProvinsi = DB::table('matchapro_alokasi_profiling as map2')
        ->joinSub(
            DB::table('matchapro_users_wilayah_akses')
                ->select('user_id')
                ->whereIn('kabupaten_kota_id', $kabupaten_kota_work)
                ->distinct(),
            'unique_muwa',
            'unique_muwa.user_id',
            '=',
            'map2.user_id'
        )
        ->select('map2.status_form', DB::raw('COUNT(*) as total'))
        ->groupBy('map2.status_form')
        ->get();

        //User Prov
        $KabupatenKotaInProv = DB::table('area_kabupaten_kota as akk')
        ->select('akk.id as kabupaten_kota_id', 'akk.kode as kabupaten_kota_kode', 'akk.nama as kabupaten_kota_nama')
        ->whereIn('akk.id', $kabupaten_kota_work)
        ->orderBy('akk.kode')
        ->get(); 
        
        $kabupatenKotaConcatenatedArray = $KabupatenKotaInProv->pluck('kabupaten_kota_kode', 'kabupaten_kota_nama')
        ->map(function ($kode, $nama) {
            return $kode . ' ' . $nama;
        })
        ->values()
        ->toArray();
        $label = $kabupatenKotaConcatenatedArray;

        //Progress Profiling 
        $kabupatenKotaIds = $kabupaten_kota_work->toArray();

        $query = "
            WITH UniqueMap2 AS (
                SELECT 
                    map2.id,
                    akk.id AS kabupaten_kota_id,
                    akk.kode, 
                    akk.nama,
                    map2.status_form,
                    ROW_NUMBER() OVER (PARTITION BY map2.id ORDER BY map2.id) AS row_num
                FROM matchapro_alokasi_profiling map2
                JOIN matchapro_users_wilayah_akses muwa ON muwa.user_id = map2.user_id
                LEFT JOIN area_provinsi ap ON ap.id = muwa.provinsi_id
                LEFT JOIN area_kabupaten_kota akk ON akk.id = muwa.kabupaten_kota_id
                WHERE ap.snapshot_id = 4 
            )
            SELECT 
                akk.id,
                akk.kode, 
                akk.nama,
                COALESCE(SUM(CASE WHEN UniqueMap2.status_form = 'OPEN' THEN 1 ELSE 0 END), 0) AS open_count,
                COALESCE(SUM(CASE WHEN UniqueMap2.status_form = 'DRAFT' THEN 1 ELSE 0 END), 0) AS draft_count,
                COALESCE(SUM(CASE WHEN UniqueMap2.status_form = 'SUBMITTED' THEN 1 ELSE 0 END), 0) AS submitted_count
            FROM ( 
                SELECT DISTINCT id, kode, nama
                FROM area_kabupaten_kota
            ) AS akk
            LEFT JOIN UniqueMap2 ON akk.id = UniqueMap2.kabupaten_kota_id
            AND UniqueMap2.row_num = 1
            WHERE akk.id IN (" . implode(',', array_fill(0, count($kabupatenKotaIds), '?')) . ")
            GROUP BY akk.kode, akk.nama, akk.id
            ORDER BY akk.kode;
        ";

        // Run the full query using DB::select() with bindings
        $progressProfilingDataKabupatenKota = DB::select(DB::raw($query), $kabupatenKotaIds);
        
        $dataKabupatenKotaOpen = array_map('intval', array_column($progressProfilingDataKabupatenKota, 'open_count'));
        $dataKabupatenKotaDraft = array_map('intval', array_column($progressProfilingDataKabupatenKota, 'draft_count'));
        $dataKabupatenKotaSubmitted = array_map('intval', array_column($progressProfilingDataKabupatenKota, 'submitted_count'));
        

        $series = [
            [
                'name' => 'OPEN',
                'data' => $dataKabupatenKotaOpen
            ],
            [
                'name' => 'DRAFT',
                'data' => $dataKabupatenKotaDraft
            ],
            [
                'name' => 'SUBMITTED',
                'data' => $dataKabupatenKotaSubmitted
            ],
        ];



        return response()->json([
            'countGroup' => $countGroupProvinsi,            
            'label' => $label,
            'series' => $series
        ]);
    }

    public function countProgressProfilingProvinsi($progressProfilingData, $snapshot_id){
        // Define the statuses you want to include
        $statuses = ['OPEN', 'DRAFT', 'SUBMITTED'];

        // Get all provinsi_id and related information
        $allProvinsi = DB::table('area_provinsi as ap')
            ->select('ap.id as provinsi_id', 'ap.kode as provinsi_kode', 'ap.nama as provinsi_nama')
            ->where('ap.snapshot_id', '=', $snapshot_id)
            ->orderBy('provinsi_kode')
            ->get();

        // Initialize an array to hold the formatted data for each status
        $allStatusData = [];

        foreach ($statuses as $status) {
            // Clone $progressProfilingData and filter for the current status
            $provinsiStatus = (clone $progressProfilingData)
                ->select('map.status_form', 'bp.provinsi_id', 'ap.kode as provinsi_kode', 'ap.nama as provinsi_nama', DB::raw('count(*) as total'))
                ->where('map.status_form', $status)
                ->groupBy('map.status_form', 'bp.provinsi_id', 'ap.kode', 'ap.nama')
                ->orderBy('provinsi_kode')
                ->get();

            // Convert to a keyed collection by 'provinsi_id' for easy lookup
            $provinsiStatus = $provinsiStatus->keyBy('provinsi_id');

            // Merge all provinces with the results, setting total = 0 for any missing 'provinsi_id'
            $allProvinsiWithStatus = $allProvinsi->map(function ($provinsi) use ($provinsiStatus, $status) {
                // Check if the provinsi exists in $provinsiStatus, if not, set count to 0
                return [
                    'status_form' => $status,
                    'provinsi_id' => $provinsi->provinsi_id,
                    'provinsi_kode' => $provinsi->provinsi_kode,
                    'provinsi_nama' => $provinsi->provinsi_nama,
                    'total' => $provinsiStatus->has($provinsi->provinsi_id) ? $provinsiStatus[$provinsi->provinsi_id]->total : 0
                ];
            });

            // Convert to an array and extract 'total' values into a new array for the current status
            $dataValues = array_column($allProvinsiWithStatus->toArray(), 'total');
            
            // Add the status data to $allStatusData
            $allStatusData[] = [
                'name' => $status,
                'data' => array_map('intval', $dataValues) // Convert totals to integers
            ];
        }

        return $allStatusData;
    }

    public function getDataProgres(Request $request) {

        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst

        $provinsi = $request->provinsi;
        $kabupaten = $request->kabupaten;
        $tahun = $request->tahun_profiling;        

        // get list profiling periodeik selected tahun
        $listProfiling = DB::table('matchapro_periode_profiling')->whereYear('start_date', $tahun)
        ->where('id', '!=', env('PERIODE_PROFILING_MANDIRI'))
        ->pluck('id')->unique()->toArray();    

        // get wilaya akses user
        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();        
        $provinsiAkses = $wilayahAkses->pluck('provinsi_id')->unique()->toArray();
        $kabupatenAkses= $wilayahAkses->pluck('kabupaten_kota_id')->unique()->toArray();

        if($provinsi) {
            if (!in_array($provinsi, $provinsiAkses)) {                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to province data'
                ], 403);
            }
            $provinsiAkses = [$provinsi];
        }

        if($kabupaten) {
            if (!in_array($kabupaten, $kabupatenAkses)) {                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to kabupaten data'
                ], 403);
            }
            $kabupatenAkses = [$kabupaten];
        }    

        $data = DB::table('matchapro_alokasi_profiling as pp')  
            ->join('area_provinsi as ap', 'ap.id', '=', 'pp.init_provinsi_id')          
            ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'pp.init_kabupaten_kota_id')
            ->join('matchapro_users as mm', 'mm.id', '=', 'pp.user_id')
            ->select('pp.id', 'pp.user_id', 'pp.status_form', 'pp.idsbr', 
                'pp.perusahaan_id', 'pp.periode_id', 'pp.action_type', DB::raw('concat(ap.kode, \' - \', ap.nama) as nmprov'),
                DB::raw('concat(akk.kode, \' - \', akk.nama) as nmkab'), 'ap.kode as kdprov', 'akk.kode as kdkab',
                'mm.username'
                )
            ->whereIn('periode_id', $listProfiling)                                    
            ->when($level_role_user != 'PUSAT', function($query) use ($provinsiAkses) {            
                $query->whereIn('init_provinsi_id', $provinsiAkses);
                
            })
            ->when($level_role_user != 'PUSAT', function($query) use ($kabupatenAkses) {
                $query->whereIn('init_kabupaten_kota_id', $kabupatenAkses);
                
            })            
            ->get();        

        // progres by status
        $statusCounts = $data->pluck('status_form')->map(function ($status) {
            if ($status === 'REJECTED') {
                return 'DRAFT';
            }
            return $status;
        })->countBy();
        $statusCounts = collect([
            'OPEN' => $statusCounts->get('OPEN', 0),
            'DRAFT' => $statusCounts->get('DRAFT', 0) + $statusCounts->get('REJECTED', 0),
            'SUBMITTED' => $statusCounts->get('SUBMITTED', 0),
            'APPROVED' => $statusCounts->get('APPROVED', 0),
        ]);

        // progres by kabupaten/kota        
        $kabupatenKotaDetails = $data->pluck('nmkab')->unique()->toArray();
        $kabupaten_axis = [];
        $listStatus =  ['OPEN', 'DRAFT', 'SUBMITTED', 'APPROVED'];
        $resultStatus = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempKabupaten = [];
            foreach ($kabupatenKotaDetails as $nmkab) {
                $count = $data->where('status_form', $status)->where('nmkab', $nmkab)->count();
                $tempCount[] = $count;
                $tempKabupaten[] = $nmkab;
            }        
            $resultStatus[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $kabupaten_axis = $tempKabupaten;
        }    

        // progres by user
        $userDetails = $data->pluck('username')->unique()->toArray();
        $user_axis = [];
        $resultUsers = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempUser = [];
            foreach ($userDetails as $username) {
                $count = $data->where('status_form', $status)->where('username', $username)->count();
                $tempCount[] = $count;
                $tempUser[] = $username;
            }        
            $resultUsers[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $user_axis = $tempUser;
        }        

        return response()->json([
            'status_usaha'=> $statusCounts,  
            'total_target' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED';
            })->count(),
            'total_approved' => $data->filter(function ($item) {
                return $item->status_form == 'APPROVED';
            })->count(),
            'total_inprogress' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED' || $item->status_form == 'APPROVED';
            })->count(),
            'kabkot_axis' => $kabupaten_axis,
            'kabkot_data' => $resultStatus,
            'user_axis' => $user_axis,
            'user_data' => $resultUsers
        ], 200);            
    }

    public function getDataMandiri(Request $request) {
        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst

        $provinsi = $request->provinsi;
        $kabupaten = $request->kabupaten;              

        // get list profiling mandiri selected tahun
        $listProfiling = DB::table('matchapro_periode_profiling')
        ->where('id', '=', env('PERIODE_PROFILING_MANDIRI'))
        ->pluck('id')->unique()->toArray();    

        // get wilaya akses user
        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();        
        $provinsiAkses = $wilayahAkses->pluck('provinsi_id')->unique()->toArray();
        $kabupatenAkses= $wilayahAkses->pluck('kabupaten_kota_id')->unique()->toArray();

        if($provinsi) {
            if (!in_array($provinsi, $provinsiAkses)) {                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to province data'
                ], 403);
            }
            $provinsiAkses = [$provinsi];
        }

        if($kabupaten) {
            if (!in_array($kabupaten, $kabupatenAkses)) {                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to kabupaten data'
                ], 403);
            }
            $kabupatenAkses = [$kabupaten];
        }    

        $data = DB::table('matchapro_alokasi_profiling as pp')  
            ->join('area_provinsi as ap', 'ap.id', '=', 'pp.init_provinsi_id')          
            ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'pp.init_kabupaten_kota_id')
            ->join('matchapro_users as mm', 'mm.id', '=', 'pp.user_id')
            ->select('pp.id', 'pp.user_id', 'pp.status_form', 'pp.idsbr', 
                'pp.perusahaan_id', 'pp.periode_id', 'pp.action_type', DB::raw('concat(ap.kode, \' - \', ap.nama) as nmprov'),
                DB::raw('concat(akk.kode, \' - \', akk.nama) as nmkab'), 'ap.kode as kdprov', 'akk.kode as kdkab',
                'mm.username'
                )
            ->whereIn('periode_id', $listProfiling)                                    
            ->when($level_role_user != 'PUSAT', function($query) use ($provinsiAkses) {            
                $query->whereIn('init_provinsi_id', $provinsiAkses);
                
            })
            ->when($level_role_user != 'PUSAT', function($query) use ($kabupatenAkses) {
                $query->whereIn('init_kabupaten_kota_id', $kabupatenAkses);
                
            })            
            ->get();        

        // progres by status
        $statusCounts = $data->pluck('status_form')->map(function ($status) {
            if ($status === 'REJECTED') {
                return 'DRAFT';
            }
            return $status;
        })->countBy();
        $statusCounts = collect([
            'OPEN' => $statusCounts->get('OPEN', 0),
            'DRAFT' => $statusCounts->get('DRAFT', 0) + $statusCounts->get('REJECTED', 0),
            'SUBMITTED' => $statusCounts->get('SUBMITTED', 0),
            'APPROVED' => $statusCounts->get('APPROVED', 0),
        ]);

        // progres by kabupaten/kota        
        $kabupatenKotaDetails = $data->pluck('nmkab')->unique()->toArray();
        $kabupaten_axis = [];
        $listStatus =  ['OPEN', 'DRAFT', 'SUBMITTED', 'APPROVED'];
        $resultStatus = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempKabupaten = [];
            foreach ($kabupatenKotaDetails as $nmkab) {
                $count = $data->where('status_form', $status)->where('nmkab', $nmkab)->count();
                $tempCount[] = $count;
                $tempKabupaten[] = $nmkab;
            }        
            $resultStatus[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $kabupaten_axis = $tempKabupaten;
        }    

        // progres by user
        $userDetails = $data->pluck('username')->unique()->toArray();
        $user_axis = [];
        $resultUsers = [];
        foreach($listStatus as $status) {
            $tempCount = [];
            $tempUser = [];
            foreach ($userDetails as $username) {
                $count = $data->where('status_form', $status)->where('username', $username)->count();
                $tempCount[] = $count;
                $tempUser[] = $username;
            }        
            $resultUsers[] = [
                'name' => $status,
                'data' => $tempCount
            ];
            $user_axis = $tempUser;
        }        

        return response()->json([
            'status_usaha'=> $statusCounts,  
            'total_target' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED';
            })->count(),
            'total_approved' => $data->filter(function ($item) {
                return $item->status_form == 'APPROVED';
            })->count(),
            'total_inprogress' => $data->reject(function ($item) {
                return $item->status_form == 'CANCELED' || $item->status_form == 'APPROVED';
            })->count(),
            'kabkot_axis' => $kabupaten_axis,
            'kabkot_data' => $resultStatus,
            'user_axis' => $user_axis,
            'user_data' => $resultUsers
        ], 200);  
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
