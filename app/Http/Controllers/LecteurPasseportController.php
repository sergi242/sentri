<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LecteurPasseportController extends Controller
{
    private string $base = 'http://127.0.0.1:8085';

    /**
     * Lire les données du document posé sur le lecteur.
     * GET /api/lecteur/read
     */
    public function read()
    {
        try {
            $response = Http::timeout(20)->get($this->base . '/read');

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error'   => true,
                'message' => 'Le lecteur a retourné une erreur HTTP ' . $response->status(),
            ], 502);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Lecteur inaccessible (port 8085). Vérifiez que le service est démarré.',
            ], 503);

        } catch (\Throwable $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Erreur inattendue : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Redémarrer le lecteur.
     * GET /api/lecteur/restart
     */
    public function restart()
    {
        try {
            $response = Http::timeout(8)->get($this->base . '/restart');

            return response()->json([
                'success' => $response->successful(),
                'status'  => $response->status(),
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Lecteur inaccessible.',
            ], 503);

        } catch (\Throwable $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
