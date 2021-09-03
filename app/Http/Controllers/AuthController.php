<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function login(Request $request){

        try{

            $user = User::where("email", $request->email)->first();
            if($user){

                if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
                    $url = redirect()->intended()->getTargetUrl();
                    return response()->json(["success" => true, "msg" => "Usuario autenticado", "url" => $url]);
                }

            }else{
                return response()->json(["success" => false, "msg" => "Usuario no encontrado"]);
            }

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function logout(){
        Auth::logout();
        return redirect()->to('/');
    }
}
