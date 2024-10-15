<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Http\Controllers\MasterWilayahController;
use App\Http\Controllers\MasterKBLI;

class FormUpdateUsahaController extends Controller
{

    protected $masterWilayah;
    protected $masterKBLI;

    public function __construct(MasterWilayahController $masterWilayah, MasterKBLI $masterKBLI) {
        $this->masterWilayah = $masterWilayah;
        $this->masterKBLI = $masterKBLI;
    }

    // 'roles' => auth()->user()->getRoleNames(), -- get roles nya user logged in
    // 'permission' => auth()->user()->getPermissionsViaRoles() -- get all permission from user logged in
    // $permission = auth()->user()->getPermissionsViaRoles();
    // dd($permission->contains('name', 'view-progres-profiling-kabkot'));

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($perusahaan_id, $alokasi_id)
    {        
        $init_perusahaan_id = $perusahaan_id;
        $init_alokasi_id = $alokasi_id;
        $perusahaan_id = Crypt::decrypt($perusahaan_id); // di decrypt dulu perusahaan_id nya biar normal lagi gaes

        // cek alokasi profiling
        $alokasiProfiling = DB::table('matchapro_alokasi_profiling')->where('id', $alokasi_id)->first();        
        if(!$alokasiProfiling) {            
            return redirect()->route('error_page.index');            
        }

        
        $status_form = $alokasiProfiling->status_form; // get status form [OPEN, DRAFT, SUBMITTED, APPROVED, REJECTED]
        $action_type = $alokasiProfiling->action_type; // get action type [UPDATE, CREATE]

        // cek periode profilingnya, siapa tau udah ditutup
        if(!$this->isPeriodeProfilingAktif($alokasiProfiling->periode_id)) {
            return redirect()->route('periode_profiling_mandiri_tutup.index'); 
        }
        
        // cek kalau yang coba akses adalah user yang sah / valid
        $profilerYangSah = $alokasiProfiling->perusahaan_id == $perusahaan_id && $alokasiProfiling->user_id == auth()->user()->id;
        $canApprove = auth()->user()->getPermissionsViaRoles()->contains('name','approval-usaha-profiling-user');

        // kalo beda usernya dan bukan user yang bisa melakukan approval 
        // -> tampilkan halaman tidak bisa edit karena sedang diedit orang lain
        if(!$profilerYangSah && !$canApprove) { // you are not that guy ...
            return redirect()->route('lagi_dikerjain_orang_lain.index'); 
        }        
                 
        // get latest update 
        $temporary_data = DB::table('matchapro_temporary_update_profiling')
            ->where('alokasi_profiling_id', $alokasi_id)
            ->orderBy('updated_at', 'desc')
            ->first();

        // - kalau status_form = 'OPEN' dan belum ada row di table tempporary_update_profilng 
        // -> insert data ke tabe temporary_update_profiling
        if(!$temporary_data && $status_form == 'OPEN') {
            // insert data ke table temprary_update_profilng                        
            $usaha = $this->seedingDataTemporaryUpdateProfiling($perusahaan_id, $alokasi_id);
            $idTemporaryUpdateProfiling = $this->insertTemporaryUpdateProfiling($usaha);
            $temporary_data = DB::table('matchapro_temporary_update_profiling')
                ->where('id', $idTemporaryUpdateProfiling)
                ->first();            
        }             

        $temporary_kegiatan = DB::table('matchapro_temporary_update_profiling_kegiatan_usaha')
                ->where('temp_ref', $temporary_data->id)
                ->get();

        
        if($temporary_kegiatan->count()) {
            $temporary_kegiatan = $temporary_kegiatan->map(function($item, $index) {
                $t_kategori = $item->kategori;
                if($t_kategori && $t_kategori != '') {
                    $m_kbli = $this->masterKBLI->getMasterKBLIByKategori($t_kategori);
                    $item->m_kbli = $m_kbli->toArray();
                } else {
                    $item->m_kbli = [];
                }
                return $item;
            });
        }

                
        $dataUsaha = $temporary_data;
        $dataKegiatan = $temporary_kegiatan;
        
        $provinsi_user = auth()->user()->provinsi_id;
        $kabupaten_user = auth()->user()->kabupaten_kota_id;
        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst
        $valid = $this->isValidUser($dataUsaha, $provinsi_user, $kabupaten_user, $level_role_user);        
        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();
    
        $mp = $this->masterWilayah->getMasterProvinsi();
        // $masterProvinsi = $valid ? $mp->filter(function($value) use ($level_role_user, $provinsi_user) {
        //     if($level_role_user !== 'PUSAT') return $value->id == $provinsi_user;
        //     return true;
        // }) : [];
        $masterProvinsi = $mp->filter(function($value) use ($level_role_user, $wilayahAkses) {
            if($level_role_user !== 'PUSAT') return $wilayahAkses->contains('provinsi_id', $value->id);
            return true;
        });
        // $masterKabKot = $valid && $dataUsaha->provinsi_id ? $this->masterWilayah->getMasterKabKot($dataUsaha->provinsi_id) : [];        
        $mk = $dataUsaha->provinsi_id ? $this->masterWilayah->getMasterKabKot($dataUsaha->provinsi_id) : [];
        $masterKabKot = [];
        if($dataUsaha->provinsi_id) {
            $masterKabKot = $mk->filter(function($value) use ($level_role_user, $wilayahAkses) {
                if($level_role_user !== 'PUSAT') return $wilayahAkses->contains('kabupaten_kota_id', $value->id);
                return true;
            });
        }
        // $masterKecamatan = $valid && $dataUsaha->kabupaten_kota_id ? $this->masterWilayah->getMasterKecamatan($dataUsaha->kabupaten_kota_id) : [];        
        $masterKecamatan = $dataUsaha->kabupaten_kota_id ? $this->masterWilayah->getMasterKecamatan($dataUsaha->kabupaten_kota_id) : [];        
        // $masterDesa =  $valid && $dataUsaha->kecamatan_id ? $this->masterWilayah->getMasterDesa($dataUsaha->kecamatan_id) : [];
        $masterDesa =  $dataUsaha->kecamatan_id ? $this->masterWilayah->getMasterDesa($dataUsaha->kecamatan_id) : [];

        // $masterProvinsiAll = $valid ? $mp : [];
        $masterProvinsiAll = $mp;
        // $masterKabupatenAll = $valid && $dataUsaha->provinsi_pindah ? $this->masterWilayah->getMasterKabKot($dataUsaha->provinsi_pindah) : [];
        $masterKabupatenAll = $dataUsaha->provinsi_pindah ? $this->masterWilayah->getMasterKabKot($dataUsaha->provinsi_pindah) : [];

        $masterKategori = $this->masterKBLI->getMasterKategori();
        // $masterKBLI = $dataUsaha->kategori ? $this->masterKBLI->getMasterKBLIByKategori($dataUsaha->kategori) : [];
        $idsbrMaster = $dataUsaha->idsbr_master ? DB::table('business_perusahaan')->where('kode', $dataUsaha->idsbr_master)->first() : null;        
            
        $pageConfigs = ['sidebarCollapsed' => false, 'pageHeader' => false];
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Layouts"], ['name' => "Collapsed menu"]
        ];
        return view('/matchapro/page/form_update', ['breadcrumbs' => $breadcrumbs, 'pageConfigs' => $pageConfigs,             
            'masterProvinsi' => $masterProvinsi,
            'masterKabKot' => $masterKabKot,
            'masterKecamatan' => $masterKecamatan,
            'masterDesa' => $masterDesa,
            'masterKategori' => $masterKategori,
            // 'masterKBLI' => $masterKBLI,
            'usaha' => $dataUsaha,
            'masterProvinsiAll' => $masterProvinsiAll  ,
            'initPerusahaanId' => $init_perusahaan_id,
            'initAlokasiId' => $init_alokasi_id,
            'status_form' => $status_form,
            'action_type' => $action_type,
            'idsbrMaster' => $idsbrMaster,
            'masterKabupatenAll' => $masterKabupatenAll,
            'kegiatan_usaha' => $dataKegiatan
        ]);
    }   
    
    public function checkIDSBR(Request $request) {
        $idsbr = $request->input('idsbr');
        $data = DB::table('business_perusahaan')->where('kode', $idsbr)->first();
        return $data;
    }

    public function isValidUser($dataUsaha, $provinsi_user, $kabupaten_user, $level_role_user) {             
        switch($level_role_user) {                        
            case 'PROVINSI':                            
                if($dataUsaha->provinsi_id != $provinsi_user) return false;
                return true;
                break;
            case 'KABKOT':                    
                if($dataUsaha->provinsi_id != $provinsi_user) return false;
                if($dataUsaha->kabupaten_kota_id != $kabupaten_user) return false;
                return true;
                break;
            default:
                return true;
        }
    }

    public function formUpdateFromDirektoriUsaha($encrypted_perusahaan_id) {
        
        $perusahaan_id = Crypt::decrypt($encrypted_perusahaan_id); // didecrypt dulu perusahaan_id nya biar normal lagi ges

        // step 1. cek perusahaan_id di table alokasi_profiling
        $perusahaanIdInAlokasiProfiling = $this->getPerusahaanIdInAlokasiProfiling($perusahaan_id);        
        
        // pernah ada perusaaan_id di table alokasi profiling?
        $pernahAdaGaYa = $perusahaanIdInAlokasiProfiling->count();
        if($pernahAdaGaYa == 0) { // kalo belum pernah ada samsek di table alokasi profiling        
            return $this->alokasiProfilingMandiri($perusahaan_id, $encrypted_perusahaan_id);            
        } 

        // kalo misalkan pernah ada id_perusahaan di table alokasi_profiling, makaa...
        // get periode profiling yang aktifnya, ada ga yaaa... ?    
        $periodeProfilingActive = $perusahaanIdInAlokasiProfiling->filter(function ($value) {
            return $value->is_periode_aktif == 1;
        })->pluck('periode_id')->unique();        
        
        // kalau ga ada periode profiling yang aktif makaa ...
        if($periodeProfilingActive->count() == 0) {
            return $this->alokasiProfilingMandiri($perusahaan_id, $encrypted_perusahaan_id);
        }

        // kalau ada periode yang aktif, cek kalau misalkan ada periode profiling yang dari pusat (bukan profiling mandiri)
        $periodeProfilingMandiri = env('PERIODE_PROFILING_MANDIRI');
        $periodeProfilingPusat = $periodeProfilingActive->filter(function ($value) use ($periodeProfilingMandiri) {
            return $value !== $periodeProfilingMandiri;
        });
        
        // kalau misalkan adaaaa
        if($periodeProfilingPusat->isNotEmpty()) { // ada periode profilng pusat
            // 1. cek kesesuain user logged in dengan user di table alokasi            
            $profilerYangSah = DB::table('matchapro_alokasi_profiling')->where('periode_id', $periodeProfilingPusat->first())
                ->where('perusahaan_id', $perusahaan_id)
                ->where('user_id', auth()->user()->id)
                ->first();            
            // 2. kalo beda usernya -> tampilkan halaman tidak bisa edit karena sedang diedit orang lain
            if(!$profilerYangSah) { // you are not that guy ...
                return redirect()->route('lagi_dikerjain_orang_lain.index'); 
            }
    
            // 3. kalo sama usernya -> redirect ke halaman form udpate usaha            
            return redirect()->route('form_update_usaha.index', [
                'perusahaan_id' => $encrypted_perusahaan_id,
                'alokasi_id' => $profilerYangSah->id
            ]);

        } else { // periode profiling mandiri
            // 1. cek status form nya ygy ...
            $usahaProfilingMandiri = DB::table('matchapro_alokasi_profiling')->where('periode_id', $periodeProfilingMandiri)
                ->where('perusahaan_id', $perusahaan_id)
                ->get();
            
            $status_form_finish_1 = 'APPROVED';
            $status_form_finish_2 = 'CANCELED';
            $profilingMandiriLagiDikerjain = $usahaProfilingMandiri->filter(function ($value) use ($status_form_finish_1, $status_form_finish_2) {
                return $value->status_form !== $status_form_finish_1 && $value->status_form !== $status_form_finish_2;
            }); 

            if($profilingMandiriLagiDikerjain->isEmpty()) { 
                // kalo tidak ada profilng mandiri yang lagi dikerjain run this code ..
                return $this->alokasiProfilingMandiri($perusahaan_id, $encrypted_perusahaan_id); // lakukan alokasi profiling mandiri                 
            }
            
            // 2. ada status_form yang diluar approved dan cancel (artinya lagi dikerjain) -> 
            // lanjut cek kesesuaian antara user login vs user alokasi
            $profilerYangSah = $profilingMandiriLagiDikerjain->where('user_id', auth()->user()->id)->first();                                
            // kalo beda usernya -> tampilkan halaman tidak bisa edit karena sedang diedit orang lain
            if(!$profilerYangSah) { // you are not that guy ...
                return redirect()->route('lagi_dikerjain_orang_lain.index'); 
            }
            // 3. kalo sama usernya -> redirect ke halaman form udpate usaha            
            return redirect()->route('form_update_usaha.index', [
                'perusahaan_id' => $encrypted_perusahaan_id,
                'alokasi_id' => $profilerYangSah->id
            ]);

        }        
    }    

    
    public function alokasiProfilingMandiri($perusahaan_id, $encrypted_perusahaan_id) {
        $periodeProfilingMandiri = env('PERIODE_PROFILING_MANDIRI');
        // cek dulu ygy siapa tau periode profilnig mandirinya lagi tutup
        if(!$this->isPeriodeProfilingAktif($periodeProfilingMandiri)) {
            // kalo periode profiling mandirinya tutup redirect ke halaman ini ygy
            return redirect()->route('periode_profiling_mandiri_tutup.index'); 
        }

        // kalo periode profilng mandirinya aktif, eksekusi kode dibawah            
        $usaha = DB::table('business_perusahaan')->where('id', $perusahaan_id)->first(); // get idsbr dulu gaes
        // insert alokasi profilingnya dulu biar sah ngerjain proflingnya.. (perusahaan_id, idsbr, tipe, periode_profiling_id)
        $idAlokasiProfiling = $this->insertAlokasiProfiling($perusahaan_id, $usaha->kode, 'UPDATE', $periodeProfilingMandiri);
        return redirect()->route('form_update_usaha.index', [
            'perusahaan_id' => $encrypted_perusahaan_id,
            'alokasi_id' => $idAlokasiProfiling
        ]); // redirect ke route ini untuk menampilkan form update usaha ygy
    }  

    public function isPeriodeProfilingAktif($periode) {
        $periode_profiling = DB::table('matchapro_periode_profiling')->where('id', $periode)->first();
        return $periode_profiling && $periode_profiling->is_active == 1;
    }

    public function getPerusahaanIdInAlokasiProfiling($perusahaan_id) {
        $alokasi = DB::table('matchapro_alokasi_profiling as aa')
                    ->select('aa.id as alokasi_id', 'aa.user_id', 'aa.perusahaan_id', 'aa.idsbr', 'aa.status_form', 
                        'pp.id  as periode_id', 'pp.is_active as is_periode_aktif')                    
                    ->join('matchapro_periode_profiling as pp', 'pp.id', '=', 'aa.periode_id')
                    ->where('aa.perusahaan_id', $perusahaan_id);                    
        return $alokasi->get();
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
                    ->get();

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
        // $kbli = $kegiatan ? $kegiatan->kbli : null;
        // $kategori = $kegiatan ? $kegiatan->kategori : null;
        // $kegiatan_utama = $kegiatan ? $kegiatan->aktivitas : null;
        $kegiatan_usaha = $kegiatan->map(function($item) {
            return [
                'kbli' => $item->kbli,
                'kategori' => $item->kategori,
                'kegiatan_usaha' => $item->aktivitas,
                'produk_usaha' => null                
            ];
        })->toArray();
        $jaringan_usaha_id = null; // sudah sesuai -> tidak perlu transformasi
        $bentuk_badan_usaha_id = $this->bhbuTransform($usaha->jenis_badan_hukum_badan_usaha_id);
        // $deskripsi_produk_usaha = $produk ? $produk->produk : null;
        $deskripsi_produk_usaha = null;
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
        'kegiatan_usaha', 'jaringan_usaha_id','bentuk_badan_usaha_id','deskripsi_produk_usaha',
        'jenis_kepemilikan_id','tahun_berdiri','keterangan_submitted','keterangan_approved','keterangan_rejected',
        'status_perusahaan_id','status_form','created_at','updated_at');
    }

    public function periodeProfilingMandiriTutup(Request $request) {        
        return view('/matchapro/page/periode_profiling_mandiri_tutup');
    }

    public function lagiDikerjainOrangLain(Request $request) {
        return view('/matchapro/page/lagi_dikerjain_orang_lain');
    }

    public function errorPage(Request $request) {
        return view('/matchapro/page/halaman_error');
    }

    public function insertAlokasiProfiling($perusahaan_id, $idsbr, $tipe, $periode_profiling) {                
        DB::table('matchapro_alokasi_profiling')->insert([
            'user_id' => auth()->user()->id,
            'perusahaan_id' => $perusahaan_id,
            'idsbr' => $idsbr,
            'status_form' => 'OPEN',
            'periode_id' => $periode_profiling,
            'catatan' => null,
            'action_type' => $tipe,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $latestRecord= DB::table('matchapro_alokasi_profiling')->where('user_id', auth()->user()->id)
                    ->where('perusahaan_id', $perusahaan_id)
                    ->where('status_form', 'OPEN')
                    ->where('periode_id', $periode_profiling)
                    ->orderBy('created_at', 'desc')
                    ->first();
        return $latestRecord->id;
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

        $kegiatan_usaha = isset($data['kegiatan_usaha']) ? $data['kegiatan_usaha'] : [];
        $temp_kegiatan = [];
        foreach($kegiatan_usaha as $kegiatan) {
            $temp_kegiatan[] = [
                'kegiatan_usaha' => isset($kegiatan['kegiatan_usaha']) ? $kegiatan['kegiatan_usaha'] : null,
                'kbli' => isset($kegiatan['kbli']) ? $kegiatan['kbli'] : null,
                'kategori' => isset($kegiatan['kategori']) ? $kegiatan['kategori'] : null,
                'produk_usaha' => isset($kegiatan['produk_usaha']) ? $kegiatan['produk_usaha'] : null,
                'temp_ref' => $latestRecord->id
            ];

        }

        // insert kegiatan usaha
        DB::table('matchapro_temporary_update_profiling_kegiatan_usaha')->insert($temp_kegiatan);

        return $latestRecord->id;
    }

    public function save(Request $request, $perusahaan_id, $alokasi_id) {

        try {            
            $perusahaan_id = Crypt::decrypt($perusahaan_id);            

            // cek alokasi profiling
            $alokasiProfiling = DB::table('matchapro_alokasi_profiling')->where('id', $alokasi_id)->first();        
            if(!$alokasiProfiling) { // alokasi profiling tidak ditemukan
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data profiling tidak ditemukan!'
                ], 404);
            }

            // cek periode profiling
            if(!$this->isPeriodeProfilingAktif($alokasiProfiling->periode_id)) { // periode nya udah tutup
                return response()->json([
                    'status' => 'error',
                    'message' => 'Periode profiling sudah ditutup!'
                ], 400);            
            }                        

            // cek status form nya ['OPEN', 'DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED', 'CANCELED']
            $status_form = $request->input('status_form') ?? null;
            $allowed_status_form = ['OPEN', 'DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED', 'CANCELED'];
            if(!$status_form || !in_array($status_form, $allowed_status_form)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terdapat kesalahan pada form profiling!'
                ], 400);  
            }
                                
            
            // current status OPEN, DRAFT, REJECTED -> upcoming status DRAFT, SUBMITTED -> OK else ERROR
            if(in_array($alokasiProfiling->status_form, ['OPEN', 'DRAFT', 'REJECTED']) &&
                !in_array($status_form, ['DRAFT', 'SUBMITTED'])
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terdapat kesalahan pada form profiling!'
                ], 400);
            }            
            
            // current status SUBMITTED -> upcoming status APPROVED, REJECTED, CANCELED -> OK else ERROR
            if(in_array($alokasiProfiling->status_form, ['SUBMITTED']) &&
                !in_array($status_form, ['APPROVED', 'REJECTED', 'CANCELED'])
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terdapat kesalahan pada form profiling!'
                ], 400);
            }

            // current status APPROVED -> upcoming status anything -> ERROR
            if(in_array($alokasiProfiling->status_form, ['APPROVED'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terdapat kesalahan pada form profiling!'
                ], 400);
            }

            // cek apakah yang mau edit orang yang sah
            $profilerYangSah = $alokasiProfiling->perusahaan_id == $perusahaan_id && $alokasiProfiling->user_id == auth()->user()->id;            
            
            // upcoming status DRAFT, SUBMITTED, CANCELED
            // yang bisa edit cuman user ybs
            if(in_array($status_form, ['DRAFT', 'SUBMITTED', 'CANCELED']) && !$profilerYangSah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak memiliki hak akses untuk melakukan profiling pada data ini!'
                ], 403);  
            }

            // upcoming status APPROVED, REJECTED
            // yang bisa approved dan rejected hanya role ADMIN
            $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
            $canApprove = auth()->user()->getPermissionsViaRoles()->contains('name','approval-usaha-profiling-user');
            if(in_array($status_form, ['APPROVED', 'REJECTED']) && 
                !$canApprove) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak memiliki hak akses untuk melakukan profiling pada data ini!'
                ], 403); 
            }     

            // check if action type is CREATE
            if($alokasiProfiling->action_type == 'CREATE') {                
                $initialCreatedData = DB::table('matchapro_temporary_update_profiling')
                    ->where('alokasi_profiling_id', $alokasi_id)
                    ->where('status_form', 'OPEN')                    
                    ->first();
                
                if(!$initialCreatedData) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terdapat kesalahan pada form profiling!'
                    ], 400);
                }

                if($request->input('status_perusahaan') != '1') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terdapat kesalahan pada form profiling!'
                    ], 400);
                }

                if($request->input('nama') != $initialCreatedData->nama_usaha) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terdapat kesalahan pada form profiling!'
                    ], 400);
                }

                if($request->input('alamat') != $initialCreatedData->alamat) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terdapat kesalahan pada form profiling!'
                    ], 400);
                }

                if($request->input('provinsi') != $initialCreatedData->provinsi_id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terdapat kesalahan pada form profiling!'
                    ], 400);
                }

                if($request->input('kabupaten_kota') != $initialCreatedData->kabupaten_kota_id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terdapat kesalahan pada form profiling!'
                    ], 400);
                }                    
            }    


            // initialize data to be inserted
            $editedAt = now();
            $data = [
                'alokasi_profiling_id' => $alokasi_id,
                'nama_usaha' => $request->input('nama') ?? null,
                'nama_komersial' => $request->input('nama_komersial') ?? null,
                'provinsi_id' => $request->input('provinsi') ?? null,
                'kabupaten_kota_id' => $request->input('kabupaten_kota') ?? null,
                'kecamatan_id' => $request->input('kecamatan') ?? null,
                'kelurahan_desa_id' => $request->input('kelurahan_desa') ?? null,
                'sls_deskripsi' => $request->input('sls') ?? null,
                'alamat' => $request->input('alamat') ?? null,
                'kodepos' => $request->input('kodepos') ?? null,
                'telp' => $request->input('telepon') ?? null,
                'no_wa' => $request->input('whatsapp') ?? null,
                'email' => $request->input('isEmailCheck') ? ($request->input('email') ?? null) : null,
                'website' => $request->input('website') ?? null,
                'latitude' => $request->input('latitude') ?? null,
                'longitude' => $request->input('longitude') ?? null,
                'kbli' => $request->input('kbli') ?? null,
                'kategori' => $request->input('kategori') ?? null,
                'kegiatan_utama' => $request->input('kegiatan_utama') ?? null,
                'jaringan_usaha_id' => $request->input('jaringan_usaha') ?? null,
                'bentuk_badan_usaha_id' => $request->input('badan_usaha') ?? null,
                'deskripsi_produk_usaha' => $request->input('produk_utama') ?? null,
                'jenis_kepemilikan_id' => $request->input('jenis_kepemilikan_usaha') ?? null,
                'tahun_berdiri' => $request->input('tahun_berdiri') ?? null,
                'keterangan_submitted' => $request->input('catatan_profiling') ?? null,
                'keterangan_approved' => $request->input('catatan_approved') ?? null,
                'keterangan_rejected' => $request->input('catatan_rejected') ?? null,
                'status_perusahaan_id' => $request->input('status_perusahaan') ?? null,
                'status_form' => $status_form,
                'created_at' => $editedAt,
                'updated_at' => $editedAt,
                'sumber_profiling' => $request->input('sumber_profiling') ?? null,
                'provinsi_pindah' => $request->input('status_perusahaan') && $request->input('status_perusahaan') == '7' ? ($request->input('provinsi_pindah') ?? null) : null,
                'kabupaten_kota_pindah' => $request->input('status_perusahaan') && $request->input('status_perusahaan') == '7' ? ($request->input('kabupaten_kota_pindah') ?? null) : null,
                'idsbr_master' => $request->input('status_perusahaan') && $request->input('status_perusahaan') == '9' ? ($request->input('idsbr_master') ?? null) : null,
                // kalo ini save biasa, let it null, but if it is an approval process let us store the user id
                'validator' => in_array($status_form, ['APPROVED', 'REJECTED']) ? auth()->user()->id : null,
                'updated_by' => auth()->user()->id,
                'kegiatan_usaha' => $request->input('kegiatan_usaha') ?? null
            ];
            
            // if status form APPROVED, REJECTED , CANCELED -: 
            // get latest updated data and insert as new row in temp table
            if(in_array($status_form, ['APPROVED', 'REJECTED', 'CANCELED'])) {
                $latestUpdate = DB::table('matchapro_temporary_update_profiling')
                    ->where('alokasi_profiling_id', $alokasi_id)
                    ->orderBy('updated_at', 'desc')
                    ->first();

                $latestKegiatanUsaha = DB::table('matchapro_temporary_update_profiling_kegiatan_usaha')
                    ->where('temp_ref', $latestUpdate->id)
                    ->get();
                
                $data['nama_usaha'] = $latestUpdate->nama_usaha;
                $data['nama_komersial'] = $latestUpdate->nama_komersial;
                $data['provinsi_id'] = $latestUpdate->provinsi_id;
                $data['kabupaten_kota_id'] = $latestUpdate->kabupaten_kota_id;
                $data['kecamatan_id'] = $latestUpdate->kecamatan_id;
                $data['kelurahan_desa_id'] = $latestUpdate->kelurahan_desa_id;
                $data['sls_deskripsi'] = $latestUpdate->sls_deskripsi;
                $data['alamat'] = $latestUpdate->alamat;
                $data['kodepos'] = $latestUpdate->kodepos;
                $data['telp'] = $latestUpdate->telp;
                $data['no_wa'] = $latestUpdate->no_wa;
                $data['email'] = $latestUpdate->email;
                $data['website'] = $latestUpdate->website;
                $data['latitude'] = $latestUpdate->latitude;
                $data['longitude'] = $latestUpdate->longitude;
                $data['kbli'] = $latestUpdate->kbli;
                $data['kategori'] = $latestUpdate->kategori;
                $data['kegiatan_utama'] = $latestUpdate->kegiatan_utama;
                $data['jaringan_usaha_id'] = $latestUpdate->jaringan_usaha_id;
                $data['bentuk_badan_usaha_id'] = $latestUpdate->bentuk_badan_usaha_id;
                $data['deskripsi_produk_usaha'] = $latestUpdate->deskripsi_produk_usaha;
                $data['jenis_kepemilikan_id'] = $latestUpdate->jenis_kepemilikan_usaha;
                $data['tahun_berdiri'] = $latestUpdate->tahun_berdiri;
                $data['keterangan_submitted'] = $latestUpdate->keterangan_submitted;
                $data['keterangan_approved'] = $latestUpdate->keterangan_approved;
                $data['keterangan_rejected'] = $latestUpdate->keterangan_rejected;
                $data['status_perusahaan_id'] = $latestUpdate->status_perusahaan_id;                
                $data['status_form'] = $status_form == 'CANCELED' ? 'DRAFT' : $status_form;
                $data['sumber_profiling'] = $latestUpdate->sumber_profiling;
                $data['provinsi_pindah'] = $latestUpdate->provinsi_pindah;
                $data['kabupaten_kota_pindah'] = $latestUpdate->kabupaten_kota_pindah;
                $data['idsbr_master'] = $latestUpdate->idsbr_master;                
                $data['kegiatan_usaha'] = $latestKegiatanUsaha->toArray();
            }

            // inserting data ....
            $idTemporaryUpdateProfiling = $this->insertTemporaryUpdateProfiling($data);

            $catatan = '';
            if(in_array($status_form, ['OPEN', 'DRAFT', 'SUBMITTED', 'CANCELED'])) $data['keterangan_submitted'];
            if(in_array($status_form, ['APPROVED'])) $data['keterangan_approved'];
            if(in_array($status_form, ['REJECTED'])) $data['keterangan_rejected'];

            // update status
            $updated = DB::table('matchapro_alokasi_profiling')
                ->where('id', $alokasi_id)
                ->update([
                    'status_form' => $status_form == 'CANCELED' ? 'DRAFT' : $status_form,
                    'catatan' => $catatan,
                    'updated_at' => $editedAt,
                    'validator' => in_array($status_form, ['APPROVED', 'REJECTED']) ? auth()->user()->id : null                 
                ]);

            
            $messages = [
                'DRAFT' => 'Berhasil menyimpan draft data!',
                'SUBMITTED' => 'Berhasil submit data final!',
                'APPROVED' => 'Berhasil melakukan approval data: APPROVED!',
                'REJECTED' => 'Berhasil melakukan approval data: REJECTED!',
                'CANCELED' => 'Berhasil membatalkan submit data!'
            ]; 
            $result = [
                'status' => 'success',
                'message' => $messages[$status_form],
                'status_form' => $status_form,
                'allowed_edit' => $profilerYangSah
            ];
            return response()->json($result, 200);

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later!'
            ], 500);
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
