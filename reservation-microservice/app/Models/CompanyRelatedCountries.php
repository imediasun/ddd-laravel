<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     title="CompaniesRelatedCountry",
 *     description="CompaniesRelatedCountry model",
 * )
 */

use Illuminate\Database\Eloquent\Model;

class CompaniesRelatedCountries extends Model
{

    /**
     * @OA\Property(
     *     title="Company_id",
     *     description="Company_id",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $Company_id;

    /**
     * @OA\Property(
     *     title="related_country",
     *     description="related_country",
     *     format="string",
     *     example="test Company"
     * )
     *
     * @var string
     */
    private $related_country;

    protected $fillable = [
        'Company_id',
        'related_country',
    ];

    protected $table = 'Companies_related_countries';

    public function Company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
