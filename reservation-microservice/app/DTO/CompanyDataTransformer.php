<?php

namespace App\DTO;

use App\Http\Requests\CreateCompanyRequest;

class CompanyDataTransformer
{

    public function __construct(
        public $legal_name,
        public $country,
        public $country_code,
        public $marketing_name,
        public $address,
        public $city,
        public $zip,
        public $state,
        public $office_region,
        public $office_sub_region,
        public $phone,
        public $website,
        public $email,
        public $lat,
        public $lng,
        public $related_countries
    ) {
    }


    static function fromArray(CreateCompanyRequest $request): self
    {
        return new self(
            legal_name: $request['legal_name'],
            country: $request['country'],
            country_code: $request['country_code'],
            marketing_name: $request['marketing_name'],
            address: $request['address'],
            city: $request['city'],
            zip: $request['zip'],
            state: $request['state'],
            office_region: $request['office_region'],
            office_sub_region: $request['office_sub_region'],
            phone: $request['phone'],
            website: $request['website'],
            email: $request['email'],
            lat: $request['lat'],
            lng: $request['lng'],
            related_countries: $request['related_countries'],
        );
    }

    public function toArray(): array
    {
        return [
            'legal_name' => $this->legal_name,
            'country' => $this->country,
            'country_code' => $this->country_code,
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
            'lat' => $this->lat,
            'lng' => $this->lng,
            'related_countries' => json_encode($this->related_countries)

        ];
    }

}
