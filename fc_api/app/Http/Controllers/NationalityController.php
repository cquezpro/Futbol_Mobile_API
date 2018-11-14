<?php

namespace App\Http\Controllers;

use App\Http\Requests\NationalityIndexRequest;
use App\LanguageKey;
use Illuminate\Http\Request;

/**
 * Class NationalityController
 * @SWG\Tag(
 *     name="Nationalities",
 *     description="Route for User Preferences"
 * )
 * @package App\Http\Controllers
 */
class NationalityController extends Controller
{

    /**
     * Ruta para listar las nacionalidades, esta ruta también utiliza el filtrado por medio del parámetro "q"
     *
     * param string q Usado para filtrar el código de la nacionalidad.
     * param Integer per_page Se usa para especificar la cantidad de registros por página, este parámetro no es obligatorio.
     * @param NationalityIndexRequest $request
     * @return \Illuminate\Http\Response
     *
     * Respuesta 200:
     * {
     *      "message": "",
     *      "data": [
     *          {
     *              "name": "nationalities",
     *              "code": "afghan",
     *              "hash_id": "EJLM5dvWpG6em1Oa4DZw",
     *              "language_key_items": []
     *          },
     *          {
     *              "name": "nationalities",
     *              "code": "albanian",
     *              "hash_id": "GlQ5NMwje9Yez0oDEyOr",
     *              "language_key_items": []
     *          },
     *          {
     *              "name": "nationalities",
     *              "code": "algerian",
     *              "hash_id": "Gjr8oYByP7JndwKW2O7z",
     *              "language_key_items": []
     *          },
     *      ]
     * }
     *
     * Respuesta 200, cuando se envía el parámetro per_page.
     *
     * {
     *      "message": "",
     *      "data": {
     *          "current_page": 1,
     *          "data": [
     *              {
     *                  "name": "nationalities",
     *                  "code": "colombian",
     *                  "hash_id": "RyGjMrQKpoz5eqY7v3Eg",
     *                  "language_key_items": [
     *                      {
     *                          "value": "Colombiano"
     *                      }
     *                  ]
     *              }
     *          ],
     *          "first_page_url": "http://api.futbolconnect.san/v1/nationalities?page=1",
     *          "from": 1,
     *          "last_page": 1,
     *          "last_page_url": "http://api.futbolconnect.san/v1/nationalities?page=1",
     *          "next_page_url": null,
     *          "path": "http://api.futbolconnect.san/v1/nationalities",
     *          "per_page": "50",
     *          "prev_page_url": null,
     *          "to": 1,
     *          "total": 1
     *      }
     * }
     */
    public function index(NationalityIndexRequest $request)
    {
        $keyItems = LanguageKey::keyItemsByKey('nationalities', $request->q);

        if($request->has('per_page')) {
            $keyItems = $keyItems->paginate($request->per_page);
        }else{
            $keyItems = $keyItems->get();
        }

        return response()->json([
            'message' => '',
            'data'    => $keyItems,
        ], 200);
    }
}
