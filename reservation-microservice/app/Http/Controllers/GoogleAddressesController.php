<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GoogleAddressesController extends Controller
{
    public function __construct(
        private $GuzzleHttpClient = Client::class
    )
    {
    }

    private function client(){
        return new $this->GuzzleHttpClient();
    }

    public function get(Request $request)
    {

        if(!$request->input){
           return [
               'predictions' => []
           ];
        }
        $country = $request->country ? '&components=country:' . $request->country : '';
        $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?' . 'types=address&key=' . Config::get('app.GOOGLE_MAP_TOKEN') . '&input=' . $request->input . $country;

        $response = $this->client()->get($url);

        return response()->json(json_decode($response->getBody()->getContents()));
    }

    public function find(Request $request)
    {

        if(!$request->place_id){
           return [
               'predictions' => []
           ];
        }

        $url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . $request->place_id . '&key=' . Config::get('app.GOOGLE_MAP_TOKEN');

        $response = $this->client()->get($url);

        return response()->json(json_decode($response->getBody()->getContents()));
    }


}
