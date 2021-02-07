<?php


namespace App\Http\Controllers;


use App\Http\Requests\Request;
use Illuminate\Support\Facades\Config;

class UpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $token = $request->get('token');
        if ($token !== Config::get('updater.token')) {
            abort(401);
        }



    }
}
