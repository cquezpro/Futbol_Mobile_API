<?php

namespace App\Http\Controllers;

use App\SpeakLanguage;
use App\TechnicalInformation;
use App\User;
use App\UserGamePosition;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class TechnicalInformationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Retorna la información técnica del usuario.
     * no se requieren páramentros y el user_id (hash_id) debe de enviarse dentro del endpoint
     * Respuesta 200
     * [
     *      "message": "",
     *      "data": {
     *          "weight": 86,
     *          "height": 170,
     *          "right_foot_strength": 4,
     *          "left_foot_strength": 2,
     *          "professional_contract": 1,
     *          "hash_id": "qlbgwkdQnJgP9M6DvmLV"
     *      }
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(User $user)
    {

        $technicalInfo = $user->load(
            'technicalInformation',
            'speakLanguages',
            'gamePositions'
        );

        return response([
            'message' => 'Response Success',
            'data'    => $technicalInfo,
        ], 200);
    }

    /**
     * Ruta para almacenar o editar la información técnica del usuario
     * ninguno de los parámetros son obligatorios.
     * los parámetros son:
     * @param integer weight Peso del usuario
     * @param integer height Altura del Usuario
     * @param integer right_foot_strength debe ser entero, mayor a 1 y menor 5
     * @param integer left_foot_strength debe ser entero, mayor a 1 y menor 5
     * @param boolean professional_contract parámetro booleano debe ser true o false.
     * @param array speak_languages Objeto con el listado de los lenguajes seleccionados por el usuario.
     *          Example:
     *          speak_languages = [
     *              {
     *                  "hash_id": "identificador del language en caso de ser nuevo enviar -1",
     *                  "name": "{idioma}"
     *              },
     *          ];
     * @param array game_position Parámetro para especificar las posiciones de juego seleccionadas por el usuario
     *          Example:
     *          "game_positions": [
     *              {
     *                  "code": "cm"
     *              },
     *              {
     *                  "code": "cf"
     *              },
     *              {
     *                  "code": "gk"
     *              },
     *              {
     *                  "code": "cb"
     *              }
     *          ]
     * @param string representative_hash_id" identificador del usuario representante seleccionado por el usuario.
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, User $user)
    {
        //return response($request->all());
        $request->validate([
            'professional_contract' => 'boolean',
            'right_foot_strength'   => 'integer|min:1|max:5',
            'left_foot_strength'    => 'integer|min:1|max:5',
            'game_positions'        => 'nullable|array',
        ]);

        $technicalInfo = !$user->technicalInformation()->exists()
            ? new TechnicalInformation()
            : $user->technicalInformation()->first();

        $technicalInfo->weight = $request->weight;
        $technicalInfo->height = $request->height;
        $technicalInfo->right_foot_strength = $request->right_foot_strength;
        $technicalInfo->left_foot_strength = $request->left_foot_strength;
        $technicalInfo->professional_contract = $request->has('professional_contract') ?
            $request->professional_contract : false;

        $user->technicalInformation()
            ->save($technicalInfo);

        if ($request->has('speak_languages')) {
            foreach ($request->speak_languages as $speak_language) {

                $speakLanguage = null;

                if ($speak_language["hash_id"] != -1) {
                    $id = Hashids::decode($speak_language["hash_id"]);
                    $newSpeakLanguage = (count($id) > 0)
                        ? SpeakLanguage::find($id[0])
                        : SpeakLanguage::create([
                            'name' => $speak_language["name"],
                        ]);
                } else {
                    $newSpeakLanguage = SpeakLanguage::where('name', 'like', "%" . $speak_language['name'] . "%")->first();

                    if (!$newSpeakLanguage || $newSpeakLanguage->count() <= 0) {
                        $newSpeakLanguage = SpeakLanguage::create([
                            'name' => $speak_language["name"],
                        ]);
                    }
                }

                $currenSpeakLanguage = $user->speakLanguages()
                    ->where('speak_language_id', '=', $newSpeakLanguage->id)
                    ->get()
                    ->count();

                if ($currenSpeakLanguage <= 0) {
                    $user->speakLanguages()->attach($newSpeakLanguage->id);
                }
            }
        }

        if ($request->has('representative_hash_id')) {
            $id = Hashids::decode($request->representative_hash_id);
            if (count($id) <= 0)
                return abort('404', trans('app.validates.hash_id'));

            $representative = User::where('id', $id)->first();

            if ($representative) {
                $user->representative()->associate($representative->id);
                $user->save();
            }
        }

        foreach ($request->game_positions as $game_position) {
            $game_position = (object)$game_position;
            $game_position->code;

            $newUserGamePosition = new UserGamePosition([
                'code' => $game_position->code,
            ]);

            if (!$user->gamePositions()->where('code', $game_position->code)->exists())
                $user->gamePositions()->save($newUserGamePosition);
        }

        return response()->json([
            'message' => '',
            'data'    => $user
                ->load('technicalInformation')
                ->load('speakLanguages', 'gamePositions'),
        ]);
    }
}
