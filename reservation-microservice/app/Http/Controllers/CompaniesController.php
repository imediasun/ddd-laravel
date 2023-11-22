<?php

namespace App\Http\Controllers;

use App\DTO\UpdateCompanyDataTransformer;
use App\Events\UpdatingCompaniesLatLonEvent;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\DTO\CompanyDataTransformer;
use App\Services\CompaniesService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportCompanies;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PostCompaniesRequest;
use App\Jobs\UpdateCompaniesLatLon;
use App\Models\CompaniesRelatedCountries;
use App\Http\Requests\GetCompaniesByRelatedCountryRequest;
/**
* @OA\Tag(
 *     name="Companies",
 *     description="API Endpoints of Companies"
 * )
 */
class CompaniesController extends Controller
{
    public $Company;

    /**
     * @OA\Post(
     *      path="/api/Companies",
     *      operationId="create",
     *      tags={"Companies"},
     *      summary="Create Company",
     *      security={{"bearerAuth":{}}},
     *      description="Returns Company data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateCompanyRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      @OA\JsonContent(ref="#/components/schemas/Company"),
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function create(CreateCompanyRequest $request)
    {
        $Company_data = CompanyDataTransformer::fromArray($request);
        $Company = Company::create($Company_data->toArray());

        foreach($request->related_countries as $country){
            DB::connection('mysql')->table('Companies_related_countries')->insert(['Company_id' => $Company->id,'related_country' => $country]);
        }


        return $Company;
    }

    /**
     * @OA\Get(
     *      path="/api/Companies",
     *      operationId="list",
     *      tags={"Companies"},
     *      summary="show full list of Companies",
     *      security={{"token":{}}},
     *      description="Returns full list of Companies with pagination",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *     @OA\Property(
     *              property="data",
     *              type="array",
     *            @OA\Items(ref="#/components/schemas/Company"),
     * ),
     * ),
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function list()
    {
        $list = Company::all();

        foreach ($list as $item) {
            unset($item->remember_token);
            $item->edit = false;
            $item->delete = false;
        }

        return $list;
    }

    /**
     * @OA\Get(
     *      path="/api/Companies-list",
     *      operationId="CompaniesList",
     *      tags={"Companies"},
     *      summary="show full list of Companies",
     *      security={{"token":{}}},
     *      description="Returns full list of Companies with pagination",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *     @OA\Property(
     *              property="data",
     *              type="array",
     *            @OA\Items(ref="#/components/schemas/Company"),
     * ),
     * ),
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function CompaniesList()
    {
        $list = Company::all();
        foreach ($list as $item) {
            unset($item->remember_token);
            unset($item->created_at);
            unset($item->updated_at);
            $item->related_countries = (!json_decode($item->related_countries)) ? [] : json_decode($item->related_countries);
        }

        return $list;
    }

    /**
     * @OA\Patch(
     *      path="/api/Companies/{Company}",
     *      operationId="update",
     *      tags={"Companies"},
     *      summary="update information of single Company",
     *      security={{"token":{}}},
     *      description="Returns Company",
     *      @OA\Parameter(
     *          description="ID of Company",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *               type="integer",
     *              format="int64"
     *    )
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *     @OA\Property(
     *              property="data",
     *              type="array",
     *            @OA\Items(ref="#/components/schemas/Company"),
     * ),
     * ),
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function update(UpdateCompanyRequest $request, Company $Company)
    {
        $Company_data = UpdateCompanyDataTransformer::fromArray($request);
        $Company_data->country_code = CompaniesService::importCountries($Company_data->country);
        CompaniesRelatedCountries::where('Company_id',$request->id)->delete();
        $input = $request->related_countries;
        if (is_string($input)) {

            // check if the string is valid JSON
            $decoded_input = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE) {

                // the input is a valid JSON string, and has been decoded into an array
                $output = $decoded_input;

            }
        }
        elseif(is_array($input)){
            $output = $input;
        }
        foreach($output as $country){
            DB::connection('mysql')->table('Companies_related_countries')->insert(['Company_id' => $Company->id,'related_country' => $country]);
        }

        return Company::find($Company->id)->update($Company_data->toArray());
    }

    public function delete(Company $Company)
    {
        return Company::find($Company->id)->delete();
    }


    /**
     * @OA\Post(
     *      path="/api/Companies-import",
     *      operationId="postCompaniesData",
     *      tags={"Companies"},
     *      summary="Post Companies data",
     *      security={{"bearerAuth":{}}},
     *      description="Returns Company data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/PostCompaniesRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      @OA\JsonContent(ref="#/components/schemas/Company"),
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function postCompaniesData(PostCompaniesRequest $request){

        if ($request->hasFile('files')) {
            \Log::info('Filename=>'.print_r($request->file('files')[0]->getClientOriginalName(),true));
        } else {
            \Log::info('Error=>');
        }

        $validator = Validator::make($request->all(),[
            'files.*' => 'required|max:2048',
        ]);

        if($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);
        }

        $import = new ImportCompanies;
        $Companies = Excel::toArray($import,  $request->file('files')[0]);
        Excel::import($import,  $request->file('files')[0]);
        $n = 0;
        foreach($Companies[0] as $Company){
           //if($n > 1){ break;}
            $this->Company = $Company;
            UpdateCompaniesLatLon::dispatch($Company)/*->onConnection('redis')*/;
            $n++;
        }

        return response()->json(['success' => 'success','redirect' => '/'], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/Companies-by-country",
     *      operationId="getCompaniesByRelatedCountry",
     *      tags={"Companies"},
     *      summary="show full list of Companies by related country",
     *      security={{"token":{}}},
     *      description="Returns full list of Companies with pagination by related country",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *     @OA\Property(
     *              property="data",
     *              type="array",
     *            @OA\Items(ref="#/components/schemas/Company"),
     * ),
     * ),
     * ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function getCompaniesByRelatedCountry(GetCompaniesByRelatedCountryRequest $request){
        $list = Company::whereHas('relatedCountries', function($query) use ($request) {
            return $query->whereIn('related_country',[$request->country] );
        })->orderBy('created_at', 'desc')->get();

        foreach ($list as $item) {
            unset($item->remember_token);
            unset($item->created_at);
            unset($item->updated_at);
        }

        return $list;
    }

}
