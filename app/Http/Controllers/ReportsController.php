<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Enrollment;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        // ── Summary stats ──────────────────────────────────────────
        $totalRevenue     = Registration::where('status', 'approved')->sum('total_amount');
        $approvedCount    = Registration::where('status', 'approved')->count();
        $pendingCount     = Registration::where('status', 'pending')->count();
        $totalStudents    = Enrollment::distinct('user_id')->count('user_id');

        // Pending value (slips uploaded but not yet approved)
        $pendingRevenue   = Registration::where('status', 'pending')
            ->whereHas('slips')->sum('total_amount');

        // ── Revenue by month (last 8 months) ──────────────────────
        $revenueByMonth = Registration::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->where('approved_at', '>=', now()->subMonths(8)->startOfMonth())
            ->selectRaw("DATE_FORMAT(approved_at, '%b %Y') as month,
                         DATE_FORMAT(approved_at, '%Y-%m') as sort_key,
                         SUM(total_amount) as total")
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();

        // ── Revenue per course ─────────────────────────────────────
        $revenueByModule = DB::table('enrollments')
            ->join('modules', 'enrollments.module_id', '=', 'modules.id')
            ->select(
                'modules.id',
                'modules.title',
                'modules.price',
                DB::raw('COUNT(enrollments.user_id) as enrolled_count'),
                DB::raw('COALESCE(modules.price,0) * COUNT(enrollments.user_id) as module_revenue')
            )
            ->groupBy('modules.id', 'modules.title', 'modules.price')
            ->orderByDesc('module_revenue')
            ->get();

        // ── Recent approved registrations (ledger) ─────────────────
        $recentPayments = Registration::where('status', 'approved')
            ->with('slips')
            ->orderByDesc('approved_at')
            ->take(10)
            ->get();

        return view('layouts.reports.financialRep', compact(
            'totalRevenue', 'approvedCount', 'pendingCount',
            'totalStudents', 'pendingRevenue',
            'revenueByMonth', 'revenueByModule', 'recentPayments'
        ));
    }

    public function systemLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $entries = [];
        $stats   = ['ERROR' => 0, 'WARNING' => 0, 'INFO' => 0, 'DEBUG' => 0, 'OTHER' => 0];

        if (file_exists($logFile) && filesize($logFile) > 0) {
            $size   = filesize($logFile);
            $handle = fopen($logFile, 'r');
            fseek($handle, max(0, $size - 204800)); // last 200 KB
            $content = fread($handle, $size);
            fclose($handle);

            $pattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.*)/m';
            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

            foreach ($matches as $i => $m) {
                $level   = strtoupper($m[3][0]);
                $message = trim($m[4][0]);

                // Grab everything between this match and the next as trace
                $traceStart = $m[0][1] + strlen($m[0][0]);
                $traceEnd   = isset($matches[$i + 1]) ? $matches[$i + 1][0][1] : strlen($content);
                $trace      = trim(substr($content, $traceStart, $traceEnd - $traceStart));

                // Strip giant JSON exception blobs from the message line
                $cleanMsg = preg_replace('/\s*\{"exception":".*$/s', '', $message);
                $cleanMsg = preg_replace('/\s*\{.*\}\s*\{.*\}\s*$/', '', $cleanMsg);

                $key = in_array($level, ['ERROR', 'CRITICAL', 'EMERGENCY', 'ALERT']) ? 'ERROR'
                     : (in_array($level, ['WARNING', 'NOTICE']) ? 'WARNING'
                     : (in_array($level, ['INFO']) ? 'INFO'
                     : (in_array($level, ['DEBUG']) ? 'DEBUG' : 'OTHER')));

                $stats[$key]++;

                $entries[] = [
                    'date'     => $m[1][0],
                    'time'     => $m[2][0],
                    'level'    => $level,
                    'group'    => $key,
                    'message'  => $cleanMsg ?: $message,
                    'trace'    => $trace,
                ];
            }

            // Newest first, cap at 300
            $entries = array_reverse(array_slice($entries, -300));
        }

        return view('layouts.reports.systemlogs', compact('entries', 'stats'));
    }

    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        return redirect()->route('systemlogs.view')->with('success', 'Log file cleared.');
    }
}
