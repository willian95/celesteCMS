<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffStoreRequest extends FormRequest
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
            "job" => "required"
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Nombre es requerido",
            "image.required" => "Imágen es requerida",
            "job.required" => "Cargon es requerido"
        ];
    }
}
