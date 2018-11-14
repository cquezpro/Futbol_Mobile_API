<?php

namespace App\Http\Controllers;

use App\Country;
use App\State;
use Illuminate\Http\Request;

/**
 * Countries
 * @SWG\Tag(
 *     name="Countries",
 *     description="Routes for the Countries"
 * )
 *
 * @resource Países
 * Rutas de acceso a las rutas para el consumo de países
 * @package App\Http\Controllers
 */
class CountriesController extends Controller
{
    /**
     * CountriesController constructor.
     */
    public function __construct()
    {
        //$this->middleware('client_credentials');
    }

    /**
     * Countries
     *
     * The list of all countries is displayed.
     *
     * @SWG\Get(
     *      path="/countries",
     *      summary="User Registration and Login With Facebook",
     *      tags={"Countries"},
     *      @SWG\Parameter(ref="#/parameters/X-Api-Key"),
     *      @SWG\Parameter(ref="#/parameters/X-Client-Id"),
     *      @SWG\Parameter(ref="#/parameters/X-localization"),
     *      @SWG\Parameter(ref="#/parameters/q"),
     *      @SWG\Parameter(ref="#/parameters/per_page"),
     *      @SWG\Response(
     *          response=200,
     *          description="Response Object when the per_page parameter is present",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="message", type="string", default="User confirmed successfully."),
     *              @SWG\Property(property="current_page", type="integer", default="Current page."),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="name", type="string"),
     *                      @SWG\Property(property="iso2", type="string"),
     *                      @SWG\Property(property="iso3", type="string"),
     *                      @SWG\Property(property="iso_num", type="string"),
     *                      @SWG\Property(property="phone_code", type="string"),
     *                      @SWG\Property(property="hash_id", type="string")
     *                  )
     *              ),
     *              @SWG\Property(property="first_page_url", type="string"),
     *              @SWG\Property(property="from", type="integer"),
     *              @SWG\Property(property="last_page", type="integer"),
     *              @SWG\Property(property="last_page_url", type="string"),
     *              @SWG\Property(property="next_page_url", type="string"),
     *              @SWG\Property(property="path", type="string"),
     *              @SWG\Property(property="per_page", type="integer"),
     *              @SWG\Property(property="prev_page_url", type="string"),
     *              @SWG\Property(property="to", type="integer"),
     *              @SWG\Property(property="total", type="integer"),
     *          )
     *      ),
     *      @SWG\Response(
     *          response="202",
     *          description="Response Object when the per_page parameter is not present. The real response code is: 200",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="message", type="string", default="User confirmed successfully."),
     *              @SWG\Property(property="current_page", type="integer", default="Current page."),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="name", type="string"),
     *                      @SWG\Property(property="iso2", type="string"),
     *                      @SWG\Property(property="iso3", type="string"),
     *                      @SWG\Property(property="iso_num", type="string"),
     *                      @SWG\Property(property="phone_code", type="string"),
     *                      @SWG\Property(property="hash_id", type="string")
     *                  )
     *              ),
     *          )
     *      ),
     *      @SWG\Response(
     *          response="401",
     *          description="Error: Unauthorized",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="message", type="string", default="Access denied for this client")
     *          )
     *      )
     * )
     * <ul>
     * <li>
     * <p>El parámetro opcional "q" es para el filtrado de datos<br>
     * en este caso se debe de utilizar de la siguiente forma:</p>
     * <p><code style="padding: 10px;">
     *   q=column_name,text_value
     * </code></p>
     * </li>
     * <li>
     * <p>
     * El parámetro opcional "limit" es para limitar la cantidad total de registros a retornar.
     * </p>
     * <p><code>limit=numeric_value</code></p>
     * </li>
     * </ul>
     *
     * @param Request $request
     * @param null $per_page
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $per_page = null)
    {
        $countries = Country::query();
        if ($request->has('q'))
            $countries->where('name', 'like', "%$request->q%");

        $countries = $per_page !== null ? $countries->paginate($per_page) : $countries->get();

        return response()->json($countries, 200);
    }

    /**
     * Estados
     *
     * @param Request $request
     * @param $country
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function  states(Request $request, $country)
    {
        $states = [];

        if ($country != '')
            $states = Country::find($country)->states();

        if ($request->has('q'))
            $states->where('name', 'like', "%$request->q%");

        return response()->json([
            'data' => $states->get(),
        ]);
    }

    /**
     * Ciudades
     *
     * Listado de ciudades según el estado dado
     *
     * @param Request $request
     * @param $state
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request, $state)
    {
        $cities = [];
        if ($state != '')
            $cities = State::find($state)->cities();

        if ($request->has('q'))
            $cities->where('name', 'like', "%$request->q%");

        return response()->json([
            'data' => $cities->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
