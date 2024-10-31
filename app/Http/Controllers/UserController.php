<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MasterWilayahController;
use DB;

class UserController extends Controller
{

    protected $masterWilayah;    

    public function __construct(MasterWilayahController $masterWilayah) {
        $this->masterWilayah = $masterWilayah;        
    }

    public function index()
    {
        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }

        $canViewUserListPage = auth()->user()->getPermissionsViaRoles()->contains('name', 'view-user-list');
        if(!$canViewUserListPage) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }            

        $roles = DB::table('matchapro_roles')->get();
        $m_provinsi = $this->masterWilayah->getMasterProvinsi();
        $pageConfigs = ['sidebarCollapsed' => false, 'pageHeader' => false];                

        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst

        if($level_role_user != 'PUSAT') {
            return view('/matchapro/page/user/list-daerah', ['pageConfigs' => $pageConfigs, 'roles' => [
                (object) ['id' => 14, 'name' => 'VIEWER'],
                (object) ['id' => 15, 'name' => 'PROFILER'],
            ],
                'm_provinsi' => $this->masterWilayah->getMasterProvinsiUser()
            ]);
        }

        return view('/matchapro/page/user/list', ['pageConfigs' => $pageConfigs, 'roles' => $roles,
            'm_provinsi' => $m_provinsi
        ]);
    }

    public function getData(Request $request)
    {        
        $roleUser = auth()->user()->getRoleNames();
        // belum memiliki roles
        if(!$roleUser->count()) {
            $pageConfigs = ['blankPage' => true];
            return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
        }

        $role_user = auth()->user()->getRoleNames()[0]; // PUSAT-ADMIN, dst
        $level_role_user = explode('-' , $role_user)[0]; // PUSAT, dst
        $wilayahAkses = DB::table('matchapro_users_wilayah_akses')->where('user_id', auth()->user()->id)->get();
        $provinsiAkses = $wilayahAkses->pluck('provinsi_id')->unique()->toArray();
        $kabupatenAkses= $wilayahAkses->pluck('kabupaten_kota_id')->unique()->toArray();
            
        $users = DB::table('matchapro_users')
            ->leftJoin('area_provinsi as ap', 'ap.id', '=', 'matchapro_users.provinsi_id')
            ->leftJoin('area_kabupaten_kota as akk', 'akk.id', '=', 'matchapro_users.kabupaten_kota_id')
            ->leftJoin('matchapro_model_has_roles as mhr', 'mhr.model_id', '=', 'matchapro_users.id')
            ->leftJoin('matchapro_roles as mr', 'mr.id', '=', 'mhr.role_id')            
            ->leftJoin('matchapro_users_wilayah_akses as ww', 'ww.user_id', '=', 'matchapro_users.id')
            ->select('matchapro_users.id', 'matchapro_users.username', 'matchapro_users.nama',
            'matchapro_users.nomor_whatsapp', 'matchapro_users.provinsi_id', 
            'matchapro_users.kabupaten_kota_id', 'matchapro_users.is_active', 
            'matchapro_users.photo_url',
            'ap.nama as provinsi', 'akk.nama as kabupaten', 'ap.kode as kode_provinsi', 
            'akk.kode as kode_kabupaten', 'mhr.role_id', 'mr.name as role',
            DB::raw('STUFF((SELECT \',\' + CAST(ww2.kabupaten_kota_id AS VARCHAR)
                             FROM matchapro_users_wilayah_akses ww2 
                             WHERE ww2.user_id = matchapro_users.id 
                             FOR XML PATH(\'\')), 1, 1, \'\') as akses_wilayah')
        );   
        
        
        if($level_role_user != 'PUSAT') {                        
            $users->where(function($query) use ($kabupatenAkses) {
                $query->whereIn('ww.kabupaten_kota_id', $kabupatenAkses)
                    ->orWhere('matchapro_users.created_by', auth()->user()->id);                      
            });            
        }         

        if($request->filled('name')) {
            $users->where(function($query) use ($request) {
                $query->whereRaw('matchapro_users.nama LIKE ?', ['%' . $request->name . '%'])
                    ->orWhereRaw('matchapro_users.username LIKE ?', ['%' . $request->name . '%']);
            });
        }

        if($request->filled('satuan_kerja')) {
            $users->where(function($query) use ($request) {
                $query->whereRaw('ap.nama LIKE ?', ['%' . $request->satuan_kerja . '%'])
                    ->orWhereRaw('akk.nama LIKE ?', ['%' . $request->satuan_kerja . '%'])
                    ->orWhereRaw('ap.kode LIKE ?', ['%' . $request->satuan_kerja . '%'])
                    ->orWhereRaw('akk.kode LIKE ?', ['%' . $request->satuan_kerja . '%'])
                    ->orWhereRaw('concat(ap.kode, akk.kode) like ?', ['%' . $request->satuan_kerja . '%']);
            });
        }

        if($request->filled('role')) {
            if($request->role == 'norole') {
                $users->whereNull('mhr.role_id');
            }
            
            if($request->role != '-' && $request->role != 'norole') {
                $users->where('mhr.role_id', $request->role);
            }
        }
        
        if($request->filled('is_active')) {
            if($request->is_active != '-') {
                $users->where('matchapro_users.is_active', $request->is_active);
            }
        }  
        
        if($request->filled('assign_wilayah')) {
            // filter yang sudah diassign wilayahnya            
            if($request->assign_wilayah == '1') {
                $users->whereNotNull('ww.id_table');
            }
            if($request->assign_wilayah == '2') {
                $users->whereNull('ww.id_table');
            }
        }

        // user yang tidak pernah di soft-delete
        $users->where('is_delete', 0);        

        $users->groupBy('matchapro_users.id', 'matchapro_users.username', 'matchapro_users.nama',
            'matchapro_users.nomor_whatsapp', 'matchapro_users.provinsi_id', 
            'matchapro_users.kabupaten_kota_id', 'matchapro_users.is_active', 
            'matchapro_users.photo_url',
            'ap.nama', 'akk.nama', 'ap.kode', 
            'akk.kode', 'mhr.role_id', 'mr.name');

        $users->orderBy('matchapro_users.id', 'desc');
        
        $recordsTotal = $users->get()->count();        
        $start = $request->input('start');
        $length = $request->input('length'); 
        $users= $users->offset($start)->limit($length)->get();

    
        $data = [];
        foreach($users as $user) {
            $kode_provinsi = $user->kode_provinsi ? $user->kode_provinsi : '00';
            $kode_kabupaten = $user->kode_kabupaten ? $user->kode_kabupaten : '00';
            $temp['id'] = $user->id;
            $temp['username'] = $user->username;
            $temp['name'] = $user->nama;
            $temp['wa'] = $user->nomor_whatsapp;
            $temp['provinsi'] = $user->provinsi_id;
            $temp['kabupaten'] = $user->kabupaten_kota_id;    
            $temp['kode_provinsi'] = $kode_provinsi;
            $temp['kode_kabupaten'] = $kode_kabupaten;
            $temp['nama_provinsi'] = $user->provinsi;
            $temp['nama_kabupaten'] = $user->kabupaten;
            $temp['is_active'] = $user->is_active;
            $temp['role'] = $user->role;    
            $temp['satuan_kerja'] = $kode_provinsi . $kode_kabupaten;
            $temp['actions'] = '';
            $temp['photo'] = $user->photo_url;
            $temp['akses_wilayah'] = $user->akses_wilayah ? explode(',', $user->akses_wilayah) : [];
            $data[] = $temp;
        }
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    public function getDetailUser(Request $request) {
        $userId = $request->user;
        $user = DB::table('matchapro_users')->where('id', $userId)->first();        
        $m_kabupaten = $this->masterWilayah->getKabupatenByProvinsi($user->provinsi_id);
        $model_has_roles = DB::table('matchapro_model_has_roles')->where('model_id', $userId)->first();        
        $role = null;
        if($model_has_roles) { $role = $model_has_roles->role_id; }
        return ['user' => $user, 'm_kabupaten' => $m_kabupaten, 'role' => $role];
    }

    public function updateUser(Request $request) {
        DB::table('matchapro_users')->where('id', $request->user)->update([
            'nama' => $request->data['name'],
            'username' => $request->data['username'],
            'provinsi_id' => $request->data['provinsi_id'],
            'kabupaten_kota_id' => $request->data['kabupaten_kota_id'],
            'is_active' => $request->data['is_active'],
            'nomor_whatsapp' => $request->data['whatsapp'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if(isset($request->data['role_id'])) {
            $isUpdated = DB::table('matchapro_model_has_roles')->where('model_id', $request->user)->update([
                'role_id' => $request->data['role_id'],
            ]);
    
            if(!$isUpdated) {
                DB::table('matchapro_model_has_roles')->insert([
                    'model_id' => $request->user,
                    'role_id' => $request->data['role_id'],
                    'model_type' => 'App\Models\User',
                ]);
            }
        }        

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function updateUserWilayah(Request $request) {

        // check user role
        $role = DB::table('matchapro_model_has_roles')
        ->join('matchapro_roles', 'matchapro_roles.id', '=', 'matchapro_model_has_roles.role_id')
        ->where('model_id', $request->user)
        ->first();        

        if(!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak bisa memperbarui wilayah akses. User tidak memiliki role pada sistem!',
            ], 400);
        }

        $roleName = explode('-', $role->name)[0];
        if($roleName == 'PUSAT') {
            return response()->json([
                'status' => 'error',
                'message' => 'User dengan role PUSAT tidak perlu mengisi wilayah [AUTO ACCESS ALL WILAYAH]',
            ], 400);
        }

        // kalo yang coba mengupdate bukan admin pusat throw error [belum dikerjain]

        $wilayahAksesUser = $request->data ?? [];
        DB::table('matchapro_users_wilayah_akses')->where('user_id', $request->user)->delete();        
        foreach($wilayahAksesUser as $wilayah) {
            foreach($wilayah['kabupaten'] as $kabupaten) {
                DB::table('matchapro_users_wilayah_akses')->insert([
                    'user_id' => $request->user,
                    'provinsi_id' => $wilayah['provinsi'],
                    'kabupaten_kota_id' => $kabupaten['value']
                ]);
            }            
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil memperbarui wilayah akses',
        ], 200);        
    }

    public function getWilayahAksesUser(Request $request) {
        $wilayahAksesUser = DB::table('matchapro_users_wilayah_akses')
                ->select('area_provinsi.nama as nmprovinsi', 'area_provinsi.kode as kdprovinsi', 
                    'area_kabupaten_kota.nama as nmkab', 'area_kabupaten_kota.kode as kdkab',
                    'area_provinsi.id as provinsi_id', 'area_kabupaten_kota.id as kabupaten_kota_id'
                    )
                ->join('area_provinsi', 'area_provinsi.id', '=', 'matchapro_users_wilayah_akses.provinsi_id')
                ->join('area_kabupaten_kota', 'area_kabupaten_kota.id', '=', 'matchapro_users_wilayah_akses.kabupaten_kota_id')
                ->where('user_id', $request->user)
                ->get();
        return $wilayahAksesUser;
    }

    // soft delete user
    public function deleteUser(Request $request) {
        $user = $request->user;
        $update = DB::table('matchapro_users')->where('id', $user)->update([
            'is_delete' => 1,
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menghapus user'
        ], 200);
        
    }

    public function addUser(Request $request) {

        // cek if username already exist in database
        $usernameExists = DB::table('matchapro_users')->where('username', $request->data['username'])->first();

        if($usernameExists) {
            // return error
            return response()->json([
                'status' => 'error',
                'message' => 'Username sudah terdaftar di database. Silahkan hubungi tim sbr!'
            ], 400);   
        }

        $userId = DB::table('matchapro_users')->insertGetId([
            'nama' => $request->data['name'],
            'username' => $request->data['username'],
            'provinsi_id' => $request->data['provinsi_id'],
            'kabupaten_kota_id' => $request->data['kabupaten_kota_id'],
            'is_active' => 1,
            'nomor_whatsapp' => $request->data['whatsapp'],
            'created_at' =>date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'password_default' => bcrypt(env('PW_DEF')),
            'created_by' => auth()->user()->id
        ]);
        
        if($request->data['role_id']) {
            DB::table('matchapro_model_has_roles')->insert([
                'model_id' => $userId,
                'role_id' => $request->data['role_id'],
                'model_type' => 'App\Models\User',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
        ]);
    }

    public function faqPage(Request $request) {
        $pageConfigs = ['sidebarCollapsed' => false, 'pageHeader' => false];                
        return view('/matchapro/page/faq', ['pageConfigs' => $pageConfigs
        ]);
    }


}
