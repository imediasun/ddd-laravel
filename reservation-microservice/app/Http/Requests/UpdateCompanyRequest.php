<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateCompanyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'legal_name' => 'required',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'office_region' => 'required',
            'office_sub_region' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));

    }
}
