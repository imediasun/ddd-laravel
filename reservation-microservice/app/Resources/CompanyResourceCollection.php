<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Company */
class CompanyResourceCollection extends ResourceCollection
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'legal_name' => $this->legal_name,
            'country' => $this->country,
            'marketing_name' => $this->marketing_name,
            'address' => $this->address,
            'city' => $this->city,
            'zip' => $this->zip,
            'state' => $this->state,
            'office_region' => $this->office_region,
            'office_sub_region' => $this->office_sub_region,
            'phone' => $this->phone,
            'website' => $this->website,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
