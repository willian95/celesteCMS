<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use Intervention\Image\Facades\Image;
use App\Models\Project;
use App\Models\SecondaryImage;

class ProductController extends Controller
{
    
    function create(){

        return view("products.create");

    }

    function store(ProjectStoreRequest $request){

        try{

            $slug = str_replace(" ","-", $request->name);
            $slug = str_replace("/", "-", $slug);

            if(Project::where("slug", $slug)->count() > 0){
                $slug = $slug."-".uniqid();
            }

            $project = new Project;
            $project->name = $request->name;
            $project->description = $request->description;
            $project->main_image = $request->image;
            $project->slug = $slug;
            $project->location = $request->location;
            $project->square_meter = $request->square_meter;
            $project->save();

            foreach($request->workImages as $workImage){

                $image = new SecondaryImage;
                $image->project_id = $project->id;
                $image->image = $workImage['finalName'];
                $image->save();

            }

            return response()->json(["success" => true, "msg" => "Proyecto creado"]);

        }catch(\Exception $e){
            return response()->json(["success" => true, "false" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function fetch(){

        try{

            $projects = Project::paginate(15);
            return response()->json(["success" => true, "projects" => $projects]);

        }catch(\Exception $e){
            return response()->json(["success" => true, "false" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }


    function edit($id){

        $project = Project::where("id", $id)->first();

        return view("products.edit", ["project" => $project]);

    }

    function update(ProjectUpdateRequest $request){
        ini_set('max_execution_time', 0);

        if($request->get("image") != null){

            try{

                $imageData = $request->get('image');
    
                if(strpos($imageData, "svg+xml") > 0){
    
                    $data = explode( ',', $imageData);
                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                    $ifp = fopen($fileName, 'wb' );
                    fwrite($ifp, base64_decode( $data[1] ) );
                    rename($fileName, 'images/products/'.$fileName);
    
                }else{
    
                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                    Image::make($request->get('image'))->save(public_path('images/products/').$fileName);
    
                }
                
    
            }catch(\Exception $e){
    
                return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
    
            }

        }

        try{

            $project = Project::find($request->id);
            $project->name = $request->name;
            $project->description = $request->description;
            if($request->get("image") != null){
                $project->image =  url('/').'/images/products/'.$fileName;
            }
            $project->location = $request->location;
            $project->square_meter = $request->square_meter;
            $project->update();

            $WorkImagesArray = [];
            $workImages = SecondaryImage::where("project_id", $project->id)->get();
            foreach($workImages as $productSecondaryImage){
                array_push($WorkImagesArray, $productSecondaryImage->id);
            }

            $requestArray = [];
            foreach($request->workImages as $image){
                if(array_key_exists("id", $image)){
                    array_push($requestArray, $image["id"]);
                }
            }

            $deleteWorkImages = array_diff($WorkImagesArray, $requestArray);
            
            foreach($deleteWorkImages as $imageDelete){
                SecondaryImage::where("id", $imageDelete)->delete();
            }

            foreach($request->workImages as $workImage){
                if(isset($workImage["finalName"])){
                    
                    $image = new SecondaryImage;
                    $image->project_id = $project->id;
                    $image->image = $workImage['finalName'];
                    $image->save();
                }

            }

            return response()->json(["success" => true, "msg" => "Proyecto actualizado"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function delete(Request $request){

        try{

            SecondaryImage::where("project_id", $request->id)->delete();
            Project::where("id", $request->id)->delete();

            return response()->json(["success" => true, "msg" => "Proyecto eliminado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

}
