<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ApiClient
{
    private Client $http;
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.dmce_api.base_url', env('API_BASE_URL', 'http://100.96.42.74:82')), '/');

        $this->http = new Client([
            'base_uri' => $this->baseUrl . '/api/v1/',
            'timeout'  => 30,
            'headers'  => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    private function token(): ?string
    {
        return Session::get('api_token') ?? config('services.dmce_api.token', env('API_TOKEN'));
    }

    private function authHeaders(): array
    {
        $token = $this->token();
        return $token ? ['Authorization' => 'Bearer ' . $token] : [];
    }

    // ── Auth ────────────────────────────────────────────────────────────────

    public function login(string $email, string $password): array
    {
        $response = $this->http->post('login', [
            'json' => compact('email', 'password'),
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['token'])) {
            Session::put('api_token', $data['token']);
            Session::put('api_user',  $data['user'] ?? null);
        }

        return $data;
    }

    public function logout(): void
    {
        try {
            $this->http->post('logout', ['headers' => $this->authHeaders()]);
        } catch (\Throwable $e) {
            Log::warning('ApiClient logout error: ' . $e->getMessage());
        }
        Session::forget(['api_token', 'api_user']);
    }

    public function me(): array
    {
        return $this->get('me');
    }

    // ── Impétrants ──────────────────────────────────────────────────────────

    public function getImpetrants(array $params = []): array
    {
        return $this->get('impetrants', $params);
    }

    public function getImpetrant(int $id): array
    {
        return $this->get("impetrants/{$id}");
    }

    public function createImpetrant(array $data): array
    {
        return $this->post('impetrants', $data);
    }

    public function updateImpetrant(int $id, array $data): array
    {
        return $this->put("impetrants/{$id}", $data);
    }

    // ── Demandes ────────────────────────────────────────────────────────────

    public function getDemandes(array $params = []): array
    {
        return $this->get('demandes', $params);
    }

    public function getDemande(int $id): array
    {
        return $this->get("demandes/{$id}");
    }

    public function createDemande(array $data): array
    {
        return $this->post('demandes', $data);
    }

    public function getStatistiquesDemandes(): array
    {
        return $this->get('demandes/statistiques');
    }

    // ── Flux migratoires ────────────────────────────────────────────────────

    public function getFlux(array $params = []): array
    {
        return $this->get('flux', $params);
    }

    public function getStatistiquesFlux(): array
    {
        return $this->get('flux/statistiques');
    }

    // ── Référentiels ────────────────────────────────────────────────────────

    public function getPays(): array
    {
        return $this->get('pays');
    }

    public function getFrontieres(): array
    {
        return $this->get('frontieres');
    }

    // ── Ping ────────────────────────────────────────────────────────────────

    public function ping(): array
    {
        try {
            $response = $this->http->get('ping');
            return json_decode($response->getBody(), true);
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── Helpers privés ──────────────────────────────────────────────────────

    private function get(string $uri, array $query = []): array
    {
        try {
            $options = ['headers' => $this->authHeaders()];
            if ($query) {
                $options['query'] = $query;
            }
            $response = $this->http->get($uri, $options);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->handleError($e);
        }
    }

    private function post(string $uri, array $data = []): array
    {
        try {
            $response = $this->http->post($uri, [
                'headers' => $this->authHeaders(),
                'json'    => $data,
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->handleError($e);
        }
    }

    private function put(string $uri, array $data = []): array
    {
        try {
            $response = $this->http->put($uri, [
                'headers' => $this->authHeaders(),
                'json'    => $data,
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->handleError($e);
        }
    }

    private function handleError(RequestException $e): array
    {
        $status  = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;
        $body    = $e->hasResponse() ? (string) $e->getResponse()->getBody() : '';
        $decoded = json_decode($body, true);

        Log::error("ApiClient error [{$status}]: " . $e->getMessage());

        return [
            'error'   => true,
            'status'  => $status,
            'message' => $decoded['message'] ?? $e->getMessage(),
        ];
    }
}
