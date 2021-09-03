<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StaffStoreRequest;
use App\Http\Requests\StaffUpdateRequest;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Models\Staff;

class StaffController extends Controller
{
    
    function create(){

        return view("staffs.create");

    }

    function list(){
        return view("staffs.list");
    }

    function store(StaffStoreRequest $request){
        ini_set('max_execution_time', 0);


        try{

            $staff = new Staff;
            $staff->name = $request->name;
            $staff->job = $request->job;
            $staff->image = $request->image;
            $staff->save();

            return response()->json(["success" => true, "msg" => "Staff creado"]);

        }catch(\Exception $e){
            return response()->json(["success" => true, "false" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function fetch(){

        try{

            $staffs = Staff::all();

            return response()->json(["success" => true, "staffs" => $staffs]);

        }catch(\Exception $e){
            return response()->json(["success" => true, "false" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function edit($id){

        $staff = Staff::where("id", $id)->first();

        return view("staffs.edit", ["staff" => $staff]);

    }

    function update(StaffUpdateRequest $request){
        ini_set('max_execution_time', 0);

        try{

            $staff = Staff::find($request->id);
            $staff->name = $request->name;
            $staff->job = $request->job;
            if($request->get("image") != null){
                $staff->image =  $request->get("image");
            }
            $staff->update();

            return response()->json(["success" => true, "msg" => "Staff actualizado"]);

        }catch(\Exception $e){
            return response()->json(["success" => true, "msg" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function delete(Request $request){

        try{

            Staff::where("id", $request->id)->delete();

            return response()->json(["success" => true, "msg" => "Staff eliminado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

}
