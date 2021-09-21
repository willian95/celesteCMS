<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required",
            "image" => "required",
            "description" => "required",
            "location" => "required",
            "square_meter" => "required",
            "project_type" => "required"
        ];
    }

    public function messages(){

        return[

            "name.required" => "Título del producto es requerido",
            "image.required" => "Imágen del producto es requerido",
            "description.required" => "Descripción es requerida",
            "location.required" => "Ubicación es requerida",
            "square_meter" => "Metros cuadrados son requeridos",
            "project_type.required" => "Tipo de proyecto es requerido"

        ];

    }
}
