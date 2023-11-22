<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use App\Services\CompaniesService;

class ImportCompanies implements ToModel, WithHeadingRow, ToCollection
{
    use Importable;
    public $data;
    public function collection(Collection $rows)
    {
        $this->data = $rows;
    }

    public function headingRow(): int
    {
        return 1;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        HeadingRowFormatter::default('none');
        $relatedCountries = '';
        try {
            $relatedCountries = CompaniesService::importCountries($row['countries']);

        } catch (\Exception $e) {
            // Log the error or inform the user of the issue
            $relatedCountries = '';
        }


        return new Company([
            'legal_name' => $row['legal_name'],
            'country' => $row['country'],
            'marketing_name' => $row['marketing_name'],
            'address' => $row['address'],
            'city'=> $row['city'],
            'zip' => $row['zip'],
            'state' => $row['state'],
            'office_region' => $row['office_region'],
            'office_sub_region' => $row['office_sub_region'],
            'phone' => $row['phone'],
            'website' => $row['website'],
            'country_code' => CompaniesService::importCountries($row['country'],true),
            'email'=> $row['email'],
            'lat' => null,
            'lon' => null,
            'related_countries' =>json_encode(explode(',',str_replace('"', '',$relatedCountries))),
        ]);
    }
}
