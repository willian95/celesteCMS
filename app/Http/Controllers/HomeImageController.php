<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeImage;

class HomeImageController extends Controller
{
    function index(){

        return view("homeImages");

    }

    function store(Request $request){

        $homeImage = new HomeImage;
        $homeImage->image = $request->image;
        $homeImage->save();

        return response()->json(["success" => true, "msg" => "Imagen creada"]);

    }

    function delete(Request $request){

        $homeImage = HomeImage::find($request->id);
        $homeImage->delete();

        return response()->json(["success" => true, "msg" => "Imagen eliminada"]);

    }

    function fetch(){

        $homeImages = HomeImage::all();
        return response()->json(["images" => $homeImages]);
        
    }

}
