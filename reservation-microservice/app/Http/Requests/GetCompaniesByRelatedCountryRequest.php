<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Input;
/**
 * @OA\Schema(
 *      title="GetCompaniesByRelatedCountryRequest",
 *      description="GetCompaniesByRelatedCountryRequest",
 *      type="object",
 *      required={"country"}
 * )
 */
class GetCompaniesByRelatedCountryRequest extends FormRequest
{
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['country'] = $this->route('country');
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules()
    {
        return [
            'country' => 'required',
        ];
    }
}
