<?php

namespace App\Http\Controllers;

use App\User;
use App\UserPreference;
use Illuminate\Http\Request;

/**
 *     name= "Preferences",
 *     description= "Route for User Preferences"
 *
 * @package App\Http\Controllers
 */
class UserPreferenceController extends Controller
{

    /**
     * User Preferences
     *
     * Ruta para almacenar las preferencias seleccionadas por el usuario.
     * <p>Tener en cuenta que el parámetro "category_code" hace referencia al código de la categoría y el parámetro "hash_id" es el identificador del usuario que se encuentra dentro del objeto "user"</p>
     *
     * @SWG\Post(
     *      path="/users/{has_id}/categories",
     *      tags={"Users"},
     *      summary="Stores preferences selected by the user.",
     *      @SWG\Parameter(ref="#/parameters/X-Api-Key"),
     *      @SWG\Parameter(ref="#/parameters/X-Client-Id"),
     *      @SWG\Parameter(ref="#/parameters/X-localization"),
     *      @SWG\Parameter(ref="#/parameters/Authorization"),
     *      @SWG\Parameter(
     *          name="hash_id",
     *          in="path",
     *          type="string",
     *          description="User Hash Id"
     *      ),
     *      @SWG\Parameter(
     *          name="category_code",
     *          in="query",
     *          type="array",
     *          items={""},
     *          description="Code of Category, the format of this param is category_code['value1','value2']",
     *          required=true,
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Response Object",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="message", type="string", default="User confirmed successfully."),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items()
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response="422",
     *          description="Error: Unprocessable Entity",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="message", type="string", default="User confirmed successfully."),
     *              @SWG\Property(
     *                  property="errors",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="category_code", type="string"),
     *                  )
     *              )
     *          )
     *      )
     *
     *  )
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'category_code' => 'required|array',
        ]);

        $user = $request->user();
        foreach ($request->category_code as $item) {
            $preference = new UserPreference([
                'category_code' => $item["code"],
            ]);

            try {
                $user->userPreferences()->save($preference);
            } catch (\Exception $e) {
            }
        }

        return response()->json([
            'message' => __('app.users.add_preferences'),
            'data' => $user->userPreferences,
        ]);
    }
}
