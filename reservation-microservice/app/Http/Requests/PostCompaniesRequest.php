<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="PostCompanyRequest",
 *      description="PostCompanyRequest",
 *      type="object",
 *      required={"legal_name","country","marketing_name","address","city","zip","office_region","office_sub_region","phone","website","email"}
 * )
 */
class PostCompaniesRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules()
    {
        return [
            'files' => 'required',
        ];
    }
}
