<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

/**
 * @OA\Schema(
 *      title="CreateCompanyRequest",
 *      description="CreateCompanyRequest",
 *      type="object",
 *      required={"legal_name","country","marketing_name","address","city","zip","office_region","office_sub_region","phone","website","email"}
 * )
 */
class CreateCompanyRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="legal_name",
     *      description="legal_name",
     *      example="Cinionic"
     * )
     *
     * @var string
     */

    public $legal_name;

    /**
     * @OA\Property(
     *      title="country",
     *      description="country",
     *      example="USA"
     * )
     *
     * @var string
     */

    public $country;

    /**
     * @OA\Property(
     *      title="marketing_name",
     *      description="marketing_name",
     *      example="Cinionic MV"
     * )
     *
     * @var string
     */

    public $marketing_name;

    /**
     * @OA\Property(
     *      title="address",
     *      description="address",
     *      example="Rue Du Martin 238"
     * )
     *
     * @var string
     */

    public $address;

    /**
     * @OA\Property(
     *      title="city",
     *      description="city",
     *      example="New York"
     * )
     *
     * @var string
     */

    public $city;

    /**
     * @OA\Property(
     *      title="zip",
     *      description="zip_code",
     *      example="1332"
     * )
     *
     * @var string
     */

    public $zip;

    /**
     * @OA\Property(
     *      title="state",
     *      description="state",
     *      example="NewYork"
     * )
     *
     * @var string
     */

    public $state;

    /**
     * @OA\Property(
     *      title="office_region",
     *      description="office_region",
     *      example="NewYork"
     * )
     *
     * @var string
     */

    public $office_region;

    /**
     * @OA\Property(
     *      title="office_sub_region",
     *      description="office_sub_region",
     *      example="NewYork"
     * )
     *
     * @var string
     */

    public $office_sub_region;

    /**
     * @OA\Property(
     *      title="phone",
     *      description="phone",
     *      example="+380965441120"
     * )
     *
     * @var string
     */

    public $phone;

    /**
     * @OA\Property(
     *      title="website",
     *      description="website",
     *      example="http://cinionic.com"
     * )
     *
     * @var string
     */

    public $website;

    /**
     * @OA\Property(
     *      title="email",
     *      description="email",
     *      example="dev.magellan@gmail.com"
     * )
     *
     * @var string
     */

    public $email;

    /**
     * @OA\Property(
     *      title="lat",
     *      description="lat",
     *      example="57.00001"
     * )
     *
     * @var string
     */

    public $lat;

    /**
     * @OA\Property(
     *      title="lon",
     *      description="lon",
     *      example="57.00001"
     * )
     *
     * @var string
     */

    public $lon;

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
