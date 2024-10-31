<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Crypt;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\MasterWilayahController;

class DirektoriUsahaController extends Controller
{

    protected $masterWilayah;

    public function __construct(MasterWilayahController $masterWilayah) {
        $this->masterWilayah = $masterWilayah;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        

        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }
        

        // get wilayah akses dari user logged-in
        $viewProvinsi = auth()->user()->getPermissionsViaRoles()->contains('name','view-usaha-provinsi');
        $viewKabupaten = auth()->user()->getPermissionsViaRoles()->contains('name','view-usaha-kabkot');        
        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();

        $usaha = DB::table('business_perusahaan')
            ->selectRaw("
                CASE 
                    WHEN status_perusahaan_id IN (1, 2, 3, 5, 8) THEN 'aktif'
                    WHEN status_perusahaan_id IN (4, 6, 7, 9, 11) THEN 'tidak_aktif'
                    WHEN status_perusahaan_id IS NULL THEN 'undefined'
                END AS status_usaha,
                COUNT(*) AS total
            ")
            ->when($viewProvinsi || $viewKabupaten, function($query) use ($wilayahAkses) {
                return $query->whereIn('kabupaten_kota_id', $wilayahAkses->pluck('kabupaten_kota_id'));
            })            
            ->where(function($query) {
                $query->where('status_perusahaan_id', '<>', 10)
                      ->orWhereNull('status_perusahaan_id');
            })
            ->groupBy(DB::raw("
                CASE 
                    WHEN status_perusahaan_id IN (1, 2, 3, 5, 8) THEN 'aktif'
                    WHEN status_perusahaan_id IN (4, 6, 7, 9, 11) THEN 'tidak_aktif'
                    WHEN status_perusahaan_id IS NULL THEN 'undefined'
                END
            "))
            ->get();
        $usaha_aktif = $usaha->where('status_usaha', 'aktif')->first()->total ?? 0;
        $usaha_tidak_aktif = $usaha->where('status_usaha', 'tidak_aktif')->first()->total ?? 0;
        $usaha_undefined = $usaha->where('status_usaha', 'undefined')->first()->total ?? 0;
        $pageConfigs = ['sidebarCollapsed' => false];
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['name' => "Direktori Usaha"]
        ];                    

        $roleUser = auth()->user()->getRoleNames()[0];
        $level_role_user = explode('-' , $roleUser)[0]; // PUSAT, dst  

        //redirect ke halaman direktori usaha
        return view('/matchapro/page/direktori_usaha', 
        ['breadcrumbs' => $breadcrumbs, 
        'pageConfigs' => $pageConfigs,
        'usaha_aktif' => $usaha_aktif, 
        'usaha_tidak_aktif' => $usaha_tidak_aktif, 
        'usaha_undefined' => $usaha_undefined,
        // 'wilayahAksesProvinsi' => $wilayahAkses->pluck('provinsi_id')->unique()->values()->toArray(),
        // 'wilayahAksesKabupaten' => $wilayahAkses->pluck('kabupaten_kota_id')->unique()->values()->toArray(),
        'masterProvinsi' => $this->masterWilayah->getMasterProvinsiUser(),
        'wilayahAkses' => $wilayahAkses->count(),
        'levelRole' => $level_role_user
        ]);
    }

    public function searchUsingFREETEXTTABLE($nama_usaha, $alamat_usaha, $provinsi_id = null, $kabupaten_kota_id = null) {
        $viewProvinsi = auth()->user()->getPermissionsViaRoles()->contains('name','view-usaha-provinsi');
        $viewKabupaten = auth()->user()->getPermissionsViaRoles()->contains('name','view-usaha-kabkot');        
        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();

        $subquery = DB::table('business_perusahaan as FT_TBL')
            ->selectRaw("
                CASE 
                    WHEN KEY_TBL.RANK IS NOT NULL AND KEY_TBL2.RANK IS NOT NULL 
                        THEN 3.0 * KEY_TBL.RANK + 1.5 * KEY_TBL2.RANK 
                    ELSE 0 
                END AS skor_kalo,
                KEY_TBL.RANK AS rank_nama,
                KEY_TBL2.RANK AS rank_alamat,
                FT_TBL.id AS perusahaan_id,
                FT_TBL.nama,
                FT_TBL.alamat,
                FT_TBL.kode AS idsbr,
                FT_TBL.provinsi_id,
                FT_TBL.kabupaten_kota_id,
                FT_TBL.kecamatan_id,
                FT_TBL.kelurahan_desa_id,
                FT_TBL.status_perusahaan_id
            ")
            ->leftJoin(DB::raw("FREETEXTTABLE(business_perusahaan, nama, ?) AS KEY_TBL"), 'FT_TBL.id', '=', DB::raw('KEY_TBL.[KEY]'))
            ->leftJoin(DB::raw("FREETEXTTABLE(business_perusahaan, alamat, ?) AS KEY_TBL2"), 'FT_TBL.id', '=', DB::raw('KEY_TBL2.[KEY]'))
            ->when($viewProvinsi || $viewKabupaten, function($query) use ($wilayahAkses) {
                return $query->whereIn('FT_TBL.kabupaten_kota_id', $wilayahAkses->pluck('kabupaten_kota_id'));
            });
            // ->when($viewProvinsi, function($query) {
            //     return $query->where('FT_TBL.provinsi_id', auth()->user()->provinsi_id);
            // })
            // ->when($viewKabupaten, function($query) {
            //     return $query->where('FT_TBL.kabupaten_kota_id', auth()->user()->kabupaten_kota_id);
            // });


            // ->when($provinsi_id, function($query, $provinsi_id) {
            //     return $query->where('FT_TBL.provinsi_id', $provinsi_id);
            // })
            // ->when($kabupaten_kota_id, function($query, $kabupaten_kota_id) {
            //     return $query->where('FT_TBL.kabupaten_kota_id', $kabupaten_kota_id);
            // });          
            // ->where(function($query) {
            //     $query->where('status_perusahaan_id', '!=', 9)
            //         ->orWhereNull('status_perusahaan_id');
            // })
            // ->orderBy('skor_kalo', 'desc');
            // ->limit(10);
            
            $dir_usaha = DB::table(DB::raw("({$subquery->toSql()}) as FT_RESULT"))
            ->mergeBindings($subquery)
            ->addBinding([$nama_usaha, $alamat_usaha], 'join')
            ->leftJoin('area_provinsi as ap', function($join) {
                $join->on('ap.id', '=', 'FT_RESULT.provinsi_id')
                    ->where('ap.snapshot_id', 4);
            })->leftJoin('area_kabupaten_kota as akk', function($join) {
                $join->on('akk.id', '=', 'FT_RESULT.kabupaten_kota_id')
                    ->on('akk.provinsi_id', '=', 'ap.id');
            })->leftJoin('area_kecamatan as ak', function($join) {
                $join->on('ak.id', '=', 'FT_RESULT.kecamatan_id')
                    ->on('ak.kabupaten_kota_id', '=', 'akk.id');
            })->leftJoin('area_kelurahan_desa as akd', function($join) {
                $join->on('akd.id', '=', 'FT_RESULT.kelurahan_desa_id')
                    ->on('akd.kecamatan_id', '=', 'ak.id');
            })->leftJoin('business_ref_status_perusahaan as ss', 'ss.id', '=', 'FT_RESULT.status_perusahaan_id')
            ->select(
                'FT_RESULT.idsbr', 'FT_RESULT.nama as nama_usaha', 'FT_RESULT.alamat as alamat_usaha', 
                DB::raw("CONCAT(ap.kode, akk.kode, ak.kode, akd.kode) as kode_wilayah"), 
                'ap.kode as kdprov', 'akk.kode as kdkab', 'ak.kode as kdkec', 'akd.kode as kddesa',
                'ap.nama as nmprov', 'akk.nama as nmkab', 'ak.nama as nmkec', 'akd.nama as nmdesa',
                'FT_RESULT.perusahaan_id', 'ss.nama as status_perusahaan', 'ss.id as status_perusahaan_id', 
                'FT_RESULT.skor_kalo'
            )
            ->where('skor_kalo', '>', 0)
            ->orderBy('skor_kalo', 'desc');  

            return $dir_usaha;
    }

    public function getDataNormally() {
        $viewProvinsi = auth()->user()->getPermissionsViaRoles()->contains('name','view-usaha-provinsi');
        $viewKabupaten = auth()->user()->getPermissionsViaRoles()->contains('name','view-usaha-kabkot');        

        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();

        $dir_usaha = DB::table('business_perusahaan as bp')
            ->join('area_provinsi as ap', function($join) {
                $join->on('ap.id', '=', 'bp.provinsi_id')
                    ->where('ap.snapshot_id', '=', 4);
            })
            ->join('area_kabupaten_kota as akk', function($join) {
                $join->on('akk.id', '=', 'bp.kabupaten_kota_id')
                    ->on('akk.provinsi_id', '=' , 'ap.id');
            })
            ->leftJoin('area_kecamatan as ak', function($join) {
                $join->on('ak.id' , '=', 'bp.kecamatan_id')
                    ->on('ak.kabupaten_kota_id', '=', 'akk.id');
            })
            ->leftJoin('area_kelurahan_desa as akd', function($join) {
                $join->on('akd.id', '=', 'bp.kelurahan_desa_id')
                    ->on('akd.kecamatan_id', '=', 'ak.id');
            })
            ->leftJoin('business_ref_status_perusahaan as ss', 'ss.id', '=', 'bp.status_perusahaan_id')
            ->select(
                'bp.kode as idsbr', 'bp.nama as nama_usaha', 'bp.alamat as alamat_usaha',
                DB::raw("CONCAT(ap.kode, akk.kode, ak.kode, akd.kode) as kode_wilayah"), 
                'ap.kode as kdprov', 'akk.kode as kdkab', 'ak.kode as kdkec', 'akd.kode as kddesa',
                'ap.nama as nmprov', 'akk.nama as nmkab', 'ak.nama as nmkec', 'akd.nama as nmdesa',
                'bp.id as perusahaan_id', 'ss.nama as status_perusahaan', 'ss.id as status_perusahaan_id'
            )  
            ->when($viewProvinsi || $viewKabupaten, function($query) use ($wilayahAkses) {
                return $query->whereIn('bp.kabupaten_kota_id', $wilayahAkses->pluck('kabupaten_kota_id'));
            });
            // ->when($viewProvinsi, function($query) {
            //     return $query->where('bp.provinsi_id', auth()->user()->provinsi_id);
            // })
            // ->when($viewKabupaten, function($query) {
            //     return $query->where('bp.kabupaten_kota_id', auth()->user()->kabupaten_kota_id);
            // });
            
        return $dir_usaha;
    }    



    public function getDirektoriUsahaData(Request $request) {        

        if($request->filled('nama_usaha') && $request->filled('alamat_usaha')) {                      
            $dir_usaha = $this->searchUsingFREETEXTTABLE($request->nama_usaha, $request->alamat_usaha);
        } else {
            $dir_usaha = $this->getDataNormally();
        }      
        
        // sudah pasti get datanya dari getdatanormally, jadi bisa pake bp.nama dan bp.alamat
        if($request->filled('nama_usaha') && !$request->filled('alamat_usaha')) {
            $dir_usaha->whereRaw('FREETEXT(bp.nama, ?)', [$request->nama_usaha]);
        }

        // sudah pasti get datanya dari getdatanormally, jadi bisa pake bp.nama dan bp.alamat
        if($request->filled('alamat_usaha') && !$request->filled('nama_usaha')) {
            $dir_usaha->whereRaw('FREETEXT(bp.alamat, ?)', [$request->alamat_usaha]);
        }

        // filter berdasarkan status perusahaan
        if ($request->filled('status_perusahaan')) {
            if ($request->status_perusahaan != '-') {
                if ($request->status_perusahaan == '') {
                    $dir_usaha->whereNull('status_perusahaan_id');
                } else {
                    $dir_usaha->where('status_perusahaan_id', $request->status_perusahaan);
                }
            } else {
                $dir_usaha->where(function($query) {
                    $query->where('status_perusahaan_id', '<>', 10)
                        ->orWhereNull('status_perusahaan_id');
                });
            }
        } else {
            $dir_usaha->where(function($query) {
                $query->where('status_perusahaan_id', '<>', 10)
                    ->orWhereNull('status_perusahaan_id');
            });
        }

        // kalau ada request pencarian by idsbr, jangan pake like query biar ga lambat
        if($request->filled('idsbr')) {
            if($request->filled('nama_usaha') && $request->filled('alamat_usaha')) {
                $dir_usaha->where('idsbr', $request->idsbr);                
            } else {
                $dir_usaha->where('bp.kode', $request->idsbr);
            }
        }   
        
        if($request->filled('wilayah')) {
            $f_provinsi = substr($request->wilayah, 0, 2);
            $f_kabupaten = substr($request->wilayah, 2, 2);
            $f_kecamatan = substr($request->wilayah, 4, 3);
            $f_desa = substr($request->wilayah, 7, 3);

            if($f_provinsi) {
                $dir_usaha->where('ap.kode', $f_provinsi);
            }

            if($f_kabupaten) {
                $dir_usaha->where('akk.kode', $f_kabupaten);
            }

            if($f_kecamatan) {
                $dir_usaha->where('ak.kode', $f_kecamatan);
            }

            if($f_desa) {
                $dir_usaha->where('akd.kode', $f_desa);
            }
        }

        $recordsTotal = $dir_usaha->count();

        $start = $request->input('start');
        $length = $request->input('length'); 
        $dir_usaha= $dir_usaha->offset($start)->limit($length)->get();        

        $data = [];
        foreach($dir_usaha as $usaha) {
            // encrypt dulu perusahaan_id nya biar kaya aman gitu...
            $encrypted_id = Crypt::encrypt($usaha->perusahaan_id); // katanya fungsi Crypt ini udah url-safe gitu, jadi ga akan bikin break / error urlnya.

            $temp['idsbr'] = $usaha->idsbr;
            $temp['nama_usaha'] = $usaha->nama_usaha;
            $temp['alamat_usaha'] = $usaha->alamat_usaha;
            $temp['kode_wilayah'] = $usaha->kode_wilayah;
            $temp['kdprov'] = $usaha->kdprov;
            $temp['kdkab'] = $usaha->kdkab;
            $temp['kdkec'] = $usaha->kdkec;
            $temp['kddesa'] = $usaha->kddesa;
            $temp['nmprov'] = $usaha->nmprov;
            $temp['nmkab']= $usaha->nmkab;
            $temp['nmkec'] = $usaha->nmkec;
            $temp['nmdesa'] = $usaha->nmdesa;
            $temp['perusahaan_id'] = $encrypted_id; // masukin dah perusahaan_id yg terenripsinya disini            
            $temp['status_perusahaan'] = $usaha->status_perusahaan_id == 9 ? 'Duplikat' : $usaha->status_perusahaan;            
            $temp['action'] = route('form_update_usaha2.index', $encrypted_id);
            $temp['perusahaan_id'] = $encrypted_id;
            $temp['skor_kalo'] = $usaha->skor_kalo ?? null;
            $data[] = $temp;
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    public function getDirektoriUsahaDataById(Request $request) {
        $perusahaan_id = Crypt::decrypt($request->input('perusahaan_id'));
        $perusahaan = DB::table('business_perusahaan as bp')
            ->leftJoin('area_provinsi as ap', 'ap.id', '=', 'bp.provinsi_id')
            ->leftJoin('area_kabupaten_kota as akk', 'akk.id', '=', 'bp.kabupaten_kota_id')
            ->leftJoin('area_kecamatan as ak', 'ak.id', '=', 'bp.kecamatan_id')
            ->leftJoin('area_kelurahan_desa as akd', 'akd.id', '=', 'bp.kelurahan_desa_id')
            ->leftJoin('business_ref_status_perusahaan as brsp', 'brsp.id', '=', 'bp.status_perusahaan_id')
            ->select(
                'bp.nama as nama_usaha',
                'bp.nama_komersial as nama_komersial',
                'bp.alamat as alamat_usaha',
                'bp.kode_pos as kode_pos',
                'bp.latitude as latitude',
                'bp.longitude as longitude',
                'bp.kode as idsbr',                
                'ap.nama as nama_provinsi',
                'akk.nama as nama_kabupaten_kota',
                'ak.nama as nama_kecamatan',
                'akd.nama as nama_kelurahan_desa',
                'brsp.nama as status_perusahaan',
                'ap.kode as kode_provinsi',
                'akk.kode as kode_kabupaten_kota',      
                'ak.kode as kode_kecamatan',
                'akd.kode as kode_kelurahan_desa'                
            )
            ->where('bp.id', $perusahaan_id)
            ->first();

        $perusahaan = (array) $perusahaan;
        
        $kegiatan = DB::table('business_aktivitas_perusahaan as kap')->where('perusahaan_id', $perusahaan_id)
                ->orderBy('kbli', 'desc')
                ->orderBy('id', 'desc')
                ->first();
        $perusahaan['kegiatan_utama'] = $kegiatan ? $kegiatan->aktivitas : null ;
        $perusahaan['kbli'] = $kegiatan ? $kegiatan->kbli : null;
        $perusahaan['kategori'] = $kegiatan ? $kegiatan->kategori : null;

        $telepon = DB::table('business_alamat_telepon_perusahaan as batp')->where('perusahaan_id', $perusahaan_id)
                ->orderBy('nomor_telepon', 'desc')
                ->orderBy('id', 'desc')
                ->first();
        $perusahaan['telepon'] = $telepon ? $telepon->nomor_telepon : null  ;

        $email = DB::table('business_alamat_email_perusahaan as baep')->where('perusahaan_id', $perusahaan_id)
                ->where('email', 'like', '%@%')
                ->orderBy('id', 'desc')
                ->first();
        $perusahaan['email'] = $email ? $email->email : null;

        $website = DB::table('business_alamat_web_perusahaan as bawp')->where('perusahaan_id', $perusahaan_id)
                ->orderBy('website', 'desc')
                ->orderBy('id', 'desc')
                ->first();
        $perusahaan['website'] = $website ? $website->website : null; 

        $produk = DB::table('business_produk_perusahaan as bpp')->where('perusahaan_id', $perusahaan_id)
                ->orderBy('produk', 'desc')
                ->orderBy('id', 'desc')
                ->first();
        $perusahaan['produk'] = $produk ? $produk->produk : null;
        $perusahaan['edit_link'] = route('form_update_usaha2.index', Crypt::encrypt($perusahaan_id));

        return response()->json($perusahaan);
    }

    public function exportExcel(Request $request)
    {
        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }
        
        // check if user can download 
        $canDownload = auth()->user()->getPermissionsViaRoles()->contains('name','export-direktori-usaha');
        if(!$canDownload) {
            return redirect()->route('not-authorized.index')
                    ->with('message', 'User tidak memiliki hak akses untuk mengunduh data usaha!');
        }

        // check if data yang mau didownload sesuai dengan wilayah akses user
        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();
        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst
        if($level_role_user != 'PUSAT') {            
            if (!$wilayahAkses->pluck('kabupaten_kota_id')->contains($request->kabupaten)) {
                return redirect()->route('not-authorized.index')
                    ->with('message', 'User tidak memiliki hak akses untuk mengunduh data pada wilayah tersebut!');
            }
        }

        $filename = 'direktori_usaha_' . date('Y-m-d_His') . '.xlsx';

        return (new FastExcel($this->exportData($request)))
        ->download($filename);
    }

    private function exportData($request)
    {        
        // Query your data in chunks to avoid memory issues
        $query = DB::table('business_perusahaan as bp')
            ->leftJoin('area_provinsi as ap', 'ap.id', '=', 'bp.provinsi_id')
            ->leftJoin('area_kabupaten_kota as akk', 'akk.id', '=', 'bp.kabupaten_kota_id')
            ->leftJoin('area_kecamatan as ak', 'ak.id', '=', 'bp.kecamatan_id')
            ->leftJoin('area_kelurahan_desa as akd', 'akd.id', '=', 'bp.kelurahan_desa_id')
            ->leftJoin('business_ref_status_perusahaan as brsp', 'brsp.id', '=', 'bp.status_perusahaan_id')
            ->select(
                'bp.kode as idsbr',
                'bp.nama as nama_usaha',
                'bp.alamat as alamat_usaha',
                DB::raw("CONCAT(ap.kode, akk.kode, ak.kode, akd.kode) as kode_wilayah"),
                'brsp.nama as status_perusahaan'
            );

        // Apply filters based on $request
        if ($request->filled('provinsi')) {
            $query->where('bp.provinsi_id', $request->provinsi);
        }
        
        if($request->filled('kabupaten')) {
            $query->where('bp.kabupaten_kota_id', $request->kabupaten);
        }

        if($request->filled('kecamatan')) {
            $query->where('bp.kecamatan_id', $request->kecamatan);
        }

        if($request->filled('desa')) {
            $query->where('bp.kelurahan_desa_id', $request->desa);
        }


        // Handle the status_usaha array
        if($request->filled('status_usaha')) {
            $statusUsaha = $request->status_usaha;
            if (is_array($statusUsaha) && !empty($statusUsaha)) {
                $query->where(function($q) use ($statusUsaha) {
                    foreach ($statusUsaha as $status) {
                        if ($status === '') {
                            $q->orWhereNull('bp.status_perusahaan_id');
                        } else {
                            $q->orWhere('bp.status_perusahaan_id', $status);
                        }
                    }
                });
            }
        }

        // Process the data in chunks
        $query->orderBy('bp.id');

        return $this->generateRows($query);
    }

    private function generateRows($query)
    {
        // Yield the header row
        yield [
            'IDSBR', 'Nama Usaha', 'Alamat', 'Kode Wilayah', 'Status Perusahaan'
            // Add more columns as needed
        ];

        foreach ($query->cursor() as $record) {
            yield [
                $record->idsbr,
                $record->nama_usaha,
                $record->alamat_usaha,
                $record->kode_wilayah,
                $record->status_perusahaan,
                // Add more fields as needed
            ];
        }
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
