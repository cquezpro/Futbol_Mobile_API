<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 *  @SWG\Swagger(
 *      basePath="/v1",
 *      @SWG\Info(
 *          title="FutbolConnect API",
 *          version="1.0.0"
 *      ),
 *      @SWG\Parameter(
 *          name="X-Api-Key",
 *          in="header",
 *          type="string",
 *          required=true,
 *          description="Api Key, whether mobile or web",
 *      ),
 *      @SWG\Parameter(
 *          name="X-Client-Id",
 *          in="header",
 *          type="string",
 *          required=true,
 *          description="Client identification, whether mobile or web",
 *      ),
 *      @SWG\Parameter(
 *          name="X-localization",
 *          in="header",
 *          type="string",
 *          required=true,
 *          description="Iso code of the language for the answers. it can only be 'es' or 'en'"
 *      ),@SWG\Parameter(
 *          name="Authorization",
 *          in="header",
 *          type="string",
 *          required=true,
 *          default="Bearer {Token}",
 *          description="Bearer Token Access"
 *      ),
 *      @SWG\Parameter(
 *          name="q",
 *          in="query",
 *          type="string",
 *          description="Name of the resource to filter",
 *          required=false,
 *      ),
 *      @SWG\Parameter(
 *          name="per_page",
 *          in="query",
 *          type="integer",
 *          description="Parameter that defines the number of rows per result",
 *          default=15,
 *          required=false,
 *      ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
