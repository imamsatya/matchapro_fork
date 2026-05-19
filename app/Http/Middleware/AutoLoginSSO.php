<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use DB;


class AutoLoginSSO
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }

        try {

            $provider = new \JKD\SSO\Client\Provider\Keycloak([
                'authServerUrl'         => env('SSO_AUTH_SERVER_URL'),
                'realm'                 => env('SSO_REALM'),
                'clientId'              => env('SSO_CLIENT_ID'),
                'clientSecret'          => env('SSO_CLIENT_SECRET'),
                // 'redirectUri'           => route('login')
            ]);

            $token = session('sso_token');
            if ($token && !$token->hasExpired()) {
                $userSSO = $provider->getResourceOwner($token);
                $userDB = User::where('username', $userSSO->getUsername())->first();

                if ($userDB) {
                    // cek apakah masih aktif
                    if ($userDB->is_active == 0) {
                        return redirect()->route('login')->withErrors(['username' => 'User not active.']);
                    }

                    // cek apakah user sudah di soft-delete
                    if ($userDB->is_delete == 1) {
                        return redirect()->route('login')->withErrors(['username' => 'User deleted.']);
                    }

                    $satkerUser = DB::table('area_provinsi as ap')
                        ->select($userDB->kabupaten_kota_id ? DB::raw('concat(ap.kode, akk.kode) as idkab') : DB::raw('concat(ap.kode, \'00\') as idkab'))
                        ->leftJoin('area_kabupaten_kota as akk', 'ap.id', '=', 'akk.provinsi_id')
                        ->where('ap.snapshot_id', '=', 4)
                        ->where('ap.id', $userDB->provinsi_id)
                        ->when($userDB->kabupaten_kota_id, function ($query) use ($userDB) {
                            $query->where('akk.id', $userDB->kabupaten_kota_id);
                        })
                        ->first();

                    $satkerUserKode = $satkerUser ? $satkerUser->idkab : '0000';

                    $kodeProvinsiSSO = $userSSO->getKodeProvinsi();
                    $kodeKabupatenSSO = $userSSO->getKodeKabupaten();
                    $satkerSSO = $kodeProvinsiSSO . "" . $kodeKabupatenSSO;

                    // cek kesesuaian satker
                    if ($satkerUserKode != $satkerSSO) {
                        $provinsi = DB::table('area_provinsi')
                            ->where('snapshot_id', 4)
                            ->where('kode', $kodeProvinsiSSO)
                            ->first();

                        if ($provinsi) {
                            $userDB->provinsi_id = $provinsi->id;

                            // 2. Jika kode kabupaten SSO bukan '00' (artinya tingkat Kab/Kota)
                            if ($kodeKabupatenSSO && $kodeKabupatenSSO != '00') {
                                $kabupaten = DB::table('area_kabupaten_kota')
                                    ->where('provinsi_id', $provinsi->id)
                                    ->where('kode', $kodeKabupatenSSO)
                                    ->first();

                                $userDB->kabupaten_kota_id = $kabupaten ? $kabupaten->id : null;
                            } else {
                                // Jika '00', set null karena level provinsi saja
                                $userDB->kabupaten_kota_id = null;
                            }

                            // 3. Simpan perubahan ke database
                            $userDB->save();
                        }
                    }

                    Auth::login($userDB);
                    return $next($request);
                }
                return redirect()->route('login')->withErrors(['username' => 'User not found in the database.']);
            }
        } catch (Exception $e) {
            return redirect()->route('login');
        }

        return redirect()->route('login');
    }
}
