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
        $roles = DB::table('matchapro_roles')->get();
        $m_provinsi = $this->masterWilayah->getMasterProvinsi();
        $pageConfigs = ['sidebarCollapsed' => false, 'pageHeader' => false];                
        return view('/matchapro/page/user/list', ['pageConfigs' => $pageConfigs, 'roles' => $roles,
        'm_provinsi' => $m_provinsi
    ]);
    }

    public function getData(Request $request)
    {
        $users = DB::table('matchapro_users')
            ->leftJoin('area_provinsi as ap', 'ap.id', '=', 'matchapro_users.provinsi_id')
            ->leftJoin('area_kabupaten_kota as akk', 'akk.id', '=', 'matchapro_users.kabupaten_kota_id')
            ->leftJoin('matchapro_model_has_roles as mhr', 'mhr.model_id', '=', 'matchapro_users.id')
            ->leftJoin('matchapro_roles as mr', 'mr.id', '=', 'mhr.role_id')
            ->select('matchapro_users.*', 'ap.nama as provinsi', 'akk.nama as kabupaten', 'ap.kode as kode_provinsi', 
            'akk.kode as kode_kabupaten', 'mhr.role_id', 'mr.name as role');

        if($request->filled('name')) {
            $users->where(function($query) use ($request) {
                $query->whereRaw('matchapro_users.nama LIKE ?', ['%' . $request->name . '%'])
                    ->orWhereRaw('matchapro_users.username LIKE ?', ['%' . $request->name . '%']);
            });
        }

        if($request->filled('satuan_kerja')) {
            $users->where(function($query) use ($request) {
                $query->whereRaw('ap.nama LIKE ?', ['%' . $request->satuan_kerja . '%'])
                    ->orWhereRaw('akk.nama LIKE ?', ['%' . $request->satuan_kerja . '%']);
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
        
        $recordsTotal = $users->count();
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


}
