<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
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
            "title" => "required",
            "image" => "required",
            "description" => "required"
        ];
    }

    public function messages()
    {
        return [
            "title.required" => "Tiulo es requerido",
            "image.required" => "Imágen es requerida",
            "description.required" => "Se requiere texto para tu blog"
        ];
    }
}
