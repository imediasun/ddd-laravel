<?php

namespace App\Jobs;

use App\Events\UpdatingCompaniesLatLonEvent;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCompaniesLatLon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $Company;

    public function __construct($Company)
    {

        $this->Company = $Company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
       $Company = DB::connection('mysql')->table('Companies')->where('legal_name', $this->Company['legal_name'])->first();

         $address = $this->Company['address'] . ', ' . $this->Company['city'] . ', ' . $this->Company['country'] . ', ' . $this->Company['zip']; // Address
         $address = str_replace(' ', '+', $address);
         $apiKey = config('services.google_maps.key');

         $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false&key=' . $apiKey);
         $geo = json_decode($geo, true); // Convert the JSON to an array

         if (isset($geo['status']) && ($geo['status'] == 'OK')) {
             \Log::info('GEOresult=>'.print_r($geo['results'][0],true));
             $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
             $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
         } else {
             \Log::info('GEOStatus=>'.$geo['status']);
             $latitude = '0.0';
             $longitude = '0.0';
         }
        $dataObj = new \stdClass();
        $dataObj->id = $Company->id;
        $dataObj->lat = $latitude;
        $dataObj->lng = $longitude;

        foreach(json_decode($Company->related_countries) as $country){
            Log::info('related_countries3=>'.$country);
            DB::connection('mysql')->table('Companies_related_countries')->insert(['Company_id' => $Company->id,'related_country' => $country]);
        }

        DB::connection('mysql')->table('Companies')->where('id', $Company->id)->update(['lat'=>$latitude,'lng'=>$longitude ]);
        //broadcast(new UpdatingCompaniesLatLonEvent($dataObj));
    }
}
