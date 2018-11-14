<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

/**
 * @SWG\Tag(
 *     name="Cities",
 *     description="Routes for the Cities"
 * )
 */
class CityController extends Controller
{
    /**
     * Método
     * List of Cities
     *
     * @SWG\Get(
     *      path="/cities",
     *      summary="User Registration and Login With Facebook",
     *      tags={"Cities"},
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
     *                      @SWG\Property(property="format_string", type="string"),
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
     *                      @SWG\Property(property="format_string", type="string"),
     *                      @SWG\Property(property="hash_id", type="string"),
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
     * @param Request $request
     * @param int $per_page
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cities = City::query();

        if ($request->has('q'))
            $cities->where('format_string', 'like', "%$request->q%");

        $cities->select(['name', 'format_string', 'id']);


        if ($request->has("per_page")) {
            $cities = $cities->paginate($request->per_page);
        } else {
            $cities = $cities->get();
        }

        return response()->json([
            'message' => '',
            'count' => count($cities),
            'cities' => $cities,
        ], 200);

    }
}
