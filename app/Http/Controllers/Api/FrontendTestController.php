<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class FrontendTestController extends Controller
{
    private ApiClient $api;

    public function __construct()
    {
        $this->api = new ApiClient();
    }

    // GET /api-test/ping
    public function ping()
    {
        $result = $this->api->ping();
        return response()->json($result);
    }

    // POST /api-test/login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->api->login($data['email'], $data['password']);
        return response()->json($result);
    }

    // GET /api-test/me  (nécessite token en session ou header X-Api-Token)
    public function me(Request $request)
    {
        if ($request->hasHeader('X-Api-Token')) {
            session(['api_token' => $request->header('X-Api-Token')]);
        }
        return response()->json($this->api->me());
    }

    // GET /api-test/demandes
    public function demandes(Request $request)
    {
        if ($request->hasHeader('X-Api-Token')) {
            session(['api_token' => $request->header('X-Api-Token')]);
        }
        return response()->json($this->api->getDemandes($request->query()));
    }

    // GET /api-test/impetrants
    public function impetrants(Request $request)
    {
        if ($request->hasHeader('X-Api-Token')) {
            session(['api_token' => $request->header('X-Api-Token')]);
        }
        return response()->json($this->api->getImpetrants($request->query()));
    }

    // GET /api-test/statistiques
    public function statistiques(Request $request)
    {
        if ($request->hasHeader('X-Api-Token')) {
            session(['api_token' => $request->header('X-Api-Token')]);
        }
        return response()->json([
            'demandes' => $this->api->getStatistiquesDemandes(),
            'flux'     => $this->api->getStatistiquesFlux(),
        ]);
    }
}
