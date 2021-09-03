<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic;
use Carbon\Carbon;

class ImageController extends Controller
{
    function upload(Request $request){

        $originName = $request->file('file')->getClientOriginalName();
        $extension = $request->file('file')->getClientOriginalExtension();
        $fileName = Carbon::now()->timestamp . '_' . uniqid().'.'.$extension;
    
        $request->file('file')->move(public_path('img'), $fileName);
        $fileRoute = url('/').'/img/'.$fileName;

        $img = ImageManagerStatic::make('img/'.$fileName);
        $img->save('img/'.$fileName, 60);

        return response()->json(["fileRoute" => $fileRoute, "originalName" => $originName,"extension" => $extension]);

    }
}
