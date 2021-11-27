<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NouveauPersonnelRequest extends FormRequest
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
            "firstname" => "required",
            "firstname_mother" => "required",
            "civility" => "required",
            "gender" => "required",
            "birthdate" => "required"
        ];
    }
}
