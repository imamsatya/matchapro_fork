<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class ProfilingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageConfigs = ['sidebarCollapsed' => false];
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['name' => "Profiling"]
        ];
        $id_profiling_mandiri = env('PERIODE_PROFILING_MANDIRI');
        
        $periode_profiling = DB::table('matchapro_periode_profiling')
                            ->where('id', '!=', $id_profiling_mandiri)
                            ->get();
        
        
        return view('/matchapro/page/profiling', [
            'breadcrumbs' => $breadcrumbs, 
            'pageConfigs' => $pageConfigs,
            'periode_profiling' => $periode_profiling,
            'id_profiling_mandiri' => $id_profiling_mandiri
        ]);
    }

    public function getData(Request $request)
    {
        
        $status_form = $request->input('status_form');
        
        $periode_id = $request->input('periode_id');
        
        $user = auth()->user();
        
        $snapshot_id = DB::table('area_provinsi')->max('snapshot_id');
        
        //Bukan Periode Mandiri
        if ($periode_id != env('PERIODE_PROFILING_MANDIRI')) {
            $query = DB::table('matchapro_alokasi_profiling as map')
            ->join('business_perusahaan as bp', 'map.perusahaan_id', '=', 'bp.id')
            ->join('area_provinsi as ap', function ($join) use ($snapshot_id) {
                $join->on('ap.id', '=', 'bp.provinsi_id')
                    ->where('ap.snapshot_id', '=', $snapshot_id);
            })
            ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'bp.kabupaten_kota_id')
            ->leftjoin('area_kecamatan as ak', 'ak.id', '=', 'bp.kecamatan_id')
            ->leftjoin('area_kelurahan_desa as akd', 'akd.id', '=', 'bp.kelurahan_desa_id')
            ->where('map.periode_id', $periode_id)
            ->where('map.user_id', $user->id)
            ->select([
                'map.id', 
                'bp.id as perusahaan_id',
                'bp.kode', 
                'bp.nama', 
                'bp.alamat', 
                'bp.provinsi_id',
                'ap.kode as provinsi_kode',
                'ap.nama as provinsi_nama',
                'bp.kabupaten_kota_id', 
                'akk.kode as kabupaten_kota_kode',
                'akk.nama as kabupaten_kota_nama',
                'bp.kecamatan_id', 
                'ak.kode as kecamatan_kode',
                'ak.nama as kecamatan_nama',
                'bp.kelurahan_desa_id',
                'akd.kode as kelurahan_desa_kode',
                'akd.nama as kelurahan_desa_nama',
                'map.status_form',
                'map.updated_at'
            ]);

        
            // Fetch data based on the selected periode
            // Apply search filter if provided by DataTables
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('bp.nama', 'like', "%$search%")
                    ->orWhere('bp.kode', 'like', "%$search%")
                    ->orWhere('bp.alamat', 'like', "%$search%")
                    ->orWhere('bp.provinsi_id', 'like', "%$search%")
                    ->orWhere('akk.kode', 'like', "%$search%")
                    ->orWhere('akk.nama', 'like', "%$search%")
                    ->orWhere('ak.kode', 'like', "%$search%")
                    ->orWhere('ak.nama', 'like', "%$search%")
                    ->orWhere('akd.kode', 'like', "%$search%")
                    ->orWhere('akd.nama', 'like', "%$search%")
                    ->orWhere('map.status_form', 'like', "%$search%")
                    ->orWhere('map.updated_at', 'like', "%$search%");
                });
            }

        }

        //Periode Mandiri
        if($periode_id == env('PERIODE_PROFILING_MANDIRI')){

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
                ->where('map.user_id', $user->id)
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
            $data = $data->map(function ($item) {
                $item->perusahaan_id = Crypt::encrypt($item->perusahaan_id);
                return $item;
            });
            
            
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
                'countGroup' => $countGroup
            ]);
    }

    public function bhbuTransform($bhbu) {
        // from sbr to form
        // 1	PT/PT Persero/Perupm -> 1
        // 2	CV -> 7
        // 3	Firma -> 8
        // 4	Koperasi/Dana pensiun -> 3
        // 5	Yayasan -> 2
        // 6	Ijin khusus -> null
        // 7	Perwakilan perusahaan/lembaga asing -> 11
        // 8	Tidak berbadan usaha -> null
        // 9	Usaha Orang Perseorangan -> 12
        // 10	BUM Desa -> 6
        $data = [
            '1' => 1,
            '2' => 7,
            '3' => 8,
            '4' => 3,
            '5' => 2,
            '6' => null,
            '7' => 11,
            '8' => 12,
            '9' => 12,
            '10' => 6,
        ];
        return isset($data[$bhbu]) ? $data[$bhbu] : null;
    }
    

    public function seedingDataTemporaryUpdateProfiling($perusahaan_id, $alokasi_id) {
        $usaha = DB::table('business_perusahaan')->where('id', $perusahaan_id)->first();        

        // ambil satu aja 
        $kegiatan = DB::table('business_aktivitas_perusahaan')->where('perusahaan_id', $perusahaan_id)
                    ->orderBy('kbli', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

        // ambil satu aja
        $telepon = DB::table('business_alamat_telepon_perusahaan')->where('perusahaan_id', $perusahaan_id)
                    ->orderBy('nomor_telepon', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

        // ambil satu aja
        $email = DB::table('business_alamat_email_perusahaan')->where('perusahaan_id', $perusahaan_id)
                ->where('email', 'like', '%@%')
                ->orderBy('id', 'desc')
                ->first();
        
        // ambil satu aja
        $website = DB::table('business_alamat_web_perusahaan')->where('perusahaan_id', $perusahaan_id)
                    ->orderBy('website', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

        // ambil satu aja
        $produk = DB::table('business_produk_perusahaan')->where('perusahaan_id', $perusahaan_id)
                    ->orderBy('produk', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

        // setup variable to be inserted into table temporary_update ygy        
        $alokasi_profiling_id = $alokasi_id;
        $nama_usaha = $usaha->nama;
        $nama_komersial = $usaha->nama_komersial;
        $provinsi_id = $usaha->provinsi_id;
        $kabupaten_kota_id = $usaha->kabupaten_kota_id;
        $kecamatan_id = $usaha->kecamatan_id;
        $kelurahan_desa_id = $usaha->kelurahan_desa_id;
        $sls_deskripsi = null;
        $alamat = $usaha->alamat;
        $kodepos = $usaha->kode_pos;
        $telp = $telepon ? $telepon->nomor_telepon : null;
        $no_wa = null;
        $email = $email ? $email->email : null;
        $website = $website ? $website->website : null;
        $latitude = $usaha->latitude;
        $longitude = $usaha->longitude;
        $kbli = $kegiatan ? $kegiatan->kbli : null;
        $kategori = $kegiatan ? $kegiatan->kategori : null;
        $kegiatan_utama = $kegiatan ? $kegiatan->aktivitas : null;
        $jaringan_usaha_id = null; // sudah sesuai -> tidak perlu transformasi
        $bentuk_badan_usaha_id = $this->bhbuTransform($usaha->jenis_badan_hukum_badan_usaha_id);
        $deskripsi_produk_usaha = $produk ? $produk->produk : null;
        $jenis_kepemilikan_id = null; // 1. BUMN, 2. Non BUMN, 3. BUMD, 4. BUMDes -> pas masukin ke db sbr perlu transformasi
        $tahun_berdiri = $usaha->tahun_pendirian;
        $keterangan_submitted = null;
        $keterangan_approved = null;
        $keterangan_rejected = null;
        $status_perusahaan_id = $usaha->status_perusahaan_id;
        $status_form = 'OPEN';
        $created_at = now();
        $updated_at = now();
                
        return  compact('alokasi_profiling_id', 'nama_usaha',
        'nama_komersial','provinsi_id','kabupaten_kota_id','kecamatan_id','kelurahan_desa_id',
        'sls_deskripsi','alamat','kodepos','telp','no_wa','email','website','latitude','longitude',
        'kbli','kategori','kegiatan_utama','jaringan_usaha_id','bentuk_badan_usaha_id','deskripsi_produk_usaha',
        'jenis_kepemilikan_id','tahun_berdiri','keterangan_submitted','keterangan_approved','keterangan_rejected',
        'status_perusahaan_id','status_form','created_at','updated_at');
    }

    public function insertTemporaryUpdateProfiling($data) {
        
        DB::table('matchapro_temporary_update_profiling')->insert([
            'alokasi_profiling_id' => isset($data['alokasi_profiling_id']) ? $data['alokasi_profiling_id'] : null,
            'nama_usaha' => isset($data['nama_usaha']) ? $data['nama_usaha'] : null,
            'nama_komersial' => isset($data['nama_komersial']) ? $data['nama_komersial'] : null,
            'provinsi_id' => isset($data['provinsi_id']) ? $data['provinsi_id'] : null,
            'kabupaten_kota_id' => isset($data['kabupaten_kota_id']) ? $data['kabupaten_kota_id'] : null,
            'kecamatan_id' => isset($data['kecamatan_id']) ? $data['kecamatan_id'] : null,
            'kelurahan_desa_id' => isset($data['kelurahan_desa_id']) ? $data['kelurahan_desa_id'] : null,
            'sls_deskripsi' => isset($data['sls_deskripsi']) ? $data['sls_deskripsi'] : null,
            'alamat' => isset($data['alamat']) ? $data['alamat'] : null,
            'kodepos' => isset($data['kodepos']) ? $data['kodepos'] : null,
            'telp' => isset($data['telp']) ? $data['telp'] : null,
            'no_wa' => isset($data['no_wa']) ? $data['no_wa'] : null,
            'email' => isset($data['email']) ? $data['email'] : null,
            'website' => isset($data['website']) ? $data['website'] : null,
            'latitude' => isset($data['latitude']) ? $data['latitude'] : null,
            'longitude' => isset($data['longitude']) ? $data['longitude'] : null,
            'kbli' => isset($data['kbli']) ? $data['kbli'] : null,
            'kategori' => isset($data['kategori']) ? $data['kategori'] : null,
            'kegiatan_utama' => isset($data['kegiatan_utama']) ? $data['kegiatan_utama'] : null,
            'jaringan_usaha_id' => isset($data['jaringan_usaha_id']) ? $data['jaringan_usaha_id'] : null,
            'bentuk_badan_usaha_id' => isset($data['bentuk_badan_usaha_id']) ? $data['bentuk_badan_usaha_id'] : null,
            'deskripsi_produk_usaha' => isset($data['deskripsi_produk_usaha']) ? $data['deskripsi_produk_usaha'] : null,
            'jenis_kepemilikan_usaha' => isset($data['jenis_kepemilikan_id']) ? $data['jenis_kepemilikan_id'] : null,
            'tahun_berdiri' => isset($data['tahun_berdiri']) ? $data['tahun_berdiri'] : null,
            'keterangan_submitted' => isset($data['keterangan_submitted']) ? $data['keterangan_submitted'] : null,
            'keterangan_approved' => isset($data['keterangan_approved']) ? $data['keterangan_approved'] : null,
            'keterangan_rejected' => isset($data['keterangan_rejected']) ? $data['keterangan_rejected'] : null,
            'status_perusahaan_id' => isset($data['status_perusahaan_id']) ? $data['status_perusahaan_id'] : null,
            'status_form' => isset($data['status_form']) ? $data['status_form'] : null,
            'created_at' => isset($data['created_at']) ? $data['created_at'] : now(),
            'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : now(),
            'sumber_profiling' => isset($data['sumber_profiling']) ? $data['sumber_profiling'] : null,
            'provinsi_pindah' => isset($data['provinsi_pindah']) ? $data['provinsi_pindah'] : null,
            'kabupaten_kota_pindah' => isset($data['kabupaten_kota_pindah']) ? $data['kabupaten_kota_pindah'] : null,
            'idsbr_master' => isset($data['idsbr_master']) ? $data['idsbr_master'] : null,
            'validator' => isset($data['validator']) ? $data['validator'] : null,
            'updated_by' => isset($data['updated_by']) ? $data['updated_by'] : null
        ]);

        $latestRecord = DB::table('matchapro_temporary_update_profiling')
                    ->where('alokasi_profiling_id', $data['alokasi_profiling_id'])
                    ->where('status_form', $data['status_form'])
                    ->orderBy('updated_at', 'desc')
                    ->first();

        return $latestRecord->id;
    }

    public function getHistoryData(Request $request)
    {
        $alokasi_id = $request->alokasi_id;
        $perusahaan_id = $request->perusahaan_id;
        $snapshot_id = DB::table('area_provinsi')->max('snapshot_id');

        $temporary_data = DB::table('matchapro_temporary_update_profiling as mtup')
        ->join('area_provinsi as ap', function ($join) use ($snapshot_id) {
            $join->on('ap.id', '=', 'mtup.provinsi_id')
                ->where('ap.snapshot_id', '=', $snapshot_id);
        })
        ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'mtup.kabupaten_kota_id')
        ->leftjoin('area_kecamatan as ak', 'ak.id', '=', 'mtup.kecamatan_id')
        ->leftjoin('area_kelurahan_desa as akd', 'akd.id', '=', 'mtup.kelurahan_desa_id')
            ->where('alokasi_profiling_id', $alokasi_id)
            ->orderBy('updated_at', 'desc')
            ->select([
                'mtup.*', 
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
            ])->get();

            
            if(count($temporary_data) == 0) {
                // insert data ke table temprary_update_profilng                        
                $usaha = $this->seedingDataTemporaryUpdateProfiling($perusahaan_id, $alokasi_id);
                $idTemporaryUpdateProfiling = $this->insertTemporaryUpdateProfiling($usaha);
                

                    //Get yang baru diinput
                    $temporary_data = DB::table('matchapro_temporary_update_profiling as mtup')
                    ->join('area_provinsi as ap', function ($join) use ($snapshot_id) {
                        $join->on('ap.id', '=', 'mtup.provinsi_id')
                            ->where('ap.snapshot_id', '=', $snapshot_id);
                    })
                    ->join('area_kabupaten_kota as akk', 'akk.id', '=', 'mtup.kabupaten_kota_id')
                    ->leftjoin('area_kecamatan as ak', 'ak.id', '=', 'mtup.kecamatan_id')
                    ->leftjoin('area_kelurahan_desa as akd', 'akd.id', '=', 'mtup.kelurahan_desa_id')
                        ->where('mtup.id', $idTemporaryUpdateProfiling) 
                        ->orderBy('updated_at', 'desc')
                        ->select([
                            'mtup.*', 
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
                        ])->get();
            
                    
            }
        
        return response()->json($temporary_data);  
        
    }

    public function cancelData(Request $request){
        
        $user = auth()->user();
       // Use a database transaction
    DB::beginTransaction();

    try {
        // Update status_form to "CANCELED" in matchapro_alokasi_profiling
        DB::table('matchapro_alokasi_profiling')
            ->where('id', $request->alokasi_id)
            ->where('perusahaan_id', $request->perusahaan_id)
            ->update([
                'status_form' => 'CANCELED',
                'updated_at' => now()
            ]);

        // Select the latest record from matchapro_temporary_update_profiling
        $record = DB::table('matchapro_temporary_update_profiling')
            ->where('alokasi_profiling_id', $request->alokasi_id)
            ->orderBy('updated_at', 'desc')
            ->first();

        // Check if a record was found
        if ($record) {
            // Insert a new row into matchapro_temporary_update_profiling with status_form "CANCELED"
            DB::table('matchapro_temporary_update_profiling')->insert([
                'alokasi_profiling_id' => $record->alokasi_profiling_id,
                'nama_usaha' => $record->nama_usaha,
                'nama_komersial' => $record->nama_komersial,
                'provinsi_id' => $record->provinsi_id,
                'kabupaten_kota_id' => $record->kabupaten_kota_id,
                'kecamatan_id' => $record->kecamatan_id,
                'kelurahan_desa_id' => $record->kelurahan_desa_id,
                'sls_deskripsi' => $record->sls_deskripsi,
                'alamat' => $record->alamat,
                'kodepos' => $record->kodepos,
                'telp' => $record->telp,
                'no_wa' => $record->no_wa,
                'email' => $record->email,
                'website' => $record->website,
                'latitude' => $record->latitude,
                'longitude' => $record->longitude,
                'kbli' => $record->kbli,
                'kategori' => $record->kategori,
                'kegiatan_utama' => $record->kegiatan_utama,
                'jaringan_usaha_id' => $record->jaringan_usaha_id,
                'bentuk_badan_usaha_id' => $record->bentuk_badan_usaha_id,
                'deskripsi_produk_usaha' => $record->deskripsi_produk_usaha,
                'jenis_kepemilikan_usaha' => $record->jenis_kepemilikan_usaha,
                'tahun_berdiri' => $record->tahun_berdiri,
                'keterangan_submitted' => $record->keterangan_submitted,
                'keterangan_approved' => $record->keterangan_approved,
                'keterangan_rejected' => $record->keterangan_rejected,
                'status_perusahaan_id' => $record->status_perusahaan_id,
                'status_form' => 'CANCELED',  // Set status_form to "CANCELED"
                'created_at' => $record->created_at, // Keep original created_at value
                'updated_at' => now(),          // Update updated_at to current timestamp
                'updated_by' => $user->id,
                'sumber_profiling' => $record->sumber_profiling,
                'provinsi_pindah' => $record->provinsi_pindah,
                'kabupaten_kota_pindah' => $record->kabupaten_kota_pindah,
                'idsbr_master' => $record->idsbr_master,
                'validator' => $record->validator,
            ]);
        }

        // Commit the transaction
        DB::commit();
        return response()->json(['message' => 'Data canceled successfully.'], 200);
    } catch (\Exception $e) {
        // Roll back the transaction on failure
        DB::rollBack();
        return response()->json(['message' => 'Failed to cancel data. Error: ' . $e->getMessage()], 500);
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
