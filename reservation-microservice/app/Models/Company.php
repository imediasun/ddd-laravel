<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     title="Company",
 *     description="Company model",
 * )
 */

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     title="legal_name",
     *     description="name of the Company",
     *     format="string",
     *     example="test Company"
     * )
     *
     * @var string
     */
    private $legal_name;

    /**
     * @OA\Property(
     *     title="country",
     *     description="country of the Company",
     *     format="string",
     *     example="USA"
     * )
     *
     * @var string
     */
    private $country;

    /**
     * @OA\Property(
     *     title="marketing_name",
     *     description="marketing name of the Company",
     *     format="string",
     *     example="Testimonal"
     * )
     *
     * @var string
     */
    private $marketing_name;

    /**
     * @OA\Property(
     *     title="address",
     *     description="address of the Company",
     *     format="string",
     *     example="PO BOX 12 35 Santa Monica"
     * )
     *
     * @var string
     */
    private $address;

    /**
     * @OA\Property(
     *     title="city",
     *     description="city of the Company",
     *     format="string",
     *     example="Orlando"
     * )
     *
     * @var string
     */
    private $city;

    /**
     * @OA\Property(
     *     title="zip",
     *     description="zip_code of the Company",
     *     format="string",
     *     example="1332"
     * )
     *
     * @var string
     */
    private $zip;

    /**
     * @OA\Property(
     *     title="state",
     *     description="state of the Company",
     *     format="string",
     *     example="New York"
     * )
     *
     * @var string
     */
    private $state;

    /**
     * @OA\Property(
     *     title="office_region",
     *     description="office_region of the Company",
     *     format="string",
     *     example="District"
     * )
     *
     * @var string
     */
    private $office_region;

    /**
     * @OA\Property(
     *     title="office_sub_region",
     *     description="office_sub_region of the Company",
     *     format="string",
     *     example="Sub District"
     * )
     *
     * @var string
     */
    private $office_sub_region;

    /**
     * @OA\Property(
     *     title="phone",
     *     description="phone of the Company",
     *     format="string",
     *     example="3245678"
     * )
     *
     * @var string
     */
    private $phone;

    /**
     * @OA\Property(
     *     title="website",
     *     description="website of the Company",
     *     format="string",
     *     example="www.disnay.com"
     * )
     *
     * @var string
     */
    private $website;

    /**
     * @OA\Property(
     *     title="email",
     *     description="email of the Company",
     *     format="string",
     *     example="test@test.com"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="lat",
     *     description="latitude of the Company",
     *     format="string",
     *     example="50.7523976220287"
     * )
     *
     * @var string
     */
    private $lat;

    /**
     * @OA\Property(
     *     title="lon",
     *     description="longitude of the Company",
     *     format="string",
     *     example="50.7523976220287"
     * )
     *
     * @var string
     */
    private $lon;

    protected $fillable = [
        'legal_name',
        'country',
        'country_code',
        'marketing_name',
        'address',
        'city',
        'zip',
        'state',
        'office_region',
        'office_sub_region',
        'phone',
        'website',
        'email',
        'lat',
        'lng',
        'related_countries'
    ];

    protected $table = 'Companies';

    public function relatedCountries(){
        return $this->HasMany('App\Models\CompaniesRelatedCountries');
    }
}
