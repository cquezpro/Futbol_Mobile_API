<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexCategoriesRequest;
use App\Language;
use App\LanguageKey;
use App\LanguageKeyItem;
use function foo\func;
use Illuminate\Http\Request;

/**
 *     name="Categories",
 *     description="Categories List"
 *
 * @author Kevin Cifuentes
 */
class CategoriesController extends Controller
{
    /**
     * Title
     * Categories List
     *
     * <p>Path to list the categories stored in the system</p>
     * <h3>Note!</h3>
     * <p>Still pending to add the images of the categories in the database, for the case of the categories that have no associated image use the first letter of this style gmail</p>
     * <br>
     * <p>To associate the category with a user, the 'code' must be sent, since the relationships are made through this attribute.</p>
     * @SWG\Get(
     *      tags={"Categories"},
     *      path="/categories",
     *      summary="Path to list the categories stored in the system.",
     *      @SWG\Parameter(ref="#/parameters/X-Api-Key"),
     *      @SWG\Parameter(ref="#/parameters/X-Client-Id"),
     *      @SWG\Parameter(ref="#/parameters/X-localization"),
     *      @SWG\Parameter(ref="#/parameters/q"),
     *      @SWG\Parameter(ref="#/parameters/per_page"),
     *      @SWG\Response(
     *          response=200,
     *          description="Response Object",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="message", type="string", default="User confirmed successfully."),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="name", type="string"),
     *                      @SWG\Property(property="code", type="string"),
     *                      @SWG\Property(property="hash_id", type="string"),
     *                      @SWG\Property(property="language_key_items", type="array",
     *                          @SWG\Items(
     *                              @SWG\Property(property="value", type="string"),
     *                              @SWG\Property(property="hash_id", type="string")
     *                          )
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     * )
     * @param IndexCategoriesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexCategoriesRequest $request)
    {
        $categories = LanguageKey::whereHas('languageKeyItems', function ($query) use ($request) {
            if ($request->has('q') && !empty($request->q))
                $query->where('value', 'like', "$request->q%");
        })->where('name', 'categories')->with([
            'languageKeyItems' => function ($query) use ($request) {
                if ($request->has('q') && !empty($request->q))
                    $query->where('value', 'like', "$request->q%");
                $query->where('language_id', Language::where('iso_code', app()->getLocale())->first()->id);
            },
        ]);

        if ($request->has("per_page")) {
            $categories = $categories->paginate($request->per_page);
        } else {
            $categories = $categories->get();
        }

        return response()->json([
            'categories' => $categories,
        ], 200);
    }
}
