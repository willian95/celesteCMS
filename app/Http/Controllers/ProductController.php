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

        try{

            $project = Project::find($request->id);
            $project->name = $request->name;
            $project->description = $request->description;
            if($request->get("image") != null){
                $project->main_image =  $request->image;
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
