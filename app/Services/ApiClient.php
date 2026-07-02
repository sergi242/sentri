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

        $data = $this->decode($response->getBody());

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
            return $this->decode($response->getBody());
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── Users ────────────────────────────────────────────────────────────────

    public function getUsers(?array $filters = []): array
    {
        return $this->get('users', $filters ?? []);
    }

    public function getUser($id): array
    {
        return $this->get("users/{$id}");
    }

    public function createUser(array $data): array
    {
        return $this->post('users', $data);
    }

    public function updateUser($id, array $data): array
    {
        return $this->put("users/{$id}", $data);
    }

    public function deleteUser($id): array
    {
        return $this->delete("users/{$id}");
    }

    public function toggleUserActive($id): array
    {
        return $this->post("users/{$id}/toggle-active");
    }

    public function resetUserPassword($id, string $password): array
    {
        return $this->post("users/{$id}/reset-password", ['password' => $password]);
    }

    // ── Roles (manage) ───────────────────────────────────────────────────────

    public function getRolesManage(): array
    {
        return $this->get('roles-manage');
    }

    public function getRoleManage($id): array
    {
        return $this->get("roles-manage/{$id}");
    }

    public function createRole(array $data): array
    {
        return $this->post('roles-manage', $data);
    }

    public function updateRole($id, array $data): array
    {
        return $this->put("roles-manage/{$id}", $data);
    }

    public function deleteRole($id): array
    {
        return $this->delete("roles-manage/{$id}");
    }

    // ── Grades (manage) ──────────────────────────────────────────────────────

    public function getGradesManage(): array
    {
        return $this->get('grades-manage');
    }

    public function getGradeManage($id): array
    {
        return $this->get("grades-manage/{$id}");
    }

    public function createGrade(array $data): array
    {
        return $this->post('grades-manage', $data);
    }

    public function updateGrade($id, array $data): array
    {
        return $this->put("grades-manage/{$id}", $data);
    }

    public function deleteGrade($id): array
    {
        return $this->delete("grades-manage/{$id}");
    }

    // ── Watchlist ────────────────────────────────────────────────────────────

    public function getWatchlist(?array $filters = []): array
    {
        return $this->get('watchlist', $filters ?? []);
    }

    public function getWatchlistItem($id): array
    {
        return $this->get("watchlist/{$id}");
    }

    public function createWatchlist(array $data): array
    {
        return $this->post('watchlist', $data);
    }

    public function updateWatchlist($id, array $data): array
    {
        return $this->put("watchlist/{$id}", $data);
    }

    public function deleteWatchlist($id): array
    {
        return $this->delete("watchlist/{$id}");
    }

    public function checkWatchlist($impetrantId): array
    {
        return $this->get("watchlist/check/{$impetrantId}");
    }

    // ── Soit-Transmis ────────────────────────────────────────────────────────

    public function getSoitTransmisList(?array $filters = []): array
    {
        return $this->get('soit-transmis', $filters ?? []);
    }

    public function getSoitTransmis($id): array
    {
        return $this->get("soit-transmis/{$id}");
    }

    public function createSoitTransmis(array $data): array
    {
        return $this->post('soit-transmis', $data);
    }

    public function updateSoitTransmis($id, array $data): array
    {
        return $this->put("soit-transmis/{$id}", $data);
    }

    public function deleteSoitTransmis($id): array
    {
        return $this->delete("soit-transmis/{$id}");
    }

    // ── Certificats d'hébergement ─────────────────────────────────────────────

    public function getCertificats(?array $filters = []): array
    {
        return $this->get('certificats-hebergement', $filters ?? []);
    }

    public function getCertificat($id): array
    {
        return $this->get("certificats-hebergement/{$id}");
    }

    public function createCertificat(array $data): array
    {
        return $this->post('certificats-hebergement', $data);
    }

    public function updateCertificat($id, array $data): array
    {
        return $this->put("certificats-hebergement/{$id}", $data);
    }

    public function deleteCertificat($id): array
    {
        return $this->delete("certificats-hebergement/{$id}");
    }

    public function validerCertificat($id): array
    {
        return $this->post("certificats-hebergement/{$id}/valider");
    }

    public function rejeterCertificat($id, string $motif): array
    {
        return $this->post("certificats-hebergement/{$id}/rejeter", ['motif_rejet' => $motif]);
    }

    // ── Statistiques ─────────────────────────────────────────────────────────

    public function getStatistiquesDemandesParJour(?array $params = []): array
    {
        return $this->get('statistiques/demandes-par-jour', $params ?? []);
    }

    public function getStatistiquesDemandesParType(?array $params = []): array
    {
        return $this->get('statistiques/demandes-par-type', $params ?? []);
    }

    public function getStatistiquesDemandesParStatut(?array $params = []): array
    {
        return $this->get('statistiques/demandes-par-statut', $params ?? []);
    }

    public function getStatistiquesDemandesParAgent(?array $params = []): array
    {
        return $this->get('statistiques/demandes-par-agent', $params ?? []);
    }

    public function getStatistiquesFluxParJour(?array $params = []): array
    {
        return $this->get('statistiques/flux-par-jour', $params ?? []);
    }

    public function getStatistiquesFluxParFrontiere(?array $params = []): array
    {
        return $this->get('statistiques/flux-par-frontiere', $params ?? []);
    }

    public function getStatistiquesFluxParNationalite(?array $params = []): array
    {
        return $this->get('statistiques/flux-par-nationalite', $params ?? []);
    }

    public function getStatistiquesComparaison(?array $params = []): array
    {
        return $this->get('statistiques/comparaison', $params ?? []);
    }

    // ── Monitor ───────────────────────────────────────────────────────────────

    public function getMonitor(?int $limit = null): array
    {
        return $this->get('monitor', $limit ? ['limit' => $limit] : []);
    }

    public function getMonitorFeed(): array
    {
        return $this->get('monitor/feed');
    }

    public function pingMonitor(): array
    {
        return $this->get('monitor/ping');
    }

    // ── Licence override via backend ────────────────────────────────────────

    public function checkLicenceFromBackend(): array
    {
        try {
            $sysToken = config('services.dmce_api.sys_token', env('SYS_TOKEN', ''));
            $response = $this->http->get('system/licence', [
                'headers' => ['X-Sys-Token' => $sysToken],
                'timeout' => 5,
            ]);
            return $this->decode($response->getBody());
        } catch (\Throwable $e) {
            return ['valid' => false, 'reason' => 'Backend inaccessible'];
        }
    }

    // ── Helpers privés ──────────────────────────────────────────────────────

    private function decode($body): array
    {
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', (string) $body);
        return json_decode($raw, true) ?? [];
    }

    private function get(string $uri, array $query = []): array
    {
        try {
            $options = ['headers' => $this->authHeaders()];
            if ($query) {
                $options['query'] = $query;
            }
            $response = $this->http->get($uri, $options);
            $this->checkLicenceHeader($response);
            return $this->decode($response->getBody());
        } catch (\Throwable $e) {
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
            $this->checkLicenceHeader($response);
            return $this->decode($response->getBody());
        } catch (\Throwable $e) {
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
            $this->checkLicenceHeader($response);
            return $this->decode($response->getBody());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    private function delete(string $uri): array
    {
        try {
            $response = $this->http->delete($uri, [
                'headers' => $this->authHeaders(),
            ]);
            $this->checkLicenceHeader($response);
            return $this->decode($response->getBody());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Lit X-Licence-Status sur chaque réponse backend.
     * Si expired → bloque immédiatement. Backend = autorité unique.
     */
    private function checkLicenceHeader($response): void
    {
        $status = $response->getHeaderLine('X-Licence-Status');
        if ($status === '') return; // header absent = backend ancien, ignorer

        if ($status === 'expired') {
            $days    = (int) $response->getHeaderLine('X-Licence-Days');
            $expires = $response->getHeaderLine('X-Licence-Expires');
            abort(redirect('/license/locked')
                ->with('reason', "Licence expirée depuis le {$expires} ({$days} jours)"));
        }

        // valid → stocker days restants en session pour affichage
        $days = (int) $response->getHeaderLine('X-Licence-Days');
        if ($days > 0 && $days < 7) {
            session()->flash('warning', "Votre licence expire dans {$days} jour(s)");
        }
    }

    private function handleError(\Throwable $e): array
    {
        $status  = ($e instanceof RequestException && $e->hasResponse()) ? $e->getResponse()->getStatusCode() : 0;
        $body    = ($e instanceof RequestException && $e->hasResponse()) ? (string) $e->getResponse()->getBody() : '';
        $decoded = json_decode($body, true);

        if ($status === 0) {
            abort(503, 'API_UNAVAILABLE');
        }

        Log::error("ApiClient error [{$status}]: " . $e->getMessage());

        return [
            'error'   => true,
            'status'  => $status,
            'message' => $decoded['message'] ?? $e->getMessage(),
        ];
    }
}
