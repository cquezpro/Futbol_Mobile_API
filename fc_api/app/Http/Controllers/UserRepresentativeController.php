<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class UserRepresentativeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $representative = User::query();
        if ($request->has('q')) {
            $representative->where('first_name', 'like', "%$request->q%");
            $representative->orWhere('last_name', 'like', "%$request->q%");
        }

        if ($request->has('per_page')) {
            $representative = $representative->paginate($request->per_page);
        } else {
            $representative = $representative->get();
        }

        return response()->json([
            'message' => '',
            'data'    => $representative,
        ], 200);
    }
}
