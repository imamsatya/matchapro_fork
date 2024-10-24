<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str; // Import the Str facade
use Illuminate\Support\Facades\Crypt;

use DB;
use Auth;

class FormCreateUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkPermission($permissionName){
        
        if(auth()->user()->getPermissionsViaRoles()->contains('name',$permissionName)){
            return true;    
        }
        return false;
    }

    public function index(Request $request)
    {
        //
        
        if(! ($this->checkPermission('create-new-usaha-provinsi') || $this->checkPermission('create-new-usaha-kabkot')) ){
            abort(403);
        }
        
        $pageConfigs = ['sidebarCollapsed' => false];
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['name' => "Create"]
        ];

        $snapshot_id = DB::table('area_provinsi')->max('snapshot_id');
        
        //Cek User
        $user = auth()->user(); // or User::find($id);
        $role = $user->getRoleNames()->first(); // Get the first role
        $role = Role::findByName($role); // Replace 'role_name' with the actual role
        
        $createNewUsahaProvinsi = false;
        //Provinsi ?
        if($role->hasPermissionTo('create-new-usaha-provinsi'))
        {
            $createNewUsahaProvinsi = true;
            $provinsi = DB::table('area_provinsi')
            ->where('snapshot_id', $snapshot_id)
            ->where('id', $user->provinsi_id)
            ->first(); 
            
            $kabupaten_kota = DB::table('area_kabupaten_kota')
            ->where('provinsi_id', $user->provinsi_id)
            ->get();

            $kecamatan = DB::table('area_kecamatan')
            ->whereIn('kabupaten_kota_id', $kabupaten_kota->pluck('id'))
            ->get();

        }
        
        //Kabupaten ?
        else if ($role->hasPermissionTo('create-new-usaha-kabkot'))
        {
            $provinsi = DB::table('area_provinsi')
            ->where('snapshot_id', $snapshot_id)
            ->where('id', $user->provinsi_id)
            ->first();         

            $kabupaten_kota = DB::table('area_kabupaten_kota')
            ->where('id', $user->kabupaten_kota_id)
            ->first();
           
            $kecamatan = DB::table('area_kecamatan')
            ->where('kabupaten_kota_id', $user->kabupaten_kota_id)
            ->get();

            $kelurahan_desa = DB::table('area_kelurahan_desa')
            ->whereIn('kecamatan_id', $kecamatan->pluck('id'))
            ->get();

        }
        else{

            abort(403);
        };
        
        return view('/matchapro/page/form_create', [
            'breadcrumbs' => $breadcrumbs, 
            'pageConfigs' => $pageConfigs, 
            'request' => $request,
            'provinsi' => $provinsi,
            'kabupaten_kota' => $kabupaten_kota,
            'kecamatan' => $kecamatan,
            'kelurahan_desa' => $kelurahan_desa ?? [],
            'createNewUsahaProvinsi' => $createNewUsahaProvinsi
        ]);

    }

    public function getDataKelurahanDesa(Request $request)
    {
        if(! ($this->checkPermission('create-new-usaha-provinsi') || $this->checkPermission('create-new-usaha-kabkot')) ){
            abort(403);
        }
        
        $kelurahan_desa = DB::table('area_kelurahan_desa')
        ->where('kecamatan_id', $request->kecamatan_id)->get();

        // Return the data as JSON for the AJAX call
        return response()->json($kelurahan_desa);
    }

    public function getDataFulltext(Request $request)
    {   
        if(! ($this->checkPermission('create-new-usaha-provinsi') || $this->checkPermission('create-new-usaha-kabkot')) ){
            abort(403);
        }

        $snapshot_id = DB::table('area_provinsi')->max('snapshot_id');

        // Get the search terms for 'nama' and 'alamat'
        $nama_usaha = $request->input('nama_usaha');
        $alamat = $request->input('alamat');
        $provinsi_id = $request->input('provinsi_id');
        $kabkot_id = $request->input('kabkot_id');
        $kecamatan_id = $request->input('kecamatan_id');
        $kelurahan_desa_id = $request->input('kelurahan_desa_id');
            
            $query = "select FT_RESULT.*, 
                        ap.id as provinsi_id, ap.kode as provinsi_kode, ap.nama as provinsi_nama,
                        akk.id as kabupaten_id, akk.kode as kabupaten_kode, akk.nama as kabupaten_nama,
                        ak.id as kecamatan_id, ak.kode as kecamatan_kode, ak.nama as kecamatan_nama,
                        akd.id as kelurahan_id, akd.kode as kelurahan_kode, akd.nama as kelurahan_nama                        
                        from 
                        (
                            select top 10
                            (  
                                CASE WHEN KEY_TBL.RANK is not null and KEY_TBL2.RANK is not null THEN 3.0 * KEY_TBL.RANK + 1.5 * KEY_TBL2.RANK      
                                else 0  
                                end  
                            ) skor_kalo,  KEY_TBL.RANK rank_nama, KEY_TBL2.RANK rank_alamat, id perusahaan_id ,  nama, alamat, kode idsbr, provinsi_id, kabupaten_kota_id, 
                            kecamatan_id, kelurahan_desa_id
                            from business_perusahaan FT_TBL  
                            full outer JOIN  
                            FREETEXTTABLE (business_perusahaan, nama, '$nama_usaha' ) AS KEY_TBL  
                            ON FT_TBL.id = KEY_TBL.[KEY]  
                            FULL OUTER JOIN  
                            FREETEXTTABLE ( business_perusahaan, alamat, '$alamat' ) AS KEY_TBL2  
                            ON FT_TBL.id = KEY_TBL2.[KEY]  
                            where provinsi_id = '$provinsi_id' 
                            and kabupaten_kota_id = '$kabkot_id'            
                            and (status_perusahaan_id != 9 or status_perusahaan_id is null) 
                            ORDER BY skor_kalo desc
                        ) as FT_RESULT
                        join area_provinsi ap on ap.snapshot_id = $snapshot_id and ap.id = FT_RESULT.provinsi_id
                        JOIN area_kabupaten_kota as akk ON akk.id = FT_RESULT.kabupaten_kota_id
                        LEFT JOIN area_kecamatan as ak ON ak.id = FT_RESULT.kecamatan_id
                        LEFT JOIN area_kelurahan_desa as akd ON akd.id = FT_RESULT.kelurahan_desa_id";
            
            
            // Execute the query
            $results = DB::select($query);
            
            if(intval($results[0]->skor_kalo) == 0){
                $results=[];
            }
            
                        // Return the search results
                        return response()->json($results);  
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
        if(! ($this->checkPermission('create-new-usaha-provinsi') || $this->checkPermission('create-new-usaha-kabkot')) ){
            abort(403);
        }

        
        $timestampInt = time();
        $perusahaanIdgenerated = 'CRT'.time();
            
        //insert ke alokasi
        DB::table('matchapro_alokasi_profiling')->insert([
            'user_id' => Auth::user()->id,
            'perusahaan_id' => $perusahaanIdgenerated,
            'idsbr' => null,
            'status_form' => 'OPEN',
            'periode_id' => env('PERIODE_PROFILING_MANDIRI'),//ID MANDIRI
            'action_type' => 'CREATE',
            'created_at' => now(), // Optional: Add timestamps
            'updated_at' => now(), // Optional: Add timestamps
            'validator'=>null
        ]);

        $id_alokasi = DB::table('matchapro_alokasi_profiling')
        ->where('perusahaan_id', $perusahaanIdgenerated)
        ->where('periode_id', env('PERIODE_PROFILING_MANDIRI'))
        ->where('status_form', 'OPEN')
        ->where('user_id', Auth::user()->id)
        ->first()->id;

        //insert ke temporary
        DB::table('matchapro_temporary_update_profiling')->insert([
            'alokasi_profiling_id' => $id_alokasi,
            'nama_usaha' => $request->nama_usaha,
            'provinsi_id' => $request->provinsi,
            'kabupaten_kota_id' => $request->kabkot,
            'kecamatan_id' =>$request->kecamatan,
            'kelurahan_desa_id' => $request->kelurahan_desa,
            'alamat' => $request->alamat,
            'status_perusahaan_id' => 1, 
            'status_form' => 'OPEN',
            'created_at' => now(), // Optional: Add timestamps
            'updated_at' => now(), // Optional: Add timestamps
            'validator'=>null
        ]);

        $perusahaanIdgenerated = Crypt::encrypt($perusahaanIdgenerated);

         // Return JSON response with redirection URL
        return response()->json([
            'success' => true,
            'redirect_url' => route('form_update_usaha.index', [
                'perusahaan_id' => $perusahaanIdgenerated,
                'alokasi_id' => $id_alokasi
            ])
        ]);
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
