<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function notAuthorizedPage(Request $request) {
        $pageConfigs = ['blankPage' => true];
        return view('/matchapro/misc/not-authorized', ['pageConfigs' => $pageConfigs]);
    }
}
