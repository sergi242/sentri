<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\UserSession;
use App\Services\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitorController extends Controller
{
    private string $tz      = 'Africa/Brazzaville';
    private string $tzMysql = '+01:00'; // UTC+1

    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        return view('admin.monitor.index');
    }

    /**
     * Ping — vérifie que la session est encore valide et l'API backend
     */
    public function ping()
    {
        $apiResult = $this->api->pingMonitor();

        return response()->json(['ok' => empty($apiResult['error'])]);
    }

    /**
     * Feed principal — via API backend
     */
    public function feed()
    {
        $result = $this->api->getMonitorFeed();

        if (!empty($result['error'])) {
            // Fallback to local if API unavailable
            return $this->feedLocal();
        }

        return response()->json($result);
    }

    /**
     * Fallback local feed implementation
     */
    private function feedLocal()
    {
        $now   = Carbon::now($this->tz);
        $today = $now->toDateString();

        $logs = AuditLog::orderBy('created_at', 'desc')
            ->limit(300)
            ->get()
            ->map(fn($l) => [
                'id'         => $l->id,
                'user_id'    => $l->user_id,
                'user_name'  => $l->user_name ?? 'Invité',
                'user_role'  => $l->user_role ?? '-',
                'action'     => $l->action_label,
                'method'     => $l->method ?? 'GET',
                'module'     => $l->module_label,
                'module_raw' => $l->module,
                'status'     => $l->status ?? 'success',
                'ip'         => $l->ip_address,
                'url'        => $l->url,
                'entity_id'  => $l->entity_id,
                'time'       => Carbon::parse($l->created_at)->setTimezone($this->tz)->format('H:i:s'),
                'date'       => Carbon::parse($l->created_at)->setTimezone($this->tz)->format('d/m/Y'),
                'timestamp'  => Carbon::parse($l->created_at)->timestamp,
            ]);

        $cutoff15Utc = $now->copy()->subMinutes(15)->utc()->toDateTimeString();
        $cutoff5     = $now->copy()->subMinutes(5);

        $activeUsers = AuditLog::select('user_id', 'user_name', 'user_role', 'ip_address')
            ->selectRaw('MAX(created_at) as last_seen')
            ->selectRaw('COUNT(*) as action_count')
            ->whereRaw('created_at >= ?', [$cutoff15Utc])
            ->whereNotNull('user_id')
            ->groupBy('user_id', 'user_name', 'user_role', 'ip_address')
            ->orderByDesc('last_seen')
            ->get()
            ->map(function ($u) use ($cutoff5, $now) {
                $lastSeen = Carbon::parse($u->last_seen)->setTimezone($this->tz);
                $online   = $lastSeen->gte($cutoff5);

                $lastLog = AuditLog::where('user_id', $u->user_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $session = UserSession::where('user_id', $u->user_id)
                    ->where('status', 'active')
                    ->latest('login_at')
                    ->first();

                $loginTs         = null;
                $sessionDuration = '—';

                if ($session?->login_at) {
                    $loginAt         = Carbon::parse($session->login_at);
                    $loginTs         = $loginAt->timestamp;
                    $sec             = (int) $loginAt->diffInSeconds($now);
                    $sessionDuration = $this->fmt($sec);
                }

                return [
                    'user_id'          => $u->user_id,
                    'user_name'        => $u->user_name,
                    'user_role'        => $u->user_role ?? '-',
                    'ip'               => $u->ip_address,
                    'last_seen'        => $lastSeen->diffForHumans(),
                    'last_url'         => $lastLog?->url ?? '-',
                    'last_module'      => $lastLog?->module ?? '-',
                    'last_action'      => $lastLog?->action_label ?? '-',
                    'action_count'     => $u->action_count,
                    'online'           => $online,
                    'session_duration' => $sessionDuration,
                    'login_ts'         => $loginTs,
                    'login_at'         => $session?->login_at
                        ? Carbon::parse($session->login_at)->setTimezone($this->tz)->format('H:i:s')
                        : null,
                ];
            });

        $tz = $this->tzMysql;

        $totalToday    = AuditLog::whereRaw("DATE(CONVERT_TZ(created_at,'+00:00',?)) = ?", [$tz, $today])->count();
        $totalErrors   = AuditLog::whereRaw("DATE(CONVERT_TZ(created_at,'+00:00',?)) = ?", [$tz, $today])->where('status', '!=', 'success')->count();
        $totalActions  = AuditLog::whereRaw("DATE(CONVERT_TZ(created_at,'+00:00',?)) = ?", [$tz, $today])->where('method', '!=', 'GET')->count();
        $totalSessions = UserSession::whereRaw("DATE(CONVERT_TZ(login_at,'+00:00',?)) = ?", [$tz, $today])->count();

        $moduleStats = AuditLog::select('module', DB::raw('count(*) as total'))
            ->whereRaw("DATE(CONVERT_TZ(created_at,'+00:00',?)) = ?", [$tz, $today])
            ->groupBy('module')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn($m) => [
                'module' => $m->module ?? 'système',
                'total'  => $m->total,
            ]);

        $hourlyRaw = AuditLog::selectRaw(
                "HOUR(CONVERT_TZ(created_at,'+00:00',?)) as hour, count(*) as total",
                [$tz]
            )
            ->whereRaw("DATE(CONVERT_TZ(created_at,'+00:00',?)) = ?", [$tz, $today])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $hours = [];
        for ($i = 0; $i <= 23; $i++) {
            $hours[$i] = (int) ($hourlyRaw[$i]?->total ?? 0);
        }

        return response()->json([
            'logs'         => $logs,
            'active_users' => $activeUsers,
            'stats'        => [
                'total_today'    => $totalToday,
                'total_errors'   => $totalErrors,
                'total_users'    => $activeUsers->count(),
                'total_actions'  => $totalActions,
                'total_sessions' => $totalSessions,
                'last_id'        => $logs->isNotEmpty() ? $logs[0]['id'] : 0,
            ],
            'module_stats' => $moduleStats,
            'hourly'       => $hours,
            'server_time'  => $now->format('H:i:s'),
            'server_date'  => $now->format('d/m/Y'),
        ]);
    }

    /**
     * Activité détaillée d'un utilisateur
     */
    public function userActivity($userId, Request $request)
    {
        $period = $request->get('period', 'day');
        $now    = Carbon::now($this->tz);
        $tz     = $this->tzMysql;

        $from = match ($period) {
            'day'   => $now->copy()->startOfDay()->utc(),
            'week'  => $now->copy()->startOfWeek()->utc(),
            'month' => $now->copy()->startOfMonth()->utc(),
            'year'  => $now->copy()->startOfYear()->utc(),
            default => $now->copy()->startOfDay()->utc(),
        };

        $user = User::find($userId);

        $logs = AuditLog::where('user_id', $userId)
            ->where('created_at', '>=', $from)
            ->orderBy('created_at', 'desc')
            ->limit(500)
            ->get()
            ->map(fn($l) => [
                'id'         => $l->id,
                'action'     => $l->action_label,
                'method'     => $l->method ?? 'GET',
                'module'     => $l->module_label,
                'module_raw' => $l->module,
                'status'     => $l->status ?? 'success',
                'url'        => $l->url,
                'entity_id'  => $l->entity_id,
                'time'       => Carbon::parse($l->created_at)->setTimezone($this->tz)->format('H:i:s'),
                'date'       => Carbon::parse($l->created_at)->setTimezone($this->tz)->format('d/m/Y'),
                'ago'        => Carbon::parse($l->created_at)->diffForHumans(),
            ]);

        $stats = [
            'total'         => $logs->count(),
            'creations'     => $logs->where('method', 'POST')->count(),
            'modifications' => $logs->whereIn('method', ['PUT', 'PATCH'])->count(),
            'suppressions'  => $logs->where('method', 'DELETE')->count(),
            'consultations' => $logs->where('method', 'GET')->count(),
            'errors'        => $logs->whereIn('status', ['failed', 'forbidden'])->count(),
        ];

        $moduleBreakdown = $logs
            ->groupBy('module_raw')
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->take(6)
            ->toArray();

        $sessions = UserSession::where('user_id', $userId)
            ->where('login_at', '>=', $from)
            ->orderBy('login_at', 'desc')
            ->get()
            ->map(fn($s) => [
                'login_at'  => Carbon::parse($s->login_at)->setTimezone($this->tz)->format('d/m/Y H:i:s'),
                'logout_at' => $s->logout_at
                    ? Carbon::parse($s->logout_at)->setTimezone($this->tz)->format('d/m/Y H:i:s')
                    : 'En cours',
                'duration'  => $s->duration_seconds
                    ? $this->fmt((int) $s->duration_seconds)
                    : ($s->status === 'active' ? 'En cours' : '—'),
                'status'    => $s->status,
                'ip'        => $s->ip_address,
            ]);

        $totalPresenceSec = (int) UserSession::where('user_id', $userId)
            ->where('login_at', '>=', $from)
            ->whereNotNull('duration_seconds')
            ->sum('duration_seconds');

        $activeSession = UserSession::where('user_id', $userId)
            ->where('status', 'active')
            ->latest('login_at')
            ->first();

        $activeLoginTs = null;
        if ($activeSession?->login_at) {
            $activeLoginTs     = Carbon::parse($activeSession->login_at)->timestamp;
            $totalPresenceSec += (int) Carbon::parse($activeSession->login_at)->diffInSeconds($now);
        }

        $dailyActivity = AuditLog::where('user_id', $userId)
            ->where('created_at', '>=', $from)
            ->selectRaw("DATE(CONVERT_TZ(created_at,'+00:00',?)) as day, count(*) as total", [$tz])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->mapWithKeys(fn($r) => [$r->day => (int) $r->total])
            ->toArray();

        return response()->json([
            'user' => [
                'id'    => $user?->id,
                'name'  => $user ? $user->prenom . ' ' . $user->nom : 'Utilisateur #' . $userId,
                'role'  => $user?->role?->lib_role ?? '—',
                'email' => $user?->email,
            ],
            'period'                 => $period,
            'logs'                   => $logs,
            'stats'                  => $stats,
            'module_breakdown'       => $moduleBreakdown,
            'sessions'               => $sessions,
            'total_presence'         => $this->fmt($totalPresenceSec),
            'total_presence_seconds' => $totalPresenceSec,
            'active_login_ts'        => $activeLoginTs,
            'daily_activity'         => $dailyActivity,
        ]);
    }

    private function fmt(int $seconds): string
    {
        $seconds = (int) $seconds;
        if ($seconds <= 0) return '0s';
        if ($seconds < 60) return $seconds . 's';
        if ($seconds < 3600) return intdiv($seconds, 60) . 'min ' . ($seconds % 60) . 's';
        $h = intdiv($seconds, 3600);
        $m = intdiv(($seconds % 3600), 60);
        return $h . 'h ' . $m . 'min';
    }
}
